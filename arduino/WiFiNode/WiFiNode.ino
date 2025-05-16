/*
 * Salinity Monitoring System - WiFi Node (ESP8266)
 * Hardware: ESP8266, TTL to RS485
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SoftwareSerial.h>
#include <ArduinoJson.h>

// WiFi settings
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// Server settings
const char* serverUrl = "http://your-domain.com/api/readings";

// Pin definitions for RS485
#define RS485_RX D2     // RS485 RX pin (GPIO4)
#define RS485_TX D3     // RS485 TX pin (GPIO0)
#define RS485_DE D4     // RS485 DE/RE pin (GPIO2)

// Create software serial for RS485
SoftwareSerial rs485Serial(RS485_RX, RS485_TX);

// Buffer for incoming data
char dataBuffer[100];
int bufferIndex = 0;

void setup() {
  // Initialize serial communications
  Serial.begin(115200);     // Debug serial
  rs485Serial.begin(9600);  // RS485 serial

  // Configure RS485 control pin
  pinMode(RS485_DE, OUTPUT);
  digitalWrite(RS485_DE, LOW);  // Set to receive mode

  // Connect to WiFi
  WiFi.begin(ssid, password);
  Serial.print("\nConnecting to WiFi");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nWiFi connected!");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  // Read data from RS485
  if (readRS485Data()) {
    // Parse the CSV data
    float ec, lat, lng, temp;
    if (parseData(dataBuffer, &ec, &lat, &lng, &temp)) {
      // Send to server
      sendToServer(ec, lat, lng, temp);
    }
    // Reset buffer
    bufferIndex = 0;
    memset(dataBuffer, 0, sizeof(dataBuffer));
  }
}

bool readRS485Data() {
  while (rs485Serial.available()) {
    char c = rs485Serial.read();
    
    if (c == '\n') {
      dataBuffer[bufferIndex] = '\0';
      return true;
    } else if (bufferIndex < sizeof(dataBuffer) - 1) {
      dataBuffer[bufferIndex++] = c;
    }
  }
  return false;
}

bool parseData(char* data, float* ec, float* lat, float* lng, float* temp) {
  char* ptr = strtok(data, ",");
  if (ptr == NULL) return false;
  *ec = atof(ptr);
  
  ptr = strtok(NULL, ",");
  if (ptr == NULL) return false;
  *lat = atof(ptr);
  
  ptr = strtok(NULL, ",");
  if (ptr == NULL) return false;
  *lng = atof(ptr);
  
  ptr = strtok(NULL, ",");
  if (ptr == NULL) return false;
  *temp = atof(ptr);
  
  return true;
}

void sendToServer(float ec, float lat, float lng, float temp) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    
    // Create JSON payload
    StaticJsonDocument<200> doc;
    doc["ec"] = ec;
    doc["latitude"] = lat;
    doc["longitude"] = lng;
    doc["temperature"] = temp;
    
    String jsonString;
    serializeJson(doc, jsonString);
    
    // Send POST request
    int httpResponseCode = http.POST(jsonString);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("HTTP Response: " + String(httpResponseCode));
      Serial.println(response);
    } else {
      Serial.println("Error sending HTTP POST: " + String(httpResponseCode));
    }
    
    http.end();
  }
}

void printDebugInfo(float ec, float lat, float lng, float temp) {
  Serial.println("\n--- Received Data ---");
  Serial.print("EC Value: ");
  Serial.print(ec);
  Serial.println(" mS/cm");
  Serial.print("Location: ");
  Serial.print(lat, 6);
  Serial.print(", ");
  Serial.println(lng, 6);
  Serial.print("Temperature: ");
  Serial.print(temp);
  Serial.println("°C");
} 