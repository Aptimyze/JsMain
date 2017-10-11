<?php
class ValidateInputFactory
{
	public static function getModuleObject($module)
	{
		if($module == "search")
			$obj = new SearchModuleInputValidate;
		elseif($module == "profile")
			$obj = new SearchModuleInputValidate;
		elseif($module == "register")
			$obj = new RegisterModuleInputValidate;
		elseif($module == "api")
			$obj = new ApiModuleInputValidate;
		elseif($module == "social")
			$obj = new PictureModuleInputValidate;
		elseif($module == "contacts")
			$obj = new ContactModuleInputValidate;
		elseif($module == "chat")
			$obj = new ChatModuleInputValidate;
		elseif($module == "myjs")
			$obj = new MyJsModuleInputValidate;
        elseif($module == "inbox")
			$obj = new InboxModuleInputValidate;
		return $obj;
	}
}
