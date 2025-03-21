CREATE DATABASE Tiendaropa;

USE TiendaRopa;

CREATE TABLE productos(
id			INT AUTO_INCREMENT PRIMARY KEY,
tipo		VARCHAR(40) NOT NULL,
genero		ENUM('Femenino','Masculino'),
talla	   VARCHAR(40) not null,
precio		DECIMAL(5,2) NOT NULL
)ENGINE=INNODB;


INSERT INTO productos(tipo,genero,talla,precio)
 VALUES 
	('Pantalón','Femenino','M',150),
    ('Camisa','Masculino','L',145);
    
SELECT * FROM productos;