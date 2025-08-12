@extends('layouts.app')

@section('title', 'View ' . $manual->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">View PDF Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manuals.index') }}">Manuals</a></li>
                        <li class="breadcrumb-item active">{{ $manual->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">{{ $manual->name }}</h4>
                    <p class="card-title-desc mb-0 text-center">Document Category: {{ $manual?->category?->name ?? 'Unspecified' }}</p>
                </div>
                <div class="card-body">
                    <div class="pdf-viewer">
                        @if ($manual && $manual->path !== null)
                            <iframe src="https://koormal-extra.4emus.com/preview-upload-pdfs/{{ basename(parse_url($manual->path, PHP_URL_PATH)) }}"
                                    width="100%" height="800px" style="border:none;"></iframe>
                        @else
                            <div class="alert alert-warning" role="alert">
                                The PDF file could not be found.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
