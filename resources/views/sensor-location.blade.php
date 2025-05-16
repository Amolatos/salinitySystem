<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Sensor Location - Salinity Monitoring System</title>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <!-- Custom Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .map-container {
                height: 600px;
                width: 100%;
                border-radius: 0.5rem;
                overflow: hidden;
            }
            @media (max-width: 640px) {
                .map-container {
                    height: 400px;
                }
            }
        </style>
    </head>
    <body class="bg-gray-100 min-h-screen">
        <div id="app" class="min-h-screen flex flex-col">
            <nav class="bg-white shadow-lg sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <a href="/" class="text-xl md:text-2xl font-bold text-green-600 truncate">
                                    <span class="hidden sm:inline">Salinity Monitoring System</span>
                                    <span class="sm:hidden">Salinity Monitor</span>
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="/" class="text-gray-600 hover:text-green-600">Dashboard</a>
                            <a href="/sensor-location" class="text-green-600">Sensor Location</a>
                            <a href="/mangrove-species" class="text-gray-600 hover:text-green-600">Mangrove Species</a>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow max-w-7xl w-full mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <!-- Map Section -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Sensor Location</h2>
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Search location..." 
                                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>
                    </div>
                    <div class="relative">
                        <map-component 
                            :latitude="10.3157" 
                            :longitude="123.8854" 
                            :is-active="true">
                        </map-component>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Current Location Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Latitude</p>
                                <p class="text-lg font-medium">10.3157° N</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Longitude</p>
                                <p class="text-lg font-medium">123.8854° E</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow-lg mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        © {{ date('Y') }} Salinity Monitoring System. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>

        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </body>
</html> 