~include_partial('global/header')`      
	<div>
		<table width=760 align="CENTER" >
			<tr class="formhead" align="CENTER">
				<td colspan=4>~$username`</td>
			</tr>
		</table>
	</div>
	<div>
	~if $deletedphotoArr neq ''`
		<div align="center"> 
			<br><b>Deleted Photos found : </b><br><br>
			~foreach from=$deletedphotoArr item=row`
			    <img src='~$row->getMainPicUrl()`' height=250 width=250>
			    <p>
			    	REASON FOR DELETION: ~if $row->getREASON() neq ''` ~PictureStaticVariablesEnum::$DELETE_REASONS[$row->getREASON()]` ~else` Not Specified ~/if`
			    </p>
			    <br>
			~/foreach`
         </div>
	~else`
		<div align="center">No deleted photos found for this user...<br></div>
	~/if`
	</div>
	<br>
	<div>
	~if $originalphotoArr neq ''`
		<div align="center"><b> Existing Photos found : </b><br><br>
			~foreach from=$originalphotoArr item=row`
			    <img src='~$row->getOriginalPicUrl()`' height=250 width=250><br>
			~/foreach`
		</div>
	~else`
		<div align="center">No existing photos found for this user...<br></div>
	~/if`
	</div>
~include_partial('global/footer')`