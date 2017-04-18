/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  palash
 * Created: 12 Apr, 2017
 */

use feedback;
ALTER TABLE  `REPORT_ABUSE_LOG` CHANGE  `OTHER_REASON`  `OTHER_REASON` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;