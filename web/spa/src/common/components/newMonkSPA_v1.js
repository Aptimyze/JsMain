//let { page } = window.interfaces;
// import * as config from "../../config.js"
import axios from "axios";
let newmonk = {
        performance: 'https://lb.jeevansathi.com/nLogger/boomLogger.php',
        feedback: 'https://lb.jeevansathi.com/nLogger/feedback.php',
        launchOrInstall: 'https://lb.jeevansathi.com/nLogger/webAppLogger.php',
        csmSummaryUrl: 'http://dev2.csm.infoedge.com/siddharth/web/app_dev.php/csm/summaryWidget'
    };
var extend = function () {

    // Variables
    var extended = {};
    var deep = false;
    var i = 0;
    var length = arguments.length;

    // Check if a deep merge
    if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
        deep = arguments[0];
        i++;
    }

    // Merge the object into the extended object
    var merge = function (obj) {
        for ( var prop in obj ) {
            if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
                // If deep merge and property is an object, merge properties
                if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
                    extended[prop] = extend( true, extended[prop], obj[prop] );
                } else {
                    extended[prop] = obj[prop];
                }
            }
        }
    };

    // Loop through each object and conduct a merge
    for ( ; i < length; i++ ) {
        var obj = arguments[i];
        merge(obj);
    }

    return extended;

};
//let beaconBaseURL = 'http://192.168.2.116:30080/nLogger/boomLogger.php?data={"rt.start":"navigation","t_resp":"0","t_page":"0","t_done":"0","r":"","r2":"","t_resr":{"t_domloaded":0},"u":"","nt_dns":"0","appId":"126","tag":"","event":""}',
let beaconJSON = {
    "rt.start": "navigation",
    "nt_red":"0",
    "nt_dns": "0",
    "nt_tcp":"0",
    "nt_req":"0",
    "nt_res":"0",
    "t_resp": "0",
    "nt_plt":"0",
    "nt_render":"0",
    "t_page": "0",
    "t_done": "0",
    "r": "",
    "r2": "",
    "u": "",
    "tag": "",
    "appId": 201,
    "t_resr": { "t_domloaded": 0 },
    "event": "routeChange"
}
let webAppTracking = {
    act:null,
    appId: 201
}
let winLoad=false
let	referrer
window.RCCT
window.RCST
let checkWinLoad

let obj =  {
    startTimer: (RCS) => {
        if (RCS) {
            if (!window.RCCT) {
                window.RCST = new Date().getTime()
            }
        } else {
            window.RCCT = new Date().getTime()
        }
    },
    stopTimer: () => {
        if(window.RCCT || window.RCST){
            let diff = new Date().getTime() - (window.RCCT || window.RCST)
            window.RCCT=null
            window.RCST=null
            return diff
        }
        else{
            return null
        }
    },
    postData: (opts) => {

        if (process.env.NODE_ENV == "dev") {
            return false;
        }

        let endTime = null
        if(!opts.type){opts.type = "performance"}
        if (opts.type == "performance" && !opts.pageLoad) {
            endTime = obj.stopTimer();
            if (!endTime) {return;}
        }
        let urlMap = {
            performance : {
                url : newmonk.performance,
                dataObj : (opts.pageload) ? beaconJSON : extend({},beaconJSON,{"t_done":endTime})
            },
            launchOrInstall : {
                url : newmonk.launchOrInstall,
                dataObj : webAppTracking
            }
        }
        let beaconData = extend({},urlMap[opts.type].dataObj,opts.data)
        let url = urlMap[opts.type].url+"?data="+JSON.stringify(beaconData)+'&'+Math.random()
        axios.get(url);
        // $.ajax({
        //     xhrFields: {
        //         withCredentials: false
        //     },
        //     method: 'GET',
        //     url: url
        // })
    },
    sendBeacon: (tag) => {
    	obj.postData({"t_done":obj.stopTimer(),tag:tag,"event":"customTimer"})
    },
    attachOnForms : () => {
        let forms = $(document).find('form')
        $.each(forms,(form,index) => {
            submitService.addCallback(form.name, {success:() => {obj.startTimer()}})
        })
    },
    postDataOnWinLoad : (opts) => {
        if(winLoad){
            clearInterval(checkWinLoad)
            let state = opts.store.getState()
            // let routeName = state.route.routeName
            let routeName = 'abc';
            let pageReferrer = document.referrer
            let pagePath = document.location.href
            // let pageTitle = state.route.title
            let pageTitle = 'def';
            // dataLayer.push({
            //    'event':'spa-pageview',
            //    'spa-page-name':routeName,x
            //    'spa-page-title':pageTitle,
            //    'spa-page-referrer':pageReferrer
            // })

            let t = performance.timing
            let redirect = t.redirectEnd - t.redirectStart
            let dns = t.domainLookupEnd - t.domainLookupStart
            let tcp = t.connectEnd - t.connectStart
            let request = (t.responseStart - t.navigationStart) - (t.requestStart - t.navigationStart)
            let res = t.responseEnd - t.responseStart
            let response = t.responseStart - t.navigationStart
            let plt = t.domInteractive - t.domLoading
            let render = t.domComplete - t.domLoading
            let page = (t.loadEventEnd - t.navigationStart) - (t.responseStart - t.navigationStart)
            let done = t.loadEventEnd - t.navigationStart
            let referrer = document.referrer

            obj.postData({
                type:"performance",
                pageLoad:true,
                data : {
                    "nt_red":redirect,
                    "nt_dns":dns,
                    "nt_tcp":tcp,
                    "nt_req":request,
                    "nt_res":res,
                    "t_resp":response,
                    "nt_plt":plt,
                    "nt_render":render,
                    "t_page":page,
                    "t_done":done,
                    "r":encodeURIComponent(pageReferrer),
                    "u":encodeURIComponent(pagePath),
                    tag:opts.tagName,
                    "event":"WindowLoad"
                }
            })
        }
    },
    init : (store,hash) => {
        obj.startTimer(true);
        if(winLoad){
            // obj.postData({"t_done":obj.stopTimer(), tag:tagName, "event":"ViewContentLoaded"});
        }
        else{
            checkWinLoad = setInterval(() => {obj.postDataOnWinLoad({"tagName":hash,"store":store})}, 10);
        }
    }
}

window.onload = () => {setTimeout(() => {winLoad = true},200)}

// if (process.env.NODE_ENV !== "dev") {
    // page.exit('*', (ctx, next) => {
    //     let path = ctx.path
    //     obj.startTimer(true)
    //     next(ctx)
    // });
// }

export default obj