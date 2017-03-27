USE billing;

UPDATE billing.SERVICES SET PRICE_RS_TAX=2925, desktop_RS=2925, iOS_app_RS=2925, mobile_website_RS=2925, JSAA_mobile_website_RS=2925, Android_app_RS=2925, old_mobile_website_RS=2925 WHERE SERVICEID='P2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=3375, desktop_RS=3375, iOS_app_RS=3375, mobile_website_RS=3375, JSAA_mobile_website_RS=3375, Android_app_RS=3375, old_mobile_website_RS=3375 WHERE SERVICEID='C2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=4050, desktop_RS=4050, iOS_app_RS=4050, mobile_website_RS=4050, JSAA_mobile_website_RS=4050, Android_app_RS=4050, old_mobile_website_RS=4050 WHERE SERVICEID='NCP2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=14625, desktop_RS=14625, iOS_app_RS=14625, mobile_website_RS=14625, JSAA_mobile_website_RS=14625, Android_app_RS=14625, old_mobile_website_RS=14625 WHERE SERVICEID='X2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=825, desktop_RS=825, iOS_app_RS=825, mobile_website_RS=825, JSAA_mobile_website_RS=825, Android_app_RS=825, old_mobile_website_RS=825 WHERE SERVICEID='R2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=1050, desktop_RS=1050, iOS_app_RS=1050, mobile_website_RS=1050, JSAA_mobile_website_RS=1050, Android_app_RS=1050, old_mobile_website_RS=1050 WHERE SERVICEID='T2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=315, desktop_RS=315, iOS_app_RS=315, mobile_website_RS=315, JSAA_mobile_website_RS=315, Android_app_RS=315, old_mobile_website_RS=315 WHERE SERVICEID='B2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=415, desktop_RS=415, iOS_app_RS=415, mobile_website_RS=415, JSAA_mobile_website_RS=415, Android_app_RS=415, old_mobile_website_RS=415 WHERE SERVICEID='A2';

UPDATE billing.SERVICES SET PRICE_RS_TAX=1350, desktop_RS=1350, iOS_app_RS=1350, mobile_website_RS=1350, JSAA_mobile_website_RS=1350, Android_app_RS=1350, old_mobile_website_RS=1350 WHERE SERVICEID='I20';

UPDATE billing.SERVICES SET PRICE_DOL=59.99, desktop_DOL=59.99, iOS_app_DOL=59.99, mobile_website_DOL=59.99, JSAA_mobile_website_DOL=59.99, Android_app_DOL=59.99, old_mobile_website_DOL=59.99 WHERE SERVICEID='P2';

UPDATE billing.SERVICES SET PRICE_DOL=71.99, desktop_DOL=71.99, iOS_app_DOL=71.99, mobile_website_DOL=71.99, JSAA_mobile_website_DOL=71.99, Android_app_DOL=71.99, old_mobile_website_DOL=71.99 WHERE SERVICEID='C2';

UPDATE billing.SERVICES SET PRICE_DOL=89.99, desktop_DOL=89.99, iOS_app_DOL=89.99, mobile_website_DOL=89.99, JSAA_mobile_website_DOL=89.99, Android_app_DOL=89.99, old_mobile_website_DOL=89.99 WHERE SERVICEID='NCP2';

UPDATE billing.SERVICES SET PRICE_DOL=299.99, desktop_DOL=299.99, iOS_app_DOL=299.99, mobile_website_DOL=299.99, JSAA_mobile_website_DOL=299.99, Android_app_DOL=299.99, old_mobile_website_DOL=299.99 WHERE SERVICEID='X2';

UPDATE billing.SERVICES SET PRICE_DOL=22.99, desktop_DOL=22.99, iOS_app_DOL=22.99, mobile_website_DOL=22.99, JSAA_mobile_website_DOL=22.99, Android_app_DOL=22.99, old_mobile_website_DOL=22.99 WHERE SERVICEID='R2';

UPDATE billing.SERVICES SET PRICE_DOL=29.99, desktop_DOL=29.99, iOS_app_DOL=29.99, mobile_website_DOL=29.99, JSAA_mobile_website_DOL=29.99, Android_app_DOL=29.99, old_mobile_website_DOL=29.99 WHERE SERVICEID='T2';

UPDATE billing.SERVICES SET PRICE_DOL=8.99, desktop_DOL=8.99, iOS_app_DOL=8.99, mobile_website_DOL=8.99, JSAA_mobile_website_DOL=8.99, Android_app_DOL=8.99, old_mobile_website_DOL=8.99 WHERE SERVICEID='B2';

UPDATE billing.SERVICES SET PRICE_DOL=11.99, desktop_DOL=11.99, iOS_app_DOL=11.99, mobile_website_DOL=11.99, JSAA_mobile_website_DOL=11.99, Android_app_DOL=11.99, old_mobile_website_DOL=11.99 WHERE SERVICEID='A2';

UPDATE billing.SERVICES SET PRICE_DOL=37.99, desktop_DOL=37.99, iOS_app_DOL=37.99, mobile_website_DOL=37.99, JSAA_mobile_website_DOL=37.99, Android_app_DOL=37.99, old_mobile_website_DOL=37.99 WHERE SERVICEID='I20';

UPDATE billing.SERVICES SET PRICE_RS = ROUND(PRICE_RS_TAX/1.15,2);