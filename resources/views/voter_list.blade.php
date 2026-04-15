@extends('layout.app')

@section('title', 'Login')

@section('content')

<div class="card ">
    <div class="card-body">
        <form action="">
            @csrf
            <div class="row justify-center">
                <div class="col-lg-4">
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
                <div class="col-lg-4">
                    <div class="input-group mb-3 ">
                        <label class="input-group-text" for="assemblySelect">Assembly</label>
                        <select class="form-select" id="assemblySelect">
                            <option selected>Choose...</option>

                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <button type="button" id="AddFiletr" class="btn btn-orange" onclick="loadVoters();">Apply Filter</button>
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn w-100" style="background: #6c757d;" onclick="clearFilter();">Clear Filter</button>

                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mt-4">
    <div class="card-body">
        <input type="text" id="searchBox" class="form-control mb-3" placeholder="Search voter...">

        <!-- <div class="row" id="voterGrid"></div> -->
        <div class="row" id="voterGrid" style="height:500px; overflow:auto;"></div>

        <!-- Loader -->
        <div id="loader" class="text-center my-3" style="display:none;">
            <span>Loading...</span>
        </div>
    </div>
</div>
<script>
    let partAssemblyData = @json($userPartsGrouped);
</script>
@endsection