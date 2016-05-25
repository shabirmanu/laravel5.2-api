@extends('layouts.app')

@section('content')
    <div class="container">
        @if(Session::has('flash_message'))
            <div class="alert alert-success {{ Session::has('flash_message_important') ? 'alert-important':'' }}">
                @if(Session::has('flash_message_important'))
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                @endif
                {{ Session::get('flash_message') }}
            </div>
        @endif
            <h2>All Articles</h2>
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ url('articles/create') }}" class="btn btn-primary">Add New Article</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-stripped">
                <thead>
                    <tr>
                        <th>Article title</th>
                        <th>Article Body</th>
                        <th>Article Excerpt</th>
                        <th>Published At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                    <tr>
                       <td>
                          <a href="{{ url('articles', $article->id)  }}"><h4>{{ $article->name }}</h4></a>
                      </td>
                        <td>{{ $article->body }}</td>
                        <td>{{ $article->excerpt }}</td>
                        <td>{{ $article->published_at->diffForHumans() }}</td>
                        <td><a href="{{ url('articles/'.$article->id.'/edit') }}">Edit</a> | Delete</td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop