use billing;
update billing.SERVICES SET ACTIVE='N',SHOW_ONLINE='N' where SERVICEID LIKE 'L%';
DELETE from billing.ADDON_RANK WHERE VAS_ID='L';

