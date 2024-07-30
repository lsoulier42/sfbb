# SFBB - Symfony Bulletin Board

Reproduction du forum phpBB en symfony

## Specifications:
- php 8.3
- symfony 6.4
- postgresql 16

## Utilisation :
- make install : build des images docker, composer install et build assets
- make start : démarrage des images php, nginx et postgresql
- make stop : arrêt des containers du projet
- make connect / node-connect : shell dans les containers php
- make clear : vidage du cache
- make composer-update : mise à jour des vendors php

- url par défaut en mode dev : http://localhost:8776