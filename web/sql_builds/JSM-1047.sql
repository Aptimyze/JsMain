use reg;
INSERT INTO `PROFILE_FIELDS` VALUES (121, 'COLLEGE', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (122, 'DEGREE_PG', 'dropdown', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (123, 'DEGREE_UG', 'dropdown', 'dropdown_not_req', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (124, 'EDUCATION', 'textarea', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (125, 'PG_COLLEGE', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (127, 'OTHER_PG_DEGREE', 'text', 'string', '', NULL, '');
INSERT INTO `PROFILE_FIELDS` VALUES (128, 'OTHER_UG_DEGREE', 'text', 'string', '', NULL, '');

INSERT INTO  `REG_EDIT_PAGE_FIELDS` (  `PAGE` ,  `FIELD_ID` ,  `GROUP` ,  `TABLE_NAME` ,  `LABEL` ,  `BLANK_VALUE` ,  `BLANK_LABEL` ) 
VALUES (
'DP2',  '121',  '',  'JPROFILE_EDUCATION:COLLEGE', 'Graduation College :' ,  '',  'Please Select'
), (
'DP2',  '122',  '',  'JPROFILE_EDUCATION:PG_DEGREE', 'PG Degree :' ,  '',  'Please Select'
);

INSERT INTO  `REG_EDIT_PAGE_FIELDS` (  `PAGE` ,  `FIELD_ID` ,  `GROUP` ,  `TABLE_NAME` ,  `LABEL` ,  `BLANK_VALUE` ,  `BLANK_LABEL` ) 
VALUES (
'DP2',  '123',  '',  'JPROFILE_EDUCATION:UG_DEGREE', 'Graduation Degree :' ,  '',  'Please Select'
), (
'DP2',  '124',  '',  'JPROFILE:EDUCATION', '' ,  '',  ''
);

INSERT INTO  `REG_EDIT_PAGE_FIELDS` (  `PAGE` ,  `FIELD_ID` ,  `GROUP` ,  `TABLE_NAME` ,  `LABEL` ,  `BLANK_VALUE` ,  `BLANK_LABEL` ) 
VALUES (
'DP2',  '125',  '',  'JPROFILE_EDUCATION:PG_COLLEGE', 'PG College :' ,  '',  'Please Select'
), (
'DP2',  '127',  '',  'JPROFILE_EDUCATION:OTHER_PG_DEGREE', 'Other PG Degree :' ,  '',  'Please Select'
),
(
'DP2',  '128',  '',  'JPROFILE_EDUCATION:OTHER_UG_DEGREE','Other Graduation Degree :' ,  '',  'Please Select'
);

