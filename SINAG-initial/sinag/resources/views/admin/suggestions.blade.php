@extends('admin.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-yellow-700">Suggestions Management</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="py-2">Suggestion</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>More campus lighting</td>
                    <td><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Pending</span></td>
                    <td>
                        <button class="text-green-600 hover:underline">Approve</button>
                        <button class="text-red-600 hover:underline">Reject</button>
                    </td>
                </tr>
                <!-- More suggestions here -->
            </tbody>
        </table>
    </div>
</div>
@endsection
