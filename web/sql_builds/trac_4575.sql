
USE newjs;

UPDATE newjs.SMS_TYPE SET MESSAGE = "Membership of Jeevansathi profile {USERNAME} expires on {EXPDATE}. Renew to save all your contacted members details. Call {TOLLNO}. Please ignore if already paid" WHERE SMS_KEY = 'MEM_EXPIRE_A15'; 
UPDATE newjs.SMS_TYPE SET MESSAGE = "Membership of Jeevansathi profile {USERNAME} expires on {EXPDATE}. Renew to save all your contacted members details. Call {TOLLNO}. Please ignore if already paid" WHERE SMS_KEY = 'MEM_EXPIRE_A10'; 
UPDATE newjs.SMS_TYPE SET MESSAGE = "Membership of Jeevansathi profile {USERNAME} expires on {EXPDATE}. Renew to save all your contacted members details. Call {TOLLNO}. Please ignore if already paid" WHERE SMS_KEY = 'MEM_EXPIRE_A5'; 

UPDATE newjs.SMS_TYPE SET MESSAGE = "Membership of Jeevansathi profile {USERNAME} expired on {EXPDATE}. Renew to save all your contacted members details! Call {TOLLNO}. Please ignore if already paid" WHERE SMS_KEY = 'MEM_EXPIRE_B1';
UPDATE newjs.SMS_TYPE SET MESSAGE = "Membership of Jeevansathi profile {USERNAME} expired on {EXPDATE}. Renew to save all your contacted members details! Call {TOLLNO}. Please ignore if already paid" WHERE SMS_KEY = 'MEM_EXPIRE_B5';