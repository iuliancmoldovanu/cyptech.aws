
$(document).off('click', '#btnGenerateTeams')
    .on('click', '#btnGenerateTeams', function (e) {
        e.preventDefault();
        var $this = $(this);
       $this.html(" Please wait ... ").addClass('disabled').attr("disabled", true);

        $.get('/generate_teams', function (data) {
            toastr.success(data.message, data.status,{ onHidden: function() {
                    $this.remove();
                    $('#players-section').empty().html(data.players_section);
                    $('#header-content').empty().html(data.header_message);
                }});
    });
});