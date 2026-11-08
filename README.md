# SKYMUN — Model United Nations Platform & CMS

[English](#english) | [Türkçe](#türkçe)

---

<a name="english"></a>
## 🌐 English

> ### ⚠️ Important Notice & Disclaimer
> **This repository is published strictly as a showcase, portfolio demonstration, and educational reference.**  
> The codebase was developed as a preliminary preparatory and conceptual platform for our **SkyMUN** event. Therefore, **we kindly ask you not to copy, re-host, or direct deploy this project or its identity directly as-is**. You are welcome to inspect, study, and learn from its architectural patterns and design implementation.

---

### 🚀 Overview & Features

A modern, aerospace-inspired, fully dynamic web platform and Content Management System (CMS) tailored for Model United Nations (MUN) conferences.

#### Frontend Experience
- **Aerospace Telemetry Clock (T− Mission Clock):** Live real-time mission countdown clock, flight HUD waypoints (Munich &bull; MUC corridor), and live distance telemetry calculation.
- **Dynamic Trajectory Timeline:** An airplane beacon smoothly traversing along a jetstream flight track toward the conference date.
- **Committees & Study Guides:** Dynamic committee overview and detail pages with academic topics, descriptions, and Study Guide PDF download access.
- **Executive Board:** Sleek editorial cards showcasing the executive secretariat with modal portrait view.
- **Applications Hub:** Registration tiers for individual delegates, school delegations, and staff.
- **Minimalist FAQ Accordion:** Seamless aerospace degree-indexed (`01°`) question-and-answer telemetry cards.
- **Contact Channels:** Informational contact channels and inquiry points, completely privacy-conscious with zero personal tracking.

#### Administration Console (CMS)
- **Committees Management:** Add, edit, sort committees; upload cover banners and Study Guide PDFs.
- **Executive Board Management:** Add and update board members and photo assets.
- **Application Packages:** Configure pricing, descriptions, and registration forms.
- **Contact & Communication Channels:** Enable/disable contact items and support channels.

---

### 🛠️ Quick Start & Installation

The project is built with a **Zero-Config Database Engine**. On first launch in any local PHP & MySQL environment (XAMPP, Laragon, WAMP, Docker, etc.), it connects to MySQL, automatically creates the database if it doesn't exist, establishes all required tables, and seeds initial starter data without requiring manual SQL imports.

#### 1. Clone the Repository
```bash
git clone https://github.com/eymencapoglu/skymun.git
cd skymun
```

#### 2. Deploy to Your Local Web Server
Place the folder in your web server's root directory (e.g., `C:/xampp/htdocs/skymun` or `C:/laragon/www/skymun`), or launch PHP's built-in development server:
```bash
php -S localhost:8000
```

#### 3. Automatic Database Setup
Open the site in your browser (`http://localhost/skymun` or `http://localhost:8000`).  
`config.php` connects using standard local defaults (`localhost`, user: `root`, empty password) and automatically:
1. Executes `CREATE DATABASE IF NOT EXISTS skymun`.
2. Generates tables (`committees`, `executives`, `applications`, `contact_settings`, `contact_phones`).
3. Seeds initial demonstration records.

*(Optional)* You can also manually import `schema.sql` via phpMyAdmin or the MySQL CLI:
```bash
mysql -u root -p < schema.sql
```

---

### ⚙️ Configuration (`config.php`)

All settings can be customized in `config.php` or overridden via environment variables:

| Constant / Variable | Default Value | Description |
|---|---|---|
| `DB_HOST` | `localhost` | Database host address |
| `DB_PORT` | `3306` | Database port |
| `DB_NAME` | `skymun` | Database name |
| `DB_USER` | `root` | Database username |
| `DB_PASS` | `''` (empty) | Database password |
| `ADMIN_USER` | `admin` | Admin panel login username |
| `ADMIN_PASS` | `admin123` | Admin panel login password |
| `SITE_NAME` | `SKYMUN` | Conference title |
| `CONFERENCE_DATE` | `2026-12-20 09:00:00` | Target countdown timestamp |

---

### 🔐 Admin Panel Access

- **URL:** `http://localhost/skymun/admin/` (or `http://localhost:8000/admin/`)
- **Username:** `admin`
- **Password:** `admin123`

---

<br>

---

<a name="türkçe"></a>
## 🇹🇷 Türkçe

> ### ⚠️ Önemli Uyarı & Yasal Bildirim
> **Bu depo, yalnızca portfolyo, örnek çalışma (showcase) ve eğitim amaçlı olarak kamuya açılmıştır.**  
> Buradaki kodlar, **SkyMUN** isimli etkinliğimiz için ön hazırlık ve konsept çalışması olarak geliştirildiğinden dolayı, **bu projeyi, görsel kimliğini veya içeriğini başka etkinlikler için birebir kopyalamamanızı, klonlayarak doğrudan yayınlamamanızı rica ederiz.** Kod yapısını mimari inceleme ve geliştirme pratikleri için referans alabilirsiniz.

---

### 🚀 Genel Bakış & Özellikler

Model United Nations (MUN) konferansları için özel olarak tasarlanmış, havacılık ve uzay (aerospace) konseptli, tam dinamik web platformu ve Yönetim Paneli (CMS).

#### Kullanıcı Arayüzü
- **Havacılık Telemetri Sayacı (T− Mission Clock):** Canlı geri sayım, Münih koridoru HUD hedef koordinatları ve kalan uçuş mesafesi hesaplayıcı.
- **Dinamik Uçuş Yörünge Hattı:** Konferans tarihine yaklaştıkça hat üzerinde akıcı ilerleyen uçak ikonu ve jetstream animasyonu.
- **Komiteler & Çalışma Rehberleri (Study Guides):** Detaylı komite sayfaları, akademik gündem maddeleri ve Study Guide PDF indirme alanı.
- **Yönetim Kurulu:** Editoryal portre kartları ve modal profil inceleme.
- **Başvuru Portalı:** Bireysel delege, delege lideri ve basın ekipleri için başvuru kartları.
- **Akordeon S.S.S. (FAQ):** 01° telemetri formatında akıcı açılır kapanır soru-cevap bölümü.
- **İletişim Alanı:** Kişisel verilerden tamamen arındırılmış, güvenli kurumsal bilgi alanı.

#### Yönetim Paneli (CMS)
- **Komite Yönetimi:** Yeni komite ekleme, düzenleme, kapak resmi ve Study Guide PDF yükleme.
- **Yönetici/Ekip Yönetimi:** Yönetim kurulu üyelerini düzenleme ve fotoğraf yükleme.
- **Başvuru Seçenekleri:** Başvuru paketleri ve kayıt linklerini dinamik yönetme.
- **İletişim Kanalları:** İletişim kanallarını açma/kapatma ve güncelleme.

---

### 🛠️ Hızlı Kurulum

Proje **Zero-Config (Sıfır Yapılandırma)** veritabanı motoruna sahiptir. Yerel PHP & MySQL ortamında (XAMPP, Laragon, WAMP, Docker vb.) ek bir SQL import işlemine gerek kalmadan ilk çalıştırmada veritabanını, tabloları ve tohum verileri otomatik kurar.

#### 1. Depoyu Klonlayın
```bash
git clone https://github.com/eymencapoglu/skymun.git
cd skymun
```

#### 2. Web Sunucunuza Yerleştirin
Dosyaları sunucunuzun kök dizinine (örn. `C:/xampp/htdocs/skymun`) taşıyın veya PHP dahili sunucusu ile başlatın:
```bash
php -S localhost:8000
```

#### 3. Otomatik Veritabanı Kurulumu
Tarayıcınızda siteyi açtığınızda (`http://localhost/skymun` veya `http://localhost:8000`), `config.php` yerel MySQL'e bağlanarak:
1. `skymun` veritabanını otomatik oluşturur.
2. Tabloları (`committees`, `executives`, `applications`, `contact_settings`, `contact_phones`) kurar.
3. Örnek başlangıç kayıtlarını yükler.

Dilerseniz `schema.sql` dosyasını manuel olarak da içe aktarabilirsiniz:
```bash
mysql -u root -p < schema.sql
```

---

### 🔐 Yönetim Paneli Girişi

- **URL:** `http://localhost/skymun/admin/` (veya `http://localhost:8000/admin/`)
- **Kullanıcı Adı:** `admin`
- **Şifre:** `admin123`

---

## 📂 Dizin Yapısı / Directory Structure

```
skymun/
├── admin/                    # Yönetim Paneli / Administration CMS
│   ├── index.php             # Admin login
│   ├── nav.php               # Admin navigation
│   ├── committees.php        # Committee list
│   ├── committee-edit.php    # Committee editor
│   ├── executives.php        # Executive team list
│   ├── executive-edit.php    # Executive editor
│   ├── applications.php      # Application tiers
│   ├── application-edit.php  # Application editor
│   ├── contact.php           # Channels settings
│   └── logout.php            # Logout handler
├── uploads/                  # Uploads folder (Protected)
│   ├── .gitkeep              # Git tracking placeholder
│   └── index.php             # Directory traversal shield
├── apply.php                 # Registration / Application page
├── committee.php             # Single committee view
├── committees.php            # Committees list
├── config.php                # Core configuration, PDO connection & database functions
├── contact.php               # Contact channels page
├── executive.php             # Executive board showcase
├── footer.php                # Site footer component
├── header.php                # Site header & background scripts
├── index.php                 # Landing page (HUD telemetry clock & FAQ)
├── navbar.php                # Navigation bar
├── schema.sql                # Manual database schema
├── logo-transparent.png      # Official SKYMUN transparent emblem
├── .gitignore                # Git exclusions
├── LICENSE                   # MIT License
└── README.md                 # Project documentation
```

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
