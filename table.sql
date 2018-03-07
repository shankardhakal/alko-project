CREATE TABLE `sdhakal`.`alko_table` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `Number` VARCHAR(200) NULL,
 `Bootlesize` VARCHAR(45) NULL,
 `Price` VARCHAR(45) NULL,
 `Timestamp` DATETIME NULL,
 `Orderamount` INT NULL DEFAULT 0,
 `Name` VARCHAR(300) NULL,
 PRIMARY KEY (`id`),
 UNIQUE INDEX `id_UNIQUE` (`id` ASC));
