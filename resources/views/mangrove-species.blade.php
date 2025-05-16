<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Mangrove Species - Salinity Monitoring System</title>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Custom Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .species-card {
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .species-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }
            .species-image {
                height: 300px;
                width: 100%;
                object-fit: cover;
                transition: transform 0.3s;
            }
            .species-card:hover .species-image {
                transform: scale(1.05);
            }
            .image-container {
                overflow: hidden;
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
                            <a href="/sensor-location" class="text-gray-600 hover:text-green-600">Sensor Location</a>
                            <a href="/mangrove-species" class="text-green-600">Mangrove Species</a>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow max-w-7xl w-full mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Common Mangrove Species</h2>
                            <p class="mt-1 text-sm text-gray-500">Explore different mangrove species and their characteristics</p>
                        </div>
                        <div class="relative w-full sm:w-auto">
                            <input type="text" 
                                   placeholder="Search species..." 
                                   class="w-full sm:w-64 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                        <!-- Avicennia marina -->
                        <div class="species-card bg-white rounded-lg shadow overflow-hidden">
                            <div class="image-container">
                                <img src="https://imgs.search.brave.com/L4nKUONgZwZqZd5p5E5cBMGoTVXR-tgbh-U3G3FnGSo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pMC53/cC5jb20vZm9yZXN0/cnlwZWRpYS5jb20v/d3AtY29udGVudC91/cGxvYWRzLzIwMjIv/MDYvQXZpY2Vubmlh/LW1hcmluYS1Gb3Jz/c2suLVZpZXJoLjQu/anBnP3Jlc2l6ZT01/MTAsNDQ1JnNzbD0x" 
                                     alt="Avicennia marina" 
                                     class="species-image">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-green-600">Avicennia marina</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">High Tolerance</span>
                                </div>
                                <p class="text-gray-600 mt-1">Grey Mangrove</p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                        <span class="font-medium">Height:</span>
                                        <span class="ml-2">3-10 meters</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-medium">Salinity Tolerance:</span>
                                        <span class="ml-2">High</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rhizophora mucronata -->
                        <div class="species-card bg-white rounded-lg shadow overflow-hidden">
                            <div class="image-container">
                                <img src="https://imgs.search.brave.com/xNK0Rqc9H-4Ir5sZ3hJv2hDK-UYKn9fOW_U-rpfNpdc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy9l/L2VhL1JoaXpvcGhv/cmFfbXVjcm9uYXRh/X1Byb3BhZ3VsZXMu/anBn" 
                                     alt="Rhizophora mucronata" 
                                     class="species-image">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-green-600">Rhizophora mucronata</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Medium-High</span>
                                </div>
                                <p class="text-gray-600 mt-1">Red Mangrove</p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                        <span class="font-medium">Height:</span>
                                        <span class="ml-2">20-25 meters</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-medium">Salinity Tolerance:</span>
                                        <span class="ml-2">Medium to High</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sonneratia alba -->
                        <div class="species-card bg-white rounded-lg shadow overflow-hidden">
                            <div class="image-container">
                                <img src="https://imgs.search.brave.com/tLbdz2Hpm2QTRQAeZWnkiJANXbex0r4XQ1gDfqiTpP4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy84/LzhjL1Nvbm5lcmF0/aWFfYWxiYV8tX2Zy/dWl0Xyg4MzQ5OTgw/MjY0KS5qcGc" 
                                     alt="Sonneratia alba" 
                                     class="species-image">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-green-600">Sonneratia alba</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">High Tolerance</span>
                                </div>
                                <p class="text-gray-600 mt-1">White Mangrove</p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                        <span class="font-medium">Height:</span>
                                        <span class="ml-2">15-20 meters</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-medium">Salinity Tolerance:</span>
                                        <span class="ml-2">High</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bruguiera gymnorrhiza -->
                        <div class="species-card bg-white rounded-lg shadow overflow-hidden">
                            <div class="image-container">
                                <img src="https://imgs.search.brave.com/NxK1aW3_gLy_e8HysN6to9_M_DyYXRqYuS5s0dMepUg/rs:fit:860:0:0:0/g:ce/aHR0cDovL3d3dy5t/YW5ncm92ZS5hdC9p/bWFnZXMvc3BlY2ll/cy9icnVndWllcmFf/Z3ltbm9yaGl6YS9p/bnRyb2R1Y3Rpb24v/YnJ1Z3VpZXJhJTIw/Z3ltbm9yaGl6YSUy/MGxpdHRsZSUyMHRy/ZWUuanBn" 
                                     alt="Bruguiera gymnorrhiza" 
                                     class="species-image">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-green-600">Bruguiera gymnorrhiza</h3>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Medium</span>
                                </div>
                                <p class="text-gray-600 mt-1">Large-leafed Orange Mangrove</p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                        <span class="font-medium">Height:</span>
                                        <span class="ml-2">Up to 30 meters</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="font-medium">Salinity Tolerance:</span>
                                        <span class="ml-2">Medium</span>
                                    </div>
                                </div>
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
    </body>
</html> 