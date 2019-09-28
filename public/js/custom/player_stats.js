var player_id = window.location.hash.substring(1);
var onLoadData = {
    reload: true,
    players: [],
    player: "Not loaded",
    years: []
};
var loadPlayerStats = function(year){
    if(player_id) {
        year = (typeof year === "undefined" ? 0 : year);
        $("#player_stats_tbl").bootstrapTable('destroy').bootstrapTable({
            url: '/result_games/table/' + player_id + '?load='+onLoadData.reload+'&year='+ year,
            onLoadSuccess: function (data) {

                var title = parseInt(year) === 0 ? "All Times" : year;
                $(".player-title").html( " - " + data.player + " Stats " + title);

                $.each(data.stats, function(k, v){
                    $("."+k+"-stats").html(v);
                });
                onLoadData.player = data.player;

                if(onLoadData.reload){ // load one time
                    var opts = '';
                    onLoadData.players = data.players;
                    onLoadData.years = data.years;
                    $.each(onLoadData.players, function(k, v){
                        opts += '<option value="'+v.p_id+'" '+(v.p_id === parseInt(player_id) ? 'selected' : '' )+'>'+v.username+'</option>'
                    });
                    $("#players_list").html(opts).selectpicker( "refresh");

                    opts = '<option value="0">All Times</option>';
                    $.each(data.years, function(k, v){
                        opts += '<option value="'+v.year+'" '+(v.year === parseInt(year) ? 'selected' : '' )+'>'+v.year+'</option>';
                    });
                    $("#years_list").html(opts).selectpicker( "refresh");

                    onLoadData.reload = false;
                }else{
                    $("#players_list").selectpicker("val", player_id);
                    $("#years_list").selectpicker("val", parseInt(year));
                }

                if(data.allowUpdate){
                    $('.suspend-player').removeClass('hide');
                }else{
                    $('.suspend-player').addClass('hide');
                }

                if(parseInt(data.total) || !data.allowUpdate){
                    $('.delete-player').addClass('hide');
                }else{
                    $('.delete-player').removeClass('hide');
                }

                document.title = data.player + " Stats " + title;

            },
            onLoadError: function () {
                console.log('error here');
            }
        });
    } else {
        console.log('error');
    }
};
loadPlayerStats();

$(document).on('change', '#players_list', function(){
    player_id = $(this).val();
    loadPlayerStats();
});
$(document).on('change', '#years_list', function(){
    loadPlayerStats($(this).val());
});

$(document).on('click', '#btn_suspend', function(){
    var $this = $(this);
    $this.addClass('disabled').attr('disabled', true);
    bootbox.confirm({
        title: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Do you really want to suspend '+onLoadData.player+' ?</div>',
        message: '<p class="text-center">Confirming this action the player will not be able to access the app.</p>',
        closeButton: false,
        className: 'rubberBand animated',
        size: 'small',
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if(result){

                $.post('/suspend', {player_id}, function () {
                    toastr.success('Player suspended "' + onLoadData.player + '"', 'Success');
                    $this.removeClass('disabled').attr('disabled', false);
                    $(".suspend-player").addClass("hide");
                    player_id = $('#players_list option:not(:selected)')[0].value;
                    onLoadData.reload = true;
                    loadPlayerStats();
                }).fail(function () {
                    $this.removeClass('disabled').attr('disabled', false);
                });
            }else{
                $this.removeClass('disabled').attr('disabled', false);
            }
        }
    });
});


$(document).on('click', '#btn_delete', function(){
    var $this = $(this);
    $this.addClass('disabled').attr('disabled', true);
    bootbox.confirm({
        title: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Do you really want to delete '+onLoadData.player+' ?</div>',
        message: '<p class="text-center">Confirming this action the player will completely removed from the app</p>',
        closeButton: false,
        className: 'rubberBand animated',
        size: 'small',
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if(result){

                $.post('/delete', {player_id}, function () {
                    toastr.success('Player suspended "' + onLoadData.player + '"', 'Success');
                    $this.removeClass('disabled').attr('disabled', false);
                    $(".suspend-player").addClass("hide");
                    $(".delete-player").addClass("hide");
                    player_id = $('#players_list option:not(:selected)')[0].value;
                    onLoadData.reload = true;
                    loadPlayerStats();
                }).fail(function () {
                    $this.removeClass('disabled').attr('disabled', false);
                });
            }else{
                $this.removeClass('disabled').attr('disabled', false);
            }
        }
    });
});