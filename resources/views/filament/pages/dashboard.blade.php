<x-filament-panels::page>
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <p class="text-gray-300">Welcome to the Sports Club Admin Panel</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700 hover:border-cyan-500 transition-all duration-300">
                <div class="flex items-center">
                    <div class="rounded-full bg-cyan-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-300">Total Users</h3>
                        <p class="text-2xl font-bold text-cyan-400">{{ \App\Models\User::count() ?: 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Events Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700 hover:border-green-500 transition-all duration-300">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-300">Total Events</h3>
                        <p class="text-2xl font-bold text-green-400">{{ \App\Models\Event::count() ?: 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Equipment Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700 hover:border-yellow-500 transition-all duration-300">
                <div class="flex items-center">
                    <div class="rounded-full bg-yellow-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-300">Total Equipment</h3>
                        <p class="text-2xl font-bold text-yellow-400">{{ \App\Models\Equipment::count() ?: 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Reservations Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700 hover:border-purple-500 transition-all duration-300">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-300">Active Reservations</h3>
                        <p class="text-2xl font-bold text-purple-400">{{ \App\Models\EquipmentReservation::where('status', 'borrowed')->count() ?: 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
           

            <!-- Recent Equipment Reservations -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Equipment Reservations</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Equipment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach(\App\Models\EquipmentReservation::with(['equipment', 'user'])->latest()->limit(5)->get() as $reservation)
                            <tr class="hover:bg-gray-750 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $reservation->equipment->name ?? 'Unknown Equipment' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $reservation->user->name ?? 'Unknown User' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($reservation->status == 'borrowed') bg-yellow-900 text-yellow-400 
                                        @elseif($reservation->status == 'returned') bg-green-900 text-green-400 
                                        @else bg-gray-700 text-gray-300 @endif">
                                        {{ ucfirst($reservation->status ?? 'unknown') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>