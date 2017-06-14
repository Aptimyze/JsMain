/*on matchalert server only*/
use matchalerts;
DROP TABLE matchalerts.JPROFILE;
CREATE TABLE matchalerts.JPROFILE LIKE newjs.JPROFILE;

DROP TABLE matchalerts.HEIGHT;
CREATE TABLE matchalerts.HEIGHT LIKE newjs.HEIGHT;
