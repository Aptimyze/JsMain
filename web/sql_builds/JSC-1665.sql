use MOBILE_API;
//To be executed on master
Delete * from MOBILE_API.APP_NOTIFICATIONS where STATUS='N';
//For stoping app notifications scheduling
UPDATE MOBILE_API.APP_NOTIFICATIONS SET STATUS='N';
//For starting app notifications scheduling
UPDATE MOBILE_API.APP_NOTIFICATIONS SET STATUS='Y';
