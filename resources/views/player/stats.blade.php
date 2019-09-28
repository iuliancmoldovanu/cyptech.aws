@extends('layouts.auth')

@section('custom')
    @parent
    <script src="{{ asset('js/custom/player_stats.js') }}"></script>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">History
                            <span class="player-title"></span>
                        </h3>
                    </div>
                    <div class="box-body with-border">
                        <div class="media discussion-widget-block">
                            <div class="media-body">

                                    <div class="well">

                                        @if($isAdmin)
                                        <div class="row" style="margin-bottom: 10px;">
                                            <div class="col-xs-3" style="padding-right: 0">
                                                <input type="button" class="btn btn-xs btn-warning btn-raised btn-block suspend-player hide"
                                                      value="Suspend" id="btn_suspend">
                                            </div>
                                            <div class="col-xs-1" style="padding-left: 5px;">
                                                <i class="fa fa-info-circle suspend-player hide pull-left" style="margin-top: 4px" data-container="body"
                                                   data-placement="right" data-toggle="popover" data-trigger="hover"
                                                   data-content="Inactive for more than 3 months"></i>
                                            </div>
                                            <div class="col-xs-4"></div>
                                            <div class="col-xs-1" style="padding-right: 5px;">
                                                <i class="fa fa-info-circle delete-player hide pull-right" style="margin-top: 4px" data-container="body"
                                                   data-placement="left" data-toggle="popover" data-trigger="hover"
                                                   data-content="Never played, safe to delete"></i>
                                            </div>
                                            <div class="col-xs-3" style="padding-left: 0">
                                                <input type="button" class="btn btn-xs btn-danger btn-raised btn-block delete-player hide"
                                                       value="Delete" id="btn_delete">
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row" style="margin-bottom: 10px;">
                                            <div class="col-xs-4">
                                                <span class="label label-outline label-success block win-stats"></span>
                                            </div>
                                            <div class="col-xs-4">
                                                <span class="label label-outline label-info block draw-stats"></span>
                                            </div>
                                            <div class="col-xs-4">
                                                <span class="label label-outline label-danger block lost-stats"></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <select id="players_list" class="selectpicker dropup show-tick show-menu-arrow"
                                                        data-live-search="true" data-style="btn-default" data-container="body"
                                                        data-size="5" data-width="100%"></select>
                                            </div>
                                            <div class="col-xs-6">
                                                <select id="years_list" class="selectpicker show-tick show-menu-arrow dropup"
                                                        data-style="btn-default" data-container="body"
                                                        data-size="5" data-width="100%"></select>
                                            </div>
                                        </div>
                                    </div>

                                <div class="table-responsive" style="overflow:auto;">

                                    <table id="player_stats_tbl" class="table table-hover"
                                           data-side-pagination="server"
                                           data-minimum-count-columns="2"
                                           data-show-pagination-switch="false"
                                           data-pagination="true"
                                           data-total-field='total'
                                           data-sort-order="desc"
                                           data-page-list="[10, 25, 50, 100]">
                                        <thead>
                                        <tr>
                                            <th data-field="game_id" data-sortable="true">Game ID</th>
                                            <th data-field="status" data-sortable="true">Result</th>
                                            <th data-field="starts_at" data-sortable="true">Started Date/Time</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-center">

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection