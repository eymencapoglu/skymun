<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists(__DIR__ . '/.env')) {
    $envLines = @file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($envLines as $envLine) {
        $envLine = trim($envLine);
        if ($envLine === '' || str_starts_with($envLine, '#')) continue;
        if (str_contains($envLine, '=')) {
            [$envKey, $envVal] = explode('=', $envLine, 2);
            $envKey = trim($envKey);
            $envVal = trim($envVal, " \t\n\r\0\x0B\"'");
            if (getenv($envKey) === false) {
                putenv("{$envKey}={$envVal}");
                $_ENV[$envKey] = $envVal;
            }
        }
    }
}

define('SITE_NAME', getenv('SITE_NAME') ?: 'SKYMUN');
define('SITE_MOTTO', getenv('SITE_MOTTO') ?: 'Diplomacy for Tomorrow');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost');
define('CONFERENCE_DATE', getenv('CONFERENCE_DATE') ?: '2026-12-20 09:00:00');
define('CONTACT_EMAIL', getenv('CONTACT_EMAIL') ?: 'info@example.com');

define('ADMIN_USER', getenv('ADMIN_USER') ?: '...');
define('ADMIN_PASS', getenv('ADMIN_PASS') ?: '....');

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'skymun');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', 'utf8mb4');

define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', 'uploads/');

$pdo = null;
$db_error = '';
$last_sql_error = '';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    $db_error = $e->getMessage();
    try {
        $dsnFallback = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsnFallback, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `" . DB_NAME . "`");
        $db_error = '';
    } catch (PDOException $eFallback) {
        $db_error = $eFallback->getMessage();
        $pdo = null;
    }
}

function init_skymun_tables() {
    global $pdo, $last_sql_error;
    if ($pdo) {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `committees` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `short_name` VARCHAR(50) NULL,
                `agenda` TEXT NULL,
                `description` LONGTEXT NULL,
                `cover_image` VARCHAR(255) NULL,
                `study_guide` VARCHAR(255) NULL,
                `member1_name` VARCHAR(150) NULL,
                `member1_title` VARCHAR(150) NULL,
                `member1_image` VARCHAR(255) NULL,
                `member2_name` VARCHAR(150) NULL,
                `member2_title` VARCHAR(150) NULL,
                `member2_image` VARCHAR(255) NULL,
                `member3_name` VARCHAR(150) NULL,
                `member3_title` VARCHAR(150) NULL,
                `member3_image` VARCHAR(255) NULL,
                `status` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sql);

            $sqlExecutives = "CREATE TABLE IF NOT EXISTS `executives` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(150) NOT NULL,
                `title` VARCHAR(150) NOT NULL,
                `image` VARCHAR(255) NULL,
                `status` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sqlExecutives);

            $sqlApplications = "CREATE TABLE IF NOT EXISTS `applications` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(150) NOT NULL,
                `price` VARCHAR(100) NULL,
                `apply_url` VARCHAR(500) NOT NULL,
                `description` TEXT NULL,
                `status` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sqlApplications);

            $sqlContactSettings = "CREATE TABLE IF NOT EXISTS `contact_settings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `channel_key` VARCHAR(50) UNIQUE NOT NULL,
                `channel_title` VARCHAR(100) NOT NULL,
                `channel_value` VARCHAR(255) NULL,
                `status` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sqlContactSettings);

            $pdo->exec("INSERT INTO `contact_settings` (`channel_key`, `channel_title`, `channel_value`, `status`) VALUES
                ('instagram', 'Instagram', '#', 1),
                ('mail', 'E-Mail', 'info@example.com', 1),
                ('phone', 'Phone Support', 'Direct Contacts', 0),
                ('tiktok', 'TikTok', '#', 1)
                ON DUPLICATE KEY UPDATE `channel_title`=VALUES(`channel_title`);");

            $sqlContactPhones = "CREATE TABLE IF NOT EXISTS `contact_phones` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(150) NOT NULL,
                `title` VARCHAR(150) NOT NULL,
                `phone_number` VARCHAR(50) NOT NULL,
                `status` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sqlContactPhones);

            $countCom = $pdo->query("SELECT COUNT(*) FROM `committees`")->fetchColumn();
            if ($countCom == 0) {
                $stmt = $pdo->prepare("INSERT INTO `committees` (`title`, `short_name`, `agenda`, `description`, `cover_image`, `study_guide`, `status`, `sort_order`) VALUES (?, ?, ?, ?, '', '', 1, 1)");
                $stmt->execute([
                    'North Atlantic Council (NAC)',
                    'NAC',
                    'Addressing Deterrence Capabilities in Eastern Europe Amid Evolving Global Security Architectures',
                    'The North Atlantic Council (NAC) is the principal political decision-making body of NATO. It oversees the political and military process relating to security issues affecting the whole alliance.'
                ]);
            }

            $countExec = $pdo->query("SELECT COUNT(*) FROM `executives`")->fetchColumn();
            if ($countExec == 0) {
                $stmt = $pdo->prepare("INSERT INTO `executives` (`name`, `title`, `image`, `status`, `sort_order`) VALUES (?, ?, '', 1, ?)");
                $stmt->execute(['Executive Member', 'Secretary-General', 1]);
                $stmt->execute(['Executive Member', 'Director-General', 2]);
            }

            $countApp = $pdo->query("SELECT COUNT(*) FROM `applications`")->fetchColumn();
            if ($countApp == 0) {
                $stmt = $pdo->prepare("INSERT INTO `applications` (`title`, `price`, `apply_url`, `description`, `status`, `sort_order`) VALUES (?, ?, ?, ?, 1, ?)");
                $stmt->execute(['Individual Delegate', '750 ₺', '#', 'Standard individual delegate application ticket covering all conference sessions and delegate materials.', 1]);
                $stmt->execute(['Delegation / Delegation Leader', '700 ₺ / Delegate', '#', 'Bulk registration for school and university delegations with faculty advisor privileges.', 2]);
            }
        } catch (PDOException $e) {
            $last_sql_error = "Tablo oluşturma hatası: " . $e->getMessage();
            error_log($last_sql_error);
        }
    }
}

if ($pdo) {
    init_skymun_tables();
}

function get_all_committees($only_active = false) {
    global $pdo;
    if (!$pdo) {
        return [];
    }

    init_skymun_tables();

    try {
        $sql = "SELECT * FROM `committees`";
        if ($only_active) {
            $sql .= " WHERE `status` = 1";
        }
        $sql .= " ORDER BY `sort_order` ASC, `id` DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    } catch (PDOException $e) {
        error_log("SQL get_all_committees Hatası: " . $e->getMessage());
        return [];
    }
}

function get_committee_by_id($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return null;
    }

    init_skymun_tables();

    try {
        $stmt = $pdo->prepare("SELECT * FROM `committees` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (PDOException $e) {
        error_log("SQL get_committee_by_id Hatası: " . $e->getMessage());
        return null;
    }
}

function save_committee($data, $id = 0) {
    global $pdo, $db_error, $last_sql_error;
    if (!$pdo) {
        $last_sql_error = "Veritabanı bağlantısı yok. " . ($db_error ? "Hata Detayı: " . $db_error : "");
        return false;
    }

    init_skymun_tables();
    $id = (int)$id;

    try {
        if ($id > 0) {
            $sql = "UPDATE `committees` SET 
                `title` = :title,
                `short_name` = :short_name,
                `agenda` = :agenda,
                `description` = :description,
                `cover_image` = :cover_image,
                `study_guide` = :study_guide,
                `member1_name` = :member1_name,
                `member1_title` = :member1_title,
                `member1_image` = :member1_image,
                `member2_name` = :member2_name,
                `member2_title` = :member2_title,
                `member2_image` = :member2_image,
                `member3_name` = :member3_name,
                `member3_title` = :member3_title,
                `member3_image` = :member3_image,
                `status` = :status
                WHERE `id` = :id";
            $data['id'] = $id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return $id;
        } else {
            $sql = "INSERT INTO `committees` (
                `title`, `short_name`, `agenda`, `description`, `cover_image`, `study_guide`,
                `member1_name`, `member1_title`, `member1_image`,
                `member2_name`, `member2_title`, `member2_image`,
                `member3_name`, `member3_title`, `member3_image`,
                `status`, `sort_order`
            ) VALUES (
                :title, :short_name, :agenda, :description, :cover_image, :study_guide,
                :member1_name, :member1_title, :member1_image,
                :member2_name, :member2_title, :member2_image,
                :member3_name, :member3_title, :member3_image,
                :status, 0
            )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return (int)$pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        $last_sql_error = "SQL Hatası: " . $e->getMessage();
        error_log("SQL save_committee Hatası: " . $e->getMessage());
        return false;
    }
}

function delete_committee($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `committees` WHERE `id` = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("SQL delete_committee Hatası: " . $e->getMessage());
        return false;
    }
}

function toggle_committee_status($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    $current = get_committee_by_id($id);
    if (!$current) {
        return false;
    }

    $newStatus = ($current['status'] == 1) ? 0 : 1;

    try {
        $stmt = $pdo->prepare("UPDATE `committees` SET `status` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $id]);
        return $newStatus;
    } catch (PDOException $e) {
        error_log("SQL toggle_committee_status Hatası: " . $e->getMessage());
        return false;
    }
}

function get_all_executives($only_active = false) {
    global $pdo;
    if (!$pdo) {
        return [];
    }

    init_skymun_tables();

    try {
        $sql = "SELECT * FROM `executives`";
        if ($only_active) {
            $sql .= " WHERE `status` = 1";
        }
        $sql .= " ORDER BY `sort_order` ASC, `id` ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    } catch (PDOException $e) {
        error_log("SQL get_all_executives Hatası: " . $e->getMessage());
        return [];
    }
}

function get_executive_by_id($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return null;
    }

    init_skymun_tables();

    try {
        $stmt = $pdo->prepare("SELECT * FROM `executives` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (PDOException $e) {
        error_log("SQL get_executive_by_id Hatası: " . $e->getMessage());
        return null;
    }
}

function save_executive($data, $id = 0) {
    global $pdo, $db_error, $last_sql_error;
    if (!$pdo) {
        $last_sql_error = "Veritabanı bağlantısı yok. " . ($db_error ? "Hata Detayı: " . $db_error : "");
        return false;
    }

    init_skymun_tables();
    $id = (int)$id;

    try {
        if ($id > 0) {
            $sql = "UPDATE `executives` SET 
                `name` = :name,
                `title` = :title,
                `image` = :image,
                `status` = :status,
                `sort_order` = :sort_order
                WHERE `id` = :id";
            $data['id'] = $id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return $id;
        } else {
            $sql = "INSERT INTO `executives` (
                `name`, `title`, `image`, `status`, `sort_order`
            ) VALUES (
                :name, :title, :image, :status, :sort_order
            )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return (int)$pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        $last_sql_error = "SQL save_executive Hatası: " . $e->getMessage();
        error_log($last_sql_error);
        return false;
    }
}

function delete_executive($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `executives` WHERE `id` = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("SQL delete_executive Hatası: " . $e->getMessage());
        return false;
    }
}

function toggle_executive_status($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    $current = get_executive_by_id($id);
    if (!$current) {
        return false;
    }

    $newStatus = ($current['status'] == 1) ? 0 : 1;

    try {
        $stmt = $pdo->prepare("UPDATE `executives` SET `status` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $id]);
        return $newStatus;
    } catch (PDOException $e) {
        error_log("SQL toggle_executive_status Hatası: " . $e->getMessage());
        return false;
    }
}

function get_all_applications($only_active = false) {
    global $pdo;
    if (!$pdo) {
        return [];
    }

    init_skymun_tables();

    try {
        $sql = "SELECT * FROM `applications`";
        if ($only_active) {
            $sql .= " WHERE `status` = 1";
        }
        $sql .= " ORDER BY `sort_order` ASC, `id` ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    } catch (PDOException $e) {
        error_log("SQL get_all_applications Hatası: " . $e->getMessage());
        return [];
    }
}

function get_application_by_id($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return null;
    }

    init_skymun_tables();

    try {
        $stmt = $pdo->prepare("SELECT * FROM `applications` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (PDOException $e) {
        error_log("SQL get_application_by_id Hatası: " . $e->getMessage());
        return null;
    }
}

function save_application($data, $id = 0) {
    global $pdo, $db_error, $last_sql_error;
    if (!$pdo) {
        $last_sql_error = "Veritabanı bağlantısı yok. " . ($db_error ? "Hata Detayı: " . $db_error : "");
        return false;
    }

    init_skymun_tables();
    $id = (int)$id;

    try {
        if ($id > 0) {
            $sql = "UPDATE `applications` SET 
                `title` = :title,
                `price` = :price,
                `apply_url` = :apply_url,
                `description` = :description,
                `status` = :status,
                `sort_order` = :sort_order
                WHERE `id` = :id";
            $data['id'] = $id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return $id;
        } else {
            $sql = "INSERT INTO `applications` (
                `title`, `price`, `apply_url`, `description`, `status`, `sort_order`
            ) VALUES (
                :title, :price, :apply_url, :description, :status, :sort_order
            )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return (int)$pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        $last_sql_error = "SQL save_application Hatası: " . $e->getMessage();
        error_log($last_sql_error);
        return false;
    }
}

function delete_application($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `applications` WHERE `id` = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("SQL delete_application Hatası: " . $e->getMessage());
        return false;
    }
}

function toggle_application_status($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    $current = get_application_by_id($id);
    if (!$current) {
        return false;
    }

    $newStatus = ($current['status'] == 1) ? 0 : 1;

    try {
        $stmt = $pdo->prepare("UPDATE `applications` SET `status` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $id]);
        return $newStatus;
    } catch (PDOException $e) {
        error_log("SQL toggle_application_status Hatası: " . $e->getMessage());
        return false;
    }
}

function get_contact_settings() {
    global $pdo;
    if (!$pdo) {
        return [];
    }

    init_skymun_tables();

    try {
        $stmt = $pdo->query("SELECT * FROM `contact_settings`");
        $rows = $stmt->fetchAll() ?: [];
        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['channel_key']] = $r;
        }
        return $settings;
    } catch (PDOException $e) {
        error_log("SQL get_contact_settings Hatası: " . $e->getMessage());
        return [];
    }
}

function update_contact_setting($channel_key, $value, $status) {
    global $pdo, $last_sql_error;
    if (!$pdo) {
        return false;
    }

    init_skymun_tables();

    try {
        $stmt = $pdo->prepare("UPDATE `contact_settings` SET `channel_value` = ?, `status` = ? WHERE `channel_key` = ?");
        return $stmt->execute([$value, (int)$status, $channel_key]);
    } catch (PDOException $e) {
        $last_sql_error = "SQL update_contact_setting Hatası: " . $e->getMessage();
        error_log($last_sql_error);
        return false;
    }
}

function get_all_contact_phones($only_active = false) {
    global $pdo;
    if (!$pdo) {
        return [];
    }

    init_skymun_tables();

    try {
        $sql = "SELECT * FROM `contact_phones`";
        if ($only_active) {
            $sql .= " WHERE `status` = 1";
        }
        $sql .= " ORDER BY `sort_order` ASC, `id` ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    } catch (PDOException $e) {
        error_log("SQL get_all_contact_phones Hatası: " . $e->getMessage());
        return [];
    }
}

function get_contact_phone_by_id($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return null;
    }

    init_skymun_tables();

    try {
        $stmt = $pdo->prepare("SELECT * FROM `contact_phones` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (PDOException $e) {
        error_log("SQL get_contact_phone_by_id Hatası: " . $e->getMessage());
        return null;
    }
}

function save_contact_phone($data, $id = 0) {
    global $pdo, $db_error, $last_sql_error;
    if (!$pdo) {
        $last_sql_error = "Veritabanı bağlantısı yok. " . ($db_error ? "Hata Detayı: " . $db_error : "");
        return false;
    }

    init_skymun_tables();
    $id = (int)$id;

    try {
        if ($id > 0) {
            $sql = "UPDATE `contact_phones` SET 
                `name` = :name,
                `title` = :title,
                `phone_number` = :phone_number,
                `status` = :status,
                `sort_order` = :sort_order
                WHERE `id` = :id";
            $data['id'] = $id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return $id;
        } else {
            $sql = "INSERT INTO `contact_phones` (
                `name`, `title`, `phone_number`, `status`, `sort_order`
            ) VALUES (
                :name, :title, :phone_number, :status, :sort_order
            )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return (int)$pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        $last_sql_error = "SQL save_contact_phone Hatası: " . $e->getMessage();
        error_log($last_sql_error);
        return false;
    }
}

function delete_contact_phone($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `contact_phones` WHERE `id` = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("SQL delete_contact_phone Hatası: " . $e->getMessage());
        return false;
    }
}

function toggle_contact_phone_status($id) {
    global $pdo;
    $id = (int)$id;
    if ($id <= 0 || !$pdo) {
        return false;
    }

    $current = get_contact_phone_by_id($id);
    if (!$current) {
        return false;
    }

    $newStatus = ($current['status'] == 1) ? 0 : 1;

    try {
        $stmt = $pdo->prepare("UPDATE `contact_phones` SET `status` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $id]);
        return $newStatus;
    } catch (PDOException $e) {
        error_log("SQL toggle_contact_phone_status Hatası: " . $e->getMessage());
        return false;
    }
}
