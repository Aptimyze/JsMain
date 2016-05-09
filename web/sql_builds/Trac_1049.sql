use incentive;

UPDATE incentive.NEGATIVE_PROFILE_LIST SET TYPE='Block Inbound Call' WHERE TYPE='Abusive Caller';
UPDATE incentive.NEGATIVE_PROFILE_LIST SET TYPE='Abusive chat with other members' WHERE TYPE='Abusive Chat';
UPDATE incentive.NEGATIVE_PROFILE_LIST SET TYPE='Others' WHERE TYPE='';
ALTER TABLE incentive.NEGATIVE_PROFILE_LIST DROP COLUMN EMAIL_FORMAT;


