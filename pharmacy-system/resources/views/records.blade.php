@extends('app')

@section('header')
    <h2 class="header-title">
        @switch(true)
            @case(request()->is('medicines'))
                Medicines
                @break
            @case(request()->is('inventory'))
                Inventory
                @break
            @case(request()->is('sales'))
                Sales
                @break
            @case(request()->is('suppliers'))
                Suppliers
                @break
            @default
                Records
        @extends('app')

        @section('header')
            <h2 class="header-title">Records</h2>
            <p class="header-subtitle">All main tabs content preview</p>
        @endsection

        @section('content')
        <div style="margin-bottom:2rem;">
            <strong>Dashboard</strong>
            <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
                <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
                    <div style="font-size: 1.1rem; color: #16a34a; font-weight: 700;">Total Medicines</div>
                    <div style="font-size: 2rem; font-weight: 800; margin-top: 0.5rem;">3</div>
                </div>
                <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
                    <div style="font-size: 1.1rem; color: #0284c7; font-weight: 700;">Inventory Items</div>
                    <div style="font-size: 2rem; font-weight: 800; margin-top: 0.5rem;">3</div>
                </div>
                <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
                    <div style="font-size: 1.1rem; color: #0e7490; font-weight: 700;">Total Sales</div>
                    <div style="font-size: 2rem; font-weight: 800; margin-top: 0.5rem;">2</div>
                </div>
                <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
                    <div style="font-size: 1.1rem; color: #155e75; font-weight: 700;">Suppliers</div>
                    <div style="font-size: 2rem; font-weight: 800; margin-top: 0.5rem;">2</div>
                </div>
            </div>
            <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
                <div style="background: #fef9c3; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(251,191,36,0.08); padding: 2rem 2.5rem; min-width: 260px; flex: 1 1 260px; max-width: 320px;">
                    <div style="font-size: 1.1rem; color: #b45309; font-weight: 700; margin-bottom: 0.5rem;">Low Stock Alert</div>
                    <div style="font-size: 1.5rem; font-weight: 700;">Items Below Reorder Level 1</div>
                    <a href="/inventory" style="margin-top: 1rem; color: #0284c7; font-weight: 600; text-decoration: underline;">View Inventory</a>
                </div>
                <div style="background: #e0f2fe; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(14,116,144,0.08); padding: 2rem 2.5rem; min-width: 260px; flex: 1 1 260px; max-width: 320px;">
                    <div style="font-size: 1.1rem; color: #0e7490; font-weight: 700; margin-bottom: 0.5rem;">Sales Summary</div>
                    <div style="font-size: 1.5rem; font-weight: 700;">Total Revenue $20.73</div>
                    <a href="/sales" style="margin-top: 1rem; color: #16a34a; font-weight: 600; text-decoration: underline;">View Sales</a>
                </div>
            </div>
            <div style="margin-bottom: 2.5rem;">
                <div style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">Quick Actions</div>
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <a href="/medicines/create" class="btn btn-primary">Add Medicine</a>
                    <a href="/inventory" class="btn btn-primary">Update Stock</a>
                    <a href="/sales/create" class="btn btn-primary">New Sale</a>
                    <a href="/suppliers/create" class="btn btn-primary">Add Supplier</a>
                </div>
            </div>
        </div>

        <hr style="margin:2rem 0;">

        <strong>Medicines</strong>
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
            <a href="/medicines/create" class="btn btn-primary">Add Medicine</a>
        </div>
        <div style="text-align:center; margin-top:3rem;">
            <div style="font-size:2rem; color:#16a34a; font-weight:800; margin-bottom:1rem;">No medicines found</div>
        </div>

        <hr style="margin:2rem 0;">

        <strong>Inventory</strong>
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
            <a href="/inventory" class="btn btn-primary">Add Stock</a>
        </div>
        <div style="text-align:center; margin-top:3rem;">
            <div style="font-size:2rem; color:#0284c7; font-weight:800; margin-bottom:1rem;">No inventory items found</div>
        </div>

        <hr style="margin:2rem 0;">

        <strong>Sales</strong>
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
            <a href="/sales/create" class="btn btn-primary">New Sale</a>
        </div>
        <div style="display: flex; gap: 2rem; margin-bottom: 2.5rem;">
            <div style="background: #e0f2fe; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(14,116,144,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
                <div style="font-size: 1.1rem; color: #0e7490; font-weight: 700;">Total Revenue</div>
                <div style="font-size: 2rem; font-weight: 800; margin-top: 0.5rem;">$0.00</div>
            </div>
            <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
                <div style="font-size: 1.1rem; color: #16a34a; font-weight: 700;">Transactions</div>
                <div style="font-size: 2rem; font-weight: 800; margin-top: 0.5rem;">0</div>
            </div>
        </div>
        <div style="text-align:center; margin-top:3rem;">
            <div style="font-size:2rem; color:#0e7490; font-weight:800; margin-bottom:1rem;">No sales records found</div>
        </div>

        <hr style="margin:2rem 0;">

        <strong>Suppliers</strong>
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
            <a href="/suppliers/create" class="btn btn-primary">Add Supplier</a>
        </div>
        <div style="text-align:center; margin-top:3rem;">
            <div style="font-size:2rem; color:#155e75; font-weight:800; margin-bottom:1rem;">No suppliers found</div>
        </div>
        @endsection
                    No inventory items found
                    @break
                @case(request()->is('sales'))
                    No sales records found
                    @break
                @case(request()->is('suppliers'))
                    No suppliers found
                    @break
                @default
                    Select a tab to view records
            @endswitch
        </div>
    </div>
@endsection
