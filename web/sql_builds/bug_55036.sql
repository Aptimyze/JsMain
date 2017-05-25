use openfire;
drop trigger xyz_delete;
delimiter |
create trigger xyz_delete BEFORE DELETE on ofPresence for each row begin insert into userplane.users values(OLD.username,now(),''); 
end |
delimiter ;
use bot_jeevansathi;
ALTER TABLE  `user_online` ADD  `lastTimeOnline` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

