@extends('user.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-blue-700">Report Abuse</h1>
    <p class="mb-6 text-gray-600">Submit an anonymous report. Upload evidence (images, PDFs, audio, video). All reports are confidential.</p>
    <!-- Form and upload UI will go here -->
    <div class="bg-white rounded-lg shadow p-6">
        <form>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea class="w-full border rounded p-2" rows="4" placeholder="Describe the incident..."></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Upload Evidence</label>
                <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple>
                <p class="text-xs text-gray-400 mt-1">Max 10MB. jpg, png, pdf, mp3, mp4, webm.</p>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">Submit Report</button>
        </form>
    </div>
</div>
@endsection
