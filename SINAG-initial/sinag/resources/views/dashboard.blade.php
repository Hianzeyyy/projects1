@extends('user.layout')
@section('content')
<div class="w-full max-w-2xl mx-auto mt-12">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <h1 class="text-3xl font-bold mb-2 text-blue-700">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600 mb-6">This is your SINAG dashboard. Use the navigation to access all features.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('report-abuse') }}" class="block bg-blue-50 hover:bg-blue-100 rounded-lg p-6 text-blue-700 font-semibold shadow transition">Report Abuse</a>
            <a href="{{ route('evidence-vault') }}" class="block bg-green-50 hover:bg-green-100 rounded-lg p-6 text-green-700 font-semibold shadow transition">Evidence Vault</a>
            <a href="{{ route('suggestions') }}" class="block bg-yellow-50 hover:bg-yellow-100 rounded-lg p-6 text-yellow-700 font-semibold shadow transition">Suggestions</a>
            <a href="{{ route('safewalk') }}" class="block bg-purple-50 hover:bg-purple-100 rounded-lg p-6 text-purple-700 font-semibold shadow transition">Safe-Walk Timer</a>
        </div>
    </div>
</div>
@endsection
