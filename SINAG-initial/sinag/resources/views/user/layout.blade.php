@extends('layouts.app')

@section('sidebar')
<aside class="w-64 bg-white shadow-lg flex flex-col py-8 px-4 min-h-screen">
    <div class="flex items-center mb-10">
        <img src="/images/sinag-logo.png" alt="SINAG Logo" class="h-10 w-10 rounded-full mr-3">
        <span class="text-2xl font-bold text-blue-700">SINAG</span>
    </div>
    <nav class="flex-1">
        <ul class="space-y-2">
            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-50 font-semibold text-blue-700">Dashboard</a></li>
            <li><a href="{{ route('report-abuse') }}" class="block px-4 py-2 rounded hover:bg-blue-50 font-semibold text-blue-700">Report Abuse</a></li>
            <li><a href="{{ route('evidence-vault') }}" class="block px-4 py-2 rounded hover:bg-green-50 font-semibold text-green-700">Evidence Vault</a></li>
            <li><a href="{{ route('suggestions') }}" class="block px-4 py-2 rounded hover:bg-yellow-50 font-semibold text-yellow-700">Suggestions</a></li>
            <li><a href="{{ route('safewalk') }}" class="block px-4 py-2 rounded hover:bg-purple-50 font-semibold text-purple-700">Safe-Walk Timer</a></li>
        </ul>
    </nav>
    <div class="mt-auto flex items-center space-x-2 pt-8 border-t">
        <span class="text-gray-700 font-medium flex-1">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline">Logout</button>
        </form>
    </div>
</aside>
@endsection

@section('main')
<div class="flex-1 flex flex-col items-center justify-center">
    @yield('content')
</div>
@endsection

@section('body')
<div class="min-h-screen bg-gray-50 flex">
    @yield('sidebar')
    @yield('main')
</div>
@endsection
