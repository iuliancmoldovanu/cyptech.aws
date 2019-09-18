@extends('layouts.auth')

@section('content')
    <div class="loading"></div>

    <div id="content"></div>

    <script>
        $('.loading').show();

        function ajaxLoad(filename, content) {
            // filename => url
            console.log(filename);
            console.log(content);
            content = typeof content !== 'undefined' ? content : 'content';
            console.log(content);
            $.ajax({
                type: "GET",
                url: filename,
                contentType: false,
                success: function (data, textStatus, jqXHR) {
                    $("#" + content).html(data);
                    $('.loading').hide();
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                statusCode: {
                    404: function () {
                        alert("page not found");
                    }
                }
            });
        }
        $(document).ready(function () {
            ajaxLoad('users/list');
        });
    </script>
@endsection