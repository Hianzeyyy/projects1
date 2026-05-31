@extends('app')

@section('header')
    <h2 class="header-title">Suppliers</h2>
    <p class="header-subtitle">Manage supplier contacts and information</p>
@endsection

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
    <a href="/suppliers/create" class="btn btn-primary">Add Supplier</a>
</div>
<div style="text-align:center; margin-top:3rem;">
    <div style="font-size:2rem; color:#155e75; font-weight:800; margin-bottom:1rem;">No suppliers found</div>
</div>
@endsection
