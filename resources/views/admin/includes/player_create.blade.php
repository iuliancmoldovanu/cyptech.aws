<div class="col-md-12">
    <div class="box box-info ">
        <div class="box-header with-border">
            <h3 class="box-title">Create player account</h3>
            <div class="box-tools pull-right">
                <!-- Remove Button -->
                <button type="button" class="btn btn-box-tool hide-new-player-form">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="box-body with-border" id="box-body">
            <div class="media discussion-widget-block">
                <div class="media-body">
                    {{--{!! Form::open(["id"=>"frm","class"=>"form-horizontal"]) !!}--}}
                    <form class="form-horizontal" method="POST">
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
                                        @if($key === "master")
                                            @if($authUser->levelAccess() >= 3)
                                                <option value="{{ $key }}">{{ ucwords($key) }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $key }}" {{"player" === $key?"selected":""}}>{{ ucwords($key) }}</option>
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
                        <div class="form-group">
                            <label for="ranking_priority" class="control-label col-md-3">Confirm days before game</label>
                            <div class="col-md-6">
                                <select class="form-control required" name="ranking_priority" id="ranking_priority">
                                    @for($i = 1; $i <= 4 ; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
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
                        <div class="form-group required" id="form-status-error">
                            <label for="status" class="control-label col-md-3">Make it as temporary account</label>
                            <div class="col-md-6">
                                <div class="switch-field">
                                    <input type="radio" id="switch_left1" name="is_temp" value="Y">
                                    <label for="switch_left1">Yes</label>
                                    <input type="radio" id="switch_right1" name="is_temp" value="N" checked="checked">
                                    <label for="switch_right1">No</label>
                                </div>
                                <span id="status-error" class="help-block"></span>
                                <p class="activate-message">
                                    <b>Do you want to send an email to the email address provided above to activate the account?</b>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-push-3">
                                <button type="button" class="btn btn-danger hide-new-player-form">
                                    <i class="fa fa-arrow-circle-o-left margin-r-5" aria-hidden="true"></i>
                                    Back
                                </button>
                                <button type="button" class="btn btn-primary pull-right" id="btnCreatePlayer">
                                    <i class="fa fa-floppy-o margin-r-5" aria-hidden="true"></i>
                                    Create new player
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>