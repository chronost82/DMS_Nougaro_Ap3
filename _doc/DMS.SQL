DROP DATABASE IF EXISTS dmsDB;

CREATE DATABASE IF NOT EXISTS dmsDB;
USE dmsDB;
# -----------------------------------------------------------------------------
#       TABLE : CT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CT
 (
   IDCT INTEGER(10) NOT NULL AUTO_INCREMENT ,
   IDELEVE INTEGER(2) NOT NULL  ,
   NUMCT INTEGER(20) NULL  ,
   DATECT DATE NULL  ,
   HEURE TIME NULL  ,
   CTENCOURS BOOL NULL  ,
   COMMENTAIRE TEXT NULL  
   , PRIMARY KEY (IDCT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE CT
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_CT_ELEVES
     ON CT (IDELEVE ASC);

# -----------------------------------------------------------------------------
#       TABLE : ELEVES
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ELEVES
 (
   IDELEVE INTEGER(5) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  ,
   PRENOM VARCHAR(128) NULL  ,
   ANNEE VARCHAR(128) NULL  
   , PRIMARY KEY (IDELEVE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : UTILISATEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS UTILISATEUR
 (
   IDUTILISATEUR INTEGER(5) NOT NULL  ,
   LOGIN VARCHAR(128) NULL  ,
   MDP VARCHAR(128) NULL  ,
   ROLE INTEGER(2) NULL  
   , PRIMARY KEY (IDUTILISATEUR) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VEHICULE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VEHICULE
 (
   IDVEHICULE INTEGER(10) NOT NULL AUTO_INCREMENT ,
   MARQUE VARCHAR(128) NULL  ,
   MODELE VARCHAR(128) NULL  
   , PRIMARY KEY (IDVEHICULE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CLIENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CLIENT
 (
   IDCLIENT INTEGER(10) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  ,
   PRENOM VARCHAR(128) NULL  ,
   TEL INTEGER(10) NULL  ,
   MAIL VARCHAR(128) NULL  ,
   NUMRANDOM VARCHAR(128) NULL  
   , PRIMARY KEY (IDCLIENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TESTSTECHNIQUE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TESTSTECHNIQUE
 (
   IDTESTTECHNIQUE INTEGER(10) NOT NULL AUTO_INCREMENT ,
   LIBELLE VARCHAR(128) NULL  
   , PRIMARY KEY (IDTESTTECHNIQUE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : DEMANDE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DEMANDE
 (
   IDDEMANDE INTEGER(10) NOT NULL AUTO_INCREMENT ,
   IDCLIENT INTEGER(10) NULL  ,
   NOM VARCHAR(128) NULL  ,
   PRENOM VARCHAR(128) NULL  ,
   EMAIL VARCHAR(128) NULL  ,
   TEL INTEGER(10) NULL  ,
   MARQUE VARCHAR(128) NULL  ,
   MODELE VARCHAR(128) NULL  ,
   ETAT VARCHAR(128) NULL  
   , PRIMARY KEY (IDDEMANDE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE DEMANDE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_DEMANDE_CLIENT
     ON DEMANDE (IDCLIENT ASC);

# -----------------------------------------------------------------------------
#       TABLE : TEST
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TEST
 (
   IDTESTTECHNIQUE INTEGER(10) NOT NULL  ,
   IDCT INTEGER(10) NOT NULL  ,
   ETAT VARCHAR(128) NULL  
   , PRIMARY KEY (IDTESTTECHNIQUE,IDCT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE TEST
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_TEST_TESTSTECHNIQUE
     ON TEST (IDTESTTECHNIQUE ASC);

CREATE  INDEX I_FK_TEST_CT
     ON TEST (IDCT ASC);

# -----------------------------------------------------------------------------
#       TABLE : POSSEDE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS POSSEDE
 (
   IDCLIENT INTEGER(10) NOT NULL  ,
   IDVEHICULE INTEGER(10) NOT NULL  ,
   IDCT INTEGER(20) NOT NULL  ,
   NUMCHASSIS VARCHAR(128) NULL  ,
   IMAT VARCHAR(128) NULL  ,
   ANNEE INTEGER(4) NULL  
   , PRIMARY KEY (IDCLIENT,IDVEHICULE,IDCT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE POSSEDE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_POSSEDE_CLIENT
     ON POSSEDE (IDCLIENT ASC);

CREATE  INDEX I_FK_POSSEDE_VEHICULE
     ON POSSEDE (IDVEHICULE ASC);

CREATE  INDEX I_FK_POSSEDE_CT
     ON POSSEDE (IDCT ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE CT 
  ADD FOREIGN KEY FK_CT_ELEVES (IDELEVE)
      REFERENCES ELEVES (IDELEVE) ;


ALTER TABLE DEMANDE 
  ADD FOREIGN KEY FK_DEMANDE_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE TEST 
  ADD FOREIGN KEY FK_TEST_TESTSTECHNIQUE (IDTESTTECHNIQUE)
      REFERENCES TESTSTECHNIQUE (IDTESTTECHNIQUE) ;


ALTER TABLE TEST 
  ADD FOREIGN KEY FK_TEST_CT (IDCT)
      REFERENCES CT (IDCT) ;


ALTER TABLE POSSEDE 
  ADD FOREIGN KEY FK_POSSEDE_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE POSSEDE 
  ADD FOREIGN KEY FK_POSSEDE_VEHICULE (IDVEHICULE)
      REFERENCES VEHICULE (IDVEHICULE) ;


ALTER TABLE POSSEDE 
  ADD FOREIGN KEY FK_POSSEDE_CT (IDCT)
      REFERENCES CT (IDCT) ;

