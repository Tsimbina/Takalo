INSERT INTO admin (login, passwd)
VALUES ('admin@takalo.local', 'admin123')
ON DUPLICATE KEY UPDATE passwd = VALUES(passwd);