<div class="overlay_wrapper_775px" style="background-color:white;" >
	<div class="top">     	
		<div class="text white b widthauto" >
			Select ~$clusterTitle`
		</div>
		<div class="fr div_close_button_green" style="cursor:pointer;">&nbsp;</div>
	</div>


	<form style="margin:0px;padding:0px;" action="/search/perform" name="form" id="myform" method="post">
	<div class="mid" style="overflow:auto;">
		<div class="textblock  no_bdr" style="width:755px;overflow: auto;">

			<div class="sp5">&nbsp;</div>
			<div style="color:#737373"> 
				~assign var="tab" value=0`	
				~assign var="tab1" value=0`	
				~foreach from=$searchClustersArray key=key item=item`
					~foreach from=$item key=key1 item=item1`
						~if $item1[0] eq 'Heading'`
							~if $tab1 neq 0`
								~if $tab neq 0`
								 <div class="sp12"></div>
							 	~/if`
							 <div class="sp12"></div>
							~/if`
							~assign var='headingId' value="head"|cat:"$tab1"`
							~assign var="tab2" value=0`	
							 <span class="noclass" id="~$headingId`">
								<input id="~$headingId`_0" name="selectedClusterArr[]" value="~$item1[1]`@" ~if $item1[2]` checked ~/if` type="checkbox" class="chbx vam">
								<span class="f_16" style="color:#505050;font-size:16px;">~$key1`
								</span>
							</span>

						 	<div class="sp5"></div>
							~assign var="tab" value=0`	
							~assign var="tab1" value=$tab1+1`	
						~elseif $item1[0] neq 'Show' && $item1[0] gt 0`
							~assign var="tab" value=$tab+1`
							~assign var="tab2" value=$tab2+1`
							<div class="fl">
								<div class="fl">
									<input ~if $headingId` id="~$headingId`_~$tab2`" class="noclass1 chbx vam" ~else` class="chbx vam" ~/if` name="selectedClusterArr[]" value="~$item1[1]`"  ~if $item1[2]` checked ~/if` type="checkbox" style="font-size:14px;"> 
								</div>
								<div style="width: 159px;" class="fl">
									~$key1`(~$item1[0]`)
								</div>
							</div>
							~if $tab eq '4'`
								<div class="sp5"></div>
						 		~assign var="tab" value=0`	
							~/if`
						~/if`

					~/foreach`
				~/foreach`
			</div>

			<div></div><div></div-->
		</div>
	</div>

	<div class="bot txt_center" style="border-top:1px solid #797979">
		<div class="sp5"></div>
		<input type="hidden" name="addRemoveCluster" value="1">
		<input type="hidden" name="searchId" value="~$searchId`">
		<input type="hidden" name="fromMoreLayerCluster" value="1">
		<input type="hidden" name="reverseDpp" value="~$reverseDpp`">
		<input type="hidden" name="searchBasedParam" value="~$searchBasedParam`">
		<input type="hidden" name="NEWSEARCH_CLUSTERING" value="~$originalCluster`">
		<input type="submit" class="btn_view b" value="Refine" name="Submit" style="cursor:pointer;">
	</div>
	</form>
</div>
<script>
        $(".div_close_button_green").click
        (
                function()
                {
                        $.colorbox.close();
                }
        );
        $(".noclass").click(
		function () 
		{
			var id="#"+this.id;
			var idc=id+"_0";
			var num=1,brk=1,id2;

			if($(idc).is(':checked'))
			{
				while(brk)
				{
					var id2 = id+"_"+num;
					if($(id2).length == 0)
						brk=0;
					num++;
					$(id2).prop('checked',true);
				}
			}
			else
			{
				while(brk)
				{
					var id2 = id+"_"+num;
					if($(id2).length == 0)
						brk=0;
					num++;
					$(id2).prop('checked',false);
				}
			}
		}
	);
        $(".noclass1").click(
		function () 
		{
			var id,idc,brk=1,brk2=1,num=1;
			var pos = this.id.indexOf('_');
			id = "#"+this.id.substr(0,pos);
			idc = id+"_0";
			if($("#"+this.id).is(':checked')==false)
				$(idc).prop('checked',false);
			else
			{
				while(brk)
				{
					var id2 = id+"_"+num;
					if($(id2).length == 0)
						brk=0;
					else
					{
						if($(id2).is(':checked')==false)
						{
							brk2=0;
							brk=0;
						}
					}
					num++;
				}
				if(brk2)
					$(idc).prop('checked',true);
			}
		}
	);
</script>
