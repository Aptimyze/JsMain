use MIS;
CREATE TABLE `DELETED_PROFILE_VIEWS` (
 `DATE` date NOT NULL,
 `STYPE` char(2) NOT NULL,
 `ACTIVATED` char(1) NOT NULL,
 `COUNT` int(11) NOT NULL,
 PRIMARY KEY (`DATE`,`STYPE`,`ACTIVATED`)
) ENGINE=MYISAM;
