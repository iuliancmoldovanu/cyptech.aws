@extends('layouts.auth')

@section('content')
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp
                    Header
                </h3>
            </div>


            <div class="box-body with-border">
                <div class="table-responsive">
                    <table id="visitors_table" class="table table-hover"
                           data-url="/master/visitors_table"
                           data-side-pagination="server"
                           data-search="true"
                           data-show-refresh="true"
                           data-minimum-count-columns="2"
                           data-show-pagination-switch="false"
                           data-pagination="true"
                           data-total-field='total'
                           data-sort-order="desc"
                           data-page-list="[10, 25, 50, 100]">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="true">ID</th>
                                <th data-field="username" data-sortable="true">Username</th>
                                <th data-field="accessed_date" data-sortable="true">Date/Time</th>
                                <th data-field="page" data-sortable="true">Page visited</th>
                                <th data-field="ip" data-sortable="true" >IP</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('local-js')
    <script>
        $('#visitors_table').bootstrapTable();
    </script>
    <script src="{{ asset('js/visitors.js') }}"></script>
@stop