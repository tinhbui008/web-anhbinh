#!/bin/bash

# Script để enable HTTPS sau khi đã có SSL certificate

set -e

echo "================================================"
echo "🔒 Enable HTTPS cho Production"
echo "================================================"
echo ""

# Kiểm tra certificate có tồn tại không
echo "📋 Kiểm tra SSL certificate..."

if docker-compose -f docker-compose.production.yml run --rm certbot certificates 2>&1 | grep -q "binhdi.io.vn"; then
    echo "✅ SSL certificate đã tồn tại!"
    echo ""
else
    echo "❌ Chưa có SSL certificate!"
    echo ""
    echo "Vui lòng chạy một trong các lệnh sau:"
    echo "1. ./setup-ssl-port8080.sh (DNS challenge)"
    echo "2. Hoặc dùng Cloudflare SSL"
    echo ""
    exit 1
fi

# Backup config cũ
echo "💾 Backup config hiện tại..."
cp nginx/production.conf nginx/production.conf.backup
echo "   Đã lưu: nginx/production.conf.backup"
echo ""

# Uncomment HTTPS block
echo "🔧 Uncomment HTTPS block trong nginx config..."
sed -i 's/^# server {/server {/g' nginx/production.conf
sed -i 's/^#     /    /g' nginx/production.conf
sed -i 's/^# }/}/g' nginx/production.conf

# Uncomment redirect HTTP -> HTTPS
sed -i 's/^    # return 301 https/    return 301 https/g' nginx/production.conf

echo "✅ Đã uncomment HTTPS config"
echo ""

# Test nginx config
echo "🧪 Test nginx config..."
if docker-compose -f docker-compose.production.yml exec nginx nginx -t 2>&1; then
    echo "✅ Nginx config valid!"
    echo ""
else
    echo "❌ Nginx config có lỗi!"
    echo ""
    echo "Rollback về config cũ..."
    mv nginx/production.conf.backup nginx/production.conf
    exit 1
fi

# Reload nginx
echo "🔄 Reload nginx..."
docker-compose -f docker-compose.production.yml exec nginx nginx -s reload

if [ $? -eq 0 ]; then
    echo ""
    echo "================================================"
    echo "✅ HTTPS đã được kích hoạt!"
    echo "================================================"
    echo ""
    echo "🌐 Truy cập:"
    echo "   - HTTPS: https://binhdi.io.vn:4443"
    echo "   - HTTP sẽ tự động redirect sang HTTPS"
    echo ""
    echo "📝 Nếu dùng Cloudflare:"
    echo "   - User truy cập: https://binhdi.io.vn (không cần port)"
    echo ""
    echo "💡 Backup config:"
    echo "   - File backup: nginx/production.conf.backup"
    echo "   - Để rollback: mv nginx/production.conf.backup nginx/production.conf"
    echo ""
else
    echo ""
    echo "❌ Có lỗi khi reload nginx!"
    echo ""
    echo "Rollback về config cũ..."
    mv nginx/production.conf.backup nginx/production.conf
    docker-compose -f docker-compose.production.yml exec nginx nginx -s reload
    exit 1
fi
