@extends('layouts.app')

@section('content')
        <h1 class="pull-left">Users</h1>

        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @include('users.table')
        
@endsection