-- init.sql : 

CREATE DATABASE IF NOT EXISTS takalo_db
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE takalo_db;

CREATE TABLE IF NOT EXISTS admin (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    login VARCHAR(190) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_admin_login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categorie (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    libele VARCHAR(190) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categorie_libele (libele)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(190) NOT NULL,
    login VARCHAR(190) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_email (email),
    UNIQUE KEY uq_user_login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;