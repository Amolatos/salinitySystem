/*
 * Salinity Monitoring System - Sensor Node (Arduino Uno)
 * Hardware: Arduino Uno, NEO-6M GPS, EC Sensor, TTL to RS485
 */

#include <SoftwareSerial.h>
#include <TinyGPS++.h>

// Pin definitions
#define EC_SENSOR_PIN A0    // EC Sensor analog pin
#define EC_POWER_PIN 12     // EC Sensor power control pin
#define GPS_RX_PIN 4        // GPS RX pin
#define GPS_TX_PIN 5        // GPS TX pin
#define RS485_RX_PIN 2      // RS485 RX pin
#define RS485_TX_PIN 3      // RS485 TX pin
#define RS485_DE_PIN 6      // RS485 DE/RE pin

// Constants for EC Sensor
#define EC_SENSOR_VCC 5.0   // VCC voltage
#define EC_SENSOR_OFFSET 0.0 // Calibration offset
#define EC_SENSOR_K 1.0     // Cell constant K

// Objects
SoftwareSerial gpsSerial(GPS_RX_PIN, GPS_TX_PIN);
SoftwareSerial rs485Serial(RS485_RX_PIN, RS485_TX_PIN);
TinyGPSPlus gps;

// Variables
float temperature = 25.0;  // Default temperature for EC compensation
float ecValue = 0.0;
float latitude = 0.0;
float longitude = 0.0;
unsigned long lastReadingTime = 0;
const unsigned long READ_INTERVAL = 5000; // Read every 5 seconds

void setup() {
  // Initialize serial communications
  Serial.begin(115200);     // Debug serial
  gpsSerial.begin(9600);    // GPS serial
  rs485Serial.begin(9600);  // RS485 serial

  // Configure pins
  pinMode(EC_POWER_PIN, OUTPUT);
  pinMode(RS485_DE_PIN, OUTPUT);
  digitalWrite(RS485_DE_PIN, LOW);  // Set RS485 to receive mode
  digitalWrite(EC_POWER_PIN, LOW);  // Turn off EC sensor initially

  Serial.println(F("Salinity Monitoring System - Sensor Node"));
  Serial.println(F("Initializing..."));
}

void loop() {
  unsigned long currentMillis = millis();

  // Read GPS data
  while (gpsSerial.available() > 0) {
    if (gps.encode(gpsSerial.read())) {
      if (gps.location.isValid()) {
        latitude = gps.location.lat();
        longitude = gps.location.lng();
      }
    }
  }

  // Take readings every READ_INTERVAL milliseconds
  if (currentMillis - lastReadingTime >= READ_INTERVAL) {
    lastReadingTime = currentMillis;
    
    // Read EC sensor
    ecValue = readEC();

    // Send data via RS485
    sendDataRS485();

    // Debug output
    printDebugInfo();
  }
}

float readEC() {
  // Power up the EC sensor
  digitalWrite(EC_POWER_PIN, HIGH);
  delay(100); // Wait for sensor to stabilize

  // Take multiple readings and average
  float rawEC = 0;
  for(int i = 0; i < 10; i++) {
    rawEC += analogRead(EC_SENSOR_PIN);
    delay(10);
  }
  rawEC /= 10;

  // Power down the EC sensor
  digitalWrite(EC_POWER_PIN, LOW);

  // Convert analog reading to voltage
  float voltage = rawEC * (EC_SENSOR_VCC / 1024.0);
  
  // Convert voltage to EC value (mS/cm)
  float ecValue = (voltage * EC_SENSOR_K) + EC_SENSOR_OFFSET;
  
  // Temperature compensation
  float temperatureCoefficient = 1.0 + 0.0185 * (temperature - 25.0);
  ecValue = ecValue / temperatureCoefficient;
  
  return ecValue;
}

void sendDataRS485() {
  // Prepare data string (CSV format: ec,lat,lng,temp)
  String dataString = String(ecValue, 2) + "," +
                     String(latitude, 6) + "," +
                     String(longitude, 6) + "," +
                     String(temperature, 1);

  // Switch to transmit mode
  digitalWrite(RS485_DE_PIN, HIGH);
  delay(10);

  // Send data
  rs485Serial.println(dataString);
  rs485Serial.flush();

  // Switch back to receive mode
  digitalWrite(RS485_DE_PIN, LOW);
}

void printDebugInfo() {
  Serial.println(F("\n--- Sensor Readings ---"));
  Serial.print(F("EC Value: "));
  Serial.print(ecValue);
  Serial.println(F(" mS/cm"));
  Serial.print(F("Temperature: "));
  Serial.print(temperature);
  Serial.println(F("°C"));
  Serial.print(F("GPS Location: "));
  Serial.print(latitude, 6);
  Serial.print(F(", "));
  Serial.println(longitude, 6);
} 