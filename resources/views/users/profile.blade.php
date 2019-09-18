@extends('layouts.toggle')

@section('plugin')
    @parent
    <script>
        $toggle = $('#notify_places');
        $isNotifyEnable = $('#isNotifyEnable');
        $toggle.on('change', function(){
	        $isNotifyEnable.addClass('hide');
        	if($toggle.is(':checked')){
		         $isNotifyEnable.removeClass('hide');
            }
        });
    </script>
    <script src="{{ asset('js\profile.js') }}"></script>
    <script>
        let lifetime = parseInt("{{$lifetime}}");
        let session_start_at = parseInt("{{$session_start_at}}");
    </script>
    <script src="{{ asset('js/site.js') }}"></script>
    @stop
@section('content')
    <div class="col-md-6">
        <div class="box box-info collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Player profile</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body with-border">
                <div class="media discussion-widget-block">
                    <div class="media-body">
                        <p class="text-primary">Games won: <b> {{ $player->games_won }} </b></p>
                        <p class="text-primary">Games lost: <b> {{ $player->games_lost }} </b></p>
                        <p class="text-primary">Games draw: <b> {{ $player->games_draw }} </b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-info p">
            <div class="box-header with-border">
                <h3 class="box-title">Update user account</h3>
            </div>
            <div class="box-body with-border" id="box-body">
                <div class="media-body">
                    <form class="form-horizontal" autocomplete="off">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">
                                Name
                            </label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" placeholder="Name" name="name"
                                       value="{{ $player->user->name }}">

                                <span class="help-block">
                                    <small></small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" placeholder="E-Mail Address"
                                       name="email"
                                       value="{{ $player->user->email }}">

                                <span class="help-block">
                                    <small></small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" placeholder="Phone"
                                       name="phone" value="{{ $player->user->phone }}">

                                <span class="help-block">
                                    <small></small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">
                                Notify me <i class="fa fa-info-circle" data-container="body" data-placement="right" data-toggle="popover" data-trigger="hover"
                                      data-content="An email will be send to you every week when the X number of players left will be reached. Please make sure you have your email address up to date."></i>
                            </label>
                            <div class="col-md-6">
                                <input type="checkbox" data-size="mini" data-toggle="toggle" name="notify_places" id="notify_places"
                                        {{ $player->notify_places ? ' checked' : '' }}  data-offstyle="danger">
                                <div id="isNotifyEnable" class="inline {{ $player->notify_places ? '' : 'hide' }}">

                                    <select class="selectpicker show-tick input-group-sm" name="notify_places_val" data-width="fit" data-height="10">
                                        @for($i = 1; $i < 7; $i++)
                                            <option value="{{ $i }}" {{ (int)$player->notify_places === $i ? ' selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="small"> place/s left </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-default pull-right" id="btnUpdateProfile">
                                    <i class="fa fa-btn fa-share-square-o"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="box-header with-border">
                        <h3 class="box-title">Update password</h3>
                    </div>
                    <form class="form-horizontal" autocomplete="off">

                        <div class="form-group">
                            <label for="oldPassword" class="col-md-4 control-label">Old Password</label>

                            <div class="col-md-6">
                                <input id="oldPassword" type="password" class="form-control"
                                       placeholder="Old Password" name="oldPassword">
                                <span class="help-block">
                                    <small></small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="newPassword" class="col-md-4 control-label">New Password</label>

                            <div class="col-md-6">
                                <input id="newPassword" type="password" class="form-control"
                                       placeholder="New Password" name="newPassword">

                                <span class="help-block">
                                    <small></small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="newPassword_confirmation" class="col-md-4 control-label">Confirm New
                                Password</label>

                            <div class="col-md-6">
                                <input id="newPassword_confirmation" type="password" class="form-control"
                                       placeholder="Confirm New Password" name="newPassword_confirmation">

                                <span class="help-block">
                                    <small></small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-default pull-right" id="btnUpdatePassword">
                                    <i class="fa fa-btn fa-share-square-o"></i> Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection