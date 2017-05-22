/*Need To Run On Matchalerts Servers Only*/
use matchalerts;
DROP TABLE matchalerts.JPROFILE;
CREATE TABLE matchalerts.JPROFILE LIKE newjs.JPROFILE;
