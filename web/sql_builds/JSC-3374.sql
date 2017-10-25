/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  tushar
 * Created: 10 Oct, 2017
 */

use jeevansathi_mailer;

UPDATE  `MAILER_SUBJECT` SET  `SUBJECT_CODE` =  'Feedback request from Jeevansathi.com',
`DESCRIPTION` =  'Jeevansathi Sales Service Feedback' WHERE  `MAIL_ID` =  '1806' AND CONVERT(  `SUBJECT_TYPE` USING utf8 ) =  'D' AND CONVERT(  `SUBJECT_CODE` USING utf8 ) = '"Feedback request from Jeevansathi.com"' AND CONVERT(  `DESCRIPTION` USING utf8 ) =  '"Jeevansathi Sales Service Feedback"' LIMIT 1 ;

UPDATE  `EMAIL_TYPE` SET  
`DESCRIPTION` =  'Jeevansathi Sales Service Feedback',
`FROM_NAME` =  'Jeevansathi Feedback' 
WHERE  `ID` =  '1806' LIMIT 1 ;