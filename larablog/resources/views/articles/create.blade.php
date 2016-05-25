@extends('layouts.app')

@section('content')

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Add new Article</h4>
                    </div>
                    <div class="panel-body">
                        @include('errors.article-errors')
                        {!! Form::open(['url' => 'articles']) !!}
                        @include('partials.form', ['submitText' => 'Create New Article'])
                        {!! Form::close() !!}
                    </div>
                </div>


            </div>
        </div>
    </div>

@stop