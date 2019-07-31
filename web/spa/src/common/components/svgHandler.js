import React from "react";

// icons
import {MdFileDownload} from 'react-icons/md';
import {MdHome} from 'react-icons/md';
import {MdSearch} from "react-icons/md";
import {MdModeEdit} from 'react-icons/md';
import {MdPeople} from 'react-icons/md';
import {MdContacts} from 'react-icons/md';
import {MdStar} from 'react-icons/md';
import {MdPermPhoneMsg} from 'react-icons/md';
import {MdRemoveRedEye} from 'react-icons/md';
import {MdInfo} from 'react-icons/md';
import {MdHelp} from 'react-icons/md';
import {MdSettings} from 'react-icons/md';
import {MdExpandMore} from 'react-icons/md';
import {MdExpandLess} from 'react-icons/md';
import {MdChevronRight} from 'react-icons/md';
import {FaRegCheckCircle} from "react-icons/fa";
import {FaRegTimesCircle} from "react-icons/fa";
import {MdTune} from "react-icons/md";
// imgs
// url : https://react-icons.netlify.com/#/
const Icon = props => {
  switch (props.type) {
    case "img":
      return <img src={props.name} alt="props.name"/>;
    case "download":
      return <MdFileDownload style={props.css}/>;
    case "home":
      return <MdHome style={props.css}/>;
    case "search":
      return <MdSearch style={props.css}/>;
    case "edit":
      return <MdModeEdit style={props.css}/>;
    case "people":
      return <MdPeople style={props.css}/>;
    case "contacts":
      return <MdContacts style={props.css}/>;
    case "star":
      return <MdStar style={props.css}/>;
    case "phoneMsg":
      return <MdPermPhoneMsg style={props.css}/>;
    case "eye":
      return <MdRemoveRedEye style={props.css}/>;
    case "info":
      return <MdInfo style={props.css}/>;
    case "help":
      return <MdHelp style={props.css}/>;
    case "setting":
      return <MdSettings style={props.css}/>;
    case "expand_more":
      return <MdExpandMore style={props.css}/>;
    case "expand_less":
      return <MdExpandLess style={props.css}/>;
    case "chevron_right":
      return <MdChevronRight style={props.css}/>;
    case "check_circle":
      return <FaRegCheckCircle style={props.css}/>;
    case "cross_circle":
      return <FaRegTimesCircle style={props.css}/>;
    case "tune":
      return <MdTune style={props.css}/>;

    default:
      return <span/>;
  }
};
export default Icon;