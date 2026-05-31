@extends('app')
@section('header')
    <h2 class="header-title">Medicine Details</h2>
    <p class="header-subtitle">View detailed information about a specific medicine in your pharmacy.</p>
@endsection
@section('content')
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2.5rem 2.5rem; max-width: 600px; margin: 0 auto 2.5rem auto;">
        <div style="font-size: 1.5rem; font-weight: 800; color: #16a34a; margin-bottom: 0.5rem;">Paracetamol 500mg</div>
        <div style="color: #64748b; margin-bottom: 1rem;">Pain reliever and fever reducer</div>
        <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem;">
            <div style="font-size: 1.05rem; color: #0284c7;">Stock: <span style="font-weight:700; color:#16a34a;">120</span></div>
            <div style="font-size: 1.05rem; color: #155e75;">Supplier: <span style="font-weight:600;">ABC Pharma</span></div>
        </div>
        <div style="margin-bottom: 1.5rem;">
            <span style="font-size: 1.05rem; color: #374151; font-weight: 600;">Expiry Date:</span> <span style="color: #ef4444; font-weight: 700;">2027-12-31</span>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="#" class="btn btn-primary">Edit</a>
            <form method="POST" action="#" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;">Delete</button>
            </form>
        </div>
    </div>
@endsection
                </div>
            </div>
        </div>
    </div>
@endsection
