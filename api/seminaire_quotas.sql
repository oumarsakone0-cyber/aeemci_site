CREATE TABLE IF NOT EXISTS seminaire_quotas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  annee_seminaire INT NOT NULL,
  quota_total INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_annee_seminaire (annee_seminaire)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO seminaire_quotas (annee_seminaire, quota_total)
VALUES (2026, 0)
ON DUPLICATE KEY UPDATE quota_total = quota_total;
