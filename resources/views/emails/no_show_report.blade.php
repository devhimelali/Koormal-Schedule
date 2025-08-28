@extends('layouts.mail')
@section('title', 'Missed Safety Inspection Notification')
@section('content')
    <p>Dear {{ $department->name }},</p>
    <p>
        The following Safety Inspection schedule was missed today - <strong>{{$schedule->asset_no }}</strong>
        {{$schedule->description }} <strong>{{ $schedule->next_due_date}}</strong>
    </p>
    <p>
        The vehicle is now non compliant with site safety requirements and may be unsafe for use.
    </p>
@endsection
