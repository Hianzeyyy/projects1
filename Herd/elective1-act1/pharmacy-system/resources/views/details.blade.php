<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div>
                @if($action === 'show')
                    @if($type === 'medicines')
                        <h2 class="header-title">Medicine Details</h2>
                    @elseif($type === 'sales')
                        <h2 class="header-title">Sale Details</h2>
                    @elseif($type === 'suppliers')
                        <h2 class="header-title">Supplier Details</h2>
                    @endif
                @else
                    @if($type === 'medicines')
                        <h2 class="header-title">Edit Medicine</h2>
                        <p class="header-subtitle">Update medicine information</p>
                    @elseif($type === 'sales')
                        <h2 class="header-title">Edit Sale</h2>
                        <p class="header-subtitle">Update sales transaction details</p>
                    @elseif($type === 'suppliers')
                        <h2 class="header-title">Edit Supplier</h2>
                        <p class="header-subtitle">Update supplier information</p>
                    @endif
                @endif
            </div>
            @if($action === 'show')
                <a href="{{ route('records.' . $type) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($action === 'show')
                        {{-- SHOW VIEWS --}}
                        @if($type === 'medicines' && isset($medicine))
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $medicine->name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <strong>Description:</strong> {{ $medicine->description ?: 'N/A' }}
                                </div>
                                <div>
                                    <strong>Price:</strong> ₱{{ number_format($medicine->price, 2) }}
                                </div>
                                <div>
                                    <strong>Stock:</strong> {{ $medicine->stock }}
                                </div>
                                <div>
                                    <strong>Supplier:</strong> {{ $medicine->supplier->name }}
                                </div>
                            </div>
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Sales History</h4>
                                <ul class="list-disc list-inside">
                                    @forelse($medicine->sales as $sale)
                                        <li>{{ $sale->quantity }} units sold on {{ $sale->created_at->format('Y-m-d') }} for ₱{{ number_format($sale->total_amount, 2) }}</li>
                                    @empty
                                        <li>No sales recorded.</li>
                                    @endforelse
                                </ul>
                            </div>

                        @elseif($type === 'sales' && isset($sale))
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Sale #{{ $sale->id }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <strong>Medicine:</strong> {{ $sale->medicine->name }}
                                </div>
                                <div>
                                    <strong>Quantity:</strong> {{ $sale->quantity }}
                                </div>
                                <div>
                                    <strong>Total Price:</strong> ₱{{ number_format($sale->total_amount, 2) }}
                                </div>
                                <div>
                                    <strong>Sale Date:</strong> {{ $sale->sale_date }}
                                </div>
                            </div>

                        @elseif($type === 'suppliers' && isset($supplier))
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $supplier->name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <strong>Contact:</strong> {{ $supplier->contact ?: 'N/A' }}
                                </div>
                                <div>
                                    <strong>Address:</strong> {{ $supplier->address ?: 'N/A' }}
                                </div>
                            </div>
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Medicines from this Supplier</h4>
                                <ul class="list-disc list-inside">
                                    @forelse($supplier->medicines as $medicine)
                                        <li>{{ $medicine->name }} (Stock: {{ $medicine->stock }})</li>
                                    @empty
                                        <li>No medicines associated.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @endif

                    @else
                        {{-- EDIT VIEWS --}}
                        @if($type === 'medicines' && isset($medicine))
                            <form action="{{ route('medicines.update', $medicine) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $medicine->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $medicine->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $medicine->price) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @error('price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                                    <input type="number" name="stock" id="stock" value="{{ old('stock', $medicine->stock) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @error('stock')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers ?? [] as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $medicine->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Medicine
                                    </button>
                                    <a href="{{ route('records.medicines') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                                </div>
                            </form>

                        @elseif($type === 'sales' && isset($sale))
                            <form action="{{ route('sales.update', $sale) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="medicine_id" class="block text-sm font-medium text-gray-700">Medicine</label>
                                    <select name="medicine_id" id="medicine_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Select Medicine</option>
                                        @foreach($medicines ?? [] as $medicine)
                                            <option value="{{ $medicine->id }}" {{ old('medicine_id', $sale->medicine_id) == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }} (Stock: {{ $medicine->stock }})</option>
                                        @endforeach
                                    </select>
                                    @error('medicine_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $sale->quantity) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
                                    @error('quantity')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date</label>
                                    <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', $sale->sale_date) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @error('sale_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Sale
                                    </button>
                                    <a href="{{ route('records.sales') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                                </div>
                            </form>

                        @elseif($type === 'suppliers' && isset($supplier))
                            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                                    <input type="text" name="contact" id="contact" value="{{ old('contact', $supplier->contact) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('contact')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Supplier
                                    </button>
                                    <a href="{{ route('records.suppliers') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
