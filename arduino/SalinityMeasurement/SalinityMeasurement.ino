#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <TinyGPS++.h>
#include <SoftwareSerial.h>

// WiFi credentials
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// API configuration
const char* apiKey = "YOUR_API_KEY";
const char* serverUrl = "http://your-server.com/api/mangroves/1/measurements";
const char* locationUrl = "http://your-server.com/api/sensors/location";
const char* sensorId = "SENSOR_01";

// GPS Module pins (adjust according to your wiring)
static const int RXPin = D7, TXPin = D8;
static const uint32_t GPSBaud = 9600;

// Sensor pins
const int salinityPin = A0;
const int temperaturePin = A1;
const int phPin = A2;

// LED pins
const int statusLed = D4;
const int errorLed = D5;
const int successLed = D6;

// The TinyGPS++ object
TinyGPSPlus gps;

// The serial connection to the GPS device
SoftwareSerial ss(RXPin, TXPin);

void setup() {
    Serial.begin(115200);
    ss.begin(GPSBaud);
    
    pinMode(statusLed, OUTPUT);
    pinMode(errorLed, OUTPUT);
    pinMode(successLed, OUTPUT);
    
    // Connect to WiFi
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        digitalWrite(statusLed, !digitalRead(statusLed));
        delay(500);
        Serial.print(".");
    }
    digitalWrite(statusLed, HIGH);
    Serial.println("\nConnected to WiFi");
}

void loop() {
    digitalWrite(successLed, LOW);
    digitalWrite(errorLed, LOW);
    
    // Get GPS data
    float latitude = 0, longitude = 0;
    bool validLocation = false;
    
    while (ss.available() > 0) {
        if (gps.encode(ss.read())) {
            if (gps.location.isValid()) {
                latitude = gps.location.lat();
                longitude = gps.location.lng();
                validLocation = true;
                break;
            }
        }
    }
    
    // Read sensor values
    float rawSalinity = readRawSalinity();
    float rawTemperature = readRawTemperature();
    float rawPH = readRawPH();
    
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        WiFiClient client;
        
        // First, get calibrated values
        StaticJsonDocument<200> calibrationDoc;
        calibrationDoc["sensor_id"] = sensorId;
        calibrationDoc["raw_salinity"] = rawSalinity;
        calibrationDoc["raw_temperature"] = rawTemperature;
        calibrationDoc["raw_ph"] = rawPH;
        
        String calibrationJson;
        serializeJson(calibrationDoc, calibrationJson);
        
        // Get calibrated values from server
        http.begin(client, serverUrl);
        http.addHeader("Content-Type", "application/json");
        http.addHeader("X-API-KEY", apiKey);
        
        int calibrationResponse = http.POST(calibrationJson);
        
        float calibratedSalinity = rawSalinity;
        float calibratedTemperature = rawTemperature;
        float calibratedPH = rawPH;
        
        if (calibrationResponse > 0) {
            String response = http.getString();
            StaticJsonDocument<300> responseDoc;
            deserializeJson(responseDoc, response);
            
            calibratedSalinity = responseDoc["calibrated_values"]["salinity"];
            calibratedTemperature = responseDoc["calibrated_values"]["temperature"];
            calibratedPH = responseDoc["calibrated_values"]["ph"];
        }
        
        http.end();
        
        // Update location if valid GPS data
        if (validLocation) {
            StaticJsonDocument<200> locationDoc;
            locationDoc["sensor_id"] = sensorId;
            locationDoc["latitude"] = latitude;
            locationDoc["longitude"] = longitude;
            
            String locationJson;
            serializeJson(locationDoc, locationJson);
            
            http.begin(client, locationUrl);
            http.addHeader("Content-Type", "application/json");
            http.addHeader("X-API-KEY", apiKey);
            http.POST(locationJson);
            http.end();
        }
        
        // Send measurement data
        StaticJsonDocument<300> measurementDoc;
        measurementDoc["salinity_value"] = calibratedSalinity;
        measurementDoc["temperature"] = calibratedTemperature;
        measurementDoc["ph_level"] = calibratedPH;
        measurementDoc["location"] = "Sensor Station 1";
        measurementDoc["api_key"] = apiKey;
        if (validLocation) {
            measurementDoc["latitude"] = latitude;
            measurementDoc["longitude"] = longitude;
        }
        
        String measurementJson;
        serializeJson(measurementDoc, measurementJson);
        
        http.begin(client, serverUrl);
        http.addHeader("Content-Type", "application/json");
        http.addHeader("X-API-KEY", apiKey);
        
        int measurementResponse = http.POST(measurementJson);
        
        if (measurementResponse > 0) {
            digitalWrite(successLed, HIGH);
            Serial.println("Data sent successfully");
            Serial.println("Location: " + String(latitude, 6) + ", " + String(longitude, 6));
            Serial.println("Salinity: " + String(calibratedSalinity) + " PPT");
            Serial.println("Temperature: " + String(calibratedTemperature) + "°C");
            Serial.println("pH: " + String(calibratedPH));
        } else {
            digitalWrite(errorLed, HIGH);
            Serial.println("Error sending data");
        }
        
        http.end();
    } else {
        digitalWrite(errorLed, HIGH);
        Serial.println("WiFi disconnected");
    }
    
    delay(300000); // 5 minutes
}

float readRawSalinity() {
    int rawValue = analogRead(salinityPin);
    return (rawValue / 1024.0) * 3.3;
}

float readRawTemperature() {
    int rawValue = analogRead(temperaturePin);
    return (rawValue / 1024.0) * 3.3;
}

float readRawPH() {
    int rawValue = analogRead(phPin);
    return (rawValue / 1024.0) * 3.3;
} 