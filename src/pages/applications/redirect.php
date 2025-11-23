<?php
/*
Ce fichier redirige les utilisateurs qui se connectent via NextCloud vers l'application de Gestion des affiches
? Cette page est due à un bug qui n'autorise pas la connexion NextCloud a rediriger directement vers affiches-app://callback
*/
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion réussie</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}body{font-family:Arial,sans-serif;min-height:100vh;display:flex;justify-content:center;align-items:center;background-color:#fff5f5}.success-card{background:#fff;padding:2rem;border-radius:10px;box-shadow:0 4px 6px rgba(0,0,0,.1);text-align:center;max-width:400px;width:90%;animation:.5s ease-out slideIn}@keyframes slideIn{from{transform:translateY(-20px);opacity:0}to{transform:translateY(0);opacity:1}}.success-icon{width:80px;height:80px;border-radius:50%;background-color:#dc2626;display:flex;justify-content:center;align-items:center;margin:0 auto 1.5rem;position:relative;animation:.5s ease-out .5s both scaleIn}@keyframes scaleIn{from{transform:scale(0)}to{transform:scale(1)}}.checkmark{width:30px;height:60px;border-right:6px solid #fff;border-bottom:6px solid #fff;transform:rotate(45deg);margin-top:-10px;margin-left:-5px;animation:.5s ease-out 1s both drawCheck}@keyframes drawCheck{from{height:0;width:0;opacity:0}to{height:60px;width:30px;opacity:1}}h1{color:#dc2626;margin-bottom:1rem;font-size:1.5rem}p{color:#666;line-height:1.6;margin-bottom:1.5rem}
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <div class="checkmark"></div>
        </div>
        <h1>Connexion réussie !</h1>
        <p>Vous pouvez fermer cette page.</p>
    </div>

    <script>
        window.location.href = "affiches-app://callback<?= str_replace("/redirect", "", $_SERVER["REQUEST_URI"]) ?>"
    </script>
</body>
</html>