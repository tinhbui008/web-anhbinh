#!/bin/bash

# Script để setup SSL certificate cho production
# Chạy script này lần đầu tiên khi deploy lên server

set -e

echo "================================================"
echo "🔒 Setup SSL Certificate cho binhdi.io.vn"
echo "================================================"

# Kiểm tra docker-compose đang chạy
if ! docker ps | grep -q "nginx_prod"; then
    echo "❌ Nginx container chưa chạy!"
    echo "Vui lòng chạy: docker-compose -f docker-compose.production.yml up -d"
    exit 1
fi

echo ""
echo "📋 Các bước sẽ thực hiện:"
echo "1. Chạy certbot container để lấy SSL certificate"
echo "2. Sử dụng webroot method (không cần stop nginx)"
echo "3. Certificate sẽ được lưu trong Docker volume"
echo ""

read -p "Bấm Enter để tiếp tục hoặc Ctrl+C để hủy..."

# Lấy SSL certificate
echo ""
echo "🔄 Đang lấy SSL certificate từ Let's Encrypt..."
echo ""

docker-compose -f docker-compose.production.yml run --rm certbot \
    certonly --webroot \
    --webroot-path=/var/www/certbot \
    --email binh.vu@mikotech.vn \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    -d binhdi.io.vn \
    -d www.binhdi.io.vn

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ SSL certificate đã được lấy thành công!"
    echo ""
    echo "🔄 Đang reload Nginx..."
    docker-compose -f docker-compose.production.yml exec nginx nginx -s reload

    echo ""
    echo "================================================"
    echo "✅ Hoàn tất! SSL đã được cài đặt"
    echo "================================================"
    echo ""
    echo "📝 Các bước tiếp theo:"
    echo "1. Truy cập https://binhdi.io.vn để kiểm tra"
    echo "2. Certbot sẽ tự động renew certificate mỗi 12h"
    echo "3. Không cần làm gì thêm!"
    echo ""
else
    echo ""
    echo "❌ Có lỗi xảy ra khi lấy SSL certificate!"
    echo ""
    echo "🔍 Kiểm tra các điểm sau:"
    echo "1. DNS đã trỏ đúng về server chưa?"
    echo "   - Kiểm tra: dig binhdi.io.vn"
    echo "2. Port 80 đã mở chưa?"
    echo "   - Kiểm tra: sudo ufw status"
    echo "3. Nginx đang chạy và accessible?"
    echo "   - Kiểm tra: curl -I http://binhdi.io.vn"
    echo ""
    exit 1
fi
