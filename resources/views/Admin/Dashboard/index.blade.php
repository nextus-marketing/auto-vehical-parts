@extends('layouts.admin')
@section('title') Dashboard @endsection
@section('content')
<div class="owl-carousel counter-carousel owl-theme">
    <div class="item">
        <div class="card border-0 zoom-in bg-light-warning shadow-none">
            <div class="card-body">
                <div class="text-center">
                    <img src="/backend/dist/images/svgs/icon-briefcase.svg" width="50" height="50" class="mb-3" alt="" />
                    <p class="fw-semibold fs-3 text-warning mb-1">Total Enquiries</p>
                    <h5 class="fw-semibold text-warning mb-0">{{ $enquiries }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="item">
        <div class="card border-0 zoom-in bg-light-success shadow-none">
            <div class="card-body">
                <div class="text-center">
                    <img src="/backend/dist/images/svgs/icon-speech-bubble.svg" width="50" height="50" class="mb-3" alt="" />
                    <p class="fw-semibold fs-3 text-success mb-1">Employees</p>
                    <h5 class="fw-semibold text-success mb-0">{{ $employees }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection