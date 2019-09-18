@extends('layouts.auth')
@section('plugin')
    @parent
    <script>
        $('#players_table').bootstrapTable({
            url: "{{ URL::to('admin/players/list') }}"
        });
    </script>
@endsection
@section('content')

    <div id="content">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Players List</h3>
                    <div class="box-tools pull-right">
                        <btn class="btn btn-info btn-xs margin-r-5" title="New" id="btnNewPlayer">
                            <i class="fa fa-user-plus" aria-hidden="true"></i> New player
                        </btn>
                    </div>
                </div>
                <div class="box-body with-border">
                    <div class="table-responsive">
                        @include('admin.includes.players_table')
                    </div>
                    <div class="player-form"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

