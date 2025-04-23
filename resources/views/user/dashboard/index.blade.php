@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card py-5">
                <div class="card-body py-4">
                    <h2 class="text-center">Equipment Schedules and daily live updates of vehicle service status</h2>
                    <div class="text-center pt-5">
                        <img src="{{ asset('assets/images/4emus.png') }}" alt="" class="img-fluid" width="600">
                    </div>
                    <p class="text-center pt-3 fs-5" style="margin-bottom: 4px;">Vehicles are to be delivered by 7am for
                        dayshift inspections and by 7pm
                        for nightshift.</p>
                    <p class="text-center fs-5">All vehicles are to be cleaned before delivery and will not be worked
                        on if there is a risk to the mechanics from the condition of the vehicle.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
