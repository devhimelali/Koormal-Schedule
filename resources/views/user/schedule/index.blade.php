@extends('layouts.app')
@section('title', 'Schedules')
@section('content')
    @php
        $type = request()->query('type');
        $label = $type == 'lv' ? 'Light Vehicles' : 'Lighting Towers';
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Koormal-Schedule</h4>
                @if ($type == 'lt')
                    <p class="mb-0 fst-italic mt-2" style="max-width: 800px;">Lighting towers are serviced on run hours.
                        The algorithm
                        determines
                        the due
                        date on an average usage basis over current past usage.
                        If the Lighting Tower is turned off, out of service or not used the due date may change.
                    </p>
                @endif

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('redirect') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Schedule {{ $label }}</li>
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
                            <select name="department" id="department" class="form-control select2">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}">{{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="asset_no" id="asset_no" class="form-control select2">
                                <option value="">Select Asset Number</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset }}">{{ $asset }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="text" name="date-range" class="form-control" id="date-range">
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#sendEmailBtn">
                                <i class="ri-mail-send-line"></i> Send Email
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-danger" id="exportPdf">
                                <i class="ri-file-pdf-2-line"></i> PDF
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-success" id="exportExcel">
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
                                    <th style="min-width: 100px; width: 100px;">Asset Number</th>
                                    <th>Description</th>
                                    <th style="min-width: 120px; width: 120px;">Department</th>
                                    <th style="min-width: 100px; width: 100px;">Next Due Date</th>
                                    <th style="min-width: 100px; width: 100px;">Action</th>
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
    <!-- Default Modals -->
    <div id="sendEmailBtn" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('schedules.email') }}" method="post" id="sendEmailForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="Enter email address. you can add multiple email separated by comma">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject"
                                placeholder="Enter email subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" placeholder="Message" rows="7"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="sendEmailSubmitBtn">Send</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Send Email Modals -->
    <div id="sendEmailModal" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('schedule.send.email') }}" method="post" id="emailSendEmailForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="asset_no" id="email_asset_no">
                    <input type="hidden" name="next_due_date" id="email_next-due-date">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Send Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_email" name="emails">
                            <span class="text-danger-emphasis" style="font-size: 12px;">Please enter one or more email
                                addresses, separated by commas (e.g., user1@example.com, user2@example.com).</span>
                        </div>
                        <div class="mb-2">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="email_subject" name="subject">
                        </div>
                        <div class="mb-2">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control ckeditor-classic" id="email_message" name="message" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary" id="emailSendEmailBtn">Send</button>
                        <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
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
@section('vendor-script')
    <script src="{{ asset('assets/cdn/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
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
                    url: "{{ route('schedules.index') }}" + "?type=" + type,
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
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'department',
                        name: 'department',
                    },
                    {
                        data: 'next_due_date',
                        name: 'next_due_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            function filterData() {
                let department = $('#department').val();
                let asset_no = $('#asset_no').val();
                let date_range = $('#date-range').val();
                table.ajax.url(
                    "{{ route('schedules.index') }}" +
                    "?type=" + type +
                    "&department=" + department +
                    "&asset_no=" + asset_no +
                    "&date_range=" + date_range
                ).load();

            }

            $('#department, #asset_no, #time_frame').on('change', function() {
                filterData();
            });

            $('#exportPdf').on('click', function() {
                let department = $('#department').val();
                let asset_no = $('#asset_no').val();
                let time_frame = $('#date-range').val();

                let url = "{{ route('schedules.export.pdf') }}" +
                    "?type=" + encodeURIComponent(type) +
                    "&department=" + encodeURIComponent(department) +
                    "&asset_no=" + encodeURIComponent(asset_no) +
                    "&time_frame=" + encodeURIComponent(time_frame);

                window.open(url, '_blank');
            });

            $('#exportExcel').on('click', function() {
                let department = $('#department').val();
                let asset_no = $('#asset_no').val();
                let time_frame = $('#date-range').val();

                let url = "{{ route('schedules.export.excel') }}" +
                    "?type=" + encodeURIComponent(type) +
                    "&department=" + encodeURIComponent(department) +
                    "&asset_no=" + encodeURIComponent(asset_no) +
                    "&time_frame=" + encodeURIComponent(time_frame);

                window.open(url, '_blank');
            });

            $('#sendEmailForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('department', $('#department').val());
                formData.append('asset_no', $('#asset_no').val());
                formData.append('time_frame', $('#time_frame').val());
                formData.append('type', type);
                let url = $(this).attr('action');
                let method = $(this).attr('method');
                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#sendEmailSubmitBtn').attr('disabled', true);
                        $('#sendEmailSubmitBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            $('#sendEmailForm')[0].reset();
                            $('#sendEmailBtn').modal('hide');
                        }

                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                notify('error', value);
                                $('[name="' + key + '"]').addClass('is-invalid');
                            })
                        } else {
                            notify('error',
                                'Something went wrong on our end. Please try again later.');
                        }
                    },
                    complete: function() {
                        $('#sendEmailSubmitBtn').attr('disabled', false);
                        $('#sendEmailSubmitBtn').html('Send');
                    }
                });
            });

            let emailEditor;

            $('#sendEmailModal').on('shown.bs.modal', function() {
                if (!emailEditor) {
                    ClassicEditor.create(document.querySelector('.ckeditor-classic'), {
                            ckfinder: {
                                uploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}"
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

            $('body').on('click', '.send-email', function() {
                let asset_no = $(this).data('asset-no');
                let emails = $(this).data('emails');
                let next_due_date = $(this).data('next-due-date');
                $('#email_email').val(emails);
                $('#email_asset_no').val(asset_no);
                $('#email_next-due-date').val(next_due_date);
                $('#sendEmailModal').modal('show');
            });

            $('#emailSendEmailForm').on('submit', function(e) {
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
                        $('#emailSendEmailBtn').attr('disabled', true);
                        $('#emailSendEmailBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                        );
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            $('#emailSendEmailForm')[0].reset();
                            $('#sendEmailModal').modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        // if validation fails
                        if (xhr.status == 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                notify('error', value);
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(value);
                            });
                        } else if (xhr.status === 429) {
                            notify('error',
                                'Too many failed attempts. Please try again later.');
                        } else if (xhr.status === 500) {
                            notify('error',
                                'Something went wrong on our end. Please try again later.');
                        } else {
                            notify('error', error);
                        }
                    },
                    complete: function() {
                        $('#emailSendEmailBtn').attr('disabled', false);
                        $('#emailSendEmailBtn').html('Send');
                    }
                });
            });
        });
    </script>
@endsection
