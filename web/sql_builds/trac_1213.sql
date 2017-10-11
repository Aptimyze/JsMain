use incentive;

ALTER TABLE incentive.LOCATION ADD SPECIAL_CITY char(1) NOT NULL;


UPDATE incentive.LOCATION SET SPECIAL_CITY='Y' WHERE NAME IN('Patna','Meerut','Varanasi','Nashik','Faridabad','Jabalpur','Allahabad','Amritsar','Ghaziabad','Srinagar ','Aurangabad','Sholapur','Ranchi','Jodhpur','Gwalior','Guwahati','Jalandhar','Bareilly','Kota','Aligarh','Moradabad','Gorakhpur','Raipur','Jamshedpur','Bhilai Nagar','Amravati','Cuttack','Bikaner','Bhavnagar','Durgapur','Asansol','Ajmer','Kolhapur','Siliguri','Saharanpur','Jamnagar','Rourkela','Nanded','Bokaro','Ujjain','Jhansi','Malegaon','Jammu','Pathankot','Yamunanagar','Bilaspur','Dehradun','Agra','Nashik','Aurangabad','Rajkot','Baroda','Surat','Bhubhaneshwar','Jamshedpur','Ambala','Sangli','Jaipur','Bhopal');
