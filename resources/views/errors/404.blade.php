
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>404 Page not found</title>
    <meta name="description" content="Football group Content Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link media="all" type="text/css" rel="stylesheet" href="{{ url('/css/vendor.css') }}">

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

<h1>404</h1>
<h2>Page Not <span> Found</span></h2>
<p>Sorry, but the page you were trying to view does not exist.</p>
<br>
<a href="{{ url('/dashboard') }}" class="btn btn-default">Go back</a>

</body>
</html>
