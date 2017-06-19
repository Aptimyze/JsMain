<?php
/**
* This Class is a factory class used to return object of class picasa / flickr / facebook based on imput parameter.
*/
class ImportPhotoFactory
{
        /**
        This function is used to return object of the class picasa / flickr / facebook
        @param $website - three possible values (picasa/flickr/facebook) to create an object of associated class
	@return object
        **/
	static public function getPhotoAgent($website)
	{
		if($website=='facebook')
		{
			return new fb;
		}
		elseif($website=='flickr')
		{
			return new fl;
		}
		elseif($website=='picasa')
		{
			return new picasa();
		}
	}
}
?>
