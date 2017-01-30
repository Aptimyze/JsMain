use newjs

alter table `NEW_DELETED_PROFILE_LOG` drop primary key, add primary key(`PROFILEID`,`DATE`);

alter table `RETRIEVE_PROFILE_LOG` drop primary key, add primary key(`PROFILEID`,`DATE`);
