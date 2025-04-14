@extends('layouts.app')
@section('title', 'Technicians')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Today Assets List</h4>
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
                <div class="card-header">
                    <div class="row align-items-center">
                        <!-- Left Side: Buttons -->
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-sm btn-primary" id="loadTodayWorks">
                                    <i class="ri-refresh-line align-bottom"></i>
                                    Today's Work
                                </button>
                                <button class="btn btn-sm btn-secondary" id="addAsset">
                                    <i class="ri-add-line align-bottom"></i>
                                    Add Asset
                                </button>
                            </div>
                        </div>

                        <!-- Right Side: Status Legend -->
                        <div class="col-12 col-md-9">
                            <ul class="list-inline mb-0 d-flex flex-wrap gap-2 justify-content-md-end">
                                <li>
                                    <span class="badge-status"
                                        style="background-color: #ffffff; border: 1px solid #000;">Not yet touched</span>
                                </li>
                                <li>
                                    <span class="badge-status"
                                        style="background-color: #00ff00; border: 1px solid #000;">Delivered</span>
                                </li>
                                <li>
                                    <span class="badge-status" style="background-color: #ff00ff; border: 1px solid #000;">No
                                        show</span>
                                </li>
                                <li>
                                    <span class="badge-status"
                                        style="background-color: #ffff00; border: 1px solid #000;">Work underway</span>
                                </li>
                                <li>
                                    <span class="badge-status"
                                        style="background-color: #ff0000; border: 1px solid #000;">Tagged out – further work
                                        found</span>
                                </li>
                                <li>
                                    <span class="badge-status"
                                        style="background-color: #00ffff; border: 1px solid #000;">Work completed, ready for
                                        pickup</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Optional CSS for cleaner styling -->
                <style>
                    .badge-status {
                        padding: 2px 8px;
                        border-radius: 2px;
                        display: inline-block;
                        font-size: 0.75rem;
                        white-space: nowrap;
                    }
                </style>

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
                                        <th style="max-width: 355px; width: 355px;">Action</th>
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
    <!-- Email Modal Start -->
    <div id="sendEmailModal" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('technician.send.email') }}" method="post" id="sendEmailForm">
                    @csrf
                    <input type="hidden" name="status" id="status">
                    <input type="hidden" name="asset_no" id="asset_no">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="emails" class="form-label">Email Address</label>
                            <select name="emails[]" id="emails" class="form-control" multiple></select>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject"
                                placeholder="Enter email subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="ckeditor-classic form-control" name="message"></textarea>
                            <div id="message"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="sendEmailBtn">Send Email</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Email Modal End -->
    <!-- Asset Modal Start -->
    <div id="addAssetModal" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Create a New Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('technician.add.asset') }}" method="post" id="addAssetForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="method">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="asset_no" class="form-label">Asset Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="asset_no" name="asset_no"
                                placeholder="Enter asset number">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-2">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department"
                                placeholder="Enter department">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-2">
                            <label for="next_due_date" class="form-label">Next Due Date</label>
                            <input type="text" class="form-control" id="next_due_date" name="next_due_date">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div>
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="7"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addAssetSubmitBtn">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Asset Modal End -->
    <!-- Asset Delete Modal Start -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>

                <form class="tablelist-form" action="" method="POST" id="deleteForm">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <div class="modal-body p-4">
                        <p id="deleteMessage">Are you sure you want to delete this asset?</p>
                    </div>
                    <div class="modal-footer" style="display: block;">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal"><i
                                    class="bi bi-x-lg align-baseline me-1"></i> Close
                            </button>
                            <button type="submit" class="btn btn-danger" id="deleteBtn">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- modal-content -->
        </div>
        <!-- modal-dialog -->
    </div>
    <!-- Asset Delete Modal End -->
@endsection
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/cdn/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script> --}}
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            let emailEditor;

            $('#sendEmailModal').on('shown.bs.modal', function() {
                if (!emailEditor) {
                    ClassicEditor.create(document.querySelector('.ckeditor-classic'), {
                            ckfinder: {
                                uploadUrl: "{{ route('technician.ckeditor.upload', ['_token' => csrf_token()]) }}"
                            }
                        })
                        .then(function(editor) {
                            emailEditor = editor;
                            editor.ui.view.editable.element.style.height = "170px";
                        })
                        .catch(function(error) {
                            console.error(error);
                        });
                }
            });
            $('#next_due_date').flatpickr({
                enableTime: false,
                dateFormat: "d-m-Y",
                defaultDate: "today"
            });

            $('#emails').select2({
                tags: true,
                placeholder: "Select or add email(s)",
                width: '100%',
                dropdownParent: $('#sendEmailModal')
            });

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
                            $(row).find('td').css('color', '#000000');
                            break;
                        case 'work underway':
                            $(row).css('background-color', '#ffff00');
                            $(row).find('td').css('color', '#000000');
                            break;
                        case 'tagged out – further work found':
                            $(row).css('background-color', '#ff0000');
                            $(row).find('td').css('color', '#ffffff');
                            break;
                        case 'work completed, ready for pickup':
                            $(row).css('background-color', '#00ffff');
                            $(row).find('td').css('color', '#000000');
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

            $('#loadTodayWorks').on('click', function() {
                $.ajax({
                    url: "{{ route('technician.load.today.works') }}",
                    method: 'GET',
                    beforeSend: function() {
                        $('#loadTodayWorks').attr('disabled', true);
                        $('#loadTodayWorks').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(response) {
                        notify('error',
                            'Something went wrong on our end. Please try again later.');
                    },
                    complete: function() {
                        $('#loadTodayWorks').attr('disabled', false);
                        $('#loadTodayWorks').html(
                            '<i class="ri-refresh-line align-bottom"></i> Today\'s Work');
                    }
                })
            });

            $('body').on('click', '.sendEmail', function() {
                let asset_emails = $(this).data('asset_emails');
                let asset_no = $(this).data('asset_no');
                let status = $(this).data('status');
                let next_due_date = $(this).data('next_due_date');
                let subject =
                    `${next_due_date} ${asset_no} Light Vehicle Inspection LC Dual Cab - 1 Monthly D/S Day shift`;
                const statusDetails = {
                    'not yet touched': {
                        background: '#ffffff',
                        color: '#000000',
                        message: 'Not yet touched',
                    },
                    'delivered': {
                        background: '#00ff00',
                        color: '#000000',
                        message: 'Delivered',
                    },
                    'no show': {
                        background: '#ff00ff',
                        color: '#ffffff',
                        message: 'No show',
                    },
                    'work underway': {
                        background: '#ffff00',
                        color: '#000000',
                        message: 'Work underway',
                    },
                    'tagged out – further work found': {
                        background: '#ff0000',
                        color: '#ffffff',
                        message: 'Tagged out – further work found',
                    },
                    'work completed, ready for pickup': {
                        background: '#00ffff',
                        color: '#000000',
                        message: 'Work completed, ready for pickup',
                    }
                };

                // Get styling info
                let statusData = statusDetails[status] ?? {
                    background: '#f0f0f0',
                    color: '#000000',
                    message: 'Job status update.',
                };

                // Create styled message preview
                let message = `
        <span style="
            background-color: ${statusData.background};
            color: ${statusData.color};
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #000;
            display: inline-block;
            margin-bottom: 10px;
        ">${statusData.message}</span>`;
                let emails = asset_emails.split(',').map(email => email.trim());
                $('#emails').empty();
                emails.forEach(email => {
                    if (email !== '') {
                        let option = new Option(email, email, true, true);
                        $('#emails').append(option);
                    }
                });
                $('#emails').trigger('change');
                $('#asset_no').val(asset_no);
                $('#status').val(status);
                $('#subject').val(subject);
                $('#message').html(message);
                $('#sendEmailModal').modal('show');
            })

            $('#sendEmailForm').on('submit', function(e) {
                e.preventDefault();
                // Sync CKEditor content into the <textarea>
                if (emailEditor) {
                    emailEditor.updateSourceElement();
                }
                // Now get the updated form data
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
                        $('#sendEmailBtn').attr('disabled', true);
                        $('#sendEmailBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            $('#sendEmailModal').modal('hide');
                        }
                    },
                    error: function(response) {
                        notify('error',
                            'Something went wrong on our end. Please try again later.');
                    },
                    complete: function() {
                        $('#sendEmailBtn').attr('disabled', false);
                        $('#sendEmailBtn').html('Send Email');
                    }
                });
            });

            $('#addAsset').on('click', function() {
                $('#addAssetModal').modal('show');
            });

            $('#addAssetModal').on('hidden.bs.modal', function() {
                $('#addAssetForm')[0].reset();
                $('#method').val('POST');
                $('#addAssetModal .modal-title').text('Create a New Asset');
                $('#addAssetForm').attr('action', "{{ route('technician.add.asset') }}");
                $('#next_due_date').flatpickr({
                    enableTime: false,
                    dateFormat: 'd-m-Y',
                    defaultDate: 'today'
                });
            });

            $('#addAssetForm').on('submit', function(e) {
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
                        $('#addAssetSubmitBtn').attr('disabled', true);
                        $('#addAssetSubmitBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            table.ajax.reload();
                            notify('success', response.message);
                            $('#addAssetModal').modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                notify('error', value);
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(value);
                            });
                        } else {
                            notify('error',
                                'Something went wrong on our end. Please try again later.');
                        }

                    },
                    complete: function() {
                        $('#addAssetSubmitBtn').attr('disabled', false);
                        $('#addAssetSubmitBtn').html('Add Asset');
                    }
                });
            });

            $('#sendEmailModal').on('hidden.bs.modal', function() {
                if (emailEditor) {
                    emailEditor.setData(''); // Clear CKEditor content
                }
                $('#sendEmailForm')[0].reset(); // Reset other fields
                $('#emails').val(null).trigger('change'); // If you're using Select2
            });

            $('body').on('click', '.deleteAsset', function() {
                let id = $(this).data('id');
                $('#deleteForm').attr('action', "{{ route('technician.delete.asset', ':id') }}".replace(
                    ':id', id));
                $('#deleteModal').modal('show');
            });

            // Handle form submission
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#deleteBtn').prop('disabled', true);
                        $('#deleteBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();
                        notify('success', response.message);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            notify('error', value);
                        });
                    },
                    complete: function() {
                        $('#deleteBtn').prop('disabled', false);
                        $('#deleteBtn').html('Delete');
                    }
                });
            });

            $('body').on('click', '.editAsset', function() {
                let id = $(this).data('id');
                $('#loader').show();

                $.get("{{ route('technician.edit.asset', ':id') }}".replace(':id', id), function(
                    response) {
                    $('#loader').hide();
                    if (response.status === 'error') {
                        notify('error', response.message);
                        return;
                    }

                    // Set modal title and form method
                    $('#addAssetModal .modal-title').text('Edit Asset');
                    $('#addAssetForm').attr('action',
                        "{{ route('technician.update.asset', ':id') }}".replace(':id', id));
                    $('#method').val('PUT');

                    // Populate fields
                    $('#addAssetModal #asset_no').val(response.data.asset_no);
                    $('#department').val(response.data.department);
                    $('#next_due_date').val(response.data.next_due_date);
                    $('#description').val(response.data.description);
                    $('#next_due_date').flatpickr({
                        enableTime: false,
                        dateFormat: "d-m-Y",
                    });
                    $('#addAssetModal').modal('show');
                    console.log(response.data);
                }).fail(function() {
                    $('#loader').hide();
                    notify('error', 'Something went wrong. Please try again.');
                });
            });

        });
    </script>
@endsection
