use feedback;
UPDATE  `REPORT_ABUSE_LOG` set REASON = replace(REASON,'\r\n','') WHERE 1;
UPDATE  `REPORT_ABUSE_LOG` set REASON = replace(REASON,'\n','') WHERE 1;
UPDATE  `REPORT_ABUSE_LOG` set REASON = replace(REASON,'\\n','') WHERE 1;
ALTER TABLE REPORT_ABUSE_LOG ADD OTHER_REASON VARCHAR( 100 );
UPDATE  `REPORT_ABUSE_LOG` SET OTHER_REASON =  '' WHERE 1;
UPDATE REPORT_ABUSE_LOG SET OTHER_REASON=REASON,REASON='other' WHERE TRIM(REASON) NOT IN ('Profile is obscene/fraud','Incorrect profile details','Duplicate profile','Fake photo','Member sends unsolicited and illicit emails/ads','Member is already married/engaged','Looks like fake profile','Inappropriate content','Spam','Looks like a fake profile');

