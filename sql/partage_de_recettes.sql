CREATE TABLE `COMMENT` (
                           `comment_id` int NOT NULL,
                           `user_id` int DEFAULT NULL,
                           `recipe_id` int DEFAULT NULL,
                           `comment` text,
                           `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                           `review` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `COMMENT`
--

INSERT INTO `COMMENT` (`comment_id`, `user_id`, `recipe_id`, `comment`, `created_at`, `review`) VALUES
                                                                                                    (1, 1, 19, 'Superbe !', '2025-06-11 22:07:48', 5),
                                                                                                    (2, 3, 20, 'Maquifique !', '2025-06-11 22:37:20', 2),
                                                                                                    (3, 3, 20, 'Test', '2025-06-12 15:31:16', 5);

-- --------------------------------------------------------

--
-- Structure de la table `CONTACT`
--

CREATE TABLE `CONTACT` (
                           `contact_id` int NOT NULL,
                           `email` varchar(100) NOT NULL,
                           `message` text,
                           `screenshoot_path` varchar(255) DEFAULT NULL,
                           `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                           `user_id_user` int DEFAULT NULL,
                           `user_id_admin` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `CONTACT`
--

INSERT INTO `CONTACT` (`contact_id`, `email`, `message`, `screenshoot_path`, `created_at`, `user_id_user`, `user_id_admin`) VALUES
    (1, 'mtg@test.com', 'gegrgrgrtg', NULL, '2025-06-11 13:00:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `recipe`
--

CREATE TABLE `recipe` (
                          `recipe_id` int NOT NULL,
                          `user_id` int DEFAULT NULL,
                          `title` varchar(255) NOT NULL,
                          `recipe` text NOT NULL,
                          `is_enabled` tinyint(1) DEFAULT '1',
                          `status` enum('published','draft') NOT NULL DEFAULT 'draft',
                          `season_id` int DEFAULT NULL,
                          `type` varchar(50) DEFAULT NULL,
                          `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `user_id`, `title`, `recipe`, `is_enabled`, `status`, `season_id`, `type`, `image`) VALUES
                                                                                                                           (15, 3, 'Blanquette de poulet', 'Ingrédients :\r\n800 g de morceaux de poulet\r\n2 carottes\r\nle vert d’un poireau\r\n1 branche de céleri\r\n1 bouquet garni\r\n10 grains de poivre noir\r\n1 cuillère(s) à café de graines de coriandre\r\n1 gousse d’ail\r\n1 gros oignon pelé et piqué de 3 clous de girofle\r\n1 cuillère(s) à soupe de gros sel\r\n250 g de champignons de Paris\r\n10 oignons grelots\r\n110 g de beurre\r\n20 cl de crème fraîche\r\n1 jaune d’oeuf\r\n1/2 cuillère(s) à café de muscade\r\n40 g de farine\r\nquelques brins de coriandre fraîche\r\n\r\nPréparation : \r\nPelez et coupez les carottes en tronçons. Pelez et écrasez l’ail. Lavez les légumes. Dans une cocotte, placez les carottes, le vert de poireau, le céleri, le bouquet garni, les grains de poivre et les graines de coriandre, l’ail, l’oignon, le gros sel et enfin les morceaux de poulet. Couvrez d’eau à hauteur et placez sur feu vif pour amener à ébullition, écumez au fur et à mesure. Faites cuire 45 mn à frémissements.\r\n\r\nPendant ce temps, coupez les pieds des champignons, puis brossez-les et coupez-les en quatre. Pelez les oignons grelots. Dans une sauteuse, laissez fondre 45 g de beurre et faites revenir à feu doux les champignons et les oignons pendant 5 mn, réservez dans un petit saladier.\r\n\r\nDans un bol, mélangez la crème avec le jaune d’œuf et la muscade.\r\n\r\nDans la même sauteuse, laissez fondre 65 g de beurre, puis ajoutez la farine, mélangez à l’aide d’un fouet, mouillez avec 1 l de jus de cuisson du poulet, en l’intégrant louche par louche. Pour finir, incorporez la crème.\r\n\r\nBaissez le feu et ajoutez à la sauce les morceaux de poulet, les carottes, les champignons et les oignons grelots, poursuivez la cuisson 10 mn à feu doux. Salez et poivrez au moulin si nécessaire et parsemez de coriandre.', 1, 'published', 4, 'salé', 'img_6849dcf92236f.jpg'),
                                                                                                                           (16, 3, 'Mini-duffins à la cannelle', 'Ingrédients :\r\nPour environ 24 mini-duffins\r\n90 g de beurre pommade\r\n60 g de vergeoise blonde\r\n120 g de sucre en poudre\r\n1 oeuf\r\n18 cl de lait fermenté ou de lait ribot\r\n180 g de farine\r\n1,5 cuillère(s) à café de levure chimique\r\n1 cuillère(s) à café de cannelle moulue\r\n1/4 cuillère(s) à café de noix de muscade moulue (facultatif)\r\n30 g de beurre fondu\r\n\r\nPréparation :\r\nPréchauffez le four à 180 °C.\r\n\r\nFouettez le beurre pommade, la vergeoise et 60 g de sucre en poudre dans un saladier jusqu’à ce que le mélange blanchisse. Incorporez ensuite l’œuf, toujours en fouettant. Versez le lait fermenté, puis la farine et la levure, en mélangeant rapidement entre chaque ajout.\r\n\r\nTransvasez la pâte dans des moules à mini-muffins légèrement beurrés, en les remplissant aux 4/5e. Enfournez pour 10 à 12 mn en surveillant la cuisson : les mini-duffins doivent être cuits à point et joliment dorés. Ajustez le temps de cuisson selon la taille des moules.\r\n\r\nMélangez, dans une assiette creuse, le reste du sucre en poudre, la cannelle et la noix de muscade.\r\n\r\nTrempez très rapidement le haut des mini-duffins dans le beurre fondu, puis dans le mélange sucre, cannelle, muscade, cette fois en insistant bien.\r\n\r\nLaissez refroidir les mini-duffins sur une grille côté sucré vers le haut avant de déguster.', 1, 'published', 4, 'sucré', 'img_6849de5ab5c99.jpg'),
                                                                                                                           (18, 1, 'Asperges mimosa aux agrumes', 'Ingrédients\r\n8 asperges vertes\r\n4 oeufs durs écalés râpés avec une râpe à fromage\r\n1 jaune d\'oeuf\r\n1 cuillère(s) à café de moutarde\r\nhuile de tournesol\r\npersil\r\nciboulette\r\nPour la sauce hollandaise\r\n2 jaunes d\'oeufs\r\n1 cl d\'eau\r\n150 g de beurre fondu\r\n1 citron bio\r\nPour le décor\r\ncroûtons\r\nagrumes confits\r\nPréparation\r\nFaites cuire les asperges 5 min dans de l’eau bouillante salée, puis plongez-les dans l’eau glacée. Réservez.\r\n\r\nPréparez la mayonnaise : mélangez le jaune d’œuf, la moutarde, du sel et du poivre, puis montez la mayonnaise en versant l’huile en filet. Ajoutez les herbes lavées et hachées, puis les œufs râpés.\r\n\r\nRéservez en poche à douille, pour le dressage.\r\n\r\nPréparez la sauce hollandaise : dans une casserole, sur feu doux, mélangez les œufs et l’eau à l’aide d’un fouet en formant des huit, jusqu’à ce que le sabayon commence à monter (attention, la casserole doit être chaude, mais pas trop). Quand le sabayon est bien pris, ajoutez le beurre fondu en filet, petit à petit, en veillant à ne pas incorporer la partie blanche ( juste le beurre clarifié). Versez 1 trait de jus de citron et le zeste finement haché. Salez, poivrez.\r\n\r\nSur chaque assiette, disposez une demi-asperge, puis une ligne d’œufs mimosa à l’aide de la poche à douille. Répétez l’opération.\r\n\r\nVersez le sabayon encore tiède, terminez en plaçant quelques tronçons d’agrumes confits et des croûtons.', 0, 'draft', 1, 'salé', NULL),
(19, 3, 'Cheesecake à la rhubarbe', 'Ingrédients :\r\nPour le sablé breton\r\n160 g de beurre pommade\r\n140 g de sucre semoule\r\n100 g de poudre d’amandes\r\n70 g de jaunes d’oeufs\r\n205 g 205 g de farine\r\n1 sachet de levure chimique\r\nPour l’appareil à cheesecake\r\n500 g de fromage type St Môret\r\n150 g de sucre glace\r\n8 g de farine\r\n2 oeufs\r\n1 jaune d’oeuf\r\n35 g de crème liquide\r\nPour la compotée\r\n300 g de rhubarbe\r\nun peu de sucre\r\n\r\nPréparation :\r\nPréparez le sablé : mélangez le beurre avec le sucre et la poudre d’amandes, ajoutez les jaunes d’œufs, puis la farine et la levure tamisées. Réservez au frais 1 heure.\r\n\r\nÉtalez et placez dans un cercle à tarte graissé. Enfournez pour environ 10-12 min à 180 °C (th. 6).\r\n\r\nPréparez l’appareil : mélangez le St Môret, le sucre glace et la farine dans le robot muni de l’outil feuille. Ajoutez les œufs et le jaune légèrement battus, puis la crème. Coulez l’appareil dans le cercle de sablé. Enfournez pour 45 min à 90 °C (th 3). Laissez refroidir avant de démouler.\r\n\r\nPréparez la compotée : épluchez la rhubarbe, coupez-la en petits tronçons. Laissez-les dégorger 15 min avec un peu de sucre. Faites cuire la rhubarbe une trentaine de minutes à feu doux.\r\n\r\nDressez en posant de la compotée de rhubarbe froide sur le cheesecake.', 1, 'published', 1, 'sucré', 'img_6849ddf184fdd.jpg'),
(20, 1, 'TEST Salade de concombres, haricots, feta', 'Pour la salade :\r\n4 mini-concombres\r\n1 oignon rouge\r\n2 branches de menthe\r\n4 branches d\'aneth\r\n250 g de haricots blancs en conserve, rincés et égouttés\r\nPour la crème de feta\r\n200 g de feta\r\n200 g de yaourt grec\r\nPour la vinaigrette\r\n1 cuillère(s) à café de miel\r\n3 cuillère(s) à soupe d\'huile d\'olive\r\n1 cuillère(s) à soupe de vinaigre de vin\r\n1 cuillère(s) à café de zaatar\r\n\r\nPréparation :\r\nPréparez la salade. Pelez les concombres et coupez-les en petits dés. Émincez finement l’oignon rouge. Hachez la menthe et l’aneth.\r\n\r\nMélangez l’oignon, les herbes et les haricots blancs dans le saladier avec les concombres. Mêlez bien le tout.\r\n\r\nPréparez la crème de feta. Émiettez le fromage dans un petit bol, et mélangez-le avec le yaourt grec jusqu’à obtenir une consistance crémeuse.\r\n\r\nPréparez la vinaigrette. Mélangez tous les ingrédients dans un bol à part.\r\n\r\nÉtalez la crème de feta au fond d’une assiette de service. Déposez la salade par-dessus, puis nappez avec la vinaigrette. Servez bien frais et dégustez !', 1, 'published', 1, 'sucré', 'img_6849e2ea354e6.jpg'),
                                                                                                                           (22, 3, 'trt', 'ttt', 1, 'published', 1, 'sucré', 'img_684ae749724f0.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `RECIPE_TAG`
--

CREATE TABLE `RECIPE_TAG` (
                              `recipe_id` int NOT NULL,
                              `tag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ROLE`
--

CREATE TABLE `ROLE` (
                        `role_id` int NOT NULL,
                        `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `SEASON`
--

CREATE TABLE `SEASON` (
                          `season_id` int NOT NULL,
                          `title` varchar(100) NOT NULL,
                          `is_enabled` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `SEASON`
--

INSERT INTO `SEASON` (`season_id`, `title`, `is_enabled`) VALUES
                                                              (1, 'printemps', 1),
                                                              (2, 'été', 1),
                                                              (3, 'automne', 1),
                                                              (4, 'hiver', 1);

-- --------------------------------------------------------

--
-- Structure de la table `SEASON_RECIPE`
--

CREATE TABLE `SEASON_RECIPE` (
                                 `season_id` int NOT NULL,
                                 `recipe_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `TAG`
--

CREATE TABLE `TAG` (
                       `tag_id` int NOT NULL,
                       `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `USER`
--

CREATE TABLE `USER` (
                        `user_id` int NOT NULL,
                        `contact_id` int DEFAULT NULL,
                        `full_name` varchar(100) NOT NULL,
                        `email` varchar(100) NOT NULL,
                        `password` varchar(255) NOT NULL,
                        `age` int DEFAULT NULL,
                        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                        `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `USER`
--

INSERT INTO `USER` (`user_id`, `contact_id`, `full_name`, `email`, `password`, `age`, `created_at`, `is_active`) VALUES
                                                                                                                     (1, NULL, 'Lara Croft', 'lc@gmail.com', '$2y$10$..DnvUYsU.Td8G97/8IumOkx5snQr2Vh7KnrqzDZGxGLJ/DircEXW', 33, '2025-06-10 12:04:45', 1),
                                                                                                                     (2, NULL, 'Barbie ', 'barbie@gmail.com', '$2y$10$6IsUzj7DgnexLceX1fP.ceOl0.Up0t9NYl5QeUKQWzUL9FB.TwVhe', 18, '2025-06-10 16:00:12', 1),
                                                                                                                     (3, NULL, 'Harry Potter', 'hp@gmail.com', '$2y$10$Hk0YVEzynRHMx/UQ8l1oV.my6NoOrXHlalj4I1ZEtRd7U5Lp.VTaq', 22, '2025-06-11 09:55:12', 1),
                                                                                                                     (4, NULL, 'Ratatouille', 'rat@gmail.com', '$2y$10$XutAPcAnQBqBfNhEd6.2x.TYu13LWJzfnPBUidGjdFKYymjNZvqtC', 56, '2025-06-11 15:05:36', 1),
                                                                                                                     (5, NULL, 'Roi Leon', 'rl@gmail.com', '$2y$10$5lqQTQTF.pbgbcsCQ7IzvOIctcszNnzHnSGJrVF0/cSuk.GZOEi7u', 22, '2025-06-11 15:10:34', 1),
                                                                                                                     (7, NULL, 'Nymphe', 'n@gmail.com', '$2y$10$k6zhWYxFop3qImJOtOZJ..PQiMz.g6bJW25ntnjnzn52GYEURXu8G', 18, '2025-06-12 15:28:07', 1);

-- --------------------------------------------------------

--
-- Structure de la table `USER_ROLE`
--

CREATE TABLE `USER_ROLE` (
                             `role_id` int NOT NULL,
                             `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
    ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Index pour la table `CONTACT`
--
ALTER TABLE `CONTACT`
    ADD PRIMARY KEY (`contact_id`),
  ADD KEY `fk_contact_user` (`user_id_user`),
  ADD KEY `fk_contact_admin` (`user_id_admin`);

--
-- Index pour la table `recipe`
--
ALTER TABLE `recipe`
    ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_ibfk_season` (`season_id`);

--
-- Index pour la table `RECIPE_TAG`
--
ALTER TABLE `RECIPE_TAG`
    ADD PRIMARY KEY (`recipe_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Index pour la table `ROLE`
--
ALTER TABLE `ROLE`
    ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `SEASON`
--
ALTER TABLE `SEASON`
    ADD PRIMARY KEY (`season_id`);

--
-- Index pour la table `SEASON_RECIPE`
--
ALTER TABLE `SEASON_RECIPE`
    ADD PRIMARY KEY (`season_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Index pour la table `TAG`
--
ALTER TABLE `TAG`
    ADD PRIMARY KEY (`tag_id`);

--
-- Index pour la table `USER`
--
ALTER TABLE `USER`
    ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Index pour la table `USER_ROLE`
--
ALTER TABLE `USER_ROLE`
    ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
    MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `CONTACT`
--
ALTER TABLE `CONTACT`
    MODIFY `contact_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `recipe`
--
ALTER TABLE `recipe`
    MODIFY `recipe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `ROLE`
--
ALTER TABLE `ROLE`
    MODIFY `role_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `SEASON`
--
ALTER TABLE `SEASON`
    MODIFY `season_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `TAG`
--
ALTER TABLE `TAG`
    MODIFY `tag_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `USER`
--
ALTER TABLE `USER`
    MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
    ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `RECIPE` (`recipe_id`);

--
-- Contraintes pour la table `CONTACT`
--
ALTER TABLE `CONTACT`
    ADD CONSTRAINT `fk_contact_admin` FOREIGN KEY (`user_id_admin`) REFERENCES `USER` (`user_id`),
  ADD CONSTRAINT `fk_contact_user` FOREIGN KEY (`user_id_user`) REFERENCES `USER` (`user_id`);

--
-- Contraintes pour la table `recipe`
--
ALTER TABLE `recipe`
    ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`),
  ADD CONSTRAINT `recipe_ibfk_season` FOREIGN KEY (`season_id`) REFERENCES `SEASON` (`season_id`);

--
-- Contraintes pour la table `RECIPE_TAG`
--
ALTER TABLE `RECIPE_TAG`
    ADD CONSTRAINT `recipe_tag_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `RECIPE` (`recipe_id`),
  ADD CONSTRAINT `recipe_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `TAG` (`tag_id`);

--
-- Contraintes pour la table `SEASON_RECIPE`
--
ALTER TABLE `SEASON_RECIPE`
    ADD CONSTRAINT `season_recipe_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `SEASON` (`season_id`),
  ADD CONSTRAINT `season_recipe_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `RECIPE` (`recipe_id`);

--
-- Contraintes pour la table `USER`
--
ALTER TABLE `USER`
    ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `CONTACT` (`contact_id`);

--
-- Contraintes pour la table `USER_ROLE`
--
ALTER TABLE `USER_ROLE`
    ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `ROLE` (`role_id`),
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`);
COMMIT;