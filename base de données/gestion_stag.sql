drop database if exists plongee;
create database if not exists plongee;
use plongee;

create table client(
    idclient int(4) auto_increment primary key,
    nom varchar(50),
    prenom varchar(50),
    civilite varchar(1),
    photo varchar(100),
    idproduit int(4)
);

create table produit(
    idproduit int(4) auto_increment primary key,
    nomproduit varchar(50),
    famille varchar(50),
    prix varchar(50)
);

create table utilisateur(
    iduser int(4) auto_increment primary key,
    login varchar(50),
    email varchar(255),
    role varchar(50),   -- admin ou visiteur
    etat int(1),        -- 1:activé 0:desactivé
    pwd varchar(255)
);

CREATE TABLE produits_selectionnes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  idproduit INT,
  nomproduit VARCHAR(255),
  quantite INT,
  prix DECIMAL(10, 2),
  idclient INT,
  FOREIGN KEY (idproduit) REFERENCES produit(idproduit),
  FOREIGN KEY (idclient) REFERENCES client(idclient)
);

Alter table client add constraint 
    foreign key(idproduit) references produit(idproduit);

INSERT INTO produit(nomproduit,niveau) VALUES
	('gonflage hélium','gonflage', '85€');
	
	
INSERT INTO utilisateur(login,email,role,etat,pwd) VALUES 
    ('admin','admin@gmail.com','ADMIN',1,md5('123')),
    ('user1','user1@gmail.com','VISITEUR',0,md5('123')),
    ('user2','user2@gmail.com','VISITEUR',1,md5('123'));	

INSERT INTO client(nom,prenom,civilite,photo,idproduit) VALUES
    ('SAADAOUI','MOHAMMED','M','Chrysantheme.jpg',1),
	('CHAABI','OMAR','M','Desert.jpg',1),
	('SALIM','RACHIDA','F','Hortensias.jpg',1),
	('FAOUZI','NABILA','F','Meduses.jpg',1),
	('ETTAOUSSI','KAMAL','M','Penguins.jpg',1),
	('EZZAKI','ABDELKARIM','M','Tulipes.jpg',1),
    
     ('SAADAOUI','MOHAMMED','M','Chrysantheme.jpg',1),
	('CHAABI','OMAR','M','Desert.jpg',1),
	('SALIM','RACHIDA','F','Hortensias.jpg',1),
	('FAOUZI','NABILA','F','Meduses.jpg',1),
	('ETTAOUSSI','KAMAL','M','Penguins.jpg',1),
	('EZZAKI','ABDELKARIM','M','Tulipes.jpg',1),

    ('SAADAOUI','MOHAMMED','M','Chrysantheme.jpg',1),
	('CHAABI','OMAR','M','Desert.jpg',1),
	('SALIM','RACHIDA','F','Hortensias.jpg',1),
	('FAOUZI','NABILA','F','Meduses.jpg',1),
	('ETTAOUSSI','KAMAL','M','Penguins.jpg',1),
	('EZZAKI','ABDELKARIM','M','Tulipes.jpg',1);


    CREATE TABLE historique_fichiers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT,
  nom_fichier VARCHAR(255),
  date_telechargement DATETIME
);
  

select * from produit;
select * from client;
select * from utilisateur;


