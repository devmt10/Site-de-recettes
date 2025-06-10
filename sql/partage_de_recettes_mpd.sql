-- Création de la base de données
CREATE DATABASE IF NOT EXISTS partage_de_recettes;
USE partage_de_recettes;

-- Table ROLE
CREATE TABLE ROLE (
                      role_id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(50) NOT NULL
);

-- Table TAG
CREATE TABLE TAG (
                     tag_id INT AUTO_INCREMENT PRIMARY KEY,
                     name VARCHAR(50) NOT NULL
);

-- Table SEASON
CREATE TABLE SEASON (
                        season_id INT AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(100) NOT NULL,
                        is_enabled BOOLEAN DEFAULT TRUE
);

-- Table CONTACT (créée avant USER pour éviter les erreurs de référence)
CREATE TABLE CONTACT (
                         contact_id INT AUTO_INCREMENT PRIMARY KEY,
                         email VARCHAR(100) NOT NULL,
                         message TEXT,
                         screenshoot_path VARCHAR(255),
                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                         user_id_user INT,
                         user_id_admin INT
);

-- Table USER
CREATE TABLE `USER` (
                        user_id INT AUTO_INCREMENT PRIMARY KEY,
                        contact_id INT,
                        full_name VARCHAR(100) NOT NULL,
                        email VARCHAR(100) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        age INT,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        is_active BOOLEAN DEFAULT TRUE,
                        FOREIGN KEY (contact_id) REFERENCES CONTACT(contact_id)
);

-- Ajout des clés étrangères de CONTACT vers USER (après création des deux tables)
ALTER TABLE CONTACT
    ADD CONSTRAINT fk_contact_user FOREIGN KEY (user_id_user) REFERENCES `USER`(user_id),
    ADD CONSTRAINT fk_contact_admin FOREIGN KEY (user_id_admin) REFERENCES `USER`(user_id);

-- Table USER_ROLE (relation N-N entre USER et ROLE)
CREATE TABLE USER_ROLE (
                           role_id INT,
                           user_id INT,
                           PRIMARY KEY (role_id, user_id),
                           FOREIGN KEY (role_id) REFERENCES ROLE(role_id),
                           FOREIGN KEY (user_id) REFERENCES `USER`(user_id)
);

-- Table RECIPE
CREATE TABLE RECIPE (
                        recipe_id INT AUTO_INCREMENT PRIMARY KEY,
                        user_id INT,
                        title VARCHAR(255) NOT NULL,
                        recipe TEXT NOT NULL,
                        is_enabled BOOLEAN DEFAULT TRUE,
                        status VARCHAR(50),
                        FOREIGN KEY (user_id) REFERENCES `USER`(user_id)
);

-- Table COMMENT
CREATE TABLE `COMMENT` (
                           comment_id INT AUTO_INCREMENT PRIMARY KEY,
                           user_id INT,
                           recipe_id INT,
                           comment TEXT,
                           created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                           review INT,
                           FOREIGN KEY (user_id) REFERENCES `USER`(user_id),
                           FOREIGN KEY (recipe_id) REFERENCES RECIPE(recipe_id)
);

-- Table RECIPE_TAG (relation N-N entre RECIPE et TAG)
CREATE TABLE RECIPE_TAG (
                            recipe_id INT,
                            tag_id INT,
                            PRIMARY KEY (recipe_id, tag_id),
                            FOREIGN KEY (recipe_id) REFERENCES RECIPE(recipe_id),
                            FOREIGN KEY (tag_id) REFERENCES TAG(tag_id)
);

-- Table SEASON_RECIPE (relation N-N entre SEASON et RECIPE)
CREATE TABLE SEASON_RECIPE (
                               season_id INT,
                               recipe_id INT,
                               PRIMARY KEY (season_id, recipe_id),
                               FOREIGN KEY (season_id) REFERENCES SEASON(season_id),
                               FOREIGN KEY (recipe_id) REFERENCES RECIPE(recipe_id)
);