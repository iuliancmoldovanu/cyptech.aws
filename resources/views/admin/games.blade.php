@extends('layouts.auth')

@section('css')
    @parent
    <link href="{{ URL::to('css/sweetalert2.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Setup current game</h3>
                <div class="box-tools pull-right">
                    <i class="fa fa-info-circle pull-right" style="margin-top: 7px" data-container="body" data-placement="left" data-toggle="popover" data-trigger="hover"
                       data-content="The teams can be reset it once has been generated"></i>
                    <button id="reset_teams"
                       class="btn btn-danger btn-xs pull-right {{ App\Game::getCurrentWeekGame()->generated_by ? '' : 'disabled'}}">
                        <i class="fa fa-btn fa-cogs"></i> Reset teams
                    </button>
                </div>
            </div>
            <div class="box-body with-border">
                <div class="media discussion-widget-block">
                    <div class="media-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ URL::to('admin/update_game') }}">
                            {{ csrf_field() }}
                            {{--<div class="form-group">--}}
                            {{--<div class="col-md-12">--}}

                            {{--<div class="switch-field">--}}
                            {{--<div class="switch-title">Apply player restriction ?</div>--}}
                            {{--<input type="radio" id="switch_left" name="restrictions" value="yes">--}}
                            {{--<label for="switch_left">Yes</label>--}}
                            {{--<input type="radio" id="switch_right" name="restrictions" value="no"--}}
                            {{--checked="checked">--}}
                            {{--<label for="switch_right">No</label>--}}
                            {{--</div>--}}

                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<hr>--}}
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="switch-field">
                                        <div class="switch-title">Players a side</div>
                                        <input type="radio"
                                               {{ $games->players_a_side == 5 ? 'checked="checked"' : '' }} id="switch_3_left"
                                               name="players" value="5">
                                        <label for="switch_3_left">Five</label>
                                        <input type="radio"
                                               {{ $games->players_a_side == 6 ? 'checked="checked"' : '' }} id="switch_3_center"
                                               name="players" value="6">
                                        <label for="switch_3_center">Six</label>
                                        <input type="radio"
                                               {{ $games->players_a_side == 7 ? 'checked="checked"' : '' }} id="switch_3_right"
                                               name="players"
                                               value="7">
                                        <label for="switch_3_right">Seven</label>
                                    </div>

                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="days" class="col-md-4 control-label">
                                    Match day/time
                                </label>
                                <div class="col-md-6">
                                    <select name="days" id="days">
                                        @foreach($week as $days)
                                            <option value="{{ $days->day }}" {{ $days->day_match ? 'selected="selected"' : '' }}>{{ $days->day }}</option>
                                        @endforeach
                                    </select>

                                    <?php //dd($match['start_time']); ?>
                                    <select name="time">
                                        @for($h = 9; $h <= 22; $h++)
                                            @for($i = 0; $i < 60; $i++)
                                                @if($i%30 == 0)
                                                    @if($h < 10 && $i < 10)
                                                        <option
                                                                value="{{ 0 . $h . ':' . 0 . $i }}:00"
                                                                {{ ($match['start_time'] == 0 . $h . ':' . 0 . $i . ":00") ? 'selected="selected"' : '' }}>
                                                            {{ 0 . $h . ':' . 0 . $i }}
                                                        </option>
                                                    @elseif($i < 10)
                                                        <option
                                                                value="{{ $h . ':' . 0 . $i }}:00"
                                                                {{ ($match['start_time'] == $h . ':' . 0 . $i . ":00") ? 'selected="selected"' : '' }}>
                                                            {{ $h . ':' . 0 . $i }}
                                                        </option>
                                                    @elseif($h < 10)
                                                        <option
                                                                value="{{ 0 . $h . ':' . $i }}:00"
                                                                {{ ($match['start_time'] == 0 . $h . ':' . $i . ":00") ? 'selected="selected"' : '' }}>
                                                            {{ 0 . $h . ':' . $i }}
                                                        </option>
                                                    @else
                                                        <option
                                                                value="{{ $h . ':' . $i }}:00"
                                                                {{ ($match['start_time'] == $h . ':' . $i . ":00") ? 'selected="selected"' : '' }}>
                                                            {{ $h . ':' . $i }}
                                                        </option>
                                                    @endif
                                                @endif
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="day_confirm" class="col-md-4 control-label">
                                    Confirm accept until
                                    @foreach($week as $days)
                                        <span class="db_day">{{ $days->day_match ? $days->day : '' }}</span>
                                        <span id="selected_day"></span>
                                    @endforeach
                                    <script>
													$('#days').change(function () {
														$("span").remove(".db_day");
														$('#selected_day').html($(this).val());
													});
                                    </script>
                                    at
                                </label>
                                <div class="col-md-6">
                                    <select name="time_confirm">
                                        @for($h = 9; $h <= 22; $h++)
                                            @for($m = 0; $m < 60; $m++)
                                                @if($m%30 === 0)
                                                    @if($h < 10 && $m < 10)
                                                        <option value="{{ 0 . $h . ':' . 0 . $m }}:00"
                                                                {{ ($match['last_confirm_time'] == 0 . $h . ':' . 0 . $m) ? ' selected="selected"' : '' }}>
                                                            {{ 0 . $h . ':' . 0 . $m }}
                                                        </option>
                                                    @elseif($m < 10)
                                                        <option value="{{ $h . ':' . 0 . $m }}:00"
                                                                {{ ($match['last_confirm_time'] == $h . ':' . 0 . $m) ? ' selected="selected"' : '' }}>
                                                            {{ $h . ':' . 0 . $m }}
                                                        </option>
                                                    @elseif($h < 10)
                                                        <option value="{{ 0 . $h . ':' . $m }}:00"
                                                                {{ ($match['last_confirm_time'] == 0 . $h . ':' . $m) ? ' selected="selected"' : '' }}>
                                                            {{ 0 . $h . ':' . $m }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $h . ':' . $m }}:00"
                                                                {{ ($match['last_confirm_time'] == $h . ':' . $m) ? ' selected="selected"' : '' }}>
                                                            {{ $h . ':' . $m }}
                                                        </option>
                                                    @endif
                                                @endif
                                            @endfor
                                        @endfor
                                    </select>
                                    <script>
													$('#days').change(function () {
														$("span").remove(".db_day");
														$('#selected_day').html($(this).val());
													});
                                    </script>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-xs btn-info pull-right btn_update">
                                        <i class="fa fa-btn fa-share-square-o"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp
                    <i class="fa fa-tasks text-green" aria-hidden="true"></i>
                    &nbsp Complete current game
                </h3>
                <div class="box-tools pull-right">
                    @if($canBeCancelled && $isCurrentGameCancelled === false)
                        <button id="btnCancelGame" class="btn btn-danger btn-xs">
                            <img class="tooltips-small img-responsive" style="height: 15px; width: 15px; margin: 2px;"
                                 src="{{ URL::to('img/tooltips/Critical.png') }}"> CANCEL
                        </button>
                    @else
                        <button class="btn btn-danger btn-xs disabled">
                            <img class="tooltips-small img-responsive" style="height: 15px; width: 15px; margin: 2px;"
                                 src="{{ URL::to('img/tooltips/Critical.png') }}"> CANCEL
                        </button>
                    @endif

                    <i class="fa fa-info-circle pull-right" style="margin-top: 7px" data-container="body" data-placement="left" data-toggle="popover" data-trigger="hover"
                       data-content="The game can be cancelled only {{ $daysWhenCancelled }}"></i>
                </div>
            </div>
            <div class="box-body with-border">
                <div class="media discussion-widget-block">
                    <div class="media-body">
                        @if((int) \App\Game::getCurrentWeekGame()->generated_by && ((int)substr(\App\WeekDay::getPlayingDay()->start_time, 0, 2)+1 < (int)date('H') || \App\WeekDay::getPlayingDay()->day != date('l')))
                            <form id="frm" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="switch-title">Select result of that game</div>
                                <div class="switch-field">
                                    <input type="radio" name="result" id="switch_right" value="green">
                                    <label for="switch_right" id="green_team">Green</label>
                                    <input type="radio" name="result" checked="checked" id="switch_center"
                                           value="draw">
                                    <label for="switch_center" id="draw">Draw</label>
                                    <input type="radio" name="result" id="switch_left" value="red">
                                    <label for="switch_left" id="red_team">Red</label>
                                </div>

                                <div class="col-md-6 col-md-push-3">
                                    <button type="button" class="btn btn-primary pull-right" id="btnCompleteGame">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                        Save
                                    </button>
                                </div>
                            </form>
                            <span class="label label-outline label-default label-dark text-blue hide" style="display: block" id="game-updated">Game already updated</span>
                        @else
                            <div class="col-md-12" style="margin-bottom: 5px">
                                @if($isCurrentGameCancelled && $canBeCancelled)
                                    <span class="label label-outline label-dark text-red pull-right">This week the game <br> has been already cancelled</span>
                                @else
                                    <span class="label label-outline label-dark text-green">Winner can be selected {{ \App\WeekDay::getPlayingDay()->day }}
                                        after <?php echo (int)\App\WeekDay::getPlayingDay()->start_time + 1 ?>:00</span>

                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('local-js')
    <script src="{{ asset('js/game.js') }}"></script>
@stop