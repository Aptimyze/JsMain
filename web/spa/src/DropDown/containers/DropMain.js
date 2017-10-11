require ('../style/drop.css')
import React from "react";
import * as CONSTANTS from '../../common/constants/apiConstants'
import Loader from "../../common/components/Loader";

export default class DropMain extends React.Component {

    constructor(props) 
    {
        super();
        this.state = {
            showLoader:false,
            showClear:false,
            showSubmit:true,
            label:"",
            data: []
        }
    }

    componentWillReceiveProps(nextProps)
    {
    
    }

    componentDidMount() 
    {   

    }

    openHam(label,type,data) 
    {
        document.getElementById("mainContent").classList.add("scrollhid"); 
        if(type == "single") {
            this.setState({
                showClear:true,
                showSubmit:true,
                label: label,
                data: data
            })
            document.getElementById("DD_OPTION").style.height = (window.innerHeight-113)+"px";
        } else {
            this.setState({
                showClear:false,
                showSubmit:false,
                label: label,
                data:data
            })
            document.getElementById("DD_OPTION").style.height = (window.innerHeight-63)+"px";
        }
        document.getElementById("dropView").classList.add("z99")
        document.getElementById("dropView").classList.remove("dn")
        document.getElementById("dropView").classList.add("backShow")
        document.getElementById("dropDown").classList.add("dropShow")
    }

    hideHam() 
    {   
        document.getElementById("dropView").classList.remove("z99")
        document.getElementById("dropView").classList.add("dn")
        document.getElementById("dropView").classList.remove("backShow")
        document.getElementById("dropDown").classList.remove("dropShow")
        document.getElementById("mainContent").classList.remove("scrollhid");
    }

    render() 
    {
        let loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        } 
        let dataView = this.state.data.map(function(name, index){
            return(
                <li key={index} id={"searchform_"+name.VALUE} className="fl f17 wid70p pad16 textTruncate padl10 color17 fontlig">{name.LABEL}
                </li>
            );
        });


        let clearView;
        if(this.state.showClear == true) {
            clearView = <div className="fr dispibl">
                <div id="searchform_clear" className="f14 color16 fontlig mt5">Clear</div>
            </div>;
        } 
        let submitView;
        if(this.state.showSubmit) {
            submitView = <div className="fullwid searchsubmit">
                <div className="bg7 white btmo posabs fullwid txtc lh50 searchform" id="searchform_submit">Done</div>
            </div>;
        } 

        let headerView = <div id="headDiv" className="padd22 bg1">
            <div className="fullwid posrel sddkey">
                <div className="txtc white fontthin f19 dispibl ml25p" id="searchform_label">{this.state.label}</div>
                {clearView}
            </div>
        </div>;       

        return (
            <div id="DropMain">
                <div id="dropDown" className="white posfix z105 wid80p fullheight overflowhidden">
                    {headerView}
                    <ul id="DD_OPTION" className="pad20 flowauto">
                        {dataView}
                    </ul>
                    {submitView}
                </div>
                {loaderView}
                
                <div onClick={this.hideHam} id="dropView" className="fullwid darkView fullheight hamView dn"></div>
            </div>
        );
    }
}

