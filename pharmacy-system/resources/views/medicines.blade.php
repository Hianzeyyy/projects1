@extends('app')

@section('header')
    <h2 class="header-title">Medicines</h2>
    <p class="header-subtitle">Manage your medicine catalog</p>
@endsection

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
    <a href="/medicines/create" class="btn btn-primary">Add Medicine</a>
</div>
<div style="text-align:center; margin-top:3rem;">
    <div style="font-size:2rem; color:#16a34a; font-weight:800; margin-bottom:1rem;">No medicines found</div>
</div>
@endsection
