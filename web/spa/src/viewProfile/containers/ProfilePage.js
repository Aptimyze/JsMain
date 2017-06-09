require ('../style/profile.css')
import React from "react";
import {connect} from "react-redux";
import Loader from "../../common/components/Loader";
import AppPromo from "../../common/components/AppPromo";
import TopError from "../../common/components/TopError";
import PhotoView from "../../common/components/PhotoView";
import {profileDetail} from "../actions/ProfileActions";

class ProfilePage extends React.Component {

    constructor(props) {
        super();
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false
        };
    }

    componentDidMount() {
        let _this = this;
        document.getElementById("ProfilePage").style.height = window.innerHeight+"px"; 
        setTimeout(function(){ 
            _this.setState ({
                showPromo : true
            });  
        }, 1200);   
        this.props.showProfile();
    } 

    componentWillReceiveProps(nextProps)
    {
        console.log('page');
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
            promoView = <AppPromo removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
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
                                    ZZZY0739
                                </div>
                                <div className="posabs vpro_pos1">
                                    <i className="vpro_sprite vpro_comHisIcon cursp"></i>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <PhotoView></PhotoView> 
                </div>
                <div>{this.props.response}</div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       response: state.response
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
