CREATE TABLE IF NOT EXISTS Quotes
(
    `id`     INT(11)      NOT NULL AUTO_INCREMENT,
    `quote`  text NOT NULL,
    `author` VARCHAR(255) NOT NULL,
    `lastTimeRead` INT(11) NOT NULL DEFAULT 0,
    `hitCounter` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0;

