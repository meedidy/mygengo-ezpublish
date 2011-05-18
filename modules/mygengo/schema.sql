DROP TABLE IF EXISTS mygengojob;
CREATE TABLE mygengojob 
(
	jid 				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	body_src 		MEDIUMTEXT NOT NULL,
	body_tgt 		MEDIUMTEXT,
	lc_src 			TINYTEXT,
	lc_tgt 			TINYTEXT,
	unit_count	INT,
	tier				TINYTEXT,
	credits			FLOAT,
	status			TINYTEXT,
	captcha_url	TEXT,
	preview_url	TEXT,
	slug				TINYTEXT,
	ctime				INT,
	atime				INT
);
