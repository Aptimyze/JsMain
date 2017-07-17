import React from "react";
import axios from "axios";
import {getCookie} from '../../common/components/CookieHelper';
require ('../style/albumcss.css');

export default class PhotoAlbumPage extends React.Component {

  constructor(props) {
      super();
      this.state={
        getRes: null,
        recAlbumlink: false,
        setCont: 0
      }
  }
  componentDidMount(){

    let _this = this;
    if(getCookie("AUTHCHECKSUM"))
    {
      console.log(this.props.location.search);
      axios.get('http://test1.jeev.com/api/v1/social/getAlbum'+ this.props.location.search + '&AUTHCHECKSUM='+ getCookie("AUTHCHECKSUM") )
        .then(function(response){
          _this.setState({
              getRes: response.data,
              recAlbumlink: true
          });
        })
    }

  }

  render() {
    if(!this.state.recAlbumlink){
      return(<div className="noData album"></div>)
    }
    else
    {
      let setcell={
        width: window.innerWidth,

      }
      let setouter={
        width : window.innerWidth*5,
          height: window.innerHeight,
          display: "table"
      }


        return (

          <div>
            <div className="bg14" style={setouter}>
              {this.state.getRes.albumUrls.map((urllist,index) =>
                <div className="dispcell vertmid txtc" style={setcell} key={urllist.pictureid}>
                    <img src={urllist.url} />
                </div>
              )}
            </div>
          </div>


          );

      }
    }




}
