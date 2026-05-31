
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50">
    <!-- Sidebar (Figma style, same as dashboard) -->
    <aside class="w-64 bg-white border-r-4 border-indigo-500 flex flex-col py-10 px-6 min-h-screen font-[Figtree] shadow-2xl z-20">
        <div class="flex flex-col items-center mb-14">
            <span class="bg-indigo-600 text-white font-bold rounded-full w-14 h-14 flex items-center justify-center text-2xl mb-2 shadow-lg">S</span>
            <span class="text-xl font-bold tracking-wide text-indigo-700">SINAG</span>
        </div>
        <nav class="flex-1 w-full">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 rounded-2xl font-semibold text-indigo-600 bg-indigo-50 shadow-sm text-base transition relative">
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
                    <a href="{{ route('wellness.index') }}" class="flex items-center px-6 py-3 rounded-2xl text-indigo-600 font-semibold bg-indigo-50 border-l-4 border-indigo-500 shadow-sm text-base transition">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h10"/></svg>
                        <span class="tracking-wide">Wellness</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col relative">
        <main class="flex-1 p-10">
            <div class="flex flex-col gap-8 items-center">
                <!-- Header Card: Wellness Library -->
                <div class="w-full max-w-[700px] rounded-[20px] bg-[#00a6c7] px-8 py-8 shadow-[0_8px_32px_0_rgba(0,166,199,0.15)] flex flex-col items-center relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-2">
                        <span class="bg-cyan-700 bg-opacity-20 rounded-full p-3 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="M7 9h10M7 13h10"/>
                            </svg>
                        </span>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-white">Wellness Library</h2>
                    </div>
                    <div class="text-white text-base md:text-lg font-medium text-center">Knowledge is protection. Access offline-ready resources about your rights, safety guides, and emergency contacts.</div>
                    <span class="absolute right-0 top-0 w-48 h-32 bg-cyan-900 bg-opacity-20 rounded-bl-full pointer-events-none" style="filter: blur(8px);"></span>
                </div>
                <!-- Emergency Hotlines -->
                <div class="w-full max-w-[700px] bg-white rounded-2xl shadow p-8 flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6.5 6.5l11 11M6.5 17.5l11-11"/></svg>
                        <span class="font-extrabold text-xl text-gray-900">Emergency Hotlines</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            <span class="font-bold text-gray-900 text-base">PSU Asingan Security Office</span>
                            <a href="tel:0755551234" class="ml-auto text-cyan-700 font-extrabold text-base">(075) 555-1234</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            <span class="font-bold text-gray-900 text-base">Campus Guidance Counselor</span>
                            <a href="tel:0755555678" class="ml-auto text-cyan-700 font-extrabold text-base">(075) 555-5678</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-pink-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            <span class="font-bold text-gray-900 text-base">PNP Women & Children Desk</span>
                            <a href="tel:177" class="ml-auto text-cyan-700 font-extrabold text-base">177</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            <span class="font-bold text-gray-900 text-base">Asingan Municipal Police</span>
                            <a href="tel:0756321008" class="ml-auto text-cyan-700 font-extrabold text-base">(075) 632-1008</a>
                        </div>
                    </div>
                </div>
                <!-- Guides & Resources -->
                <div class="w-full max-w-[700px] flex flex-col gap-6">
                    <!-- Resource Cards: Single vertical stack, no duplicates -->
                    <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-cyan-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-cyan-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/><path d="M8 8h8M8 12h8M8 16h8"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-extrabold text-lg text-gray-900">Safe Spaces Act (RA 11313) Simplified <a href="#" class="ml-1 text-cyan-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                            <div class="text-gray-800 text-base">A student-friendly guide to understanding your rights against gender-based sexual harassment in public spaces and online.</div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="bg-cyan-200 text-cyan-800 text-xs font-bold rounded px-2 py-0.5">PDF GUIDE</span>
                                <span class="text-gray-500 text-xs">2.4 MB</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-indigo-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/><path d="M8 8h8M8 12h8M8 16h8"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-extrabold text-lg text-gray-900">How to File a Report at PSU Asingan <a href="#" class="ml-1 text-indigo-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                            <div class="text-gray-800 text-base">Step-by-step flowchart on filing a complaint with the Committee on Decorum and Investigation (CODI).</div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="bg-indigo-200 text-indigo-800 text-xs font-bold rounded px-2 py-0.5">INFOGRAPHIC</span>
                                <span class="text-gray-500 text-xs">1.8 MB</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-pink-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-pink-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 21C12 21 7 16.5 7 12.5C7 9.5 9.5 7 12 7C14.5 7 17 9.5 17 12.5C17 16.5 12 21 12 21Z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-extrabold text-lg text-gray-900">Coping with Trauma <a href="#" class="ml-1 text-pink-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                            <div class="text-gray-800 text-base">Self-care strategies and mental health resources for survivors of harassment and abuse.</div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="bg-pink-200 text-pink-800 text-xs font-bold rounded px-2 py-0.5">ARTICLE</span>
                                <span class="text-gray-500 text-xs">5 min read</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-yellow-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-extrabold text-lg text-gray-900">Cyberbullying & Online Safety <a href="#" class="ml-1 text-yellow-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                            <div class="text-gray-800 text-base">Tips on how to protect your digital footprint and what to do if you are being harassed online.</div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="bg-yellow-200 text-yellow-800 text-xs font-bold rounded px-2 py-0.5">VIDEO</span>
                                <span class="text-gray-500 text-xs">10 min watch</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-[700px] bg-indigo-50 rounded-2xl shadow p-10 flex flex-col items-center mt-8 mx-auto">
                    <div class="text-3xl md:text-4xl font-extrabold mb-4 text-indigo-900 text-center">Need to talk to someone?</div>
                    <div class="text-xl font-semibold mb-8 text-center text-indigo-900">The PSU Guidance Office offers confidential counseling services for students. You are not alone.</div>
                    <a href="#" class="bg-white text-indigo-800 font-extrabold rounded-lg px-10 py-4 shadow hover:bg-indigo-100 transition text-xl mt-2">Schedule an Appointment</a>
                </div>
            </div>
                        <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-cyan-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-cyan-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/><path d="M8 8h8M8 12h8M8 16h8"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-extrabold text-lg text-gray-900">Safe Spaces Act (RA 11313) Simplified <a href="#" class="ml-1 text-cyan-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                                <div class="text-gray-800 text-base">A student-friendly guide to understanding your rights against gender-based sexual harassment in public spaces and online.</div>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="bg-cyan-200 text-cyan-800 text-xs font-bold rounded px-2 py-0.5">PDF GUIDE</span>
                                    <span class="text-gray-500 text-xs">2.4 MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-indigo-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/><path d="M8 8h8M8 12h8M8 16h8"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-extrabold text-lg text-gray-900">How to File a Report at PSU Asingan <a href="#" class="ml-1 text-indigo-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                                <div class="text-gray-800 text-base">Step-by-step flowchart on filing a complaint with the Committee on Decorum and Investigation (CODI).</div>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="bg-indigo-200 text-indigo-800 text-xs font-bold rounded px-2 py-0.5">INFOGRAPHIC</span>
                                    <span class="text-gray-500 text-xs">1.8 MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-pink-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-pink-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 21C12 21 7 16.5 7 12.5C7 9.5 9.5 7 12 7C14.5 7 17 9.5 17 12.5C17 16.5 12 21 12 21Z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-extrabold text-lg text-gray-900">Coping with Trauma <a href="#" class="ml-1 text-pink-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                                <div class="text-gray-800 text-base">Self-care strategies and mental health resources for survivors of harassment and abuse.</div>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="bg-pink-200 text-pink-800 text-xs font-bold rounded px-2 py-0.5">ARTICLE</span>
                                    <span class="text-gray-500 text-xs">5 min read</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-5 flex gap-4 items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-[16px] bg-yellow-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-extrabold text-lg text-gray-900">Cyberbullying & Online Safety <a href="#" class="ml-1 text-yellow-700 underline underline-offset-2" target="_blank"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/></svg></a></div>
                                <div class="text-gray-800 text-base">Tips on how to protect your digital footprint and what to do if you are being harassed online.</div>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="bg-yellow-200 text-yellow-800 text-xs font-bold rounded px-2 py-0.5">VIDEO</span>
                                    <span class="text-gray-500 text-xs">10 min watch</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-50 rounded-2xl shadow p-10 flex flex-col items-center mt-8 w-full max-w-3xl mx-auto">
                        <div class="text-3xl md:text-4xl font-extrabold mb-4 text-indigo-900 text-center">Need to talk to someone?</div>
                        <div class="text-xl font-semibold mb-8 text-center text-indigo-900">The PSU Guidance Office offers confidential counseling services for students. You are not alone.</div>
                        <a href="#" class="bg-white text-indigo-800 font-extrabold rounded-lg px-10 py-4 shadow hover:bg-indigo-100 transition text-xl mt-2">Schedule an Appointment</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
