<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection</title>
    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
            text-align: center;
            font-family: "Segoe UI";
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: grey;
        }

        .redirectClass {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow:  0 8px 24px rgba(17, 24, 39, 0.08);
        }

        #back,
        #leave {
            background-color: #A24246;
            color: #fff;
            border: none;
            border-radius: 10px;
            width: 25%;
            margin: 10px;
            padding: 10px 0;
            font-size: 1em;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="redirectClass">
        <h1>Pour accéder au site</h1>
        <h2>Vous devez accepter la collecte des données pour faire une demande.</h2>
        <button id="back">Retourner sur le site</button>
    </div>
    <script>
        back.onclick = function() {
            window.location = window.location.protocol + '//' + window.location.host;
        };

        window.onload = function () {
            if (choixRgpd === "true") {
                window.location = window.location.protocol + '//' + window.location.host;
            }
        }
    </script>
</body>

</html>