use newjs;
UPDATE `SMS_CONTACTS` SET `ADDRESS` = "B-8, Sector 132, Noida" WHERE `ID` = '16';
UPDATE `SMS_TYPE` SET `MESSAGE` = '{PHOTO_REQUEST_COUNT} Jeevansathi members want to see your son/daughters photo. Courier to {SALES_ADDRESS_NOIDA}. Write your phone number or profile ID on the photo.' WHERE `ID` = '52';
UPDATE `SMS_TYPE` SET `MESSAGE` = 'Yr profile {USERNAME} is being ignored by Jeevansathi members as it doesnt have a photo. Courier it to {SALES_ADDRESS_NOIDA}. Write your phone-No on photo' WHERE `ID` = '65';
UPDATE `SMS_TYPE` SET `MESSAGE` = '{PHOTO_REQUEST_COUNT} Jeevansathi member wants to see your son/daughters photo. Courier to {SALES_ADDRESS_NOIDA}. Write your phone number or profile ID on the photo.' WHERE `ID` = '71';
UPDATE  `CONTACT_MAILERS` SET  `LOCALITY` =  'B - 8, Sector - 132' WHERE CITY = 'UP25' LIMIT 1 ;
