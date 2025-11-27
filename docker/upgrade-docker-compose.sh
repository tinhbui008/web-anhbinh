#!/bin/bash

# Script để upgrade Docker Compose lên version v2.x

set -e

echo "================================================"
echo "🔄 Upgrade Docker Compose lên v2.x"
echo "================================================"
echo ""

# Check current version
CURRENT_VERSION=$(docker-compose --version 2>&1 || echo "not installed")
echo "📦 Version hiện tại: $CURRENT_VERSION"
echo ""

# Warning
echo "⚠️  Cảnh báo:"
echo "   Script này sẽ:"
echo "   1. Xóa Docker Compose v1.x (nếu có)"
echo "   2. Download Docker Compose v2.x mới nhất"
echo "   3. Cài vào /usr/local/bin/docker-compose"
echo ""

read -p "Bấm Enter để tiếp tục hoặc Ctrl+C để hủy..."

echo ""
echo "🗑️  Bước 1: Xóa version cũ..."
sudo apt remove docker-compose -y 2>/dev/null || echo "   Không có package docker-compose từ apt"
sudo rm -f /usr/local/bin/docker-compose 2>/dev/null || echo "   Không có file cũ"

echo ""
echo "⬇️  Bước 2: Download Docker Compose v2.x..."
echo "   Đang download từ GitHub..."

sudo curl -L \
  "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" \
  -o /usr/local/bin/docker-compose

if [ $? -ne 0 ]; then
    echo ""
    echo "❌ Lỗi: Không thể download Docker Compose!"
    echo "   Kiểm tra kết nối internet và thử lại."
    exit 1
fi

echo ""
echo "✅ Bước 3: Cấp quyền execute..."
sudo chmod +x /usr/local/bin/docker-compose

echo ""
echo "🔗 Bước 4: Tạo symlink..."
sudo ln -sf /usr/local/bin/docker-compose /usr/bin/docker-compose

echo ""
echo "================================================"
echo "✅ Hoàn tất!"
echo "================================================"
echo ""

# Check new version
NEW_VERSION=$(docker-compose --version)
echo "📦 Version mới: $NEW_VERSION"
echo ""

# Verify
if docker-compose --version | grep -q "v2"; then
    echo "✅ Upgrade thành công lên Docker Compose v2!"
    echo ""
    echo "📝 Các bước tiếp theo:"
    echo "   1. Chạy lại containers:"
    echo "      cd /path/to/project/docker"
    echo "      docker-compose -f docker-compose.production.yml up -d"
    echo ""
    echo "   2. Kiểm tra containers:"
    echo "      docker ps"
    echo ""
else
    echo "⚠️  Cảnh báo: Có thể upgrade chưa thành công"
    echo "   Version hiện tại: $NEW_VERSION"
    echo "   Vui lòng kiểm tra lại."
fi

echo "================================================"
