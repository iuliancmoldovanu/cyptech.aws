<div class="col-md-12">
    <div class="box box-info ">
        <div class="box-header with-border">
            <h3 class="box-title">Create player account</h3>
        </div>
        <div class="box-body with-border" id="box-body">
            <div class="media discussion-widget-block">
                <div class="media-body">
                    {!! Form::open(["id"=>"frm","class"=>"form-horizontal"]) !!}
                    {{ csrf_field() }}
                        <div class="form-group required" id="form-name-error">
                            <label for="name" class="control-label col-md-3">Name</label>
                            <div class="col-md-6">
                                <input class="form-control required" id="focus" name="name" type="text">
                                <span id="name-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-username-error">
                            <label for="username" class="control-label col-md-3">Username</label>
                            <div class="col-md-6">
                                <input class="form-control required" name="username" type="text" id="username">
                                <span id="username-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-email-error">
                            <label for="email" class="control-label col-md-3">E-mail</label>
                            <div class="col-md-6">
                                <input class="form-control required" name="email" type="text" id="email">
                                <span id="email-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-password-error">
                            <label for="password" class="control-label col-md-3">Password</label>
                            <div class="col-md-6">
                                <input class="form-control required" name="password" type="password" id="password">
                                <span id="password-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-password_confirmation-error">
                            <label for="password_confirmation" class="control-label col-md-3">Confirm password</label>
                            <div class="col-md-6">
                                <input class="form-control required" name="password_confirmation" type="password" id="password">
                                <span id="password_confirmation-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-role-error">
                            <label for="role" class="control-label col-md-3">Role</label>
                            <div class="col-md-6">
                                <select class="form-control required" name="role" id="role">
                                    @foreach(\App\Player::getPossibleRoles('users', 'role') as $key => $role)
                                        @if(Auth::user()->levelAccess() >= 3 && $key === "master")
                                            <option value="{{ $key }}">{{ ucwords($key) }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ ucwords($key) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span id="role-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-skill-error">
                            <label for="skill" class="control-label col-md-3">Skill level</label>
                            <div class="col-md-6">
                                <select class="form-control required" name="skill" id="skill">
                                    @foreach(\App\Player::getPossibleRoles('players', 'skill_level') as $key => $role)
                                        <option value="{{ $key }}">{{ ucwords($key) }}</option>
                                    @endforeach
                                </select>
                                <span id="skill-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group required" id="form-status-error">
                            <label for="status" class="control-label col-md-3">Add to the current game?</label>
                            <div class="col-md-6">
                                <div class="switch-field">
                                    <input type="radio" id="switch_left" name="status" value="available">
                                    <label for="switch_left">Yes</label>
                                    <input type="radio" id="switch_right" name="status" value="waiting"
                                           checked="checked">
                                    <label for="switch_right">No</label>
                                </div>
                                <span id="status-error" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-push-3">
                                <a href="javascript:ajaxLoad('users/list')" class="btn btn-danger btn-xs">
                                    <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
                                    Back</a>
                                <button type="submit" class="btn btn-primary pull-right btn-xs">
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