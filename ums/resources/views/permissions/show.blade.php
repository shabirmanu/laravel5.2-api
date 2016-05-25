@extends('layouts.app')

@section('content')
    @include('permissions.show_fields')

    <div class="form-group">
           <a href="{!! route('permissions.index') !!}" class="btn btn-default">Back</a>
    </div>
@endsection
