<div class="row gy-3">
    <div class="col-md-4">
        <strong>Asset Number:</strong><br>
        {{ $log->asset_no ?? 'N/A' }}
    </div>
    <div class="col-md-4">
        <strong>Department:</strong><br>
        {{ $log->department ?? 'N/A' }}
    </div>
    <div class="col-md-4">
        <strong>Next Due Date:</strong><br>
        {{ $log->next_due_date ?? 'N/A' }}
    </div>

    <div class="col-md-4">
        <strong>Sent Date:</strong><br>
        {{ $log->sent_date ?? 'N/A' }}
    </div>
    <div class="col-md-4">
        <strong>Sent Time:</strong><br>
        {{ $log->sent_time ?? 'N/A' }}
    </div>
    <div class="col-md-4">
        <strong>Status:</strong><br>
        @if ($log->is_sent)
            <span class="badge bg-success">Success</span>
        @else
            <span class="badge bg-danger">Failed</span>
        @endif
    </div>

    <div class="col-md-12">
        <strong>Recipient Email:</strong><br>
        {{ $log->recipient_email ?? 'N/A' }}
    </div>

    <div class="col-md-12">
        <strong>Subject:</strong><br>
        {{ $log->email_subject ?? 'N/A' }}
    </div>

    <div class="col-md-12">
        <strong>Email Body:</strong><br>
        {!! $log->email_body ?? '<span class="text-muted">N/A</span>' !!}
    </div>
</div>
