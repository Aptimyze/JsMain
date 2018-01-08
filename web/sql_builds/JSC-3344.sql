use billing;

UPDATE SERVICES SET PRICE_RS_TAX='500',PRICE_RS=ROUND(PRICE_RS_TAX/1.18,2),desktop_RS='500',mobile_website_RS='500',JSAA_mobile_website_RS='500',Android_app_RS='500',old_mobile_website_RS='500' WHERE SERVICEID='P2' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX='1000',PRICE_RS=ROUND(PRICE_RS_TAX/1.18,2),desktop_RS='1000',mobile_website_RS='1000',JSAA_mobile_website_RS='1000',Android_app_RS='1000',old_mobile_website_RS='1000' WHERE SERVICEID='P4' LIMIT 1;

UPDATE SERVICES SET PRICE_RS_TAX='600',PRICE_RS=ROUND(PRICE_RS_TAX/1.18,2),desktop_RS='600',mobile_website_RS='600',JSAA_mobile_website_RS='600',Android_app_RS='600',old_mobile_website_RS='600' WHERE SERVICEID='C2' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX='1200',PRICE_RS=ROUND(PRICE_RS_TAX/1.18,2),desktop_RS='1200',mobile_website_RS='1200',JSAA_mobile_website_RS='1200',Android_app_RS='1200',old_mobile_website_RS='1200' WHERE SERVICEID='C4' LIMIT 1;

UPDATE SERVICES SET PRICE_RS_TAX='750',PRICE_RS=ROUND(PRICE_RS_TAX/1.18,2),desktop_RS='750',mobile_website_RS='750',JSAA_mobile_website_RS='750',Android_app_RS='750',old_mobile_website_RS='750' WHERE SERVICEID='NCP2' LIMIT 1;
UPDATE SERVICES SET PRICE_RS_TAX='1400',PRICE_RS=ROUND(PRICE_RS_TAX/1.18,2),desktop_RS='1400',mobile_website_RS='1400',JSAA_mobile_website_RS='1400',Android_app_RS='1400',old_mobile_website_RS='1400' WHERE SERVICEID='NCP4' LIMIT 1;


