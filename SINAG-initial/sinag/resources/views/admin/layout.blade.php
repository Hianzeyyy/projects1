<div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex flex-col py-8 px-4 min-h-screen">
        <div class="flex items-center mb-10">
            <img src="/images/sinag-logo.png" alt="SINAG Logo" class="h-10 w-10 rounded-full mr-3">
            <span class="text-2xl font-bold text-blue-700">SINAG Admin</span>
        </div>
        <nav class="flex-1">
            <ul class="space-y-2">
                <li><a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-50 font-semibold text-blue-700">Dashboard</a></li>
                <li><a href="{{ route('admin.inbox') }}" class="block px-4 py-2 rounded hover:bg-blue-50 font-semibold text-blue-700">Abuse Inbox</a></li>
                <li><a href="{{ route('admin.suggestions') }}" class="block px-4 py-2 rounded hover:bg-yellow-50 font-semibold text-yellow-700">Suggestions</a></li>
                <li><a href="{{ route('admin.users') }}" class="block px-4 py-2 rounded hover:bg-green-50 font-semibold text-green-700">User Management</a></li>
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
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        @yield('content')
    </main>
</div>
