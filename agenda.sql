CREATE DATABASE crudphp DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE crudphp;

CREATE TABLE agenda (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(45) NOT NULL,
    telefone VARCHAR(15) DEFAULT NULL,
    PRIMARY KEY(id)
);

SELECT * FROM contatos;