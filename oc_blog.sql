-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : oc_blog_db
-- Généré le : dim. 21 nov. 2021 à 16:52
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
(43, 'Test commentaire 1', '2021-11-21 16:51:11', 1, 19, 30),
(44, 'Test commentaire 2', '2021-11-21 16:51:23', 1, 19, 31),
(45, 'Test commentaire 3', '2021-11-21 16:51:34', 1, 19, 32);

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
(30, 'Or a guy who burns down a bar for the insurance money!', 'Stop it, stop it. It\'s fine. I will \'destroy\' you! Check it out, y\'all. Everyone who was invited is here. I\'ll get my kit! I videotape every customer that comes in here, so that I may blackmail them later.', 'And yet you haven\'t said what I told you to say! How can any of us trust you? It\'s just like the story of the grasshopper and the octopus. All year long, the grasshopper kept burying acorns for winter, while the octopus mooched off his girlfriend and watched TV. But then the winter came, and the grasshopper died, and the octopus ate all his acorns. Also he got a race car. Is any of this getting through to you?\r\n\r\nNo, of course not. It was… uh… porno. Yeah, that\'s it. Oh, you\'re a dollar naughtier than most. Ok, we\'ll go deliver this crate like professionals, and then we\'ll go ride the bumper cars.\r\n\r\nHey, tell me something. You\'ve got all this money. How come you always dress like you\'re doing your laundry? Daddy Bender, we\'re hungry. Why am I sticky and naked? Did I miss something fun? You can crush me but you can\'t crush my spirit!\r\n\r\nYou\'re going back for the Countess, aren\'t you? No! Don\'t jump! You\'ve killed me! Oh, you\'ve killed me! Goodbye, friends. I never thought I\'d die like this. But I always really hoped. Who said that? SURE you can die! You want to die?!', '2021-11-21 16:48:34', NULL, 20),
(31, 'But, like most politicians, he promised more than he could deliver.', 'Shut up and get to the point! Who are those horrible orange men? Look, everyone wants to be like Germany, but do we really have the pure strength of \'will\'? I guess if you want children beaten, you have to do it yourself.', 'What kind of a father would I be if I said no? What\'s with you kids? Every other day it\'s food, food, food. Alright, I\'ll get you some stupid food. You won\'t have time for sleeping, soldier, not with all the bed making you\'ll be doing.\r\n\r\nUmmm…to eBay? Ow, my spirit! Hello, little man. I will destroy you! Maybe I love you so much I love you no matter who you are pretending to be. Bender, this is Fry\'s decision… and he made it wrong. So it\'s time for us to interfere in his life.\r\n\r\nNow what? Ah, the \'Breakfast Club\' soundtrack! I can\'t wait til I\'m old enough to feel ways about stuff! Come, Comrade Bender! We must take to the streets! Moving along… Or a guy who burns down a bar for the insurance money!', '2021-11-21 16:49:22', NULL, 20),
(32, 'You, minion. Lift my arm. AFTER HIM!', 'Good man. Nixon\'s pro-war and pro-family. And from now on you\'re all named Bender Jr. Robot 1-X, save my friends! And Zoidberg! Oh dear! She\'s stuck in an infinite loop, and he\'s an idiot! Well, that\'s love for you.', 'And yet you haven\'t said what I told you to say! How can any of us trust you? It\'s just like the story of the grasshopper and the octopus. All year long, the grasshopper kept burying acorns for winter, while the octopus mooched off his girlfriend and watched TV. But then the winter came, and the grasshopper died, and the octopus ate all his acorns. Also he got a race car. Is any of this getting through to you?\r\n\r\nWell I\'da done better, but it\'s plum hard pleading a case while awaiting trial for that there incompetence. Perhaps, but perhaps your civilization is merely the sewer of an even greater society above you!\r\n\r\nHello Morbo, how\'s the family? Wow! A superpowers drug you can just rub onto your skin? You\'d think it would be something you\'d have to freebase. A true inspiration for the children. You lived before you met me?!\r\n\r\nYou wouldn\'t. Ask anyway! Negative, bossy meat creature! I was having the most wonderful dream. Except you were there, and you were there, and you were there! I haven\'t felt much of anything since my guinea pig died.', '2021-11-21 16:50:07', NULL, 20);

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
(19, 'Sullivan', 'Admin', 'admin@sullivan.com', '$2y$10$gMtjD/6/sNvTaydvef6O8.iVK4jQ98idxtos/ImyUGrowQAty8eHu', 3),
(20, 'Sullivan', 'Editor', 'editor@sullivan.com', '$2y$10$UZC0lCnH7zFjUC/v0gv7JuKxMmzNZe20aA5QvMoDQPoILj5l/7GO6', 2),
(21, 'Sullivan', 'Reader', 'reader@sullivan.com', '$2y$10$eANbvWhbLQN51hH2RAYTGuSsU2oM34Zu8aXe8Xs6G5VQq7UzLs/ey', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
