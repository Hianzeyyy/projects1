@extends('admin.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-blue-700">Abuse Inbox</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="py-2">Report</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Assigned</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Anonymous: "Harassment in hallway"</td>
                    <td><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Unread</span></td>
                    <td>admin@psu.edu.ph</td>
                    <td>
                        <button class="text-blue-600 hover:underline">View</button>
                        <button class="text-green-600 hover:underline">Resolve</button>
                    </td>
                </tr>
                <!-- More reports here -->
            </tbody>
        </table>
    </div>
</div>
@endsection
