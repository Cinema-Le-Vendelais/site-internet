# Site Web - Cinéma Le Vendelais

> Site web public du cinéma associatif Le Vendelais

## À propos

Ce dépôt contient la partie **publique** du site web du Cinéma Le Vendelais.

Le site permet aux visiteurs de :
- Consulter la programmation des films
- Découvrir l'association et ses activités
- Suivre l'actualité du cinéma
- Trouver les informations pratiques (horaires, tarifs, accès)
- Commandes des affiches
- Contacter le cinéma

## Architecture

```
pages.json           # Index des pages
index.php            # Redistribution de l'url vers la page correspondante
src/
├── tools/           # Outils utiles pour le développement (BD, ...)
├── pages/           # Tous les fichiers des pages
├── includes/        # Fichiers requis pour chaque page (header, footer, ...)
└── errors/          # Pages d'erreurs
```

## Sécurité

⚠️ **Important** : Ce dépôt contient uniquement le code **front-end public**. 

- Aucune donnée sensible (clés API, mots de passe, tokens) ne doit être commitée
- Les fichiers de configuration contenant des secrets sont listés dans `.gitignore`
- La partie back-end et la base de données sont hébergées séparément

Un hook pre-commit avec **Gitleaks** est configuré pour prévenir toute fuite accidentelle de secrets.

## Installation

### Prérequis

- PHP >= 8.4
- Composer >= 2.x

#### Ce projet doit être dans un dossier parent ùu vous pourrez exécuter : 

### Installation des dépendances (retrouvez composer.json dans le repo : X)

```bash
composer install
```

### Configuration

1. Copiez le fichier de configuration exemple :
```bash
cp .env.example .env
```

2. Renseignez les variables d'environnement nécessaires dans `.env`

## Contribution

Pour l'instant, aucune contribution n'est attendue.

## 📞 Contact

**Cinéma Le Vendelais**
- Site web : [levendelaiscinema.fr](https://levendelaiscinema.fr)
- Email : association@levendelaiscinema.fr

---

💙 Développé avec passion par Liam Charpentier pour promouvoir le cinéma associatif.