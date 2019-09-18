$(function () {
    jQuery.validator.setDefaults({
        debug: true,
        success: "valid",
        errorPlacement: function (error, element) {
            return true;
        }
    });

    $('.html-editor-mini').summernote({
        height: "200px",
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    $('.html-editor').summernote({
        height: "200px",
        onImageUpload: function (files, editor, welEditable) {
            app.sendFile(files[0], editor, welEditable);
        }
    });

    $('input .pickadate').pickadate({
        format: 'dd mmm, yyyy',
        formatSubmit: 'yyyy-mm-dd',
        hiddenSuffix: '',
        selectMonths: true,
        selectYears: true
    }).prop('type', 'text');

    $('input .pickatime').pickatime({
        format: 'h:i A',
        formatSubmit: 'HH:i:00',
        hiddenSuffix: '',
        interval: 10,
        selectMonths: true,
        selectYears: true
    }).prop('type', 'text');

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
    */
    $('body').on('click', '[data-action]', function (e) {
        e.preventDefault();

        var $tag = $(this);

        if ($tag.data('action') == 'CREATE')
            return app.create($tag.data('form'), $tag.data('load-to'), $tag.data('datatable'));

        if ($tag.data('action') == 'UPDATE')
            return app.update($tag.data('form'), $tag.data('load-to'), $tag.data('datatable'));

        if ($tag.data('action') == 'DELETE') {
            return app.delete($tag.data('href'), $tag.data('load-to'), $tag.data('datatable'));
        }
        if ($tag.data('action') == 'REQUEST')
            return app.makeRequest($tag.data('method'), $tag.data('href'));

        app.load($tag.data('load-to'), $tag.data('href'));
    });

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    jQuery("time.timeago").timeago();
});

$(document).ajaxComplete(function () {
    $("form[id$='-show'] :input").prop("disabled", true);

    $('.html-editor').summernote({
        height: "200px",
        onImageUpload: function (files) {
            url = $(this).data('upload');
            app.sendFile(files[0], url, $(this));
        }
    });

    $('.html-editor-mini').summernote({
        height: "200px",
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    $('input .pickadate').pickadate({
        format: 'dd mmm, yyyy',
        formatSubmit: 'yyyy-mm-dd',
        hiddenSuffix: '',
        selectMonths: true,
        selectYears: true
    }).prop('type', 'text');

    $('input .pickatime').pickatime({
        format: 'h:i A',
        formatSubmit: 'HH:i:00',
        hiddenSuffix: '',
        interval: 10,
        selectMonths: true,
        selectYears: true
    }).prop('type', 'text');

    $.AdminLTE.boxWidget.activate()


});


$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
    app.message(jqxhr);
});

$(document).ajaxSuccess(function (event, xhr, settings) {
    app.message(xhr);
});


var app = {

    'create': function (forms, tag, datatable) {
        var form = $(forms);

        if (form.valid() == false) {
            toastr.error('Please enter valid information.', 'Error');
            return false;
        }

        var formData = new FormData();
        params = form.serializeArray();

        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });

        $.each($(forms + ' .html-editor'), function (i, val) {
            formData.append(val.name, $('#' + val.id).code());
        });

        var url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                app.load(tag, data.redirect);
                $(datatable).DataTable().ajax.reload(null, false);
            }
        });
    },

    'update': function (forms, tag, datatable) {
        var form = $(forms);

        if (form.valid() == false) {
            toastr.error('Please enter valid information.', 'Error');
            return false;
        }

        var formData = new FormData($(forms));
        params = form.serializeArray();

        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });

        $.each($(forms + ' .html-editor'), function (i, val) {
            formData.append(val.name, $('#' + val.id).code());
        });

        var url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                app.load(tag, data.redirect);
                $(datatable).DataTable().ajax.reload(null, false);
            }
        });
    },

    'delete': function (target, tag, datatable) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            var data = new FormData();
            $.ajax({
                url: target,
                type: 'DELETE',
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    swal("Deleted!", data.message, "success");
                    app.load(tag, data.redirect);
                    $(datatable).DataTable().ajax.reload(null, false);
                },
                error: function (data, textStatus, jqXHR) {
                    console.log(data);
                    swal("Delete failed!", data.message, "error");
                },
            });
        });
    },

    'load': function (tag, target) {
        console.log(tag + ' ' + target);
        $(tag).load(target);
    },

    'sendFile': function (file, url, editor) {
        var data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: url,
            cache: false,
            contentType: false,
            processData: false,
            success: function (objFile) {
                editor.summernote('insertImage', objFile.folder + objFile.file);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    },

    'dataTable': function (aoData) {
        var iSortBy = jQuery.grep(aoData, function (n, i) {
            console.log(n);
            return (n.name == 'iSortCol_0');
        });

        sSortBy = jQuery.grep(aoData, function (n, i) {
            return (n.name == 'mDataProp_' + iSortBy[0].value);
        });
        aoData.push({'name': 'sortBy', 'value': sSortBy[0].value});

        iSortOrder = jQuery.grep(aoData, function (n, i) {
            return (n.name == 'sSortDir_0');
        });
        aoData.push({'name': 'sortOrder', 'value': iSortOrder[0].value});

        page = jQuery.grep(aoData, function (n, i) {
            return (n.name == 'iDisplayStart');
        });
        page = page[0].value;

        pageLimit = jQuery.grep(aoData, function (n, i) {
            return (n.name == 'iDisplayLength');
        });
        pageLimit = pageLimit[0].value;

        aoData.push({'name': 'page', 'value': (page / pageLimit) + 1});
        aoData.push({'name': 'pageLimit', 'value': pageLimit});
    },

    'makeRequest': function (method, target) {
        $.ajax({
            url: target,
            type: method,
            success: function (data, textStatus, jqXHR) {
                app.message(jqXHR);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                app.message(jqXHR);
            }
        });
    },

    'message': function (info) {

        if (info.status === 200) {
            return true;
        }

        var msgType = '';
        var msgTitle = '';
        var msgText = '';

        if (info.status === 201) {
            msgTitle = 'Success';
            msgType = 'success';
            msgText = $.parseJSON(info.responseText).message;
        } else if (info.status === 422) {
            msgType = 'warning';
            msgTitle = info.statusText;
            $.each($.parseJSON(info.responseText), function (key, val) {
                msgText += val + "<br>";
            });
        } else if (info.status >= 100 && info.status <= 199) {
            msgTitle = 'Info';
            msgType = 'info';
            msgText = info.statusText;
        } else if (info.status >= 202 && info.status <= 299) {
            msgTitle = 'Success';
            msgType = 'success';
            msgText = info.statusText;
        } else if (info.status >= 400 && info.status <= 499) {
            msgTitle = 'Warning';
            msgType = 'warning';
            msgText = info.statusText;
        } else if (info.status >= 500 && info.status <= 599) {
            msgType = 'error';
            msgTitle = 'Error';
            msgText = info.statusText;
        }

        if (msgType !== "") {
            toastr[msgType](msgText, msgTitle);
        }

        return true;
    }
};

$(document).on("click", "#btnEditPlayer", function () {
    var $thisBtn = $(this);
    $('.table-responsive').addClass('hide');
    $.get('player/update/' + $thisBtn.data('id'),
        function (result) {
            $('.player-form').html(result.playerDetails);
        }
    );
});

$(document).on("click", "#btnNewPlayer", function () {
    var $this = $(this);
    $this.addClass('hide');
    $('.table-responsive').addClass('hide');
    $.get('player/create',
        function (result) {
            $('.player-form').html(result.newPlayerForm);
        }
    ).error(function () {
        $this.removeClass('hide');
    });
});

$(document).on("click", ".hide-player-details", function () {
    $('.table-responsive').removeClass('hide');
    $('.player-form').empty();
});

$(document).on("click", ".hide-new-player-form", function () {
    $('.table-responsive').removeClass('hide');
    $("#btnNewPlayer").removeClass('hide');
    $('.player-form').empty();
});

$(document).on("click", "#btnUpdatePlayer", function () {
    var $thisBtn = $(this);
    var $thisForm = $thisBtn.closest('form');
    var dialog = bootbox.dialog({
        message: '<p class="text-center"><i class="fa fa-spin fa-spinner"></i> Sending the request, please wait...</p>',
        closeButton: false,
        backdrop: true,
        className: "modal-center"
    });

    $.post('player/update/' + $thisBtn.data('id'), $thisForm.serialize(), function (data) {
        if (data.status === "OK") {
            $('#players_table').bootstrapTable('refresh', {
                url: "players/list"
            });
            $('.table-responsive').removeClass('hide');
            $('.player-form').empty();
            toastr['success']('Player updated', 'Success');
        } else {
            $thisForm.find('input.required, textarea.required').each(function () {
                var index = $(this).attr('name');
                if (index in data.errors) {
                    $("#form-" + index + "-error").addClass("has-error");
                    $("#" + index + "-error").html(data.errors[index]);
                }
                else {
                    $("#form-" + index + "-error").removeClass("has-error");
                    $("#" + index + "-error").empty();
                }
            });
            toastr['error']('Please check the red fields', 'Error');
        }
        dialog.modal("hide");
    });
});

$(document).on("click", "#btnCreatePlayer", function () {
    var $thisBtn = $(this);
    var $thisForm = $thisBtn.closest('form');
    var dialog = bootbox.dialog({
        message: '<p class="text-center"><i class="fa fa-spin fa-spinner"></i> Sending the request, please wait...</p>',
        closeButton: false,
        backdrop: true,
        className: "modal-center"
    });

    $.post('player/create/post', $thisForm.serialize(), function (data) {
        if (data.status === "OK") {
            $('#players_table').bootstrapTable('refresh', {
                url: "players/list"
            });
            $('.table-responsive').removeClass('hide');
            $('.player-form').empty();
            toastr['success']('New player created', 'Success');
        } else {
            $thisForm.find('input.required, textarea.required').each(function () {
                var index = $(this).attr('name');
                if (index in data.errors) {
                    $("#form-" + index + "-error").addClass("has-error");
                    $("#" + index + "-error").html(data.errors[index]);
                }
                else {
                    $("#form-" + index + "-error").removeClass("has-error");
                    $("#" + index + "-error").empty();
                }
            });
            toastr['error']('Please check the red fields', 'Error');
        }
        $("#btnNewPlayer").removeClass('hide');
        dialog.modal("hide");
    });
});

$(document).on("click", '#btnStatusPlayer', function () {
    var $thisBtn = $(this);
    var statusType = $thisBtn.data('status-type');
    var playerId = $thisBtn.data('id');
    var isActiveRequest = statusType === 'active';
    var text = isActiveRequest ? 'This player will be activated!' : 'This player will be disabled!';
    var type = isActiveRequest ? 'info' : 'warning';
    var resultText = isActiveRequest ? 'The player has been added to the player list!' : 'The player has been removed from the player list!';
    var resultTitle = isActiveRequest ? 'Player added!' : 'Player disabled!';
    swal({
        title: 'Are you sure?',
        text: text,
        type: type,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        customClass: 'btn-xs',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then(function () {
        $.get('player/' + statusType + '/' + playerId, function (result) {
            if (result.status === 'OK') {
                $('#players_table').bootstrapTable('refresh', {
                    url: "players/list"
                });
            }
        });
        swal(resultTitle, resultText, 'success');
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal(
                'Cancelled',
                'Nothing has been change :)',
                'error'
            )
        }
    });
});

$(document).on("click", '#btnConfirm', function () {
    var $this = $(this);
    $this.addClass('disabled');
    $.get('user/confirm', function (result) {
        if (result === 'success') {
            toastr.success('Your confirmation has been accepted.', 'Success', {
                onHidden: function () {
                    window.location = "/dashboard";
                }
            });
        } else {
            toastr['error']('Sorry, to late. Someone confirmed just before you pressed the button', 'Error');
        }
    });
});

$(document).on("click", '#btnUnavailable', function () {
    var $this = $(this);
    $this.addClass('disabled');
    $('#btnConfirm').addClass('disabled');
    $.get('user/unavailable/' + $('#weeks').val(), function (result) {
        toastr.warning('You\'ll be unavailable for ' + $('#weeks').val() + " weeks", 'Success', {
            onHidden: function () {
                window.location = "/dashboard";
            }
        });
    });
});

$(document).on("click", '#reset_teams', function () {
    var $this = $(this);
    $this.addClass('disabled').attr('disabled', true);
    bootbox.confirm({
        title: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Reset Teams</div>',
        message: '<p>Do you really want to reset the teams?</p>',
        closeButton: false,
        className: 'rubberBand animated',
        size: 'small',
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> No'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Yes, reset it'
            }
        },
        callback: function (result) {
            if(result){
                $.get('/admin/cancel_teams', function () {
                    toastr.success('Teams reset', 'Success');
                }).error(function () {
                    toastr.warning('Reset the teams failed', 'Error, something wrong happened', {
                        onHidden: function () {
                            $this.removeClass('disabled').attr('disabled', false);
                        }
                    });
                });
            }else{
                toastr.warning('Resetting the teams aborted', 'Action decline', {
                    onHidden: function () {
                        $this.removeClass('disabled').attr('disabled', false);
                    }
                });
            }
        }
    });
});

$(document).on("click", '#btnCancelGame', function () {
    var $this = $(this);
    $this.addClass('disabled').attr('disabled', true);
    bootbox.confirm({
        title: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Cancel current game</div>',
        message: '<p>Do you really want to cancel the game this week?</p>',
        closeButton: false,
        className: 'rubberBand animated',
        size: 'small',
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> No'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Yes, cancel it'
            }
        },
        callback: function (result) {
            if(result){
                $.get('/admin/cancel_game', function () {
                    toastr.success('Game canceled', 'Success');
                }).error(function () {
                    toastr.warning('Game is still active', 'Error, something wrong happened', {
                        onHidden: function () {
                            $this.removeClass('disabled').attr('disabled', false);
                        }
                    });
                });
            }else{
                toastr.warning('Game is still active', 'Action decline', {
                    onHidden: function () {
                        $this.removeClass('disabled').attr('disabled', false);
                    }
                });
            }
        }
    });

});

