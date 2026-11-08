# SKYMUN — Model United Nations Web Platform & CMS

Modern, havacılık ve uzay (aerospace) temalı, tam dinamik Model United Nations (MUN) konferans web sitesi ve yönetim paneli (CMS).

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

---

## 🚀 Özellikler

### Kullanıcı Arayüzü (Frontend)
- **Havacılık Telemetri Sayacı (T− Mission Clock):** Canlı konferans geri sayımı, HUD hedef koordinatları (Münih/İstanbul koridoru) ve canlı kalan mesafe hesabı.
- **Uçuş Yörünge Çizgisi (Flight Trajectory Timeline):** Konferans gününe yaklaştıkça hat üzerinde yumuşak şekilde ilerleyen dinamik uçak ikonu ve jetstream animasyonu.
- **Komiteler & Çalışma Rehberleri (Committees & Study Guides):** Detaylı komite sayfaları, başkanlar ve çalışma rehberi (Study Guide PDF) indirme alanı.
- **Yönetim Kurulu (Executive Board):** Şık editoryal kartlar, modal profil büyütme ve unvan gösterimi.
- **Başvuru Portalı (Applications):** Delege, delege lideri ve basın ekipleri için fiyatlandırma ve başvuru linkleri.
- **İletişim & Canlı Telefon Destek Modalı:** Instagram, TikTok, E-posta ve dinamik yetkili telefon listesi (tek tıkla arama/WhatsApp yönlendirmesi).
- **Akordeon S.S.S. (FAQ):** 01° derece telemetri formatında akıcı açılır kapanır soru-cevap bölümü.

### Yönetim Paneli (Admin CMS)
- **Komite Yönetimi:** Yeni komite ekleme, düzenleme, kapak resmi ve Study Guide PDF yükleme.
- **Yönetici/Ekip Yönetimi:** Yönetim kurulu üyelerini düzenleme, fotoğraf yükleme ve sıralama.
- **Başvuru Seçenekleri:** Başvuru paketleri ve kayıt linklerini dinamik yönetme.
- **İletişim & Telefon Rehberi:** Sosyal medya bağlantılarını açıp kapatma, yetkili telefon numaralarını ekleme/güncelleme.

---

## 🛠️ Kurulum (Quick Start)

Proje **Zero-Config (Sıfır Yapılandırma)** ile çalışacak şekilde tasarlanmıştır. Yerel bir PHP ve MySQL ortamında (XAMPP, Laragon, WAMP, Docker vb.) ek bir SQL import işlemine gerek kalmadan ilk çalıştırmada veritabanını ve varsayılan tabloları otomatik oluşturur.

### 1. Depoyu Klonlayın
```bash
git clone https://github.com/KULLANICI_ADI/skymun.git
cd skymun
```

### 2. Web Sunucunuza Yerleştirin
Dosyaları yerel web sunucunuzun kök dizinine (örn. XAMPP için `C:/xampp/htdocs/skymun` veya Laragon için `C:/laragon/www/skymun`) taşıyın.

Veya doğrudan PHP dahili sunucusu ile başlatın:
```bash
php -S localhost:8000
```

### 3. Veritabanı Bağlantısı
`config.php` dosyası varsayılan olarak yerel `root` (şifresiz) kullanıcısı ile `skymun` veritabanına bağlanır.

Eğer veritabanı henüz oluşturulmamışsa, sistem MySQL'e bağlanarak:
1. `skymun` veritabanını otomatik oluşturur (`CREATE DATABASE IF NOT EXISTS`).
2. Gerekli tüm tabloları (`committees`, `executives`, `applications`, `contact_settings`, `contact_phones`) otomatik kurar.
3. Örnek başlangıç verilerini otomatik yükler.

Dilerseniz `schema.sql` dosyasını phpMyAdmin veya MySQL CLI üzerinden manuel olarak da içe aktarabilirsiniz:
```bash
mysql -u root -p < schema.sql
```

---

## ⚙️ Yapılandırma (`config.php`)

Gerekli tüm ayarları `config.php` içerisinden ya da ortam değişkenleri (Environment Variables) ile düzenleyebilirsiniz:

| Sabit / Değişken | Varsayılan Değer | Açıklama |
|---|---|---|
| `DB_HOST` | `localhost` | MySQL sunucu adresi |
| `DB_PORT` | `3306` | MySQL port numarası |
| `DB_NAME` | `skymun` | Veritabanı adı |
| `DB_USER` | `root` | Veritabanı kullanıcı adı |
| `DB_PASS` | `''` (boş) | Veritabanı şifresi |
| `ADMIN_USER` | `admin` | Admin panel kullanıcı adı |
| `ADMIN_PASS` | `admin123` | Admin panel şifresi |
| `SITE_NAME` | `SKYMUN` | Konferans/Site adı |
| `CONFERENCE_DATE` | `2026-12-20 09:00:00` | Geri sayım hedef tarihi |

---

## 🔐 Yönetim Paneli Girişi

- **URL:** `http://localhost/skymun/admin/` (veya `http://localhost:8000/admin/`)
- **Kullanıcı Adı:** `admin`
- **Şifre:** `admin123`

*(Canlı yayına almadan önce `config.php` üzerinden şifrenizi değiştirmeyi unutmayınız.)*

---

## 📂 Dizin Yapısı

```
skymun/
├── admin/                    # Yönetim Paneli sayfaları
│   ├── index.php             # Admin giriş sayfası
│   ├── nav.php               # Admin üst navigasyon menüsü
│   ├── committees.php        # Komite listesi & sıralama
│   ├── committee-edit.php    # Komite ekleme/düzenleme formu
│   ├── executives.php        # Ekip üyeleri listesi
│   ├── executive-edit.php    # Ekip üyesi düzenleme formu
│   ├── applications.php      # Başvuru paketleri yönetimi
│   ├── application-edit.php  # Başvuru paketi düzenleme
│   ├── contact.php           # Sosyal medya ve telefon kanalları yönetimi
│   └── logout.php            # Çıkış işlemi
├── uploads/                  # Kullanıcı yüklemeleri (Kapak resimleri, PDF'ler)
│   ├── .gitkeep              # Dizin yapısını korumak için
│   └── index.php             # Dizin listelemeyi engelleyen güvenlik dosyası
├── apply.php                 # Başvuru sayfası
├── committee.php             # Tekil komite detay sayfası
├── committees.php            # Komiteler vitrini
├── config.php                # Çekirdek konfigürasyon, PDO ve DB fonksiyonları
├── contact.php               # İletişim sayfası
├── executive.php             # Yönetim kurulu sayfası
├── footer.php                # Site altbilgi bileşeni
├── header.php                # Site üstbilgi bileşeni
├── index.php                 # Ana sayfa (Telemetri sayacı, uçuş hattı, FAQ)
├── navbar.php                # Ana gezinme çubuğu
├── schema.sql                # Manuel veritabanı şeması ve tohum verileri
├── logo-transparent.png      # Resmi şeffaf SKYMUN logosu
├── .gitignore                # Git yok sayma kuralları
└── README.md                 # Proje dokümantasyonu
```

---

## 🛡️ Güvenlik

- Dosya yükleme dizini (`uploads/`) doğrudan dizin listelemesine karşı korunmuştur (`index.php`).
- SQL sorguları PDO Prepared Statements ile SQL Injection ataklarına karşı korunmuştur.
- XSS açıklarına karşı tüm dinamik çıktılar `htmlspecialchars()` filtresinden geçirilmektedir.
- Hassas şifreler ve ortam değişkenleri `.gitignore` koruması altındadır.

---

## 📄 Lisans

Bu proje [MIT](LICENSE) lisansı altında sunulmaktadır.
