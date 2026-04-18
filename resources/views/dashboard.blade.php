@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">

    <!-- Parts Card -->
    <div class="col-lg-12">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="card-header bg-gradient-primary text-white py-3 px-4">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-geo-alt-fill me-2"></i> Polling Booth Overview
                </h5>
            </div>

            <div class="card-body p-4">

                <!-- Part & Assembly -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="text-muted mb-1">Part</h6>
                        <h5 class="fw-bold text-dark">
                            {{$userMappingIds->parts->booth_address}}
                        </h5>
                    </div>

                    <div>
                        <h6 class="text-muted mb-1">Assembly</h6>
                        <h5 class="fw-bold text-dark">
                            {{$userMappingIds->assembly->name}}
                        </h5>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row g-3 text-center">

                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-card stat-primary">
                            <i class="bi bi-people-fill"></i>
                            <h6>Total</h6>
                            <h4>{{ $totalVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-card stat-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <h6>Visited</h6>
                            <h4>{{ $VisitedVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-card stat-danger">
                            <i class="bi bi-x-circle-fill"></i>
                            <h6>Unvisited</h6>
                            <h4>{{ $NotVisitedVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-card stat-info">
                            <i class="bi bi-link-45deg"></i>
                            <h6>Mapped</h6>
                            <h4>{{ $MappedVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-card stat-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <h6>Non-Mapped</h6>
                            <h4>{{ $NonMappedVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="stat-card stat-purple">
                            <i class="bi bi-house-fill"></i>
                            <h6>Family</h6>
                            <h4>{{ $FamilyVoter }}</h4>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body align-middle">
                <img src="{{asset('assets/images/sena.png')}}" alt="Logo" class="dashboard-img">
            </div>
        </div>
    </div>
</div>

@endsection