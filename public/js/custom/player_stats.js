var onLoadData = {
    player_id: window.location.hash.substring(1),
    reload: true,
    players: [],
    player: "Not loaded",
    years: []
};
var loadPlayerStats = function(){
    if(onLoadData.player_id) {
        var year = $("#years_list").selectpicker("val");
        year = year === null ? 0 : year;
        $("#player_stats_tbl").bootstrapTable('destroy').bootstrapTable({
            url: '/result_games/table/' + onLoadData.player_id + '?load='+onLoadData.reload+'&year='+ year,
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
                        opts += '<option value="'+v.p_id+'" '+(v.p_id === parseInt(onLoadData.player_id) ? 'selected' : '' )+'>'+v.username+'</option>'
                    });
                    $("#players_list").html(opts).selectpicker( "refresh");

                    opts = '<option value="0">All Times</option>';
                    $.each(data.years, function(k, v){
                        opts += '<option value="'+v.year+'" '+(v.year === parseInt(year) ? 'selected' : '' )+'>'+v.year+'</option>';
                    });
                    $("#years_list").html(opts).selectpicker( "refresh");

                    onLoadData.reload = false;
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
            },
            onExpandRow: function(e, index, row){
                row.html(' - '+index.team_green+'<br>' + ' - '+index.team_red+'<br>');
            }
        });
    } else {
        console.log('error');
    }
};
loadPlayerStats();

$(document).on('change', '#players_list', function(){
    onLoadData.player_id = $(this).val();
    loadPlayerStats();
});
$(document).on('change', '#years_list', function(){
    loadPlayerStats($(this).val());
});


$(document).on('click', '#btn_suspend', function(){
    var $this = $(this);
    $this.addClass('disabled').attr('disabled', true);

    var bootboxOpts = {
        title:  'Do you really want to suspend '+onLoadData.player+' ?',
        message: 'Confirming this action the player will not be able to access the app',
        url: '/suspend',
        this: $this
    };

    bootboxConfirm(bootboxOpts);
});

$(document).on('click', '#btn_delete', function(){
    var $this = $(this);
    $this.addClass('disabled').attr('disabled', true);

    var bootboxOpts = {
        title:  ' Do you really want to delete '+onLoadData.player+' ?',
        message: 'Confirming this action the player will completely removed from the app',
        url: '/delete',
        this: $this
    };

    bootboxConfirm(bootboxOpts);
});


var bootboxConfirm = function (bootboxOpts) {
    bootbox.confirm({
        title: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>' + bootboxOpts.title + '</div>',
        message: '<p class="text-center">' + bootboxOpts.title + '</p>',
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
                $.post(bootboxOpts.url, {player_id: onLoadData.player_id}, function () {
                    toastr.success('Player suspended "' + onLoadData.player + '"', 'Success');
                    bootboxOpts.this.removeClass('disabled').attr('disabled', false);
                    onLoadData.player_id = $('#players_list option:not(:selected)')[0].value;
                    onLoadData.reload = true;
                    loadPlayerStats();
                }).fail(function () {
                    bootboxOpts.this.removeClass('disabled').attr('disabled', false);
                });
            }else{
                bootboxOpts.this.removeClass('disabled').attr('disabled', false);
            }
        }
    });
};