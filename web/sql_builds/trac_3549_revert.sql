use billing;
update billing.SERVICES SET ACTIVE='Y' where SERVICEID LIKE 'L%';
update billing.SERVICES SET SHOW_ONLINE='Y' where SERVICEID in('L3','L6','L9','L12');

