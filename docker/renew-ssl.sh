#!/bin/bash

# Script để renew SSL certificate thủ công
# Certbot container sẽ tự động renew mỗi 12h,
# nhưng có thể dùng script này để force renew ngay

set -e

echo "================================================"
echo "🔄 Renew SSL Certificate cho binhdi.io.vn"
echo "================================================"

# Kiểm tra docker-compose đang chạy
if ! docker ps | grep -q "nginx_prod"; then
    echo "❌ Nginx container chưa chạy!"
    echo "Vui lòng chạy: docker-compose -f docker-compose.production.yml up -d"
    exit 1
fi

echo ""
echo "📋 Thông tin:"
echo "- Certificate sẽ chỉ renew nếu còn < 30 ngày"
echo "- Để force renew, dùng flag --force-renewal"
echo ""

# Renew SSL certificate
echo "🔄 Đang renew SSL certificate..."
echo ""

docker-compose -f docker-compose.production.yml run --rm certbot renew

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Renew thành công!"
    echo ""
    echo "🔄 Đang reload Nginx..."
    docker-compose -f docker-compose.production.yml exec nginx nginx -s reload

    echo ""
    echo "================================================"
    echo "✅ Hoàn tất! Certificate đã được renew"
    echo "================================================"
    echo ""
else
    echo ""
    echo "❌ Có lỗi xảy ra khi renew certificate!"
    echo ""
    echo "💡 Có thể certificate chưa hết hạn (còn > 30 ngày)"
    echo "   Để force renew, chạy:"
    echo "   docker-compose -f docker-compose.production.yml run --rm certbot renew --force-renewal"
    echo ""
    exit 1
fi
