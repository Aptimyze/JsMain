import React from "react";

export default class PhotoView extends React.Component {

    constructor(props) {
        super();
    }

    componentDidMount() {
    
    } 
     
    render() {
        return (
            <div id="PhotoView" className="posrel">
                <img id="profilePic" className="vpro_w100Per" src="https://mediacdn.jeevansathi.com/3236/15/64735531-1465627855.jpeg" />    
            </div>
        );
    }
}
