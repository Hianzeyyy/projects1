<aside class="h-screen w-64 bg-white border-r border-blue-100 flex flex-col py-8 px-4 shadow-xl">
    <div class="flex items-center mb-10">
        <span class="text-2xl font-extrabold text-blue-700 tracking-wide">SINAG</span>
    </div>
    <nav class="flex-1">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg font-semibold text-blue-700 hover:bg-blue-50 transition">
                    <span class="mr-3">🏠</span> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('student.report-abuse') }}" class="flex items-center px-4 py-2 rounded-lg font-semibold text-blue-700 hover:bg-blue-50 transition">
                    <span class="mr-3">🚨</span> Report Abuse
                </a>
            </li>
            <li>
                <a href="{{ route('student.evidence-vault') }}" class="flex items-center px-4 py-2 rounded-lg font-semibold text-green-700 hover:bg-green-50 transition">
                    <span class="mr-3">🗂️</span> Evidence Vault
                </a>
            </li>
            <li>
                <a href="{{ route('student.suggestions') }}" class="flex items-center px-4 py-2 rounded-lg font-semibold text-yellow-700 hover:bg-yellow-50 transition">
                    <span class="mr-3">💡</span> Suggestions
                </a>
            </li>
            <li>
                <a href="{{ route('student.safewalk') }}" class="flex items-center px-4 py-2 rounded-lg font-semibold text-purple-700 hover:bg-purple-50 transition">
                    <span class="mr-3">🕒</span> Safe-Walk Timer
                </a>
            </li>
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
