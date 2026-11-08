SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE IF NOT EXISTS `committees` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `short_name` VARCHAR(50) DEFAULT NULL,
  `agenda` TEXT DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `study_guide` VARCHAR(255) DEFAULT NULL,
  `member1_name` VARCHAR(150) DEFAULT NULL,
  `member1_title` VARCHAR(150) DEFAULT NULL,
  `member1_image` VARCHAR(255) DEFAULT NULL,
  `member2_name` VARCHAR(150) DEFAULT NULL,
  `member2_title` VARCHAR(150) DEFAULT NULL,
  `member2_image` VARCHAR(255) DEFAULT NULL,
  `member3_name` VARCHAR(150) DEFAULT NULL,
  `member3_title` VARCHAR(150) DEFAULT NULL,
  `member3_image` VARCHAR(255) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `committees` (
  `id`, `title`, `short_name`, `agenda`, `description`, 
  `cover_image`, `study_guide`, 
  `member1_name`, `member1_title`, `member1_image`,
  `member2_name`, `member2_title`, `member2_image`,
  `member3_name`, `member3_title`, `member3_image`,
  `status`, `sort_order`
) VALUES (
  1, 
  'North Atlantic Council (NAC)', 
  'NAC', 
  'Addressing Deterrence Capabilities in Eastern Europe Amid Evolving Global Security Architectures', 
  'The North Atlantic Council (NAC) is the principal political decision-making body of NATO. It oversees the political and military process relating to security issues affecting the whole alliance. It brings together representatives of each member country to discuss policy or operational issues requiring collective decisions, providing an indispensable forum for consultation and mutual defense coordination.',
  '', '',
  '', '', '',
  '', '', '',
  '', '', '',
  1, 0
) ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `executives` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `executives` (`id`, `name`, `title`, `image`, `status`, `sort_order`) VALUES
(1, 'Alexander Wright', 'Secretary-General', '', 1, 1),
(2, 'Sarah Jenkins', 'Director-General', '', 1, 2)
ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `price` VARCHAR(100) DEFAULT NULL,
  `apply_url` VARCHAR(500) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `applications` (`id`, `title`, `price`, `apply_url`, `description`, `status`, `sort_order`) VALUES
(1, 'Individual Delegate', '650 ₺', 'https://forms.google.com', 'Participate individually in standard committees representing an assigned member state.', 1, 1),
(2, 'Delegation Application', '600 ₺ / delegate', 'https://forms.google.com', 'Apply as a school or university delegation led by a faculty advisor or head delegate.', 1, 2),
(3, 'Executive & Academic Team', 'Free', 'https://forms.google.com', 'Join the academic chair board, crisis team, or logistics organization staff.', 1, 3)
ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `contact_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `channel_key` VARCHAR(50) NOT NULL UNIQUE,
  `channel_title` VARCHAR(100) NOT NULL,
  `channel_value` VARCHAR(255) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_settings` (`channel_key`, `channel_title`, `channel_value`, `status`) VALUES
('instagram', 'Instagram', 'https://instagram.com/skymun', 1),
('mail', 'E-Mail', 'info@skymun.org', 1),
('phone', 'Phone Support', 'Direct Contacts', 1),
('tiktok', 'TikTok', 'https://tiktok.com/@skymun', 1)
ON DUPLICATE KEY UPDATE `channel_title`=VALUES(`channel_title`);

CREATE TABLE IF NOT EXISTS `contact_phones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `phone_number` VARCHAR(50) NOT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_phones` (`id`, `name`, `title`, `phone_number`, `status`, `sort_order`) VALUES
(1, 'Secretary-General Desk', 'General Coordination', '+90 555 123 45 67', 1, 1),
(2, 'Director-General Desk', 'Delegate & School Affairs', '+90 555 987 65 43', 1, 2)
ON DUPLICATE KEY UPDATE `id`=`id`;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
