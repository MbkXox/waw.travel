# Waw - Where Are We

Waw (Where Are We) est une plateforme sociale innovante dédiée aux passionnés de road trips, développée par l'agence **New Horizons**.

## Description

Waw permet aux voyageurs de partager facilement leurs aventures en road trip avec leur famille et amis. La plateforme offre une expérience immersive et conviviale pour documenter et suivre les voyages en temps réel.

## Fonctionnalités principales

- **Création de profils utilisateurs**
- **Partage de photos, vidéos et mises à jour de voyage**
- **Géolocalisation et cartographie des itinéraires**
- **Interaction sociale** (commentaires, likes, partages)
- **Intégration avec les réseaux sociaux existants**
- **Interface responsive** (web et mobile)

## Installation

Pour cloner le projet et le démarrer localement, suivez ces étapes :

```bash
git clone https://github.com/new-horizons/waw.git
cd waw
composer install
symfony server:start -d
symfony console doctrine:fixtures:load