<?php
/** 
 * CustomRegPage Class For SEM Custom Pages
 */
class CustomRegPage
{
	private $m_szHeading 		= "REGISTER FREE";
	private $m_szSubHeading1 	= "& meet over";
	private $m_szSubHeading2 	= "2 Lakh profiles";
	private $m_urlImage			= "https://ieplads.com/mailers/2013/jeevansathi/shortReg_p1/images/mainIMG1.jpg";
	private $m_iPageId			= -1;
	
	const HEADING 		= "HEADING";
	const SUBHEAD1		= "SUBHEAD1";
	const SUBHEAD2		= "SUBHEAD2";
	const IMAGEURL		= "IMAGEURL";
	const BODYSTYLE		= "padding-top :28px  !important";
	
	private $m_objSEM_CUSTOM_REG_STORE = null;
	
	public function __construct($request ="")
	{
		if($request == "")
			$request = sfContext::getInstance()->getRequest();
			
		//Reterving Info
		
		//Heading
		if(($heading = $request->getParameter('h')) && isset($heading))
		{
			$this->m_szHeading = htmlspecialchars($heading,ENT_NOQUOTES);
		}
		//Subheading 1
		if(($subhead1 = $request->getParameter('sh1')) && isset($subhead1))
		{
			$this->m_szSubHeading1 = htmlspecialchars($subhead1,ENT_NOQUOTES);
		}
		//Subheading 2
		if(($subhead2 = $request->getParameter('sh2')) && isset($subhead2))
		{
			$this->m_szSubHeading2 = htmlspecialchars($subhead2,ENT_NOQUOTES);
		}
		//Image Url
		if(($image = $request->getParameter('image')) && isset($image))
		{
			$this->m_urlImage = htmlspecialchars($image,ENT_NOQUOTES);
		}
		//Page ID 
		if(($page_id = $request->getParameter('p')) && isset($page_id))
		{
			$this->m_iPageId = htmlspecialchars($page_id,ENT_NOQUOTES);
		}
		
		//Initalised SEM_CUSTOM_REG Store
		$this->m_objSEM_CUSTOM_REG_STORE = new jsadmin_SEM_CUSTOM_REG_PAGE;
	}
	
	public function ProcessRequest($request)
	{
		//Get Html Content As per PageID
		$htmlContent = $this->m_objSEM_CUSTOM_REG_STORE->fetchRecord($this->m_iPageId);
		$htmlCode = $htmlContent['CONTENT'];
		
		if($this->m_iPageId == -1 || $htmlCode == null)
		{
			//Forware to register/page1
			return 1;
		}
		
		//Get Html Content As per PageID
		$htmlContent = $this->m_objSEM_CUSTOM_REG_STORE->fetchRecord($this->m_iPageId);
		$htmlCode = $htmlContent['CONTENT'];
		
		//Set all paramter in request before getting presentation onf registration of page1
		$this->SetRegistration_PageParameter($request);
		//GetPresentation For 'page1' Action of 'register' Module
		$szFormstr = sfContext::getInstance()->getController()->getPresentationFor('register', 'page1');
		
		$newHtml = $this->BakeHtml($htmlCode,$szFormstr);
		
		return $newHtml;
	}
	
	private function SetRegistration_PageParameter($request)
	{		
		//Heading, subheadings, and image
		$request->setParameter("customReg",1);
		$request->setParameter("h",$this->m_szHeading);
		$request->setParameter("sh1",$this->m_szSubHeading1);
		$request->setParameter("sh2",$this->m_szSubHeading2);
		$request->setParameter("image",$this->m_urlImage);
		$request->setParameter("p",$this->m_iPageId);
		
		//Default Value of Servie Email,Sms,Call and Promo-email
		$request->setParameter("service_email","S");
		$request->setParameter("service_call","S");
		$request->setParameter("service_sms","S");
		$request->setParameter("service_email","S");		
	}
	
	private function BakeHtml($szHtmlCode,$szFormData)
	{
		$szFirstHalf = substr($szHtmlCode,0,stripos($szHtmlCode,"<form>"));
		
		//Heading
		$arrTemp = explode(self::HEADING,$szFirstHalf);
		$szFirstHalf = implode("$this->m_szHeading",$arrTemp);
		//Subheading1
		$arrTemp = explode(self::SUBHEAD1,$szFirstHalf);
		$szFirstHalf = implode("$this->m_szSubHeading1",$arrTemp);
		//Subheading2
		$arrTemp = explode(self::SUBHEAD2,$szFirstHalf);
		$szFirstHalf = implode("$this->m_szSubHeading2",$arrTemp);
		
		//Image
		$arrTemp = explode(self::IMAGEURL,$szFirstHalf);
		$szFirstHalf = implode("$this->m_urlImage ",$arrTemp);
		
		//Now Extract html between </form> tag to </body> tag.
		$szSecondHalf = substr($szHtmlCode,stripos($szHtmlCode,"</form>")+strlen("</form>"));
		$szSecondHalf = substr($szSecondHalf,0,stripos($szSecondHalf,"</body>"));
		
		$newHtmlContent = $szFirstHalf."\n".$szFormData."\n".$szSecondHalf;
		
		return $newHtmlContent;
	}
	
}
?>
