import screenTrack from "../../common/components/screenTrack";

require ('../style/searchForm.css')
import React from "react";
import { connect   } from "react-redux";
import TopError from "../../common/components/TopError"
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import GA from "../../common/components/GA";
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
import HamMain from "../../Hamburger/containers/HamMain";
import {getCookie} from '../../common/components/CookieHelper';
import AppPromo from "../../common/components/AppPromo";
import axios from "axios";;
import * as CONSTANTS from '../../common/constants/apiConstants';
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');
import DropMain from "../../DropDown/containers/DropMain";
import SavedSearchPage from "../components/savedSearchPage";
var Dropdata = require('./SearchDrop.json');
import {DPP_FIELDS} from "../../common/constants/CommonConstants";
import GoogleTagManager from "../../common/components/GoogleTagManager";
const USERDEFINEDVALUESKEY = "userDefinedSearchValues";
const USERNAMEKEY = "USERNAME";
import MetaTagComponents from '../../common/components/MetaTagComponents';
import { withLastLocation } from 'react-router-last-location';

export class SearchFormPage extends React.Component {

    constructor(props) {
        super();
        jsb9Fun.recordBundleReceived(this,new Date().getTime());
        this.GAObject = new GA();
        let data = [  {
                        "name":"age",
                        "type":"double",
                        "title1":"Min Age",
                        "title2":"Max Age",
                        "default1":"18 Years",
                        "default2":"70 Years",
                        "value1":"18","value2":"70",
                        "headerText1":"Minimum Age",
                        "headerText2":"Maximum Age",
                      },
                      {
                        "name":"height",
                        "type":"double",
                        "title1":"Min Height",
                        "title2":"Max Height",
                        "default1":"4'0\"",
                        "default2":"7'",
                        "value1":"1",
                        "value2":"37",
                        "headerText1":"Minimum Height",
                        "headerText2":"Maximum Height",
                      },
                      {
                        "name":"religion",
                        "type":"single",
                        "label":"Religion",
                        "default":"Any Religion",
                        "dependent":"",
                        "value":"",
                        "dependentValue":""
                      },
                      {
                        "name":"sect",
                        "type":"single",
                        "label":"Caste",
                        "default":"Any Caste",
                        "dependent":"",
                        "value":"",
                        "dependentValue":""
                      },
                      {
                        "name":"mtongue",
                        "type":"single",
                        "label":"Mother Tongue",
                        "default":"Any Mother Tongue",
                        "dependent":"",
                        "value":"",
                        "dependentValue":""
                      },
                      {
                        "name":"location",
                        "type":"single",
                        "label":"Country",
                        "default":"Any Country",
                        "dependent":"","value":"",
                        "dependentValue":""
                      },
                      {"name":"location_cities",
                      "type":"single",
                      "label":"State/City",
                      "default":"Any State/City",
                      "dependent":"",
                      "value":"",
                      "dependentValue":""
                    },{
                          "name":"mstatus",
                          "type":"single",
                          "label":"Marital Status",
                          "default":"Doesn't Matter",
                          "dependent":"",
                          "value":"",
                          "dependentValue":""
                        },
                        {
                          "name":"income",
                          "type":"double",
                          "title1":"Min Income",
                          "title2":"Max Income",
                          "default1":"Rs. 0",
                          "default2":"and above",
                          "value1":"0",
                          "value2":"19",
                          "headerText1":"Minimum Income",
                          "headerText2":"Maximum Income",
                        },
                        ];


        //console.log(props);

        let moreData;
        // this has been segregated for dpp on reg page and search
        if(props.dppReg=="1")
        {
          moreData = [
              {"name":"education","label":"Education","default":"Doesn't Matter","type":"single","dependent":"","value":"","dependentValue":""},
              {"name":"occupation_grouping","label":"Occupation","default":"Doesn't Matter","type":"single","dependent":"","value":"","dependentValue":""},
              {"name":"manglik","label":"Manglik","default":"Doesn't Matter","type":"single","dependent":"","value":"","dependentValue":""}];

        }
        else
        {
          moreData = [
              {
                "name":"education",
                "label":"Education",
                "default":"Doesn't Matter",
                "type":"single",
                "dependent":"",
                "value":"",
                "dependentValue":""
              },
              {
                "name":"employed_in",
                "label":"Employed_in",
                "default":"Doesn't Matter",
                "type":"single",
                "dependent":"",
                "value":"",
                "dependentValue":""
              },
              {
                "name":"occupation",
                "label":"Occupation",
                "default":"Doesn't Matter",
                "type":"single",
                "dependent":"",
                "value":"",
                "dependentValue":""
              },
              {
                "name":"manglik",
                "label":"Manglik",
                "default":"Doesn't Matter",
                "type":"single",
                "dependent":"",
                "value":"",
                "dependentValue":""
              }
            ];

        }

        // console.log("--1");
        // console.log(moreData);

        let newmoreData =  JSON.stringify(moreData);
        let newmoreData_one = JSON.parse(newmoreData);




        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            loggedInStatus: false,
            showPromo: false,
            showMore: false,
            primaryData: data,
            moreData: newmoreData_one,
            savedSearchCount: 0,
            showSavedSearch: false,
            savedSearchData: [],
            maxSavedSearchLimit: 0,
            tupleData: [],
            flagForShowMore:0,
            listDropValues: Dropdata.services.searchForm.data,
            gender:"F",
            caste:"",
            extendedStr:"",
            selfUsername:"",
            gtmAge:"",
            gtmCity:"",
            gtmMtongue:"",
            gtmSource:"",
            gtmGroupname:"",
            gtmGender:"",
            currentPageName:"JSMS_SEARCH_PAGE_LOGOUT"
        };
        if(getCookie("AUTHCHECKSUM")) {
            this.state.loggedInStatus = true;
            this.state.currentPageName = 'JSMS_SEARCH_PAGE';
        }
    }

    componentDidMount() {
      screenTrack('advanceSearch', window.location.pathname);
      if(this.props.dppReg == "1") //if the user is coming from reg page, then we show the dpp page and accordingly make the call
      {
        let _this = this;
              let callUrl = CONSTANTS.DPP_REG_API+"&AUTHCHECKSUM="+getCookie("AUTHCHECKSUM");
              commonApiCall(callUrl,'','','POST').then(function(response) {

                _this.appendDefaultDppValues(response);
                _this.callGTM(response);
              });
      }
      else
      {
        let _this = this;
        if(this.props.history.zeroResults)
        {
          this.noResultFoundError();
        }

        let defaultValueLocalStorageArray = JSON.parse(localStorage.getItem(USERDEFINEDVALUESKEY));

        
        
        /* check for gender for same profile in case of auto login from email */
        if(localStorage.getItem(USERDEFINEDVALUESKEY) && localStorage.getItem(USERDEFINEDVALUESKEY)!=null) 
        {
          if(localStorage.getItem("GENDER") && localStorage.getItem("GENDER")!=null)
          {
            

             /* ---- if gender save in userdefined is equal to GENDER, then revert userdefined gender to opposite value stored in GENDER ---- */

            if(localStorage.getItem("GENDER") == defaultValueLocalStorageArray.gender)
            {
              if(defaultValueLocalStorageArray.gender=="F")
              {
                defaultValueLocalStorageArray.gender= "M";
              }
              else
              {
                defaultValueLocalStorageArray.gender= "F";
              }
              localStorage.setItem(USERDEFINEDVALUESKEY,JSON.stringify(defaultValueLocalStorageArray));
            }
          }
        }




        let more_data_present = false;
        if(defaultValueLocalStorageArray)
        {
          for(let i=0;i<defaultValueLocalStorageArray.moreData.length;i++)
          {
            if(defaultValueLocalStorageArray.moreData[i].value)
            {
              more_data_present = true;
              break;
            }
          }
          if(more_data_present)
          {
            this.setState({
              showMore: true
            })
          }

          this.setStateForDefaultValues(defaultValueLocalStorageArray.primaryData,defaultValueLocalStorageArray.moreData,defaultValueLocalStorageArray.caste,defaultValueLocalStorageArray.gender,defaultValueLocalStorageArray.havePhoto,defaultValueLocalStorageArray.flagForShowMore,defaultValueLocalStorageArray.selfUsername);
        }

       //--start:--this is the data to be used for populating the last search values
       if(getCookie("AUTHCHECKSUM"))
       {

          if(!defaultValueLocalStorageArray)
          {
            let call_url = "/api/v2/search/populateDefaultValues";
            axios({
              method: "POST",
            url: API_SERVER_CONSTANTS.API_SERVER +call_url+"?AUTHCHECKSUM="+getCookie("AUTHCHECKSUM"),//+"&searchId="+getCookie("JSSearchId"),
            data: '',
            headers: {
              'Accept': 'application/json',
              'withCredentials':true
            },
            }).then( (response) => {
              this.appendDefaultValues(response.data)
            });
          }
        }
        else if(!defaultValueLocalStorageArray)
        {
          document.getElementById("searchform_photo").classList.add("selectedTab");
          document.getElementById("search_GENDERF").classList.add("selectedTab");
          this.setMaxAgeForLoggedOut();
        }
        _this.GAObject.trackJsEventGA("jsms","new","1","",this.state.currentPageName);
      }
    }

    setMaxAgeForLoggedOut()
    {
      let temp = this.state.primaryData;
      for(let i=0;i<this.state.primaryData.length;i++)
      {
        if(this.state.primaryData[i].name == "age")
        {
          temp[i].default2 = "35 Years";
          temp[i].value2 = "35";
        }
      }
      this.setState({
        primaryData:temp,
      })
    }
    appendDefaultValues(defaultData)
    {  
        
        let temp = this.state.primaryData;
        let tempMoreData = this.state.moreData;
        let dataInCache={};
        //loop to append primary data values
        for(let i=0;i<this.state.primaryData.length;i++)
        {
            if(this.state.primaryData[i].name == "age")
            {
                temp[i].default1 = defaultData.lage+" Years";
                temp[i].default2 = defaultData.hage+" Years";
                temp[i].value1 = defaultData.lage;
                temp[i].value2 = defaultData.hage;
            }
            if(this.state.primaryData[i].name == "height")
            {
                temp[i].default1 = defaultData.lheight_label;
                temp[i].default2 = defaultData.hheight_label;
                temp[i].value1 = defaultData.lheight;
                temp[i].value2 = defaultData.hheight;
            }
            if(this.state.primaryData[i].name == "income")
            {
                temp[i].default1 = defaultData.lincome_label;
                temp[i].default2 = defaultData.hincome_label;
                temp[i].value1 = defaultData.lincome;
                temp[i].value2 = defaultData.hincome;
            }
            if(this.state.primaryData[i].name == "religion" && this.state.primaryData[i].default != defaultData.religion_label)
            {
                this.appendDefaultValuesFromApi(
                    temp[i],
                    defaultData.religion_label,
                    defaultData.caste_label,
                    defaultData.religion,
                    defaultData.caste
                );
            }
            if(this.state.primaryData[i].name == "sect" && this.state.primaryData[i].default != defaultData.muslim_caste_label && defaultData.religion == "2")
            {
              this.appendDefaultValuesFromApi(temp[i],defaultData.muslim_caste_label,defaultData.muslim_caste_label_dep,defaultData.muslim_caste);
            }
            if(this.state.primaryData[i].name == "mtongue" && this.state.primaryData[i].default != defaultData.mtongue_label)
            {
                this.appendDefaultValuesFromApi(temp[i],defaultData.mtongue_label,defaultData.mtongue_label_dep,defaultData.mtongue);
            }
            if(this.state.primaryData[i].name == "location" && this.state.primaryData[i].default != defaultData.location_label)
            {
                this.appendDefaultValuesFromApi(temp[i],defaultData.location_label,defaultData.location_label_dep,defaultData.location);
            }
            if(this.state.primaryData[i].name == "location_cities" && this.state.primaryData[i].default != defaultData.location_cities_label)
            {
                this.appendDefaultValuesFromApi(temp[i],defaultData.location_cities_label,defaultData.location_cities_label_dep,defaultData.location_cities);
            }
        }

        let occLabelPop,occMoreText;
        let empInPop=[],occPop=[];

        //loop to append more data values
        for(let i=0;i<this.state.moreData.length;i++)
        {
            if(this.state.moreData[i].name == "education" && this.state.moreData[i].default != defaultData.education_label)
            {
                this.state.flagForShowMore++;
                this.appendDefaultValuesFromApi(tempMoreData[i],defaultData.education_label,defaultData.education_label_dep,defaultData.education);
            }
            if(this.state.moreData[i].name == "employed_in" && this.state.moreData[i].default != defaultData.employed_in_label)
            {
                this.state.flagForShowMore++;
                this.appendDefaultValuesFromApi(tempMoreData[i],defaultData.employed_in_label,defaultData.employed_in_label_dep,defaultData.employed_in);
            }
            if(this.state.moreData[i].name == "occupation" && this.state.moreData[i].default != defaultData.occupation_label)
            {
              this.state.flagForShowMore++;

              //console.log(defaultData);

              //start: if data is present in employed in but not in occupation
              if(defaultData.employed_in && (defaultData.occupation == "" || defaultData.occupation == "null" ))
              {
                //console.log("case1");
                occLabelPop = defaultData.employed_in_label;
                occPop = defaultData.employed_in.split(",");
              }

              //start: if data is present in occupation in but not in employedin

              else if(defaultData.occupation && (defaultData.employed_in == "" || defaultData.employed_in == "null" ))
              {
                //console.log("case2");                
                occLabelPop = defaultData.occupation_label;
                occPop = defaultData.occupation.split(",");
              }

              //start: if data is present in occupation and in employedin
              else (defaultData.employed_in && defaultData.occupation)
              {
                //console.log("case3");
                occLabelPop = defaultData.employed_in_label;
                empInPop = defaultData.employed_in.split(",");
                occPop = defaultData.occupation.split(",");
                let restoccLen = empInPop.length+occPop.length;
                if(restoccLen>1)
                {
                  occMoreText = "+"+(restoccLen-1)+" more";
                }

              }



              this.appendDefaultValuesFromApi(
                    tempMoreData[i],
                    occLabelPop,
                    occMoreText,
                    defaultData.occupation
               );




            }
            if(this.state.moreData[i].name == "manglik" && this.state.moreData[i].default != defaultData.manglik_label)
            {
                this.state.flagForShowMore++;
                this.appendDefaultValuesFromApi(tempMoreData[i],defaultData.manglik_label,defaultData.manglik_label_dep,defaultData.manglik);
            }
        }


        dataInCache['primaryData'] = temp;
        dataInCache['moreData'] = tempMoreData;
        dataInCache['gender'] = defaultData.gender;
        dataInCache['caste'] = defaultData.caste;
        dataInCache['havePhoto'] = defaultData.havephoto;
        dataInCache['flagForShowMore'] = this.state.flagForShowMore;
        dataInCache['selfUsername'] = defaultData.selfUsername;
        localStorage.setItem(USERDEFINEDVALUESKEY,JSON.stringify(dataInCache));
        this.setStateForDefaultValues(temp,tempMoreData,defaultData.caste,defaultData.gender,defaultData.havephoto,this.state.flagForShowMore,defaultData.selfUsername);
    }

    appendDefaultValuesFromApi(obj,label,labelDep,value,dependentValue="")
    {
        obj.default = label;
        obj.value = value;
        obj.dependentValue = dependentValue;
        if(labelDep!=null && labelDep !="")
        {
            obj.dependent = labelDep;
        }
    }
    componentWillMount(){
    
    }
    componentWillReceiveProps(nextProps)
    {
      

    }

    componentDidUpdate(prevprops)
    {
      //console.log("Sreachfrom component Did Update");
        jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
        if(prevprops.location) {
            if(prevprops.location.search.indexOf("ham=1") != -1 && window.location.search.indexOf("ham=1") == -1) {
                this.refs.Hamchild.getWrappedInstance().hideHam();
                if(this.refs.Dropchild) {
                    this.refs.Dropchild.hideHam();
                }
            }
        }
    }

    componentWillUnmount(props){
      localStorage.setItem('fromSearchFilter',false);
        // props.jsb9TrackRedirection(new Date().getTime(),this.url);
    }

    showError(inputString,extendedStr="")
    {
        let _this = this;
        this.setState ({
                insertError : true,
                errorMessage : inputString,
                extendedStr:extendedStr,
        })
        setTimeout(function(){
            _this.setState ({
                insertError : false,
                errorMessage : "",
                extendedStr:extendedStr,
            })
        }, this.state.timeToHide+100);
    }

    //showDrop(elem,selVal,mapSelVal,e,typeSel)
    //showDrop(elem,selVal,mapSelVal,e,typeSel)
    showDrop(param,e)
    {


      if(window.location.search.indexOf("ham=1") == -1)
      {
        if(window.location.search.indexOf("?") == -1)
        {
          this.props.history.push(window.location.pathname+"?ham=1");
        }
        else
        {
          this.props.history.push(window.location.pathname+window.location.search+"&ham=1");
        }
      }
      let temp;
      if(param.listData.type == "double")
      {

        if(e.target.className.indexOf("drop1") > -1)
        {

          let obj_1 = {
              mainCate:param.listData.name,
              subCate: param.listData.headerText1,
              type: param.listData.type,
              listValues: this.state.listDropValues[param.listData.name],
              labelMinValue: param.selectedVal1,
              labelMaxValue: param.selectedVal2,
              mapMinValue: param.mapSelVal1,
              mapMaxValue: param.mapSelVal2,
              fieldNum : 'title1',
              gender:this.state.gender,
          };
          this.refs.Dropchild.openHam(obj_1);
        }
        else if(e.target.className.indexOf("drop2") > -1)
        {
          let obj_2 = {
              mainCate:param.listData.name,
              subCate: param.listData.headerText2,
              type: param.listData.type,
              listValues: this.state.listDropValues[param.listData.name],
              labelMinValue: param.selectedVal1,
              labelMaxValue: param.selectedVal2,
              mapMinValue: param.mapSelVal1,
              mapMaxValue: param.mapSelVal2,
              fieldNum : 'title2',
              gender:this.state.gender,
          };
          this.refs.Dropchild.openHam(obj_2);

        }
      }
      else
      {        
        
        let emptempSF_1, emptempSF_2=[],finaldataSF;
        if(param.listData.name=="occupation")
        {
          
          this.state.moreData.map(function(i,v){
            if(i.name=="employed_in" && i.value!="")
            {
              //start:value is present in employed in
              emptempSF_1 =i.value;
            }           
          });
          if(emptempSF_1)
          {
            emptempSF_1 = emptempSF_1.split(",");
            for(let n=0;n<emptempSF_1.length;n++)
            {
              emptempSF_2.push(emptempSF_1[n]+"E");
            }
            emptempSF_2 = emptempSF_2.toString();
            if(param.listData.value)
            {
              finaldataSF= emptempSF_2.concat(",").concat(param.listData.value);
            }
            else
            {
              finaldataSF = emptempSF_2;
            }
        }
        else
        {
          finaldataSF = param.listData.value
        }

      }
      else
      {
        finaldataSF = param.listData.value;
      }

        //start:this variable will remain false for every drop down and will get true only when muslim casre drop down is open
        let muslimCaste=false;
        if(param.listData.name=="sect")
        {
          muslimCaste = true;
        }

        let obj_3 = {
            mainCate:param.listData.label,
            subCate: param.listData.dependentValue,
            type: param.listData.type,
            listValues: this.state.listDropValues[param.listData.name],
            labelValue: '',
            mapValue: finaldataSF,
            DropCate: '',
            fieldNum : '',
            gender:this.state.gender,
            muslimCaste: muslimCaste,
            mainName: param.listData.name,
            PrevUpdatedFormVal: this.state.primaryData


        };

        //console.log(obj_3);
        this.refs.Dropchild.openHam(obj_3);
      }
    }
    showHam()
    {
          if(window.location.search.indexOf("ham=1") == -1) {
              if(window.location.search.indexOf("?") == -1) {
                  this.props.history.push(window.location.pathname+"?ham=1");
              } else {
                  this.props.history.push(window.location.pathname+window.location.search+"&ham=1");
              }

          }
          this.refs.Hamchild.getWrappedInstance().openHam();
    }
    changeTab(e)
    {
          if(e.target.nextSibling) {
              e.target.nextSibling.classList.remove("selectedTab");
              if(e.target.classList.contains("havePhoto"))
              {
                  this.setState({
                  havePhoto:""
                  });
              }
              else if(e.target.classList.contains("gender"))
              {
                  this.toggleAgeWithGenderChange(this.state.primaryData,"18");
                  this.setState({
                  gender:"F"
                  });
              }
          } else if(e.target.previousSibling) {
              e.target.previousSibling.classList.remove("selectedTab");
              if(e.target.classList.contains("havePhoto"))
              {
                  this.setState({
                  havePhoto:"Y"
                  });
              }
              else if(e.target.classList.contains("gender"))
              {
                  this.toggleAgeWithGenderChange(this.state.primaryData,"21");
                  this.setState({
                  gender:"M"
                  });
              }
          }
          e.target.classList.add("selectedTab");
      }
    changeMore()
    {
          if(this.state.showMore == false) {
              document.getElementById("moreDetails").classList.add("openShowMoreDiv");
          } else {
              document.getElementById("moreDetails").classList.remove("openShowMoreDiv");

          }
          this.setState({
              showMore : !this.state.showMore
          });
      }
    goToSavedSearchView()
    {
          document.getElementById("savedSearches").scrollIntoView();
    }
    //this function gets hit when "Search" button is HIT. It computes the data to be sent to the listing, converts it into a desired format and sends it as required.
    searchProfiles()
    {
        let valJson={};
        let storeSearchInCache = {};
        storeSearchInCache['primaryData'] = this.state.primaryData;
        storeSearchInCache['moreData'] = this.state.moreData;
        storeSearchInCache['gender'] = this.state.gender;
        storeSearchInCache['caste'] = (this.state.caste!=null?this.state.caste:"");
        storeSearchInCache['havePhoto'] = (this.state.havePhoto!=null?this.state.havePhoto:"");
        storeSearchInCache['selfUsername'] = this.state.selfUsername;
        let isSameSearchKeys = (localStorage.getItem(USERDEFINEDVALUESKEY) === JSON.stringify(storeSearchInCache));
        localStorage.setItem(USERDEFINEDVALUESKEY,JSON.stringify(storeSearchInCache));

        for(let i=0;i<this.state.primaryData.length;i++)
        {
            if(this.state.primaryData[i].name == "age")
            {
                valJson["LAGE"] = this.state.primaryData[i].value1;
                valJson["HAGE"] = this.state.primaryData[i].value2;
            }
            else if(this.state.primaryData[i].name == "height")
            {
                valJson["LHEIGHT"] = this.state.primaryData[i].value1;
                valJson["HHEIGHT"] = this.state.primaryData[i].value2;
            }
            else if(this.state.primaryData[i].name == "income")
            {
                valJson["LINCOME"] = this.state.primaryData[i].value1;
                valJson["HINCOME"] = this.state.primaryData[i].value2;
            }
            else
            {
                valJson[this.state.primaryData[i].name.toUpperCase()] = this.state.primaryData[i].value;
            } 

        }
        for(let i=0;i<this.state.moreData.length;i++)
        {
            valJson[this.state.moreData[i].name.toUpperCase()] = this.state.moreData[i].value;
        }
        valJson["GENDER"] = this.state.gender;
        valJson["CASTE"] = (this.state.caste!=null?this.state.caste:"");
        valJson["LOCATION"]= valJson["LOCATION"].replace("DE", "DE00");
        valJson["LOCATION"]= valJson["LOCATION"].replace("DE0000", "DE00");
        valJson["PHOTO"] = (this.state.havePhoto!=null?this.state.havePhoto:"");
        valJson = JSON.stringify(valJson);


       

        this.props.history.searchFormData = valJson;
        let lastLocation = '' ;
        
       if(this.props.lastLocation)
        {
             lastLocation = this.props.lastLocation.search;  
        }
        //console.log('lastLocation',lastLocation);
        if(localStorage.getItem('fromSearchFilter') == 'true'  )
        {         
          isSameSearchKeys = false;
          this.props.history.isSameSearchKeys = false;         
        } 


        else if(lastLocation.indexOf('ham') > -1 && window.jsMain.isClusterApplied == 'yes')
        {
           /* this is for handlling cluster issue: search hit is not going in no data case*/
           isSameSearchKeys = false;
           this.props.history.isSameSearchKeys = false;
        }
        else
        {         
          this.props.history.isSameSearchKeys = isSameSearchKeys;          
        }
        
        if(isSameSearchKeys && this.props.history.zeroResults)
        {
          this.noResultFoundError();
        }
        else
        {
          this.props.history.zeroResults = 0;
          this.props.history.push("/search/QuickSearchBand");
        }
    }
    //this function is used to set state for prepopulating values of last search. it also calls a callback function
    setStateForDefaultValues(primaryData,moreData,caste,gender,havePhoto,flagForShowMore,selfUsername)
    {
      this.setState({
          primaryData:primaryData,
          moreData:moreData,
          gender:gender,
          havePhoto:havePhoto,
          caste:caste,
          flagForShowMore:flagForShowMore,
          selfUsername:selfUsername,
      },()=>{
          this.callBackFunction();
      });
      }

    callBackFunction()
    {
          this.checkIfShowMore();
          this.defaultSelectedTab();
          if(this.state.loggedInStatus != true)
              this.defaultSelectedGender();
    }
    //this is to ensure that the "MORE OPTIONS" tab is open if even one of the value is filled
    checkIfShowMore()
    {
          if(this.state.flagForShowMore)
          {
              this.setState({
                  showMore:false
              },()=>{
                  this.changeMore();
              });
          }
      }
    defaultSelectedTab()
    {
          if(this.state.havePhoto=="Y")
          {
              document.getElementById("searchform_photo").classList.add("selectedTab");
          }
          else
          {
              document.getElementById("searchform_all").classList.add("selectedTab");
          }
      }
    //this function is used to decide which  Gender tab will be selected in case the search form is opened in loggedOut state. Also setting the age with it.
    defaultSelectedGender()
    {
            let tempArr = this.state.primaryData;
            if(this.state.gender=="M")
            {
                document.getElementById("search_GENDERM").classList.add("selectedTab");
              if(tempArr[0].value1<"21")
                this.toggleAgeWithGenderChange(tempArr,"21");
            }
            else
            {
                document.getElementById("search_GENDERF").classList.add("selectedTab");
              if(tempArr[0].value1<="18")
                this.toggleAgeWithGenderChange(tempArr,"18");
            }
        }

    toggleAgeWithGenderChange(dataArr,age)
    {
      for(let i=0;i<dataArr.length;i++)
      {
        if(dataArr[i].name == "age")
        {
          dataArr[i].default1  = age+" Years";
          dataArr[i].value1 = age;
          if(dataArr[i].value2<"21" && age == "21")
          {
            dataArr[i].default2 = age+" Years";
            dataArr[i].value2 = age;
          }
        }
      }
      this.setState({
        primaryData:dataArr,
      });
    }
    updateSF(param)
    {
        //console.log("udatesf", param);
        // console.log("primaryData", this.state.primaryData);
        let temp = this.state.primaryData;
        let mainCategory =  param.mainFieldName;
        let feildtitle =  param.field;
        let maxIncomeListArr;
        let result, muslimValInit ,casteFieldValue = "";
        if((mainCategory=="education")||(mainCategory=="occupation")||(mainCategory=="manglik") || (mainCategory=="occupation_grouping"))
        {
          result = this.state.moreData.filter(function( obj ) {
            return obj.name == mainCategory;
          });

        }
        else
        {
          result = this.state.primaryData.filter(function( obj ) {
            return obj.name == mainCategory;
          });
        }


        if((mainCategory=='age') || (mainCategory=="height") || (mainCategory=="income"))
        {
          let suffix = '';
          if(mainCategory== 'age')
          {
            suffix = "Years";
          }
          if(feildtitle == 'title1' )
          {
            if(param.label.indexOf(suffix) !=-1 && mainCategory == "age")
            {
              result[0].default1 = param.label;
            }
            else
              result[0].default1 = param.label+" "+ suffix;
            result[0].value1 = param.mappedVal;
            if(parseInt(result[0].value1)>parseInt(result[0].value2)) //this is when min value is greater than max value.
            {
              if(mainCategory == "income")
              {
                maxIncomeListArr = this.maxIncomeListMapping(param.mappedVal,suffix);
                result[0].default2 = maxIncomeListArr["label"];
                result[0].value2 = maxIncomeListArr["value"];
              }
              else
              {

                result[0].default2 = param.label+" "+ suffix;
                result[0].value2 = param.mappedVal;
              }
            }
            else if(parseInt(result[0].value1) == parseInt(result[0].value2) && mainCategory=="income")
            {
              maxIncomeListArr = this.maxIncomeListMapping(param.mappedVal,suffix);
              result[0].default2 = maxIncomeListArr["label"];
              result[0].value2 = maxIncomeListArr["value"];
            }
          }
          else if(feildtitle == 'title2' )
          {
            if(param.label.indexOf(suffix) !=-1 && mainCategory == "age")
            {
              result[0].default2 = param.label;
            }
            else
              result[0].default2 = param.label+" "+ suffix;
            result[0].value2 = param.mappedVal;
          }

        }
        else if(mainCategory=="religion")
        {
          result[0].default = param.rel_label;
          result[0].dependent = param.casteLabelV;
          result[0].dependentValue = param.casteV;
          result[0].value= param.religionV;
          casteFieldValue = param.casteV;

          let temp = result[0].value.split(",");
          if(((temp.length>1) && (temp.indexOf("2")!=-1)) || (result[0].default == "Any Religion"))
          {
            muslimValInit =  this.state.primaryData.filter(function(obj){
              return obj.name == "sect"
            });

            muslimValInit[0].default = "Any Caste";
            muslimValInit[0].dependent ="";
            muslimValInit[0].dependentValue = "";
            muslimValInit[0].label = "Caste";
            muslimValInit[0].value="";
          }


        }
        else if(mainCategory=="mtongue")
        {
          result[0].default = param.mtongueL;
          result[0].value =  param.mtongueV;
          result[0].dependent = param.mtongueDep;

        }
        else if(mainCategory=="mstatus")
        {

          result[0].default = param.other_Label;
          result[0].value =  param.other_Data;
          result[0].dependent = param.other_label_Dep;
        }
        else if(mainCategory=="occupation_grouping")
        {

          result[0].default = param.other_Label;
          result[0].value =  param.other_Data;
          result[0].dependent = param.other_label_Dep;
        }
        else if(mainCategory=="location")
        {
          result[0].default = param.location_label;
          result[0].value =  param.locationData;
          result[0].dependent = param.location_label_dep;

        }
        else if(mainCategory=="location_cities")
        {
          result[0].default = param.locationCity_label;
          result[0].value =  param.locationCityData;
          result[0].dependent = param.locationCity_label_dep;
        }
        else if(mainCategory=="education")
        {
          result[0].default = param.more_label;
          result[0].value =  param.more_data;
          result[0].dependent = param.more_label_dep;
        }
        else if(mainCategory=="occupation")
        {
          
          let empinValInit =  this.state.moreData.filter(function(obj){
            return obj.name == "employed_in"
          });
          
          let combineVal = param.more_data.split(',');
          let tempEmpInV =[],tempOccV = [];

          for(let i =0;i<combineVal.length;i++)
          {
            if (combineVal[i].match(/[a-z]/i)) 
            {
              tempEmpInV.push(combineVal[i].replace(/\D/g,''));
            }
            else
            {
              tempOccV.push(combineVal[i]);
            }
          }

          let empVStr = tempEmpInV.toString();
          let occVStr = tempOccV.toString();


          result[0].default = param.more_label;
          result[0].value =  occVStr;
          result[0].dependent = param.more_label_dep;
          empinValInit[0].value=empVStr;
         
          


        }
        else if(mainCategory=="manglik")
        {
          result[0].default = param.manglik_label;
          result[0].value =  param.manglik_data;
          result[0].dependent = param.manglik_label_dep;
        }
        else if(mainCategory=="sect")
        {
          result[0].default = param.musCaste_Label;
          result[0].value =  param.musCaste_Data;
          result[0].dependent = param.musCaste_label_Dep;
        }
        
        if(mainCategory=="religion")
        {

          this.setState({
            primaryData: temp,
            caste:casteFieldValue,
          });
        }
        else if(mainCategory == "location")
        {
          for(let i=0;i<temp.length;i++)
          {
            //----start:we need to clear location_cities data when india is not filled in country
            if(temp[i].name == "location_cities" && (param.locationData.indexOf('51') ==-1 || param.locationData.indexOf('128') ==-1 ))
            {
              temp[i].default="Any State/City";
              temp[i].dependent="";
              temp[i].value="";
            }
          }
          this.setState({
            primaryData: temp,
          });
        }
        else
        {
         this.setState({
            primaryData: temp,
          });
        }
        //console.log(this.state.primaryData);
    }
    //this is used to map max income listing and change the dropdowns to be shown when the min income field is changed.
    maxIncomeListMapping(incomeVal,suffix)
    {
      let returnArr=[];
      let incomeListArr = this.state.listDropValues.income;
      for(let i=0;i<incomeListArr.length;i++)
      {
        if(incomeListArr[i].VALUE == incomeVal)
        {
          returnArr["label"] = incomeListArr[i+1].LABEL+" "+ suffix;
          returnArr["value"] = incomeListArr[i+1].VALUE;
          break;
        }
      }
      return returnArr;
    }
    getMainView()
    {
      let setIndia=false;
      let showState=false;
      let onlyMusPresent = false;

      return this.state.primaryData.map(function(key, index)
      {
          if(key.type == "double")
          {
            let mainlabel,selectedVal1,selectedVal2,mapSelVal1,mapSelVal2;
            let objToDD = {
              mapSelVal1 : key.value1,
              mapSelVal2 : key.value2,
              selectedVal1 : key.default1,
              selectedVal2 : key.default2,
              listData : key
            }

            return (
                  <div id={"search_"+key.name}  key={index}>
                    <MetaTagComponents page="topSearchBand"/>
                      <div className="brdr1 pad18">
                          <div onClick={(e) => this.showDrop(objToDD,e)} className="wid45p dispibl drop1" id={"search_l"+key.name}>
                              <div className="fullwid drop1">
                                  <div className="fl drop1">
                                      <div className="color8 f12 drop1">{key.title1}</div>
                                      <div className="color8 f17 pt10 drop1">
                                          <span className="label wid70p drop1">{key.default1}</span>
                                      </div>
                                  </div>
                                  <div className="fr pt8 drop1">
                                      <i className="drop1 mainsp arow1"></i>
                                  </div>
                              </div>
                          </div>
                          <div onClick={(e) => this.showDrop(objToDD,e)} id={"search_h"+key.name} className="wid45p fr mrr5 dispibl drop2">
                              <div className="fullwid drop2">
                                  <div className="fl srfrm_wrap drop2">
                                      <div className="color8 f12 drop2">{key.title2}</div>
                                      <div className="color8 f17 pt10 drop2">
                                          <span className="label wid70p drop2">{key.default2}</span>
                                      </div>
                                  </div>
                                  <div className="fr pt8 drop2">
                                      <i className="mainsp arow1 drop2"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              );
          }
          else
          {


            let objToDD,showHideState;
            //----start: if in location India, US are presnt show the state field
            if(key.name=="location")
            {
              if( (key.value.indexOf(51)>-1) || (key.value.indexOf(128)>-1))
              {
                showState= true;
              }
            }
            
            if(key.name=="religion" )
            {
              let newArr =  key.value.split(',');
              if( (newArr.length==1) && (newArr.indexOf('2')!=-1))
              {
                onlyMusPresent = true
              }
            }
            if((key.name == "sect" && this.props.dppReg) || (key.name == "mstatus" && !this.props.dppReg))
            {
              return;
            }
            if((showState==false && key.name=="location_cities") || (onlyMusPresent==false && key.name=="sect"))
            {

              showHideState = "dn";

            }
            else if((showState==true && key.name=="location_cities") || (onlyMusPresent==true &&  key.name=="sect"))
            {


              showHideState = "dispbl";
            }




            objToDD = {
                selectedVal : key.default,
                mapSelVal : key.dependentValue,
                mapSelMainVal : key.value,
                listData : key
            };

            

            return(
                  <div onClick={(e) => this.showDrop(objToDD,e)} id={"search_"+key.name} key={index} className={showHideState} >
                      <div className="pad18 brdr1">

                          <div className="clearfix">
                            <div className="fl wid94p srfrm_wrap">
                                <div className="color8 f12">{key.label}</div>
                                <div className="color8 f17 pt10">
                                  <span className="label wid70p">{key.default}</span>
                                  <span className="dependent f13 color7"> {key.dependent}</span>
                                </div>
                            </div>
                            <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
                          </div>



                      </div>
                  </div>
              );
          }

        },this)
    }
    getMoreView()
    {
      return this.state.moreData.map(function(key, index){

        // console.log("===");
        // console.log(key);
        

        //Note: note to show employed in data in view
        if(key.name!="employed_in")
        {
           let objToDD;
            objToDD = {
              selectedVal : key.default,
              mapSelVal : key.dependentValue,
              mapSelMainVal : key.value,
              listData : key
            };


            //console.log(objToDD);

             return(
          <div onClick={(e) => this.showDrop(objToDD,e)} id={"search_"+key.name} key={index}>
          <div className="pad18 brdr1">
              <div className="dispibl srfrm_wrap">
                  <div className="color8 f12">{key.label}</div>
                  <div className="color8 f17 pt10">
                      <span className="label wid70p">{key.default}</span>
                      <span className="dependent f13 color7"> {key.dependent}</span>
                  </div>
              </div>
              <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
          </div>
          </div>
        );

        }       

      },this);





    }
    getSavedSearchCount(count)
    {
      this.setState({
        savedSearchCount:count,
      });
    }
    getSavedSearchResults(searchId)
    {
      this.props.history.push("/search/SavedSearches?mySaveSearchId="+searchId);
    }
    noResultFoundError()
    {
      setTimeout(function(){
          document.getElementById("SearchFormPage").scrollIntoView();
        },100);
        this.showError("No results found.","Kindly broaden your search criteria and try again");
    }

    //this function is used to append default dpp values into the form when dpp page gets opened. It loops through the altered array to fill in the form data and set state of primary and more data.

    appendDefaultDppValues(dataArr)
    {
      dataArr = this.getSortedDppArr(dataArr);    
      //console.log(dataArr);  
      let temp = this.state.primaryData;
      let tempMoreData = this.state.moreData;
      let key = "";



      for(let i=0;i<this.state.primaryData.length;i++)
      {
        if(this.state.primaryData[i].name == "age")
        {
          temp[i].default1 = dataArr.age.default1;
          temp[i].default2 = dataArr.age.default2;
          temp[i].value1 = dataArr.age.value1;
          temp[i].value2 = dataArr.age.value2;
        }
        else if(this.state.primaryData[i].name == "height")
        {
          temp[i].default1 = dataArr.height.default1;
          temp[i].default2 = dataArr.height.default2;
          temp[i].value1 = dataArr.height.value1;
          temp[i].value2 = dataArr.height.value2;
        }
        else if(this.state.primaryData[i].name == "income")
        {
          if(dataArr.income.value1 != "DM")
          {
            temp[i].default1 = dataArr.income.default1;
            temp[i].default2 = dataArr.income.default2;
            temp[i].value1 = dataArr.income.value1;
            temp[i].value2 = dataArr.income.value2;
          }
        }
        else if(this.state.primaryData[i].name == "religion" && dataArr.religion.value != "DM")
        {
          let sectVal = 0;
          if(dataArr.religion.value.includes("2") || dataArr.religion.value.includes("3"))
            sectVal = 1;
          this.appendDefaultDppValuesFromApi(
            temp[i],dataArr.religion.value,dataArr.religion.default,dataArr.caste.dependent,dataArr.caste.value,sectVal
            );
        }
        else if(this.state.primaryData[i].name == "mtongue" && dataArr.mtongue.value !="DM")
        {
          this.appendDefaultDppValuesFromApi(temp[i],dataArr.mtongue.value,dataArr.mtongue.default,dataArr.mtongue.dependent);
        }
        else if(this.state.primaryData[i].name == "location" && dataArr.location.value != "DM")
        {
          this.appendDefaultDppValuesFromApi(temp[i],dataArr.location.value,dataArr.location.default,dataArr.location.dependent);
        }
        else if(this.state.primaryData[i].name == "location_cities" && dataArr.location_cities.value != "DM")
        {
          this.appendDefaultDppValuesFromApi(temp[i],dataArr.location_cities.value,dataArr.location_cities.default,dataArr.location_cities.dependent);
        }
        else if(this.state.primaryData[i].name == "mstatus" && dataArr.mstatus.value != "DM")
        {
          this.appendDefaultDppValuesFromApi(temp[i],dataArr.mstatus.value,dataArr.mstatus.default,dataArr.mstatus.dependent);
        }
      }

      
      

      for(let n=0;n<this.state.moreData.length;n++)
      {
        //console.log(this.state.moreData[n]);

        if(this.state.moreData[n].name == "education" && dataArr.education.value != "DM")
        {
          this.state.flagForShowMore++;
          this.appendDefaultDppValuesFromApi(tempMoreData[n],dataArr.education.value,dataArr.education.default,dataArr.education.dependent);
        }
        if(this.state.moreData[n].name == "occupation_grouping" && dataArr.occupation_grouping.value != "DM")
        {

          // console.log(dataArr.employed_in.value);
          // console.log(dataArr.occupation_grouping.value);
          let empV_reg,empL_reg,emp_Tmp=[],finalRegDpp,finalLabelDpp;

          if(dataArr.employed_in.value!="" && dataArr.employed_in.value!="DM")
          {
            empV_reg = dataArr.employed_in.value.split(",");
            empL_reg = dataArr.employed_in.default;  
            if(empV_reg)
            {
              for(let n=0;n<empV_reg.length;n++)
              {
                emp_Tmp.push(empV_reg[n]+"E");
              }

              emp_Tmp = emp_Tmp.toString();

              if(dataArr.occupation_grouping.value!="" && dataArr.occupation_grouping.value!="DM")
              {
                finalRegDpp = emp_Tmp.concat(",").concat(dataArr.occupation_grouping.value);


              }
              else
              {
                finalRegDpp = emp_Tmp.concat;
              }

              finalLabelDpp = dataArr.employed_in.default;
            } 
          }
          else
          {
            finalRegDpp = dataArr.occupation_grouping.value;
            finalLabelDpp = dataArr.occupation_grouping.default;
          }

          
          






          


          this.state.flagForShowMore++;

          this.appendDefaultDppValuesFromApi(
              tempMoreData[n],
              finalRegDpp,
              finalLabelDpp,
              dataArr.occupation_grouping.dependent
              );
        }
        if(this.state.moreData[n].name == "manglik" && dataArr.manglik.value != "DM")
        {
          this.state.flagForShowMore++;
          this.appendDefaultDppValuesFromApi(tempMoreData[n],dataArr.manglik.value,dataArr.manglik.default,dataArr.manglik.dependent);
        }
      }

      return false;


      this.setStateForDppValues(temp,tempMoreData,this.state.flagForShowMore);
    }

    //this function is called from  "appendDefaultDppValues" and is used to set values in the object
    appendDefaultDppValuesFromApi(obj,value,label,dependent,caste="",sectVal="")
    {   

      

      obj.default = label;
      obj.value = value;

     


      if(dependent != "")
        obj.dependent = "+"+dependent+" more";      
      if(caste !== "" && caste !== "DM")
      {
        if(dependent !="")
        {
          if(sectVal)
            obj.dependent = "+"+dependent+" sects";
          else
           obj.dependent = "+"+dependent+" castes";
        }
        obj.dependentValue = caste;
      }     
    }

    //this function is used to sort the original array into a form where it can be used to loop and set into the state
    getSortedDppArr(dataArr)
    {    
      
      let tempArr = {};
      let key = "";     
       
      for(let i=0;i<dataArr.length;i++)
        {
          
          if(DPP_FIELDS.includes(dataArr[i].key))
          {

            if(dataArr[i].key == "P_COUNTRY")
            {
              key = "location";
            }
            else if(dataArr[i].key == "P_CITY")
            {
              key = "location_cities";
            }
            else
            {
              key = dataArr[i].key.substr(2).toLowerCase();
            }

            if(key == "age")
            {
              let ageArr = dataArr[i].value.split(",");
              tempArr[key] = {"value1": ageArr[0],"value2": ageArr[1],"default1": ageArr[0] + " Years","default2": ageArr[1] + " Years"};
            }
            else if(key == "height")
            {
              let heightLabelArr = dataArr[i].label_val.split(" - ");
              let heightValArr = dataArr[i].value.split(",");
              tempArr[key] = {"value1": heightValArr[0],"value2": heightValArr[1],"default1": heightLabelArr[0].replace("&quot;","\""),"default2": heightLabelArr[1].replace("&quot;","\"")};
            }
            else if(key == "income")
            {
              let incomeArr = dataArr[i].value.split(",");
              let incomeValArr = dataArr[i].label_val.split(",");
              let incomeData = incomeValArr[0].split(" and ");
              tempArr[key] = {"value1": incomeArr[0],"value2": incomeArr[1],"default1": incomeData[0],"default2": incomeData[1]};  //check this case for certain values
            }
            else if(key == "location" || key == "location_cities" || key == "education" || key == "mstatus" || key == "occupation_grouping" || key == "mtongue" || key == "manglik" || key == "employed_in" )
            {
              if(dataArr[i].value !="DM")
              {

                
                let valueArr = dataArr[i].value.split(",");
                let labelValArr = dataArr[i].label_val.split(",");


                let dataLength = "";
                let labelVal = "";
                if(valueArr.length>1)
                {
                  dataLength = valueArr.length - 1;
                  labelVal = labelValArr[0];
                }
                else
                {
                  //dataLength = valueArr.length;
                  labelVal = dataArr[i].label_val;
                }

                tempArr[key] = {
                  "default":labelVal,
                  "value":dataArr[i].value,
                  "dependent":dataLength
                };
              }
              else
              {
                tempArr[key] = {
                  "default":dataArr[i].label_val,
                  "value":dataArr[i].value,
                  "dependent":""
                };
              }
            }
            else if(key == "religion")
            {
              //handle this and other cases
              tempArr[key] = {"default":dataArr[i].label_val,"value":dataArr[i].value};
            }
            else if(key == "caste")
            {

              let valueArr = dataArr[i].value.split(",");
              let dataLength = "";
              if(dataArr[i].value != "DM")
                dataLength = valueArr.length;

              tempArr[key] = {"default":dataArr[i].label_val,"value":"","dependent":dataLength};
            }
            else if(key == "caste_mapping") //this is assuming that the casteMapping field is always after caste.
            {
              tempArr["caste"].value = dataArr[i].value;
            }

            key="";
          }
        }

        
        return tempArr;
    }

    setStateForDppValues(temp,tempMoreData,flagForShowMore)
    {
      this.setState({
        primaryData:temp,
        moreData:tempMoreData,
        flagForShowMore:flagForShowMore,        
      });
      this.setGenderForDpp();
    }

    setGenderForDpp()
    {
      if(localStorage.getItem("GENDER") == "F")
      {
        this.setState({
          gender:"M"
        });
      }
      else
      {
        this.setState({
          gender:"F"
        }); 
      }
    }
    //this function takes the form values and calls the api to save the desired values.
    setDPP()
    {
      //console.log("primaryData:",this.state.primaryData);
      //console.log("moreData:",this.state.moreData);
      let paramString='';
      let editFieldArr = {};
      for(let i=0;i<this.state.primaryData.length;i++)
      {
        if(this.state.primaryData[i].name != "sect")
        {
          if(this.state.primaryData[i].name == "age")
          {
            paramString +=("editFieldArr[P_LAGE] ="+ this.state.primaryData[i].value1+"&editFieldArr[P_HAGE] ="+this.state.primaryData[i].value2+"&");
          }
          else if(this.state.primaryData[i].name == "height")
          {
            paramString +=("editFieldArr[P_LHEIGHT] ="+ this.state.primaryData[i].value1+"&editFieldArr[P_HHEIGHT] ="+this.state.primaryData[i].value2+"&");
          }
          else if(this.state.primaryData[i].name == "income")
          {
            paramString +=("editFieldArr[P_LRS] ="+ this.state.primaryData[i].value1+"&editFieldArr[P_HRS] ="+this.state.primaryData[i].value2+"&");

          }

          else if(this.state.primaryData[i].name == "location")
          {
            paramString +=("editFieldArr[P_COUNTRY] ="+ this.state.primaryData[i].value)+"&";
          }
          else if(this.state.primaryData[i].name == "location_cities")
          {
            paramString +=("editFieldArr[P_CITY] ="+ this.state.primaryData[i].value)+"&";
          }
          else if(this.state.primaryData[i].name == "religion")
          {
            paramString +=("editFieldArr[P_"+this.state.primaryData[i].name.toUpperCase()+']='+ this.state.primaryData[i].value)+"&";
            paramString +=("editFieldArr[P_CASTE] ="+ this.fetchCasteString(this.state.primaryData[i].dependentValue)+"&");
          }
          else
            paramString +=("editFieldArr[P_"+this.state.primaryData[i].name.toUpperCase()+']='+ this.state.primaryData[i].value)+"&";
        }
      }

      for(let i=0;i<this.state.moreData.length;i++)
      {
        
        if(this.state.moreData[i].name == "occupation")
        {
          paramString+= "editFieldArr[P_OCCUPATION_GROUPING]="+this.state.moreData[i].value+"&";
        }
        else if(this.state.moreData[i].name == "occupation_grouping")
        {
          let empT=[],occT=[];

          let tempG = this.state.moreData[i].value;

         

          if(tempG!="" && tempG!=null)
          {
            tempG = tempG.split(",");
            if(tempG.length>0)
            {
              for(let i =0;i<tempG.length;i++)
              {
                if (tempG[i].match(/[a-z]/i)) 
                {
                  empT.push(tempG[i].replace(/\D/g,''));
                }
                else
                {
                  occT.push(tempG[i]);
                }
              }
            } 
          } 

          occT = occT.toString();
          empT = empT.toString();

          

          paramString += "editFieldArr[P_OCCUPATION_GROUPING]=";
          paramString += occT;
          paramString += "&"; 

          paramString += "editFieldArr[P_EMPLOYED_IN]=";
          paramString += empT;
          paramString += "&"; 


         


         
        }
        else
        {
          paramString += ("editFieldArr[P_"+this.state.moreData[i].name.toUpperCase()+']='+this.state.moreData[i].value)+"&";
        }
      }


      //console.log("PARAMSTRING:--:",paramString);

      

      //call api and redirect to non spa page (membership)
      let _this = this;


      let callUrl = CONSTANTS.DPP_SUBMIT_API+"?"+paramString;
      commonApiCall(callUrl,'','','POST').then(function(response) {
        _this.redirectToMemPage();
      });
    }

    fetchCasteString(casteJson)
    {
      if(casteJson !="")
      {
        casteJson = JSON.parse(casteJson);
        let alteredCasteArr = [];

        Object.keys(casteJson).forEach(function(key) {
          if(casteJson[key] != "14" && casteJson[key] != "DONT_MATTER" && casteJson[key] !="")
          {
            alteredCasteArr[key] = casteJson[key];
          }
        });        
        return Object.values(alteredCasteArr).toString();
      }
      return "";      
    }
    redirectToMemPage()
    {
      window.location = "/membership/jsms"+window.location.search;
    }

    callGTM(response)
    {      
      for(let i=0;i<response.length;i++)
      {
        if(response[i].key == "GTM")
        {        
          var gtmAge = response[i].age;
          var gtmCity = response[i].city;
          var gtmMtongue = response[i].mtongue;
          var gtmSource = response[i].sourcename;
          var gtmGender = localStorage.getItem("GENDER");          
          break;
        }
      }

      let sampleArr = window.location.search.split("&");
      for(let i=0;i<sampleArr.length;i++)
      {
        if(sampleArr[i].indexOf("groupname")!==-1)
        {
          let groupname = sampleArr[i].split("=");
          var gtmGroupname = groupname[1];
          break;
        }
      }

      this.setState({
        gtmAge:gtmAge,
        gtmMtongue:gtmMtongue,
        gtmCity:gtmCity,
        gtmSource:gtmSource,
        gtmGroupname:gtmGroupname,
        gtmGender:gtmGender,
      });      
    }
    render()
    {        
        let GTM;        
        if(this.props.dppReg && this.state.gtmAge!="")
        {  
          laterGTM(this.state.gtmGroupname,this.state.gtmSource,this.state.gtmAge,this.state.gtmMtongue,this.state.gtmCity,this.state.gtmGender);
        }
                
        let errorView;
        if(this.state.insertError == true)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage} extendedMessage={this.state.extendedStr}></TopError>;
        }

        let loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        }

        var promoView;
        if(this.state.showPromo)
        {
            promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }
        let savedSearchDetailView;
        if(!this.props.dppReg)
        {
          savedSearchDetailView = <div id="savedSearchSection"><SavedSearchPage fromSearchForm="1" getCount = {this.getSavedSearchCount.bind(this)} getSavedSearchResults = {this.getSavedSearchResults.bind(this)}> </SavedSearchPage>
          </div>;
        }

        let savedSearchCountView;
        if(this.state.savedSearchCount && !this.props.dppReg) {
            savedSearchCountView = <div className="posabs savsrc-pos2">
                <div className="txtc color6 f12 roundIcon">{this.state.savedSearchCount}</div>
            </div>;
        }
        let savedSearchView,genderView,hamView;
        if(this.state.loggedInStatus == true) {
            if(this.state.savedSearchCount  && !this.props.dppReg)
            {
                savedSearchView = <div className="dispibl fr" id="savedSearchIcon" onClick={()=>this.goToSavedSearchView()}>
                <i className="savsrc-sp savsrc-icon1"></i>
                {savedSearchCountView}
                </div>;
            }
            if(this.props.myjsData.apiDataHam != undefined)
               hamView = <HamMain bellResponse={this.props.myjsData.apiDataHam.hamburgerDetails} ref="Hamchild" page="others"></HamMain>;
             else
               hamView = <HamMain ref="Hamchild" page="others"></HamMain>;
        } else {
            genderView = <div id="search_GENDER">
                <div className="pad3 brdr1 txtc">
                    <div className="brdr12 fullwid">
                        <div id="search_GENDERF" onClick={(e) => this.changeTab(e)} className="defaultTab gender">
                            Bride
                        </div>
                        <div id="search_GENDERM" onClick={(e) => this.changeTab(e)} className="defaultTab gender">
                            Groom
                        </div>
                    </div>
                </div>
            </div>;
            hamView = <HamMain ref="Hamchild" page="Login"></HamMain>;
        }

        let headerView;
        if(this.props.dppReg)
        {          
          headerView = <div>
                        <div className="bg1 padd22 ">
                          <div className="posrel">

                            <div className="white fontthin f19 txtc dispibl wid100p">
                              Partner Preference              
                            </div>
                            <div id="skipBtn1" className="posabs rv2_pos1 fontthin">
                              <div onClick = {() => this.redirectToMemPage()}className="white" ref="skip"> Skip </div>
                            </div>

                          </div>
                      </div>
          <div className="bg11 padd22">
          <div className="fontthin f14 txtc dispibl wid100p">
                The criteria below influences the matches and interests you receive.
            </div>
          </div>
          </div>;
        }
        else
        {
          headerView = <div className="bg1 padd22">
            <i id="hamburgerIcon" onClick={() => this.showHam()} className="fl dispbl mainsp baricon"></i>
            <div className="white fontthin f19 txtc dispibl wid84p">
                Search Your Match
            </div>
            {savedSearchView}
          </div>;
        }

        let photoView;
        if(!this.props.dppReg)
        {
          photoView = <div id="search_PHOTO">
            <div className="pad3 brdr1 txtc">
                <div className="brdr12 fullwid">
                    <div id="searchform_all" onClick={(e) => this.changeTab(e)} className="defaultTab havePhoto">
                        All Profiles
                    </div>
                    <div id="searchform_photo" onClick={(e) => this.changeTab(e)} className="defaultTab havePhoto">
                        Profile with Photos
                    </div>
                </div>
            </div>
        </div>;
        }
        let moreOptionsView,showHideMore;



        if(this.state.showMore == false)
        {
          moreOptionsView = <div onClick={() => this.changeMore()} className="showmorelink pad18 txtc bg6" id="moreoptions">
                <span className="moreoptions color8">More Options +  </span>
                <i className="mainsp arow7 fr"></i>
            </div>;
        }
        else
        {
          moreOptionsView = <div  onClick={() => this.changeMore()} className="showlesslink pad18 txtc" id="lessoptions0" rel="0">
                <span className="lessoptions">Less Options - </span>
                <i className="arow8 fr"></i>
            </div>;
        }

        let moreContainer;

        if(this.state.showMore== false)
        {
          moreContainer = <div className="showMoreDiv scrollhid" id="moreDetails">
              {this.getMoreView()}
          </div>;
        }
        else {
          moreContainer = <div className="showMoreDiv scrollhid openShowMoreDiv" id="moreDetails">
              {this.getMoreView()}
          </div>;
        }

        let resultButton;
        if(this.props.dppReg)
        {
          resultButton = <div id="search_submit" className="bg7 white fullwid dispbl txtc lh50 pinkRipple" onClick={(e) => this.setDPP(e)}>Submit</div>
        }
        else
        {
         resultButton = <div id="search_submit" className="bg7 white fullwid dispbl txtc lh50 pinkRipple" onClick={(e) => this.searchProfiles(e)}>Search</div>;
        }

        this.trackJsb9 = 1;

        return (
            <div id="SearchFormPage">
                {/*<GA ref="GAchild" />*/}
                  <DropMain ref="Dropchild" update={this.updateSF.bind(this)} />
                {promoView}
                {hamView}
                {errorView}
                {loaderView}
                <div className=" bg4" id="mainContent">
                    {headerView}
                    {genderView}
                    {this.getMainView()}
                    {photoView}
                    {moreOptionsView}
                    {moreContainer}
                    {resultButton}
                    {savedSearchDetailView}
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
     myjsData: state.MyjsReducer
    }
}

export default connect(mapStateToProps)(withLastLocation(SearchFormPage))
