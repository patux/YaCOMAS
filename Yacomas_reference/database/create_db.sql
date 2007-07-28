CREATE DATABASE yacomas_db;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP
ON yacomas_db.* TO 'yacomas_user'@'localhost' IDENTIFIED BY 'yacomas_pwd';
USE yacomas_db;

--
-- Table structure for table 'administrador'
--

CREATE TABLE administrador (
  id int(10) unsigned NOT NULL auto_increment,
  login varchar(15) NOT NULL default '',
  passwd varchar(15) NOT NULL default '',
  nombrep varchar(50) NOT NULL default '',
  apellidos varchar(50) NOT NULL default '',
  mail varchar(100) NOT NULL default '',
  id_tadmin int(10) NOT NULL default '0',
  PRIMARY KEY  (id,login,id_tadmin)
) TYPE=MyISAM;

--
-- Table structure for table 'asistente'
--

CREATE TABLE asistente (
  id int(10) unsigned NOT NULL auto_increment,
  login varchar(15) NOT NULL default '',
  passwd varchar(15) NOT NULL default '',
  nombrep varchar(50) NOT NULL default '',
  apellidos varchar(50) NOT NULL default '',
  sexo char(1) NOT NULL default '',
  mail varchar(100) NOT NULL default '',
  ciudad varchar(100) default NULL,
  org varchar(100) default NULL,
  fecha_nac date default NULL,
  reg_time datetime NOT NULL default '0000-00-00 00:00:00',
  act_time timestamp(14) NOT NULL,
  id_estudios int(10) unsigned NOT NULL default '0',
  id_tasistente int(10) unsigned NOT NULL default '0',
  id_estado int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id,login,id_estudios,id_tasistente,id_estado)
) TYPE=MyISAM;

--
-- Table structure for table 'config'
--

CREATE TABLE config (
  id int(11) NOT NULL auto_increment,
  descr varchar(100) default NULL,
  status tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'estado'
--

CREATE TABLE estado (
  id int(10) unsigned NOT NULL auto_increment,
  descr varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'estudios'
--

CREATE TABLE estudios (
  id int(10) unsigned NOT NULL auto_increment,
  descr varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'evento'
--

CREATE TABLE evento (
  id int(10) unsigned NOT NULL auto_increment,
  id_propuesta int(10) unsigned NOT NULL default '0',
  id_administrador int(10) unsigned NOT NULL default '0',
  reg_time datetime NOT NULL default '0000-00-00 00:00:00',
  act_time timestamp(14) NOT NULL,
  PRIMARY KEY  (id,id_propuesta,id_administrador)
) TYPE=MyISAM;

--
-- Table structure for table 'evento_ocupa'
--

CREATE TABLE evento_ocupa (
  id_evento int(11) NOT NULL default '0',
  hora tinyint(4) NOT NULL default '0',
  id_fecha int(11) NOT NULL default '0',
  id_lugar int(11) NOT NULL default '0',
  PRIMARY KEY  (hora,id_fecha,id_lugar)
) TYPE=MyISAM;

--
-- Table structure for table 'fecha_evento'
--

CREATE TABLE fecha_evento (
  id int(11) NOT NULL auto_increment,
  fecha date default NULL,
  descr varchar(50) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'inscribe'
--

CREATE TABLE inscribe (
  id_asistente int(10) unsigned NOT NULL default '0',
  id_evento int(10) unsigned NOT NULL default '0',
  reg_time datetime NOT NULL default '0000-00-00 00:00:00',
  act_time timestamp(14) NOT NULL,
  PRIMARY KEY  (id_asistente,id_evento)
) TYPE=MyISAM;

--
-- Table structure for table 'lugar'
--

CREATE TABLE lugar (
  id int(10) unsigned NOT NULL auto_increment,
  cupo int(11) NOT NULL default '99999',
  nombre_lug varchar(100) NOT NULL default '',
  ubicacion varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'orientacion'
--

CREATE TABLE orientacion (
  id int(10) unsigned NOT NULL auto_increment,
  descr varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'ponente'
--

CREATE TABLE ponente (
  id int(10) unsigned NOT NULL auto_increment,
  login varchar(15) NOT NULL default '',
  passwd varchar(15) NOT NULL default '',
  nombrep varchar(50) NOT NULL default '',
  apellidos varchar(50) NOT NULL default '',
  sexo char(1) NOT NULL default '',
  mail varchar(100) NOT NULL default '',
  ciudad varchar(100) default NULL,
  org varchar(100) default NULL,
  titulo varchar(50) default NULL,
  resume text,
  domicilio varchar(100) default NULL,
  telefono varchar(100) NOT NULL default '',
  fecha_nac date default NULL,
  reg_time datetime NOT NULL default '0000-00-00 00:00:00',
  act_time timestamp(14) NOT NULL,
  id_estudios int(10) unsigned NOT NULL default '0',
  id_estado int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id,login,id_estudios,id_estado)
) TYPE=MyISAM;

--
-- Table structure for table 'prop_nivel'
--

CREATE TABLE prop_nivel (
  id int(11) NOT NULL auto_increment,
  descr varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'prop_status'
--

CREATE TABLE prop_status (
  id int(10) unsigned NOT NULL auto_increment,
  descr varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'propuesta'
--

CREATE TABLE propuesta (
  id int(10) unsigned NOT NULL auto_increment,
  nombre varchar(50) NOT NULL default '',
  id_nivel int(10) NOT NULL default '0',
  tpropuesta char(1) NOT NULL default '',
  duracion int(10) unsigned NOT NULL default '0',
  resumen text NOT NULL,
  reqtecnicos text,
  reqasistente text,
  id_ponente int(10) unsigned NOT NULL default '0',
  id_administrador int(10) unsigned NOT NULL default '0',
  id_orientacion int(10) unsigned NOT NULL default '0',
  id_status int(10) unsigned NOT NULL default '1',
  reg_time datetime NOT NULL default '0000-00-00 00:00:00',
  act_time timestamp(14) NOT NULL,
  PRIMARY KEY  (id,id_ponente,id_administrador,id_orientacion,id_status)
) TYPE=MyISAM;

--
-- Table structure for table 'tadmin'
--

CREATE TABLE tadmin (
  id int(10) unsigned NOT NULL auto_increment,
  descr varchar(100) default NULL,
  tareas varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table 'tasistente'
--

CREATE TABLE tasistente (
  id int(10) unsigned NOT NULL auto_increment,
  descr varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

------------------------------------------------
-- DATA INSERTION
------------------------------------------------

--
-- Dumping data for table 'administrador'
--


INSERT INTO administrador VALUES (1,'admin','21232f297a57a5a','Administrador','Principal','my@email.com',1);

--
-- Dumping data for table 'config'
--

INSERT INTO config VALUES (1,'Registro ponentes',1);
INSERT INTO config VALUES (2,'Registro asistentes',1);
INSERT INTO config VALUES (3,'Registro ponencias',1);
INSERT INTO config VALUES (4,'Inscripcion talleres',1);

--
-- Dumping data for table 'estado'
--


INSERT INTO estado VALUES (1,'Aguascalientes');
INSERT INTO estado VALUES (2,'Baja California');
INSERT INTO estado VALUES (3,'Baja California Sur');
INSERT INTO estado VALUES (4,'Campeche');
INSERT INTO estado VALUES (5,'Chiapas');
INSERT INTO estado VALUES (6,'Chihuahua');
INSERT INTO estado VALUES (7,'Coahuila');
INSERT INTO estado VALUES (8,'Colima');
INSERT INTO estado VALUES (9,'Distrito Federal');
INSERT INTO estado VALUES (10,'Durango');
INSERT INTO estado VALUES (11,'Guanajuato');
INSERT INTO estado VALUES (12,'Guerrero');
INSERT INTO estado VALUES (13,'Hidalgo');
INSERT INTO estado VALUES (14,'Jalisco');
INSERT INTO estado VALUES (15,'México');
INSERT INTO estado VALUES (16,'Michoacán');
INSERT INTO estado VALUES (17,'Morelos');
INSERT INTO estado VALUES (18,'Nayarit');
INSERT INTO estado VALUES (19,'Nuevo León');
INSERT INTO estado VALUES (20,'Oaxaca');
INSERT INTO estado VALUES (21,'Puebla');
INSERT INTO estado VALUES (22,'Querétaro');
INSERT INTO estado VALUES (23,'Quintana Roo');
INSERT INTO estado VALUES (24,'San Luis Potosí');
INSERT INTO estado VALUES (25,'Sinaloa');
INSERT INTO estado VALUES (26,'Sonora');
INSERT INTO estado VALUES (27,'Tabasco');
INSERT INTO estado VALUES (28,'Tamaulipas');
INSERT INTO estado VALUES (29,'Tlaxcala');
INSERT INTO estado VALUES (30,'Veracruz');
INSERT INTO estado VALUES (31,'Yucatán');
INSERT INTO estado VALUES (32,'Zacatecas');
INSERT INTO estado VALUES (33,'Fuera de México');

--
-- Dumping data for table 'estudios'
--


INSERT INTO estudios VALUES (1,'Ninguno');
INSERT INTO estudios VALUES (2,'Técnico');
INSERT INTO estudios VALUES (3,'Licenciatura');
INSERT INTO estudios VALUES (4,'Maestría');
INSERT INTO estudios VALUES (5,'Doctorado');
INSERT INTO estudios VALUES (6,'Otro');

--
-- Dumping data for table 'orientacion'
--


INSERT INTO orientacion VALUES (1,'Desarollo de Software');
INSERT INTO orientacion VALUES (2,'Seguridad y redes');
INSERT INTO orientacion VALUES (3,'Aplicaciones');
INSERT INTO orientacion VALUES (4,'Principiantes');
INSERT INTO orientacion VALUES (5,'Educacion');
INSERT INTO orientacion VALUES (6,'Otro');

--
-- Dumping data for table 'prop_nivel'
--


INSERT INTO prop_nivel VALUES (1,'Basico');
INSERT INTO prop_nivel VALUES (2,'Intermedio');
INSERT INTO prop_nivel VALUES (3,'Avanzado');

--
-- Dumping data for table 'prop_status'
--
-- Be careful with the order of prop status
-- To make yacomas work perfect you have to 
-- leave this values as is.
-- If you modify something yacomas won't work 


INSERT INTO prop_status VALUES (1,'Nueva');
INSERT INTO prop_status VALUES (2,'Detalles requeridos');
INSERT INTO prop_status VALUES (3,'Rechazada');
INSERT INTO prop_status VALUES (4,'Por Aceptar');
INSERT INTO prop_status VALUES (5,'Aceptada');
INSERT INTO prop_status VALUES (6,'Cancelada');
INSERT INTO prop_status VALUES (7,'Eliminada');
INSERT INTO prop_status VALUES (8,'Programada');

--
-- Dumping data for table 'tadmin'
--

INSERT INTO tadmin VALUES (1,'Total',NULL);
INSERT INTO tadmin VALUES (2,'Parcial',NULL);
INSERT INTO tadmin VALUES (3,'Evaluador',NULL);

--
-- Dumping data for table 'tasistente'
--


INSERT INTO tasistente VALUES (1,'Estudiante');
INSERT INTO tasistente VALUES (2,'Academico');
INSERT INTO tasistente VALUES (3,'Empresa');
INSERT INTO tasistente VALUES (4,'Externo');

