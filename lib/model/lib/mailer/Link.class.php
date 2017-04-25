<?php
include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
class LinkClass {
	private $_link_id;
	private $_link_name;
	private $_link_auto_login;
	private $_link_address;
	private $_outer_link;
	protected $_var_object;
	private $_other_get_params;

	 function __construct($id)
	{	
		$result_array = MailerArray::getLink($id);
		$this->_link_id=$id;
    if (gettype($result_array) === 'array') {
      $this->_link_name=$result_array["LINK_NAME"];
      $this->_link_auto_login=$result_array["REQAUTOLOGIN"];
      $this->_link_address=$result_array["URL"];
      $this->_other_get_params=$result_array["OTHER_GET_PARAMS"];
      $this->_outer_link=$result_array["OUTER_LINK"];
      $this->_app_screen_id = $result_array["APP_SCREEN_ID"];
    }
	}
	function getLinkId()
	{
		return $this->_link_id;
	}
	function getLinkName()
	{
		return $this->_link_name;
	}
	function getRequireAutologin()
	{
		return $this->_link_auto_login;
	}
	function getLinkAddress()
	{
		return $this->_link_address;
	}
  
  private function _setLinkName($link_name) {
    if (isset($link_name)) {
      $this->_link_name = $link_name;
    }
    else {
      throw new NullPointerException('Link Name not defined');
    }
  }

  private function _setLinkId($link_id) {
    if (isset($link_id)) {
      $this->_link_id = $link_id;
    }
    else {
      throw new NullPointerException('Link Id not defined');
    }
  }

  private function _setRequireAutoLogin($auto_login) {
    if (isset($auto_login)) {
      $this->_link_auto_login = $auto_login;
    }
    else {
      throw new NullPointerException('Auto Login not defined');
    }
  }

  private function _setLinkAddress($link_address) {
    if (isset($link_address)) {
      $this->_link_address = $link_address;
    }
    else {
      throw new NullPointerException('Link Address not defined');
    }
  }
	
  function getLinkUrl($noMailGroup="")
  {
	if($noMailGroup=="1")
		$mail_group=0; 
	else
		$mail_group=$this->_var_object->getParam('mail_group');
	global $do_not_send;
	if($this->_outer_link=='Y')
		$url=$this->_link_address;
	else{

		$app_screen_id="";
		if($this->_app_screen_id !="")
			$app_screen_id=$this->_app_screen_id."/"; 
		
  		if($this->_link_auto_login && $noMailGroup!="1"){ 
			$sender_id=$this->_var_object->getParam('profileid');
			if(!$sender_id){
				$do_not_send=true;
				return;
			}
			
			$EmailUID=$this->_var_object->getParam('EmailUID');
			$checksum=md5($sender_id)."i".$sender_id;
			$protect_obj=new protect;
			$echecksum=$protect_obj->js_encrypt($checksum);
			//Link url format is /e/<link_id>/<echecksum>/<checksum>?<var1>=<val1>...
			$url=JsConstants::$siteUrl.'/e/'.$app_screen_id.$this->_link_id.'/'.$mail_group.'/'.$echecksum.'/'.$checksum;
  	 	}
  		else
	  		$url=JsConstants::$siteUrl.'/e/'.$app_screen_id.$this->_link_id.'/'.$mail_group;
	  	if($EmailUID)
			$url=$url."?EmailUID=".$EmailUID;
	}
	html_entity_decode($url);
  	return $url;
  }

	function trackLink()
	{
	}
	public function setVariable($var){
		$this->_var_object=$var;
	}
	//Forward the user to destination after doing its stuff
	public function forward($request){
		$stype=$this->getStypeFromMailGroup($request->getParameter('mail_group'));
		$uri=$_SERVER['REQUEST_URI'];
		$params=explode('?',$uri);
		$param_str=urldecode($params[1]);
		$url=$this->_link_address;
		if($this->_link_id==1){
			$profilechecksum=$request->getParameter('profilechecksum');
			$arr=explode("i",$profilechecksum);
			if(md5($arr[1])!=$arr[0])
			{
			}
			else
				$profileid=$arr[1];
			$profile=new Profile('',$profileid);
			$profile->getDetail('','','PROFILEID,HAVEPHOTO,PHOTO_DISPLAY');
			if($profile->getHAVEPHOTO()!='Y'||$profile->getPHOTO_DISPLAY()=='C')  
				$url='/profile/viewprofile.php';
		}
		$checksum=$request->getParameter('checksum');
		$echecksum=$request->getParameter('echecksum');
		
		

		if($param_str){
			$url.="?$param_str";
		}
		if($this->_other_get_params){
			$append=strpos($url,'?')?"&":"?";
			$url.=$append.$this->_other_get_params;
		}

		if($this->_link_auto_login=='Y'){
			$append=strpos($url,'?')?"&":"?";
			$stypeNew =$request->getParameter('stype');
			if($stypeNew)
                                $url.=$append."checksum=$checksum";
                        else
				$url.=$append."checksum=$checksum&stype=$stype";
			$_SERVER['REQUEST_URI']=$url;
			if($checksum){
				$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj(null);
				if($authenticationLoginObj->decrypt($echecksum,"Y")==$checksum)
				{
					$authenticationLoginObj->setAutologinAuthchecksum($checksum,$url);
				}
				else
				{
					$authenticationLoginObj->removeCookies();
				}
			}
		}
	    
	        $site_url=sfConfig::get('app_site_url');
                $loc=$site_url."/".$url;
                $append=strpos($loc,'?')?"&":"?";
                $loc.=$append.'from_mailer=1';

		
		
	//	echo "<script>document.location='$loc';</script>";
		header("Location:$loc");
		die;
	}
  private function _setLinkDetails($link) {
    $result_array = MailerArray::getLink($link);
    if (gettype($result_array) === 'array') {
      $this->_setLinkId($result_array["ID"]);
      $this->_setRequireAutoLogin($result_array["REQAUTOLOGIN"]);
      $this->_setLinkAddress($result_array["URL"]);
    }
    else {
      throw new RecordsNotFetchedException('No records were fetched for the link name ' + $link);
    }
  }

  public function getLinkValue($link_short_hand, $id) {
    $link_short_hand = strtoupper($link_short_hand);
    
    //Set link short hand;
    $this->_setLinkName($link_short_hand);

    $this->_setLinkDetails($this->getLinkName());
    return /*/array($this->getLinkId(), $this->getLinkUrl(), $this->getRequireAutoLogin())/*/$this->getLink($id)/**/;
  }
  //Stype is used from a naming convention M<Mail Group>
  public function getStypeFromMailGroup($mail_group){
		  return 'M'.$mail_group;
  }
}
