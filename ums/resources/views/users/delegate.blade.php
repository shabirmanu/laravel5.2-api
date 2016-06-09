@extends('layouts.app')

@section('content')
    <h1>Delegate Ownership of these Articles</h1>
    @if(!empty($e))
        <div class="alert-info alert">{{ print $e->message }}</div>
        {!! Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']) !!}
        <div class='btn-group'>
            {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
        </div>
        {!! Form::close() !!}
    @else

        <ul>
            @foreach($articles['data'] as $article)
                <li>{{ print $article->name }}</li>
            @endforeach
        </ul>
        {!! Form::open(['route' => ['users.delegateAndDestroy'], 'method' => 'post']) !!}
        {!! Form::hidden('user_id', $id); !!}
        @if(!empty($usersArr))
            <div class="form-group">
                {!! Form::select('delegate_id', $usersArr, null,  ['class' => 'form-control']) !!}
            </div>
        @else
            <div class="alert-danger alert">No user found! Deleting this user will delete associated articles</div>
        @endif
        <div class='btn-group'>
            {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
        </div>
        {!! Form::close() !!}

    @endif
@endsection

