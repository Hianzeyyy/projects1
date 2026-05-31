<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center;">
            <button id="hamburger-btn" type="button" aria-label="Toggle navigation" style="background: none; border: none; font-size: 2rem; margin-right: 1rem; cursor: pointer;">&#9776;</button>
            <div style="flex: 1;">
                @if($type === 'medicines')
                    <h2 class="header-title">Medicines</h2>
                    <p class="header-subtitle">Manage your medicine catalog</p>
                @elseif($type === 'inventory')
                    <h2 class="header-title">Inventory</h2>
                    <p class="header-subtitle">Manage stock levels and batches</p>
                @elseif($type === 'sales')
                    <h2 class="header-title">Sales</h2>
                    <p class="header-subtitle">Track and manage sales transactions</p>
                @elseif($type === 'suppliers')
                    <h2 class="header-title">Suppliers</h2>
                    <p class="header-subtitle">Manage supplier contacts and information</p>
                @endif
            </div>
            <div class="header-actions">
                @if($type === 'medicines')
                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                        ➕ Add Medicine
                    </a>
                @elseif($type === 'inventory')
                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                        ➕ Add Stock
                    </a>
                @elseif($type === 'sales')
                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                        ➕ New Sale
                    </a>
                @elseif($type === 'suppliers')
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                        ➕ Add Supplier
                    </a>
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

    @if(session('success'))
        <div class="success-alert">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($type === 'inventory')
        {{-- Low Stock Alert --}}
        @php
            $lowStockCount = collect($inventory ?? [])->filter(function($item) {
                return $item->quantity <= $item->reorder_level;
            })->count();
        @endphp
        @if($lowStockCount > 0)
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <span style="color: #d97706; font-size: 1.5rem;">⚠️</span>
                <span style="color: #92400e; font-weight: 500;">{{ $lowStockCount }} {{ $lowStockCount == 1 ? 'item needs' : 'items need' }} restocking</span>
            </div>
        @endif
    @endif

    @if($type === 'sales')
        {{-- Sales Revenue Card --}}
        @php
            $totalRevenue = $sales->sum('total_amount');
            $totalTransactions = $sales->count();
        @endphp
        <div style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; margin-bottom: 0.5rem;">Total Revenue</p>
                    <h2 style="color: white; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">₱{{ number_format($totalRevenue, 2) }}</h2>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">{{ $totalTransactions }} {{ $totalTransactions == 1 ? 'transaction' : 'transactions' }}</p>
                </div>
                <div style="color: rgba(255, 255, 255, 0.3); font-size: 4rem;">📅</div>
            </div>
        </div>
    @endif

    {{-- Search Bar --}}
    <div style="margin-bottom: 1.5rem;">
        <div style="position: relative; max-width: 400px;">
            <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem;">🔍</span>
            <input type="text" placeholder="Search {{ $type }}..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 3rem; border: 1px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; background: white;">
        </div>
    </div>

    @if($type === 'suppliers')
        {{-- SUPPLIERS CARD LAYOUT --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5rem;">
            @forelse($suppliers ?? [] as $supplier)
                <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #f3f4f6;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">{{ $supplier->name }}</h3>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('suppliers.edit', $supplier) }}" style="background: transparent; border: none; cursor: pointer; padding: 0.25rem; color: #6b7280; font-size: 1.2rem;" title="Edit">✏️</a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: transparent; border: none; cursor: pointer; padding: 0.25rem; color: #ef4444; font-size: 1.2rem;" title="Delete" data-confirm-delete="Are you sure you want to delete this supplier?">🗑️</button>
                            </form>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                            <span>📧</span>
                            <span>{{ $supplier->email ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                            <span>📞</span>
                            <span>{{ $supplier->phone ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; align-items: start; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                            <span>📍</span>
                            <span>{{ $supplier->address ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🤝</div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">No suppliers found</h3>
                    <p style="color: #6b7280; margin-bottom: 1.5rem;">Get started by adding your first supplier.</p>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                        ➕ Add Supplier
                    </a>
                </div>
            @endforelse
        </div>
    @else
        {{-- TABLE LAYOUT FOR MEDICINES AND SALES --}}
        <div class="data-table">
            <table>
            @if($type === 'medicines')
                {{-- MEDICINES TABLE --}}
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Price</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines ?? [] as $medicine)
                        <tr>
                            <td><strong>{{ $medicine->name }}</strong></td>
                            <td>{{ $medicine->category ?: 'General' }}</td>
                            <td>{{ $medicine->manufacturer }}</td>
                            <td><strong>₱{{ number_format($medicine->price, 2) }}</strong></td>
                            <td>{{ date('m/d/Y', strtotime($medicine->expiry_date)) }}</td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('medicines.edit', $medicine) }}" class="action-btn edit" title="Edit">✏️</a>
                                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete" data-confirm-delete="Are you sure you want to delete this medicine?">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">💊</div>
                                    <h3>No medicines found</h3>
                                    <p>Get started by adding your first medicine to the inventory.</p>
                                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                                        ➕ Add Medicine
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            @elseif($type === 'inventory')
                {{-- INVENTORY TABLE --}}
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Reorder Level</th>
                        <th>Status</th>
                        <th>Supplier</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory ?? [] as $item)
                        @php
                            if ($item->quantity > $item->reorder_level * 2) {
                                $statusClass = 'badge-success';
                                $statusText = 'In Stock';
                            } elseif ($item->quantity > $item->reorder_level) {
                                $statusClass = 'badge-warning';
                                $statusText = 'Low Stock';
                            } else {
                                $statusClass = 'badge-danger';
                                $statusText = 'Out of Stock';
                            }
                        @endphp
                        <tr>
                            <td><strong>{{ $item->medicine_name }}</strong></td>
                            <td><span class="batch-number">{{ $item->batch_number }}</span></td>
                            <td><strong>{{ $item->quantity }}</strong></td>
                            <td>{{ $item->reorder_level }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
                            <td>{{ $item->supplier_name }}</td>
                            <td>{{ date('n/j/Y', strtotime($item->expiry_date)) }}</td>
                            <td>
                                <div class="action-btns">
                                    <a href="#" class="action-btn edit" title="Edit">✏️</a>
                                    <button class="action-btn delete" title="Delete">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📦</div>
                                    <h3>No inventory items found</h3>
                                    <p>Get started by adding your first inventory item.</p>
                                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                                        ➕ Add Stock
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            @elseif($type === 'sales')
                {{-- SALES TABLE --}}
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Medicine</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales ?? [] as $sale)
                        <tr>
                            <td class="sales-date-cell">
                                <div class="sales-date-text">{{ date('n/j/Y', strtotime($sale->created_at)) }}</div>
                                <div class="sales-time-text">{{ date('g:i A', strtotime($sale->created_at)) }}</div>
                            </td>
                            <td class="sales-medicine-cell"><strong>{{ $sale->medicine_name }}</strong></td>
                            <td class="sales-customer-cell">{{ $sale->customer_name ?? 'Walk-in' }}</td>
                            <td class="sales-quantity-cell">{{ $sale->quantity }}</td>
                            <td class="sales-price-cell">₱{{ number_format($sale->unit_price, 2) }}</td>
                            <td class="sales-total-cell"><strong class="sales-total-amount">₱{{ number_format($sale->total_amount, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">💰</div>
                                    <h3>No sales found</h3>
                                    <p>Get started by recording your first sale.</p>
                                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                        ➕ Record Sale
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            @endif
            </table>
        </div>
    @endif
</x-app-layout>
