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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p class="fs-5 mb-0">
                        Contact Koormal Admin for changes to the email contacts or other schedule related issues.
                        You cannot just move schedules to suit - there are over 150 light vehicles to inspect and service so
                        maintaining a schedule is important. To move one vehicle we generally have to move another.
                        The schedule gives the vehicle owners at least a four weeks notice to organise another vehicle.
                        Production does not come before safety and vehicle inspections are a legal requirement, it is not
                        optional.
                    </p>
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
