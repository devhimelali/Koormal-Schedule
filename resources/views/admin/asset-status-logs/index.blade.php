@extends('layouts.app')
@section('title', 'Status Logs')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Status Logs</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Status Logs</li>
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
                        <h5 class="card-title mb-0">Schedule List</h5>
                    </div>

                    <div class="flex-shrink-0 d-flex align-items-center gap-2">
                        <div>
                            <select name="asset_no" id="asset_no" class="form-control select2">
                                <option value="">Select Asset Number</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset }}">{{ $asset }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="status" id="new-status" class="form-control select2">
                                <option value="">Select Status</option>
                                <option value="no status yet">No Status Yet</option>
                                <option value="no show">No Show</option>
                                <option value="work underway">Work Underway</option>
                                <option value="mud buildup unsafe">Mud Buildup Unsafe</option>
                                <option value="tagged out – further work found">Tagged Out – Further Work Found</option>
                                <option value="work completed, ready for pickup">Work Completed, Ready for Pickup</option>
                                <option value="late delivery">Late Delivery</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="date-range" class="form-control" id="date-range">
                        </div>
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
                                    <th>Description</th>
                                    <th>Old Status</th>
                                    <th>New Status</th>
                                    <th>Time</th>
                                    <th>Date</th>
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
@endsection
@section('page-script')
    <script>
        $('.select2').select2();

        $('#date-range').flatpickr({
            mode: "range",
            dateFormat: "d-m-Y",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    filterData();
                }
            }
        })

        let type = "{{ request()->query('type') }}";

        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 100,
            ajax: {
                url: "{{ route('status-logs.index') }}" + "?type=" + type,
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
                    data: 'description',
                    name: 'description',
                },
                {
                    data: 'old_status',
                    name: 'old_status',
                },
                {
                    data: 'new_status',
                    name: 'new_status',
                },
                {
                    data: 'change_time',
                    name: 'change_time',
                },
                {
                    data: 'change_date',
                    name: 'change_date',
                }

            ],
        });

        function filterData() {
            let status = $('#new-status').val();
            let asset_no = $('#asset_no').val();
            let date_range = $('#date-range').val();
            table.ajax.url(
                "{{ route('status-logs.index') }}" +
                "?type=" + type +
                "&status=" + status +
                "&asset_no=" + asset_no +
                "&date_range=" + date_range
            ).load();

        }

        $('#new-status, #asset_no, #time_frame').on('change', function() {
            filterData();
        });

        $('#exportPdf').on('click', function() {
            let status = $('#new-status').val();
            let asset_no = $('#asset_no').val();
            let time_frame = $('#date-range').val();

            let url = "{{ route('status-logs.export.pdf') }}" +
                "?type=" + encodeURIComponent(type) +
                "&status=" + encodeURIComponent(status) +
                "&asset_no=" + encodeURIComponent(asset_no) +
                "&time_frame=" + encodeURIComponent(time_frame);

            window.open(url, '_blank');
        });

        $('#exportExcel').on('click', function() {
            let status = $('#new-status').val();
            let asset_no = $('#asset_no').val();
            let time_frame = $('#date-range').val();

            let url = "{{ route('status-logs.export.excel') }}" +
                "?type=" + encodeURIComponent(type) +
                "&status=" + encodeURIComponent(status) +
                "&asset_no=" + encodeURIComponent(asset_no) +
                "&time_frame=" + encodeURIComponent(time_frame);

            window.open(url, '_blank');
        });
    </script>
@endsection
@section('page-style')
    <style>
        .select2-selection__rendered {
            line-height: 36px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@endsection
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/cdn/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
@endsection
