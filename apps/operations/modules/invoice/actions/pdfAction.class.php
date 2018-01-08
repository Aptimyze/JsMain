<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 */
class pdfAction extends sfAction
{
        public function execute($request)
	{
          
        $response =$this->getResponse();

        $cid 		=$request->getParameter("cid");
	$saleid 	=$request->getParameter("saleid");
	$receiptid 	=$request->getParameter("receiptid");
	$saleType 	=$request->getParameter("saleType");
	$billid       	=$request->getParameter("billid");
	$invoiceType	=$request->getParameter("invoiceType");

	if($invoiceType=='M'){
        	if($saleType=='P')
                	$filename ="Proforma Invoice -NM JS";
        	else
                	$filename ="Sales Invoice -NM JS";
        	$url=sfConfig::get("app_site_url")."/billing/misc_salesInvoice_printbill.php?saleid=$saleid&receiptid=$receiptid&saleType=$saleType&invoiceType=$invoiceType&cid=$cid";
	}
	elseif($invoiceType=='JS'){
		$filename ="Sales Invoice -JS";	
	        $url=sfConfig::get("app_site_url")."/billing/misc_salesInvoice_printbill.php?billid=$billid&receiptid=$receiptid&invoiceType=$invoiceType&cid=$cid";  
	}

          $file=PdfCreation::PdfFile($url);
          // Adding the file to the Response object
          $response->clearHttpHeaders();
          $response->setHttpHeader('Pragma: public', true);
          $response->setContentType('application/pdf');
          $response->setHttpHeader('Content-Disposition','attachment; filename="'.$filename.'.pdf"');

	  $response->setContent($file);	
          $response->sendHttpHeaders();
          pclose($fp);	
          return sfView::NONE;
        }
} 
