
# Générateur de QR Code

Ce dépôt contient l'application PHP pour générer des QR codes dynamiquement avec la possibilité de personnaliser la taille, la couleur de premier plan et de fond, ainsi que d'ajouter un logo.

## Configuration Requise

- PHP 7.4 ou supérieur
- GD Library pour le traitement d'images
- Composer pour la gestion des dépendances

## Installation

1. **Cloner le dépôt :**
   ```bash
   git clone https://tools.coworking-metz.fr/qr/
   cd qr
   ```

2. **Installer les dépendances :**
   ```bash
   composer install
   ```

3. **Configurer votre serveur web :**
   Assurez-vous que votre serveur web pointe vers le dossier où vous avez cloné le dépôt.

## Utilisation

Pour générer un QR code, faites une requête GET avec les paramètres désirés. Voici un exemple d'utilisation :

```
https://tools.coworking-metz.fr/qr/?url=https://exemple.com&size=300&color=000000&bgcolor=FFFFFF&logo=/path/to/logo.png
```

### Paramètres

- **url** (obligatoire) : L'URL ou le texte à encoder dans le QR code.
- **size** : La taille du QR code en pixels (par défaut 300).
- **color** : La couleur du QR code (par défaut noir, spécifié en hexadécimal).
- **bgcolor** : La couleur de fond du QR code (par défaut blanc, peut être 'transparent').
- **logo** : Le chemin local vers une image logo à incorporer au centre du QR code.

## Contribution

Les contributions à ce projet sont les bienvenues. Veuillez envoyer vos pull requests sur le dépôt GitHub pour toute fonctionnalité ou correction de bug.

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.
