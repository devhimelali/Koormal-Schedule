@extends('layouts.app')
@section('title', 'Technicians')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Technicians</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Technicians</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Today Asset List</h5>
                    <div>
                        <p class="mb-1"><span class="fw-semibold mb-0">Status:</span></p>
                        <ul class="unstyled-list list-inline mb-0 d-flex align-items-center gap-2">
                            <li>
                                <span
                                    style="background-color: #ffffff; padding: 4px 8px; border-radius: 2px; border: 1px solid #000;">Not
                                    yet
                                    touched</span>
                            </li>
                            <li>
                                <span
                                    style="background-color: #ff00ff; padding: 4px 8px; border-radius: 2px;border: 1px solid #000;">No
                                    show</span>
                            </li>
                            <li>
                                <span
                                    style="background-color: #ffff00; padding: 4px 8px; border-radius: 2px;border: 1px solid #000;">Work
                                    underway</span>
                            </li>
                            <li>
                                <span
                                    style="background-color: #ff0000; padding: 4px 8px; border-radius: 2px;border: 1px solid #000;">Tagged
                                    out –
                                    further work found</span>
                            </li>
                            <li>
                                <span
                                    style="background-color: #00ffff; padding: 4px 8px; border-radius: 2px;border: 1px solid #000;">Work
                                    completed,
                                    ready for pickup</span>
                            </li>
                            <li>
                                <span
                                    style="background-color: #00ff00; padding: 4px 8px; border-radius: 2px;border: 1px solid #000;">Delivered</span>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered align-middle mb-0" id="dataTable">
                            <thead class="table-active">
                                <tr>
                                    <th>#</th>
                                    <th>Asset Number</th>
                                    <th>Description</th>
                                    <th>Department</th>
                                    <th>Next Due Date</th>
                                    @role('admin|technician')
                                        <th>Action</th>
                                    @endrole
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

    <div id="statusModal" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('technicians.change.status') }}" method="post" id="statusForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="scheduleId">
                        <div class="mb-3">
                            <label for="scheduleStatus" class="form-label">Status</label>
                            <select class="form-select" id="scheduleStatus" name="status">
                                <option value="not yet touched">Not yet touched</option>
                                <option value="no show">No show</option>
                                <option value="work underway">Work underway</option>
                                <option value="tagged out – further work found">Tagged out – further work found</option>
                                <option value="work completed, ready for pickup">Work completed, ready for pickup</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
            let isAdmin = @json(auth()->user()->hasRole('admin'));
            let isTechnician = @json(auth()->user()->hasRole('technician'));
            let url = "{{ route('technicians.index') }}";
            if (isTechnician) {
                url = "{{ route('technicians.index.confirm') }}";
            }

            let columns = [{
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
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'next_due_date',
                    name: 'next_due_date'
                },
            ];

            if (isAdmin || isTechnician) {
                columns.push({
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                });
            }

            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                },
                columns: columns,
                createdRow: function(row, data, dataIndex) {
                    let status = data.status?.toLowerCase();
                    switch (status) {
                        case 'delivered':
                            $(row).css('background-color', '#00ff00');
                            $(row).find('td').css('color', '#ffffff');
                            break;
                        case 'work underway':
                            $(row).css('background-color', '#ffff00');
                            $(row).find('td').css('color', '#ffffff');
                            break;
                        case 'tagged out – further work found':
                            $(row).css('background-color', '#ff0000');
                            $(row).find('td').css('color', '#ffffff');
                            break;
                        case 'work completed, ready for pickup':
                            $(row).css('background-color', '#00ffff');
                            $(row).find('td').css('color', '#000');
                            break;
                        case 'no show':
                            $(row).css('background-color', '#ff00ff');
                            $(row).find('td').css('color', '#ffffff');
                            break;
                        case 'not yet touched':
                        default:
                            $(row).css('background-color', '#ffffff');
                    }
                }
            });

            $('body').on('click', '.changeStatus', function() {
                let id = $(this).data('id');
                $('#scheduleId').val(id);
                $('#scheduleStatus').val($(this).data('status'));
                $('#statusModal').modal('show');
            });

            $('#statusForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = $(this).attr('action');
                let method = $(this).attr('method');
                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#submitBtn').attr('disabled', true);
                        $('#submitBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            table.ajax.reload();
                            $('#statusModal').modal('hide');
                        }
                    },
                    error: function(response) {
                        notify('error', response.responseJSON.message);
                    },
                    complete: function() {
                        $('#submitBtn').attr('disabled', false);
                        $('#submitBtn').html('Update');
                    }
                });
            });
        });
    </script>
@endsection
