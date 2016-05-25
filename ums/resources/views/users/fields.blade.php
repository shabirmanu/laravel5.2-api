<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('role_list', 'Roles:') !!}
    {!! Form::select('role_list[]', $roles, null,  ['class' => 'form-control', 'multiple' =>'multiple']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('app_list', 'Apps:') !!}
    {!! Form::select('app_list[]', $apps, null,  ['class' => 'form-control', 'multiple' =>'multiple']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
