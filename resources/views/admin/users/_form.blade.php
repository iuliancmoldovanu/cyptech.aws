<div class="form-group required" id="form-name-error">
    <label for="name" class="control-label col-md-3">Name</label>
    <div class="col-md-6">
        <input class="form-control required" id="focus" name="name" type="text">
        <span id="name-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-username-error">
    <label for="username" class="control-label col-md-3">Username</label>
    <div class="col-md-6">
        <input class="form-control required" name="username" type="text" id="username">
        <span id="username-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-email-error">
    <label for="email" class="control-label col-md-3">E-mail</label>
    <div class="col-md-6">
        <input class="form-control required" name="email" type="text" id="email">
        <span id="email-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-password-error">
    <label for="password" class="control-label col-md-3">Password</label>
    <div class="col-md-6">
        <input class="form-control required" name="password" type="password" id="password">
        <span id="password-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-password_confirmation-error">
    <label for="password_confirmation" class="control-label col-md-3">Confirm password</label>
    <div class="col-md-6">
        <input class="form-control required" name="password_confirmation" type="password" id="password">
        <span id="password_confirmation-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-role-error">
    <label for="role" class="control-label col-md-3">Role</label>
    <div class="col-md-6">
        <select class="form-control required" name="role" id="role">
            <option value="">Choose one</option>
            @foreach(\App\Player::getPossibleRoles('users') as $key => $role)
                <option value="{{ $key }}">{{ ucwords($key) }}</option>
            @endforeach
        </select>
        <span id="role-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-skill-error">
    <label for="skill" class="control-label col-md-3">Skill level</label>
    <div class="col-md-6">
        <select class="form-control required" name="skill" id="skill">
            <option value="">Choose one</option>
            @for($i = 1; $i < 10; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <span id="skill-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-status-error">
    <label for="status" class="control-label col-md-3">Add to the current game?</label>
    <div class="col-md-6">
        <div class="switch-field">
            <input type="radio" id="switch_left" name="status" value="available">
            <label for="switch_left">Yes</label>
            <input type="radio" id="switch_right" name="status" value="waiting"
                   checked="checked">
            <label for="switch_right">No</label>
        </div>
        <span id="status-error" class="help-block"></span>
    </div>
</div>
<div class="form-group">
    <div class="col-md-6 col-md-push-3">
        <a href="javascript:ajaxLoad('users/list')" class="btn btn-danger">
            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
            Back</a>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-floppy-o" aria-hidden="true"></i>
            Save
        </button>
    </div>
</div>
<script>
    $("#frm").submit(function (event) {
        event.preventDefault();
        $('.loading').show();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.fail) {
                    $('#frm input.required, #frm textarea.required').each(function () {
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
                    $('.loading').hide();
                    $('#focus').focus().select();
                } else {
                    $(".has-error").removeClass("has-error");
                    $(".help-block").empty();
                    $('.loading').hide();
                    ajaxLoad(data.url, data.content);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        return false;
    });
</script>