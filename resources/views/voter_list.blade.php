@extends('layout.app')

@section('title', 'Voter List')

@section('content')

<div class="card ">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center mb-3">
                <h5 class="mb-1 me-5 fw-bold text-primary">
                    Part: <span class="text-dark">{{$userMappingIds->parts->booth_address}}</span>
                </h5>
                <h5 class="mb-1 fw-bold text-primary">
                    Assembly: <span class="text-dark">{{$userMappingIds->assembly->name}}</span>
                </h5>
        </div>
        <form action="">
            @csrf
            <div class="row justify-center g-2">
                <input type="hidden" name="partSelect" id="partSelect" value="{{$userMappingIds->part_id}}">
                <input type="hidden" name="assemblySelect" id="assemblySelect" value="{{$userMappingIds->acid}}">
                <!-- <div class="col-lg-4 col-xl-3">
                    <div class="input-group mb-3 ">
                        <label class="input-group-text" for="partSelect">Parts</label>
                        <select class="form-select" id="partSelect">
                            <option selected>Choose...</option>
                            @foreach($userParts as $value)
                            <option value="{{ $value->part_id }}">
                                {{ $value->part_id }} - {{ $value->booth_address }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="input-group mb-3 ">
                        <label class="input-group-text" for="assemblySelect">Assembly</label>
                        <select class="form-select" id="assemblySelect">
                            <option selected>Choose...</option>

                        </select>
                    </div>
                </div> -->
                <div class="col-lg-4 col-xl-3 col-md-6">
                    <input type="text" id="searchBox" class="form-control mb-3" placeholder="Search voter...">

                </div>
                <div class="col-lg-2 col-md-2">
                    <button type="button" id="applyfilter" class="btn btn-orange">Apply Filter</button>
                </div>
                <div class="col-lg-2 col-md-2">
                    <button type="button" class="btn w-100 text-white" style="background: #6c757d;" id="clearFilter">Clear Filter</button>

                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mt-4 d-none" id="createFamilyDiv">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-4 col-xl-3 col-md-6 ">
                <div class="custom-select-wrapper">
                    <label class="custom-label" for="family_mobile">Mobile<span class="text-danger">*</span></label>
                    <input type="number" maxlength="10" id="family_mobile" name="family_mobile" class="form-control">
                    <div id="family_mobile_error"></div>
                </div>
            </div>
            <div class=" col-lg-4 col-xl-3 col-md-6  ">
                <div class="custom-select-wrapper">
                    <label class="custom-label" for="family_religions">Religions<span class="text-danger">*</span></label>
                    <select id="family_religions" name="family_religions" class="form-control normalselect2">
                        <option value="">Select</option>
                        @foreach($religions as $value)
                        <option value="{{ $value->id }}">
                            {{ $value->name }}
                        </option>
                        @endforeach
                    </select>
                    <div id="family_religions_error"></div>
                </div>

            </div>
            <div class=" col-lg-4 col-xl-3 col-md-6 ">
                <div class="custom-select-wrapper">

                    <label class="custom-label" for="family_visited_date">Visited Date</label>
                    <input type="date" id="family_visited_date" name="family_visited_date" class="form-control ">
                    <div id="family_visited_date_error"></div>
                </div>
            </div>
            <div class=" col-lg-4 col-xl-3 col-md-6 ">
                <div class="custom-select-wrapper">

                    <label class="custom-label" for="family_visited_time">Visited Time</label>
                    <input type="time" id="family_visited_time" name="family_visited_time" class="form-control ">
                    <div id="family_visited_time_error"></div>
                </div>
            </div>
            <div class=" col-lg-4 col-xl-3 col-md-6 ">
                <div class="custom-select-wrapper">

                    <label class="custom-label" for="family_visited_location">Visited Location</label>
                    <input type="text" id="family_visited_location" name="family_visited_location" class="form-control ">
                    <div id="family_visited_location_error"></div>
                </div>
            </div>
            <div class=" col-lg-3 col-xl-2 col-md-2 align-content-center">
                <button type="button" id="CreateFamily" class="btn btn-blue">Create Family</button>
            </div>
        </div>
    </div>
</div>
<div class="card mt-4">
    <div class="card-body">

        <div class="table-responsive">
            <table id="voterTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Voter ID</th>
                        <th>Voter Name</th>
                        <th>Relation</th>
                        <th>Relative Full Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>
<div class="modal fade modal-xl" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Voter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="UpdateVoter" method="post">
                    @csrf
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="row g-4">
                        <div class=" col-lg-4 col-xl-3 col-md-6">
                            <div class="custom-select-wrapper">
                                <label class="custom-label" for="applicant_full_name">Applicant Full Name</label>
                                <input type="text" disabled name="applicant_full_name" id="applicant_full_name" class="form-control">
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6 ">
                            <div class="custom-select-wrapper">
                                <label class="custom-label" for="dob">DOB<span class="text-danger">*</span></label>
                                <input type="date" id="dob" name="dob" class="form-control ">
                                <div id="dob_error"></div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6">
                            <div class="custom-select-wrapper">
                                <label abel class="custom-label" for="age">Age<span class="text-danger">*</span></label>
                                <input type="number" id="age" name="age" class="form-control">
                                <div id="age_error"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-3 col-md-6 ">
                            <div class="custom-select-wrapper">
                                <label class="custom-label" for="mobile">Mobile<span class="text-danger">*</span></label>
                                <input type="number" maxlength="10" id="mobile" name="mobile" class="form-control">
                                <div id="mobile_error"></div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6  ">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="status">Statuses<span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($statuses as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="status_error"></div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6  ">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="color">Colors<span class="text-danger">*</span></label>
                                <select id="color" name="color" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($colors as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="color_error"></div>
                            </div>

                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6  ">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="religions">Religions<span class="text-danger">*</span></label>
                                <select id="religions" name="religions" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($religions as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="religions_error"></div>
                            </div>

                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6  ">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="occupations">Occupations<span class="text-danger">*</span></label>
                                <select id="occupations" name="occupations" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($occupations as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="occupations_error"></div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="castes">Castes<span class="text-danger">*</span></label>
                                <select id="castes" name="castes" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($castes as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="castes_error"></div>
                            </div>

                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6  ">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="educations">Educations<span class="text-danger">*</span></label>
                                <select id="educations" name="educations" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($educations as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="educations_error"></div>
                            </div>

                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6 ">
                            <div class="custom-select-wrapper">

                                <label class="custom-label" for="languages">Languages<span class="text-danger">*</span></label>
                                <select id="languages" name="languages" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($languages as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="languages_error"></div>
                            </div>

                        </div>
                        <div class=" col-lg-4 col-xl-3 col-md-6  ">

                            <label>Visited Status</label>

                            <div class="d-flex">
                                <div class="me-3 status-group">
                                    <input class="form-check-input" type="radio" name="visited_status" id="flexRadioDefault1" value="1">
                                    <label class="form-check-label status-card" for="flexRadioDefault1">
                                        Visited
                                    </label>
                                </div>
                                <div class="status-group">
                                    <input class="form-check-input" type="radio" name="visited_status" id="flexRadioDefault2" value="0" checked>
                                    <label class="form-check-label status-card" for="flexRadioDefault2">
                                        Not Visited
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="VisitedDiv d-none col-lg-12 col-md-12 row g-3">
                            <div class=" col-lg-4 col-xl-3 col-md-6 ">
                                <div class="custom-select-wrapper">

                                    <label class="custom-label" for="visited_date">Visited Date</label>
                                    <input type="date" id="visited_date" name="visited_date" class="form-control ">
                                    <div id="visited_date_error"></div>
                                </div>
                            </div>
                            <div class=" col-lg-4 col-xl-3 col-md-6 ">
                                <div class="custom-select-wrapper">

                                    <label class="custom-label" for="visited_time">Visited Time</label>
                                    <input type="time" id="visited_time" name="visited_time" class="form-control ">
                                    <div id="visited_time_error"></div>
                                </div>
                            </div>
                            <div class=" col-lg-4 col-xl-3 col-md-6 ">
                                <div class="custom-select-wrapper">

                                    <label class="custom-label" for="visited_location">Visited Location</label>
                                    <input type="text" id="visited_location" name="visited_location" class="form-control ">
                                    <div id="visited_location_error"></div>
                                </div>
                            </div>

                        </div>
                        <div class=" col-lg-4 col-xl-3 ">
                            <label>Status 1</label>

                            <div class="d-flex">
                                <div class="me-3 status-group">
                                    <input class="form-check-input" type="radio" name="status1" id="accepted" checked value="1">
                                    <label class="form-check-label status-card" for="accepted">
                                        Accepted
                                    </label>
                                </div>
                                <div class="status-group">
                                    <input class="form-check-input" type="radio" name="status1" id="notice" value="0">
                                    <label class="form-check-label status-card" for="notice">
                                        Notice
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 ">

                            <label>Status 2</label>
                            <div class="d-flex">

                                <div class="me-3 status-group">
                                    <input class="form-check-input" type="radio" name="status2" id="accepted2" checked value="1">
                                    <label class="form-check-label status-card" for="accepted2">
                                        Accepted
                                    </label>
                                </div>
                                <div class="status-group">
                                    <input class="form-check-input" type="radio" name="status2" id="rejected" value="0">
                                    <label class="form-check-label status-card" for="rejected">
                                        Rejected
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 ">

                            <label>Status 3</label>
                            <div class="d-flex">

                                <div class="me-3 status-group">
                                    <input class="form-check-input" type="radio" name="status3" id="apeal_1" checked value="1">
                                    <label class="form-check-label status-card" for="apeal_1">
                                        Apeal 1
                                    </label>
                                </div>
                                <div class="status-group">
                                    <input class="form-check-input" type="radio" name="status3" id="rejected_3" value="0">
                                    <label class="form-check-label status-card" for="rejected_3">
                                        Rejected
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-xl-3 ">

                            <label>Status 3</label>
                            <div class="d-flex">

                                <div class="me-3 status-group">
                                    <input class="form-check-input" type="radio" name="status4" id="apeal_2" checked value="1">
                                    <label class="form-check-label status-card" for="apeal_2">
                                        Apeal 2
                                    </label>
                                </div>
                                <div class="status-group">
                                    <input class="form-check-input" type="radio" name="status4" id="final_rejected_3" value="0">
                                    <label class="form-check-label status-card" for="final_rejected_3">
                                        Final Rejected
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer mt-2">
                        <button class="btn btn-success" type="submit" id="updateBtn">Update</button>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>
<script>
    let partAssemblyData = @json($userPartsGrouped);
</script>
@endsection