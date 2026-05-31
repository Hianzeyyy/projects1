<aside class="w-64 bg-white border-r-4 border-indigo-500 flex flex-col py-10 px-6 min-h-screen font-[Figtree] shadow-2xl z-20">
    <div class="flex flex-col items-center mb-14">
        <span class="bg-indigo-600 text-white font-bold rounded-full w-14 h-14 flex items-center justify-center text-2xl mb-2 shadow-lg">S</span>
        <span class="text-xl font-bold tracking-wide text-indigo-700">SINAG</span>
    </div>
    <nav class="flex-1 w-full">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 rounded-2xl font-semibold text-indigo-600 bg-indigo-50 shadow-sm text-base transition relative @if(request()->routeIs('dashboard')) border-l-4 border-indigo-500 @endif">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 12l8-8 8 8"/></svg>
                    <span class="tracking-wide">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('report.create') }}" class="flex items-center px-6 py-3 rounded-2xl text-gray-700 hover:bg-indigo-50 text-base transition">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 20v-6m0 0V4m0 10l-4-4m4 4l4-4"/></svg>
                    <span class="tracking-wide">Report</span>
                </a>
            </li>
            <li>
                <a href="{{ route('evidence.index') }}" class="flex items-center px-6 py-3 rounded-2xl text-gray-700 hover:bg-indigo-50 text-base transition">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
                    <span class="tracking-wide">Vault</span>
                </a>
            </li>
            <li>
                <a href="{{ route('suggestions.index') }}" class="flex items-center px-6 py-3 rounded-2xl text-gray-700 hover:bg-indigo-50 text-base transition">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h10"/></svg>
                    <span class="tracking-wide">Boses</span>
                </a>
            </li>
            <li>
                <a href="{{ route('safewalk.index') }}" class="flex items-center px-6 py-3 rounded-2xl text-gray-700 hover:bg-indigo-50 text-base transition">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/><text x="16" y="20" font-size="10" fill="#7c3aed">W</text></svg>
                    <span class="tracking-wide">Safe<span class="text-indigo-400">Walk</span></span>
                </a>
            </li>
            <li>
                <a href="{{ route('wellness.index') }}" class="flex items-center px-6 py-3 rounded-2xl text-gray-700 hover:bg-indigo-50 text-base transition @if(request()->routeIs('wellness.index')) font-semibold text-indigo-600 bg-indigo-50 border-l-4 border-indigo-500 shadow-sm @endif">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h10"/></svg>
                    <span class="tracking-wide">Wellness</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
