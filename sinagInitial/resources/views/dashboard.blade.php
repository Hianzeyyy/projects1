

@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $initial = strtoupper(substr($user->name ?? 'A', 0, 1));
    $name = $user->name ?? 'Admin User';
@endphp

<div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50">
    @include('partials.sidebar')

    <!-- Main Content (with topbar, Figma style) -->
    <div class="flex-1 flex flex-col relative">
        <main class="flex-1 p-10">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-indigo-500 to-violet-500 rounded-2xl p-8 mb-8 shadow-2xl text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Welcome, Student</h2>
                    <p class="mb-4">Your secure, gender-responsive portal for PSU Asingan. Report safely, speak up, and stay informed.</p>
                    <button class="bg-white text-indigo-600 font-semibold px-6 py-2 rounded-lg shadow hover:bg-indigo-50 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h8m-4-4v8"/></svg>
                        Report an Incident
                    </button>
                </div>
                <span class="absolute right-8 top-4 opacity-20 text-8xl select-none">●</span>
                <span class="absolute right-0 top-0 w-40 h-40 bg-white opacity-10 rounded-full pointer-events-none" style="filter: blur(8px);"></span>
            </div>

            <!-- Quick Access -->
            <h3 class="font-semibold text-lg mb-4 text-gray-800">Quick Access</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition border-t-4 border-indigo-400 flex flex-col relative group cursor-pointer">
                    <div class="flex items-center mb-2">
                        <span class="bg-indigo-100 text-indigo-600 rounded-lg p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6"/></svg>
                        </span>
                        <span class="font-bold text-gray-900">Secure Reporting</span>
                    </div>
                    <span class="text-gray-500 text-sm">Submit encrypted incident reports anonymously.</span>
                    <span class="absolute top-4 right-4 bg-indigo-100 group-hover:bg-indigo-200 rounded-full p-2 shadow transition">
                        <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition border-t-4 border-green-400 flex flex-col relative">
                    <div class="flex items-center mb-2">
                        <span class="bg-green-100 text-green-600 rounded-lg p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2"/></svg>
                        </span>
                        <span class="font-bold text-gray-900">Boses-Isolan</span>
                    </div>
                    <span class="text-gray-500 text-sm">Suggest campus improvements and upvote ideas.</span>
                    <span class="absolute top-4 right-4 bg-gray-100 rounded-full p-2 shadow">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition border-t-4 border-purple-400 flex flex-col relative">
                    <div class="flex items-center mb-2">
                        <span class="bg-purple-100 text-purple-600 rounded-lg p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg>
                        </span>
                        <span class="font-bold text-gray-900">Evidence Vault</span>
                    </div>
                    <span class="text-gray-500 text-sm">Securely store and timestamp digital evidence.</span>
                    <span class="absolute top-4 right-4 bg-gray-100 rounded-full p-2 shadow">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition border-t-4 border-yellow-400 flex flex-col relative">
                    <div class="flex items-center mb-2">
                        <span class="bg-yellow-100 text-yellow-600 rounded-lg p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m4 0h-1v4h-1"/></svg>
                        </span>
                        <span class="font-bold text-gray-900">Safe-Walk Timer</span>
                    </div>
                    <span class="text-gray-500 text-sm">Set a timer for your walk home.</span>
                    <span class="absolute top-4 right-4 bg-gray-100 rounded-full p-2 shadow">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition border-t-4 border-cyan-400 flex flex-col relative">
                    <div class="flex items-center mb-2">
                        <span class="bg-cyan-100 text-cyan-600 rounded-lg p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 17l4-4 4 4"/></svg>
                        </span>
                        <span class="font-bold text-gray-900">Wellness Library</span>
                    </div>
                    <span class="text-gray-500 text-sm">Access RA 11313 guides and resources.</span>
                    <span class="absolute top-4 right-4 bg-gray-100 rounded-full p-2 shadow">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>

            <!-- Recent Notifications -->
            <h3 class="font-semibold text-lg mb-4 text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m4 0h-1v4h-1"/></svg>
                Recent Notifications
            </h3>
            <div class="bg-white rounded-xl p-6 shadow border border-gray-100 mb-8">
                <span class="text-gray-500 text-sm">No new notifications.</span>
            </div>
        </main>
    </div>

    @if(request()->routeIs('dashboard'))
    <!-- Floating Alert Button -->
    <button class="absolute bottom-8 right-8 bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-2xl z-50 text-4xl border-4 border-white">
        <svg class="w-9 h-9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </button>
    @endif
</div>
@endsection

<!-- Chart.js for the bar chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [
                {
                    label: 'Reports',
                    data: [3, 9, 18, 27, 15, 21, 19, 24, 22, 30],
                    backgroundColor: '#00b894',
                    borderRadius: 8
                },
                {
                    label: 'Suggestions',
                    data: [2, 3, 7, 4, 6, 8, 2, 5, 9, 12],
                    backgroundColor: '#ff5e5e',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f3ff' }
                }
            }
        }
    });
</script>
