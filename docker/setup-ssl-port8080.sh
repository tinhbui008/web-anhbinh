#!/bin/bash

# Script để setup SSL certificate khi port 80 đã bị sử dụng
# Sử dụng DNS challenge thay vì webroot

set -e

echo "================================================"
echo "🔒 Setup SSL Certificate (Port 80 unavailable)"
echo "================================================"
echo ""
echo "⚠️  Lưu ý: Port 80 đã bị sử dụng trên server"
echo "   → Sử dụng DNS challenge thay vì webroot"
echo ""

# Kiểm tra docker-compose đang chạy
if ! docker ps | grep -q "nginx_prod"; then
    echo "❌ Nginx container chưa chạy!"
    echo "Vui lòng chạy: docker-compose -f docker-compose.production.yml up -d"
    exit 1
fi

echo "📋 Cách hoạt động của DNS challenge:"
echo "1. Certbot sẽ yêu cầu bạn thêm TXT record vào DNS"
echo "2. Bạn phải thêm record này vào DNS provider (VD: Cloudflare)"
echo "3. Sau khi DNS propagate, certbot sẽ verify và cấp certificate"
echo ""

read -p "Bấm Enter để tiếp tục hoặc Ctrl+C để hủy..."

echo ""
echo "🔄 Đang chạy Certbot với DNS challenge..."
echo ""
echo "📝 Hướng dẫn:"
echo "   - Certbot sẽ hiển thị TXT record cần thêm"
echo "   - Format: _acme-challenge.binhdi.io.vn TXT \"value\""
echo "   - Thêm record này vào DNS provider của bạn"
echo "   - Đợi 5-10 phút để DNS propagate"
echo "   - Kiểm tra: dig _acme-challenge.binhdi.io.vn TXT"
echo "   - Bấm Enter trong certbot khi đã xong"
echo ""

# Chạy certbot với manual DNS challenge
docker-compose -f docker-compose.production.yml run --rm \
    -e TERM=xterm-256color \
    certbot certonly \
    --manual \
    --preferred-challenges dns \
    --email binh.vu@mikotech.vn \
    --agree-tos \
    --no-eff-email \
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
    echo "📝 Lưu ý quan trọng:"
    echo "⚠️  DNS challenge KHÔNG tự động renew được!"
    echo ""
    echo "Khi certificate hết hạn (90 ngày), bạn phải:"
    echo "1. Chạy lại script này"
    echo "2. Thêm TXT record mới vào DNS"
    echo "3. Hoặc giải phóng port 80 để dùng webroot method"
    echo ""
    echo "🌐 Truy cập:"
    echo "   - HTTPS: https://binhdi.io.vn"
    echo "   - HTTP:  http://binhdi.io.vn:8080"
    echo ""
else
    echo ""
    echo "❌ Có lỗi xảy ra khi lấy SSL certificate!"
    echo ""
    echo "🔍 Các giải pháp thay thế:"
    echo ""
    echo "1. Dừng nginx cũ để giải phóng port 80:"
    echo "   sudo systemctl stop nginx"
    echo "   Sau đó dùng script setup-ssl.sh (webroot method)"
    echo ""
    echo "2. Sử dụng Cloudflare SSL (miễn phí):"
    echo "   - Thêm domain vào Cloudflare"
    echo "   - Bật SSL/TLS mode: Full"
    echo "   - Cloudflare tự động xử lý HTTPS"
    echo ""
    echo "3. Mua SSL certificate từ nhà cung cấp"
    echo "   - Copy cert vào /etc/letsencrypt/live/binhdi.io.vn/"
    echo ""
    exit 1
fi
