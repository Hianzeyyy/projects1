<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center;">
            <button id="hamburger-btn" type="button" aria-label="Toggle navigation" style="background: none; border: none; font-size: 2rem; margin-right: 1rem; cursor: pointer;">&#9776;</button>
            <div style="flex: 1;">
                @if($type === 'medicines')
                    <h2 class="header-title">Add New Medicine</h2>
                    <p class="header-subtitle">Create a new medicine record in the inventory</p>
                @elseif($type === 'sales')
                    <h2 class="header-title">Record New Sale</h2>
                    <p class="header-subtitle">Create a new sales transaction record</p>
                @elseif($type === 'suppliers')
                    <h2 class="header-title">Add New Supplier</h2>
                    <p class="header-subtitle">Create a new supplier record in the system</p>
                @endif
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var btn = document.getElementById('hamburger-btn');
                btn.addEventListener('click', function() {
                    document.body.classList.toggle('show-sidebar');
                });
            });
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($type === 'medicines')
                        {{-- MEDICINES FORM --}}
                        <form action="{{ route('medicines.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Paracetamol 500mg" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="Analgesic" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer') }}" placeholder="PharmaCorp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('manufacturer')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" placeholder="Pain relief and fever reducer" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" placeholder="5.99" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('expiry_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Create Medicine
                                </button>
                                <a href="{{ route('records.medicines') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            </div>
                        </form>

                    @elseif($type === 'sales')
                        {{-- SALES FORM --}}
                        <form action="{{ route('sales.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="medicine_id" class="block text-sm font-medium text-gray-700">Medicine</label>
                                <select name="medicine_id" id="medicine_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Select Medicine{{ $sampleSale?->medicine?->name ? ' (e.g. ' . $sampleSale->medicine->name . ')' : '' }}</option>
                                    @foreach($medicines ?? [] as $medicine)
                                        <option value="{{ $medicine->id }}" {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }} (Stock: {{ $medicine->stock }})</option>
                                    @endforeach
                                </select>
                                @error('medicine_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" placeholder="{{ $sampleSale?->quantity ?? '2' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name (Optional)</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" placeholder="Enter customer name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Record Sale
                                </button>
                                <a href="{{ route('records.sales') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            </div>
                        </form>

                    @elseif($type === 'suppliers')
                        {{-- SUPPLIERS FORM --}}
                        <form action="{{ route('suppliers.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="{{ $sampleSupplier?->name ?? 'MediCore Distributors' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                                <input type="text" name="contact" id="contact" value="{{ old('contact') }}" placeholder="{{ $sampleSupplier?->contact ?? '+63 912 345 6789' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('contact')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" id="address" placeholder="{{ $sampleSupplier?->address ?? '123 Health Avenue, Manila' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Create Supplier
                                </button>
                                <a href="{{ route('records.suppliers') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
