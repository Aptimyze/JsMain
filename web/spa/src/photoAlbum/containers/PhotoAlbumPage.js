import React from "react";
import axios from "axios";
import {getCookie} from '../../common/components/CookieHelper';

let setdim={
  width: window.innerWidth,
  height: window.innerHeight
}

export default class PhotoAlbumPage extends React.Component {

    constructor(props) {
        super();
        this.state={
          getRes: null,
          setCont: 0
        }

    }
    componentDidMount(){

      let _this = this;

      if(getCookie("AUTHCHECKSUM"))
      {
        axios.get('http://test1.jeev.com/api/v1/social/getAlbum'+ this.props.location.search + '&AUTHCHECKSUM='+ getCookie("AUTHCHECKSUM") )
          .then(function(response){
            _this.setState({ getRes: response.data });
            calculateDimGallery

          })
      }

    }
    calculateDimGallery(param){

    }


    render() {
      console.log(this.state);


        return (
          <div className="bg14" style={setdim}>



          </div>
        );
    }
}
