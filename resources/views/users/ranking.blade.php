@extends('layouts.auth')

@section('plugin')
    @parent
    <script>
        $('#players_table').bootstrapTable({
            url: "{{ URL::to('players/list') }}"
        });
    </script>
@endsection

@section('content')
    <div id="content">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Players List</h3>
                </div>
                <div class="box-body with-border">
                    <div class="table-responsive">
                        <table id="players_table" class="table-hover"
                               data-search="true"
                               data-show-refresh="true"
                               data-show-toggle="true"
                               data-show-columns="true"
                               data-show-export="true"
                               data-minimum-count-columns="2"
                               data-show-pagination-switch="false"
                               data-pagination="true"
                               data-id-field="id"
                               data-total-field='total'
                               data-page-list="[10, 25, 50, 100, ALL]">
                            <thead>
                            <tr>
                                <th data-formatter="indexTable" data-field="index" data-switchable="false" data-sortable="false">#</th>
                                <th data-field="username" data-sortable="true">Username</th>
                                <th data-field="player_position" data-sortable="true" >Position</th>
                                <th data-field="total_games" data-sortable="true">Games</th>
                                <th data-field="games_won" data-sortable="true" data-visible="false">Won</th>
                                <th data-field="games_draw" data-sortable="true" data-visible="false">Draw</th>
                                <th data-field="games_lost" data-sortable="true" data-visible="false">Lost</th>
                                <th data-field="points" data-sortable="true" data-visible="false">Points</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection