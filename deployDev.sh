echo "Deploy in dev"
echo "Pull last changes"
sudo git pull origin develop
echo "Change owner for build"
sudo chown -R ingenius:www-data /var/www/andamiosdev.ingeniuscloud.net/
npm run build
sudo chown -R www-data:www-data /var/www/andamiosdev.ingeniuscloud.net/
echo "Deployed in dev"




