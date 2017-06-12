import React from 'react';

class FamilyTab extends React.Component {
	constructor(props) {
        super();
        console.log("family",props);
        props.family.sibling_info = props.family.sibling_info.replace(/(?:\n)/g, '<br />');
    }
    render() {

    	var myfamily;
    	if(this.props.family.myfamily)
    	{
    		myfamily = <div className="fontlig pad20 wordBreak vpro_lineHeight" id="vpro_myfamily">{this.props.family.myfamily}</div>;
    	} else 
    	{
    		myfamily = <div class="hgt10"></div>;
    	}
    	
    	var family_bg;
    	if(this.props.family.family_bg)
    	{
    		family_bg = <div>
    			<div className="f12 color1">Family Background</div>
            	<div className="fontlig pb15" id="vpro_family_bg" >
            		{this.props.family.family_bg}
            	</div>
    		</div>;
    	}

    	var family_income;
    	if(this.props.family.family_income)
    	{
    		family_income = <div>
    			<div className="f12 color1">Family Income</div>
            	<div className="fontlig pb15" id="vpro_family_income" >
            		{this.props.family.family_income}
            	</div>
    		</div>;
    	}

    	var native_place;
    	if(this.props.family.native_place)
    	{
    		native_place = <div>
    			<div className="f12 color1">Family based out of</div>
            	<div className="fontlig pb15" id="vpro_native_place" >
            		{this.props.family.native_place}
            	</div>
    		</div>;
    	}

		var father_occ;
    	if(this.props.family.father_occ)
    	{
    		father_occ = <div>
    			<div className="f12 color1">Father is</div>
            	<div className="fontlig pb15" id="vpro_father_occ" >
            		{this.props.family.father_occ}
            	</div>
    		</div>;
    	}

    	var mother_occ;
    	if(this.props.family.mother_occ)
    	{
    		mother_occ = <div>
    			<div className="f12 color1">Mother is</div>
            	<div className="fontlig pb15" id="vpro_mother_occ" >
            		{this.props.family.mother_occ}
            	</div>
    		</div>;
    	}

    	var sibling_info;
    	if(this.props.family.sibling_info)
    	{
    		sibling_info = <div>
    			<div className="f12 color1">Brother / Sister</div>
            	<div className="fontlig pb15" id="vpro_sibling_info">
            		<div dangerouslySetInnerHTML={{__html: this.props.family.sibling_info}} />
            	</div>
    		</div>;
    	}

    	var sub_caste;
    	if(this.props.family.sub_caste)
    	{
    		sub_caste = <div>
    			<div className="f12 color1">Sub Caste</div>
            	<div className="fontlig pb15" id="vpro_sub_caste" >
            		{this.props.family.sub_caste}
            	</div>
    		</div>;
    	}

    	var gothra;
    	if(this.props.family.gothra)
    	{
    		gothra = <div>
    			<div className="f12 color1">Gothra</div>
            	<div className="fontlig pb15" id="vpro_gothra" >
            		{this.props.family.gothra}
            	</div>
    		</div>;
    	}

    	var caste;
    	if(this.props.family.caste)
    	{
    		caste = <div>
    			<div className="f12 color1">Caste</div>
            	<div className="fontlig pb15" id="vpro_caste" >
            		{this.props.family.caste}
            	</div>
    		</div>;
    	}

    	var mathab;
    	if(this.props.family.mathab)
    	{
    		mathab = <div>
    			<div className="f12 color1">Ma'thab</div>
            	<div className="fontlig pb15" id="vpro_mathab" >
            		{this.props.family.mathab}
            	</div>
    		</div>;
    	}

    	var diocese;
    	if(this.props.family.diocese)
    	{
    		diocese = <div>
    			<div className="f12 color1">Diocese</div>
            	<div className="fontlig pb15" id="vpro_diocese" >
            		{this.props.family.diocese}
            	</div>
    		</div>;
    	}

    	var sect;
    	if(this.props.family.sect)
    	{
    		sect = <div>
    			<div className="f12 color1">Sect</div>
            	<div className="fontlig pb15" id="vpro_sect" >
            		{this.props.family.sect}
            	</div>
    		</div>;
    	}
    	

    	var FamilyData;
    	if(this.props.family.myfamily || this.props.family.family_bg || this.props.family.family_income || this.props.family.father_occ || this.props.family.mother_occ || this.props.family.sibling_info || this.props.family.sub_caste || this.props.family.gothra || this.props.family.native_place || this.props.family.caste || this.props.family.mathab || this.props.family.diocese || this.props.family.sect || this.props.family.living)
    	{
    		FamilyData = <div>
    			{myfamily}
    			{family_bg}
    			{family_income}
    			{native_place}
    			{father_occ}
    			{mother_occ}
    			{sibling_info}
    			{sub_caste}
    			{gothra}
    			{caste}
    			{mathab}
    			{diocese}
    			{sect}
    			<div className="fontlig color1 pb15" id="vpro_living">{this.props.family.living}</div>
    		</div>;
    	} else
    	{
    		FamilyData = <div>
    			<div className="hgt10"></div>
    			<div className="fontlig color1 f14 pb10 txtc" id="vpro_no_family_detail">This user has not provided family details yet</div>
    		</div>;	
    	}

    	return(
    		<div id="FamilyTab" className="dn pad5 bg4 fontlig color3 clearfix f14">
				{FamilyData}
			</div>
    	);
    }
}


export default FamilyTab;
