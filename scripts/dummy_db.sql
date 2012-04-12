﻿# SQL Manager 2007 for MySQL 4.2.0.2
# ---------------------------------------
# Host     : phpmyadmin.tsab.local
# Port     : 3306
# Database : dummy_db


SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `dummy_db`;

CREATE DATABASE `dummy_db`
    CHARACTER SET 'latin1'
    COLLATE 'latin1_swedish_ci';

USE `dummy_db`;

#
# Structure for the `country` table : 
#

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `printable_name` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`iso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Data for the `country` table  (LIMIT 0,500)
#

INSERT INTO `country` (`iso`, `name`, `printable_name`, `iso3`, `numcode`) VALUES 
  ('AF','AFGHANISTAN','Afghanistan','AFG',4),
  ('AL','ALBANIA','Albania','ALB',8),
  ('DZ','ALGERIA','Algeria','DZA',12),
  ('AS','AMERICAN SAMOA','American Samoa','ASM',16),
  ('AD','ANDORRA','Andorra','AND',20),
  ('AO','ANGOLA','Angola','AGO',24),
  ('AI','ANGUILLA','Anguilla','AIA',660),
  ('AQ','ANTARCTICA','Antarctica',NULL,NULL),
  ('AG','ANTIGUA AND BARBUDA','Antigua and Barbuda','ATG',28),
  ('AR','ARGENTINA','Argentina','ARG',32),
  ('AM','ARMENIA','Armenia','ARM',51),
  ('AW','ARUBA','Aruba','ABW',533),
  ('AU','AUSTRALIA','Australia','AUS',36),
  ('AT','AUSTRIA','Austria','AUT',40),
  ('AZ','AZERBAIJAN','Azerbaijan','AZE',31),
  ('BS','BAHAMAS','Bahamas','BHS',44),
  ('BH','BAHRAIN','Bahrain','BHR',48),
  ('BD','BANGLADESH','Bangladesh','BGD',50),
  ('BB','BARBADOS','Barbados','BRB',52),
  ('BY','BELARUS','Belarus','BLR',112),
  ('BE','BELGIUM','Belgium','BEL',56),
  ('BZ','BELIZE','Belize','BLZ',84),
  ('BJ','BENIN','Benin','BEN',204),
  ('BM','BERMUDA','Bermuda','BMU',60),
  ('BT','BHUTAN','Bhutan','BTN',64),
  ('BO','BOLIVIA','Bolivia','BOL',68),
  ('BA','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina','BIH',70),
  ('BW','BOTSWANA','Botswana','BWA',72),
  ('BV','BOUVET ISLAND','Bouvet Island',NULL,NULL),
  ('BR','BRAZIL','Brazil','BRA',76),
  ('IO','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL),
  ('BN','BRUNEI DARUSSALAM','Brunei Darussalam','BRN',96),
  ('BG','BULGARIA','Bulgaria','BGR',100),
  ('BF','BURKINA FASO','Burkina Faso','BFA',854),
  ('BI','BURUNDI','Burundi','BDI',108),
  ('KH','CAMBODIA','Cambodia','KHM',116),
  ('CM','CAMEROON','Cameroon','CMR',120),
  ('CA','CANADA','Canada','CAN',124),
  ('CV','CAPE VERDE','Cape Verde','CPV',132),
  ('KY','CAYMAN ISLANDS','Cayman Islands','CYM',136),
  ('CF','CENTRAL AFRICAN REPUBLIC','Central African Republic','CAF',140),
  ('TD','CHAD','Chad','TCD',148),
  ('CL','CHILE','Chile','CHL',152),
  ('CN','CHINA','China','CHN',156),
  ('CX','CHRISTMAS ISLAND','Christmas Island',NULL,NULL),
  ('CC','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL),
  ('CO','COLOMBIA','Colombia','COL',170),
  ('KM','COMOROS','Comoros','COM',174),
  ('CG','CONGO','Congo','COG',178),
  ('CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the','COD',180),
  ('CK','COOK ISLANDS','Cook Islands','COK',184),
  ('CR','COSTA RICA','Costa Rica','CRI',188),
  ('CI','COTE D''IVOIRE','Cote D''Ivoire','CIV',384),
  ('HR','CROATIA','Croatia','HRV',191),
  ('CU','CUBA','Cuba','CUB',192),
  ('CY','CYPRUS','Cyprus','CYP',196),
  ('CZ','CZECH REPUBLIC','Czech Republic','CZE',203),
  ('DK','DENMARK','Denmark','DNK',208),
  ('DJ','DJIBOUTI','Djibouti','DJI',262),
  ('DM','DOMINICA','Dominica','DMA',212),
  ('DO','DOMINICAN REPUBLIC','Dominican Republic','DOM',214),
  ('EC','ECUADOR','Ecuador','ECU',218),
  ('EG','EGYPT','Egypt','EGY',818),
  ('SV','EL SALVADOR','El Salvador','SLV',222),
  ('GQ','EQUATORIAL GUINEA','Equatorial Guinea','GNQ',226),
  ('ER','ERITREA','Eritrea','ERI',232),
  ('EE','ESTONIA','Estonia','EST',233),
  ('ET','ETHIOPIA','Ethiopia','ETH',231),
  ('FK','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)','FLK',238),
  ('FO','FAROE ISLANDS','Faroe Islands','FRO',234),
  ('FJ','FIJI','Fiji','FJI',242),
  ('FI','FINLAND','Finland','FIN',246),
  ('FR','FRANCE','France','FRA',250),
  ('GF','FRENCH GUIANA','French Guiana','GUF',254),
  ('PF','FRENCH POLYNESIA','French Polynesia','PYF',258),
  ('TF','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL),
  ('GA','GABON','Gabon','GAB',266),
  ('GM','GAMBIA','Gambia','GMB',270),
  ('GE','GEORGIA','Georgia','GEO',268),
  ('DE','GERMANY','Germany','DEU',276),
  ('GH','GHANA','Ghana','GHA',288),
  ('GI','GIBRALTAR','Gibraltar','GIB',292),
  ('GR','GREECE','Greece','GRC',300),
  ('GL','GREENLAND','Greenland','GRL',304),
  ('GD','GRENADA','Grenada','GRD',308),
  ('GP','GUADELOUPE','Guadeloupe','GLP',312),
  ('GU','GUAM','Guam','GUM',316),
  ('GT','GUATEMALA','Guatemala','GTM',320),
  ('GN','GUINEA','Guinea','GIN',324),
  ('GW','GUINEA-BISSAU','Guinea-Bissau','GNB',624),
  ('GY','GUYANA','Guyana','GUY',328),
  ('HT','HAITI','Haiti','HTI',332),
  ('HM','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL),
  ('VA','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)','VAT',336),
  ('HN','HONDURAS','Honduras','HND',340),
  ('HK','HONG KONG','Hong Kong','HKG',344),
  ('HU','HUNGARY','Hungary','HUN',348),
  ('IS','ICELAND','Iceland','ISL',352),
  ('IN','INDIA','India','IND',356),
  ('ID','INDONESIA','Indonesia','IDN',360),
  ('IR','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of','IRN',364),
  ('IQ','IRAQ','Iraq','IRQ',368),
  ('IE','IRELAND','Ireland','IRL',372),
  ('IL','ISRAEL','Israel','ISR',376),
  ('IT','ITALY','Italy','ITA',380),
  ('JM','JAMAICA','Jamaica','JAM',388),
  ('JP','JAPAN','Japan','JPN',392),
  ('JO','JORDAN','Jordan','JOR',400),
  ('KZ','KAZAKHSTAN','Kazakhstan','KAZ',398),
  ('KE','KENYA','Kenya','KEN',404),
  ('KI','KIRIBATI','Kiribati','KIR',296),
  ('KP','KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF','Korea, Democratic People''s Republic of','PRK',408),
  ('KR','KOREA, REPUBLIC OF','Korea, Republic of','KOR',410),
  ('KW','KUWAIT','Kuwait','KWT',414),
  ('KG','KYRGYZSTAN','Kyrgyzstan','KGZ',417),
  ('LA','LAO PEOPLE''S DEMOCRATIC REPUBLIC','Lao People''s Democratic Republic','LAO',418),
  ('LV','LATVIA','Latvia','LVA',428),
  ('LB','LEBANON','Lebanon','LBN',422),
  ('LS','LESOTHO','Lesotho','LSO',426),
  ('LR','LIBERIA','Liberia','LBR',430),
  ('LY','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya','LBY',434),
  ('LI','LIECHTENSTEIN','Liechtenstein','LIE',438),
  ('LT','LITHUANIA','Lithuania','LTU',440),
  ('LU','LUXEMBOURG','Luxembourg','LUX',442),
  ('MO','MACAO','Macao','MAC',446),
  ('MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of','MKD',807),
  ('MG','MADAGASCAR','Madagascar','MDG',450),
  ('MW','MALAWI','Malawi','MWI',454),
  ('MY','MALAYSIA','Malaysia','MYS',458),
  ('MV','MALDIVES','Maldives','MDV',462),
  ('ML','MALI','Mali','MLI',466),
  ('MT','MALTA','Malta','MLT',470),
  ('MH','MARSHALL ISLANDS','Marshall Islands','MHL',584),
  ('MQ','MARTINIQUE','Martinique','MTQ',474),
  ('MR','MAURITANIA','Mauritania','MRT',478),
  ('MU','MAURITIUS','Mauritius','MUS',480),
  ('YT','MAYOTTE','Mayotte',NULL,NULL),
  ('MX','MEXICO','Mexico','MEX',484),
  ('FM','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of','FSM',583),
  ('MD','MOLDOVA, REPUBLIC OF','Moldova, Republic of','MDA',498),
  ('MC','MONACO','Monaco','MCO',492),
  ('MN','MONGOLIA','Mongolia','MNG',496),
  ('MS','MONTSERRAT','Montserrat','MSR',500),
  ('MA','MOROCCO','Morocco','MAR',504),
  ('MZ','MOZAMBIQUE','Mozambique','MOZ',508),
  ('MM','MYANMAR','Myanmar','MMR',104),
  ('NA','NAMIBIA','Namibia','NAM',516),
  ('NR','NAURU','Nauru','NRU',520),
  ('NP','NEPAL','Nepal','NPL',524),
  ('NL','NETHERLANDS','Netherlands','NLD',528),
  ('AN','NETHERLANDS ANTILLES','Netherlands Antilles','ANT',530),
  ('NC','NEW CALEDONIA','New Caledonia','NCL',540),
  ('NZ','NEW ZEALAND','New Zealand','NZL',554),
  ('NI','NICARAGUA','Nicaragua','NIC',558),
  ('NE','NIGER','Niger','NER',562),
  ('NG','NIGERIA','Nigeria','NGA',566),
  ('NU','NIUE','Niue','NIU',570),
  ('NF','NORFOLK ISLAND','Norfolk Island','NFK',574),
  ('MP','NORTHERN MARIANA ISLANDS','Northern Mariana Islands','MNP',580),
  ('NO','NORWAY','Norway','NOR',578),
  ('OM','OMAN','Oman','OMN',512),
  ('PK','PAKISTAN','Pakistan','PAK',586),
  ('PW','PALAU','Palau','PLW',585),
  ('PS','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL),
  ('PA','PANAMA','Panama','PAN',591),
  ('PG','PAPUA NEW GUINEA','Papua New Guinea','PNG',598),
  ('PY','PARAGUAY','Paraguay','PRY',600),
  ('PE','PERU','Peru','PER',604),
  ('PH','PHILIPPINES','Philippines','PHL',608),
  ('PN','PITCAIRN','Pitcairn','PCN',612),
  ('PL','POLAND','Poland','POL',616),
  ('PT','PORTUGAL','Portugal','PRT',620),
  ('PR','PUERTO RICO','Puerto Rico','PRI',630),
  ('QA','QATAR','Qatar','QAT',634),
  ('RE','REUNION','Reunion','REU',638),
  ('RO','ROMANIA','Romania','ROM',642),
  ('RU','RUSSIAN FEDERATION','Russian Federation','RUS',643),
  ('RW','RWANDA','Rwanda','RWA',646),
  ('SH','SAINT HELENA','Saint Helena','SHN',654),
  ('KN','SAINT KITTS AND NEVIS','Saint Kitts and Nevis','KNA',659),
  ('LC','SAINT LUCIA','Saint Lucia','LCA',662),
  ('PM','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon','SPM',666),
  ('VC','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines','VCT',670),
  ('WS','SAMOA','Samoa','WSM',882),
  ('SM','SAN MARINO','San Marino','SMR',674),
  ('ST','SAO TOME AND PRINCIPE','Sao Tome and Principe','STP',678),
  ('SA','SAUDI ARABIA','Saudi Arabia','SAU',682),
  ('SN','SENEGAL','Senegal','SEN',686),
  ('CS','SERBIA AND MONTENEGRO','Serbia and Montenegro',NULL,NULL),
  ('SC','SEYCHELLES','Seychelles','SYC',690),
  ('SL','SIERRA LEONE','Sierra Leone','SLE',694),
  ('SG','SINGAPORE','Singapore','SGP',702),
  ('SK','SLOVAKIA','Slovakia','SVK',703),
  ('SI','SLOVENIA','Slovenia','SVN',705),
  ('SB','SOLOMON ISLANDS','Solomon Islands','SLB',90),
  ('SO','SOMALIA','Somalia','SOM',706),
  ('ZA','SOUTH AFRICA','South Africa','ZAF',710),
  ('GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL),
  ('ES','SPAIN','Spain','ESP',724),
  ('LK','SRI LANKA','Sri Lanka','LKA',144),
  ('SD','SUDAN','Sudan','SDN',736),
  ('SR','SURINAME','Suriname','SUR',740),
  ('SJ','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen','SJM',744),
  ('SZ','SWAZILAND','Swaziland','SWZ',748),
  ('SE','SWEDEN','Sweden','SWE',752),
  ('CH','SWITZERLAND','Switzerland','CHE',756),
  ('SY','SYRIAN ARAB REPUBLIC','Syrian Arab Republic','SYR',760),
  ('TW','TAIWAN, PROVINCE OF CHINA','Taiwan, Province of China','TWN',158),
  ('TJ','TAJIKISTAN','Tajikistan','TJK',762),
  ('TZ','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of','TZA',834),
  ('TH','THAILAND','Thailand','THA',764),
  ('TL','TIMOR-LESTE','Timor-Leste',NULL,NULL),
  ('TG','TOGO','Togo','TGO',768),
  ('TK','TOKELAU','Tokelau','TKL',772),
  ('TO','TONGA','Tonga','TON',776),
  ('TT','TRINIDAD AND TOBAGO','Trinidad and Tobago','TTO',780),
  ('TN','TUNISIA','Tunisia','TUN',788),
  ('TR','TURKEY','Turkey','TUR',792),
  ('TM','TURKMENISTAN','Turkmenistan','TKM',795),
  ('TC','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands','TCA',796),
  ('TV','TUVALU','Tuvalu','TUV',798),
  ('UG','UGANDA','Uganda','UGA',800),
  ('UA','UKRAINE','Ukraine','UKR',804),
  ('AE','UNITED ARAB EMIRATES','United Arab Emirates','ARE',784),
  ('GB','UNITED KINGDOM','United Kingdom','GBR',826),
  ('US','UNITED STATES','United States','USA',840),
  ('UM','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL),
  ('UY','URUGUAY','Uruguay','URY',858),
  ('UZ','UZBEKISTAN','Uzbekistan','UZB',860),
  ('VU','VANUATU','Vanuatu','VUT',548),
  ('VE','VENEZUELA','Venezuela','VEN',862),
  ('VN','VIET NAM','Viet Nam','VNM',704),
  ('VG','VIRGIN ISLANDS, BRITISH','Virgin Islands, British','VGB',92),
  ('VI','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.','VIR',850),
  ('WF','WALLIS AND FUTUNA','Wallis and Futuna','WLF',876),
  ('EH','WESTERN SAHARA','Western Sahara','ESH',732),
  ('YE','YEMEN','Yemen','YEM',887),
  ('ZM','ZAMBIA','Zambia','ZMB',894),
  ('ZW','ZIMBABWE','Zimbabwe','ZWE',716);

COMMIT;

#
# Structure for the `user_profile` table : 
#

DROP TABLE IF EXISTS `user_profile`;

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) NOT NULL,
  `email_add1` varchar(100) DEFAULT NULL,
  `email_add2` varchar(100) DEFAULT NULL,
  `contact_no` varchar(45) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='For testing only';

#
# Data for the `user_profile` table  (LIMIT 0,500)
#

INSERT INTO `user_profile` (`user_id`, `first_name`, `middle_name`, `last_name`, `email_add1`, `email_add2`, `contact_no`, `date_added`, `last_modified`) VALUES 
  (3,'Eric Jose','Exaltacion','Salip Ahmad','ejsalipahmad@yahoo.com',NULL,'09227700303','2012-04-10 19:04:47','2012-04-10 19:04:47'),
  (4,'Borgy','Makapugong','Manotoy','borgymanotoy@gmail.com',NULL,'09173881518','2012-04-10 19:05:15','2012-04-10 19:05:15'),
  (5,'Charles','Austria','Amper','camper@yahoo.com',NULL,'09167135122','2012-04-10 19:37:58','2012-04-10 19:37:58'),
  (6,'Fredrik','','Nygren','nygren@telescopesoftware.se',NULL,'09123456789','2012-04-10 19:53:22','2012-04-10 19:53:22'),
  (7,'Jefferson','King','Kong','jeff@telescopesoftware.se',NULL,'09171234567','2012-04-12 18:57:08','2012-04-12 18:57:08');

COMMIT;

#
# Definition for the `show_contacts` view : 
#

DROP VIEW IF EXISTS `show_contacts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `show_contacts` AS 
  select 
    concat(`user_profile`.`first_name`,' ',if((length(substr(`user_profile`.`middle_name`,1,1)) > 0),concat(substr(`user_profile`.`middle_name`,1,1),'. '),' '),`user_profile`.`last_name`) AS `name`,
    `user_profile`.`email_add1` AS `email_add1`,
    `user_profile`.`contact_no` AS `contact_no` 
  from 
    `user_profile`;

