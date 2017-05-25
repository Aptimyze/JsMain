<!-- Pagination -->	
	<ul class="pagination rf">

         ~if $prev`
			 ~if $fromSeo eq 'N'`
	                <li><a href="~sfConfig::get("app_site_url")`/successStory/story?page=~$prev`&year=~$year`">Previous</a></li>
			~else`
			 		<li><a href="~sfConfig::get("app_site_url")`/successStory/story?page=~$prev`&year=~$year`&parentvalue=~$fetchStoryObj->getParentValue()`&mappedvalue=~$fetchStoryObj->getMappedValue()`&parenttype=~$fetchStoryObj->getParentType()`&mappedtype=~$fetchStoryObj->getMappedType()`">Previous</a></li>
			~/if`
        ~else`
                <li style="padding: 2px 10px;">Previous</li>
         ~/if`

        <li style="border: medium none ;" class="pg_num" >
	        <select class="gray" style="z-index:10" onChange="javavscript:{if (this.value!='' )page_load(this.value);}"><option value='' selected=""> Page&nbsp;</option>
	        	~foreach $totalPages as $val`
		        	~if $val eq $page`
		        		<option value="~$val`" selected>~$val`</option>
		        	~else`
		        		<option value="~$val`">~$val`</option>
		        	~/if`
	        	~/foreach`
	        </select>
        </li>

		
		~if $next`
			 ~if $fromSeo eq 'N'`
		        <li><a href="~sfConfig::get("app_site_url")`/successStory/story?page=~$next`&year=~$year`">Next</a></li>
			~else`
				 <li><a href="~sfConfig::get("app_site_url")`/successStory/story?page=~$next`&year=~$year`&parentvalue=~$fetchStoryObj->getParentValue()`&mappedvalue=~$fetchStoryObj->getMappedValue()`&parenttype=~$fetchStoryObj->getParentType()`&mappedtype=~$fetchStoryObj->getMappedType()`">Next</a></li>
			~/if`
		~else`
		        <li style="padding: 2px 10px;">Next</li>
		~/if`
	</ul>


<!-- End of Pagination -->

<script>

function page_load(to_page,year)
{
	 ~if $fromSeo eq 'N'`
	document.location="~sfConfig::get("app_site_url")`/successStory/story?page="+to_page+"&year=~$year`";
	~else`
	document.location="~sfConfig::get("app_site_url")`/successStory/story?page="+to_page+"&parentvalue=~$fetchStoryObj->getParentValue()`&mappedvalue=~$fetchStoryObj->getMappedValue()`&parenttype=~$fetchStoryObj->getParentType()`&mappedtype=~$fetchStoryObj->getMappedType()`";
	~/if`
}

</script>

