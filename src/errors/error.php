<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur <?= ERROR["code"] ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            background-color: #1a1a1a;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            border-left: 4px solid #8b0000;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .error-code {
            font-size: 5rem;
            font-weight: bold;
            color: #8b0000;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .error-text {
            font-size: 1.5rem;
            color: #8b0000;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .error-description {
            margin-bottom: 2rem;
            line-height: 1.6;
            color: #b0b0b0;
        }

        .home-button {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #8b0000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .home-button:hover {
            background-color: #a00000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 0, 0, 0.4);
        }

        .home-button:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .error-container {
                width: 90%;
                padding: 1.5rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-code {
                font-size: 3.5rem;
            }
            
            .error-text {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-title">Erreur</h1>
        <div class="error-code"><?= ERROR["code"] ?></div>
        <div class="error-text"><?= ERROR["name"] ?></div>
        <p class="error-description">Une erreur est survenue et a été signalée à l'administrateur. Nous nous excusons pour le dérangement</p>
        <a href="/" class="home-button">Retour à l'accueil</a>
    </div>
</body>
</html>