# Real-time Salinity Monitoring System

This project implements a real-time salinity monitoring system using ESP8266, NEO-6M GPS module, EC sensor, Arduino Uno, and TTL to RS485 converter. The system collects salinity (EC) readings along with GPS coordinates and sends the data to a Laravel backend for storage and visualization.

## Hardware Requirements

- ESP8266 WiFi Module
- NEO-6M GPS Module
- EC (Electrical Conductivity) Sensor
- Arduino Uno
- TTL to RS485 Converter
- Power supply for the components

## Hardware Setup

1. **Arduino Uno Connections:**
   - EC Sensor -> A0
   - GPS Module:
     - RX -> Pin 4
     - TX -> Pin 5
   - RS485 Module:
     - RX -> Pin 2
     - TX -> Pin 3
     - DE -> Pin 6

2. **ESP8266 Setup:**
   - Connect to your WiFi network by updating the SSID and password in the Arduino sketch
   - Update the server URL in the Arduino sketch to point to your Laravel backend

## Software Requirements

- PHP 8.1 or higher
- Composer
- Node.js and npm
- Arduino IDE with required libraries:
  - ESP8266WiFi
  - ESP8266HTTPClient
  - ArduinoJson
  - TinyGPS++
  - SoftwareSerial

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd salinitySystem
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Set up the environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure the database in .env:**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=salinity_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets:**
   ```bash
   npm run build
   ```

8. **Upload Arduino code:**
   - Open `arduino/SalinityMonitor/SalinityMonitor.ino` in Arduino IDE
   - Update WiFi credentials and server URL
   - Upload to your Arduino

## Running the Application

1. **Start the Laravel development server:**
   ```bash
   php artisan serve
   ```

2. **Start the Vite development server:**
   ```bash
   npm run dev
   ```

3. **Access the application:**
   - Open your browser and navigate to `http://localhost:8000`
   - The dashboard will show real-time salinity readings, GPS location on a map, and historical data chart

## Features

- Real-time EC (salinity) monitoring
- GPS location tracking
- Temperature compensation for EC readings
- Interactive map showing sensor location
- Historical data visualization
- REST API endpoints for data access
- Real-time updates using polling (every 5 seconds)

## API Endpoints

- `POST /api/readings` - Store new reading
- `GET /api/readings` - Get last 100 readings
- `GET /api/readings/latest` - Get most recent reading

## Troubleshooting

1. **No readings appearing:**
   - Check WiFi connection on ESP8266
   - Verify server URL in Arduino sketch
   - Check serial monitor for debugging information

2. **Incorrect EC readings:**
   - Calibrate the EC sensor using known solution
   - Adjust the conversion factor in the Arduino code
   - Check temperature compensation

3. **GPS not working:**
   - Ensure clear view of the sky
   - Check GPS module connections
   - Verify GPS data in serial monitor

## Contributing

Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
