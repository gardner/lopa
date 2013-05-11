create table signatures (
	id INT NOT NULL AUTO_INCREMENT,
	full_name VARCHAR(70),
	email VARCHAR(250) NOT NULL,
	zip int(5) NOT NULL default '0',
	state CHAR(2),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY (state),
	KEY (email)
);

