(function(f){var e=f._AP?_AP:(f.RA=f.AP={});
var d={};
e.define=function(i,k,h){var j=c(i),g;
if(!h){h=k;
k=[]
}if(h){g=typeof h!=="function"?function(){return h
}:h;
b(k,function(){var m=g.apply(f,arguments);
if(m){if(typeof m==="function"){j.exports.__target__=m
}for(var l in m){if(m.hasOwnProperty(l)){j.exports[l]=m[l]
}}}})
}};
e.require=function(g,h){b(typeof g==="string"?[g]:g,h)
};
function b(l,m){var j=[],h=0,g=l.length;
function k(p){j.push(p);
if(j.length===g){var n=[],o=0;
for(;
o<g;
o+=1){n[o]=j[o].exports
}if(m){m.apply(f,n)
}}}if(l&&l.length>0){for(;
h<g;
h+=1){a(l[h],k)
}}else{if(m){m()
}}}function a(g,h){h(c(g))
}function c(g){return d[g]=d[g]||{name:g,exports:function(){function h(){var i=h.__target__;
if(i){return i.apply(f,arguments)
}}return h
}()}
}}(this));
AP.define("_util",function(){function d(h,g){var e,f;
if(h){e=h.length;
if(e!=null&&typeof h!=="function"){f=0;
while(f<e){if(g.call(h[f],f,h[f])===false){break
}f+=1
}}else{for(f in h){if(h.hasOwnProperty(f)){if(g.call(h[f],f,h[f])===false){break
}}}}}}function a(e,f){e+="EventListener";
f+="Event";
return function(h,i,g){if(h[e]){h[e](i,g,false)
}else{if(h[f]){h[f]("on"+i,g)
}}}
}function c(){var f=this.console;
if(f&&f.log){var g=[].slice.call(arguments);
if(f.log.apply){f.log.apply(f,g)
}else{for(var h=0,e=g.length;
h<e;
h+=1){g[h]=JSON.stringify(g[h])
}f.log(g.join(" "))
}return true
}}function b(e){return e==null?null:decodeURIComponent(e.replace(/\+/g,"%20"))
}return{each:d,extend:function(f){var e=arguments,g=[].slice.call(e,1,e.length);
d(g,function(h,j){d(j,function(l,i){f[l]=i
})
});
return f
},bind:a("add","attach"),unbind:a("remove","detach"),trim:function(e){return e&&e.replace(/^\s+|\s+$/g,"")
},debounce:function(e,g){var f;
return function(){var h=this,j=[].slice.call(arguments);
function i(){f=null;
e.apply(h,j)
}if(f){clearTimeout(f)
}f=setTimeout(i,g||50)
}
},inArray:function(h,i,g){if(Array.prototype.indexOf){return Array.prototype.indexOf.call(i,h,g)
}var f=g>>>0,e=i.length>>>0;
for(;
f<e;
f+=1){if(i[f]===h){return f
}}return -1
},isFunction:function(e){return typeof e==="function"
},log:c,handleError:function(e){if(!c.apply(this,e&&e.message?[e,e.message]:[e])){throw e
}},decodeQueryComponent:b}
});
AP.define("_dollar",["_util"],function(b){var d=b.each,e=b.extend,a=window.document;
function c(i,h){h=h||a;
var g=[];
if(i){if(typeof i==="string"){var f=h.querySelectorAll(i);
d(f,function(k,j){g.push(j)
})
}else{if(i.nodeType===1){g.push(i)
}else{if(i===window){g.push(i)
}}}}e(g,{each:function(j){d(this,j);
return this
},bind:function(j,k){this.each(function(l,m){b.bind(m,j,k)
})
},attr:function(l){var j;
this.each(function(k,m){j=m[l]||(m.getAttribute&&m.getAttribute(l));
return !j
});
return j
},removeClass:function(j){return this.each(function(k,l){if(l.className){l.className=l.className.replace(new RegExp("(^|\\s)"+j+"(\\s|$)")," ")
}})
},html:function(j){return this.each(function(k,l){l.innerHTML=j
})
},append:function(j){return this.each(function(k,m){var l=h.createElement(j.tag);
d(j,function(o,n){if(o==="$text"){if(l.styleSheet){l.styleSheet.cssText=n
}else{l.appendChild(h.createTextNode(n))
}}else{if(o!=="tag"){l[o]=n
}}});
m.appendChild(l)
})
}});
return g
}return e(c,b)
});
(window.AP||window._AP).define("_events",["_dollar"],function(f){var b=window,d=(b.AJS&&b.AJS.log)||(b.console&&b.console.log)||(function(){});
function a(h,g){this._key=h;
this._origin=g;
this._events={};
this._any=[]
}var e=a.prototype;
e.on=function(g,h){if(g&&h){this._listeners(g).push(h)
}return this
};
e.once=function(h,i){var g=this;
var j=function(){g.off(h,j);
i.apply(null,arguments)
};
this.on(h,j);
return this
};
e.onAny=function(g){this._any.push(g);
return this
};
e.off=function(g,k){var j=this._events[g];
if(j){var h=f.inArray(k,j);
if(h>=0){j.splice(h,1)
}if(j.length===0){delete this._events[g]
}}return this
};
e.offAll=function(g){if(g){delete this._events[g]
}else{this._events={}
}return this
};
e.offAny=function(j){var h=this._any;
var g=f.inArray(j,h);
if(g>=0){h.splice(g,1)
}return this
};
e.emit=function(g){return this._emitEvent(this._event.apply(this,arguments))
};
e._event=function(g){return{name:g,args:[].slice.call(arguments,1),attrs:{},source:{key:this._key,origin:this._origin}}
};
e._emitEvent=function(h){var g=h.args.concat(h);
c(this._listeners(h.name),g);
c(this._any,[h.name].concat(g));
return this
};
e._listeners=function(g){return this._events[g]=this._events[g]||[]
};
function c(j,g){for(var h=0;
h<j.length;
++h){try{j[h].apply(null,g)
}catch(k){d(k.stack||k.message||k)
}}}return{Events:a}
});
(window.AP||window._AP).define("_base64",["_dollar"],function(e){function i(){this.buffer=[]
}i.prototype.append=function c(j){this.buffer.push(j);
return this
};
i.prototype.toString=function b(){return this.buffer.join("")
};
var f={codex:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(l){var j=new i();
var k=new d(l);
while(k.moveNext()){var s=k.current;
k.moveNext();
var q=k.current;
k.moveNext();
var o=k.current;
var r=s>>2;
var p=((s&3)<<4)|(q>>4);
var n=((q&15)<<2)|(o>>6);
var m=o&63;
if(isNaN(q)){n=m=64
}else{if(isNaN(o)){m=64
}}j.append(this.codex.charAt(r)+this.codex.charAt(p)+this.codex.charAt(n)+this.codex.charAt(m))
}return j.toString()
},decode:function(l){var k=new i();
var o=new h(l);
while(o.moveNext()){var j=o.current;
if(j<128){k.append(String.fromCharCode(j))
}else{if((j>191)&&(j<224)){o.moveNext();
var n=o.current;
k.append(String.fromCharCode(((j&31)<<6)|(n&63)))
}else{o.moveNext();
var n=o.current;
o.moveNext();
var m=o.current;
k.append(String.fromCharCode(((j&15)<<12)|((n&63)<<6)|(m&63)))
}}}return k.toString()
}};
function d(j){this._input=j;
this._index=-1;
this._buffer=[]
}d.prototype={current:Number.NaN,moveNext:function(){if(this._buffer.length>0){this.current=this._buffer.shift();
return true
}else{if(this._index>=(this._input.length-1)){this.current=Number.NaN;
return false
}else{var j=this._input.charCodeAt(++this._index);
if((j==13)&&(this._input.charCodeAt(this._index+1)==10)){j=10;
this._index+=2
}if(j<128){this.current=j
}else{if((j>127)&&(j<2048)){this.current=(j>>6)|192;
this._buffer.push((j&63)|128)
}else{this.current=(j>>12)|224;
this._buffer.push(((j>>6)&63)|128);
this._buffer.push((j&63)|128)
}}return true
}}}};
function h(j){this._input=j;
this._index=-1;
this._buffer=[]
}h.prototype={current:64,moveNext:function(){if(this._buffer.length>0){this.current=this._buffer.shift();
return true
}else{if(this._index>=(this._input.length-1)){this.current=64;
return false
}else{var p=f.codex.indexOf(this._input.charAt(++this._index));
var o=f.codex.indexOf(this._input.charAt(++this._index));
var n=f.codex.indexOf(this._input.charAt(++this._index));
var m=f.codex.indexOf(this._input.charAt(++this._index));
var l=(p<<2)|(o>>4);
var k=((o&15)<<4)|(n>>2);
var j=((n&3)<<6)|m;
this.current=l;
if(n!=64){this._buffer.push(k)
}if(m!=64){this._buffer.push(j)
}return true
}}}};
function g(j){return window.btoa?window.btoa(j):f.encode(j)
}function a(j){return window.atob?window.atob(j):f.decode(j)
}return{encode:g,decode:a}
});
/*!
 * jsUri
 * https://github.com/derek-watson/jsUri
 *
 * Copyright 2013, Derek Watson
 * Released under the MIT license.
 *
 * Includes parseUri regular expressions
 * http://blog.stevenlevithan.com/archives/parseuri
 * Copyright 2007, Steven Levithan
 * Released under the MIT license.
 */
(this.AP||this._AP).define("_uri",[],function(){var d={starts_with_slashes:/^\/+/,ends_with_slashes:/\/+$/,pluses:/\+/g,query_separator:/[&;]/,uri_parser:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/};
if(!Array.prototype.forEach){Array.prototype.forEach=function(j,h){for(var g=0,f=this.length;
g<f;
++g){j.call(h||this,this[g],g,this)
}}
}function e(f){if(f){f=decodeURIComponent(f);
f=f.replace(d.pluses," ")
}return f
}function c(i){var j=d.uri_parser;
var g=["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"];
var f=j.exec(i||"");
var h={};
g.forEach(function(l,k){h[l]=f[k]||""
});
return h
}function a(o){var h,m,l,q,g,f;
var j=[];
if(typeof(o)==="undefined"||o===null||o===""){return j
}if(o.indexOf("?")===0){o=o.substring(1)
}m=o.toString().split(d.query_separator);
for(h=0;
h<m.length;
h++){l=m[h];
q=l.indexOf("=");
if(q!==0){g=decodeURIComponent(l.substring(0,q));
f=decodeURIComponent(l.substring(q+1).replace(/\+/g," "));
j.push(q===-1?[l,null]:[g,f])
}}return j
}function b(f){this.uriParts=c(f);
this.queryPairs=a(this.uriParts.query);
this.hasAuthorityPrefixUserPref=null
}["protocol","userInfo","host","port","path","anchor"].forEach(function(f){b.prototype[f]=function(g){if(typeof g!=="undefined"){this.uriParts[f]=g
}return this.uriParts[f]
}
});
b.prototype.hasAuthorityPrefix=function(f){if(typeof f!=="undefined"){this.hasAuthorityPrefixUserPref=f
}if(this.hasAuthorityPrefixUserPref===null){return(this.uriParts.source.indexOf("//")!==-1)
}else{return this.hasAuthorityPrefixUserPref
}};
b.prototype.query=function(j){var g="",f,h;
if(typeof j!=="undefined"){this.queryPairs=a(j)
}for(f=0;
f<this.queryPairs.length;
f++){h=this.queryPairs[f];
if(g.length>0){g+="&"
}if(h[1]===null){g+=h[0]
}else{g+=h[0];
g+="=";
if(h[1]){g+=encodeURIComponent(h[1])
}}}return g.length>0?"?"+g:g
};
b.prototype.getQueryParamValue=function(g){var h,f;
for(f=0;
f<this.queryPairs.length;
f++){h=this.queryPairs[f];
if(g===h[0]){return h[1]
}}};
b.prototype.getQueryParamValues=function(h){var f=[],g,j;
for(g=0;
g<this.queryPairs.length;
g++){j=this.queryPairs[g];
if(h===j[0]){f.push(j[1])
}}return f
};
b.prototype.deleteQueryParam=function(j,m){var f=[],h,l,g,k;
for(h=0;
h<this.queryPairs.length;
h++){l=this.queryPairs[h];
g=e(l[0])===e(j);
k=l[1]===m;
if((arguments.length===1&&!g)||(arguments.length===2&&(!g||!k))){f.push(l)
}}this.queryPairs=f;
return this
};
b.prototype.addQueryParam=function(g,h,f){if(arguments.length===3&&f!==-1){f=Math.min(f,this.queryPairs.length);
this.queryPairs.splice(f,0,[g,h])
}else{if(arguments.length>0){this.queryPairs.push([g,h])
}}return this
};
b.prototype.replaceQueryParam=function(k,h,f){var g=-1,j,l;
if(arguments.length===3){for(j=0;
j<this.queryPairs.length;
j++){l=this.queryPairs[j];
if(e(l[0])===e(k)&&decodeURIComponent(l[1])===e(f)){g=j;
break
}}this.deleteQueryParam(k,f).addQueryParam(k,h,g)
}else{for(j=0;
j<this.queryPairs.length;
j++){l=this.queryPairs[j];
if(e(l[0])===e(k)){g=j;
break
}}this.deleteQueryParam(k);
this.addQueryParam(k,h,g)
}return this
};
["protocol","hasAuthorityPrefix","userInfo","host","port","path","query","anchor"].forEach(function(f){var g="set"+f.charAt(0).toUpperCase()+f.slice(1);
b.prototype[g]=function(h){this[f](h);
return this
}
});
b.prototype.scheme=function(){var f="";
if(this.protocol()){f+=this.protocol();
if(this.protocol().indexOf(":")!==this.protocol().length-1){f+=":"
}f+="//"
}else{if(this.hasAuthorityPrefix()&&this.host()){f+="//"
}}return f
};
b.prototype.origin=function(){var f=this.scheme();
if(f=="file://"){return f+this.uriParts.authority
}if(this.userInfo()&&this.host()){f+=this.userInfo();
if(this.userInfo().indexOf("@")!==this.userInfo().length-1){f+="@"
}}if(this.host()){f+=this.host();
if(this.port()){f+=":"+this.port()
}}return f
};
b.prototype.addTrailingSlash=function(){var f=this.path()||"";
if(f.substr(-1)!=="/"){this.path(f+"/")
}return this
};
b.prototype.toString=function(){var g,f=this.origin();
if(this.path()){g=this.path();
if(!(d.ends_with_slashes.test(f)||d.starts_with_slashes.test(g))){f+="/"
}else{if(f){f.replace(d.ends_with_slashes,"/")
}g=g.replace(d.starts_with_slashes,"/")
}f+=g
}else{if(this.host()&&(this.query().toString()||this.anchor())){f+="/"
}}if(this.query().toString()){if(this.query().toString().indexOf("?")!==0){f+="?"
}f+=this.query().toString()
}if(this.anchor()){if(this.anchor().indexOf("#")!==0){f+="#"
}f+=this.anchor()
}return f
};
b.prototype.clone=function(){return new b(this.toString())
};
return{init:b}
});
(this.AP||this._AP).define("_ui-params",["_dollar","_base64","_uri"],function(c,a,b){return{encode:function(d){return a.encode(JSON.stringify(d))
},fromUrl:function(d){var d=new b.init(d),e=d.getQueryParamValue("ui-params");
return this.decode(e)
},decode:function(g){var f={};
if(g&&g.length>0){try{f=JSON.parse(a.decode(g))
}catch(d){if(console&&console.log){console.log("Cannot decode passed ui params",g)
}}}return f
}}
});
(this.AP||this._AP).define("_xdm",["_events","_base64","_uri"],function(c,b,f){var a=window,g=a.location.toString(),e=0;
function d(F,L,P){var p,I,h,k,K,R,J,O,u,v=P.local||{},B=P.remote||[],D=A(g);
var j=function(){var S={};
return{add:function(V,U,T){S[V]={done:U||null,fail:T||null,async:!!U}
},invoke:function(V,U,T){var W;
if(S[U]){if(S[U][V]){S[U][V](T);
W=true
}else{W=!S[U].async&&V!=="fail"
}delete S[U]
}return W
}}
}();
if(!/xdm_e/.test(g)){var q=x(L);
h=q.contentWindow;
J=n(L.remote,"oauth_consumer_key");
O=L.remoteKey;
u=O;
k=A(L.remote);
K=L.channel;
R={isHost:true,iframe:q,destroy:function(){w();
if(p.iframe){F(p.iframe).remove();
delete p.iframe
}},isActive:function(){return F.contains(document.documentElement,p.iframe)
}}
}else{h=a.parent;
J="local";
var H=n(g,"jwt");
O=H?m(H):n(g,"oauth_consumer_key");
if(null==O){O=Math.random()
}u=J;
k=n(g,"xdm_e");
K=n(g,"xdm_c");
R={isActive:function(){return true
}}
}I=u+"|"+(e+=1);
p=F.extend({id:I,remoteOrigin:k,channel:K},R);
function C(S,U,V){try{h.postMessage(JSON.stringify({c:K,i:S,t:U,m:V}),k)
}catch(T){o(z(T))
}}function Q(V,W,U,T){var S=Math.floor(Math.random()*1000000000).toString(16);
j.add(S,U,T);
C(S,"request",{n:V,a:W})
}function M(S,T){C(S,"done",T)
}function N(S,T){C(S,"fail",T)
}function i(Y){try{var ac=JSON.parse(Y.data),W=ac.i,X=ac.c,ae=ac.t,ad=ac.m;
if(Y.source!==h||Y.origin!==k||X!==K){return
}if(ae==="request"){var S=ad.n,aa=ad.a,ab=v[S],V,T,U;
if(ab){V=function(af){M(W,af)
};
T=function(af){N(W,af)
};
U=(aa?aa.length:0)<ab.length;
try{if(U){ab.apply(v,aa.concat([V,T]))
}else{V(ab.apply(v,aa))
}}catch(Z){T(z(Z))
}}else{y("Unhandled request:",ac)
}}else{if(ae==="done"||ae==="fail"){if(!j.invoke(ae,W,ad)){y("Unhandled response:",ae,W,ad)
}}}}catch(Z){o(z(Z))
}}function r(S){return function(){var V=[].slice.call(arguments),U,T;
function W(){if(F.isFunction(V[V.length-1])){return V.pop()
}}T=W();
U=W();
if(!U){U=T;
T=undefined
}Q(S,V,U,T)
}
}F.each(B,function(T,S){if(typeof T==="number"){T=S
}p[T]=r(T)
});
var s=p.events=new c.Events(J,D);
s.onAny(function(){var T=arguments[arguments.length-1];
var U=T.trace=T.trace||{};
var S=I+"|xdm";
if((p.isHost&&!U[S]&&T.source.channel!==I)||(!p.isHost&&T.source.key===J)){U[S]=true;
T=F.extend({},T);
delete T.trace;
y("Forwarding "+(p.isHost?"host":"addon")+" event:",T);
Q("_event",[T])
}});
v._event=function(S){delete S.trace;
if(p.isHost){S.source={channel:I,key:O,origin:k}
}y("Receiving "+(p.isHost?"addon":"host")+" event:",S);
s._emitEvent(S)
};
function E(S){if(p.isActive()){i(S.originalEvent?S.originalEvent:S)
}else{w()
}}function l(){F(window).bind("message",E)
}function w(){F(window).unbind("message",E)
}function n(T,S){return new f.init(T).getQueryParamValue(S)
}function A(S){return new f.init(S).origin()
}function G(T,U){var S=new f.init(T);
F.each(U,function(W,V){S.addQueryParam(W,V)
});
return S.toString()
}function x(S){var T=document.createElement("iframe"),U="easyXDM_"+S.container+"_provider";
F.extend(T,{id:U,name:U,frameBorder:"0"},S.props);
T.setAttribute("rel","nofollow");
F("#"+S.container).append(T);
T.src=S.remote;
return T
}function z(S){return S.message||S.toString()
}function y(){if(d.debug){o.apply(a,["DEBUG:"].concat([].slice.call(arguments)))
}}function o(){var S=F.log||(a.AJS&&a.AJS.log);
if(S){S.apply(a,arguments)
}}function m(S){return t(S)["iss"]
}function t(V){if(null==V||""==V){throw ("Invalid JWT: must be neither null nor empty-string.")
}var U=V.indexOf(".");
var T=V.indexOf(".",U+1);
if(U<0||T<=U){throw ('Invalid JWT: must contain 2 period (".") characters.')
}var W=V.substring(U+1,T);
if(null==W||""==W){throw ("Invalid JWT: encoded claims must be neither null nor empty-string.")
}var S=b.decode(W);
return JSON.parse(S)
}l();
return p
}return d
});
AP.define("_rpc",["_dollar","_xdm"],function(b,c){var l=b.each,j=b.extend,d=b.isFunction,k={},e,f={},a=["init"],g={},i=[],h;
return{extend:function(m){if(d(m)){m=m(k)
}j(f,m.apis);
j(g,m.internals);
a=a.concat(m.stubs||[]);
var n=m.init;
if(d(n)){i.push(n)
}return m.apis
},init:function(m){m=m||{};
if(!h){l(f,function(n){a.push(n)
});
e=this.rpc=new c(b,{},{remote:a,local:g});
e.init();
j(k,e);
l(i,function(n,p){try{p(j({},m))
}catch(o){b.handleError(o)
}});
h=true
}}}
});
AP.define("events",["_dollar","_rpc"],function(a,b){return b.extend(function(d){var c={};
a.each(["on","once","onAny","off","offAll","offAny","emit"],function(f,e){c[e]=function(){var g=d.events;
g[e].apply(g,arguments);
return c
}
});
return{apis:c}
})
});
AP.define("env",["_dollar","_rpc"],function(b,c){var a=c.extend(function(d){return{apis:{getLocation:function(e){d.getLocation(e)
},getUser:function(e){d.getUser(e)
},getTimeZone:function(e){d.getTimeZone(e)
},fireEvent:function(f,e){console.log("AP.fireEvent deprecated; will be removed in future version")
},resize:b.debounce(function(f,e){var g=a.size(f,e,a.container());
d.resize(g.w,g.h)
},50),sizeToParent:b.debounce(function(){d.sizeToParent()
},50)}}
});
return b.extend(a,{meta:function(d){if(navigator.userAgent.indexOf("MSIE 8")>=0){var e,f=document.getElementsByTagName("meta");
for(e=0;
e<f.length;
e++){if(f[e].getAttribute("name")==="ap-"+d){return f[e].getAttribute("content")
}}}else{return b("meta[name='ap-"+d+"']").attr("content")
}},container:function(){var d=b(".ac-content, #content");
return d.length>0?d[0]:document.body
},localUrl:function(d){return this.meta("local-base-url")+(d==null?"":d)
},size:function(j,d,f){var e=j==null?"100%":j,i,g;
if(d){i=d
}else{g=Math.max(f.scrollHeight,document.documentElement.scrollHeight,f.offsetHeight,document.documentElement.offsetHeight,f.clientHeight,document.documentElement.clientHeight);
if(f===document.body){i=g
}else{i=Math.max(f.offsetHeight,f.clientHeight);
if(i===0){i=g
}}}return{w:e,h:i}
}})
});
AP.define("request",["_dollar","_rpc"],function(d,e){var c=d.each,f=d.extend;
function b(g){var i=f({},g);
var h=g.headers||{};
delete i.headers;
return f(i,{getResponseHeader:function(j){var k=null;
if(j){j=j.toLowerCase();
c(h,function(m,l){if(m.toLowerCase()===j){k=l;
return false
}})
}return k
},getAllResponseHeaders:function(){var j="";
c(h,function(m,l){j+=(j?"\r\n":"")+m+": "+l
});
return j
}})
}var a=e.extend(function(g){return{apis:{request:function(l,k){var m,j;
function i(o){return m(o[0],o[1],b(o[2]))
}function h(o){return j(b(o[0]),o[1],o[2])
}if(typeof l==="object"){k=l
}else{if(!k){k={url:l}
}else{k.url=l
}}function n(){}m=k.success||n;
delete k.success;
j=k.error||n;
delete k.error;
g.request(k,i,h)
}}}
});
return a.request
});
AP.define("dialog",["_dollar","_rpc","_ui-params","_uri"],function(e,g,f,c){var a=Boolean(f.fromUrl(window.location.toString()).dlg),b,d=new c.init(window.location.toString());
if(d.getQueryParamValue("dialog")==="1"){a=true
}g.extend(function(i){var h={};
b={create:function(j){i.createDialog(j);
return{on:function(k,l){i.events.once("dialog."+k,l)
}}
},close:function(j){i.events.emit("dialog.close",j);
i.closeDialog()
},isDialog:a,onDialogMessage:function(j,k){this.getButton(j).bind(k)
},getButton:function(j){return{name:j,enable:function(){i.setDialogButtonEnabled(j,true)
},disable:function(){i.setDialogButtonEnabled(j,false)
},toggle:function(){var k=this;
k.isEnabled(function(l){k[l?"disable":"enable"](j)
})
},isEnabled:function(k){i.isDialogButtonEnabled(j,k)
},bind:function(l){var k=h[j];
if(!k){k=h[j]=[]
}k.push(l)
},trigger:function(){var m=this,l=true,k=true,n=h[j];
e.each(n,function(o,p){k=p.call(m,{button:m,stopPropagation:function(){l=false
}});
return l
});
return !!k
}}
}};
return{internals:{dialogMessage:function(k){var j=true;
try{if(a){j=b.getButton(k).trigger()
}else{e.handleError("Received unexpected dialog button event from host:",k)
}}catch(l){e.handleError(l)
}return j
}},stubs:["setDialogButtonEnabled","isDialogButtonEnabled","createDialog","closeDialog"]}
});
return b
});
AP.define("inline-dialog",["_dollar","_rpc"],function(b,c){var a;
c.extend(function(d){a={hide:function(){d.hideInlineDialog()
}};
return{stubs:["hideInlineDialog"]}
});
return a
});
AP.define("messages",["_dollar","_rpc"],function(c,d){var b=0;
function a(){b++;
return"ap-message-"+b
}return d.extend(function(f){var e={};
c.each(["generic","error","warning","success","info","hint"],function(h,g){e[g]=function(k,i,j){j=j||{};
j.id=a();
f.showMessage(g,k,i,j);
return j.id
}
});
e.clear=function(g){f.clearMessage(g)
};
return{apis:e,stubs:["showMessage","clearMessage"]}
})
});
(window.AP||window._AP).define("_resize_listener",["_dollar"],function(b){function c(e,g,f){var d=g=="over";
e.addEventListener("OverflowEvent" in window?"overflowchanged":g+"flow",function(h){if(h.type==(g+"flow")||((h.orient==0&&h.horizontalOverflow==d)||(h.orient==1&&h.verticalOverflow==d)||(h.orient==2&&h.horizontalOverflow==d&&h.verticalOverflow==d))){h.flow=g;
return f.call(this,h)
}},false)
}function a(f,j){var d="onresize" in f;
if(!d&&!f._resizeSensor){b("head").append({tag:"style",type:"text/css",$text:".ac-resize-sensor,.ac-resize-sensor>div {position: absolute;top: 0;left: 0;width: 100%;height: 100%;overflow: hidden;z-index: -1;}"});
var e=f._resizeSensor=document.createElement("div");
e.className="ac-resize-sensor";
e.innerHTML='<div class="ac-resize-overflow"><div></div></div><div class="ac-resize-underflow"><div></div></div>';
var k=0,i=0,h=e.firstElementChild.firstChild,l=e.lastElementChild.firstChild,g=function(p){var q=false,o=f.offsetWidth;
if(k!=o){h.style.width=o-1+"px";
l.style.width=o+1+"px";
q=true;
k=o
}var n=f.offsetHeight;
if(i!=n){h.style.height=n-1+"px";
l.style.height=n+1+"px";
q=true;
i=n
}if(q&&p.currentTarget!=f){var p=document.createEvent("Event");
p.initEvent("resize",true,true);
f.dispatchEvent(p)
}};
if(getComputedStyle(f).position==="static"){f.style.position="relative";
f._resizeSensor._resetPosition=true
}c(e,"over",g);
c(e,"under",g);
c(e.firstElementChild,"over",g);
c(e.lastElementChild,"under",g);
f.appendChild(e);
g({})
}var m=f._flowEvents||(f._flowEvents=[]);
if(b.inArray(j,m)==-1){m.push(j)
}if(!d){f.addEventListener("resize",j,false)
}f.onresize=function(n){b.each(m,function(o,p){p.call(f,n)
})
}
}return{addListener:a}
});
AP.define("jira",["_dollar","_rpc"],function(b,f){var e,d;
var c={onSaveValidation:function(g){d=g
},onSave:function(g){e=g
},trigger:function(){var g=true;
if(b.isFunction(d)){g=d.call()
}return{valid:g,value:g?""+e.call():undefined}
}};
var a=f.extend(function(g){return{apis:{getWorkflowConfiguration:function(h){g.getWorkflowConfiguration(h)
}},internals:{setWorkflowConfigurationMessage:function(){return c.trigger()
}}}
});
return b.extend(a,{WorkflowConfiguration:c})
});
AP.define("confluence",["_dollar","_rpc"],function(a,b){return b.extend(function(c){return{apis:{saveMacro:function(d){c.saveMacro(d)
},getMacroData:function(d){c.getMacroData(d)
},closeMacroEditor:function(){c.closeMacroEditor()
}}}
})
});
AP.require(["_dollar","_rpc","_resize_listener","env","request","dialog","jira"],function(f,g,l,h,e,i,d){function b(){h.getLocation(function(n){f("head").append({tag:"base",href:n,target:"_parent"})
})
}function a(){var p=document.createElement("meta"),o=document.head||document.getElementsByTagName("head")[0],n=false;
f("meta").each(function(r,q){if(q.getAttribute("http-equiv")==="X-UA-Compatible"){n=true;
return false
}});
if(n===false){p.setAttribute("http-equiv","X-UA-Compatible");
p.setAttribute("content","IE=edge");
o.appendChild(p)
}}function k(){var n=i.isDialog?"10px 10px 0 10px":"0";
f("head").append({tag:"style",type:"text/css",$text:"body {margin: "+n+" !important;}"})
}g.extend({init:function(n){if(n.margin!==false){k(n)
}if(n.base===true){b(n)
}if(n.injectRenderModeMeta!==false||this.JSON===undefined){a()
}if(n.sizeToParent){h.sizeToParent()
}else{if(n.resize!==false){var o=n.resize;
o=o==="auto"?125:+o;
if(o>=0&&o<60){o=60
}if(!i.isDialog&&o>0){f.bind(window,"load",function(){var p;
setInterval(function(){var q=h.size();
if(!p||p.w!==q.w||p.h!==q.h){h.resize(q.w,q.h);
p=q
}},o)
})
}else{f.bind(window,"load",function(){h.resize();
var p=h.container();
if(p){l.addListener(p,function(){h.resize()
})
}else{f.log("Your page should have a root block element with an ID called #content or class called .ac-content if you want your page to dynamically resize after the initial load.")
}})
}}}}});
f.extend(AP,h,d,{Meta:{get:h.meta},request:e,Dialog:i});
var m={},c=f("script[src*='/atlassian-connect/all']");
if(c&&/\/atlassian-connect\/all(-debug)?\.js($|\?)/.test(c.attr("src"))){var j=c.attr("data-options");
if(j){f.each(j.split(";"),function(s,r){var n=f.trim;
r=n(r);
if(r){var q=r.split(":"),p=n(q[0]),o=n(q[1]);
if(p&&o!=null){m[p]=o==="true"||o==="false"?o==="true":o
}}})
}}g.init(m)
});
