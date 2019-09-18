<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Users List</h3>
            <div class="box-tools pull-right">
                <a href="javascript:ajaxLoad('users/create')" class="btn btn-primary  btn-xs pull-right">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> New player</a>
            </div>
        </div>
        <div class="box-body with-border">
            <div class="news-gadget-box pull-left">
                <div class="input-group">
                    <input class="form-control" id="search" value="{{ Session::get('user_search') }}"
                           onkeydown="if (event.keyCode == 13) ajaxLoad('{{url('admin/users/list')}}?ok=1&search='+this.value)"
                           placeholder="Search..."
                           type="text">

                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default"
                                onclick="ajaxLoad('{{url('admin/users/list')}}?ok=1&search='+$('#search').val())">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <p class="text-primary"></p>
            </div>
        </div>
        <div class="box-footer clearfix user-table" style="overflow-x:auto;">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th style="text-align: center">#</th>
                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=username&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            User
                            <i style="font-size: 12px"
                               class="fa {{ Session::get('user_field') == 'username' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=last_access_at&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Last access
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'last_access_at' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=total_login&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Logs
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'total_login' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=players.skill_level&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Skill
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'players.skill_level' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key=>$user)
                    <tr>
                        <td align="center">{{ (++$key) + (($users->currentPage()-1) * $users->perPage()) }} </td>
                        <td>{{$user->username}}</td>
                        <td>
                            {{ date_format(date_create($user->last_access_at),"d-M-y") }}
                            {{ date_format(date_create($user->last_access_at),"H:i") }}
                        </td>
                        <td>
                            {{ $user->total_login }}
                        </td>
                        <td>
                            {{ $user->skill_level }}
                        </td>
                        <td style="text-align: center;">
                            <btn class="btn btn-primary btn-xs" title="Edit"
                               href="javascript:ajaxLoad('users/update/{{$user->id}}')">
                                <i class="fa fa-pencil-square-o"></i> Edit</btn>
                            @if($user->active)
                            <btn class="btn btn-danger btn-xs" title="Disable"
                                 onclick="playerStatus('disable', '{{$user->id}}')">
                                <i class="fa fa-trash-o"></i> Disable
                            </btn>
                             @else
                                <btn class="btn btn-default btn-xs" title="Active"
                                     onclick="playerStatus('active', '{{$user->id}}')">
                                    <i class="fa fa-trash-o"></i> Active
                                </btn>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! str_replace('/?','?',$users->render()) !!}
            </div>
            <div class="pull-right">
                <i class="col-sm-12">
                    Total: {{$users->total()}} records
                </i>
            </div>
            <script>
                $('.pagination a').on('click', function (event) {
                    event.preventDefault();
                    ajaxLoad($(this).attr('href'));
                });
            </script>
        </div>
    </div>
</div>