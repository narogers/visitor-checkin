/* Modernizr 2.5.2 (Custom Build) | MIT & BSD
 * Build: http://www.modernizr.com/download/#-borderradius-csscolumns-canvas-touch-mq-cssclasses-addtest-teststyles-testprop-testallprops-prefixes-domprefixes-fullscreen_api
 */
;window.Modernizr=function(a,b,c){function A(a){j.cssText=a}function B(a,b){return A(m.join(a+";")+(b||""))}function C(a,b){return typeof a===b}function D(a,b){return!!~(""+a).indexOf(b)}function E(a,b){for(var d in a)if(j[a[d]]!==c)return b=="pfx"?a[d]:!0;return!1}function F(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:C(f,"function")?f.bind(d||b):f}return!1}function G(a,b,c){var d=a.charAt(0).toUpperCase()+a.substr(1),e=(a+" "+o.join(d+" ")+d).split(" ");return C(b,"string")||C(b,"undefined")?E(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),F(e,b,c))}var d="2.5.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k=b.createElement("div"),l=b.body,m=l?l:b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),k.appendChild(j);return f=["&#173;","<style>",a,"</style>"].join(""),k.id=h,m.innerHTML+=f,m.appendChild(k),l||g.appendChild(m),i=c(k,a),l?k.parentNode.removeChild(k):m.parentNode.removeChild(m),!!i},x=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return w("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},y={}.hasOwnProperty,z;!C(y,"undefined")&&!C(y.call,"undefined")?z=function(a,b){return y.call(a,b)}:z=function(a,b){return b in a&&C(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e});var H=function(c,d){var f=c.join(""),g=d.length;w(f,function(c,d){var f=b.styleSheets[b.styleSheets.length-1],h=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"",i=c.childNodes,j={};while(g--)j[i[g].id]=i[g];e.touch="ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch||(j.touch&&j.touch.offsetTop)===9},g,d)}([,["@media (",m.join("touch-enabled),("),h,")","{#touch{top:9px;position:absolute}}"].join("")],[,"touch"]);q.canvas=function(){var a=b.createElement("canvas");return!!a.getContext&&!!a.getContext("2d")},q.touch=function(){return e.touch},q.borderradius=function(){return G("borderRadius")},q.csscolumns=function(){return G("columnCount")};for(var I in q)z(q,I)&&(v=I.toLowerCase(),e[v]=q[I](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)z(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,g.className+=" "+(b?"":"no-")+a,e[a]=b}return e},A(""),i=k=null,e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.mq=x,e.testProp=function(a){return E([a])},e.testAllProps=G,e.testStyles=w,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),Modernizr.addTest("fullscreen",function(){for(var a=0;a<Modernizr._domPrefixes.length;a++)if(document[Modernizr._domPrefixes[a].toLowerCase()+"CancelFullScreen"])return!0;return!!document.cancelFullScreen||!1});
/*
 
jSignature v2 "2013-12-09T05:51" "commit ID ebe94c351d7267e21b4fc741c79a8191391cb579"
Copyright (c) 2012 Willow Systems Corp http://willow-systems.com
Copyright (c) 2010 Brinley Ang http://www.unbolt.net
MIT License <http://www.opensource.org/licenses/mit-license.php> 


base64 encoder
MIT, GPL
http://phpjs.org/functions/base64_encode
+   original by: Tyler Akins (http://rumkin.com)
+   improved by: Bayron Guevara
+   improved by: Thunder.m
+   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
+   bugfixed by: Pellentesque Malesuada
+   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
+   improved by: Rafal Kukawski (http://kukawski.pl)


jSignature v2 jSignature's Undo Button and undo functionality plugin


jSignature v2 jSignature's custom "base30" format export and import plugins.


jSignature v2 SVG export plugin.


Simplify.js BSD 
(c) 2012, Vladimir Agafonkin
mourner.github.com/simplify-js

*/
(function(){function r(c){var a,b=c.css("color"),d;c=c[0];for(var l=!1;c&&!d&&!l;){try{a=$(c).css("background-color")}catch(E){a="transparent"}"transparent"!==a&&"rgba(0, 0, 0, 0)"!==a&&(d=a);l=c.body;c=c.parentNode}c=/rgb[a]*\((\d+),\s*(\d+),\s*(\d+)/;var l=/#([AaBbCcDdEeFf\d]{2})([AaBbCcDdEeFf\d]{2})([AaBbCcDdEeFf\d]{2})/,h;a=void 0;(a=b.match(c))?h={r:parseInt(a[1],10),g:parseInt(a[2],10),b:parseInt(a[3],10)}:(a=b.match(l))&&(h={r:parseInt(a[1],16),g:parseInt(a[2],16),b:parseInt(a[3],16)});var e;
d?(a=void 0,(a=d.match(c))?e={r:parseInt(a[1],10),g:parseInt(a[2],10),b:parseInt(a[3],10)}:(a=d.match(l))&&(e={r:parseInt(a[1],16),g:parseInt(a[2],16),b:parseInt(a[3],16)})):e=h?127<Math.max.apply(null,[h.r,h.g,h.b])?{r:0,g:0,b:0}:{r:255,g:255,b:255}:{r:255,g:255,b:255};a=function(a){return"rgb("+[a.r,a.g,a.b].join(", ")+")"};h&&e?(c=Math.max.apply(null,[h.r,h.g,h.b]),h=Math.max.apply(null,[e.r,e.g,e.b]),h=Math.round(h+-0.75*(h-c)),h={r:h,g:h,b:h}):h?(h=Math.max.apply(null,[h.r,h.g,h.b]),c=1,127<
h&&(c=-1),h=Math.round(h+96*c),h={r:h,g:h,b:h}):h={r:191,g:191,b:191};return{color:b,"background-color":e?a(e):d,"decor-color":a(h)}}function k(c,a){this.x=c;this.y=a;this.reverse=function(){return new this.constructor(-1*this.x,-1*this.y)};this._length=null;this.getLength=function(){this._length||(this._length=Math.sqrt(Math.pow(this.x,2)+Math.pow(this.y,2)));return this._length};var b=function(a){return Math.round(a/Math.abs(a))};this.resizeTo=function(a){if(0===this.x&&0===this.y)this._length=
0;else if(0===this.x)this._length=a,this.y=a*b(this.y);else if(0===this.y)this._length=a,this.x=a*b(this.x);else{var c=Math.abs(this.y/this.x),e=Math.sqrt(Math.pow(a,2)/(1+Math.pow(c,2))),c=c*e;this._length=a;this.x=e*b(this.x);this.y=c*b(this.y)}return this};this.angleTo=function(a){var b=this.getLength()*a.getLength();return 0===b?0:Math.acos(Math.min(Math.max((this.x*a.x+this.y*a.y)/b,-1),1))/Math.PI}}function g(c,a){this.x=c;this.y=a;this.getVectorToCoordinates=function(a,c){return new k(a-this.x,
c-this.y)};this.getVectorFromCoordinates=function(a,c){return this.getVectorToCoordinates(a,c).reverse()};this.getVectorToPoint=function(a){return new k(a.x-this.x,a.y-this.y)};this.getVectorFromPoint=function(a){return this.getVectorToPoint(a).reverse()}}function n(c,a,b,d,l){this.data=c;this.context=a;if(c.length)for(var e=c.length,h,m,A=0;A<e;A++){h=c[A];m=h.x.length;b.call(a,h);for(var f=1;f<m;f++)d.call(a,h,f);l.call(a,h)}this.changed=function(){};this.startStrokeFn=b;this.addToStrokeFn=d;this.endStrokeFn=
l;this.inStroke=!1;this._stroke=this._lastPoint=null;this.startStroke=function(a){if(a&&"number"==typeof a.x&&"number"==typeof a.y){this._stroke={x:[a.x],y:[a.y]};this.data.push(this._stroke);this._lastPoint=a;this.inStroke=!0;var b=this._stroke,c=this.startStrokeFn,d=this.context;setTimeout(function(){c.call(d,b)},3);return a}return null};this.addToStroke=function(a){if(this.inStroke&&"number"===typeof a.x&&"number"===typeof a.y&&4<Math.abs(a.x-this._lastPoint.x)+Math.abs(a.y-this._lastPoint.y)){var b=
this._stroke.x.length;this._stroke.x.push(a.x);this._stroke.y.push(a.y);this._lastPoint=a;var c=this._stroke,d=this.addToStrokeFn,l=this.context;setTimeout(function(){d.call(l,c,b)},3);return a}return null};this.endStroke=function(){var a=this.inStroke;this.inStroke=!1;this._lastPoint=null;if(a){var b=this._stroke,c=this.endStrokeFn,d=this.context,l=this.changed;setTimeout(function(){c.call(d,b);l.call(d)},3);return!0}return null}}function s(c,a,b,d){if("ratio"===a||"%"===a.split("")[a.length-1])this.eventTokens[b+
".parentresized"]=d.subscribe(b+".parentresized",function(a,e,h,m){return function(){var m=e.width();if(m!==h){for(var f in a)a.hasOwnProperty(f)&&(d.unsubscribe(a[f]),delete a[f]);var p=c.settings;c.$parent.children().remove();for(f in c)c.hasOwnProperty(f)&&delete c[f];f=p.data;var m=1*m/h,x=[],D,q,g,k,s,n;q=0;for(g=f.length;q<g;q++){n=f[q];D={x:[],y:[]};k=0;for(s=n.x.length;k<s;k++)D.x.push(n.x[k]*m),D.y.push(n.y[k]*m);x.push(D)}p.data=x;e[b](p)}}}(this.eventTokens,this.$parent,this.$parent.width(),
1*this.canvas.width/this.canvas.height))}function v(c,a,b){var d=this.$parent=$(c);c=this.eventTokens={};this.events=new t(this);var l=$.fn[f]("globalEvents"),e={width:"ratio",height:"ratio",sizeRatio:4,color:"#000","background-color":"#fff","decor-color":"#eee",lineWidth:0,minFatFingerCompensation:-10,showUndoButton:!1,readOnly:!1,data:[]};$.extend(e,r(d));a&&$.extend(e,a);this.settings=e;for(var h in b)b.hasOwnProperty(h)&&b[h].call(this,h);this.events.publish(f+".initializing");this.$controlbarUpper=
$('<div style="padding:0 !important; margin:0 !important;width: 100% !important; height: 0 !important; -ms-touch-action: none;margin-top:-1em !important; margin-bottom:1em !important;"></div>').appendTo(d);this.isCanvasEmulator=!1;a=this.canvas=this.initializeCanvas(e);b=$(a);this.$controlbarLower=$('<div style="padding:0 !important; margin:0 !important;width: 100% !important; height: 0 !important; -ms-touch-action: none;margin-top:-1.5em !important; margin-bottom:1.5em !important; position: relative;"></div>').appendTo(d);
this.canvasContext=a.getContext("2d");b.data(f+".this",this);e.lineWidth=function(a,b){return a?a:Math.max(Math.round(b/400),2)}(e.lineWidth,a.width);this.lineCurveThreshold=3*e.lineWidth;e.cssclass&&""!=$.trim(e.cssclass)&&b.addClass(e.cssclass);this.fatFingerCompensation=0;d=function(a){var b,c,d=function(d){d=d.changedTouches&&0<d.changedTouches.length?d.changedTouches[0]:d;return new g(Math.round(d.pageX+b),Math.round(d.pageY+c)+a.fatFingerCompensation)},e=new u(750,function(){a.dataEngine.endStroke()});
this.drawEndHandler=function(b){if(!a.settings.readOnly){try{b.preventDefault()}catch(c){}e.clear();a.dataEngine.endStroke()}};this.drawStartHandler=function(l){if(!a.settings.readOnly){l.preventDefault();var h=$(a.canvas).offset();b=-1*h.left;c=-1*h.top;a.dataEngine.startStroke(d(l));e.kick()}};this.drawMoveHandler=function(b){a.settings.readOnly||(b.preventDefault(),a.dataEngine.inStroke&&(a.dataEngine.addToStroke(d(b)),e.kick()))};return this}.call({},this);(function(a,b,c){var d=this.canvas,l=
$(d);this.isCanvasEmulator?(l.bind("mousemove."+f,c),l.bind("mouseup."+f,a),l.bind("mousedown."+f,b)):(d.ontouchstart=function(l){d.onmousedown=d.onmouseup=d.onmousemove=void 0;this.fatFingerCompensation=e.minFatFingerCompensation&&-3*e.lineWidth>e.minFatFingerCompensation?-3*e.lineWidth:e.minFatFingerCompensation;b(l);d.ontouchend=a;d.ontouchstart=b;d.ontouchmove=c},d.onmousedown=function(e){d.ontouchstart=d.ontouchend=d.ontouchmove=void 0;b(e);d.onmousedown=b;d.onmouseup=a;d.onmousemove=c},window.navigator.msPointerEnabled&&
(d.onmspointerdown=b,d.onmspointerup=a,d.onmspointermove=c))}).call(this,d.drawEndHandler,d.drawStartHandler,d.drawMoveHandler);c[f+".windowmouseup"]=l.subscribe(f+".windowmouseup",d.drawEndHandler);this.events.publish(f+".attachingEventHandlers");s.call(this,this,e.width.toString(10),f,l);this.resetCanvas(e.data);this.events.publish(f+".initialized");return this}function w(c){if(c.getContext)return!1;var a=c.ownerDocument.parentWindow,b=a.FlashCanvas?c.ownerDocument.parentWindow.FlashCanvas:"undefined"===
typeof FlashCanvas?void 0:FlashCanvas;if(b){c=b.initElement(c);b=1;a&&a.screen&&a.screen.deviceXDPI&&a.screen.logicalXDPI&&(b=1*a.screen.deviceXDPI/a.screen.logicalXDPI);if(1!==b)try{$(c).children("object").get(0).resize(Math.ceil(c.width*b),Math.ceil(c.height*b)),c.getContext("2d").scale(b,b)}catch(d){}return!0}throw Error("Canvas element does not support 2d context. jSignature cannot proceed.");}var f="jSignature",u=function(c,a){var b;this.kick=function(){clearTimeout(b);b=setTimeout(a,c)};this.clear=
function(){clearTimeout(b)};return this},t=function(c){this.topics={};this.context=c?c:this;this.publish=function(a,b,c,e){if(this.topics[a]){var f=this.topics[a],h=Array.prototype.slice.call(arguments,1),m=[],q,g,p,x;g=0;for(p=f.length;g<p;g++)x=f[g],q=x[0],x[1]&&(x[0]=function(){},m.push(g)),q.apply(this.context,h);g=0;for(p=m.length;g<p;g++)f.splice(m[g],1)}};this.subscribe=function(a,b,c){this.topics[a]?this.topics[a].push([b,c]):this.topics[a]=[[b,c]];return{topic:a,callback:b}};this.unsubscribe=
function(a){if(this.topics[a.topic])for(var b=this.topics[a.topic],c=0,e=b.length;c<e;c++)b[c]&&b[c][0]===a.callback&&b.splice(c,1)}},y=function(c,a,b,d,e){c.beginPath();c.moveTo(a,b);c.lineTo(d,e);c.closePath();c.stroke()},C=function(c){var a=this.canvasContext,b=c.x[0];c=c.y[0];var d=this.settings.lineWidth,e=a.fillStyle;a.fillStyle=a.strokeStyle;a.fillRect(b+d/-2,c+d/-2,d,d);a.fillStyle=e},q=function(c,a){var b=new g(c.x[a-1],c.y[a-1]),d=new g(c.x[a],c.y[a]),e=b.getVectorToPoint(d);if(1<a){var f=
new g(c.x[a-2],c.y[a-2]),h=f.getVectorToPoint(b),m;if(h.getLength()>this.lineCurveThreshold){m=2<a?(new g(c.x[a-3],c.y[a-3])).getVectorToPoint(f):new k(0,0);var q=0.35*h.getLength(),n=h.angleTo(m.reverse()),p=e.angleTo(h.reverse());m=(new k(m.x+h.x,m.y+h.y)).resizeTo(Math.max(0.05,n)*q);var x=(new k(h.x+e.x,h.y+e.y)).reverse().resizeTo(Math.max(0.05,p)*q),h=this.canvasContext,q=f.x,p=f.y,n=b.x,D=b.y,s=f.x+m.x,f=f.y+m.y;m=b.x+x.x;x=b.y+x.y;h.beginPath();h.moveTo(q,p);h.bezierCurveTo(s,f,m,x,n,D);h.closePath();
h.stroke()}}e.getLength()<=this.lineCurveThreshold&&y(this.canvasContext,b.x,b.y,d.x,d.y)},e=function(c){var a=c.x.length-1;if(0<a){var b=new g(c.x[a],c.y[a]),d=new g(c.x[a-1],c.y[a-1]),e=d.getVectorToPoint(b);if(e.getLength()>this.lineCurveThreshold)if(1<a){c=(new g(c.x[a-2],c.y[a-2])).getVectorToPoint(d);var f=(new k(c.x+e.x,c.y+e.y)).resizeTo(e.getLength()/2),e=this.canvasContext;c=d.x;var a=d.y,h=b.x,m=b.y,q=d.x+f.x,d=d.y+f.y,f=b.x,b=b.y;e.beginPath();e.moveTo(c,a);e.bezierCurveTo(q,d,f,b,h,m);
e.closePath();e.stroke()}else y(this.canvasContext,d.x,d.y,b.x,b.y)}};v.prototype.resetCanvas=function(c,a){var b=this.canvas,d=this.settings,l=this.canvasContext,g=this.isCanvasEmulator,h=b.width,m=b.height;a||l.clearRect(0,0,h+30,m+30);l.shadowColor=l.fillStyle=d["background-color"];g&&l.fillRect(0,0,h+30,m+30);l.lineWidth=Math.ceil(parseInt(d.lineWidth,10));l.lineCap=l.lineJoin="round";if(null!=d["decor-color"]){l.strokeStyle=d["decor-color"];l.shadowOffsetX=0;l.shadowOffsetY=0;var A=Math.round(m/
5);y(l,1.5*A,m-A,h-1.5*A,m-A)}l.strokeStyle=d.color;g||(l.shadowColor=l.strokeStyle,l.shadowOffsetX=0.5*l.lineWidth,l.shadowOffsetY=-0.6*l.lineWidth,l.shadowBlur=0);c||(c=[]);l=this.dataEngine=new n(c,this,C,q,e);d.data=c;$(b).data(f+".data",c).data(f+".settings",d);l.changed=function(a,b,c){return function(){b.publish(c+".change");a.trigger("change")}}(this.$parent,this.events,f);l.changed();return!0};v.prototype.initializeCanvas=function(c){var a=document.createElement("canvas"),b=$(a);c.width===
c.height&&"ratio"===c.height&&(c.width="100%");b.css("margin",0).css("padding",0).css("border","none").css("height","ratio"!==c.height&&c.height?c.height.toString(10):1).css("width","ratio"!==c.width&&c.width?c.width.toString(10):1).css("-ms-touch-action","none");b.appendTo(this.$parent);"ratio"===c.height?b.css("height",Math.round(b.width()/c.sizeRatio)):"ratio"===c.width&&b.css("width",Math.round(b.height()*c.sizeRatio));b.addClass(f);a.width=b.width();a.height=b.height();this.isCanvasEmulator=
w(a);a.onselectstart=function(a){a&&a.preventDefault&&a.preventDefault();a&&a.stopPropagation&&a.stopPropagation();return!1};return a};(function(c){function a(a,b,c){var d=new Image,e=this;d.onload=function(){e.getContext("2d").drawImage(d,0,0,d.width<e.width?d.width:e.width,d.height<e.height?d.height:e.height)};d.src="data:"+b+","+a}function b(a,b){this.find("canvas."+f).add(this.filter("canvas."+f)).data(f+".this").resetCanvas(a,b);return this}function d(a,b){if(void 0===b&&"string"===typeof a&&
"data:"===a.substr(0,5)&&(b=a.slice(5).split(",")[0],a=a.slice(6+b.length),b===a))return;var c=this.find("canvas."+f).add(this.filter("canvas."+f));if(m.hasOwnProperty(b))0!==c.length&&m[b].call(c[0],a,b,function(a){return function(){return a.resetCanvas.apply(a,arguments)}}(c.data(f+".this")));else throw Error(f+" is unable to find import plugin with for format '"+String(b)+"'");return this}var e=new t;(function(a,b,c,d){var e,h=function(){a.publish(b+".parentresized")};c(d).bind("resize."+b,function(){e&&
clearTimeout(e);e=setTimeout(h,500)}).bind("mouseup."+b,function(c){a.publish(b+".windowmouseup")})})(e,f,$,c);var q={},h={"default":function(a){return this.toDataURL()},"native":function(a){return a},image:function(a){a=this.toDataURL();if("string"===typeof a&&4<a.length&&"data:"===a.slice(0,5)&&-1!==a.indexOf(",")){var b=a.indexOf(",");return[a.slice(5,b),a.substr(b+1)]}return[]}},m={"native":function(a,b,c){c(a)},image:a,"image/png;base64":a,"image/jpeg;base64":a,"image/jpg;base64":a},g={"export":h,
"import":m,instance:q},k={init:function(a){return this.each(function(){var b,c=!1;for(b=this.parentNode;b&&!c;)c=b.body,b=b.parentNode;c&&new v(this,a,q)})},getSettings:function(){return this.find("canvas."+f).add(this.filter("canvas."+f)).data(f+".this").settings},isModified:function(){return null!==this.find("canvas."+f).add(this.filter("canvas."+f)).data(f+".this").dataEngine._stroke},updateSetting:function(a,b,c){var d=this.find("canvas."+f).add(this.filter("canvas."+f)).data(f+".this");d.settings[a]=
b;d.resetCanvas(c?null:d.settings.data,!0);return d.settings[a]},clear:b,reset:b,addPlugin:function(a,b,c){g.hasOwnProperty(a)&&(g[a][b]=c);return this},listPlugins:function(a){var b=[];if(g.hasOwnProperty(a)){a=g[a];for(var c in a)a.hasOwnProperty(c)&&b.push(c)}return b},getData:function(a){var b=this.find("canvas."+f).add(this.filter("canvas."+f));void 0===a&&(a="default");if(0!==b.length&&h.hasOwnProperty(a))return h[a].call(b.get(0),b.data(f+".data"),b.data(f+".settings"))},importData:d,setData:d,
globalEvents:function(){return e},disable:function(){this.find("input").attr("disabled",1);this.find("canvas."+f).addClass("disabled").data(f+".this").settings.readOnly=!0},enable:function(){this.find("input").removeAttr("disabled");this.find("canvas."+f).removeClass("disabled").data(f+".this").settings.readOnly=!1},events:function(){return this.find("canvas."+f).add(this.filter("canvas."+f)).data(f+".this").events}};$.fn[f]=function(a){if(a&&"object"!==typeof a){if("string"===typeof a&&k[a])return k[a].apply(this,
Array.prototype.slice.call(arguments,1));$.error("Method "+String(a)+" does not exist on jQuery."+f)}else return k.init.apply(this,arguments)}})(window)})();
(function(){function r(k,g,n){k=k.call(this);(function(g,k,n){g.events.subscribe(n+".change",function(){g.dataEngine.data.length?k.show():k.hide()})})(this,k,g);(function(g,k,n){var f=n+".undo";k.bind("click",function(){g.events.publish(f)});g.events.subscribe(f,function(){var f=g.dataEngine.data;f.length&&(f.pop(),g.resetCanvas(f))})})(this,k,this.events.topics.hasOwnProperty(g+".undo")?n:g)}$.fn.jSignature("addPlugin","instance","UndoButton",function(k){this.events.subscribe("jSignature.attachingEventHandlers",
function(){if(this.settings[k]){var g=this.settings[k];"function"!==typeof g&&(g=function(){var g=$('<input type="button" value="Undo last stroke" style="position:absolute;display:none;margin:0 !important;top:auto" />').appendTo(this.$controlbarLower),k=g.width();g.css("left",Math.round((this.canvas.width-k)/2));k!==g.width()&&g.width(k);return g});r.call(this,g,"jSignature",k)}})})})();
(function(){for(var r={},k={},g="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWX".split(""),n=g.length/2,s=n-1;-1<s;s--)r[g[s]]=g[s+n],k[g[s+n]]=g[s];var v=function(f){f=f.split("");for(var e=f.length,c=1;c<e;c++)f[c]=r[f[c]];return f.join("")},w=function(f){for(var e=[],c=0,a=1,b=f.length,d,l,g=0;g<b;g++)d=Math.round(f[g]),l=d-c,c=d,0>l&&0<a?(a=-1,e.push("Z")):0<l&&0>a&&(a=1,e.push("Y")),d=Math.abs(l),d>=n?e.push(v(d.toString(n))):e.push(d.toString(n));return e.join("")},f=function(f){var e=
[];f=f.split("");for(var c=f.length,a,b=1,d=[],l=0,g=0;g<c;g++)a=f[g],a in r||"Z"===a||"Y"===a?(0!==d.length&&(d=parseInt(d.join(""),n)*b+l,e.push(d),l=d),"Z"===a?(b=-1,d=[]):"Y"===a?(b=1,d=[]):d=[a]):d.push(k[a]);e.push(parseInt(d.join(""),n)*b+l);return e},u=function(f){for(var e=[],c=f.length,a,b=0;b<c;b++)a=f[b],e.push(w(a.x)),e.push(w(a.y));return e.join("_")},t=function(g){var e=[];g=g.split("_");for(var c=g.length/2,a=0;a<c;a++)e.push({x:f(g[2*a]),y:f(g[2*a+1])});return e},y=function(f){return["image/jsignature;base30",
u(f)]},C=function(f,e,c){"string"===typeof f&&("image/jsignature;base30"===f.substring(0,23).toLowerCase()&&(f=f.substring(24)),c(t(f)))};if(null==this.jQuery)throw Error("We need jQuery for some of the functionality. jQuery is not detected. Failing to initialize...");(function(f){f=f.fn.jSignature;f("addPlugin","export","base30",y);f("addPlugin","export","image/jsignature;base30",y);f("addPlugin","import","base30",C);f("addPlugin","import","image/jsignature;base30",C)})(this.jQuery);this.jSignatureDebug&&
(this.jSignatureDebug.base30={remapTailChars:v,compressstrokeleg:w,uncompressstrokeleg:f,compressstrokes:u,uncompressstrokes:t,charmap:r})}).call("undefined"!==typeof window?window:this);
(function(){function r(e,c){this.x=e;this.y=c;this.reverse=function(){return new this.constructor(-1*this.x,-1*this.y)};this._length=null;this.getLength=function(){this._length||(this._length=Math.sqrt(Math.pow(this.x,2)+Math.pow(this.y,2)));return this._length};var a=function(a){return Math.round(a/Math.abs(a))};this.resizeTo=function(b){if(0===this.x&&0===this.y)this._length=0;else if(0===this.x)this._length=b,this.y=b*a(this.y);else if(0===this.y)this._length=b,this.x=b*a(this.x);else{var c=Math.abs(this.y/
this.x),e=Math.sqrt(Math.pow(b,2)/(1+Math.pow(c,2))),c=c*e;this._length=b;this.x=e*a(this.x);this.y=c*a(this.y)}return this};this.angleTo=function(a){var c=this.getLength()*a.getLength();return 0===c?0:Math.acos(Math.min(Math.max((this.x*a.x+this.y*a.y)/c,-1),1))/Math.PI}}function k(e,c){this.x=e;this.y=c;this.getVectorToCoordinates=function(a,b){return new r(a-this.x,b-this.y)};this.getVectorFromCoordinates=function(a,b){return this.getVectorToCoordinates(a,b).reverse()};this.getVectorToPoint=function(a){return new r(a.x-
this.x,a.y-this.y)};this.getVectorFromPoint=function(a){return this.getVectorToPoint(a).reverse()}}function g(e,c){var a=Math.pow(10,c);return Math.round(e*a)/a}function n(e,c,a){c+=1;var b=new k(e.x[c-1],e.y[c-1]),d=new k(e.x[c],e.y[c]),d=b.getVectorToPoint(d),f=new k(e.x[c-2],e.y[c-2]),b=f.getVectorToPoint(b);return b.getLength()>a?(a=2<c?(new k(e.x[c-3],e.y[c-3])).getVectorToPoint(f):new r(0,0),e=0.35*b.getLength(),f=b.angleTo(a.reverse()),c=d.angleTo(b.reverse()),a=(new r(a.x+b.x,a.y+b.y)).resizeTo(Math.max(0.05,
f)*e),d=(new r(b.x+d.x,b.y+d.y)).reverse().resizeTo(Math.max(0.05,c)*e),d=new r(b.x+d.x,b.y+d.y),["c",g(a.x,2),g(a.y,2),g(d.x,2),g(d.y,2),g(b.x,2),g(b.y,2)]):["l",g(b.x,2),g(b.y,2)]}function s(e,c){var a=e.x.length-1,b=new k(e.x[a],e.y[a]),d=new k(e.x[a-1],e.y[a-1]),b=d.getVectorToPoint(b);if(1<a&&b.getLength()>c){var a=(new k(e.x[a-2],e.y[a-2])).getVectorToPoint(d),d=b.angleTo(a.reverse()),f=0.35*b.getLength(),a=(new r(a.x+b.x,a.y+b.y)).resizeTo(Math.max(0.05,d)*f);return["c",g(a.x,2),g(a.y,2),g(b.x,
2),g(b.y,2),g(b.x,2),g(b.y,2)]}return["l",g(b.x,2),g(b.y,2)]}function v(e,c,a){c=["M",g(e.x[0]-c,2),g(e.y[0]-a,2)];a=1;for(var b=e.x.length-1;a<b;a++)c.push.apply(c,n(e,a,1));0<b?c.push.apply(c,s(e,a,1)):0===b&&c.push.apply(c,["l",1,1]);return c.join(" ")}function w(e){for(var c=[],a=[["fill",void 0,"none"],["stroke","color","#000000"],["stroke-width","lineWidth",2],["stroke-linecap",void 0,"round"],["stroke-linejoin",void 0,"round"]],b=a.length-1;0<=b;b--){var d=a[b][1],f=a[b][2];c.push(a[b][0]+
'="'+(d in e&&e[d]?e[d]:f)+'"')}return c.join(" ")}function f(e,c){var a=['<?xml version="1.0" encoding="UTF-8" standalone="no"?>','<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'],b,d=e.length,f,g=[],h=[],m=f=b=0,k=0,n=[];if(0!==d){for(b=0;b<d;b++){m=e[b];k=[];f={x:[],y:[]};for(var p=void 0,q=void 0,p=0,q=m.x.length;p<q;p++)k.push({x:m.x[p],y:m.y[p]});k=simplify(k,0.7,!0);p=0;for(q=k.length;p<q;p++)f.x.push(k[p].x),f.y.push(k[p].y);n.push(f);g=
g.concat(f.x);h=h.concat(f.y)}d=Math.min.apply(null,g)-1;b=Math.max.apply(null,g)+1;g=Math.min.apply(null,h)-1;h=Math.max.apply(null,h)+1;m=0>d?0:d;k=0>g?0:g;b-=d;f=h-g}a.push('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="'+b.toString()+'" height="'+f.toString()+'">');b=0;for(d=n.length;b<d;b++)f=n[b],a.push("<path "+w(c)+' d="'+v(f,m,k)+'"/>');a.push("</svg>");return a.join("")}function u(e,c){return[C,f(e,c)]}function t(e,c){return[q,y(f(e,c))]}(function(e,c){"use strict";(typeof exports!=
c+""?exports:e).simplify=function(a,b,d){b=b!==c?b*b:1;if(!d){var e=a.length,f,g=a[0],m=[g];for(d=1;d<e;d++){f=a[d];var k=f.x-g.x,n=f.y-g.y;k*k+n*n>b&&(m.push(f),g=f)}a=(g!==f&&m.push(f),m)}f=a;d=f.length;var e=new (typeof Uint8Array!=c+""?Uint8Array:Array)(d),g=0,m=d-1,p,q,r=[],s=[],y=[];for(e[g]=e[m]=1;m;){n=0;for(k=g+1;k<m;k++){p=f[k];var z=f[g],v=f[m],t=z.x,u=z.y,z=v.x-t,B=v.y-u,w=void 0;if(0!==z||0!==B)w=((p.x-t)*z+(p.y-u)*B)/(z*z+B*B),1<w?(t=v.x,u=v.y):0<w&&(t+=z*w,u+=B*w);p=(z=p.x-t,B=p.y-
u,z*z+B*B);p>n&&(q=k,n=p)}n>b&&(e[q]=1,r.push(g),s.push(q),r.push(q),s.push(m));g=r.pop();m=s.pop()}for(k=0;k<d;k++)e[k]&&y.push(f[k]);return a=y,a}})(window);if("function"!==typeof y)var y=function(e){var c="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".split(""),a,b,d,f,g=0,h=0,k="",k=[];do a=e.charCodeAt(g++),b=e.charCodeAt(g++),d=e.charCodeAt(g++),f=a<<16|b<<8|d,a=f>>18&63,b=f>>12&63,d=f>>6&63,f&=63,k[h++]=c[a]+c[b]+c[d]+c[f];while(g<e.length);k=k.join("");e=e.length%3;return(e?
k.slice(0,e-3):k)+"===".slice(e||3)};var C="image/svg+xml",q="image/svg+xml;base64";if("undefined"===typeof $)throw Error("We need jQuery for some of the functionality. jQuery is not detected. Failing to initialize...");(function(e){e=e.fn.jSignature;e("addPlugin","export","svg",u);e("addPlugin","export",C,u);e("addPlugin","export","svgbase64",t);e("addPlugin","export",q,t)})($)})();
//# sourceMappingURL=all.js.map
