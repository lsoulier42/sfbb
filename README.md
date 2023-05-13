
# SFBB - Symfony Bulletin Board

Reproduction du forum phpBB en symfony

## Specifications:
- php 8.1
- symfony 6.2
- postgresql 15
- nginx 1.23

## Utilisation :
- make install : build des images docker, composer install, npm install et build assets
- make start : démarrage des images php, nginx et postgresql
- make stop : arrêt des containers du projet
- make connect / node-connect : shell dans les containers php / nodejs
- make clear : vidage du cache
- make composer-update : mise à jour des vendors php
- make node-install : installation des vendors js
- make node-build : compilation des assets js et scss

- url par défaut en mode dev : http://localhost:8180