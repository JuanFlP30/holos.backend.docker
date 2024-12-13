#!/bin/bash
read -p "Usuario del sistema: " myuser

chown -R $myuser:www-data bootstrap/cache/ storage/
chmod -R 775 bootstrap/cache/ storage/

echo "Done!"
