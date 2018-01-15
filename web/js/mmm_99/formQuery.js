function validate()
{
   	var docF=document.form1;

	if(docF.mailer_id.value == '')
	{
		alert("Please select a mailer to form query for");
		docF.mailer_id.focus();
		return false;
	}
		
	var buy = document.getElementsByName('buyer_preference_buy');
	var rent = document.getElementsByName('buyer_preference_rent');
	var lease = document.getElementsByName('buyer_preference_lease');
	var pg = document.getElementsByName('buyer_preference_pg');
	var sub_promo = document.getElementById('sub_promo');
	var sub_partners = document.getElementById('sub_partners');
	
	if(sub_promo.checked == false && sub_partners.checked == false){
		alert("You need to select either \"Susbscribed for Promotional Mails\" or \"Subscribed for Mailers From Our Partners\"");
		return false;
	}
	
	if((buy[0].checked==false) && (rent[0].checked==false) && (lease[0].checked==false) && (pg[0].checked==false))
	{
		alert('You need to select atlease one preference to proceed');
		return false;
	}

	return true;	
}

function CheckDD(value,type){
	var budget_max=parseInt(document.getElementById('buy_budget_max').value);
	var budget_min=parseInt(document.getElementById('buy_budget_min').value);
	if(type=='MIN'){
		if(value==499999){
			document.getElementById('buy_budget_max').disabled=true;
			document.getElementById('buy_budget_max').selectedIndex=0;
		}
		else if(value>499999 || value==0){
			document.getElementById('buy_budget_max').disabled=false;
			if(budget_max>0){
				if(budget_max<value){
					alert('Min price should be less than Max Price');
					document.getElementById('buy_budget_min').selectedIndex=0;
				}
			}
		}
	}
	
	
	if(type=='MAX'){
		if(value==499999){
			document.getElementById('buy_budget_min').disabled=true;
			document.getElementById('buy_budget_min').selectedIndex=0;
		}
		else if(value>499999 || value==0){
			document.getElementById('buy_budget_min').disabled=false;
			if(budget_min>0){
				if(budget_min>value){
					alert('Max price should be greater than Min Price');
					document.getElementById('buy_budget_max').selectedIndex=0;
				}
			}
		}
	}
	
}

function BuyerPreferenceSelected(obj)
{
	var docF=document.form1;
	var buy = document.getElementsByName('buyer_preference_buy');

	if(buy[0].checked==true)
	{
		document.getElementById('buy_budget_min').disabled=false;
		document.getElementById('buy_budget_max').disabled=false;
	}
	if(buy[0].checked==false)
	{
		document.getElementById('buy_budget_min').disabled=true;
		document.getElementById('buy_budget_max').disabled=true;
		document.getElementById('buy_budget_max').selectedIndex=0;
		document.getElementById('buy_budget_min').selectedIndex=0;
	}
}

function SellerPreferenceSelected(obj)
{
	var docF=document.form1;
	if(obj.checked)
	{
	        if(obj.name=="seller_preference_all")
	        {
	                docF.seller_preference_sell.checked=false;
	                docF.seller_preference_rent.checked=false;
	                docF.seller_preference_lease.checked=false;
	                docF.seller_preference_pg.checked=false;
	        }
	        else
	        {
	                docF.seller_preference_all.checked=false;
	        }
	}
	var all = document.getElementsByName('seller_preference_all');
	var sell = document.getElementsByName('seller_preference_sell');
	var rent = document.getElementsByName('seller_preference_rent');
	var lease = document.getElementsByName('seller_preference_lease');
	var pg = document.getElementsByName('seller_preference_pg');
	
	if(sell[0].checked==false && rent[0].checked==false && lease[0].checked==false && pg[0].checked==false)
	{
	        all[0].checked=true;
	}
}


function RecipientsSelected()
{
	var docF = document.form1;
	var val = docF.recipient_type;
	var v1 = document.getElementById('recipient_type_hidden').value;
	var v2 = document.getElementById('register_city_radio_hidden').value;
	var v3 = document.getElementById('buyer_city_radio_hidden').value;
	var v4 = document.getElementById('seller_city_radio_hidden').value;

	if(v1 != '' && v1 == 'S')
		val[0].checked=true;
	if(v1 != '' && v1 == 'B')
		val[1].checked=true;
	if(val[0].checked==true)	//Only sellers selected, hide buyer criteria and unhide seller criteria
	{
		if(v1 != '')
		{
			if(v2 == 'CR'){
				document.getElementById('register_city_region').disabled = false;
				document.getElementById('register_city_region_radio').disabled = false;
				document.getElementById('register_city_region_radio').checked=true;		
				document.getElementById('register_city').disabled = true;

			}
			if(v2 == 'C'){
				document.getElementById('register_city').disabled = false;
                                document.getElementById('register_city_radio').disabled = false;
				document.getElementById('register_city_radio').checked = true;
                                document.getElementById('register_city_region').disabled = true;

			}
			if(v2==''){
				document.getElementById('register_city_region').disabled = false;
                                document.getElementById('register_city_region_radio').disabled = false;
                                document.getElementById('register_city_region_radio').checked=true;
                                document.getElementById('register_city').disabled = true;
				document.getElementById('register_city').disabled = false;
                                document.getElementById('register_city_radio').disabled = false;
                                document.getElementById('register_city_radio').checked = true;
                                document.getElementById('register_city_region').disabled = true;

			}
			if(v4 == 'SR')
			{
				document.getElementById('seller_city_region').disabled = false;
                                document.getElementById('seller_city_region_radio').disabled = false;
                                document.getElementById('seller_city_region_radio').checked=true;
                                document.getElementById('seller_city').disabled = true;
			}
			if(v4 == 'S')
			{
				document.getElementById('seller_city').disabled = false;
                                document.getElementById('seller_city_radio').disabled = false;
                                document.getElementById('seller_city_radio').checked=true;
                                document.getElementById('seller_city_region').disabled = true;

			}

		}
		else
		{
			document.getElementById('register_city_region').disabled = true;
			document.getElementById('register_city_region_radio').disabled = false;
			document.getElementById('register_city').disabled=false;
			document.getElementById('register_city_radio').disabled = false;
			document.getElementById('register_city_radio').checked = true;
			document.getElementById('seller_city_region_radio').disabled = false;
			document.getElementById('seller_city_region').disabled = true;
			document.getElementById('seller_city_radio').checked = true;
			document.getElementById('seller_city_radio').disabled = false;
			document.getElementById('seller_city').disabled = false;
				

		}

        var buyer = document.getElementById('buyer_criteria');
        buyer_cells = buyer.getElementsByTagName('tr');
        for(i=0;i<buyer_cells.length;i++)
        {
                buyer_cells[i].style.display='none';
        }

        var seller = document.getElementById('seller_criteria');
        seller_cells = seller.getElementsByTagName('tr');
        for(i=0;i<seller_cells.length;i++)
        {
                seller_cells[i].style.display='';
        }
		ResComSelected();
	}
	else				//Only buyers selected, hide seller criteria and unhide buyer criteria
	{

		if(v1 != '')
                {
			if(v2 == 'CR'){
                                document.getElementById('register_city_region').disabled = true;
                                document.getElementById('register_city_region_radio').disabled = true;
                                document.getElementById('register_city_region_radio').checked=false;
                                document.getElementById('register_city').disabled = true;
                                document.getElementById('register_city_radio').disabled = true;

                        }
                        if(v2 == 'C'){
                                document.getElementById('register_city').disabled = true;
                                document.getElementById('register_city_radio').disabled = true;
                                document.getElementById('register_city_radio').checked = false;
                                document.getElementById('register_city_region').disabled = true;
                                document.getElementById('register_city_region_radio').disabled = true;

                        }
			if(v2 == '')
			{
				document.getElementById('register_city_region').disabled = true;
                                document.getElementById('register_city_region_radio').disabled = true;
                                document.getElementById('register_city_region_radio').checked=false;
                                document.getElementById('register_city').disabled = true;
                                document.getElementById('register_city_radio').disabled = true;
				document.getElementById('register_city').disabled = true;
                                document.getElementById('register_city_radio').disabled = true;
                                document.getElementById('register_city_radio').checked = false;
                                document.getElementById('register_city_region').disabled = true;
                                document.getElementById('register_city_region_radio').disabled = true;
			}
                        if(v3 == 'BR')
                        {
                                document.getElementById('buyer_city_region').disabled = false;
                                document.getElementById('buyer_city_region_radio').disabled = false;
                                document.getElementById('buyer_city_region_radio').checked=true;
                                document.getElementById('buyer_city').disabled = true;
                        }
                        if(v3 == 'B')
                        {
                                document.getElementById('buyer_city').disabled = false;
                                document.getElementById('buyer_city_radio').disabled = false;
                                document.getElementById('buyer_city_radio').checked=true;
                                document.getElementById('buyer_city_region').disabled = true;

                        }


                }
                else
                {
                       
			document.getElementById('register_city_radio').disabled='true';
                	document.getElementById('register_city_region_radio').disabled='true';
			document.getElementById('register_city_region').disabled = true;
                        document.getElementById('register_city').disabled=true;  
			document.getElementById('buyer_city_region').disabled=true;
			document.getElementById('buyer_city_region_radio').enabled=true;
			document.getElementById('buyer_city_radio').enabled=true;
			document.getElementById('buyer_city_radio').checked=true;
			document.getElementById('buyer_city').enabled=true;

                }

       	var buyer = document.getElementById('buyer_criteria');
       	buyer_cells = buyer.getElementsByTagName('tr');
       	for(i=0;i<buyer_cells.length;i++)
       	{
               buyer_cells[i].style.display='';
       	}

       	var seller = document.getElementById('seller_criteria');
       	seller_cells = seller.getElementsByTagName('tr');
       	for(i=0;i<seller_cells.length;i++)
       	{
               seller_cells[i].style.display='none';
       	}
		ResComSelected();
	}
}

function ResComSelected()
{
		var docF = document.form1;
		if(docF.recipient_type[1].checked)	//buyers
		{
			var res_com = document.getElementsByName('buyer_rescom');
			var rent = docF.buyer_preference_rent;
			var lease = docF.buyer_preference_lease;
			var pg = docF.buyer_preference_pg;
		}	
		else if(docF.recipient_type[0].checked)	//sellers
		{
			var res_com = document.getElementsByName('seller_rescom');
			var rent = docF.seller_preference_rent;
			var lease = docF.seller_preference_lease;
			var pg = docF.seller_preference_pg;
		}
		if(res_com[0].checked)	//both selected, display all
		{
			rent.disabled=false;
			lease.disabled=false;
			pg.disabled=false;

		}
	
		if(res_com[1].checked)  //Residential selected
		{
			rent.disabled=false;
			lease.disabled=true;
			pg.disabled=false;
		}
		if(res_com[2].checked)  //Commercial selected
		{
			rent.disabled=true;
			lease.disabled=false;
			pg.disabled=true;
	        }
}

function SellerClassSelected()
{
	var docF = document.form1;
	if(docF.seller_class_agent.checked==false && docF.seller_class_builder.checked==false && docF.seller_class_owner.checked==false)
	{
		alert("Please select atleast one of Agents, Builders and Owners");
		docF.seller_class_agent.checked=true;
		docF.seller_class_builder.checked=true;
		docF.seller_class_owner.checked=true;	
	}
}

function SelectDefaults()
{
	var docF = document.form1;

	docF.screening[1].checked=true;
	docF.activated[1].checked=true;
}

function handleRegionDD(val){
	var docF = document.form1;
	var radio = val+'_radio';

	document.getElementById(radio).checked=false;
	var lastChars = val.substr(val.length - 7);
	if(lastChars != '_region')
	{
		first = val;
		second = val + '_region';

	}
	else{
		first = val;
		second = val.slice(0,val.length-7);

	}
	document.getElementById(first).disabled = true;
	document.getElementById(second).disabled = false;	

}

