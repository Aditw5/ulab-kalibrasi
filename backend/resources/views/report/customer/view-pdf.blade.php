<!DOCTYPE html>
<html>
<head>
    <title>Lihat PDF AMS</title>
    <style>
        body, html {
            margin: 0; padding: 0;
            height: 100%; width: 100%;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <iframe src="{{ $filepath }}"></iframe>
</body>
</html>
