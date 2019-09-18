<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Ranking List</h3>

        </div>
        <div class="box-body with-border">
            <div class="news-gadget-box pull-left">
                <div class="input-group">
                    <input class="form-control" id="search" value="{{ Session::get('user_search') }}"
                           onkeydown="if (event.keyCode == 13) ajaxLoad('{{url('users/list')}}?ok=1&search='+this.value)"
                           placeholder="Search..."
                           type="text">

                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default"
                                onclick="ajaxLoad('{{url('users/list')}}?ok=1&search='+$('#search').val())">
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
                        <a href="javascript:ajaxLoad('users/list?field=total_games&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Games
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'total_games' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=player_position&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Position
                            <i style="font-size: 12px"
                               class="fa {{ Session::get('user_field') == 'player_position' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    {{--<th>--}}
                        {{--<a href="javascript:ajaxLoad('users/list?field=players.skill_level&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">--}}
                            {{--Skill--}}
                            {{--<i style="font-size: 12px"--}}
                               {{--class="fa  {{ Session::get('user_field') == 'players.skill_level' ? 'fa-sort' : '' }}">--}}
                            {{--</i>--}}
                        {{--</a>--}}
                    {{--</th>--}}

                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=games_won&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Wins
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'games_won' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>

                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=games_draw&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Draw
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'games_draw' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>

                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=games_lost&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Lost
                            <i style="font-size: 12px"
                               class="fa  {{ Session::get('user_field') == 'games_lost' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                    <th>
                        <a href="javascript:ajaxLoad('users/list?field=points&sort={{Session::get("user_sort")=="asc"?"desc":"asc"}}')">
                            Points
                            <i style="font-size: 12px"
                               class="fa {{ Session::get('user_field') == 'points' ? 'fa-sort' : '' }}">
                            </i>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key=>$user)
                    <tr>
                        <td align="center">{{ (++$key) + (($users->currentPage()-1) * $users->perPage()) }} </td>
                        <td>{{$user->username}}</td>
                        <td>
                            {{ $user->total_games }}
                        </td>
                        <td>
                            {{ $user->player_position }}
                        </td>
                        {{--<td>--}}
                            {{--{{ $user->skill_level }}--}}
                        {{--</td>--}}
                        <td>
                            {{ $user->games_won }}
                        </td>
                        <td>
                            {{ $user->games_draw }}
                        </td>
                        <td>
                            {{ $user->games_lost }}
                        </td>
                        <td>
                            {{ $user->points }}
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