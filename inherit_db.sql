CREATE TABLE estates(
id INT AUTO_INCREMENT,
name VARCHAR(255),
subject TEXT,
summary TEXT,
address VARCHAR(255),
access VARCHAR(255),
property_form VARCHAR(255),
structure TEXT,
build VARCHAR(255),
sale_term TEXT,
rent_term TEXT,
patients VARCHAR(255),
pharmacy VARCHAR(255),
equipment VARCHAR(255),
transaction VARCHAR(255),
terms TEXT,
contact TEXT,
created DATETIME,
modified DATETIME,
primary key(id)
);
