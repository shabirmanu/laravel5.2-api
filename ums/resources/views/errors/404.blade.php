@extends('layouts.app')

@section('content')
    <h2 class="page-header">Page Not Found!</h2>
    <p>The requested page <strong>'{!! Request::path() !!}'</strong> could not be found.</p>
@endsection