@extends('master')

@section('content')
    <h2>About US: {{ $first }} {{ $last }}</h2>

    @if(count($people))
        <ul>
            @foreach($people as $person)
                <li>{{ $person }}</li>
            @endforeach
        </ul>
    @endif
@stop