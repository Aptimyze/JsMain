
/*This function is used to enable and disable various payment detail fields depending on Source and Mode of payments in 
  new_entry_paydet_billing.htm, upgrade_paydet_billing.htm and refund_paypart.htm*/

function check()
{
	var docF = define_docF();

	var disable_arr = define_disable_array();

	for(var i=0; i < disable_arr.length; i++)
	{
		if(docF.mode.value == disable_arr[i] && docF.from_source.value != "BANK_TRSFR_CASH")
		{
			docF.cdnum.value="";
			docF.cd_day.value="";
			docF.cd_month.value="";
			docF.cd_year.value="";
			docF.cd_city.value="";
			docF.Bank.value="";
			docF.obank.value="";
			docF.cdnum.disabled=true;
			docF.cd_day.disabled=true;
			docF.cd_month.disabled=true;
			docF.cd_year.disabled=true;
			docF.cd_city.disabled=true;
			docF.Bank.disabled=true;
			docF.obank.disabled=true;
			break;
		}
		else
		{
			if(docF.from_source.value != "BANK_TRSFR_CASH")
				docF.cdnum.disabled=false;
			docF.cd_day.disabled=false;
			docF.cd_month.disabled=false;
			docF.cd_year.disabled=false;
			docF.cd_city.disabled=false;
			docF.Bank.disabled=false;
			docF.obank.disabled=false;
		}
	}
}

/*This function is used to validate various payment detail fields depending on Source and Mode of payments in 
  new_entry_paydet_billing.htm, upgrade_paydet_billing.htm and refund_paypart.htm*/
function cheque_fields_validation()
{
	var docF = define_docF();

	var disable_arr = define_disable_array();
	var check_fields;
	for(var i=0; i < disable_arr.length; i++)
	{
		if(docF.mode.value == disable_arr[i])
		{
			check_fields = 0;
			break;
		}
		else
			check_fields = 1;
	}
	if(check_fields || docF.from_source.value == "BANK_TRSFR_CASH")
	{
		if(docF.cdnum.value.length=="0" && docF.from_source.value != "BANK_TRSFR_CASH")
		{
			alert('Please fill the  Cheque/DD Number/CC Offline Transaction No field');
			return 1;
		}
		if(docF.mode.value=="CHEQUE" || docF.mode.value=="DD" || docF.from_source.value == "BANK_TRSFR_CASH")
		{
			if(docF.cd_day.value.length=="0" || docF.cd_month.value.length=="0" || docF.cd_year.value.length=="0")
			{
				alert('Please fill the  Cheque/DD Date field');
				return 1;
			}
			if(docF.cd_city.value.length=="0")
			{
				alert('Please fill the  Cheque/DD City field');
				return 1;
			}
			if(docF.Bank.value=="Other" && docF.obank.value.length==0)
			{
				alert('Please select a Bank Name or fill the If Other(bank) field');
				return 1;
			}
		}
	}
	else
		return 0;
}

/*This function is used to change the label displayed payment detail page depending on Source of payment in 
  new_entry_paydet_billing.htm, upgrade_paydet_billing.htm and refund_paypart.htm*/
function change_label_value()
{
	var docF = define_docF();

	var source_arr_value = define_source_val_array();
	var source_arr_label = define_source_label_array();

	for(var i=0; i < source_arr_value.length; i++)
	{
		if(docF.from_source.value == source_arr_value[i])
		{
			docF.transaction_number.disabled=false;
			docF.transaction_number.value = source_arr_label[i];
			break;
		}
		else
		{
			docF.transaction_number.value = "";
			docF.transaction_number.disabled=true;
		}
	}
}

/*This function is used to clear the textbox when the focus is on the textbox in new_entry_paydet_billing.htm, 
  upgrade_paydet_billing.htm and refund_paypart.htm*/
function clear_box(blur)
{
	var docF = define_docF();

	var to_clear = docF.transaction_number.value;
	var source_arr_label = define_source_label_array();
	if(!blur)
	{
		for(var i=0; i < source_arr_label.length; i++)
		{
			if(source_arr_label[i] == to_clear)
				docF.transaction_number.value = "";
		}
	}
	if(to_clear.length==0)
		change_label_value();
}

/*This function is used to validate transaction number field in payment detail page in new_entry_paydet_billing.htm,
  upgrade_paydet_billing.htm and refund_paypart.htm*/
function check_trans_num()
{
	var docF = define_docF();

	var source_arr_value = define_source_val_array();
	var source_arr_label = define_source_label_array();

	for(var i=0;i < source_arr_value.length;i++)
	{
		if(source_arr_value[i] == docF.from_source.value && source_arr_label[i] == docF.transaction_number.value)
		{
			var alert_str = "Please enter " + source_arr_label[i];
			alert(alert_str);
			return 1;
		}
	}
	return 0; 
}

/*This function defines values for which transaction number fields should get enabled.*/
function define_source_val_array()
{
	var source_arr_value = new Array();

	source_arr_value[0] = "EB_CASH";
	source_arr_value[1] = "EB_CHEQUE";
	source_arr_value[2] = "ONLINE";
	source_arr_value[3] = "BANK_TRSFR_ONLINE";
	source_arr_value[4] = "CCOFFLINE";
	source_arr_value[5] = "TT";
	source_arr_value[6] = "IVR";
	source_arr_value[7] = "CASH";
	source_arr_value[8] = "GHAR_PAY_CASH";
	source_arr_value[9] = "PayTM_ON_DELIVERY";

	return source_arr_value;
}

/*This function defines values which is initally displayed in transaction number field depending on source of payment.*/
function define_source_label_array()
{
	var source_arr_label = new Array();

	source_arr_label[0] = "Easy Bill Reference Number";
	source_arr_label[1] = "Easy Bill Reference Number";
	source_arr_label[2] = "Order ID";
	source_arr_label[3] = "Transaction Number";
	source_arr_label[4] = "Approval Code";
	source_arr_label[5] = "Transaction Number";
	source_arr_label[6] = "IVR Code";
	source_arr_label[7] = "Cash Receipt Number";
	source_arr_label[8] = "Ghar Pay Cash Receipt Number";
	source_arr_label[9] = "Paytm Receipt Number";
	return source_arr_label;
}

/*This function defines values for which certain payment detail fields gets disabled. */
function define_disable_array()
{
	var disable_arr = new Array();

	disable_arr[0] = "CASH";
	disable_arr[1] = "ONLINE";
	disable_arr[2] = "BANK_TRSFR_ONLINE";
	disable_arr[3] = "CCOFFLINE";
	disable_arr[4] = "TT";
	disable_arr[5] = "IVR";
	disable_arr[6] = "GHAR_PAY_CASH";
	disable_arr[7] = "PayTM_ON_DELIVERY";

	return disable_arr;
}

function total_amount_topay(tax_rate)
{
	var docF = define_docF();

	var discount_value;
	if(docF.discount.value.length != 0)
		discount_value  = parseInt(docF.discount.value);
	else
		discount_value  = 0;
	
	var final_amount = 0;
	var amount;
	var id;
	for(var i=0;i<services_array.length;i++)
	{
		id = services_array[i] + "_price";
        if(document.getElementById(id) != null){
            amount = parseInt(document.getElementById(id).value);
            if(!isNaN(amount))
                final_amount += amount;
        }
	}
		
	var net_pay = final_amount - discount_value;
														     
	if(docF.curtype.value=="0")
	{
		if(net_pay < 0)
			net_pay=0;
		docF.tobepaid.value = net_pay;
	}
	else
	{
		if(net_pay < 0)
			net_pay=0;
		docF.tobepaid.value = net_pay;
	}
}

/*This function defines docF depending on form from which this js is called.*/
function define_docF()
{
	if(document.pg2_frm)
		docF = document.pg2_frm;
	else if(document.ren_pg2)
		docF = document.ren_pg2;
	else if(document.pg1_frm)
		docF = document.pg1_frm;
	else if(document.frm)
		docF = document.frm;

	return docF;
}
