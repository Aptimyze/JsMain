use newjs;

update newjs.SMS_TYPE SET MESSAGE='Membership of Jeevansathi profile {USERNAME} expired on {EXPDATE}. Call {TOLLNO}. Please ignore if already paid' WHERE SMS_KEY IN('MEM_EXPIRE_B1','MEM_EXPIRE_B5');
