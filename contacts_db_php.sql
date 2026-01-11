-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3310
-- Généré le : jeu. 01 jan. 2026 à 16:54
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



--
-- Base de données : `contacts_db_php`
--

CREATE DATABASE IF NOT EXISTS contacts_db_php;
USE contacts_db_php;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_tag`
--

CREATE TABLE `contact_tag` (
  `contact_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `avatar_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Index pour la table `contact_tag`
--
ALTER TABLE `contact_tag`
  ADD PRIMARY KEY (`contact_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contact_tag`
--
ALTER TABLE `contact_tag`
  ADD CONSTRAINT `contact_tag_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

INSERT INTO users (nom, prenom, email, password, role) VALUES
('Ezzit', 'Mustapha', 'mustapha.ezzit@company.com',
 '$2y$10$WqXz2nKk8kQZ5JYJpLw9xO3C6VZqz7V8Z0y9M1QG9zXj8ZPqF9W7a', 'admin'),

('Benali', 'Sara', 'sara.benali@company.com',
 '$2y$10$k8GQJk8ZKkD4sF5Y0ZzGQeXcZK0s8AjtKoa6HgMHqYjgJv1nP9WlS', 'user'),

('El Amrani', 'Youssef', 'youssef.elamrani@company.com',
 '$2y$10$k8GQJk8ZKkD4sF5Y0ZzGQeXcZK0s8AjtKoa6HgMHqYjgJv1nP9WlS', 'user'),

('Tahiri', 'Khadija', 'khadija.tahiri@company.com',
 '$2y$10$k8GQJk8ZKkD4sF5Y0ZzGQeXcZK0s8AjtKoa6HgMHqYjgJv1nP9WlS', 'user'),

('Ouazzani', 'Hamza', 'hamza.ouazzani@company.com',
 '$2y$10$k8GQJk8ZKkD4sF5Y0ZzGQeXcZK0s8AjtKoa6HgMHqYjgJv1nP9WlS', 'user');


INSERT INTO tags (label) VALUES
('Family'),
('Work'),
('Client'),
('Supplier'),
('Emergency'),
('Partner'),
('VIP'),
('HR'),
('Support'),
('Other');


INSERT INTO contacts (
  owner_id, nom, prenom, email, phone, city, company, notes, photo_path
) VALUES
(1, 'Karimi', 'Omar', 'omar.karimi@partner.com', '0611111111', 'Casablanca', 'Karimi SARL', 'Business partner', NULL),
(1, 'Zahraoui', 'Imane', 'imane.zahraoui@client.com', '0622222222', 'Rabat', 'IZ Consulting', 'VIP client', NULL),

(2, 'Bennani', 'Hicham', 'hicham.bennani@work.com', '0633333333', 'Tanger', 'LogiTech', 'Project manager', NULL),
(2, 'Fassi', 'Salma', 'salma.fassi@hr.com', '0644444444', 'Fes', 'HR Solutions', 'HR contact', NULL),

(3, 'Amine', 'Nabil', 'nabil.amine@supplier.com', '0655555555', 'Marrakech', 'SupplyPro', 'Main supplier', NULL),
(3, 'Chakir', 'Aya', 'aya.chakir@support.com', '0666666666', 'Agadir', 'HelpDesk', 'Technical support', NULL),

(4, 'Rami', 'Anas', 'anas.rami@client.com', '0677777777', 'Kenitra', 'Rami Design', 'Design client', NULL),
(4, 'Lahlou', 'Meriem', 'meriem.lahlou@vip.com', '0688888888', 'Rabat', 'Luxury Corp', 'High priority', NULL),

(5, 'Skalli', 'Younes', 'younes.skalli@partner.com', '0699999999', 'Oujda', 'Skalli Group', 'Strategic partner', NULL);


INSERT INTO contact_tag (contact_id, tag_id) VALUES
(1, 2), -- Work
(1, 6), -- Partner

(2, 3), -- Client
(2, 7), -- VIP

(3, 2), -- Work
(3, 8), -- HR

(4, 8), -- HR

(5, 4), -- Supplier
(5, 2), -- Work

(6, 9), -- Support

(7, 3), -- Client
(7, 2), -- Work

(8, 7), -- VIP

(9, 6); -- Partner



