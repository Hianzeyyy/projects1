@extends('admin.layout')
@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-4 text-blue-700">User Management</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="py-2">Name</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Role</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Dela Cruz</td>
                    <td>student@psu.edu.ph</td>
                    <td><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">Student</span></td>
                    <td>
                        <button class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>Admin User</td>
                    <td>admin@psu.edu.ph</td>
                    <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">Admin</span></td>
                    <td>
                        <button class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
                <!-- More users here -->
            </tbody>
        </table>
    </div>
</div>
@endsection
