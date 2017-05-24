use incentive;

INSERT INTO incentive.LOCATION(VALUE,NAME,STATE,REGION) VALUES('CH03','BILASPUR','CH','E');
INSERT INTO incentive.LOCATION(VALUE,NAME,STATE,REGION) VALUES('MH03','KOLHAPUR','MH','S');
INSERT INTO incentive.LOCATION(VALUE,NAME,STATE,REGION) VALUES('GO00','GOA','MH','S');

INSERT INTO incentive.SUB_LOCATION(LABEL,VALUE,PRIORITY) VALUES('Bilaspur','CH03','CH03');
INSERT INTO incentive.SUB_LOCATION(LABEL,VALUE,PRIORITY) VALUES('Kolhapur','MH03','MH03');
INSERT INTO incentive.SUB_LOCATION(LABEL,VALUE,PRIORITY) VALUES('Goa','GO00','GO00');

INSERT incentive.LOCATION_CITY(NAME,VALUE,STATE) VALUES('Goa','GO00','MH');

use billing;
INSERT INTO billing.BRANCHES(NAME) VALUES('KOLHAPUR');


