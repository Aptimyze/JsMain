use newjs;
UPDATE  `SMS_TYPE` SET  `STATUS` =  'N' WHERE  SMS_KEY='PHOTO_REQUEST';
INSERT INTO `SMS_TYPE` VALUES (212, 'PHOTO_REQUEST', 'D', 50, 'A', 'A', 'MUL', 1, 0, 'SERVICE', 'Y', 'Hi, {OTHER_USERNAME} and {PHOTO_REQUEST_COUNT} others have requested you to add your photo to your profile. Upload here {PHOTO_UPLOAD_URL} or email photos to photos@js1.in');
INSERT INTO `SMS_TYPE` VALUES (213, 'PHOTO_REQUEST', 'D', 50, 'A', 'A', 'SINGLE', 1, 0, 'SERVICE', 'Y', 'Hi, {OTHER_USERNAME} has requested you to add your photo to your profile. Upload here {PHOTO_UPLOAD_URL} or email photos to photos@js1.in');
