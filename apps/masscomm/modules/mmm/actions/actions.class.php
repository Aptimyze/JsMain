<?php
/**
* The class contains all the actions required by the MMM.
*/ 
class mmmActions extends sfActions
{
	/**
	 * To execute the home page of MMM.
	 */ 
	public function executeHome(sfWebRequest  $request)
	{
		//homeSuccess.tpl
	}

	/**
	* This function will perform login operation.
	*/	
	public function executeLogin(sfWebrequest $request)
	{  
		$auth = new MmmAuthentication;
		$data=$request->getParameterHolder()->getAll();
		$info['username'] = $data['username'];
		$info['password'] = $data['password'];
                $cid= $auth->login($info);
		$this->cid=$cid;
	}

	/*
	* This function will return the menu to be displayed on left side of the mmm interface.
	*/	
	public function executeMenu(sfWebrequest $request)
	{	 
		$menu = MmmUtility::getLeftPanelMenu();
		foreach($menu as $name=>$action)
		{
			$leftMenu[]=array("name" => $name,
			   	      "url" => $action,
			);
		}
		$this->leftMenu=$leftMenu;
	}
	/**
	 * The first welcome page after loging in.
	 */ 
	public function executeWelcome(sfWebrequest $request)
	{
		//calls WelcomeSuccess.tpl
	}


	/**
	* This function will .....
	* 1. Display the inteface of create mailer.
	* 2. Perform action related to create mailer.
	*/
	public function executeCreateMailer(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();		

		if(array_key_exists('submit',$data) && $data["submit"])
		{
			$this->errors = MmmFormValidation::validateCreateMailerForm($data);
			if($this->errors)
			{
				$this->edit = $data;
				$this->setTemplate("createMailer");
			}
			else
			{
				$this->successMsg=1;
				$MmmMailerBasicInfo = new MmmMailerBasicInfo;
				$id = $MmmMailerBasicInfo->createNewMailer($data);
				$this->mail = $MmmMailerBasicInfo->retreiveMailerInfo($id);
			}
		}
	}

	/**
	* This loads the form containing the necessary fields required for generating the query for retrieving profiles that fulfill the criteria filled in the form .....
	* This link will display form for  
	* A) New 
	* B) Edit case
	*/
	public function executeFormQueryJs(sfWebrequest $request)
	{	
		$mailer = new MmmMailerBasicInfo;
		$this->mailers= $mailer->retrieveAllMailers('J');

		$this->typeOfMail = MmmConfig::$typeOfMail;
		$this->yesNo = MmmConfig::$yesNo;
		$this->paid = MmmConfig::$paid;

		/* can be called direcly in html*/
		$this->gender = FieldMap::getFieldLabel("gender",1,1);
        	$this->caste = FieldMap::getFieldLabel("caste",1,1);
		$this->manglik = FieldMap::getFieldLabel("manglik",1,1);
		$this->children = FieldMap::getFieldLabel("children",1,1);
		$this->mtongue = FieldMap::getFieldLabel("community_small",1,1);
	        $this->marital = FieldMap::getFieldLabel("marital_status",1,1);
		$this->education = FieldMap::getFieldLabel("education_label",1,1);
		$this->relation = FieldMap::getFieldLabel("relation",1,1);
		$this->occupation = FieldMap::getFieldLabel("occupation",1,1);
		$this->country = FieldMap::getFieldLabel("country",1,1);
		$city = FieldMap::getFieldLabel("city_india",1,1);
		foreach($city as $k=>$v)
		{
			if(is_numeric($k))		
				;
			elseif(ctype_alpha($k))
				$state[$k] = $v;
			else
				$cityy[$k] = $v;
		}
		unset($city);
		asort($cityy);
		asort($state);
		foreach($state as $k=>$v)
		{
			$city[$k] = $v;
			foreach($cityy as $kk=>$vv)
			{
				if(substr($kk,0,2) == $k)	
					$city[$kk] = "--".$vv;
			}	
		}
		$this->city = $city;

		$this->lincome = FieldMap::getFieldLabel("lincome",1,1);
		$this->hincome = FieldMap::getFieldLabel("hincome",1,1);
		$this->lincome_dol = FieldMap::getFieldLabel("lincome_dol",1,1);
		$this->hincome_dol = FieldMap::getFieldLabel("hincome_dol",1,1);

		$this->height1 = FieldMap::getFieldLabel("height_without_meters",1,1);
		foreach($this->height1 as $k=>$v)
			$height[$k] = html_entity_decode($v);
		$this->height = $height;

		$this->rstatus = FieldMap::getFieldLabel("rstatus",1,1);
		$this->photo_a = FieldMap::getFieldLabel("havephoto_array",1,1);
		$this->btype = FieldMap::getFieldLabel("bodytype",1,1);
		$this->complexion = FieldMap::getFieldLabel("complexion",1,1);
		$this->diet = FieldMap::getFieldLabel("diet",1,1);
		$this->drink = FieldMap::getFieldLabel("drink",1,1);
		$this->smoke = FieldMap::getFieldLabel("smoke",1,1);
		$this->handicapped = FieldMap::getFieldLabel("handicapped",1,1);
		$this->age = array();
 		for($i = 18; $i <= 70; $i++)
			$this->age[$i] = $i;

		$data = $request->getParameterHolder()->getAll();				
	
		/* Editing Search Query */	
		if(isset($data['id']))
		{
			$mailer_spec = new SearchQueryJs;
			$this->edit = $mailer_spec->showMailerSpecs($data['id']);
		}
		/* Editing Search Query */	
	}

	/**
	* Action of search query forms for all website.
	* This will log the search criteia in the table and show the count of results to users.
	*/
	public function executeFormQuerySubmit(sfWebrequest $request)
	{
		ini_set('max_execution_time',-1);
		$data = $request->getParameterHolder()->getAll();
		$this->site = $data["site"];
		$this->mailerId = $data["mailer_id"];
		if(!$this->site)
		{
			$MmmMailerBasicInfo = new MmmMailerBasicInfo;
			$this->site = $MmmMailerBasicInfo->getSiteEnumFromMailerId($this->mailerId);
		}
		
		if($this->site == '9') 	
		{
			$mmm_99acresUtility = new mmm_99acresUtility;
			if(isset($data['city_region'])){
				$mmm_99acresUtility->getChildCity($data,'city');
			}
			if(isset($data['seller_prop_city_region'])){
				$mmm_99acresUtility->getChildCity($data,'seller_prop_city');

			}
			if(isset($data['buyer_prop_city_region'])){
			
				$mmm_99acresUtility->getChildCity($data,'buyer_prop_city');
			}

			$this->register_city_radio = $data["register_city_radio"];
			$this->seller_city_radio = $data["seller_city_radio"];
			$this->buyer_city_radio = $data["buyer_city_radio"];
			$this->recipient_type = $data['recipient_type'];
		}
		else
		{
                        if(is_array($data["city_res"]) && ($data["city_res"][0]!='' || $data["city_res"][1]!=''))
                        {
                                foreach($data["city_res"] as $k=>$v)
                                {
                                        if(ctype_alpha($v))
                                                $tempStr.= $v.",";
                                        elseif(ctype_alnum($v))
                                                $cityy[] = $v;
                                }
                                $city = FieldMap::getFieldLabel("city_india",1,1);
                                foreach($city as $k=>$v)        
                                {
                                        if(strstr($tempStr,substr($k,0,2)))
                                        {
                                                $cityy[] = $k;
                                        }
                                }
                        }
			$data["city_res"] = $cityy;
		}
		$mailerspec = SearchQueryFactory::getObject($this->site,$data);
		$this->expectedProfilesCount =  $mailerspec->getExpectedMailsCount();
		$mailerspec->logSearchCriteria();
	}


	/**
	* This is called when we search query is finilized for the mailer.
	* This update the status of the mailer.
	*/	
	public function executeFormQuerySave(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();
		$newMailer = new MmmMailerBasicInfo;
		$newMailer->updateStatus($data['mailer_id'], MmmConfig::$status['FORM_QUERY']);
		$this->message="Your Query has been saved with mailer_id :".$data['mailer_id'].". Now you can write mail for the corresponding mailer.";
	}

	/**
	* To be implemneted by Neha
	*/
	public function executeFormQuery_99(sfWebrequest $request)
	{
		$obj = new createDropdown();
		$mailer = new MmmMailerBasicInfo;
                $this->mailers = $mailer->retrieveAllMailers('9');
		$this->site = '9';  // hard coded mailer for 99 so that we dont need to make a query.
                $cityDD['city'] = $obj->create_dd('','city99');
		$cityDD['buyer_prop_city'] = $cityDD['city'];
		$cityDD['seller_prop_city'] = $cityDD['city'];
                $propertyType['seller_property_type'] = $obj->create_dd('','99property_type');
		$propertyType['buyer_property_type'] = $obj->create_dd('','99property_type');
                $budgetDD['budget_min'] = $obj->create_dd('','buying_budget');
		$budgetDD['budget_max'] = $obj->create_dd('','buying_budget');
		$cityRegionDD['city_region'] = $obj->create_dd('','city_region');
		$cityRegionDD['seller_prop_city_region'] = $cityRegionDD['city_region'];
		$cityRegionDD['buyer_prop_city_region'] = $cityRegionDD['city_region'];
		if(isset($_POST['edit_query']))
		{	
			$id=$_POST['id'];
			$mailer_spec = new SearchQuery99();
                        $res = $mailer_spec->showMailerSpecs($id,'toLowerCase');
			$this->recipient_type = $res['recipient_type'];
			$this->mailer_id=$id;
			$res['city'] = explode(',',$res['city']);
			$res['seller_prop_city'] = explode(',',$res['seller_prop_city']);
			$res['buyer_prop_city'] = explode(',',$res['buyer_prop_city']);
			$res['seller_property_type'] = explode(',',$res['seller_property_type']);
			$res['buyer_property_type'] = explode(',',$res['buyer_property_type']);
			$res['city_region'] = explode(',',$res['city_region']);
			$res['seller_prop_city_region'] = explode(',',$res['seller_prop_city_region']);
			$res['buyer_prop_city_region'] = explode(',',$res['buyer_prop_city_region']);
			$city_arr = array('city','seller_prop_city','buyer_prop_city');
			$city_region_arr = array('city_region','seller_prop_city_region','buyer_prop_city_region');
			$budget_arr = array('budget_min','budget_max');
			foreach($city_region_arr as $regionType)
			{	
				foreach($cityRegionDD[$regionType] as $k=>&$v){
					if(is_array($res[$regionType]))
                                        {
                                                foreach($res[$regionType] as $i=>$regionvalue)
                                                {
                                                        if($v['value'] == $regionvalue)
                                                                $v['selected'] = 1;
                                                }

                                        }
                                        else
                                        {
                                                if($v['value'] == $res[$regionType]){
                                                        $v['selected'] = 1;
                                                        break;
                                                }
                                        }

				}
				if($regionType == 'city_region')
					$this->CITY_REGION = $cityRegionDD['city_region'];
				if($regionType == 'seller_prop_city_region')
                                        $this->SELLER_CITY_REGION = $cityRegionDD['seller_prop_city_region'];
				if($regionType == 'buyer_prop_city_region')
                                        $this->BUYER_CITY_REGION = $cityRegionDD['buyer_prop_city_region'];
	
	
			}
			foreach($budget_arr as $budgetType){
			foreach($budgetDD[$budgetType] as $k=>&$v){
				if($v['value'] == $res[$budgetType]){
					$v['selected'] = 1;
					if($budgetType=='budget_min')
						$this->BUYING_BUDGET_MIN = $budgetDD['budget_min'];
					if($budgetType == 'budget_max')
						$this->BUYING_BUDGET_MAX = $budgetDD['budget_max'];
					break;
				}
				else{
					$this->BUYING_BUDGET_MIN = $budgetDD['budget_min'];
					$this->BUYING_BUDGET_MAX = $budgetDD['budget_max'];
				}
			}
			}
			foreach($city_arr as $cityType){
				foreach($cityDD[$cityType] as $k=>&$v)
				{
					if(is_array($res[$cityType]))
					{
						foreach($res[$cityType] as $i=>$cityvalue)
						{
							if($v['value'] == $cityvalue)
								$v['selected'] = 1;
						}

					}	
					else 
					{
						if($v['value'] == $res[$cityType]){
							$v['selected'] = 1;
							break;
						}
					}	
				}
				if($cityType == 'city')	
					$this->CITY = $cityDD['city'];
				if($cityType=='seller_prop_city')
					$this->SELLER_CITY = $cityDD['seller_prop_city'];
				if($cityType=='buyer_prop_city')
					$this->BUYER_CITY = $cityDD['buyer_prop_city'];
			}
			$propertytype_arr = array('seller_property_type','buyer_property_type');
			foreach($propertytype_arr as $type){
				foreach($propertyType[$type] as $k=>&$v){
					if(is_array($res[$type]))
					{
						foreach($res[$type] as $i=>$prop_type)
						{
							if($v['value'] == $prop_type)
								$v['selected'] = 1;
						}

					}
					else
					{
						if($v['value'] == $res[$type])
						{
							$v['selected'] = 1;
							break;
						}
					}	
				}
				if($type == 'seller_property_type')
					$this->SELLER_PROPERTY_TYPE = $propertyType['seller_property_type'];
				if($type=='buyer_property_type')
					$this->PROPERTY_TYPE = $propertyType['buyer_property_type'];
			}
			$this->sub_partners = $res['sub_partners'];
			$this->sub_promo = $res['sub_promo'];
			$this->buyer_preference_buy = $res['buyer_preference_buy'];
                	$this->buyer_preference_rent = $res['buyer_preference_rent'];
                	$this->buyer_preference_lease = $res['buyer_preference_lease'];
                	$this->buyer_preference_pg = $res['buyer_preference_pg'];
			$this->buyer_preference_all = $res['buyer_preference_all'];
			$this->buyer_country_source = $res['buyer_country_source'];
			$this->seller_rescom = $res['seller_rescom'];
			$this->buyer_rescom = $res['buyer_rescom'];
                	$this->seller_preference_all = $res['seller_preference_all'];
                	$this->seller_class_agent = $res['seller_class_agent'];
                	$this->seller_class_builder = $res['seller_class_builder'];
			$this->seller_class_owner = $res['seller_class_owner'];
			$this->seller_preference_sell = $res['seller_preference_sell'];
			$this->seller_preference_rent = $res['seller_preference_rent'];
			$this->seller_preference_lease = $res['seller_preference_lease'];
			$this->seller_preference_pg = $res['seller_preference_pg'];
			$this->seller_country_source = $res['seller_country_source'];
			$this->buyer_upper_limit = $res['buyer_upper_limit'];
			$this->seller_upper_limit = $res['seller_upper_limit'];
			$this->register_city_radio = $_POST['register_city_radio_hidden'];
			$this->seller_city_radio = $_POST['seller_city_radio_hidden'];
			$this->buyer_city_radio = $_POST['buyer_city_radio_hidden'];
		}
		else{
			$this->CITY = $cityDD['city'];
			$this->BUYER_CITY = $cityDD['buyer_prop_city'];
			$this->SELLER_CITY = $cityDD['seller_prop_city'];
			$this->buyer_preference_buy = 'on';
			$this->buyer_preference_rent = 'on';
			$this->buyer_preference_lease = 'on';
			$this->buyer_preference_pg = 'on';
			$this->seller_preference_all = 'on';
			$this->seller_class_agent = 'on';
			$this->seller_class_builder = 'on';
			$this->seller_class_owner = 'on';
			$this->PROPERTY_TYPE = $propertyType['buyer_property_type'];
			$this->SELLER_PROPERTY_TYPE = $propertyType['seller_property_type'];
			$this->BUYING_BUDGET_MIN = $budgetDD['budget_min'];
			$this->BUYING_BUDGET_MAX = $budgetDD['budget_max'];
			$this->CITY_REGION = $cityRegionDD['city_region'];
			$this->SELLER_CITY_REGION = $cityRegionDD['seller_prop_city_region'];
			$this->BUYER_CITY_REGION = $cityRegionDD['buyer_prop_city_region'];
		}
		
	}


	/**
	* A) This will show the interface to upload csv (recievers info) .....
	* B) Add user into associated mailer table.
	*/
	public function executeCsvUpload(sfWebrequest $request)
	{
		$MmmMailerBasicInfo = new MmmMailerBasicInfo;
		$this->mailers= $MmmMailerBasicInfo->retrieveAllMailers();
		$data = $request->getParameterHolder()->getAll();

		if(array_key_exists('csv',$_FILES))
		{
			$this->msgToDisplay="INVALID FILE";
			$this->mail_id=$data["mailer_id"];

			if($_FILES["csv"]["type"]=="text/csv" || strstr($_FILES["csv"]["type"],'ms-excel'))
			{
				$this->noError = 1;
				$this->msgToDisplay="FILE UPLOADED!";
				$handle=fopen($_FILES["csv"]["tmp_name"],"r");
				$row=1;
				$arr=array();
				$key=array();
				$info=array();
				while (($data = fgetcsv($handle, ",")) !== FALSE)
				{
					$i=0;
					if($row==1)
					{
                                                $validOptArr = array('PROFILEID','EMAIL','NAME','PHONE');
                                                foreach($data as $v)
                                                {
                                                        if(!in_array($v,$validOptArr))
                                                        {
                                                                $this->msgToDisplay = "INVALID SYNTAX IN CSV FILE UPLOADED - ($v)";
                                                                $this->noError = 0;
                                                                break;
                                                        }
                                                }
                                                if($this->noError== 0)
                                                        break;

						if(!(in_array("PROFILEID",$data)&&in_array("EMAIL",$data)))
						{
							$this->msgToDisplay = "INVALID SYNTAX IN CSV FILE UPLOADED!";
							$this->noError = 0;
							break;
						}
						while(array_key_exists($i,$data))
						{
							$key[$i]=CommonUtility::removeQuotes($data[$i]);
							$i++;
						}
						$row++;
						continue;
					}
					while(array_key_exists($i,$data))
					{
						$arr[$key[$i]]=CommonUtility::removeQuotes($data[$i]);
						$i++;
					}
					$row++;
					$info[]=$arr;
				}

				//$this->insertedRows=$row-2;

				$MmmMailerBasicInfo = new MmmMailerBasicInfo;
				$webSite = $MmmMailerBasicInfo->getSiteEnumFromMailerId($this->mail_id);
				$indM = new Individual_Mailers;
				$indM->createDumpPerform($this->mail_id,$webSite,"csv",$info);
				$this->insertedRows = $indM->getCountOfMails($this->mail_id);
			}
		}
	}


	/*
	* This action will show the user to choose from one of the two options to send mail .....
	* 1) Sending Url
	* 2) Write Hard Code Mail
	*/
	public function executeWriteMail(sfWebrequest $request)
	{
	}


	/**
	* This section will handles entering mail detail (based on Url) .....
	* 1. Action/interface
	* 2. Error handling
	*/
	public function executeUrlMail(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();

		$scheduleTime = $data['rl_reminder_date'].'T'.$data['hour'].':'.$data['minute'];
		$data['schedule_time'] = $scheduleTime;
	
		/* form is submitted */
		if(array_key_exists('submit',$data) && $data["submit"])
		{
			$this->errors = MmmFormValidation::validateSubmitUrlMail($data);
			if($this->errors)
			{
				$this->edit = $data;

                $this->edit['date'] = $data['rl_reminder_date'];
                $this->edit['hour'] = $data['hour'];
                $this->edit['minute'] = $data['minute'];
			}
			else
			{
				/* write mail to disk */
				$urlMail  = $data["browserUrl"];
				$MmmMailerBasicInfo = new MmmMailerBasicInfo;
				$mailerId = $data["mailer_id"];
				$site = $MmmMailerBasicInfo->getSiteEnumFromMailerId($mailerId);
				MmmUtility::writeMmmMail($urlMail,$mailerId,$site);
				/* write mail to disk */
	
				$MmmMailerDetailedInfo = new MmmMailerDetailedInfo;
				$MmmMailerDetailedInfo->addMailerInfo($data);

				$MmmMailerBasicInfo->updateStatus($mailerId,MmmConfig::$status['WRITE_MAIL']);
				$this->successMsg = "Mail data has been added please ,Now you can send test mail";
			}
		}


		/* 
		* A) If its initial form
		* B) Form submit got error so that we need to rethrow the form
		* C) we choose a mailed id from the form, it rethrow filling the form values.
		*/
		if(!array_key_exists('submit',$data) || $this->errors)
		{
			$MmmMailerBasicInfo = new MmmMailerBasicInfo;
			$this->mailers= $MmmMailerBasicInfo->retrieveAllMailers('',array('urm','tpm'));

			/* display information of choosen mailer-id */
			if(array_key_exists('id',$_GET))
			{
				$this->id = $_GET['id'];
				if($this->id)
				{
					$this->site = $MmmMailerBasicInfo->getSiteEnumFromMailerId($this->id);
					$MmmMailerDetailedInfo=new MmmMailerDetailedInfo;
					$this->mailinfo = $MmmMailerDetailedInfo->getMailerInfo($this->id);

					$dateArray = explode('T',$this->mailinfo['SCHEDULE_TIME']);
					$this->mailinfo['date'] = $dateArray[0];
					$timeArray = explode(':',$dateArray[1]);
					$this->mailinfo['hour'] = $timeArray[0];
					$this->mailinfo['minute'] = $timeArray[1]; 

					/*suggest template name if its not added */
					if($this->mailinfo["TEMPLATE_NAME"]=='')
						$this->templateName = $this->mailers[$this->id]."_Template";
				}
			}
		}
	}
	
	/**
	* This section will handle following of hardcodeMail .....
	*  1. action/interface
	*  2. error handling
	*/
	public function executeHardcodeMail(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();
		$MmmMailerBasicInfo = new MmmMailerBasicInfo;

		if(array_key_exists('submit',$data) && $data["submit"])
		{
			$this->errors = MmmFormValidation::validateHardcodeMail($data);
			if($this->errors)
			{
				$this->edit = $data;
			}
			else
			{
				$MmmMailerBasicInfo = new MmmMailerBasicInfo;
				$MmmMailerBasicInfo->updateStatus($data['mailer_id'], MmmConfig::$status['WRITE_MAIL']);

				$MmmMailerDetailedInfo = new MmmMailerDetailedInfo;
				$MmmMailerDetailedInfo->addMailerInfo($data);
				$this->successMsg = "Mail data has been added please ,Now you can send test mail";
			}
		}
		if(!array_key_exists('submit',$data) || $this->errors)
		{
			$this->mailers= $MmmMailerBasicInfo->retrieveAllMailers('','hcm');
			$this->templateName="";
			/* display information of choosen mailer-id */
			if(array_key_exists('id',$_GET))
			{
				$this->id = $_GET['id'];
				if($this->id)
				{
					$MmmMailerDetailedInfo = new MmmMailerDetailedInfo;
					$this->mailinfo = $MmmMailerDetailedInfo->getMailerInfo($this->id);
					$mailer_type=$MmmMailerBasicInfo->retrieveAllMailers('J');
					if(array_key_exists($this->id,$mailer_type))
						$this->site="J";
					else
						$this->site="9";
					$mailer_type = $MmmMailerBasicInfo->retrieveAllMailers();
					$this->templateName=$mailer_type[$this->id]."_Template";
				}
			}
		}
	}
	
	/**
	* This function will test perform .....
	* 1. List test email-ids.
	* 2. Add permanent test email to specific sites
	* 3. Add temporary test email to specific sites
	* 4. Delete email ids.
	*/
	public function executeSetTestId(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();		
		$testMail = new TestMail;

		if(array_key_exists('submit',$data) && $data["submit"])
		{
			/* 
			* handling actions like Delete/PermanentAdd/TemporaryAdd of email-ids
			*/
			if(array_key_exists('actionTaken', $data))
			{
				if($data['actionTaken'] == "Delete")
				{
					if(array_key_exists('mailIdsPermanent', $data)) //delete from permanent 
						$testMail->deletePermanentTestEmail($data['mailIdsPermanent']);
					if(array_key_exists('mailIdsTemporary', $data)) //delete from temporary
						$testMail->deleteTemporaryTestEmail($data['mailIdsTemporary']);
				}	
				else if($data['actionTaken'] == "PermanentAdd")
				{	
					$testMail->addPermanentTestEmail($data);
				}
				else if($data['actionTaken'] == "TemporaryAdd")
				{
					$testMail->addTemporaryTestEmail($data);
				}
			}

			/*	
			* If We choose a mailer-id, we need to show temporary-id assigned.
			*/
			if(array_key_exists('mailer_id', $data))
			{
				$this->mailer_id = $data['mailer_id'];
				$this->testIdT = $testMail->showTemporaryTestEmail($data['mailer_id']);
				if(empty($this->testIdT))
					$this->testIdT="";
			}
				
		}
		
		$this->sites = MmmConfig::$mailerWebsite;

		/* populate test mailers */
		$this->testIdPJ = $testMail->showPermanentTestEmail('J');
		$this->testIdP9 = $testMail->showPermanentTestEmail('9');

		$MmmMailerBasicInfo = new MmmMailerBasicInfo;
		$this->mailers= $MmmMailerBasicInfo->retrieveAllMailers();
	}

	/**
	* This functionm will perform the action on mailers
	*/
	public function executeFireMenu(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();		
		
		$MmmMailerBasicInfo = new MmmMailerBasicInfo;		
		if(array_key_exists('perform', $data))
		{	
			/** can be generalized */	
			if($data['perform'] == "test")
			{
				if(array_key_exists('readyForTest', $data))
					$MmmMailerBasicInfo->updateStatus($data['readyForTest'], MmmConfig::$status['MARKED_FOR_TESTING']);
			}	
			else if($data['perform'] == "retest")
			{
				if(array_key_exists('testCompleted', $data))
					$MmmMailerBasicInfo->updateStatus($data['testCompleted'], MmmConfig::$status['MARKED_FOR_TESTING']);
			}
			else if($data['perform'] == "start")
			{
				if(array_key_exists('testCompleted', $data))
					$MmmMailerBasicInfo->updateStatus($data['testCompleted'], MmmConfig::$status['RUNNING']);
				if(array_key_exists('stopped', $data))
					$MmmMailerBasicInfo->updateStatus($data['stopped'], MmmConfig::$status['RUNNING']);
			}
			else if($data['perform'] == "stop")
			{
				foreach($data["running"] as $k=>$v)
				{
					$mmmjs_MAILER_STOPED = new mmmjs_MAILER_STOPED;
					$mmmjs_MAILER_STOPED->add($v);
				}
				//passthru("pkill -f 'php symfony cron:FireMail ACTUAL 40'");
				if(array_key_exists('testing', $data))
					$MmmMailerBasicInfo->updateStatus($data['testing'], MmmConfig::$status['STOP']);
				if(array_key_exists('fired', $data))
					$MmmMailerBasicInfo->updateStatus($data['fired'], MmmConfig::$status['STOP']);
				if(array_key_exists('running', $data))
					$MmmMailerBasicInfo->updateStatus($data['running'], MmmConfig::$status['STOP']);
			}
		}
		
		/* This section can be optimized by using a single query (will pick later) */
		$this->readyForTest = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['WRITE_MAIL']);
		$this->testCompleted = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['TEST_COMPLETED']);
		$this->running = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['RUNNING']);
		$this->testing = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['MARKED_FOR_TESTING']);
		$this->fired = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['FIRED']);
		$this->completed = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['RUNNING_COMPLETED']);
		$this->stopped = $MmmMailerBasicInfo->retrieveMailersByStatus(MmmConfig::$status['STOP']);
	}
	public function executeChangeState(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();		
                print_r($data);
		die;
	}
	/**
	 * This action is used to display the form for getting MIS.
	 */ 
	public function executeMis(sfWebrequest $request)
	{
		$temp=new MmmUtility;
		$year = $temp->getYears();
		foreach($year as $y)
		{
			$years[]=$y;
		}
		$this->years=$years;
		$month =$temp->getMonths();
		foreach($month as $m)
		{
			$months[]=$m;
		}
		$this->months=$months;
		if(array_key_exists('site',$_GET))
		{
			$this->sites = $_GET['site']; 
			if($this->sites == '9')
			{
				$mailer = new MmmMailerBasicInfo;
				$this->mailers= $mailer->retrieveAllMailers('9','','notRequired');
				$this->l=count($this->mailers); //l= the number of mailers fetched for 99 acres.	
			}
			else if($this->sites=='J')
			{
				$mailer = new MmmMailerBasicInfo;
				$this->mailers= $mailer->retrieveAllMailers('J');
				$this->l=count($this->mailers);//l= the number of mialers fetched for jeevansathi.
			}
			else
			{
					
				$this->mailers=array();
				$this->l=0;// if no site is chosen l=0.
			}
		}
		//print_r($this->years);
		//print_r($this->months);
		//die;
	}
	/**
	 * This action gets data from the form filled and shows the MIS as per the requirements.
	 * The fields of the form in array $data:
	 * site=> either 'J' or  '9' or '';
	 * $mailer_id=> id of the mailer.
	 * dt_type=> the type of date. Either mnt for monthwise or day for daywise.
	 * years_m=> the year in case of monthwise MIS.
	 * years_d=> the year in case of daywise MIS.
	 * months=> the month in case of daywise MIS.
	 * sent, open, unsubscribe => the fields needed in MIS.
	 */ 
	public function executeMISInfo(sfwebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();
		$mis_dt = new CreateMIS;
		$this->mis = $mis_dt->mis_data($data);
		$this->loop_o=array_keys($this->mis);
		$temp=new MmmMailerBasicInfo;
		//$this->mailer_name = $temp->getNamebyId($this->loop_o);
		$this->mailer_name = $temp->getNamebyId($this->loop_o,'WithKeyId');
		$temp=new MmmUtility;
		if($data['dt_type']=="mnt")
		{
			$this->year=$data['years_m'];
			$this->loop_i = array_values($temp->getMonths());
			$this->flag=0;
		}
		else
		{
			$this->year=$data['years_d'];
			$this->month=$data['months'];
			$this->loop_i =array_values($temp->getDays());
			$this->flag=1;
		}
		if(array_key_exists('sent',$data))
		{
			$this->s=1;
			$this->tots=array();
			$this->totals=0;
			$this->sums=array();
			foreach($this->loop_o as $k => $i)
			{
				$this->sums[$i]=0;
				foreach($this->loop_i as $l => $j)
					$this->sums[$i]=$this->sums[$i]+$this->mis[$i]['sent'][$j];
			}
			foreach($this->loop_i as $k => $i)
			{
				$this->tots[$i]=0;
				foreach($this->loop_o as $l => $j)
					$this->tots[$i]=$this->tots[$i]+$this->mis[$j]['sent'][$i];
				$this->totals=$this->totals+$this->tots[$i];
			}
		}
		if(array_key_exists('open',$data))
		{
			$this->o=1;
			$this->toto=array();
			$this->totalo=0;
			$this->sumo=array();
			foreach($this->loop_o as $k => $i)
			{
				$this->sumo[$i]=0;
				foreach($this->loop_i as $l => $j)
					$this->sumo[$i]=$this->sumo[$i]+$this->mis[$i]['open'][$j];
			}
			foreach($this->loop_i as $k => $i)
			{
				$this->toto[$i]=0;
				foreach($this->loop_o as $l => $j)
					$this->toto[$i]=$this->toto[$i]+$this->mis[$j]['open'][$i];
				$this->totalo=$this->totalo+$this->toto[$i];
			}
		}
		if(array_key_exists('unsubscribe',$data))
		{
			$this->u=1;
			$this->totu=array();
			$this->totalu=0;
			$this->sumu=array();
			foreach($this->loop_o as $k => $i)
			{
				$this->sumu[$i]=0;
				foreach($this->loop_i as $l => $j)
					$this->sumu[$i]=$this->sumu[$i]+$this->mis[$i]['uns'][$j];
			}
			foreach($this->loop_i as $k => $i)
			{
				$this->totu[$i]=0;
				foreach($this->loop_o as $l => $j)
					$this->totu[$i]=$this->totu[$i]+$this->mis[$j]['uns'][$i];
				$this->totalu=$this->totalu+$this->totu[$i];
			}
		}
		
		//calls MISInfoSuccess.tpl
	}
	/**
	 * This action creates the form for getting client MIS link and then forms the link for the client.
	 * $data fields:
	 * 1. mailer_id.
	 */ 
	public function executeClientMISLink(sfWebrequest $request)
	{	
		$data = $request->getParameterHolder()->getAll();
		if(array_key_exists('mailer_id',$data))
		{
			$this->f=1;
			$unique=new MmmMailerBasicInfo;
			$uniqID=$unique->getUniqueId($data['mailer_id']);
			$this->mailer_id=$data['mailer_id'];
			if($uniqID['UNIQUEID'])
			{	
				$this->link=JsConstants::$ser2Url."/masscomm.php/mmm/showMIS?mailerid=".$data['mailer_id']."&uniqueid=".$uniqID['UNIQUEID'];
			}
		}
		//call clientMISLinkSuccess.tpl
	}
	/**
	 * This action shows the client the MIS for a partiular mailer_id.
	 */ 
	public function executeShowMIS(sfWebrequest $request)
	{
		$this->mailer_id=$_GET["mailerid"];
		$unique_id=$_GET["uniqueid"];
		$unique=new MmmMailerBasicInfo;
		$uniqID=$unique->getUniqueId($this->mailer_id);
		if($uniqID['UNIQUEID']==$unique_id)
		{
			$clientMIS=new CreateMIS;
			$res=$clientMIS->getClientMIS($this->mailer_id,$_GET['showCount']);
			$this->no_of_sent=$res[0]['SENT'];
			$this->mis=$res[1];
			$this->mailer_name=$uniqID['MAILER_NAME'];

			if($_GET['showCount'] == 1) $this->mailsToBeSent = $res[2]; 
		}
		else
		{
			print_r("Invalid URL");
			die;
		}
	}
	public function executeLogout(sfWebrequest $request)
	{
		//call LogoutSuccess.tpl
	}
	public function executeTest(sfWebrequest $request)
	{
		$ind = new Individual_Mailers;
		$ind->createTable(3);
		$ind->createDump(3);
		print "HEll";
		die;
	}

	/**
	* This function will unsubscribe the profile from promotion mailers .....
	* Flag is also gets updated.
	*/
	public function executeUnsubscribe(sfWebrequest $request)
	{
		$formData = $request->getParameterHolder()->getAll();			
		JsCommon::oldIncludes(); //Include files which are required for integrating older authentication functionality
		$protect_obj = new protect;
		$userData = $protect_obj->checkSession();	
		$stopTableAction = $formData["isTestMailer"];
		$epid=$protect_obj->js_decrypt($formData['autologin'],"Y");
		$checksum = $formData["chksum"];
		if($checksum == $epid)
		{
			$epid_arr = explode("i",$epid);
			$profileId = $epid_arr[1];
			$userData["PROFILEID"] = $profileId;
		}
		if(!$stopTableAction)
		if($formData["site"]=='J' && $formData["serviceMail"]!='Y')
		{
			$ind = new Individual_Mailers;
			$data["PROFILEID"] = $userData["PROFILEID"];
			$ind->unsubscribe($data['PROFILEID']);
		}

		$data['FLAG'] = $formData["flag"];
		if($data['FLAG'] == 'U')
		{
			$this->title = "Successfully unsubscribed";
			$this->message = "We have updated your mailer preferences. You will no longer receive such promotional mailers.";
		}
		else
		{
			$this->title = "Spam report received";
			$this->message = "Your complaint has been noted. You will no longer receive such promotional mailers.";
		}
		$data['mailer_id']=$formData["mailerId"];
		$data['date']=date("Y-m-d");

		////////Flow for Unsubscribe Domain-Wise Count////////

        if(!$stopTableAction){

            $unsubscribedEmail = $formData["email"];
            $domain =  MmmUtility::checkEmailDomain($unsubscribedEmail);

            $obj = new mmmjs_MAIL_SENT;
            if($domain == 'G') $obj->domainDataInsert('mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE',$data['date'],$data['mailer_id'],'GMAIL_US',1);
            else if($domain == 'Y')  $obj->domainDataInsert('mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE',$data['date'],$data['mailer_id'],'YAHOO_US',1);
            else if($domain == 'H') $obj->domainDataInsert('mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE',$data['date'],$data['mailer_id'],'HOTMAIL_US',1);
            else if($domain == 'R') $obj->domainDataInsert('mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE',$data['date'],$data['mailer_id'],'REDIFF_US',1);
            else  $obj->domainDataInsert('mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE',$data['date'],$data['mailer_id'],'OTHERS_US',1);

        }

        /////////////////////////////////////////////////////


		if(!$stopTableAction)
		{	
			$uns=new CreateMIS;
			$uns->unsubscribe($data);
		}
		if($formData['site']=='9')
		{
			if(!$stopTableAction || $data['FLAG']=='U')
			{
				$checksum = $formData['checksum'];
				$obj = new mmm_99acresUtility();
				$obj->unsubscribeSpamButton($data['FLAG'],$checksum,$formData['profileid'],$formData["isMrc"],$formData["email"]);
			}
		}
		elseif($formData["site"]=='J' && $formData["serviceMail"]=='Y')
		{
			header("Location: ".JsConstants::$siteUrl.'/profile/unsubscribe.php');	
			die;
		}
	}

	/**
	* This function will handle the open count 
	*/
	public function executeOpenCount(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();	
		$data['mailer_id']= $data["mailerId"];
		$data['date']=date("Y-m-d");
		$data['domain']=MmmUtility::checkEmailDomain($data["email"]);
		$data['email']=$data["email"];
		$data['time']=date('Y-m-d H:i:s');
		$op=new CreateMIS;
		$op->open($data);
		$op->openIndividual($data);
        	header('Content-type: image/gif');
	        readfile(JsConstants::$imgUrl.'/profile/images/transparent_img.gif');
		die;
	}


	/**
	* This function will
	* 1. Display interface to upload csv.
	* 2. create csv having information of MIS.
	*/
	public function executeCreateCsv(sfWebrequest $request)
	{
		$data = $request->getParameterHolder()->getAll();
		$this->f=0;
		$this->csv=0;
		$this->mailer_id = $data['mailer_id'];
		if(array_key_exists('mailer_id',$data))
		{
			$MmmMailerBasicInfo = new MmmMailerBasicInfo;
			$mailers= $MmmMailerBasicInfo->retrieveAllMailers('','','notRequired');
			if(array_key_exists($data['mailer_id'],$mailers))
			{
				if($data['type']=="o")
				{
					$data['filename']="overall";
				}
				else
				{
					$data['filename']="individual";
					
				}
				$CreateMIS = new CreateMIS;
				$CreateMIS->getCsv($data);
				$this->csv=1;
				$filename = $data['filename']."-".$data['mailer_id'].".csv";
				$this->path=JsConstants::$ser2Url."/uploads/mmm/".$data['filename']."-".$data['mailer_id'].".csv";
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				//header('Location:'.$this->path);
				readfile("$this->path");
				die;
			}
			$this->f=1;
		}
	}

	public function executeRedirectUrl(sfWebrequest $request){

        $data = $request->getParameterHolder()->getAll();

        /***Tracking***/

        $obj = new mmmjs_TRACK_LINKS;
        $obj->trackLink($data['email'],$data['mailerId'],$data['url']);

        /***Tracking***/

        /***Redirection***/

        $www = substr($data['url'],0,6);//www://
        $http = substr($data['url'],0,7);//http://
        $https = substr($data['url'],0,8);//https://
        if($http == 'http://' || $https == 'https://'){
            header('Location: '.$data['url']);
            die;
        }
        else
        {
            header('Location: '.'http://'.$data['url']);
            die;
        }

		/***Redirection***/
	}
	public function executeViewMailInBrowser(){
                $mailerId=$_GET['mailerId'];
                $webSite=$_GET['website'];
                $name=$_GET['name'];
                $email=$_GET['email'];
                $phone=$_GET['phone'];
                $arr = base64_decode($_GET['profileid']);
		$arr1=explode('|',$arr);
                $profileid=$arr1[1];
                $this->MmmMailerBasicInfo = new MmmMailerBasicInfo;
                $this->mmm_99acresUtility = new mmm_99acresUtility;
                $this->mailerBasicInfoArr = $this->MmmMailerBasicInfo->retreiveMailerInfo($mailerId);
                $templateName = MmmUtility::getTemplateName($mailerId);
                $this->smarty = MmmUtility::createSmartyObject();
                $mailInfoArr = array();
                $mailInfoArr['NAME'] = $name;
                $mailInfoArr['EMAIL'] = $email;
                $mailInfoArr['PHONE'] = $phone;
                $responseType=$this->mailerBasicInfoArr['RESPONSE_TYPE'];
                $smartyArr = $this->mmm_99acresUtility->getSmartyArray($responseType,$mailerId,$profileid,$mailInfoArr);
                if(is_array($smartyArr))
                        foreach($smartyArr as $key=>$val)
                                $this->smarty->assign($key,$val);
                $isMrc = $this->mmm_99acresUtility->is15DigitProfileId($profileid);
                if($isMrc)
                        $this->smarty->assign("isMrc",'Y');
                else
                        $this->smarty->assign("isMrc",'N');

                $this->smarty->assign('name',$name);
                $this->smarty->assign('email',$email);
                $this->smarty->assign('phone',$phone);
                $this->smarty->assign('showHeaderFooter','N');
		$url = JsConstants::$mmmjs99acres."/do/MMM_Utility/getToken";
                $postParams = "pid=$profileid";
                $checksum = mmm_99acresUtility::sendCurlRequestFor99($url."?".$postParams);
                $this->smarty->assign("checksum",$checksum);
                
                $ppcSmarty = base64_encode($mailInfoArr["NAME"].'|'.$mailInfoArr["EMAIL"].'|'.$mailInfoArr["PHONE"]);
                $this->smarty->assign('ppcqueryform',$ppcSmarty);
		sfProjectConfiguration::getActive()->loadHelpers("Partial","footer".$webSite."Mailer");
                sfProjectConfiguration::getActive()->loadHelpers("Partial","header".$webSite."Mailer");
                $msg = $this->smarty->fetch("individual_mailer_templates/".$templateName);
                echo $msg;die;

        }
	
	public function executeTrackAdvProperty(sfWebrequest $request){

        $data = $request->getParameterHolder()->getAll();
                 
        $checksum = $data['checksum'];
        $m3source = $data['m3source'];

        $URL = "/do/advertiseproperty?m3source=$m3source";
        $redirectURL = JsConstants::$baseUrl99."/maillink?code=".$checksum."&url=".base64_encode($URL);
    
        header("Location: ".$redirectURL);
        die;
    }

	public function executeMis99Info(sfWebrequest $request){

        $data = $request->getParameterHolder()->getAll();

        if(array_key_exists('submit',$data))
        {

            $obj = new CreateMIS();
            $mailerDataArr = $obj -> getMis99Data($data['startDate'],$data['endDate']);

            $this -> mailerDataArr = $mailerDataArr;
			$this -> columnName = array('MAILER_ID','NAME','TYPE','START_TIME','RECEIVER','FROM_EMAIL','SUBJECT','TARGET_CITY','SENT','OPEN','UNSUBSCRIBE','RESPONSE','OPEN_RATE(%)','BROWSERURL');
        }

    }

    public function executeMis99(sfWebrequest $request){

    }

}
