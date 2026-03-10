<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            padding: 15px;
            text-align: justify;
            font-family: "Bell MT";
            font-style: normal;
            font-size: 22px;
        }
    </style>
</head>
<body>
<div>
    <section>
        {{$viewData['title']}} <br>
        {{$viewData['message']}} <br>
        {{$viewData['message1']}}
        <br>
        Merci,<br>
        <b>SCB-LAFARGE,{{ config('app.name') }}</b>
    </section>
</div>

</body>
</html>
