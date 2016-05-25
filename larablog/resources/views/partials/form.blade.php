<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name' ,null , ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('tag_list', 'Tags:') !!}
    {!! Form::select('tag_list[]', $tags, null,  ['id' => 'tagList', 'class' => 'form-control', 'multiple' =>'multiple']) !!}
</div>
<div class="form-group">
    {!! Form::label('published_at', 'Published At:') !!}
    {!! Form::input('date','published_at', date('Y-m-d') , ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('body', 'Article Body:') !!}
    {!! Form::textarea('body', null , ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('excerpt', 'Excerpt:') !!}
    {!! Form::textarea('excerpt', null , ['class' => 'form-control']) !!}
</div>
{!! Form::submit($submitText, ['class' => 'btn btn-primary']) !!}