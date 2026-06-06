#!/bin/bash
# ============================================================
# deploy.sh — হীরক জয়ন্তী Production Deployment Script
# সার্ভারে SSH দিয়ে প্রথমবার: bash deploy.sh
# পরবর্তী আপডেটে: bash deploy.sh update
# ============================================================

set -e

MODE=${1:-"full"}
echo "=============================="
echo " JSS Age60 Deployment Script"
echo " Mode: $MODE"
echo "=============================="

# --- প্রথমবার সম্পূর্ণ সেটআপ ---
if [ "$MODE" = "full" ]; then

    # .env ফাইল তৈরি (না থাকলে)
    if [ ! -f ".env" ]; then
        cp .env.example .env
        echo "✅ .env তৈরি হয়েছে — DB credentials পূরণ করুন"
        echo "    nano .env"
        echo ""
        echo "⚠️  DB credentials পূরণ করার পর আবার চালান: bash deploy.sh setup"
        exit 0
    fi

    echo "⚙️  Composer install..."
    composer install --no-dev --optimize-autoloader

    echo "🔑 APP_KEY generate..."
    php artisan key:generate --force

    echo "🗄️  Database migrate..."
    php artisan migrate --force

    echo "📁 Storage link..."
    php artisan storage:link || true

    echo "🔒 Permissions..."
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

    echo "⚡ Cache optimize..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    echo ""
    echo "✅ সম্পূর্ণ সেটআপ শেষ!"

# --- আপডেট (git pull এর পর) ---
elif [ "$MODE" = "update" ]; then

    echo "🔄 git pull..."
    git pull origin main

    echo "⚙️  Composer update..."
    composer install --no-dev --optimize-autoloader

    echo "🗄️  Database migrate..."
    php artisan migrate --force

    echo "🔒 Permissions..."
    chmod -R 775 storage bootstrap/cache

    echo "⚡ Cache refresh..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    echo ""
    echo "✅ আপডেট সম্পন্ন!"

# --- শুধু cache clear ---
elif [ "$MODE" = "cache" ]; then
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    echo "✅ Cache cleared!"

fi
