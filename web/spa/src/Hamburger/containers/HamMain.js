require ('../style/ham.css')
import React from "react";
import {Link} from "react-router-dom";

export default class HamMain extends React.Component {

    constructor(props) {
        super();
    }

    componentDidMount() {
      
    }
    openHam() {
        document.getElementById("hamView").classList.add("z99")
        document.getElementById("hamView").classList.add("backShow")
        document.getElementById("hamburger").classList.add("hamShow")
    }
    hideHam() {
        document.getElementById("hamView").classList.remove("z99")
        document.getElementById("hamView").classList.remove("backShow")
        document.getElementById("hamburger").classList.remove("hamShow")
    }

    render() {
        return (
            <div id="hamMain">
                <div id="hamburger" className="white posfix z105 wid80p fl fullheight">
                    Hamburger 
                </div>
                <div onClick={this.hideHam} id="hamView" className="fullwid darkView fullheight hamView"></div>
            </div>
        );
    }
}
