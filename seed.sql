USE contacts_db_php;

DELETE FROM contact_tag;
DELETE FROM contacts;
DELETE FROM tags;
DELETE FROM users;

ALTER TABLE contact_tag AUTO_INCREMENT = 1;
ALTER TABLE contacts AUTO_INCREMENT = 1;
ALTER TABLE tags AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;

-- Password for all users is: 123456 (bcrypt hashed)

INSERT INTO users (nom, prenom, email, password, role) VALUES
('Alpha', 'User1', 'user1@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'admin'),
('Beta', 'User2', 'user2@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Gamma', 'User3', 'user3@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Delta', 'User4', 'user4@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Epsilon', 'User5', 'user5@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Zeta', 'User6', 'user6@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Eta', 'User7', 'user7@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Theta', 'User8', 'user8@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Iota', 'User9', 'user9@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user'),
('Kappa', 'User10', 'user10@test.com', '$2y$10$fCc7dw3rCrsuFVKZIE8vlupMgVC1HoGc0btS/Ij21NPkHTMi7hr02', 'user');

INSERT INTO tags (label) VALUES
('Family'),
('Work'),
('Friend'),
('Client'),
('Emergency'),
('School'),
('Gym'),
('VIP'),
('Service'),
('Other');

INSERT INTO contacts (
  owner_id, nom, prenom, email, phone, city, company, notes, photo_path
) VALUES
(1, 'ContactA', 'One', 'a1@test.com', '0600000001', 'CityA', 'CompanyA', 'Note A', NULL),
(1, 'ContactB', 'Two', 'b2@test.com', '0600000002', 'CityB', 'CompanyB', 'Note B', NULL),
(2, 'ContactC', 'Three', 'c3@test.com', '0600000003', 'CityC', 'CompanyC', 'Note C', NULL),
(2, 'ContactD', 'Four', 'd4@test.com', '0600000004', 'CityD', 'CompanyD', 'Note D', NULL),
(3, 'ContactE', 'Five', 'e5@test.com', '0600000005', 'CityE', 'CompanyE', 'Note E', NULL),
(4, 'ContactF', 'Six', 'f6@test.com', '0600000006', 'CityF', 'CompanyF', 'Note F', NULL),
(5, 'ContactG', 'Seven', 'g7@test.com', '0600000007', 'CityG', 'CompanyG', 'Note G', NULL),
(6, 'ContactH', 'Eight', 'h8@test.com', '0600000008', 'CityH', 'CompanyH', 'Note H', NULL),
(7, 'ContactI', 'Nine', 'i9@test.com', '0600000009', 'CityI', 'CompanyI', 'Note I', NULL),
(8, 'ContactJ', 'Ten', 'j10@test.com', '0600000010', 'CityJ', 'CompanyJ', 'Note J', NULL);

INSERT INTO contact_tag (contact_id, tag_id) VALUES
(1, 1),
(1, 3),
(2, 2),
(2, 4),
(3, 3),
(3, 5),
(4, 4),
(5, 1),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);
