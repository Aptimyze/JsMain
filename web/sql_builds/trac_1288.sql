use billing;

update billing.SERVICES set SHOW_ONLINE='Y' where SERVICEID in('P4','B4','D4','C4','A4','T4','L4','M4');
