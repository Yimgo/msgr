CREATE TABLE user (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    email VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE dossier (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL,
    user_id INT(10) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE flux (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(200) NOT NULL UNIQUE,
    nom VARCHAR(50) NOT NULL,
    description TEXT
);

CREATE TABLE abonnement (
    user_id INT(10),
    dossier_id INT(10),
    flux_id INT(10),
    PRIMARY KEY (dossier_id, flux_id, user_id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (dossier_id) REFERENCES dossier(id),
    FOREIGN KEY (flux_id) REFERENCES flux(id)
);

CREATE TABLE tag (
    id INT(10) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(10) NOT NULL,
    nom VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE article (
    id INT(10) PRIMARY KEY AUTO_INCREMENT,
    flux_id INT(10),
    titre VARCHAR(100) NOT NULL,
    url VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    contenu TEXT,
    date DATETIME,
    FOREIGN KEY (flux_id) REFERENCES flux(id)
);

CREATE TABLE map_tag_article (
    article_id INT(10),
    tag_id INT(10),
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES article(id),
    FOREIGN KEY (tag_id) REFERENCES tag(id)
);

CREATE TABLE lecture (
    user_id INT(10),
    article_id INT(10),
    lu INT(1) NOT NULL,
    favori INT(1) NOT NULL,
    PRIMARY KEY (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (article_id) REFERENCES article(id)
);

CREATE TABLE commentaire (
    commentaire TEXT NOT NULL,
    date DATETIME NOT NULL,
    user_id INT(10) NOT NULL,
    article_id INT(10) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (article_id) REFERENCES article(id)
);

DELIMITER //
CREATE TRIGGER fill_lecture_after_new_abonnement AFTER INSERT ON abonnement FOR EACH ROW
BEGIN
DECLARE done INT DEFAULT 0;
DECLARE id_article INT;
DECLARE article_ids CURSOR FOR SELECT id FROM article WHERE flux_id = NEW.flux_id ORDER BY date DESC LIMIT 10;
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

OPEN article_ids;

REPEAT
FETCH article_ids INTO id_article;
IF NOT done THEN
INSERT INTO lecture(user_id, article_id, lu, favori) VALUES(NEW.user_id, id_article, 0, 0);
END IF;
UNTIL done END REPEAT;

CLOSE article_ids;

END
//
DELIMITER;

DELIMITER //
CREATE TRIGGER fill_lecture_after_new_article AFTER INSERT ON article FOR EACH ROW
BEGIN
DECLARE done INT DEFAULT 0;
DECLARE id_user INT;
DECLARE user_ids CURSOR FOR SELECT user_id FROM abonnement WHERE flux_id = NEW.flux_id;
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

OPEN user_ids;

REPEAT
FETCH user_ids INTO id_user;
IF NOT done THEN
INSERT INTO lecture(user_id, article_id, lu, favori) VALUES(id_user, NEW.id, 0, 0);
END IF;
UNTIL done END REPEAT;

CLOSE user_ids;

END
//
DELIMITER;
