@extends('layout.app')

@section('title', 'Login')

@section('content')

<div class="card ">
    <div class="card-body">
        <form action="">
            @csrf
            <div class="row justify-center">
                <div class="col-lg-3">
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
                <div class="col-lg-3">
                    <div class="input-group mb-3 ">
                        <label class="input-group-text" for="assemblySelect">Assembly</label>
                        <select class="form-select" id="assemblySelect">
                            <option selected>Choose...</option>

                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="searchBox" class="form-control mb-3" placeholder="Search voter...">

                </div>
                <div class="col-lg-2">
                    <button type="button" id="applyfilter" class="btn btn-orange">Apply Filter</button>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn w-100 text-white" style="background: #6c757d;" id="clearFilter">Clear Filter</button>

                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mt-4">
    <div class="card-body">

        <table id="voterTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>EPIC</th>
                    <th>Part</th>
                    <th>Assembly</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

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
                    <div class="row">
                        <div class="mb-2 col-lg-4">
                            <label>Applicant Full Name</label>
                            <input type="text" name="applicant_full_name" id="applicant_full_name" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Applicant First Name</label>
                            <input type="text" name="applicant_first_name" id="applicant_first_name" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Applicant Last Name</label>
                            <input type="text" name="applicant_last_name" id="applicant_last_name" class="form-control">
                        </div>

                        <div class="mb-2 col-lg-4">
                            <label>Age</label>
                            <input type="number" id="age" name="age" class="form-control">
                        </div>

                        <div class="mb-2 col-lg-4">
                            <label>Gender</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Other</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Relation</label>
                            <select id="relation" name="relation" class="form-control">
                                <option value="FTHR">Father</option>
                                <option value="HSBN">Husband</option>
                                <option value="MTHR">Mother</option>
                                <option value="WIFE">Wife</option>
                                <option value="OTHR">Other</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Parts</label>
                            <select id="part_id" name="part_id" class="form-control">
                                @foreach($userParts as $value)
                                <option value="{{ $value->part_id }}">
                                    {{ $value->part_id }} - {{ $value->booth_address }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Assembly</label>
                            <select id="ac_id" name="acid" class="form-control">

                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Relation Full Name</label>
                            <input type="text" name="realtion_full_name" id="realtion_full_name" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Relation Last Name</label>
                            <input type="text" name="realtion_last_name" id="realtion_last_name" class="form-control">
                        </div>

                        <div class="mb-2 col-lg-4">
                            <label>EPIC</label>
                            <input type="text" id="epic_number" name="epic_number" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>V Address</label>
                            <input type="text" id="v_address" name="v_address" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Booth Address</label>
                            <input type="text" id="booth_address" name="booth_address" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Mobile</label>
                            <input type="number" maxlength="10" id="mobile" name="mobile" class="form-control">
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Statuses</label>
                            <select id="status" name="status" class="form-control">
                                @foreach($statuses as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Colors</label>
                            <select id="color" name="color" class="form-control">
                                @foreach($colors as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Religions</label>
                            <select id="religions" name="religions" class="form-control">
                                @foreach($religions as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Occupations</label>
                            <select id="occupations" name="occupations" class="form-control">
                                @foreach($occupations as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Castes</label>
                            <select id="castes" name="castes" class="form-control">
                                @foreach($castes as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Educations</label>
                            <select id="educations" name="educations" class="form-control">
                                @foreach($educations as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label>Languages</label>
                            <select id="languages" name="languages" class="form-control">
                                @foreach($languages as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4 ">
                            <label>Visited Status</label>

                            <div class="d-flex">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="visited_status" id="flexRadioDefault1" checked value="1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Visited
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="visited_status" id="flexRadioDefault2" value="0">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Not Visited
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-4 ">
                            <label>Status 1</label>

                            <div class="d-flex">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="status1" id="accepted" checked value="1">
                                    <label class="form-check-label" for="accepted">
                                        Accepted
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status1" id="notice" value="0">
                                    <label class="form-check-label" for="notice">
                                        Notice
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-4 ">

                            <label>Status 2</label>
                            <div class="d-flex">

                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="status2" id="accepted2" checked value="1">
                                    <label class="form-check-label" for="accepted2">
                                        Accepted
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status2" id="rejected" value="0">
                                    <label class="form-check-label" for="rejected">
                                        Rejected
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-4 ">

                            <label>Status 3</label>
                            <div class="d-flex">

                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="status3" id="apeal_1" checked value="1">
                                    <label class="form-check-label" for="apeal_1">
                                        Apeal 1
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status3" id="rejected_3" value="0">
                                    <label class="form-check-label" for="rejected_3">
                                        Rejected
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-4 ">

                            <label>Status 3</label>
                            <div class="d-flex">

                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="status4" id="apeal_2" checked value="1">
                                    <label class="form-check-label" for="apeal_2">
                                        Apeal 2
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status4" id="final_rejected_3" value="0">
                                    <label class="form-check-label" for="final_rejected_3">
                                        Final Rejected
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
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