@extends('layouts.auth')

@section('content')
    <section class="content-header">
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <img class="tooltips-small" src="{{ asset("img/tooltips/Info.png") }}">
            <div style="display: inline; font-weight: bold; color: #494a4f;">
                <h5 class="inline">Address:</h5>
               Goals Beckenham, Elmers End Rd, Beckenham BR3 4EJ
                <a href="https://maps.app.goo.gl/RbTdLc2UqRKW6PDx7" target="_new" class="btn btn-xs btn-animate btn-raised btn-primary pull-right" style="margin-top:5px">Directions</a>
            </div>
        </div>
    </section>
    @if($canBeCancelled && $isCurrentGameCancelled)
        <section class="content-header">
            <div class="alert alert-danger text-center">
                <h4>THIS WEEK THE GAME HAS BEEN CALLED OFF !!!</h4>
                <p>The next date to start your confirmation will be available from {{ $nextConfirmDate }}</p>
            </div>
        </section>
    @else
        @if($jsonConfirm->isConfirmDay)
            @if(\App\Player::alertMessage())
                <section class="content-header">
                    <div class="alert alert-{{ \App\Player::alertMessage()['alertStyle'] }}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <img class="tooltips-small" src="{{ \App\Player::alertMessage()['imgSrc'] }}">
                        @if(\App\Player::alertMessage()['showButton'])
                            <button type="button" class="btn btn-xs btn-raised btn-primary margin-right-10 pull-right" id="btnGenerateTeams">
                                Generate Teams
                            </button>
                        @endif
                        <div id="header-content" style="display: inline; font-weight: bold; color: #494a4f;">
                            @include('templates.dashboard.header_content')
                        </div>
                    </div>
                </section>
            @endif
        @endif


        <!-- UPDATE `players` SET `status` = 'available' WHERE id > 0 AND id < 15 -->


        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">&nbsp
                        @if(!$jsonConfirm->isConfirmDay)
                            <i class="fa fa-circle text-red"></i>
                            &nbsp
                            Not available at this time
                        @else
                            <i class="fa fa-circle text-{{ $player->title_icon }}"></i>
                            &nbsp
                            {{ $player->title }}
                        @endif
                    </h3>
                </div>


                <div class="box-body with-border">
                    <div class="media discussion-widget-block">
                        <div class="media-left media-middle">
                            <a href="{{ URL::to('profile') }}">
                                <img class="media-object img-thumbnail"
                                     src="{{asset($player->status == 'waiting' ? 'img/avatar/footballer_3.png' : $player->status == 'unavailable' ? 'img/avatar/footballer_cross.png' : 'img/avatar/footballer_check.png')}}">
                            </a>
                        </div>
                        <div class="media-body">
                            @if(!$jsonConfirm->isConfirmDay)
                                <p>
                                    <i class="fa fa-cog fa-spin" aria-hidden="true"></i>
                                    The next date to start your confirmation will be available from {{ $nextConfirmDate }}
                                </p>
                                <p>
                                    <span class="label label-outline label-primary pull-right">Game starts {{ $nextStartDate }}</span>
                                </p>

                            @else
                                <p>
                                    <i class="fa fa-{{ $player->body_icon }}" aria-hidden="true"></i>
                                    {!! $player->body !!}
                                </p>
                                @if(\App\Player::getPlayerPermission() != 'updating')
                                    <p>
                                        <span class="label label-outline label-primary pull-right">Game starts {{ $nextStartDate }}</span>
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if($jsonConfirm->isConfirmDay)
                @if($player->hasMessage || $player->isBtnUnavailable || $player->isBtnAvailable)
                    <div class="box-footer clearfix">
                        <div class="box box-info collapsed-box" style="border-top: none !important;">
                            <div class="box-header">
                                <div class="box-tools" style="position: static !important;">
                                    @if($player->hasMessage)
                                        <section class="content-header">
                                            <div class="alert alert-warning">
                                                {{ $player->privateMessage }}
                                            </div>
                                        </section>
                                    @endif
                                    @if($player->isBtnUnavailable)
                                        <a href="#" class="pull-left" data-widget="collapse" id="unavailable">
                                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                            Unavailable
                                        </a>
                                        <script>
                                            $("#unavailable").click(function () {
                                                $("#available").toggle();
                                            });
                                        </script>
                                    @endif
                                    @if($player->isBtnAvailable)
                                        <button type="button" class="btn btn-raised btn-success pull-right" id="btnConfirm"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                            Available
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="box-body">
                                <div class="media discussion-widget-block">
                                    <div class="media-body">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select class="form-control show-tick" id="weeks">
                                                    <option value="1">1 week</option>
                                                    <option value="2">2 weeks</option>
                                                    <option value="3">3 weeks</option>
                                                    <option value="4">4 weeks</option>
                                                </select>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-raised btn-danger pull-left" id="btnUnavailable">
                                                    <i class="fa fa-frown-o" aria-hidden="true"></i>
                                                    Send
                                                </button>
                                            </span>

                                            </div><!-- /input-group -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @endif
            </div>
        </div>

        <div class="col-md-6" id="players-section">
            @include('templates.dashboard.players_section')
        </div>
    @endif
@endsection

@section('local-js')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@stop