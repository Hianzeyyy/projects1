@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    @include('student.sidebar')
    <main class="flex-1 flex flex-col items-center justify-center p-8">
        <div class="w-full max-w-2xl mx-auto mt-12">
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-center border border-blue-100">
                <h1 class="text-3xl font-extrabold mb-2 text-blue-700">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600 mb-6">This is your SINAG dashboard. Use the navigation to access all features.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('student.report-abuse') }}" class="block bg-blue-50 hover:bg-blue-100 rounded-xl p-6 text-blue-700 font-semibold shadow transition">🚨 Report Abuse</a>
                    <a href="{{ route('student.evidence-vault') }}" class="block bg-green-50 hover:bg-green-100 rounded-xl p-6 text-green-700 font-semibold shadow transition">🗂️ Evidence Vault</a>
                    <a href="{{ route('student.suggestions') }}" class="block bg-yellow-50 hover:bg-yellow-100 rounded-xl p-6 text-yellow-700 font-semibold shadow transition">💡 Suggestions</a>
                    <a href="{{ route('student.safewalk') }}" class="block bg-purple-50 hover:bg-purple-100 rounded-xl p-6 text-purple-700 font-semibold shadow transition">🕒 Safe-Walk Timer</a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
