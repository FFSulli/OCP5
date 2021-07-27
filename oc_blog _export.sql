-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : oc_blog_db
-- Généré le : ven. 23 juil. 2021 à 18:57
-- Version du serveur : 10.5.9-MariaDB-1:10.5.9+maria~focal
-- Version de PHP : 7.4.20

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
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_fk` int(11) NOT NULL,
  `post_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `content`, `verified`, `created_at`, `user_fk`, `post_fk`) VALUES
(12, 'Teststtetsttst', 0, '2021-06-22 13:23:11', 3, 2),
(13, 'Verified', 1, '2021-06-22 13:23:11', 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `user_fk` int(11) NOT NULL,
  `post_status_fk` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `excerpt`, `content`, `slug`, `created_at`, `updated_at`, `user_fk`, `post_status_fk`) VALUES
(2, 'Developer lines up builders for $950 million community southwest of Dallas', 'A new residential community southwest of Dallas will include almost 2,000 homes.', 'The BridgeWater community in Midlothian is a project of Hanover Property Co. It’s located on U.S. Highway 287, about 30 minutes from downtown Dallas. The first phase in the $950 million project will include sites for 470 homes.\r\n\r\n“We’re releasing more lots in this first phase than we originally anticipated based on the high market demand,” Ben Luedtke, executive vice president of Hanover Property, said in a statement. “As the first true master-planned community of this size in Midlothian, the interest has been phenomenal.\r\n\r\n“We have secured a group of high-quality builders that will offer architecturally appealing homes on a variety of lot sizes.”\r\n\r\nHomes in the first phase will be priced from the $300,000s.\r\n\r\nBuilders signed up for the project include John Houston Homes, Perry Homes, Highland Homes, American Legend Homes and TriPointe Homes.\r\n\r\nThe first houses — ranging in size from 1,800 to 4,500 square feet — won’t be ready until early 2023.\r\n\r\nAlong with the single-family homes, BridgeWater will include a central park, walking trails, an outdoor amphitheater and a playground.\r\n\r\nPlans for the community also include 160 townhomes and commercial construction.\r\n\r\nDeveloper Hanover is also building the Somerset and M3 Ranch communities in Mansfield; Berkshire and Wellington communities in north Fort Worth; and the Mira Lagos community in Grand Prairie.', 'post-test-2', '2021-02-17 08:53:34', '2021-07-07 09:38:55', 3, 2),
(4, 'Developer claims anti-Hasidic bias in lawsuits over thwarted warehouse project', 'NEW WINDSOR — A four-year controversy over a proposed warehouse development that neighbors fiercely oppose has now spawned its fourth court case.', 'The latest lawsuit was filed last week in state Supreme Court in Goshen by the owner of the 120-acre property at Route 207 and Toleman Road and the developer who planned to build Stewart Hill Industrial Park, a project consisting of nearly 500,000 square feet of mostly warehouse space.\r\n\r\nThe plaintiffs are asking the court to overturn the town\'s recent rezoning of their property, which would restrict it to residential development. They were making their second attempt to get Planning Board approval for their project after a judge voided their 2019 approval in a lawsuit brought by the neighbors.\r\n\r\nRejection: Board denies moratorium waiver for warehouse developer\r\n\r\nOverturned: Court invalidates approval for New Windsor warehouse plans\r\n\r\nApproval: Developer gets conditional OK for warehouse project\r\n\r\nIncluded in both the new lawsuit and a similar case the developer brought against the New Windsor Town Board in federal court in February are his claim that town officials began working with the neighbors to thwart the project after learning of his ties to the Hasidic community of Kiryas Joel.\r\n\r\nThe developer is Zigmund Brach, who is currently building the 181-home Smith Farm project in Monroe and 482-home Forest Edge in Kiryas Joel. His evidence for possible anti-Hasidic motivation was an email from a resident to fellow warehouse opponents – and apparently forwarded to town officials – that urges them to Google Brach\'s name for information about his \"past real estate dealings in Kiryas Joel.\"\r\n\r\nNeighbors have fought the warehouse proposal since 2017, arguing it didn\'t belong in a largely residential area and would lower home property values and have other negative effects for more than 70 nearby homes. They won a round of the litigation in 2020 when a judge agreed the project didn\'t conform to the town\'s zoning.\r\n\r\nBrach and the property owner, Henry Van Leeuwen, have both appealed the decision and submitted a revised development plan in response to the ruling. They also sued the Town Board in December after it imposed a development moratorium and refused to grant Stewart Hill a waiver so the Planning Board could review the new plans.\r\n\r\nA judge sided with Brach and Van Leeuwen on the waiver denial in May, but the decision was moot by then. The town had finished updating its Comprehensive Plan and zoning and lifted the moratorium. Among the zoning changes was one switching the bulk of the Stewart Hill property to residential development instead of offices and light industry.\r\n\r\nVan Leeuwen is a longtime New Windsor resident and local business owner who served on the town Planning Board for decades. He stepped down in 2017, shortly before the application was made to that board for the warehouse project.\r\n\r\nThe court papers say he is 81 and had been trying for years to sell or develop his land off Toleman Road and Route 207 in order to \"settle his estate.\"\r\n\r\nAttorneys for Brach and Van Leeuwen claim that town officials \"entered into a conspiracy\" with neighbors of the development site to stop the project after Brach disclosed his interest in the property as part of the lawsuit the neighbors brought. They argue the rezoning of his property was unjustified and amounted to illegal \"spot zoning.\"\r\n\r\nMore than $600,000 had been spent on the Stewart Hill plans by the time the moratorium was imposed. Brach cleared 16 acres of the property in 2020 but was stopped from removing more trees by a court injunction because of the neighbors\' pending lawsuit.', 'post-test-4', '2021-02-17 08:53:34', '2021-07-07 09:40:58', 4, 2),
(13, 'Developer claims anti-Hasidic bias in lawsuits over thwarted warehouse project', 'NEW WINDSOR — A four-year controversy over a proposed warehouse development that neighbors fiercely oppose has now spawned its fourth court case.', 'The latest lawsuit was filed last week in state Supreme Court in Goshen by the owner of the 120-acre property at Route 207 and Toleman Road and the developer who planned to build Stewart Hill Industrial Park, a project consisting of nearly 500,000 square feet of mostly warehouse space.\r\n\r\nThe plaintiffs are asking the court to overturn the town\'s recent rezoning of their property, which would restrict it to residential development. They were making their second attempt to get Planning Board approval for their project after a judge voided their 2019 approval in a lawsuit brought by the neighbors.\r\n\r\nRejection: Board denies moratorium waiver for warehouse developer\r\n\r\nOverturned: Court invalidates approval for New Windsor warehouse plans\r\n\r\nApproval: Developer gets conditional OK for warehouse project\r\n\r\nIncluded in both the new lawsuit and a similar case the developer brought against the New Windsor Town Board in federal court in February are his claim that town officials began working with the neighbors to thwart the project after learning of his ties to the Hasidic community of Kiryas Joel.\r\n\r\nThe developer is Zigmund Brach, who is currently building the 181-home Smith Farm project in Monroe and 482-home Forest Edge in Kiryas Joel. His evidence for possible anti-Hasidic motivation was an email from a resident to fellow warehouse opponents – and apparently forwarded to town officials – that urges them to Google Brach\'s name for information about his \"past real estate dealings in Kiryas Joel.\"\r\n\r\nNeighbors have fought the warehouse proposal since 2017, arguing it didn\'t belong in a largely residential area and would lower home property values and have other negative effects for more than 70 nearby homes. They won a round of the litigation in 2020 when a judge agreed the project didn\'t conform to the town\'s zoning.\r\n\r\nBrach and the property owner, Henry Van Leeuwen, have both appealed the decision and submitted a revised development plan in response to the ruling. They also sued the Town Board in December after it imposed a development moratorium and refused to grant Stewart Hill a waiver so the Planning Board could review the new plans.\r\n\r\nA judge sided with Brach and Van Leeuwen on the waiver denial in May, but the decision was moot by then. The town had finished updating its Comprehensive Plan and zoning and lifted the moratorium. Among the zoning changes was one switching the bulk of the Stewart Hill property to residential development instead of offices and light industry.\r\n\r\nVan Leeuwen is a longtime New Windsor resident and local business owner who served on the town Planning Board for decades. He stepped down in 2017, shortly before the application was made to that board for the warehouse project.\r\n\r\nThe court papers say he is 81 and had been trying for years to sell or develop his land off Toleman Road and Route 207 in order to \"settle his estate.\"\r\n\r\nAttorneys for Brach and Van Leeuwen claim that town officials \"entered into a conspiracy\" with neighbors of the development site to stop the project after Brach disclosed his interest in the property as part of the lawsuit the neighbors brought. They argue the rezoning of his property was unjustified and amounted to illegal \"spot zoning.\"\r\n\r\nMore than $600,000 had been spent on the Stewart Hill plans by the time the moratorium was imposed. Brach cleared 16 acres of the property in 2020 but was stopped from removing more trees by a court injunction because of the neighbors\' pending lawsuit.', 'post-test-4', '2021-02-17 08:53:34', '2021-07-07 09:40:58', 4, 2),
(14, 'Former Richardson Mayor And Developer Husband On Trial For Bribery For 2nd Time', 'SHERMAN, Texas (CBSDFW.COM) – For the second time, a former Richardson Mayor and the developer she later married go on trial for bribery and other federal charges.\r\n\r\n', 'Prosecutors say the case is about corruption and an affair the two had before they got married.\r\n\r\nFormer Richardson Mayor Laura Maczka, now Laura Jordan, and developer Mark Jordan walked to federal court on Tuesday, July 6 surrounded by their family.\r\n\r\na group of people posing for the camera© Provided by CBS Dallas\r\nLaura Jordan and Mark Jordan (CBS 11)\r\n\r\nBefore the jury entered the courtroom, the couple pleaded not guilty to charges of bribery, honest services wire fraud, and tax evasion.\r\n\r\nProsecutors said the former mayor accepted cash, vacations, and home renovations from Jordan and had sex with him in exchange for her votes to change the city’s zoning on land fronting Central Expressway and to approve hundreds of apartments that Jordan wanted to build there.\r\n\r\nDuring opening statements Tuesday, prosecutors told jurors that the Jordans “corruptly convinced the city of Richardson to approve a multi-million dollar real estate deal” and that they “used their adulterous affair and subsequent marriage to cover up corruption and get away with this.”\r\n\r\nBut the Jordans’ attorneys told the jury that the former mayor voted for the project because it was in the best interest of the city, and that a majority of council members approved the project too.\r\n\r\nThe couple’s lawyers also said their affair was totally separate.\r\n\r\nA Richardson resident who testified for prosecutors said he voted for Maczka in 2013 because of her campaign pledge to vote against new apartments.\r\n\r\nThe former Mayor’s attorney called that pledge “fake news.”\r\n\r\nTwo years ago, a jury convicted the Jordans.\r\n\r\nBut the judge tossed out the conviction after it was revealed a court officer advised one of the jurors before a verdict was reached.\r\n\r\nThe government decided to prosecute the case again.\r\n\r\nThe FBI and Richardson city officials are set to testify, along with Laura Jordan’s ex-husband and Mark Jordan’s ex-wife.\r\n\r\nIf convicted, they each face years in prison.', 'post-test-72', '2021-07-07 09:43:02', '2021-07-07 09:42:11', 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `posts_tags`
--

CREATE TABLE `posts_tags` (
  `id` int(11) NOT NULL,
  `post_fk` int(11) NOT NULL,
  `tag_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `post_status`
--

CREATE TABLE `post_status` (
  `id` int(11) NOT NULL,
  `post_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `post_status`
--

INSERT INTO `post_status` (`id`, `post_status`) VALUES
(1, 'DRAFT'),
(2, 'PUBLISHED');

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
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'tag-1'),
(2, 'tag-2'),
(3, 'tag-3'),
(4, 'tag-4'),
(5, 'tag-5'),
(6, 'tag-6'),
(7, 'tag-7'),
(8, 'tag-8');

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
(5, 'Sullivan', 'Berger', 'sullivan.berger@gmail.com', '$2y$12$0lJaWwoFf2n3NtGLLz4pdupBp3A6aM8ctrbtJUx34HAKR6AQ3nTT2', 3);

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
  ADD KEY `user_fk` (`user_fk`),
  ADD KEY `post_status_fk` (`post_status_fk`);

--
-- Index pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_fk` (`post_fk`),
  ADD KEY `tag_fk` (`tag_fk`);

--
-- Index pour la table `post_status`
--
ALTER TABLE `post_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_status` (`post_status`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role` (`role`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`post_status_fk`) REFERENCES `post_status` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD CONSTRAINT `posts_tags_ibfk_1` FOREIGN KEY (`post_fk`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_tags_ibfk_2` FOREIGN KEY (`tag_fk`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_fk`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
