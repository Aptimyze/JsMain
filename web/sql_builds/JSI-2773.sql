CREATE DATABASE jsprofiler;
CREATE TABLE jsprofiler.`MYJS_PROFILER_LOGS` (
 `Id` bigint(12) NOT NULL AUTO_INCREMENT,
 `Request_Id` varchar(128) NOT NULL,
 `ModuleName` varchar(64) NOT NULL,
 `Memory_In_Mb` double(10,5) NOT NULL,
 `Time` double(12,8) NOT NULL,
 `Label` varchar(4096) NOT NULL,
 `Channel` varchar(8) NOT NULL,
 PRIMARY KEY (`Id`)
) ENGINE=MyISAM;

