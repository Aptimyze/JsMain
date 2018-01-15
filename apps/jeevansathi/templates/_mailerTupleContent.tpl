<table width="100%" border="0" cellspacing="0" cellpadding="0">
  ~foreach from=$users name=users item=user` 
	~if $user->getLOGICLEVEL()`
		~$stypeMatchTemp=$user->getLOGICLEVEL()|cat:"&stypeMatch="|cat:$stypeMatch`
	~else`
		~$stypeMatchTemp= $stypeMatch`
	~/if`
	~if $count>1`
		~if $smarty.foreach.users.index % 2 == 0`
			<tr>
			<td>
		~/if`
		~include_partial("global/mailerTupleMultiple",[user=>$user,index=>$smarty.foreach.users.index,count=>$count,stypeMatch=>$stypeMatchTemp,logic=>$logic,commonParameters=>$commonParameters,mailerLinks=>$mailerLinks])`
		~if $smarty.foreach.users.index % 2 == 1` 
			</td>
			</tr>
		~/if`
	~else`
		<tr>
			<td>
			~include_partial("global/mailerTupleSingle",[user=>$user,logic=>$logic,stypeMatch=>$stypeMatchTemp,commonParameters=>$commonParameters,mailerLinks=>$mailerLinks])`
			</td>
		</tr>
	~/if`
  ~/foreach`
</table>
