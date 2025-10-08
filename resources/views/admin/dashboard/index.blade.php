@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('content')
<main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Dashboard</h1>
                <p class="text-muted mb-0">Welcome back! Here's what's happening.</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn" style="background-color: #BFFF00;">
                    <i class="bi bi-plus-lg me-2"></i>
                    New Item
                </button>
                <button type="button" class="btn btn-outline-secondary" 
                        data-bs-toggle="tooltip" 
                        title="Refresh data">
                    <i class="bi-arrow-clockwise icon-hover"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" 
                        data-bs-toggle="tooltip" 
                        title="Export data">
                    <i class="bi bi-download icon-hover"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" 
                        data-bs-toggle="tooltip" 
                        title="Settings">
                    <i class="bi bi-gear icon-hover"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-lg-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Total Users</h6>
                                <h3 class="mb-0">12,426</h3>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up"></i> +12.5%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Revenue</h6>
                                <h3 class="mb-0">$54,320</h3>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up"></i> +8.2%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-bag-check"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Orders</h6>
                                <h3 class="mb-0">1,852</h3>
                                <small class="text-danger">
                                    <i class="bi bi-arrow-down"></i> -2.1%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Avg. Response</h6>
                                <h3 class="mb-0">2.3s</h3>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up"></i> +5.4%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Revenue Overview</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-chart-period="7d">7D</button>
                            <button type="button" class="btn btn-outline-primary" data-chart-period="30d">30D</button>
                            <button type="button" class="btn btn-outline-primary" data-chart-period="90d">90D</button>
                            <button type="button" class="btn btn-outline-primary" data-chart-period="1y">1Y</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            <div class="activity-item">
                                <div class="activity-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">New user registered</p>
                                    <small class="text-muted">2 minutes ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-bag-check"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">Order #1234 completed</p>
                                    <small class="text-muted">5 minutes ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">Server maintenance scheduled</p>
                                    <small class="text-muted">1 hour ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
