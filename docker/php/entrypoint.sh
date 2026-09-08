#!/bin/sh

set -e

echo "======================================"
echo " Initialisation de la base de données"
echo "======================================"

php app seed

echo "======================================"
echo " Seed terminé"
echo "======================================"

exec php-fpm