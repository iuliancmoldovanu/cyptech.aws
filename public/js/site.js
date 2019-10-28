$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

/** Session timeout */
let session = ((session_start_at+lifetime)-(Math.floor(Date.now()/1000)))*1000; // total duration of this session in milliseconds
let run = session-60000 <= 0 ? 1000 : session-60000; // setInterval when reach 60000 milliseconds

let hasBootboxTriggered = false;
let b = "";

let myFunction = function(){
    let end = session_start_at+lifetime; // timestamp (seconds) when this session will expire
    let now = Math.floor(Date.now()/1000); // timestamp (seconds) now
    let seconds_left = end - now;


    if(seconds_left <= 0){ // if timestamp now is bigger than timestamp session, session terminated logout the user

        if( typeof bootbox === "undefined" ){ // refresh
            // refresh the session
            location.reload();
            return;
        }

        if(typeof b.modal !== "undefined"){ // when the session will expire in the next 60 seconds, the setInterval will run every second
            b.modal("hide");
        }
        $.get("/logout"); // logout the user when session expired
        bootbox.alert({
            message: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>' + " Your session has been terminated</div>",
            closeButton: false,
            size: 'small',
            buttons: {
                ok: {
                    label: '<i class="fa fa-times"></i> Ok'
                }
            },
            callback: function () {
                window.location.href = "/login"; // redirect to login
            }
        });

        clearInterval(interval);
        return;
    }

    if(seconds_left <= 60){ // when the session will expire in the next 60 seconds, the setInterval will run every second
        let $TimeSessionCounter = $("#time_session");
        if($TimeSessionCounter.length){
            $TimeSessionCounter.text(seconds_left);
        }

        if(hasBootboxTriggered === true){ return; } // bootbox already triggered

        clearInterval(interval);
        run = 1000; // run every second
        interval = setInterval(myFunction, run);

        if(typeof bootbox === "undefined"){ return; } // when user not logged in

        b = bootbox.confirm({
            title: '<div style="text-align: center; font-size: 16px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>' + " Your session is about to expire in <span id='time_session'>" + seconds_left + "</span> seconds</div>",
            message: '<p>What would you like to do?</p>',
            closeButton: false,
            className: 'rubberBand animated',
            size: 'small',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Logout'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Keep me login'
                }
            },
            callback: function (result) {
                if(result){
                    b.modal("hide");
                    hasBootboxTriggered = false;
                    clearInterval(interval);

                    // refresh the session
                    $.get("/reload_session", function(data){
                        session_start_at = data.session_start_at;
                        lifetime = data.lifetime;
                        session = ((session_start_at+lifetime)-(Math.floor(Date.now()/1000)))*1000; // total duration of this session in milliseconds
                        run = session-60000 <= 0 ? 1000 : session-60000; // setInterval when reach 60000 milliseconds
                        interval = setInterval(myFunction, run);
                    });

                }else{
                    window.location.href = "/logout";
                }
            }
        });

        hasBootboxTriggered = true;
    }else{ // making sure to set the setInterval run to 60 seconds in case the run is bigger than 60 seconds
        clearInterval(interval);
        run = (seconds_left - 60) * 1000;
        interval = setInterval(myFunction, run);
    }

};
let interval = setInterval(myFunction, run);
/** End session timeout */
