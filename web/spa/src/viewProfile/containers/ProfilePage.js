require ('../style/profile.css')

import React from "react";
import {connect} from "react-redux";
import {Link} from "react-router-dom";

import Loader from "../../common/components/Loader";
import {getParameterByName} from '../../common/components/UrlDecoder';
import AppPromo from "../../common/components/AppPromo";
import TopError from "../../common/components/TopError";
import PhotoView from "../../common/containers/PhotoView";
import AboutTab from"../components/AboutTab";
import FamilyTab from"../components/FamilyTab";
import DppTab from"../components/DppTab";
import CommHistory from "./CommHistory";
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";
import {getCookie} from '../../common/components/CookieHelper';
import GA from "../../common/components/GA"

class ProfilePage extends React.Component {

    constructor(props) {
        let profilechecksum = getParameterByName(window.location.href,"profilechecksum");
        super();
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false,
            tabArray: ["About","Family","Dpp"],
            dataLoaded: false,
            showHistory: false,
            profilechecksum: profilechecksum || "f0acc30e3f8794558209b01c0bee23d3i6467012",
            gender: "M",
            defaultPicData: ""
        };
        if ( profilechecksum )
        {
           this.state.profilechecksum = profilechecksum;
        }


        if(localStorage.getItem('GENDER') == "F") {
            this.state.gender =  "F";
        }
        props.showProfile(this.state.profilechecksum);
    }
    componentWillMount() {

    }

    componentDidMount() {
        let _this = this;
        document.getElementById("ProfilePage").style.height = window.innerHeight+"px";
        document.getElementById("photoParent").style.height = window.innerWidth +"px";
        var backHeight = window.innerHeight - document.getElementById("tabHeader").clientHeight - document.getElementById("photoParent").clientHeight -26;
        document.getElementById("animated-background").style.height = backHeight + "px";
        if(this.state.gender == "M") {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto"
            })
        } else {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"
            })
        }
    }

    componentWillReceiveProps(nextProps)
    {   let picData;
        if(!nextProps.pic) {
            if(this.state.gender == "M") {
               picData = {url: "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto"};
            } else {
                picData = {url: "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"};
            }
        } else {
            picData = nextProps.pic;
        }
        this.setState ({
            dataLoaded : true,
            pic: picData
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
        var _this = this;
        //calling tracking event
        /*setTimeout(function(){
            console.log("mm",_this.refs.GAchild.trackJsEventGA("jsms","new","2"))
        },3000); 
        */
        
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
    initHistory() {
        this.setState({
            showHistory:true
        });
    }
    closeHistoryTab() {
        this.setState({
            showHistory:false
        });
    }

    imageLoaded() {
        document.getElementById("showAbout").classList.remove("dn");
        document.getElementById("showPhoto").classList.remove("dn");
        document.getElementById("preLoader").classList.add("dn");
    }

    goBack() {
        this.props.history.goBack();
    }

    render() {
        var himHer = "him",photoViewTemp,AboutViewTemp;
        if(this.state.gender == "M") {
            himHer = "her";
            photoViewTemp = <img src = "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto" />;

        } else {
            photoViewTemp = <img src = "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto" />;

        }

        AboutViewTemp = <div id="preLoader" className="timeline-wrapper">
            <div className="timeline-item">
                <div id="animated-background" className="animated-background">
                    <div className="background-masker div1"></div>
                    <div className="background-masker div2"></div>
                    <div className="background-masker div3"></div>
                    <div className="background-masker div4"></div>
                    <div className="background-masker div5"></div>
                    <div className="background-masker div6"></div>
                    <div className="background-masker div7"></div>
                    <div className="background-masker div8"></div>
                    <div className="background-masker div9"></div>
                    <div className="background-masker div10"></div>
                    <div className="background-masker div11"></div>
                    <div className="background-masker div12"></div>
                    <div className="background-masker div13"></div>
                    <div className="background-masker div14"></div>
                    <div className="background-masker div15"></div>
                    <div className="background-masker div16"></div>
                    <div className="background-masker div17"></div>
                </div>
            </div>
        </div>;

        



        var historyIcon;
        if(getCookie("AUTHCHECKSUM")) {
            historyIcon = <div id="historyIcon" onClick={() => this.initHistory()} className="posabs vpro_pos1">
                <i className="vpro_sprite vpro_comHisIcon cursp"></i>
            </div>;
        }
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

        var historyView;
        if(this.state.showHistory) {
            historyView = <CommHistory closeHistory={()=>this.closeHistoryTab()} profileId={this.props.profileId} username={this.props.AboutInfo.username} profileThumbNailUrl={this.props.AboutInfo.thumbnailPic} ></CommHistory>
        }

        var AboutView,FamilyView,DppView,Header = "View Profile",photoView;
        if(this.state.dataLoaded)
        {
            photoView = <div id="showPhoto" className="dn"><PhotoView defaultPhoto={this.state.defaultPicData} imageLoaded={this.imageLoaded}  verification_status={this.props.AboutInfo.verification_status} profilechecksum={this.state.profilechecksum} picData={this.state.pic}  /></div>;

            if(this.props.AboutInfo.name_of_user)
            {
                Header = this.props.AboutInfo.name_of_user;
            } else
            {
                 Header = this.props.AboutInfo.username;
            }
            AboutView = <div id="showAbout" className="dn"><AboutTab show_gunascore={this.props.show_gunascore} profilechecksum={this.state.profilechecksum} life={this.props.LifestyleInfo} about={this.props.AboutInfo}></AboutTab></div>;

            FamilyView = <FamilyTab family={this.props.FamilyInfo}></FamilyTab>;

            DppView = <DppTab about={this.props.AboutInfo} dpp_Ticks={this.props.dpp_Ticks}  dpp={this.props.DppInfo}></DppTab>;
            setTimeout(function(){
                document.getElementById("showAbout").classList.remove("dn");
            }, 100);
            
        
        }

        return (
            <div id="ProfilePage">
                <GA ref="GAchild" />
                {promoView}
                {errorView}
                {loaderView}
                {historyView}
                <div className="fullheight bg4" id="mainContent">
                    <div id="tabHeader" className="fullwid bg1">
                        <div className="padd22 txtc">
                            <div className="posrel">
                                <div onClick={() => this.goBack()} className="posabs ot_pos1">
                                    <i id="backBtn" className="mainsp arow2"></i>
                                </div>
                                <div className="fontthin f19 white headerOverflow" id="vpro_headerTitle">
                                    {Header}
                                </div>
                                {historyIcon}
                            </div>
                        </div>
                    </div>
                    <Link to={"/social/MobilePhotoAlbum?profilechecksum="+this.state.profilechecksum}>
                        <div id="photoParent" className="fullwid scrollhid">
                            {photoView}
                            {photoViewTemp}
                        </div>
                    </Link>
                    <div id="tab" className="fullwid tabBckImage posabs mtn39">
                        <div id="tabContent" className="fullwid bg2 vpro_pad5 fontlig posrel">
                            <div id="AboutHeader" onClick={() => this.showTab("About")} className="dispibl wid29p f12 vpro_selectTab">About  {himHer} </div>
                            <div id="FamilyHeader" onClick={() => this.showTab("Family")} className="dispibl wid40p txtc f12 opa70">Family</div>
                            <div id="DppHeader" onClick={() => this.showTab("Dpp")}  className="dispibl wid30p txtr f12 opa70">Looking for</div>
                            <div className="clr"></div>
                        </div>
                    </div>
                    {AboutView}
                    {AboutViewTemp}
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
       dpp_Ticks: state.ProfileReducer.dpp_Ticks,
       profileId: state.ProfileReducer.profileId,
       show_gunascore:state.ProfileReducer.show_gunascore
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        showProfile: (profilechecksum) => {
            let call_url = "/api/v1/profile/detail?profilechecksum="+profilechecksum;
            dispatch(commonApiCall(call_url,{},'SHOW_INFO','GET'));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(ProfilePage)
