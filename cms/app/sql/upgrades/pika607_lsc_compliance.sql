ALTER TABLE cases 
ADD COLUMN lsc_cfr_1605 TINYINT default NULL,
ADD COLUMN lsc_cfr_1609 TINYINT default NULL AFTER lsc_cfr_1605,
ADD COLUMN lsc_cfr_1612 TINYINT default NULL AFTER lsc_cfr_1609,
ADD COLUMN lsc_cfr_1620 TINYINT default NULL AFTER lsc_cfr_1612,
ADD COLUMN lsc_cfr_1633 TINYINT default NULL AFTER lsc_cfr_1620,
ADD COLUMN lsc_cfr_1636 TINYINT default NULL AFTER lsc_cfr_1633,
ADD COLUMN lsc_cfr_1637 TINYINT default NULL AFTER lsc_cfr_1636;