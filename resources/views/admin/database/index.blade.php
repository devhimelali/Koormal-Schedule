@extends('layouts.app')
@section('title', 'Database Backup')
@section('content')
    {{--  Start breadcrumb  --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Database Backup</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('redirect') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Database Backup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{--  End breadcrumb  --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between  align-items-center">
                    <h4 class="card-title">Database Backup</h4>
                    <a href="{{ route('database.backup.create') }}"
                        class="btn btn-sm btn-secondary d-flex align-items-center">
                        <i class="ph ph-upload me-2"></i>
                        Create Backup
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered align-middle mb-0" id="dataTable">
                            <thead class="table-active">
                                <tr>
                                    <th style="max-width: 50px !important; width: 50px;"> #</th>
                                    <th class="th-name">Database Name</th>
                                    <th style="max-width: 80px; width: 80px !important;">Size</th>
                                    <th style="max-width: 150px; width: 150px !important;">Backup Date</th>
                                    <th style="max-width: 130px; width: 130px !important;">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
        $(document).ready(function() {
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('database.backups.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'filename',
                        name: 'filename',
                    },
                    {
                        data: 'size',
                        name: 'size',
                    },
                    {
                        data: 'backup_at',
                        name: 'backup_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
