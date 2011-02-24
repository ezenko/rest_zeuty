ALTER TABLE `re_user_rent_pays` ADD `payment_not_season` INT UNSIGNED NULL;
CREATE TABLE `rest`.`re_user_rent_pays_by_month` (
`id_ad` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`january` INT NOT NULL ,
`february` INT NOT NULL ,
`march` INT NOT NULL ,
`april` INT NOT NULL ,
`may` INT NOT NULL ,
`june` INT NOT NULL ,
`july` INT NOT NULL ,
`august` INT NOT NULL ,
`september` INT NOT NULL ,
`october` INT NOT NULL ,
`november` INT NOT NULL ,
`december` INT NOT NULL
) ENGINE = MYISAM ;