/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Tushar Gandhi
 * Created: 11 Dec, 2017
 */


USE incentive;

CREATE TABLE  `EXCLUSIVE_CLIENT_NOTES` (
 `CLIENT_ID` INT( 11 ) NOT NULL ,
 `CLIENT_NOTES` TEXT DEFAULT  '',
PRIMARY KEY (  `CLIENT_ID` )
) ENGINE = MYISAM