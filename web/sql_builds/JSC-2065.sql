use MOBILE_API;

INSERT INTO MOBILE_API.`APP_NOTIFICATIONS` VALUES (43, 'LOGIN_REGISTER', 'Join Jeevansathi now and upload a photo to start receiving interests instantly. If you are already a member, sign in!', 1, 'AND', 'Y', 'D', 0, 0, 'SINGLE', 'Y', 86400, 'A', 'A', 'Login or Register on Jeevansathi', 'D');

ALTER TABLE  MOBILE_API.`SCHEDULED_APP_NOTIFICATIONS` ADD  `REG_ID` VARCHAR( 255 );