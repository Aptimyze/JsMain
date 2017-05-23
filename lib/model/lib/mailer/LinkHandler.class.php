<?php
class LinkHandler implements VariableHandler{
  private $_link_class;
  private $_token_name;
  private $_token_profileId;
  private $_var_object;

  public function __construct($var_object) {
	  $this->_var_object=$var_object;
  }

  public function getActualValue() {
	  $link_id=MailerArray::getLinkId($this->_var_object->getVariableName());
	  $this->_link_class=LinkFactory::getLink($link_id);
	  $this->_link_class->setVariable($this->_var_object);
    
    /*/list($id, $url, $req_auto)/*/$url/**/ = $this->_link_class->getLinkUrl();
    //echo "Link Id: " . $id . ", Link URL: " . $url . ", AutoLogin: " . $req_auto;die; 
    return $url;
  }
}
