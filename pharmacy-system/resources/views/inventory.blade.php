@extends('app')

@section('header')
    <h2 class="header-title">Inventory</h2>
    <p class="header-subtitle">Manage stock levels and batches</p>
@endsection

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
    <a href="/inventory" class="btn btn-primary">Add Stock</a>
</div>
<div style="text-align:center; margin-top:3rem;">
    <div style="font-size:2rem; color:#0284c7; font-weight:800; margin-bottom:1rem;">No inventory items found</div>
</div>
@endsection
