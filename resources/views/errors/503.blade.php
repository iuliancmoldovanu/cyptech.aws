
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Lavalite</title>
    <meta name="description" content="The Lavalite Content Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="http://localhost:8000/apple-touch-icon.png">
    <link href="http://localhost:8000/css/vendor_public.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link media="all" type="text/css" rel="stylesheet" href="http://localhost:8000/themes/public/assets/css/main.css">

    <script src="http://localhost:8000/packages/jquery/js/jquery.min.js"></script>

    <style>
        @import  url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800');

        * {
            line-height: 1.2;
            margin: 0;
        }

        html {
            color: #888;
            display: table;
            font-family: 'Open Sans', sans-serif;
            height: 100%;
            text-align: center;
            width: 100%;
        }

        body {
            display: table-cell;
            vertical-align: middle;
            margin: 2em auto;
        }

        h1 {
            color: #ef5350;
            font-size: 8em;
            font-weight: 700;
            text-transform: uppercase;
        }
        h2 {
            font-weight: 700;
            font-size: 2em;
            text-transform: uppercase;
            color: #555;
        }
        h2 span {
            font-weight: 300;
        }

        p {
            margin: 0 auto;
            width: 280px;
            margin-top: 10px;
        }

        @media  only screen and (max-width: 280px) {

            body, p {
                width: 95%;
            }

            h1 {
                font-size: 1.5em;
                margin: 0 0 0.3em;
            }

        }

    </style>
</head>
<body class="blank">

<h1>Come back <span>later</span>.</h1>
<p>We are working on it.</p>
<div class="pull-right">
    <a href="{{ URL::to('logout') }}" class="btn btn-default btn-flat">Go back</a>
</div>

</body>
</html>
