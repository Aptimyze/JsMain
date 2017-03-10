USE billing;

UPDATE billing.SERVICES SET PRICE_RS_TAX=22500, desktop_RS=22500, iOS_app_RS=21500, mobile_website_RS=22500, JSAA_mobile_website_RS=22500, Android_app_RS=22500, old_mobile_website_RS=22500 WHERE SERVICEID='X3';

UPDATE billing.SERVICES SET PRICE_RS_TAX=38500, desktop_RS=38500, iOS_app_RS=35900, mobile_website_RS=38500, JSAA_mobile_website_RS=38500, Android_app_RS=38500, old_mobile_website_RS=38500 WHERE SERVICEID='X6';

UPDATE billing.SERVICES SET PRICE_RS_TAX=55000, desktop_RS=55000, iOS_app_RS=49900, mobile_website_RS=55000, JSAA_mobile_website_RS=55000, Android_app_RS=55000, old_mobile_website_RS=55000 WHERE SERVICEID='X12';


UPDATE billing.SERVICES SET PRICE_DOL=459.99, desktop_DOL=459.99, iOS_app_DOL=459.99, mobile_website_DOL=459.99, JSAA_mobile_website_DOL=459.99, Android_app_DOL=459.99, old_mobile_website_DOL=459.99 WHERE SERVICEID='X3';

UPDATE billing.SERVICES SET PRICE_DOL=699.99, desktop_DOL=699.99, iOS_app_DOL=699.99, mobile_website_DOL=699.99, JSAA_mobile_website_DOL=699.99, Android_app_DOL=699.99, old_mobile_website_DOL=699.99 WHERE SERVICEID='X6';

UPDATE billing.SERVICES SET PRICE_DOL=999.99, desktop_DOL=999.99, iOS_app_DOL=999.99, mobile_website_DOL=999.99, JSAA_mobile_website_DOL=999.99, Android_app_DOL=999.99, old_mobile_website_DOL=999.99 WHERE SERVICEID='X12';


UPDATE billing.SERVICES SET PRICE_RS = ROUND(PRICE_RS_TAX/1.15,2);
