@extends('layout.app')

@section('title', 'Login')

@section('content')

<div class="login-container">
    <div class="login-card shadow">

        <!-- Logo -->
        <div class="logo">
            <img src="assets/images/sena.png" alt="Logo" class="login-logo-img">

        </div>

        <h3 class="font-weight">Login to Role <br> Management System</h3>
        @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
        @endif
        <form id="MobileEnterForm">
            @csrf
            <div class="mb-3 text-start">
                <label for="mobile_no" class="form-label">Mobile Number</label>
                <input type="number" class="form-control" id="mobile_no" maxlength="10" placeholder="Enter 10-digit mobile number">
            </div>
            <button type="submit" id="MobileVerfication" class="btn btn-orange">Continue</button>
        </form>
        <form id="LoginForm">
            @csrf
            <div class="mb-3 text-start">
                <label for="mobile_no" class="form-label">Mobile Number</label>
                <input type="number" class="form-control" id="mobile_no" maxlength="10" placeholder="Enter 10-digit mobile number" value="{{session('mobile_number')}}">
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password">
            </div>
            <button type="submit" id="LoginBtn" class="btn btn-orange">Submit</button>
            <button type="button" class="btn w-100" style="background: #6c757d; margin-top: 10px;" onclick="backfunction();">Back</button>


        </form>
        <form id="VerifyOtpForm">
            @csrf
            <input type="hidden" id="mob_no" value="{{session('mobile_number')}}">
            <div class="mb-3 text-start">
                <label for="mobile_no" class="form-label">Mobile Number</label>
                <input type="number" class="form-control" readonly id="mobile_no" placeholder="Enter 10-digit mobile number" value="{{session('mobile_number')}}">
            </div>
            <div class="mb-3 text-start">
                <label for="otp_code" class="form-label">OTP Code</label>
                <input type="number" class="form-control" id="otp_code" maxlength="4" placeholder="Enter 4-digit OTP">
            </div>
            <button type="submit" id="OtpMobileVerfication" class="btn btn-orange">Verify</button>
            <button type="button" class="btn w-100" style="background: #6c757d; margin-top: 10px;" onclick="backfunction();">Back</button>

        </form>

    </div>
    @endsection