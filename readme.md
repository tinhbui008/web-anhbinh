## Tổng quan

Project này đã được cấu hình để deploy lên staging và production với domain thật. Thay đổi config theo hướng dẫn bên dưới

---

## 📋 Chuẩn bị

- ✅ Đã có domain cho staging và production (vd: `yourdomain.com`)
- ✅ Server có Docker & Docker Compose
- ✅ DNS đã trỏ domain về IP server
- ✅ Mở port 80 và 443 trên firewall

---

## 📁 Cấu trúc thư mục

```text
project-root
├── database
│ └── db.sql
│
├── docker
│ ├── env
│ │ ├── .env.production
│ │ └── .env.staging
│ │
│ ├── letsencrypt
│ │ └── acme.json
│ │
│ ├── letsencrypt_prod
│ │ └── acme.json
│ │
│ ├── nginx
│ │ ├── production.conf
│ │ └── staging.conf
│ │
│ ├── docker-compose.production.yml
│ └── docker-compose.staging.yml
│
├── src # Source WordPress
│
└── readme.md
```

## Cấu hình file `.env.production`

- Thiết lập biến **MYSQL_ROOT_PASSWORD** (mặc định: `yourStrongRootPass`)
- Thiết lập **MYSQL_USER** (mặc định: `wpuser`) và **MYSQL_PASSWORD** (mặc định: `yourStrongRootPass`)
- Thiết lập **WORDPRESS_DB_USER**, **WORDPRESS_DB_PASSWORD** theo đúng giá trị của `MYSQL_USER` và `MYSQL_PASSWORD`
- Thiết lập domain cho **WP_HOME** và **WP_SITEURL**
  - Ví dụ: `https://yourdomain.com`

---

## Cấu hình file `.env.staging`

- Cấu hình tương tự như file `.env.production`.

---

## Chuẩn bị và chạy Docker Compose

### **Bước 1 — Cập nhật domain - email**

Thay thông tin email `admin@example.com` bằng email thật trong các file sau:

- `docker-compose.staging.yml`
- `docker-compose.production.yml`

Thay `staging.domain.com` và `yourdomain.com` bằng domain thật trong các file sau:

- `docker-compose.staging.yml`
- `docker-compose.production.yml`
- `nginx/staging.conf`
- `nginx/production.conf`

### **Bước 2 — Tạo chứng chỉ Let's Encrypt**

Tạo file:

- `letsencrypt/acme.json` (staging)
- `letsencrypt_prod/acme.json` (production)

Thiết lập quyền:

```bash
chmod 600 acme.json
```

### **Bước 3 — Chạy Docker Compose**

# Staging

docker-compose -f docker-compose.staging.yml up -d

# Production

docker-compose -f docker-compose.production.yml up -d

### **Bước 4 — Giải nén và import dữ liệu uploads**

Giải nén uploads.zip và copy toàn bộ nội dung vào thư mục tương ứng với volume:
wordpress_staging_uploads (cho container wordpress_staging)
wordpress_prod_uploads (cho container wordpress_prod)

### **Bước 5 — Thiết lập quyền cho file và thư mục**

- chmod 755 cho thư mục
- chmod 644 cho file
