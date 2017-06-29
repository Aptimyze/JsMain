import React from 'react';
import { Helmet } from "react-helmet";
var data = require('./../constants/MetaTags.json');
import PropTypes from 'prop-types';


const MetaTagComponents = (props) => {
	return (<Helmet>
			if (data[props.page]['title'] !== 'undefined' )
			{
	        	<title>{data[props.page]['title']}</title>
			}
			if (data[props.page]['description'] !== 'undefined' )
			{
	        	<meta name="description" content={data[props.page]['description']}/>
	        }
	        if ( typeof data[props.page]['keywords'] !== 'undefined')
	        {
		        <meta name="keywords" content={data[props.page]['keywords']}/>
	        }
	    </Helmet>);
}

MetaTagComponents.propTypes = {
   page: PropTypes.string.isRequired,
}

export default MetaTagComponents;