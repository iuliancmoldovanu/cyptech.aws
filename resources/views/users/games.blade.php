@extends('layouts.auth')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">History</h3>
                    </div>
                    <div class="box-body with-border">
                        <div class="media discussion-widget-block">
                            <div class="media-body">
                                @forelse($games as $game) {{-- get games start from latest added --}}
                                <div class="box collapsed-box games">
                                    <div class="btn btn-default btn-xs show" data-widget="collapse">
                                        @if($game->result == '0')
                                            @if($game->generated_by == '-1')
                                                {{ date_format(date_create($game->starts_at), 'D d M Y') }} (Cancelled)
                                            @else
                                                {{ date_format(date_create($game->starts_at), 'D d M Y') }} Processing ...
                                            @endif
                                        @else
                                            {{ date_format(date_create($game->updated_at), 'D d M Y') }}
                                        @endif
                                    </div>
                                    <div class="box-body with-border">
                                        <div class="media discussion-widget-block">
                                            <div class="media-body">
                                                <p class="text-muted">
                                                    @if($game->result == '0')
                                                        @if($game->generated_by == '-1')
                                                            <small>
                                                                Insufficient players
                                                            </small>
                                                        @else
                                                            <small>
                                                                In progress ...
                                                            </small>
                                                        @endif
                                                    @else
                                                        <small>
                                                            <i class="fa fa-{{ $game->result == 'green' ? 'trophy text-green' :  'circle text-success'}}"></i>
                                                            {{ $game->team_green }}</small>
                                                        <br>
                                                        <small>
                                                            <i class="fa fa-{{ $game->result == 'red' ? 'trophy text-red' :  'circle text-red'}}"></i>
                                                            {{ $game->team_red }}</small>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @empty
                                    No games
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-center">
                        {!! str_replace('/?','?',$games->render()) !!}
                        <div class="pull-right">
                            <i class="col-sm-12"> Total: {{$games->total()}} records </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection