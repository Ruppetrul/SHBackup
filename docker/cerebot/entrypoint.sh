certbot certonly --webroot --webroot-path=/var/www/certbot \
  --email nautbek@gmail.com \
  --agree-tos \
  --no-eff-email \
  -d simply-shop.ru

exec "$@"
