<?php

/* 
 * this class gets count of eois for a receiver
 */

class receiverEoiCount
{
	public function __construct()
	{
	}
        
        /*
         * this function gets profileids with count reaching a number
         * @param - $count that is upper limit
         * @return - profileids
         */
        public function getReceiversWithLimit($count){
            $receiverEoiCountObj = new ASSISTED_PRODUCT_EOI_RECEIVED_COUNT();
            $eoiCount = $receiverEoiCountObj->getReceiversWithLimit($count);
            return $eoiCount;
        }
        
        /*
         * this function inserts or updates entries in table
         * @param - receiver's profileid 
         */
        public function insertOrUpdateEntryForReceiver($receiverId){
            $receiverEoiCountObj = new ASSISTED_PRODUCT_EOI_RECEIVED_COUNT();
            $eoiCount = $receiverEoiCountObj->insertOrUpdateEntryForReceiver($receiverId);
        }
        
        /*
         * this function empties the table
         */
        public function emptyTable(){
            $receiverEoiCountObj = new ASSISTED_PRODUCT_EOI_RECEIVED_COUNT('newjs_master');
            $eoiCount = $receiverEoiCountObj->emptyTable();
        }
        
}
