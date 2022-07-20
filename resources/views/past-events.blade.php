@extends('layouts.master')

@section('title', 'MomsinLA')

@section('content')
    {{-- Past Events image --}}
    <div class="col-12 text-center mt-4">
        <img src="{{ asset('img/donations/left.jpg') }}" class="img-fluid" alt="">
    </div>

    @foreach ($events as $event)
        <div class="col-12 mt-2 mb-2 p-3" style="border: 1px solid rgba(0, 0, 0, 0.150);">
            <div class="row">
                <div class="col-4">
                    <img src="{{ $event['imgs'][0] }}" class="img img-fluid" alt=""
                        style="object-fit: cover; object-position: center;background-size: cover; height: 15rem; width: 100%">
                </div>
                <div class="col-8">
                    <h5>{{ $event['title'] }}</h5>
                    <p>{{ $event['content'] }}</p>
                    <p><b>Address</b> {{ $event['address'] }}, {{ $event['city'] }}, {{ $event['zip'] }}</p>
                    <p><b>Start time</b> {{ date('d/m/Y H:i:s', $event['activityDate'][0]['from'] / 1000) }}</p>
                    <p><b>End time</b> {{ date('d/m/Y H:i:s', $event['activityDate'][0]['to'] / 1000) }}</p>
                </div>
            </div>
        </div>
    @endforeach
@stop
