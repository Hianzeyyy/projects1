@extends('layouts.app')

@section('content')
    @php
        $tab = request()->segment(2) ?? 'dashboard';
    @endphp


    @if ($tab === 'dashboard')
        {{-- Dashboard content (keep as is) --}}
        @php
            $todaySalesCount = \App\Models\Sale::whereDate('created_at', now()->toDateString())->count();
            $todaySalesTotal = \App\Models\Sale::whereDate('created_at', now()->toDateString())->sum('total_amount');
            $outOfStockCount = \App\Models\Inventory::where('quantity', '<=', 0)->count();
            $lowStockCount = \App\Models\Inventory::whereRaw('quantity <= reorder_level')->count();
            $lowStockMedicines = \App\Models\Inventory::whereRaw('quantity <= reorder_level')->orderBy('quantity')->limit(5)->get();
            $recentSales = \App\Models\Sale::orderByDesc('created_at')->limit(5)->get();
            $topMedicines = \App\Models\Sale::selectRaw('medicine_name, SUM(quantity) as total_qty, SUM(total_amount) as total_amount')->groupBy('medicine_name')->orderByDesc('total_qty')->limit(5)->get();
            $latestSuppliers = \App\Models\Supplier::latest('id')->limit(5)->get();
            $healthyStockCount = \App\Models\Inventory::where('quantity', '>', 10)->count();
            $suppliersWithContact = \App\Models\Supplier::whereNotNull('phone')->count();
            $salesCount = \App\Models\Sale::count();
            $totalSales = \App\Models\Sale::sum('total_amount');
            $averageSale = $salesCount > 0 ? $totalSales / $salesCount : 0;
            $inventoryCount = \App\Models\Inventory::count();
            $stockHealth = $inventoryCount > 0 ? round(($healthyStockCount / $inventoryCount) * 100) : 0;
            $medicinesCount = \App\Models\Medicine::count();
            $suppliersCount = \App\Models\Supplier::count();
            $activeAlerts = $outOfStockCount + $lowStockCount;
        @endphp

        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome to PharmaSys</h1>
            <p class="text-lg text-gray-500 mb-4">Overview of your pharmacy management system</p>
            <div class="flex flex-wrap gap-3 mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-sm font-medium text-gray-700">📅 {{ now()->format('l, M d Y') }}</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-sm font-medium text-yellow-800">⚠️ {{ $activeAlerts }} active inventory alerts</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-sm font-medium text-green-800">✅ Stock health: {{ $stockHealth }}%</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
                <div class="text-gray-500 text-sm mb-1">Today Sales</div>
                <div class="text-2xl font-bold text-gray-900">{{ $todaySalesCount }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
                <div class="text-gray-500 text-sm mb-1">Today Revenue</div>
                <div class="text-2xl font-bold text-gray-900">₱{{ number_format($todaySalesTotal, 2) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
                <div class="text-gray-500 text-sm mb-1">Out of Stock</div>
                <div class="text-2xl font-bold text-gray-900">{{ $outOfStockCount }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
                <div class="text-gray-500 text-sm mb-1">Low Stock Items</div>
                <div class="text-2xl font-bold text-gray-900">{{ $lowStockCount }}</div>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-900 mb-6">Care Priorities</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-3xl shadow-lg p-8 flex flex-col items-start">
                <div class="text-2xl mb-2">🔴</div>
                <div class="text-lg font-bold text-gray-900 mb-1">Urgent Restock</div>
                <div class="text-gray-600 mb-6">{{ $lowStockCount }} medicines are below reorder threshold and need action.</div>
                <a href="{{ route('records.medicines') }}" class="inline-block px-6 py-2 rounded-xl bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow hover:scale-105 transition">Review Inventory</a>
            </div>
            <div class="bg-white rounded-3xl shadow-lg p-8 flex flex-col items-start">
                <div class="text-2xl mb-2">🟢</div>
                <div class="text-lg font-bold text-gray-900 mb-1">Supplier Coverage</div>
                <div class="text-gray-600 mb-6">{{ $suppliersWithContact }} suppliers have contact details for faster purchasing.</div>
                <a href="{{ route('records.suppliers') }}" class="inline-block px-6 py-2 rounded-xl bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow hover:scale-105 transition">View Suppliers</a>
            </div>
            <div class="bg-white rounded-3xl shadow-lg p-8 flex flex-col items-start">
                <div class="text-2xl mb-2">📈</div>
                <div class="text-lg font-bold text-gray-900 mb-1">Sales Momentum</div>
                <div class="text-gray-600 mb-6">{{ $todaySalesCount }} sales recorded today with strong pharmacy flow.</div>
                <a href="{{ route('records.sales') }}" class="inline-block px-6 py-2 rounded-xl bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold shadow hover:scale-105 transition">Open Sales</a>
            </div>
        </div>
                            <a href="{{ route('records.suppliers') }}" class="panel-btn">View Suppliers</a>
                        </article>
                        <article class="priority-card priority-info">
                            <h4>Sales Momentum</h4>
                            <p>{{ $todaySalesCount }} sales recorded today with strong pharmacy flow.</p>
                            <a href="{{ route('records.sales') }}" class="panel-btn">Open Sales</a>
                        </article>
                    </section>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-3xl shadow-lg p-8 flex flex-col items-start">
                <div class="text-gray-500 mb-2">Average Sale Value</div>
                <div class="text-2xl font-bold text-gray-900 mb-1">₱{{ number_format($averageSale, 2) }}</div>
                <div class="text-gray-400">How much each transaction contributes on average.</div>
            </div>
            <div class="bg-white rounded-3xl shadow-lg p-8 flex flex-col items-start">
                <div class="text-gray-500 mb-2">Healthy Stock Medicines</div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $healthyStockCount }} items</div>
                <div class="text-gray-400">Medicines with stock above the reorder threshold.</div>
            </div>
            <div class="bg-white rounded-3xl shadow-lg p-8 flex flex-col items-start">
                <div class="text-gray-500 mb-2">Suppliers with Contact</div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $suppliersWithContact }} suppliers</div>
                <div class="text-gray-400">Suppliers ready for fast purchase orders and follow-up.</div>
            </div>
        </div>

                    <h2 class="section-title">Records Overview</h2>

                    <section class="stats-grid">
                        <article class="stat-card">
                            <div>
                                <div class="stat-title">Total Medicines</div>
                                <div class="stat-value">{{ $medicinesCount }}</div>
                            </div>
                            <div class="stat-icon icon-blue">💊</div>
                        </article>
                        <article class="stat-card">
                            <div>
                                <div class="stat-title">Inventory Items</div>
                                <div class="stat-value">{{ $medicinesCount }}</div>
                            </div>
                            <div class="stat-icon icon-green">📦</div>
                        </article>
                        <article class="stat-card">
                            <div>
                                <div class="stat-title">Total Sales</div>
                                <div class="stat-value">{{ $salesCount }}</div>
                            </div>
                            <div class="stat-icon icon-purple">🛒</div>
                        </article>
                        <article class="stat-card">
                            <div>
                    
                    </div>
                </section>

                <h2 class="section-title">Business Highlights</h2>

                <section class="secondary-grid">
                    <article class="insight-card">
                        <h3 class="insight-title">Top Selling Medicines</h3>
                        @if ($topMedicines->isEmpty())
                            <div class="empty-note">No sale history yet. Top-selling medicines will appear here.</div>
                        @else
                            <ul class="insight-list">
                                @foreach ($topMedicines as $item)
                                    <li class="insight-item">
                                        <div>
                                            <strong>{{ $item->medicine_name ?? 'Medicine' }}</strong>
                                            <div class="insight-meta">Revenue:
                                                ₱{{ number_format($item->total_amount, 2) }}</div>
                                        </div>
                                        <span class="chip">{{ $item->total_qty }} sold</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </article>

                    <article class="insight-card">
                        <h3 class="insight-title">Latest Suppliers</h3>
                        @if ($latestSuppliers->isEmpty())
                            <div class="empty-note">No suppliers added yet. Add suppliers to manage procurement.</div>
                        @else
                            <ul class="insight-list">
                                @foreach ($latestSuppliers as $supplier)
                                    <li class="insight-item">
                                        <div>
                                            <strong>{{ $supplier->name }}</strong>
                                            <div class="insight-meta">{{ $supplier->contact ?: 'No contact info' }}
                                            </div>
                                        </div>
                                        <span class="chip">Supplier</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                </section>
            @endif
        </main>
    </div>
</div>
@endsection
