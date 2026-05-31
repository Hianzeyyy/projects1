<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-[#f6f6ff] to-[#ece9fd] py-12">
    <div class="flex flex-col items-center mb-8">
        <div class="bg-[#5B4FFF] rounded-full p-4 mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" class="w-10 h-10">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5c2.485 0 4.5 1.567 4.5 3.5v2.25c0 1.933-2.015 3.5-4.5 3.5s-4.5-1.567-4.5-3.5V8c0-1.933 2.015-3.5 4.5-3.5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0c-2.485 0-4.5-1.567-4.5-3.5V8m9 11.5c2.485 0 4.5-1.567 4.5-3.5V8" />
            </svg>
        </div>
        <h1 class="text-5xl font-extrabold text-gray-900 mb-1 tracking-wide">Create Account</h1>
        <p class="text-lg text-gray-500">Join SINAG - Your voice matters</p>
    </div>
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 border border-transparent">
        <form wire:submit.prevent="register" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.564-7.499-1.632z" />
                        </svg>
                    </span>
                    <input id="name" type="text" wire:model.defer="name" required autofocus class="w-full pl-10 pr-4 py-3 text-base bg-[#e9edfb] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5B4FFF] text-gray-900 font-medium placeholder-gray-400" placeholder="Juan Dela Cruz">
                </div>
            </div>
            <div>
                <label for="student_id" class="block text-sm font-semibold text-gray-700 mb-1">Student ID Number</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </span>
                    <input id="student_id" type="text" wire:model.defer="student_id" required class="w-full pl-10 pr-4 py-3 text-base bg-[#e9edfb] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5B4FFF] text-gray-900 font-medium placeholder-gray-400" placeholder="2024-12345">
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">PSU Email Address</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 16.5v-9m19.5 0A2.25 2.25 0 0019.5 5.25H4.5A2.25 2.25 0 002.25 7.5m19.5 0v.243a2.25 2.25 0 01-.659 1.591l-7.091 7.091a2.25 2.25 0 01-3.182 0l-7.091-7.091A2.25 2.25 0 012.25 7.743V7.5" />
                        </svg>
                    </span>
                    <input id="email" type="email" wire:model.defer="email" required class="w-full pl-10 pr-4 py-3 text-base bg-[#e9edfb] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5B4FFF] text-gray-900 font-medium placeholder-gray-400" placeholder="student@psu.edu.ph">
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m13.5 0v7.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V10.5m13.5 0h-15" />
                        </svg>
                    </span>
                    <input id="password" type="password" wire:model.defer="password" required class="w-full pl-10 pr-12 py-3 text-base bg-[#e9edfb] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5B4FFF] text-gray-900 font-medium placeholder-gray-400" placeholder="Password">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75A3.75 3.75 0 1112 8.25a3.75 3.75 0 010 7.5z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m13.5 0v7.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V10.5m13.5 0h-15" />
                        </svg>
                    </span>
                    <input id="password_confirmation" type="password" wire:model.defer="password_confirmation" required class="w-full pl-10 pr-12 py-3 text-base bg-[#e9edfb] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5B4FFF] text-gray-900 font-medium placeholder-gray-400" placeholder="Re-enter password">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#5B4FFF" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75A3.75 3.75 0 1112 8.25a3.75 3.75 0 010 7.5z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="bg-[#f3f0fe] rounded-xl p-4 flex items-center gap-3">
                <span class="bg-[#5B4FFF] rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5c2.485 0 4.5 1.567 4.5 3.5v2.25c0 1.933-2.015 3.5-4.5 3.5s-4.5-1.567-4.5-3.5V8c0-1.933 2.015-3.5 4.5-3.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0c-2.485 0-4.5-1.567-4.5-3.5V8m9 11.5c2.485 0 4.5-1.567 4.5-3.5V8" />
                    </svg>
                </span>
                <span class="text-sm text-[#5B4FFF] font-semibold">The Cloak Protection</span>
                <span class="text-xs text-gray-500">You'll receive a unique alias to protect your identity when reporting incidents.</span>
            </div>
            <div class="flex items-center">
                <input id="terms" type="checkbox" required class="form-checkbox rounded text-[#5B4FFF]">
                <label for="terms" class="ml-2 text-sm text-gray-600">I agree to the <a href="#" class="text-[#5B4FFF] font-semibold hover:underline">Terms of Service</a> and <a href="#" class="text-[#5B4FFF] font-semibold hover:underline">Privacy Policy</a></label>
            </div>
            <button type="submit" class="w-full py-3 bg-[#5B4FFF] text-white rounded-xl hover:bg-[#4836d7] font-bold text-lg shadow transition">Create Account</button>
        </form>
        <div class="mt-4 text-center text-sm">
            Already have an account? <a href="/login" class="text-[#5B4FFF] font-semibold hover:underline">Sign in</a>
        </div>
        <div class="mt-4 text-xs text-gray-400 text-center">
            Your identity is protected by encryption and will never be shared without your consent and rights under the Safe Spaces Act (RA 11313).
        </div>
    </div>
</div>
