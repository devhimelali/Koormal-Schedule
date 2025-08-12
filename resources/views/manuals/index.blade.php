@extends('layouts.app')
@section('title', 'Manuals')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Manuals</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('redirect') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Manuals</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h1 class="card-title">Manual List</h1>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-medium" style="min-width: fit-content">Filter by Category</span>
                        <select class="form-select form-select-sm" name="category" id="category">
                            <option value="">Select Category</option>
                            @forelse($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped" id="dataTable">
                            <thead>
                            <th class="th-sn">#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Created At</th>
                            <th>Action</th>
                            </thead>
                        </table>
                        <tbody></tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.css') }}">
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/cdn/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.js') }}"></script>
@endsection
@section('page-script')
    <script>
        $(document).ready(function () {
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('manuals.index')}}",
                    data: function (d) {
                        d.category = $('#category').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: "created_at",
                        name: "created_at"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false
                    }
                ]
            })

            // On category change, reload table with filter
            $('#category').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
@endsection
