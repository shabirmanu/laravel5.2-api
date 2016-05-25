@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Assign Permissions</h1>
        </div>
        <div class="col-sm-12">
            <p class="pull-left">Permissions let you control what users can do and see on your site.
                You can define a specific set of permissions for each role. (See the Roles page to create a role).
            </p>
        </div>
    </div>
        {!! Form::open(['url' => 'people/permission']) !!}
        <div class="row">
            <div class="col-sm-12 table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Pemission</th>
                            @foreach($roles as $role)
                                <th>{!! $role->display_name !!}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{!! $permission->display_name !!}
                                <br /><p class="small"> {!! $permission->description !!}</p>
                                </td>
                                @foreach($roles as $role)
                                    <td>
                                        {{ Form::checkbox('role[]', $role->id.'_'.$permission->id, in_array($role->id.'_'.$permission->id, $existing_roles_perms)) }}
                                        {{--<input type="checkbox" name="{!! $role->id.'_'.$permission->id !!}" value="{!! $role->id.'_'.$permission->id !!}"/>--}}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                </table>

                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save permissions', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>

                {!! Form::close() !!}
            </div>

        </div>

    </div>
@endsection