@extends('layouts.app')
@section('title', 'Koormal Contact')
@section('content')
    {{-- Breadcrumb --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Koormal Contact</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Koormal Contact</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-6 mb-4">
            <div class="card real-estate-grid-widgets card-animate">
                <div class="card-body p-2">
                    <img src="{{ asset('assets/images/koormal.png') }}" alt="img-01.jpg" class="rounded w-100"
                        style="height: 220px">
                </div>
                <div class="card-body p-3">
                    <h6 class="fs-lg text-capitalize text-truncate">Koormal Group</h6>
                    <div class="border-top border-dashed mt-2 pt-1">
                        <a href="mailto:siteadmin@koormalmining.com.au" class="text-muted">
                            <i class="bi bi-envelope align-baseline me-1"></i>
                            siteadmin@koormalmining.com.au</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-style')
    <style>
        .min-vh-80 {
            min-height: 80vh;
        }
    </style>
@endsection
