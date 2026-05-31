@extends('user.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-green-700">Evidence Vault</h1>
    <p class="mb-6 text-gray-600">Securely upload, download, and manage your evidence files. Files are encrypted and private.</p>
    <!-- File management UI will go here -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Upload New File</label>
            <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
        </div>
        <div class="mt-6">
            <h2 class="font-semibold mb-2">Your Files</h2>
            <ul class="divide-y divide-gray-200">
                <li class="py-2 flex items-center justify-between">
                    <span>evidence1.pdf</span>
                    <div>
                        <button class="text-blue-600 hover:underline mr-2">Download</button>
                        <button class="text-red-600 hover:underline">Delete</button>
                    </div>
                </li>
                <!-- More files here -->
            </ul>
        </div>
    </div>
</div>
@endsection
