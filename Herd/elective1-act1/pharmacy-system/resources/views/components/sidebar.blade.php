<!-- resources/views/components/sidebar.blade.php -->
<aside class="h-screen w-80 bg-white flex flex-col justify-between shadow-lg">
    <div>
        <div class="flex items-center px-8 py-8 rounded-b-2xl" style="background: linear-gradient(135deg, #16a34a 0%, #0e7490 100%); min-height: 110px;">
            <button class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 border-0 transition-all duration-150 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/20 text-4xl">💊</div>
                <span class="text-3xl font-extrabold text-white ml-2">PharmaSys</span>
            </div>
        </div>
        <nav class="mt-12 px-6 flex flex-col gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-xl {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow' : 'hover:bg-gray-100/30 text-gray-800 font-medium' }}">
                <span class="text-3xl">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('records.medicines') }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-xl {{ request()->routeIs('medicines.*') ? 'bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow' : 'hover:bg-gray-100/30 text-gray-800 font-medium' }}">
                <span class="text-3xl">💊</span>
                <span>Medicines</span>
            </a>
            <a href="{{ route('records.inventory') }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-xl {{ (request()->routeIs('inventory.*') || request()->is('inventory')) ? 'bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow' : 'hover:bg-gray-100/30 text-gray-800 font-medium' }}">
                <span class="text-3xl">📦</span>
                <span>Inventory</span>
            </a>
            <a href="{{ route('records.sales') }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-xl {{ request()->routeIs('sales.*') ? 'bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow' : 'hover:bg-gray-100/30 text-gray-800 font-medium' }}">
                <span class="text-3xl">💰</span>
                <span>Sales</span>
            </a>
            <a href="{{ route('records.suppliers') }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-xl {{ request()->routeIs('suppliers.*') ? 'bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow' : 'hover:bg-gray-100/30 text-gray-800 font-medium' }}">
                <span class="text-3xl">🤝</span>
                <span>Suppliers</span>
            </a>
        </nav>
    </div>
    <div class="px-8 py-8">
        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
            @csrf
            <button type="submit" class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow w-full justify-center text-xl">
                <span class="text-3xl">🚪</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
