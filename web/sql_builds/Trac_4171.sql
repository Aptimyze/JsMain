use billing;

UPDATE SERVICES SET PRICE_RS_TAX=3295,PRICE_DOL=105,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='P3' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=3795,PRICE_DOL=110,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='P4' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=4095,PRICE_DOL=120,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='P5' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=4495,PRICE_DOL=150,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='P6' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=5695,PRICE_DOL=155,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='P8' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=7395,PRICE_DOL=205,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='P12' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=8995,PRICE_DOL=240,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='PL' LIMIT 1;

UPDATE SERVICES SET PRICE_RS_TAX=3995,PRICE_DOL=115,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='C3' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=4495,PRICE_DOL=140,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='C4' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=4895,PRICE_DOL=150,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='C5' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=5395,PRICE_DOL=175,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='C6' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=6995,PRICE_DOL=190,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='C8' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=8995,PRICE_DOL=220,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='C12' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX=10895,PRICE_DOL=255,PRICE_RS=ROUND(PRICE_RS_TAX/1.236,2) WHERE SERVICEID='CL' LIMIT 1;

UPDATE SERVICES SET SHOW_ONLINE='N' WHERE SERVICEID='P9' LIMIT 1;
UPDATE SERVICES SET SHOW_ONLINE='N' WHERE SERVICEID='C9' LIMIT 1;

UPDATE  `DIRECT_CALL_COUNT` SET  `COUNT` =  '75' WHERE CONVERT(  `SERVICEID` USING utf8 ) =  'P3' AND  `COUNT` =  '60' LIMIT 1 ;
UPDATE  `DIRECT_CALL_COUNT` SET  `COUNT` =  '225' WHERE CONVERT(  `SERVICEID` USING utf8 ) =  'PL' AND  `COUNT` =  '250' LIMIT 1 ;
UPDATE  `DIRECT_CALL_COUNT` SET  `COUNT` =  '225' WHERE CONVERT(  `SERVICEID` USING utf8 ) =  'P12' AND  `COUNT` =  '200' LIMIT 1 ;
UPDATE  `DIRECT_CALL_COUNT` SET  `COUNT` =  '75' WHERE CONVERT(  `SERVICEID` USING utf8 ) =  'C3' AND  `COUNT` =  '60' LIMIT 1 ;
UPDATE  `DIRECT_CALL_COUNT` SET  `COUNT` =  '225' WHERE CONVERT(  `SERVICEID` USING utf8 ) =  'CL' AND  `COUNT` =  '250' LIMIT 1 ;
UPDATE  `DIRECT_CALL_COUNT` SET  `COUNT` =  '225' WHERE CONVERT(  `SERVICEID` USING utf8 ) =  'C12' AND  `COUNT` =  '200' LIMIT 1 ;