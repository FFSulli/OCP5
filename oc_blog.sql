-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : oc_blog_db
-- Généré le : mar. 19 oct. 2021 à 12:52
-- Version du serveur : 10.5.9-MariaDB-1:10.5.9+maria~focal
-- Version de PHP : 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `oc_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `user_fk` int(11) NOT NULL,
  `post_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `content`, `created_at`, `verified`, `user_fk`, `post_fk`) VALUES
(21, 'fgdfgdfgfdgfdg', '2021-10-13 15:57:08', 0, 8, 15);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `user_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `excerpt`, `content`, `created_at`, `updated_at`, `user_fk`) VALUES
(15, 'nvbnvbn', 'bvnvbnbvn', 'bvnvbnvbnvbn', '2021-10-12 17:29:17', NULL, 8),
(16, 'gfhfghgf', 'gfhfghfg', 'fghfghfgh', '2021-10-12 17:37:19', NULL, 8),
(17, 'dsfsdfggjhhdfg', 'hgjghjhgfdhfghdfdgfdgdfgfdgfdgfdg', 'fgjdhdfgjhgfhfgdhgfdh', '2021-10-12 18:01:03', '2021-10-12 18:01:46', 8),
(18, 'Test article', 'Blablabla', 'dsfsdfdsfsdf', '2021-10-12 18:02:32', NULL, 8);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(3, 'ADMIN'),
(2, 'EDITOR'),
(1, 'READER');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_fk` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role_fk`) VALUES
(1, 'John', 'Dude', 'sulli@sullo.com', '$2y$12$0lJaWwoFf2n3NtGLLz4pdupBp3A6aM8ctrbtJUx34HAKR6AQ3nTT2', 1),
(2, 'Pete', 'John', 'pete.john@gmail.com', '$2y$12$0lJaWwoFf2n3NtGLLz4pdupBp3A6aM8ctrbtJUx34HAKR6AQ3nTT2', 1),
(3, 'Jane', 'Doe', 'jane.doe@gmail.com', '$2y$12$0lJaWwoFf2n3NtGLLz4pdupBp3A6aM8ctrbtJUx34HAKR6AQ3nTT2', 2),
(4, 'John', 'Dude', 'john.dude@gmail.com', '$2y$12$0lJaWwoFf2n3NtGLLz4pdupBp3A6aM8ctrbtJUx34HAKR6AQ3nTT2', 2),
(5, 'Sullivan', 'Berger', 'sullivan.berger@gmail.com', '$2y$12$0lJaWwoFf2n3NtGLLz4pdupBp3A6aM8ctrbtJUx34HAKR6AQ3nTT2', 3),
(8, 'Sullivan', 'Berger', 'toto@toto.com', '$2y$10$GKlKALUkpNzHWet8HelsVusnDOgCcCqP3v76EvZAJDiT8/hRYhkKu', 3),
(9, 'Sullivan', 'Berger', 'sullivan@sullivan.com', '$2y$10$//zo9dvWXDXcCa.wP395KOrDzbPwFJ0trUkUSYKEGt9RXCnOMTV4u', 1),
(10, 'Sullivan', 'Berger', 'tutu@tutu.com', '$2y$10$GE07k9DxfaIZ3Z11Jl4K4e2UwUDvI6DGW5fzOJB01S0sBdFFVVdyO', 1),
(11, 'Sullivan', 'Berger', 'sullivan.berger@tutu.com', '$2y$10$wHQwVVtLhwJNYeOsJs2tYeym.pBeHS6fliVaTPGTA5uC4NlHsnDDW', 1),
(12, 'Sullivan', 'Berger', 'sullivan.berger@popo.com', '$2y$10$TPhbxezTBd8hIKNHX22MPuUbCyecOcJ.XoEeblliSZZvc9X1rNKY.', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk` (`user_fk`),
  ADD KEY `post_fk` (`post_fk`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk` (`user_fk`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role` (`role`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_fk` (`role_fk`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_fk`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_fk`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
