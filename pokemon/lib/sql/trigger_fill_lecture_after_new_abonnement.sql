delimiter $$
CREATE TRIGGER fill_lecture_after_new_abonnement AFTER INSERT ON abonnement FOR EACH ROW 
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE id_article INT;
  DECLARE ids_article CURSOR FOR SELECT article_id FROM article WHERE article_id_flux = NEW.abonnement_id_flux ORDER BY article_date DESC LIMIT 10; 
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
   
  OPEN ids_article;

  REPEAT
    FETCH ids_article INTO id_article;
    IF NOT done THEN
      INSERT INTO tag_article(lecture_id_user, lecture_id_article, lecture_lu_nonlu, lecture_sauvegarde) VALUES(NEW.abonnement_id_user, id_article, 0, 0);
    END IF;
  UNTIL done END REPEAT;

  CLOSE ids_article;

END$$
delimiter;
