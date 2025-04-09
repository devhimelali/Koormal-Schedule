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
                </div>
            </div>
        </div>
    </div>
@endsection
