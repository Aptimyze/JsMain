~assign var='myClass' value=''` 
~assign var='group' value=''`
~assign var='postfix' value=''` 
~if $value["GROUP"] neq ''`
	~assign var='group' value=$value["GROUP"]`
	
	~if $value["IN_GROUP"] neq ''`
		~$myClass =$myClass|cat:' js-inGroup'`
	~/if`
	~if $value["IS_GROUP_HEADING"] neq ''`
		~$myClass =$myClass|cat:' js-isGroupheading'`
	~/if`
	~if $value["ISGROUP"] neq ''`
		~$myClass =$myClass|cat:' js-isGroup'`
	~/if`
~else`
	~assign var='myClass' value='js-noGroup'` 
~/if`
~if $value["HAS_DEPENDENT"] neq ''`
	~$myClass =$myClass|cat:' js-hasDependent'`
~/if`
~if $field eq "hage" || $field eq "lage"`
	~$postfix = "yrs"`
~/if`
<li id="sf_~$field`_~$value['VALUE']`" data=~$value["VALUE"]` class='~$myClass`' group='~$group`' ~if isset($extraAttribute)` ~$extraAttribute` = ~$extraAttributeValue`~/if` ><div>~$value["LABEL"]` ~$postfix`</div></li>
