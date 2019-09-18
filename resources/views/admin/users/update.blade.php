@extends('layouts.auth')
@section('plugin')
    @parent

@endsection
@section('content')
    <div id="content">
        <div class="col-md-12">
            <div class="box box-info ">
                <div class="box-header with-border">
                    <h3 class="box-title">Update "{{ $user->username }}" account</h3>
                </div>
                <div class="box-body with-border" id="box-body">
                    <div class="media discussion-widget-block">
                        <div class="media-body">
                            {!! Form::model($user,["id"=>"frm","class"=>"form-horizontal"]) !!}
                            {{ csrf_field() }}
                            <div class="form-group required" id="form-name-error">
                                <label for="name" class="control-label col-md-3">Name</label>
                                <div class="col-md-6">
                                    <input class="form-control required" id="name" name="name" type="text"
                                           value="{{ $user->name != old('name') ? $user->name : old('name') }}">
                                    <span id="name-error" class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group required" id="form-username-error">
                                <label for="username" class="control-label col-md-3">Username</label>
                                <div class="col-md-6">
                                    <input class="form-control required" id="username" name="username" type="text"
                                           value="{{ $user->username != old('username') ? $user->username : old('username') }}">
                                    <span id="username-error" class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group required" id="form-email-error">
                                <label for="email" class="control-label col-md-3">E-mail</label>
                                <div class="col-md-6">
                                    <input class="form-control required" id="email" name="email" type="text"
                                           value="{{ $user->email != old('email') ? $user->email : old('email') }}">
                                    <span id="email-error" class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group required" id="form-role-error">
                                <label for="role" class="control-label col-md-3">Role</label>
                                <div class="col-md-6">
                                    <select class="form-control required" name="role" id="role">
                                        @foreach(\App\Player::getPossibleRoles('users', 'role') as $key => $role)
                                            @if($user->role == $key)
                                                <option value="{{ $key }}" selected>{{ ucwords($key) }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ ucwords($key) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span id="role-error" class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group required" id="form-position-error">
                                <label for="position" class="control-label col-md-3">Position</label>
                                <div class="col-md-6">
                                    <select class="form-control required" name="position" id="position">
                                        @foreach(\App\Player::getPossibleRoles('players', 'player_position') as $key => $field)
                                            @if($player->player_position == $key)
                                                <option value="{{ $key }}" selected>{{ $key}}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $key }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span id="position-error" class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group required" id="form-skill-error">
                                <label for="skill" class="control-label col-md-3">Skill level</label>
                                <div class="col-md-6">
                                    <select class="form-control required" name="skill" id="skill">
                                        @foreach(\App\Player::getPossibleRoles('players', 'skill_level') as $key => $field)
                                            @if($player->skill_level == $key)
                                                <option value="{{ $key }}" selected>{{ $key}}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $key }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span id="skill-error" class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group required" id="form-status-error">
                                <label for="skill" class="control-label col-md-3">Status</label>
                                <div class="col-md-6">
                                    <select class="form-control required" name="status" id="status">
                                        @foreach(\App\Player::getPossibleRoles('players', 'status') as $key => $field)
                                            @if($player->status == $key)
                                                <option value="{{ $key }}" selected>{{ ucfirst($key)}}</option>
                                            @else
                                                <option value="{{ $key }}">{{ ucfirst($key) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span id="status-error" class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-push-3">
                                    <a href="{{ url('admin/players') }}" class="btn btn-danger">
                                        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
                                        Back</a>
                                    <button type="submit" class="btn btn-primary pull-right">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                        Save
                                    </button>
                                </div>
                            </div>
                            @include("admin.users._validationjs")
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection