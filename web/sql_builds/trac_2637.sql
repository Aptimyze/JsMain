use billing;

update billing.SERVICES s,billing.COMPONENTS c SET s.DURATION=c.DURATION where s.SERVICEID=c.COMPID and s.ADDON='Y';

update billing.SERVICES set DURATION='1',NAME='We Talk For You - 10 Profiles' where SERVICEID='I10';
update billing.SERVICES set DURATION='2',NAME='We Talk For You - 20 Profiles' where SERVICEID='I20';
update billing.SERVICES set DURATION='3',NAME='We Talk For You - 30 Profiles' where SERVICEID='I30';
update billing.SERVICES set DURATION='4',NAME='We Talk For You - 40 Profiles' where SERVICEID='I40';
update billing.SERVICES set DURATION='5',NAME='We Talk For You - 50 Profiles' where SERVICEID='I50';
update billing.SERVICES set DURATION='6',NAME='We Talk For You - 60 Profiles' where SERVICEID='I60';
update billing.SERVICES set DURATION='7',NAME='We Talk For You - 70 Profiles' where SERVICEID='I70';
update billing.SERVICES set DURATION='8',NAME='We Talk For You - 80 Profiles' where SERVICEID='I80';
update billing.SERVICES set DURATION='9',NAME='We Talk For You - 90 Profiles' where SERVICEID='I90';
update billing.SERVICES set DURATION='10',NAME='We Talk For You - 100 Profiles' where SERVICEID='I100';
update billing.SERVICES set DURATION='11',NAME='We Talk For You - 110 Profiles' where SERVICEID='I110';
update billing.SERVICES set DURATION='12',NAME='We Talk For You - 120 Profiles' where SERVICEID='I120';
update billing.SERVICES set DURATION='0',NAME='We Talk For You - 5 Profiles' where SERVICEID='I1W';
update billing.SERVICES set DURATION='0',NAME='We Talk For You - 2 Profiles' where SERVICEID='I2W';
 
