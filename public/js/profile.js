$(document).on("click", "#btnUpdatePassword", function(e){
    e.preventDefault();
    var $thisBtn = $(this);
    var $form = $thisBtn.closest("form");
    var divErrors = $form.find("div.has-error");
    divErrors.removeClass("has-error");
    divErrors.find("span.help-block small").empty();

    $.ajax({
        type: "PATCH",
        url: "/user/update_password",
        data: $form.serialize(),
        success: function (data, textStatus, jqXHR) {
            if(prepareAjaxResult(data, $form)){
                $form.trigger("reset");
            }
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
});
$(document).on("click", "#btnUpdateProfile", function(e){
    e.preventDefault();
    var $thisBtn = $(this);
    var $form = $thisBtn.closest("form");
    var divErrors = $form.find("div.has-error");
    divErrors.removeClass("has-error");
    divErrors.find("span.help-block small").empty();

    $.ajax({
        type: "PATCH",
        url: "/user/update_profile",
        data: $form.serialize(),
        success: function (data, textStatus, jqXHR) {
            prepareAjaxResult(data, $form);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
});

var prepareAjaxResult = function(data){
    if(data.status === "success"){
        toastr[data.status](data.message, data.title);
        return true;
    }else{
        $.each(data, function(idEl, messages){
            $.each(messages, function(k, message){
                var $thisParent = $("#"+idEl).closest("div");
                $thisParent.addClass("has-error");
                $thisParent.find("span.help-block small").append(message+"<br>");
            });
        });
        return false;
    }
};