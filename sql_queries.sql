# Insert global admin
INSERT INTO personaldata VALUES(null,'2022-02-22','pcs_globaladmin',null,'globaladmin','global','admin',null,null,null,null,null,null,null,null,null,'228','Dementia','all','all');

#MANUALLY LOADING SQL DATA INTO TABLES:
LOAD DATA LOCAL INFILE '~/Downloads/interventions.sql' INTO TABLE  `abait-c`.interventions;

### Increase notify field size
ALTER TABLE `personaldata` CHANGE `notify` `notify` VARCHAR(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;