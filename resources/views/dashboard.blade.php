@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">

    <!-- Parts Card -->
    <div class="col-lg-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h5 class="mb-0">Parts Overview</h5>
            </div>
            <div class="card-body">

                <div class="row text-center">

                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3">
                            <h6>Total</h6>
                            <h4 class="fw-bold text-primary">{{ $taotalPart }}</h4>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 bg-success text-white rounded-3">
                            <h6>Assigned</h6>
                            <h4 class="fw-bold">{{ $assignedPart }}</h4>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 bg-danger text-white rounded-3">
                            <h6>Unassigned</h6>
                            <h4 class="fw-bold">{{ $unassignedPart }}</h4>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Assembly Card -->
    <div class="col-lg-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4">
                <h5 class="mb-0">Assembly Overview</h5>
            </div>
            <div class="card-body">

                <div class="row text-center">

                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3">
                            <h6>Total</h6>
                            <h4 class="fw-bold text-dark">{{ $taotalAssemble }}</h4>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 bg-success text-white rounded-3">
                            <h6>Assigned</h6>
                            <h4 class="fw-bold">{{ $assignedAssem }}</h4>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 bg-danger text-white rounded-3">
                            <h6>Unassigned</h6>
                            <h4 class="fw-bold">{{ $unassignedAssem }}</h4>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body align-middle">
                <img src="assets/images/sena.png" alt="Logo" class="login-logo-img">
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow border-0 rounded-4 h-100">
            <div class="card-header bg-secondary  text-white rounded-top-4">
                <h5 class="mb-0">Voter Overview</h5>
            </div>
            <div class="card-body">

                <div class="row text-center">

                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3">
                            <h6>Total</h6>
                            <h4 class="fw-bold text-dark">{{ $totalVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 bg-success text-white rounded-3">
                            <h6>Visited</h6>
                            <h4 class="fw-bold">{{ $VisitedVoter }}</h4>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 bg-danger text-white rounded-3">
                            <h6>Not Visited</h6>
                            <h4 class="fw-bold">{{ $NotVisitedVoter }}</h4>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection