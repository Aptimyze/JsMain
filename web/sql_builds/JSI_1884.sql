use jeevansathi_mailer;

UPDATE  `LINK_MAILERS` SET  `APP_SCREEN_ID` = NULL ,
`LINK_URL` =  'settings/jspcSettings?hideDelete=1',
`OTHER_GET_PARAMS` = NULL WHERE  `LINKID` =  '31' LIMIT 1 ;
