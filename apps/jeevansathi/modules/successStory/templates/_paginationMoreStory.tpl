<!-- Pagination -->	
	<ul class="pagination rf">
	
		         ~if $prev`
					 		<li><a href="~sfConfig::get("app_site_url")`/successStory/morestory?page=~$prev`">Previous</a></li>
					
		        ~else`
		                <li style="padding: 2px 10px;">Previous</li>
		         ~/if`
	
		        <li style="border: medium none ;" class="pg_num" >
			        <select class="gray" style="z-index:10" onChange="javavscript:{if (this.value!='' )page_load1(this.value);}"><option value='' selected=""> Page&nbsp;</option>
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
		        <li><a href="~sfConfig::get('app_site_url')`/successStory/morestory?page=~$next`">Next</a></li>

		~else`
		        <li style="padding: 2px 10px;">Next</li>
		~/if`
	</ul>


<!-- End of Pagination -->

<script>

function page_load1(to_page)
{
	document.location="~sfConfig::get("app_site_url")`/successStory/morestory?page="+to_page+"";
	
}

</script>

