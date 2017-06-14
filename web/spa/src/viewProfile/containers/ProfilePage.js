require ('../style/profile.css')
import React from "react";
import {connect} from "react-redux";
import Loader from "../../common/components/Loader";
import AppPromo from "../../common/components/AppPromo";
import TopError from "../../common/components/TopError";
import PhotoView from "../../common/components/PhotoView";
import {profileDetail} from "../actions/ProfileActions";
import AboutTab from"../components/AboutTab";
import FamilyTab from"../components/FamilyTab";
import DppTab from"../components/DppTab";

class ProfilePage extends React.Component {

    constructor(props) {
        super();
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false,
            tabArray: ["About","Family","Dpp"],
            dataLoaded: false,
            picUrl: "http://test.jeevansathi.com/images/picture/450x600_m.png?noPhoto"
        };
        props.showProfile();   
    }

    componentDidMount() {
        let _this = this;
        document.getElementById("ProfilePage").style.height = window.innerHeight+"px"; 
        document.getElementById("photoParent").style.height = window.innerWidth +"px"; 
    } 
    
    componentWillReceiveProps(nextProps)
    {
        console.log("next",nextProps);
        this.setState ({
            dataLoaded : true,
            picUrl: nextProps.pic.url
        });  
        if(nextProps.appPromotion == true) {
            this.setState ({
                showPromo : true
            });   
        }
        window.addEventListener('scroll', (event) => {
            let tabElem = document.getElementById("tab");
            if(tabElem.getBoundingClientRect().top < 0 && !tabElem.classList.contains("posFixTop")) {
                tabElem.classList.add("posFixTop");
            } 
            if(document.getElementById("photoParent").getBoundingClientRect().bottom > 30 && tabElem.classList.contains("posFixTop")) {
                tabElem.classList.remove("posFixTop");
            }
        });
    }

    showError(inputString) {
        let _this = this;
        this.setState ({
                insertError : true,
                errorMessage : inputString
        })    
        setTimeout(function(){ 
            _this.setState ({
                insertError : false,
                errorMessage : ""
            })     
        }, this.state.timeToHide+100);  
    }

    removePromoLayer() {
        this.setState ({
            showPromo : false
        });  
        document.getElementById("mainContent").classList.remove("ham_b100");
    }

    showTab(elem) {
        for(let i=0; i<this.state.tabArray.length; i++) {
            document.getElementById(this.state.tabArray[i]+"Header").classList.remove("vpro_selectTab");
            document.getElementById(this.state.tabArray[i]+"Tab").classList.add("dn");
        }
        document.getElementById(elem+"Header").classList.add("vpro_selectTab");
        document.getElementById(elem+"Tab").classList.remove("dn");

    }

    render() {
        var errorView;
        if(this.state.insertError)          
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }
        
        var loaderView;
        if(this.state.showLoader)          
        {
          loaderView = <Loader show="page"></Loader>;
        }

        var promoView;
        if(this.state.showPromo)
        {
            promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }

        var AboutView,FamilyView,DppView,Header = "View Profile";
        if(this.state.dataLoaded)
        {   
            AboutView = <AboutTab life={this.props.LifestyleInfo} about={this.props.AboutInfo}></AboutTab>;
            FamilyView = <FamilyTab family={this.props.FamilyInfo}></FamilyTab>;
            
            if(this.props.dpp_Ticks) 
            {
                DppView = <DppTab about={this.props.AboutInfo} dpp_Ticks={this.props.dpp_Ticks}  dpp={this.props.DppInfo}></DppTab>;    
            } else
            {
                DppView = <DppTab about={this.props.AboutInfo}  dpp={this.props.DppInfo}></DppTab>;
            }

            if(this.props.AboutInfo.name_of_user) 
            {
                Header = this.props.AboutInfo.name_of_user;
            } else
            {
                 Header = this.props.AboutInfo.username;
            }

        }

        return (
            <div id="ProfilePage">
                {promoView}
                {errorView}
                {loaderView}
                <div className="fullheight" id="mainContent">
                    <div id="tabHeader" className="fullwid bg1">
                        <div className="padd22 txtc">
                            <div className="posrel">
                                <div className="posabs ot_pos1"> 
                                    <i id="backBtn" className="mainsp arow2"></i>
                                </div>
                                <div className="fontthin f19 white headerOverflow" id="vpro_headerTitle">
                                    {Header} 
                                </div>
                                <div className="posabs vpro_pos1">
                                    <i className="vpro_sprite vpro_comHisIcon cursp"></i>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div id="photoParent" className="fullwid scrollhid">
                        <PhotoView src={this.state.picUrl}></PhotoView> 
                    </div>
                    <div id="tab" className="fullwid tabBckImage posabs mtn39">
                        <div id="tabContent" className="fullwid bg2 vpro_pad5 fontlig posrel">
                            <div id="AboutHeader" onClick={() => this.showTab("About")} className="dispibl wid29p f12 vpro_selectTab">About  him </div>
                            <div id="FamilyHeader" onClick={() => this.showTab("Family")} className="dispibl wid40p txtc f12 opa70">Family</div>
                            <div id="DppHeader" onClick={() => this.showTab("Dpp")}  className="dispibl wid30p txtr f12 opa70">Looking for</div>
                            <div className="clr"></div>
                        </div>
                    </div>
                    {AboutView}
                    {FamilyView}
                    {DppView}
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       responseMessage: state.ProfileReducer.responseMessage,
       AboutInfo: state.ProfileReducer.aboutInfo,
       FamilyInfo: state.ProfileReducer.familyInfo,
       DppInfo: state.ProfileReducer.dppInfo,
       appPromotion : state.ProfileReducer.appPromotion,
       pic: state.ProfileReducer.pic,
       LifestyleInfo: state.ProfileReducer.lifestyle,
       dpp_Ticks: state.ProfileReducer.dpp_Ticks
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        showProfile: () => {
            dispatch(profileDetail());
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(ProfilePage)
