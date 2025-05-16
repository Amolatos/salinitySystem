/*
 * Salinity Monitoring System
 * Hardware: ESP8266, NEO-6M GPS, EC Sensor, Arduino Uno, TTL to RS485
 */

#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <TinyGPS++.h>

// WiFi credentials
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// Server details
const char* serverUrl = "http://your-domain.com/api/readings";

// Pin definitions
#define EC_PIN A0           // EC Sensor analog pin
#define GPS_RX 4           // GPS RX pin
#define GPS_TX 5           // GPS TX pin
#define RS485_RX 2         // RS485 RX pin
#define RS485_TX 3         // RS485 TX pin
#define RS485_DE 6         // RS485 DE pin

// Objects
SoftwareSerial gpsSerial(GPS_RX, GPS_TX);
SoftwareSerial rs485Serial(RS485_RX, RS485_TX);
TinyGPSPlus gps;

// Variables
float latitude = 0.0;
float longitude = 0.0;
float ecValue = 0.0;
float temperature = 25.0; // Default temperature for EC compensation

void setup() {
  Serial.begin(115200);
  gpsSerial.begin(9600);
  rs485Serial.begin(9600);
  
  pinMode(RS485_DE, OUTPUT);
  digitalWrite(RS485_DE, LOW); // Set to receive mode
  
  // Connect to WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected");
}

void loop() {
  // Read GPS data
  while (gpsSerial.available() > 0) {
    if (gps.encode(gpsSerial.read())) {
      if (gps.location.isValid()) {
        latitude = gps.location.lat();
        longitude = gps.location.lng();
      }
    }
  }

  // Read EC sensor
  ecValue = readEC();

  // Send data via RS485
  sendDataRS485();

  // Send data to server
  sendDataToServer();

  delay(5000); // Wait 5 seconds before next reading
}

float readEC() {
  // Read raw EC value
  int rawEC = analogRead(EC_PIN);
  
  // Convert to EC value (mS/cm)
  // Note: This conversion needs to be calibrated according to your specific EC sensor
  float voltage = rawEC * (5.0 / 1024.0);
  float ecValue = voltage * 1.0; // Replace with your calibration factor
  
  // Temperature compensation
  float temperatureCoefficient = 1.0 + 0.0185 * (temperature - 25.0);
  ecValue = ecValue / temperatureCoefficient;
  
  return ecValue;
}

void sendDataRS485() {
  digitalWrite(RS485_DE, HIGH); // Set to transmit mode
  delay(10);
  
  // Create JSON data
  StaticJsonDocument<200> doc;
  doc["ec"] = ecValue;
  doc["lat"] = latitude;
  doc["lng"] = longitude;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  rs485Serial.println(jsonString);
  rs485Serial.flush();
  
  digitalWrite(RS485_DE, LOW); // Set back to receive mode
}

void sendDataToServer() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    
    // Create JSON payload
    StaticJsonDocument<200> doc;
    doc["ec"] = ecValue;
    doc["latitude"] = latitude;
    doc["longitude"] = longitude;
    doc["temperature"] = temperature;
    
    String jsonString;
    serializeJson(doc, jsonString);
    
    int httpResponseCode = http.POST(jsonString);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("HTTP Response: " + response);
    } else {
      Serial.println("Error sending HTTP POST: " + String(httpResponseCode));
    }
    
    http.end();
  }
} 