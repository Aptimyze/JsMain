<?php 
/* 
This class will be used for authentication of  mmmjs
@author : lavesh/vipin
*/ 
class MmmAuthentication
{
	private $_timedOut   = 1800; //30 min is time out time.
	private $_authCookie = 'mmmUser';
	private $_seperator  = "|i|";
	//private $_key = 'unusual suspect';

	/*
	* This function will encrypt the id.
	* @param val 
	* @return ecyrpted checksum
	*/
	private function encrypt($val)
	{
		return md5($val).$this->_seperator.$val;
	}

	/*
	* This function will get the original id.
	* @param val 
	* @return original id.
	*/
        private function decrypt($val)
        {     
                $arrTmp = explode($this->_seperator, $val);
		$id = $arrTmp[1];
	
		if(md5($id) == $arrTmp[0])
			return  $id;
		return NULL;
        }

	/* 
	* This function will login based on username and password.
	* @user_info @param array containing username and passowrd.
	*/
	public function login($user_info)
	{	
		$pswrd = new mmmjs_PSWRDS;
		$connect =  new mmmjs_CONNECT;
		$res = $pswrd->entryExist($user_info);
		if( $res == NULL)
			return $res;
		else
                {
			$id = $connect->insertEntry($res);
		        $cid = $this->encrypt($id);
			$expire = time()+$this->_timedOut;
			setcookie($this->_authCookie,$cid,$expire,"/");
			return true;
     		}
   	}
      
        /*
	* This function will authenticate user
	* @return true on successfull login
	*/
        public function authenticate()
        {       
		$cid = $_COOKIE[$this->_authCookie];
		if(!$cid)
			return false;
		$connect =  new mmmjs_CONNECT;
		$expire = time()+$this->_timedOut;
		setcookie($this->_authCookie,$cid,$expire,"/");
		return $connect->checkEntry($this->decrypt($cid));
        }
 }
?>
