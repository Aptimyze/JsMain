USE billing;

UPDATE billing.SERVICES SET NAME=REPLACE(NAME, 'eValue+', 'eAdvantage');

UPDATE billing.COMPONENTS SET NAME=REPLACE(NAME, 'eValue+', 'eAdvantage'); 

UPDATE billing.MEMBERSHIPS SET SERVICE_NAME='eAdvantage';