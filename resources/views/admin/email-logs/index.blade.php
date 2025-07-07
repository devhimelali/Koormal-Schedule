@extends('layouts.app')
@section('title', 'Email Logs')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Email Logs</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Email Logs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">Email Log List</h5>
                    </div>
                    <div class="flex-shrink-0 d-flex align-items-center gap-2">
                        <div>
                            <button type="button" class="btn btn-danger" id="exportPdf">
                                <i class="ri-file-pdf-2-line"></i> PDF
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success" id="exportExcel">
                                <i class="ri-file-excel-2-line"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered align-middle mb-0" id="dataTable">
                            <thead class="table-active">
                                <tr>
                                    <th>#</th>
                                    <th>Asset Number</th>
                                    <th>Next Due Date</th>
                                    <th style="min-width: 160px; width: 160px !important;">Recipient Email</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!--end table-responsive-->
                </div>
            </div>
        </div>
    </div>

    <!-- Email Log Details Modal -->
    <div id="emailLogDetailsModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light py-2">
                    <h5 class="modal-title" id="myModalLabel">Email Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('page-script')
    <script>
        let type = "{{ request()->query('type') }}";

        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 100,
            ajax: {
                url: "{{ route('email-logs.index') }}" + "?type=" + type,
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'asset_no',
                    name: 'asset_no'
                },
                {
                    data: 'next_due_date',
                    name: 'next_due_date'
                },
                {
                    data: 'recipient_email',
                    name: 'recipient_email'
                },
                {
                    data: 'sent_time',
                    name: 'sent_time',
                },
                {
                    data: 'sent_date',
                    name: 'sent_date',
                },
                {
                    data: 'is_sent',
                    name: 'is_sent',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
        });

        $(document).on('click', '.view-details', function() {
            let id = $(this).data('id');
            let url = "{{ route('email-logs.show', ':id') }}";
            url = url.replace(':id', id);
            $.get(url, function(data) {
                $('#emailLogDetailsModal .modal-body').html(data);
                $('#emailLogDetailsModal').modal('show');
            });
        });

        $('#exportPdf').on('click', function() {
            let url = "{{ route('email-logs.export.pdf') }}" +
                "?type=" + encodeURIComponent(type);

            window.open(url, '_blank');
        });

        $('#exportExcel').on('click', function() {
            let url = "{{ route('email-logs.export.excel') }}" +
                "?type=" + encodeURIComponent(type);

            window.open(url, '_blank');
        });
    </script>
@endsection
@section('page-style')

@endsection
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.css') }}">
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/cdn/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.js') }}"></script>
@endsection
