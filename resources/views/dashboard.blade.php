<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard - Salinity Monitoring System</title>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Custom Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .fade-enter-active, .fade-leave-active {
                transition: opacity 0.3s;
            }
            .fade-enter-from, .fade-leave-to {
                opacity: 0;
            }
            .loading-overlay {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(4px);
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
                            <a href="/" class="text-green-600">Dashboard</a>
                            <a href="/sensor-location" class="text-gray-600 hover:text-green-600">Sensor Location</a>
                            <a href="/mangrove-species" class="text-gray-600 hover:text-green-600">Mangrove Species</a>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow max-w-7xl w-full mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <!-- Current Readings Section -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Real-time Salinity Monitoring</h2>
                            <p class="mt-1 text-sm text-gray-500">Live readings from the sensor network</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                <span class="animate-pulse inline-block h-2 w-2 mr-2 rounded-full bg-green-600"></span>
                                Live
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- EC Value Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-blue-900">EC Value</h3>
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex items-baseline">
                                <p class="text-2xl font-bold text-blue-900">0</p>
                                <p class="ml-2 text-blue-800">mS/cm</p>
                            </div>
                            <div class="mt-2">
                                <div class="h-2 bg-blue-200 rounded-full">
                                    <div class="h-2 bg-blue-500 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Temperature Card -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-orange-900">Temperature</h3>
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex items-baseline">
                                <p class="text-2xl font-bold text-orange-900">0</p>
                                <p class="ml-2 text-orange-800">°C</p>
                            </div>
                            <div class="mt-2">
                                <div class="h-2 bg-orange-200 rounded-full">
                                    <div class="h-2 bg-orange-500 rounded-full" style="width: 30%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-green-900">Location</h3>
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex items-baseline">
                                <p class="text-lg font-bold text-green-900">0, 0</p>
                            </div>
                            <p class="mt-1 text-sm text-green-800">Coordinates</p>
                        </div>

                        <!-- Last Updated Card -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-purple-900">Last Updated</h3>
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex items-baseline">
                                <p class="text-lg font-bold text-purple-900">-</p>
                            </div>
                            <p class="mt-1 text-sm text-purple-800">Auto-updates every minute</p>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="mt-6">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Salinity Trends</h3>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-full hover:bg-gray-50">24h</button>
                                    <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-full hover:bg-gray-50">7d</button>
                                    <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-full hover:bg-gray-50">30d</button>
                                </div>
                            </div>
                            <div class="h-64">
                                <canvas id="salinityChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Average EC</h4>
                                    <p class="text-lg font-semibold text-gray-900">0.45 mS/cm</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-3 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Readings Today</h4>
                                    <p class="text-lg font-semibold text-gray-900">24</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-3 bg-orange-100 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Last Reading</h4>
                                    <p class="text-lg font-semibold text-gray-900">2 mins ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <a href="/sensor-location" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-semibold text-green-600 mb-2">Sensor Location</h3>
                        <p class="text-gray-600">View and manage sensor locations on the interactive map</p>
                    </a>
                    <a href="/mangrove-species" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-semibold text-green-600 mb-2">Mangrove Species</h3>
                        <p class="text-gray-600">Explore different mangrove species and their characteristics</p>
                    </a>
                </div>

                <!-- History Section -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-semibold mb-4">History</h2>
                    <div class="relative">
                        <test-history></test-history>
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
    </body>
</html> 