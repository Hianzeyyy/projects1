@extends('app')

@section('header')
    <h2 class="header-title">Sales</h2>
    <p class="header-subtitle">Track and manage sales transactions</p>
@endsection

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
    <a href="/sales/create" class="btn btn-primary">New Sale</a>
</div>
<div style="display: flex; gap: 2rem; margin-bottom: 2.5rem;">
    <div style="background: #e0f2fe; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(14,116,144,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
        <div style="font-size: 1.1rem; color: #0e7490; font-weight: 700;">Total Revenue $0.00</div>
    </div>
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
        <div style="font-size: 1.1rem; color: #16a34a; font-weight: 700;">0 transactions</div>
    </div>
</div>
<div style="text-align:center; margin-top:3rem;">
    <div style="font-size:2rem; color:#0e7490; font-weight:800; margin-bottom:1rem;">No sales records found</div>
</div>
@endsection
