import React from "react";

export default class PhotoView extends React.Component {
    constructor(props) {
        super();
    }
    render() {
        return (
            <div id="PhotoView" className="posrel">
                <img id="profilePic" className="vpro_w100Per" src={this.props.src} />    
            </div>
        );
    }
}
