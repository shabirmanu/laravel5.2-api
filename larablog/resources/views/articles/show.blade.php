@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $article->name }}</h2>
        <article class="row">
            <div class="col-lg-8">
                {{ $article->body }}
                {{ $article->excerpt }}
            </div>
            @if( $article->tags )
                <div class="col-lg-4">
                    <h2>Tags:</h2>
                    <ul>
                        @foreach($article->tags as $tag)
                            <li>{{ $tag->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>
    </div>
@stop