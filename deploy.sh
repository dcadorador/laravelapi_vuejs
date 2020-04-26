set -e
echo 'Deploy starting.'
cd ~/public_html
php artisan down
php artisan queue:restart
php artisan up
npm run dev
echo 'Deploy finished.'
exit 0