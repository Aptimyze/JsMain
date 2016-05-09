use bot_jeevansathi;
CREATE TABLE `removeusers` (
  `jid` varchar(1024) NOT NULL,
  `username` varchar(64) NOT NULL
);
insert into removeusers select jid, username from openfire.ofRoster where username='sathi';
