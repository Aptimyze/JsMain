/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  tushar
 * Created: 27 Oct, 2017
 */

use billing;
ALTER TABLE  `EXCLUSIVE_MAIL_LOG` ADD  `PENDING_INTEREST_MAIL_STATUS` VARCHAR( 1 ) DEFAULT  'N' NOT NULL ;

