@extends('layouts.app')
@section('title', 'Equipment Long Term Downtime')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Equipment Long Term Downtime</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Equipment Long Term Downtime</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main card --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- Body --}}
                <div class="card-body">
                    @if ($pdf && $pdf->file_path !== null)
                        <iframe
                            src="https://koormal-extra.4emus.com/equipment-pdf/{{ basename(parse_url($pdf->file_path, PHP_URL_PATH)) }}"
                            width="100%" height="800px" style="border: none;">
                        </iframe>
                    @else
                        <p class="text-danger text-center py-5 my-3 fw-bold">No PDF available or file not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
