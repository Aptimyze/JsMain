

import React from "react";
import {connect} from "react-redux";
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";

class PhotoAlbumPage extends React.Component {

    constructor(props) {
        super();
        if(props.location.search.split("?profilechecksum=")[1]) {
            this.state = {
                profilechecksum:props.location.search.split("?profilechecksum=")[1]
            };
            props.getGallery(this.state.profilechecksum);
        } 
    }
    componentWillReceiveProps(nextProps) {
        console.log("next",nextProps.photoAlbumData.albumUrls);
    }
    componentDidMount() {
        document.getElementById("PhotoAlbumPage").style.height = window.innerHeight+"px"; 
    } 

    render() {

        return (
            <div id="PhotoAlbumPage">
                Gallery Page   
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       photoAlbumData: state.AlbumReducer.photoAlbumData,
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        getGallery: (profilechecksum) => {
            let call_url = "/api/v1/social/getAlbum?profileChecksum="+profilechecksum;
            dispatch(commonApiCall(call_url,{},'GET_GALLERY','GET'));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(PhotoAlbumPage)
