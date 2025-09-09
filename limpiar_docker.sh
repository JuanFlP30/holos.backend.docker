echo "eliminando imagenes no utilizadas..."

docker image prune -a -f

echo "¡Limpio!"
