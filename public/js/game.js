
$(document).on('click', '#btnCompleteGame', function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.addClass('disabled');
    var $thisForm = $this.closest('form');
    $.post('/admin/complete_game', $thisForm.serialize(), function (data) {
        toastr[data.status](data.message, data.status);
        $this.removeClass('disabled');
        $thisForm.addClass('hide');
        $("#game-updated").removeClass('hide');
    });
});

$(document).on('click', "#red_team", function () {
    $(this).text('Win');
    $("#green_team").text('Lost');
});
$(document).on('click', "#green_team", function () {
    $(this).text('Win');
    $("#red_team").text('Lost');
});
$(document).on('click', "#draw", function () {
    $("#green_team").text('Green');
    $("#red_team").text('Red');
});
