import React from "react";

export default class Inspectlet extends React.Component{
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    const s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.innerHTML = "(function() {\
				window.__insp = window.__insp || [];\
        __insp.push(['wid', 2073846614]);\
        __insp.push(['tagSession', {unique_id: '123456'}]);\
				var ldinsp = function(){\
				if(typeof window.__inspld != 'undefined') return; window.__inspld = 1; var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = 'inspsync'; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js?wid=2073846614&r=' + Math.floor(new Date().getTime()/3600000); var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); };\
				setTimeout(ldinsp, 0);\
				})();";
    document.body.appendChild(s);
  }

  render() {
    return(<div></div>);
  }
}

