@extends('layouts.app')
@section('title', '4emus Contact')
@section('content')
    {{-- Breadcrumb --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">4emus Contact</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">4emus Contact</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p class="fs-5">
                        4EMUS has a strong focus on delivering work processes and methods to carry out heavy industry
                        related work safely and efficiently. This is not just talk, we have the software and experience
                        to actually deliver efficient and safe work practices where Safety and Production combine.
                    </p>
                    <p class="fs-5">
                        Once a method has been developed to make the work process efficient the safety requirements are
                        implemented to ensure the stringent legal requirements are met.
                        Doing the task efficiently is only a small part of the process often overlooked by heavy industry.
                    </p>
                    <p class="fs-5">
                        Doing the work efficiently and safely has been difficult because there was no simple method to
                        achieve both safety and efficiency.
                    </p>
                    <p class="fs-5">
                        4EMUS was developed from decades of experience in Management, Maintenance Planning, Scheduling and
                        Safety Management. Very rarely do all of these combine effectively.
                    </p>
                    <p class="fs-5 mb-0">
                        This process has been designed and implemented by people who have actually been at the front line
                        for decades and seen the issues most current systems fail to address.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card real-estate-grid-widgets card-animate">
                <div class="card-body p-2">
                    <img src="{{ asset('assets/images/alex.png') }}" alt="img-01.jpg" class="rounded w-100"
                        style="height: 270px">
                </div>
                <div class="card-body p-3">
                    <h6 class="fs-lg text-capitalize text-truncate">Alex Herbertson</h6>
                    <div class="border-top border-dashed mt-2 pt-1">
                        <p class="text-muted mb-0">
                            <i class="bi bi-briefcase align-baseline me-1"></i>
                            Founder,
                            Owner
                        </p>
                        <a href="mailto:alex.herbertson@4emus.com" class="text-muted">
                            <i class="bi bi-envelope align-baseline me-1"></i>
                            alex.herbertson@4emus.com</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card real-estate-grid-widgets card-animate">
                <div class="card-body p-2">
                    <img src="{{ asset('assets/images/rashed.jpg') }}" alt="img-01.jpg" class="rounded w-100"
                        style="height: 250px">
                </div>
                <div class="card-body p-3">
                    <h6 class="fs-lg text-capitalize text-truncate">Md Rashedul Islam</h6>
                    <div class="border-top border-dashed mt-2 pt-1">
                        <p class="text-muted mb-0">
                            <i class="bi bi-briefcase align-baseline me-1"></i>
                            Project Manager, Software Developer
                        </p>
                        <a href="mailto:rashed@4emus.com" class="text-muted">
                            <i class="bi bi-envelope align-baseline me-1"></i>
                            rashed@4emus.com</a>
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
