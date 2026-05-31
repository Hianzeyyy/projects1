@extends('admin.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-blue-700">Admin Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-3xl font-bold text-blue-700 mb-2">128</div>
            <div class="text-gray-600">Total Reports</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-3xl font-bold text-green-700 mb-2">56</div>
            <div class="text-gray-600">Evidence Files</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-3xl font-bold text-yellow-700 mb-2">34</div>
            <div class="text-gray-600">Suggestions</div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4">Activity Logs</h2>
        <ul class="divide-y divide-gray-200">
            <li class="py-2">User <span class="font-semibold">student@psu.edu.ph</span> submitted a report.</li>
            <li class="py-2">Admin <span class="font-semibold">admin@psu.edu.ph</span> resolved a case.</li>
            <!-- More logs here -->
        </ul>
    </div>
</div>
@endsection
