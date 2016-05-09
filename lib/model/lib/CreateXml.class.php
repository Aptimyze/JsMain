<?php
//This class is used to generate a xml file
class CreateXml
{
	public function __construct()
	{
	}

	/*
	This function creates the parent dom object for the xml file
	@return - dom object
	*/
	public function createDoc()
	{
		$domtree = new DOMDocument('1.0', 'UTF-8');
		return $domtree;
	}

	/*
	This function is used to add a child with value to a given node
	@param - dom object,node to which the child needs to be appended, name of child node, value of child node, if child node generated needs to be returned then pass 1(optional)
	@return - blank or child node
	*/
	public function addChildWithValue($domtree,$node,$childname,$value,$ifReturn='')
	{
		$xmlRoot = $node->appendChild($domtree->createElement($childname,$value));

		if($ifReturn)
			return $xmlRoot;
	}

	/*
	This function is used to add a child without a value to a given node
	@param - dom object,node to which the child needs to be appended, name of child node, if child node generated needs to be returned then pass 1(optional)
	@return - blank or child node
	*/
	public function addChildWithoutValue($domtree,$node,$childname,$ifReturn='')
	{
		$xmlRoot = $node->appendChild($domtree->createElement($childname));
		if($ifReturn)
			return $xmlRoot;
	}

	/*
	This function prints the xml file
	@param - dom object
	*/
	public function saveDoc($domtree)
	{
		return $domtree->saveXML();
	}
}
?>
