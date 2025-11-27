#!/bin/bash

echo "================================================"
echo "🔍 Kiểm tra Docker & Docker Compose Version"
echo "================================================"
echo ""

# Check Docker Engine
echo "📦 Docker Engine:"
docker --version
DOCKER_VERSION=$(docker --version | grep -oP '\d+\.\d+\.\d+' | head -1)
echo "   Version: $DOCKER_VERSION"
echo ""

# Check Docker Compose
echo "🐳 Docker Compose:"
docker-compose --version
COMPOSE_VERSION=$(docker-compose --version | grep -oP '\d+\.\d+\.\d+' | head -1)
echo "   Version: $COMPOSE_VERSION"
echo ""

# Check compatibility
echo "================================================"
echo "📊 Phân tích Compatibility"
echo "================================================"
echo ""

# Parse versions
COMPOSE_MAJOR=$(echo $COMPOSE_VERSION | cut -d. -f1)
COMPOSE_MINOR=$(echo $COMPOSE_VERSION | cut -d. -f2)

if [ "$COMPOSE_MAJOR" -eq 1 ]; then
    echo "⚠️  Cảnh báo: Bạn đang dùng Docker Compose v1.x (cũ)"
    echo ""
    echo "   Lỗi 'ContainerConfig' thường xảy ra do:"
    echo "   1. Docker Compose v1.29.2 không tương thích với Docker Engine mới"
    echo "   2. Version 3.9 trong docker-compose.yml có bug với v1.29.2"
    echo ""
    echo "   ✅ Giải pháp khuyến nghị:"
    echo "   → Upgrade lên Docker Compose v2.x"
    echo ""
    echo "   📝 Các bước upgrade:"
    echo "   sudo curl -L \"https://github.com/docker/compose/releases/latest/download/docker-compose-\$(uname -s)-\$(uname -m)\" -o /usr/local/bin/docker-compose"
    echo "   sudo chmod +x /usr/local/bin/docker-compose"
    echo "   docker-compose --version"
    echo ""

elif [ "$COMPOSE_MAJOR" -eq 2 ]; then
    echo "✅ Tốt! Bạn đang dùng Docker Compose v2.x (mới)"
    echo ""
else
    echo "❓ Không xác định được version"
fi

# Check if running in Docker context
echo "================================================"
echo "🔧 Troubleshooting 'ContainerConfig' Error"
echo "================================================"
echo ""
echo "Nếu vẫn gặp lỗi 'ContainerConfig', thử:"
echo ""
echo "1. Xóa containers và volumes cũ:"
echo "   docker-compose -f docker-compose.staging.yml down -v"
echo ""
echo "2. Xóa images cũ:"
echo "   docker system prune -a"
echo ""
echo "3. Chạy lại:"
echo "   docker-compose -f docker-compose.staging.yml up -d"
echo ""
echo "4. Nếu vẫn lỗi, upgrade Docker Compose (xem hướng dẫn ở file FIX-DOCKER-COMPOSE.md)"
echo ""
