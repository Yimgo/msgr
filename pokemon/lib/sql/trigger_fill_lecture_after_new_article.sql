delimiter $$
CREATE TRIGGER fill_lecture_after_new_article AFTER INSERT ON article FOR EACH ROW 
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE id_user INT;
  DECLARE ids_user CURSOR FOR SELECT abonnement_id_user FROM abonnement WHERE abonnement_id_flux = NEW.article_id_flux; 
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
   
  OPEN ids_user;

  REPEAT
    FETCH ids_user INTO id_user;
    IF NOT done THEN
      INSERT INTO tag_article(lecture_id_user, lecture_id_article, lecture_lu_nonlu, lecture_sauvegarde) VALUES(id_user, new.article_id_flux, 0, 0);
    END IF;
  UNTIL done END REPEAT;

  CLOSE ids_user;

END$$
delimiter;
