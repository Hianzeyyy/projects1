@extends('user.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-yellow-700">Boses-Isolan (Suggestions)</h1>
    <p class="mb-6 text-gray-600">Submit your suggestions or upvote others. One upvote per user per suggestion.</p>
    <!-- Suggestion form and list UI will go here -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Your Suggestion</label>
                <textarea class="w-full border rounded p-2" rows="3" placeholder="Share your idea..."></textarea>
            </div>
            <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 font-semibold">Submit Suggestion</button>
        </form>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-2">Suggestions</h2>
        <ul class="divide-y divide-gray-200">
            <li class="py-2 flex items-center justify-between">
                <span>More campus lighting</span>
                <button class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 font-semibold">Upvote (12)</button>
            </li>
            <!-- More suggestions here -->
        </ul>
    </div>
</div>
@endsection
