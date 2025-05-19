#include <ESP8266WiFi.h>

// Sensor pins
const int salinityPin = A0;
const int temperaturePin = A1;
const int phPin = A2;

// LED pins
const int statusLed = D4;
const int errorLed = D5;
const int successLed = D6;

void setup() {
  Serial.begin(115200);
  
  pinMode(statusLed, OUTPUT);
  pinMode(errorLed, OUTPUT);
  pinMode(successLed, OUTPUT);
  
  // Turn on status LED to show we're ready
  digitalWrite(statusLed, HIGH);
  
  Serial.println("\nSensor Calibration Test");
  Serial.println("----------------------");
}

void loop() {
  // Read raw values
  int rawSalinity = analogRead(salinityPin);
  int rawTemp = analogRead(temperaturePin);
  int rawPH = analogRead(phPin);
  
  // Convert to voltages
  float salinityVoltage = (rawSalinity / 1024.0) * 3.3;
  float tempVoltage = (rawTemp / 1024.0) * 3.3;
  float phVoltage = (rawPH / 1024.0) * 3.3;
  
  // Print readings
  Serial.println("\n--- Raw Readings ---");
  Serial.println("Salinity Sensor:");
  Serial.printf("  Raw: %d, Voltage: %.3fV\n", rawSalinity, salinityVoltage);
  
  Serial.println("Temperature Sensor:");
  Serial.printf("  Raw: %d, Voltage: %.3fV\n", rawTemp, tempVoltage);
  
  Serial.println("pH Sensor:");
  Serial.printf("  Raw: %d, Voltage: %.3fV\n", rawPH, phVoltage);
  
  // Visual indicator
  digitalWrite(successLed, HIGH);
  delay(100);
  digitalWrite(successLed, LOW);
  
  delay(2000); // Read every 2 seconds
} 