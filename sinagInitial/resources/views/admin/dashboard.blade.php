
@extends('layouts.app')

@section('content')
<div style="background: #ff4d4f; color: #fff; padding: 32px; border-radius: 16px; margin-bottom: 32px; text-align: center; font-weight: bold; font-size: 2rem;">
    TEST BANNER: If you see this, you are viewing <br>resources/views/admin/dashboard.blade.php<br> (remove this banner after confirming)
</div>
@php
    $user = Auth::user();
    $initial = strtoupper(substr($user->name ?? 'A', 0, 1));
    $name = $user->name ?? 'Admin User';
@endphp

<div class="d-flex" style="min-height:100vh;background:#f7f8fa;">
    <!-- Sidebar -->
    <aside class="bg-white border-end" style="width:250px;min-height:100vh;padding-top:1.5rem;">
        <div class="d-flex align-items-center mb-4 px-4">
            <span class="rounded-circle d-flex align-items-center justify-content-center me-2" style="background:#6C63FF;width:36px;height:36px;color:#fff;font-weight:700;font-size:1.2rem;">S</span>
            <span class="fw-bold fs-5" style="letter-spacing:0.03em;">SINAG</span>
        </div>
        <ul class="nav flex-column gap-1 px-2">
            <li class="nav-item">
                <a class="nav-link fw-semibold {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-dark' }}" style="background:{{ request()->routeIs('admin.dashboard') ? '#f3f3ff' : 'transparent' }};border-radius:1rem;color:{{ request()->routeIs('admin.dashboard') ? '#6C63FF' : '#222' }};padding:12px 18px;" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.inbox') ? 'active' : 'text-dark' }}" style="background:{{ request()->routeIs('admin.inbox') ? '#f3f3ff' : 'transparent' }};border-radius:1rem;color:{{ request()->routeIs('admin.inbox') ? '#6C63FF' : '#222' }};padding:12px 18px;" href="{{ route('admin.inbox') }}"><i class="fa-regular fa-file-lines me-2"></i> Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.suggestions') ? 'active' : 'text-dark' }}" style="background:{{ request()->routeIs('admin.suggestions') ? '#f3f3ff' : 'transparent' }};border-radius:1rem;color:{{ request()->routeIs('admin.suggestions') ? '#6C63FF' : '#222' }};padding:12px 18px;" href="{{ route('admin.suggestions') }}"><i class="fa-regular fa-comment-dots me-2"></i> Suggestions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : 'text-dark' }}" style="background:{{ request()->routeIs('admin.users') ? '#f3f3ff' : 'transparent' }};border-radius:1rem;color:{{ request()->routeIs('admin.users') ? '#6C63FF' : '#222' }};padding:12px 18px;" href="{{ route('admin.users') }}"><i class="fa-regular fa-user me-2"></i> Users</a>
            </li>
        </ul>
    </aside>
    <!-- Main Content -->
    <div class="flex-grow-1 d-flex flex-column" style="min-height:100vh;">
        <!-- Topbar -->
        <nav class="navbar navbar-light bg-white shadow-sm px-4 d-flex justify-content-between align-items-center" style="height:64px;">
            <div></div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-primary btn-sm px-3" style="border-radius:1rem;"><i class="fa-regular fa-file-arrow-down me-1"></i> Download Report</button>
                <button class="btn btn-primary btn-sm px-3" style="border-radius:1rem;background:#6C63FF;border:none;"><i class="fa-solid fa-bullhorn me-1"></i> Broadcast Alert</button>
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width:40px;height:40px;font-size:1.2rem;background:#6C63FF;color:#fff;">
                        {{ $initial }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown" style="min-width:200px;">
                        <li class="px-3 py-2 fw-bold">Admin User</li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i> Profile Settings</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-circle-question me-2"></i> Help & Guide</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Content -->
        <main class="flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
                            <div>
                                <div class="fw-bold" style="font-size:2rem;color:#222;"><i class="fa-regular fa-user me-2" style="color:#6C63FF;"></i> Admin Dashboard</div>
                                <div class="text-muted" style="font-size:1.1rem;">Overview of GAD office metrics and student engagement.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;">
                            <div class="card-body text-center">
                                <span class="d-block mb-2" style="font-size:2rem;background:#fff0f0;border-radius:12px;padding:8px 0;color:#ff5e5e;width:48px;margin:0 auto;"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                <div class="fw-bold fs-4">0</div>
                                <div class="text-muted small">ACTIVE CASES</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;">
                            <div class="card-body text-center">
                                <span class="d-block mb-2" style="font-size:2rem;background:#f0fff7;border-radius:12px;padding:8px 0;color:#00b894;width:48px;margin:0 auto;"><i class="fa-regular fa-comment-dots"></i></span>
                                <div class="fw-bold fs-4">0</div>
                                <div class="text-muted small">NEW SUGGESTIONS</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;">
                            <div class="card-body text-center">
                                <span class="d-block mb-2" style="font-size:2rem;background:#f3f3ff;border-radius:12px;padding:8px 0;color:#6366F1;width:48px;margin:0 auto;"><i class="fa-regular fa-file-lines"></i></span>
                                <div class="fw-bold fs-4">0</div>
                                <div class="text-muted small">RESOLVED CASES</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;">
                            <div class="card-body text-center">
                                <span class="d-block mb-2" style="font-size:2rem;background:#f8f0ff;border-radius:12px;padding:8px 0;color:#a259ff;width:48px;margin:0 auto;"><i class="fa-solid fa-chart-line"></i></span>
                                <div class="fw-bold fs-4">0</div>
                                <div class="text-muted small">TOTAL ENGAGEMENT</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;">
                            <div class="card-body">
                                <div class="fw-bold mb-2" style="font-size:1.1rem;"><i class="fa-solid fa-chart-line me-2"></i>Report vs Suggestion Trends</div>
                                <canvas id="trendChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;">
                            <div class="card-body">
                                <div class="fw-bold mb-2" style="font-size:1.1rem;">Recent Activity</div>
                                <div class="text-muted small">No recent activity</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Chart.js for the bar chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [
                {
                    label: 'Reports',
                    data: [3, 9, 18, 27, 15, 21, 19, 24, 22, 30],
                    backgroundColor: '#00b894',
                    borderRadius: 8
                },
                {
                    label: 'Suggestions',
                    data: [2, 3, 7, 4, 6, 8, 2, 5, 9, 12],
                    backgroundColor: '#ff5e5e',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f3ff' }
                }
            }
        }
    });
</script>
@endsection
