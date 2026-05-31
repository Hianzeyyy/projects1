@extends('user.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-purple-700">Safe-Walk Timer</h1>
    <p class="mb-6 text-gray-600">Start a countdown timer for your walk. Use the panic button to alert admin if you feel unsafe.</p>
    <!-- Timer and panic button UI will go here -->
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="mb-6">
            <span class="text-5xl font-mono font-bold text-purple-700">00:10:00</span>
        </div>
        <div class="flex space-x-4">
            <button class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 font-semibold">Start Timer</button>
            <button class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 font-semibold">Panic</button>
        </div>
    </div>
</div>
@endsection
