use feedback;
UPDATE  `REPORT_ABUSE_LOG` set REASON = replace(REASON,'\r\n','') WHERE 1;
UPDATE  `REPORT_ABUSE_LOG` set REASON = replace(REASON,'\n','') WHERE 1;
ALTER TABLE REPORT_ABUSE_LOG ADD OTHER_REASON VARCHAR( 100 );
UPDATE  `REPORT_ABUSE_LOG` SET OTHER_REASON =  '' WHERE 1;
UPDATE feedback.REPORT_ABUSE_LOG SET OTHER_REASON=REASON WHERE TRIM(REASON),REASON='other' NOT IN ('Looks like fake profile','Inappropriate content','Spam','Duplicate profile','Already married/engaged','Incorrect details/photo','Looks like a fake profile');

