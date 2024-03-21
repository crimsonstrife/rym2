!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?t(exports,require("leaflet")):"function"==typeof define&&define.amd?define(["exports","leaflet"],t):t((e||self).GeoSearch={},e.L)}(this,function(e,t){function r(e){if(e&&e.__esModule)return e;var t=Object.create(null);return e&&Object.keys(e).forEach(function(r){if("default"!==r){var n=Object.getOwnPropertyDescriptor(e,r);Object.defineProperty(t,r,n.get?n:{enumerable:!0,get:function(){return e[r]}})}}),t.default=e,t}var n=/*#__PURE__*/r(t);function o(){return o=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},o.apply(this,arguments)}function i(e,t){e.prototype=Object.create(t.prototype),e.prototype.constructor=e,s(e,t)}function s(e,t){return s=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e},s(e,t)}function a(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],function(){})),!0}catch(e){return!1}}function l(e,t,r){return l=a()?Reflect.construct.bind():function(e,t,r){var n=[null];n.push.apply(n,t);var o=new(Function.bind.apply(e,n));return r&&s(o,r.prototype),o},l.apply(null,arguments)}function c(e,t,r,n){void 0===t&&(t=""),void 0===n&&(n={});var o=document.createElement(e);return t&&(o.className=t),Object.keys(n).forEach(function(e){if("function"==typeof n[e]){var t=0===e.indexOf("on")?e.substr(2).toLowerCase():e;o.addEventListener(t,n[e])}else"html"===e?o.innerHTML=n[e]:"text"===e?o.innerText=n[e]:o.setAttribute(e,n[e])}),r&&r.appendChild(o),o}function u(e){e.preventDefault(),e.stopPropagation()}var h=function(){return[].slice.call(arguments).filter(Boolean).join(" ").trim()};function p(e,t){e&&e.classList&&(Array.isArray(t)?t:[t]).forEach(function(t){e.classList.contains(t)||e.classList.add(t)})}function d(e,t){e&&e.classList&&(Array.isArray(t)?t:[t]).forEach(function(t){e.classList.contains(t)&&e.classList.remove(t)})}var f,v=13,m=40,g=38,y=[v,27,m,g,37,39],b=/*#__PURE__*/function(){function e(e){var t=this,r=e.handleSubmit,n=e.searchLabel,o=e.classNames,i=void 0===o?{}:o;this.container=void 0,this.form=void 0,this.input=void 0,this.handleSubmit=void 0,this.hasError=!1,this.container=c("div",h("geosearch",i.container)),this.form=c("form",["",i.form].join(" "),this.container,{autocomplete:"none",onClick:u,onDblClick:u,touchStart:u,touchEnd:u}),this.input=c("input",["glass",i.input].join(" "),this.form,{type:"text",placeholder:n||"search",onInput:this.onInput,onKeyUp:function(e){return t.onKeyUp(e)},onKeyPress:function(e){return t.onKeyPress(e)},onFocus:this.onFocus,onBlur:this.onBlur,onClick:function(){t.input.focus(),t.input.dispatchEvent(new Event("focus"))}}),this.handleSubmit=r}var t=e.prototype;return t.onFocus=function(){p(this.form,"active")},t.onBlur=function(){d(this.form,"active")},t.onSubmit=function(e){try{var t=this;return u(e),d(r=t.container,"error"),p(r,"pending"),Promise.resolve(t.handleSubmit({query:t.input.value})).then(function(){d(t.container,"pending")})}catch(e){return Promise.reject(e)}var r},t.onInput=function(){this.hasError&&(d(this.container,"error"),this.hasError=!1)},t.onKeyUp=function(e){27===e.keyCode&&(d(this.container,["pending","active"]),this.input.value="",document.body.focus(),document.body.blur())},t.onKeyPress=function(e){e.keyCode===v&&this.onSubmit(e)},t.setQuery=function(e){this.input.value=e},e}(),E=/*#__PURE__*/function(){function e(e){var t=this,r=e.handleClick,n=e.classNames,o=void 0===n?{}:n,i=e.notFoundMessage;this.handleClick=void 0,this.selected=-1,this.results=[],this.container=void 0,this.resultItem=void 0,this.notFoundMessage=void 0,this.onClick=function(e){if("function"==typeof t.handleClick){var r=e.target;if(r&&t.container.contains(r)&&r.hasAttribute("data-key")){var n=Number(r.getAttribute("data-key"));t.handleClick({result:t.results[n]})}}},this.handleClick=r,this.notFoundMessage=i?c("div",h(o.notfound),void 0,{html:i}):void 0,this.container=c("div",h("results",o.resultlist)),this.container.addEventListener("click",this.onClick,!0),this.resultItem=c("div",h(o.item))}var t=e.prototype;return t.render=function(e,t){var r=this;void 0===e&&(e=[]),this.clear(),e.forEach(function(e,n){var o=r.resultItem.cloneNode(!0);o.setAttribute("data-key",""+n),o.innerHTML=t({result:e}),r.container.appendChild(o)}),e.length>0?(p(this.container.parentElement,"open"),p(this.container,"active")):this.notFoundMessage&&(this.container.appendChild(this.notFoundMessage),p(this.container.parentElement,"open")),this.results=e},t.select=function(e){return Array.from(this.container.children).forEach(function(t,r){return r===e?p(t,"active"):d(t,"active")}),this.selected=e,this.results[e]},t.count=function(){return this.results?this.results.length:0},t.clear=function(){for(this.selected=-1;this.container.lastChild;)this.container.removeChild(this.container.lastChild);d(this.container.parentElement,"open"),d(this.container,"active")},e}(),w={position:"topleft",style:"button",showMarker:!0,showPopup:!1,popupFormat:function(e){return""+e.result.label},resultFormat:function(e){return""+e.result.label},marker:{icon:n&&n.Icon?new n.Icon.Default:void 0,draggable:!1},maxMarkers:1,maxSuggestions:5,retainZoomLevel:!1,animateZoom:!0,searchLabel:"Enter address",clearSearchLabel:"Clear search",notFoundMessage:"",messageHideDelay:3e3,zoomLevel:18,classNames:{container:"leaflet-bar leaflet-control leaflet-control-geosearch",button:"leaflet-bar-part leaflet-bar-part-single",resetButton:"reset",msgbox:"leaflet-bar message",form:"",input:"",resultlist:"",item:"",notfound:"leaflet-bar-notfound"},autoComplete:!0,autoCompleteDelay:250,autoClose:!1,keepResult:!1,updateMap:!0},x="Leaflet must be loaded before instantiating the GeoSearch control",L={options:o({},w),classNames:o({},w.classNames),initialize:function(e){var t,r,i,s,a=this;if(!n)throw new Error(x);if(!e.provider)throw new Error("Provider is missing from options");this.options=o({},w,e),this.classNames=o({},this.classNames,e.classNames),this.markers=new n.FeatureGroup,this.classNames.container+=" leaflet-geosearch-"+this.options.style,this.searchElement=new b({searchLabel:this.options.searchLabel,classNames:{container:this.classNames.container,form:this.classNames.form,input:this.classNames.input},handleSubmit:function(e){return a.onSubmit(e)}}),this.button=c("a",this.classNames.button,this.searchElement.container,{title:this.options.searchLabel,href:"#",onClick:function(e){return a.onClick(e)}}),n.DomEvent.disableClickPropagation(this.button),this.resetButton=c("button",this.classNames.resetButton,this.searchElement.form,{text:"×","aria-label":this.options.clearSearchLabel,onClick:function(){""===a.searchElement.input.value?a.close():a.clearResults(null,!0)}}),n.DomEvent.disableClickPropagation(this.resetButton),this.options.autoComplete&&(this.resultList=new E({handleClick:function(e){var t=e.result;a.searchElement.input.value=t.label,a.onSubmit({query:t.label,data:t})},classNames:{resultlist:this.classNames.resultlist,item:this.classNames.item,notfound:this.classNames.notfound},notFoundMessage:this.options.notFoundMessage}),this.searchElement.form.appendChild(this.resultList.container),this.searchElement.input.addEventListener("keyup",(t=function(e){return a.autoSearch(e)},void 0===(r=this.options.autoCompleteDelay)&&(r=250),void 0===i&&(i=!1),function(){var e=[].slice.call(arguments);s&&clearTimeout(s),s=setTimeout(function(){s=null,i||t.apply(void 0,e)},r),i&&!s&&t.apply(void 0,e)}),!0),this.searchElement.input.addEventListener("keydown",function(e){return a.selectResult(e)},!0),this.searchElement.input.addEventListener("keydown",function(e){return a.clearResults(e,!0)},!0)),this.searchElement.form.addEventListener("click",function(e){e.preventDefault()},!1)},onAdd:function(e){var t=this.options,r=t.showMarker,o=t.style;if(this.map=e,r&&this.markers.addTo(e),"bar"===o){var i=e.getContainer().querySelector(".leaflet-control-container");this.container=c("div","leaflet-control-geosearch leaflet-geosearch-bar"),this.container.appendChild(this.searchElement.form),i.appendChild(this.container)}return n.DomEvent.disableClickPropagation(this.searchElement.form),this.searchElement.container},onRemove:function(){var e;return null==(e=this.container)||e.remove(),this},open:function(){var e=this.searchElement,t=e.input;p(e.container,"active"),t.focus()},close:function(){d(this.searchElement.container,"active"),this.clearResults()},onClick:function(e){e.preventDefault(),e.stopPropagation(),this.searchElement.container.classList.contains("active")?this.close():this.open()},selectResult:function(e){if(-1!==[v,m,g].indexOf(e.keyCode))if(e.preventDefault(),e.keyCode!==v){var t=this.resultList.count()-1;if(!(t<0)){var r=this.resultList.selected,n=e.keyCode===m?r+1:r-1,o=this.resultList.select(n<0?t:n>t?0:n);this.searchElement.input.value=o.label}}else{var i=this.resultList.select(this.resultList.selected);this.onSubmit({query:this.searchElement.input.value,data:i})}},clearResults:function(e,t){if(void 0===t&&(t=!1),!e||27===e.keyCode){var r=this.options,n=r.autoComplete;!t&&r.keepResult||(this.searchElement.input.value="",this.markers.clearLayers()),n&&this.resultList.clear()}},autoSearch:function(e){try{var t=this;if(y.indexOf(e.keyCode)>-1)return Promise.resolve();var r=e.target.value,n=t.options.provider,o=function(){if(r.length)return Promise.resolve(n.search({query:r})).then(function(e){e=e.slice(0,t.options.maxSuggestions),t.resultList.render(e,t.options.resultFormat)});t.resultList.clear()}();return Promise.resolve(o&&o.then?o.then(function(){}):void 0)}catch(e){return Promise.reject(e)}},onSubmit:function(e){try{var t=this;return t.resultList.clear(),Promise.resolve(t.options.provider.search(e)).then(function(r){r&&r.length>0&&t.showResult(r[0],e)})}catch(e){return Promise.reject(e)}},showResult:function(e,t){var r=this.options,n=r.autoClose,o=r.updateMap,i=this.markers.getLayers();i.length>=this.options.maxMarkers&&this.markers.removeLayer(i[0]);var s=this.addMarker(e,t);o&&this.centerMap(e),this.map.fireEvent("geosearch/showlocation",{location:e,marker:s}),n&&this.closeResults()},closeResults:function(){var e=this.searchElement.container;e.classList.contains("active")&&d(e,"active"),this.clearResults()},addMarker:function(e,t){var r=this,o=this.options,i=o.marker,s=o.showPopup,a=o.popupFormat,l=new n.Marker([e.y,e.x],i),c=e.label;return"function"==typeof a&&(c=a({query:t,result:e})),l.bindPopup(c),this.markers.addLayer(l),s&&l.openPopup(),i.draggable&&l.on("dragend",function(e){r.map.fireEvent("geosearch/marker/dragend",{location:l.getLatLng(),event:e})}),l},centerMap:function(e){var t=this.options,r=t.retainZoomLevel,o=t.animateZoom,i=e.bounds?new n.LatLngBounds(e.bounds):new n.LatLng(e.y,e.x).toBounds(10),s=i.isValid()?i:this.markers.getBounds();!r&&i.isValid()&&!e.bounds||r||!i.isValid()?this.map.setView(s.getCenter(),this.getZoom(),{animate:o}):this.map.fitBounds(s,{animate:o})},getZoom:function(){var e=this.options,t=e.zoomLevel;return e.retainZoomLevel?this.map.getZoom():t}};function P(){if(!n)throw new Error(x);var e=n.Control.extend(L);return l(e,[].slice.call(arguments))}!function(e){e[e.SEARCH=0]="SEARCH",e[e.REVERSE=1]="REVERSE"}(f||(f={}));var k=/*#__PURE__*/function(){function e(e){void 0===e&&(e={}),this.options=void 0,this.options=e}var t=e.prototype;return t.getParamString=function(e){void 0===e&&(e={});var t=o({},this.options.params,e);return Object.keys(t).map(function(e){return encodeURIComponent(e)+"="+encodeURIComponent(t[e])}).join("&")},t.getUrl=function(e,t){return e+"?"+this.getParamString(t)},t.search=function(e){try{var t=this,r=t.endpoint({query:e.query,type:f.SEARCH});return Promise.resolve(fetch(r)).then(function(e){return Promise.resolve(e.json()).then(function(e){return t.parse({data:e})})})}catch(e){return Promise.reject(e)}},e}(),S=/*#__PURE__*/function(e){function t(){return e.apply(this,arguments)||this}i(t,e);var r=t.prototype;return r.endpoint=function(){return"https://places-dsn.algolia.net/1/places/query"},r.findBestMatchLevelIndex=function(e){var t=e.find(function(e){return"full"===e.matchLevel})||e.find(function(e){return"partial"===e.matchLevel});return t?e.indexOf(t):0},r.getLabel=function(e){var t,r,n,o;return[null==(t=e.locale_names)?void 0:t.default[this.findBestMatchLevelIndex(e._highlightResult.locale_names.default)],null==(r=e.city)?void 0:r.default[this.findBestMatchLevelIndex(e._highlightResult.city.default)],e.administrative[this.findBestMatchLevelIndex(e._highlightResult.administrative)],null==(n=e.postcode)?void 0:n[this.findBestMatchLevelIndex(e._highlightResult.postcode)],null==(o=e.country)?void 0:o.default].filter(Boolean).join(", ")},r.parse=function(e){var t=this;return e.data.hits.map(function(e){return{x:e._geoloc.lng,y:e._geoloc.lat,label:t.getLabel(e),bounds:null,raw:e}})},r.search=function(e){var t=e.query;try{var r=this,n="string"==typeof t?{query:t}:t;return Promise.resolve(fetch(r.endpoint(),{method:"POST",body:JSON.stringify(o({},r.options.params,n))})).then(function(e){return Promise.resolve(e.json()).then(function(e){return r.parse({data:e})})})}catch(e){return Promise.reject(e)}},t}(k),U=/*#__PURE__*/function(e){function t(){for(var t,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(t=e.call.apply(e,[this].concat(n))||this).searchUrl="https://dev.virtualearth.net/REST/v1/Locations",t}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query,r="string"==typeof t?{q:t}:t;return r.jsonp=e.jsonp,this.getUrl(this.searchUrl,r)},r.parse=function(e){return 0===e.data.resourceSets.length?[]:e.data.resourceSets[0].resources.map(function(e){return{x:e.point.coordinates[1],y:e.point.coordinates[0],label:e.address.formattedAddress,bounds:[[e.bbox[0],e.bbox[1]],[e.bbox[2],e.bbox[3]]],raw:e}})},r.search=function(e){var t,r,n,o=e.query;try{var i=this,s="BING_JSONP_CB_"+Date.now();return Promise.resolve((t=i.endpoint({query:o,jsonp:s}),r=s,n=c("script",null,document.body),n.setAttribute("type","text/javascript"),new Promise(function(e){window[r]=function(t){n.remove(),delete window[r],e(t)},n.setAttribute("src",t)}))).then(function(e){return i.parse({data:e})})}catch(e){return Promise.reject(e)}},t}(k),C=/*#__PURE__*/function(e){function t(){for(var t,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(t=e.call.apply(e,[this].concat(n))||this).searchUrl="https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find",t}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query,r="string"==typeof t?{text:t}:t;return r.f="json",this.getUrl(this.searchUrl,r)},r.parse=function(e){return e.data.locations.map(function(e){return{x:e.feature.geometry.x,y:e.feature.geometry.y,label:e.name,bounds:[[e.extent.ymin,e.extent.xmin],[e.extent.ymax,e.extent.xmax]],raw:e}})},t}(k),R=/*#__PURE__*/function(e){function t(t){var r;return void 0===t&&(t={}),(r=e.call(this,t)||this).host=void 0,r.host=t.host||"http://localhost:4000",r}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query;return e.type===f.REVERSE?this.getUrl(this.host+"/v1/reverse","string"==typeof t?{}:t):this.getUrl(this.host+"/v1/autocomplete","string"==typeof t?{text:t}:t)},r.parse=function(e){return e.data.features.map(function(e){var t={x:e.geometry.coordinates[0],y:e.geometry.coordinates[1],label:e.properties.label,bounds:null,raw:e};return Array.isArray(e.bbox)&&4===e.bbox.length&&(t.bounds=[[e.bbox[1],e.bbox[0]],[e.bbox[3],e.bbox[2]]]),t})},t}(k),j=/*#__PURE__*/function(e){function t(t){return void 0===t&&(t={}),t.host="https://api.geocode.earth",e.call(this,t)||this}return i(t,e),t}(R);function I(e){return e&&e.__esModule&&Object.prototype.hasOwnProperty.call(e,"default")?e.default:e}"function"==typeof SuppressedError&&SuppressedError;var A=/*@__PURE__*/I(function e(t,r){if(t===r)return!0;if(t&&r&&"object"==typeof t&&"object"==typeof r){if(t.constructor!==r.constructor)return!1;var n,o,i;if(Array.isArray(t)){if((n=t.length)!=r.length)return!1;for(o=n;0!=o--;)if(!e(t[o],r[o]))return!1;return!0}if(t.constructor===RegExp)return t.source===r.source&&t.flags===r.flags;if(t.valueOf!==Object.prototype.valueOf)return t.valueOf()===r.valueOf();if(t.toString!==Object.prototype.toString)return t.toString()===r.toString();if((n=(i=Object.keys(t)).length)!==Object.keys(r).length)return!1;for(o=n;0!=o--;)if(!Object.prototype.hasOwnProperty.call(r,i[o]))return!1;for(o=n;0!=o--;){var s=i[o];if(!e(t[s],r[s]))return!1}return!0}return t!=t&&r!=r});const O="__googleMapsScriptId";var N;!function(e){e[e.INITIALIZED=0]="INITIALIZED",e[e.LOADING=1]="LOADING",e[e.SUCCESS=2]="SUCCESS",e[e.FAILURE=3]="FAILURE"}(N||(N={}));class F{constructor({apiKey:e,authReferrerPolicy:t,channel:r,client:n,id:o=O,language:i,libraries:s=[],mapIds:a,nonce:l,region:c,retries:u=3,url:h="https://maps.googleapis.com/maps/api/js",version:p}){if(this.callbacks=[],this.done=!1,this.loading=!1,this.errors=[],this.apiKey=e,this.authReferrerPolicy=t,this.channel=r,this.client=n,this.id=o||O,this.language=i,this.libraries=s,this.mapIds=a,this.nonce=l,this.region=c,this.retries=u,this.url=h,this.version=p,F.instance){if(!A(this.options,F.instance.options))throw new Error(`Loader must not be called again with different options. ${JSON.stringify(this.options)} !== ${JSON.stringify(F.instance.options)}`);return F.instance}F.instance=this}get options(){return{version:this.version,apiKey:this.apiKey,channel:this.channel,client:this.client,id:this.id,libraries:this.libraries,language:this.language,region:this.region,mapIds:this.mapIds,nonce:this.nonce,url:this.url,authReferrerPolicy:this.authReferrerPolicy}}get status(){return this.errors.length?N.FAILURE:this.done?N.SUCCESS:this.loading?N.LOADING:N.INITIALIZED}get failed(){return this.done&&!this.loading&&this.errors.length>=this.retries+1}createUrl(){let e=this.url;return e+="?callback=__googleMapsCallback&loading=async",this.apiKey&&(e+=`&key=${this.apiKey}`),this.channel&&(e+=`&channel=${this.channel}`),this.client&&(e+=`&client=${this.client}`),this.libraries.length>0&&(e+=`&libraries=${this.libraries.join(",")}`),this.language&&(e+=`&language=${this.language}`),this.region&&(e+=`&region=${this.region}`),this.version&&(e+=`&v=${this.version}`),this.mapIds&&(e+=`&map_ids=${this.mapIds.join(",")}`),this.authReferrerPolicy&&(e+=`&auth_referrer_policy=${this.authReferrerPolicy}`),e}deleteScript(){const e=document.getElementById(this.id);e&&e.remove()}load(){return this.loadPromise()}loadPromise(){return new Promise((e,t)=>{this.loadCallback(r=>{r?t(r.error):e(window.google)})})}importLibrary(e){return this.execute(),google.maps.importLibrary(e)}loadCallback(e){this.callbacks.push(e),this.execute()}setScript(){var e,t;if(document.getElementById(this.id))return void this.callback();const r={key:this.apiKey,channel:this.channel,client:this.client,libraries:this.libraries.length&&this.libraries,v:this.version,mapIds:this.mapIds,language:this.language,region:this.region,authReferrerPolicy:this.authReferrerPolicy};Object.keys(r).forEach(e=>!r[e]&&delete r[e]),(null===(t=null===(e=null===window||void 0===window?void 0:window.google)||void 0===e?void 0:e.maps)||void 0===t?void 0:t.importLibrary)||(e=>{let t,r,n,o="The Google Maps JavaScript API",i="google",s="importLibrary",a="__ib__",l=document,c=window;c=c[i]||(c[i]={});const u=c.maps||(c.maps={}),h=new Set,p=new URLSearchParams,d=()=>t||(t=new Promise((s,c)=>{return d=this,v=function*(){var d;for(n in yield r=l.createElement("script"),r.id=this.id,p.set("libraries",[...h]+""),e)p.set(n.replace(/[A-Z]/g,e=>"_"+e[0].toLowerCase()),e[n]);p.set("callback",i+".maps."+a),r.src=this.url+"?"+p,u[a]=s,r.onerror=()=>t=c(Error(o+" could not load.")),r.nonce=this.nonce||(null===(d=l.querySelector("script[nonce]"))||void 0===d?void 0:d.nonce)||"",l.head.append(r)},new((f=void 0)||(f=Promise))(function(e,t){function r(e){try{o(v.next(e))}catch(e){t(e)}}function n(e){try{o(v.throw(e))}catch(e){t(e)}}function o(t){var o;t.done?e(t.value):(o=t.value,o instanceof f?o:new f(function(e){e(o)})).then(r,n)}o((v=v.apply(d,[])).next())});var d,f,v}));u[s]?console.warn(o+" only loads once. Ignoring:",e):u[s]=(e,...t)=>h.add(e)&&d().then(()=>u[s](e,...t))})(r);const n=this.libraries.map(e=>this.importLibrary(e));n.length||n.push(this.importLibrary("core")),Promise.all(n).then(()=>this.callback(),e=>{const t=new ErrorEvent("error",{error:e});this.loadErrorCallback(t)})}reset(){this.deleteScript(),this.done=!1,this.loading=!1,this.errors=[],this.onerrorEvent=null}resetIfRetryingFailed(){this.failed&&this.reset()}loadErrorCallback(e){if(this.errors.push(e),this.errors.length<=this.retries){const e=this.errors.length*Math.pow(2,this.errors.length);console.error(`Failed to load Google Maps script, retrying in ${e} ms.`),setTimeout(()=>{this.deleteScript(),this.setScript()},e)}else this.onerrorEvent=e,this.callback()}callback(){this.done=!0,this.loading=!1,this.callbacks.forEach(e=>{e(this.onerrorEvent)}),this.callbacks=[]}execute(){if(this.resetIfRetryingFailed(),this.done)this.callback();else{if(window.google&&window.google.maps&&window.google.maps.version)return console.warn("Google Maps already loaded outside @googlemaps/js-api-loader.This may result in undesirable behavior as options and script parameters may not match."),void this.callback();this.loading||(this.loading=!0,this.setScript())}}}var M=/*#__PURE__*/function(e){function t(t){var r;return(r=e.call(this,t)||this).loader=null,r.geocoder=null,"undefined"!=typeof window&&(r.loader=new F(t).load().then(function(e){var t=new e.maps.Geocoder;return r.geocoder=t,t})),r}i(t,e);var r=t.prototype;return r.endpoint=function(e){throw new Error("Method not implemented.")},r.parse=function(e){return e.data.results.map(function(e){var t=e.geometry.location.toJSON(),r=t.lat,n=t.lng,o=e.geometry.viewport.toJSON();return{x:n,y:r,label:e.formatted_address,bounds:[[o.south,o.west],[o.north,o.east]],raw:e}})},r.search=function(e){try{var t=function(t){if(!t)throw new Error("GoogleMaps GeoCoder is not loaded. Are you trying to run this server side?");return Promise.resolve(t.geocode({address:e.query},function(e){return{results:e}}).catch(function(e){return"ZERO_RESULTS"!==e.code&&console.error(e.code+": "+e.message),{results:[]}})).then(function(e){return r.parse({data:e})})},r=this,n=r.geocoder;return Promise.resolve(n?t(n):Promise.resolve(r.loader).then(t))}catch(e){return Promise.reject(e)}},t}(k),_=/*#__PURE__*/function(e){function t(){for(var t,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(t=e.call.apply(e,[this].concat(n))||this).searchUrl="https://maps.googleapis.com/maps/api/geocode/json",t}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query;return this.getUrl(this.searchUrl,"string"==typeof t?{address:t}:t)},r.parse=function(e){return e.data.results.map(function(e){return{x:e.geometry.location.lng,y:e.geometry.location.lat,label:e.formatted_address,bounds:[[e.geometry.viewport.southwest.lat,e.geometry.viewport.southwest.lng],[e.geometry.viewport.northeast.lat,e.geometry.viewport.northeast.lng]],raw:e}})},t}(k),q=/*#__PURE__*/function(e){function t(){for(var t,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(t=e.call.apply(e,[this].concat(n))||this).searchUrl="https://geocode.search.hereapi.com/v1/autosuggest",t}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query;return this.getUrl(this.searchUrl,"string"==typeof t?{q:t}:t)},r.parse=function(e){return e.data.items.filter(function(e){return void 0!==e.position}).map(function(e){return{x:e.position.lng,y:e.position.lat,label:e.address.label,bounds:null,raw:e}})},t}(k),B=/*#__PURE__*/function(e){function t(t){var r;void 0===t&&(t={}),(r=e.call(this,t)||this).searchUrl=void 0,r.reverseUrl=void 0;var n="https://nominatim.openstreetmap.org";return r.searchUrl=t.searchUrl||n+"/search",r.reverseUrl=t.reverseUrl||n+"/reverse",r}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query,r=e.type,n="string"==typeof t?{q:t}:t;return n.format="json",this.getUrl(r===f.REVERSE?this.reverseUrl:this.searchUrl,n)},r.parse=function(e){return(Array.isArray(e.data)?e.data:[e.data]).map(function(e){return{x:Number(e.lon),y:Number(e.lat),label:e.display_name,bounds:[[parseFloat(e.boundingbox[0]),parseFloat(e.boundingbox[2])],[parseFloat(e.boundingbox[1]),parseFloat(e.boundingbox[3])]],raw:e}})},t}(k),D=/*#__PURE__*/function(e){function t(t){return e.call(this,o({},t,{searchUrl:"https://locationiq.org/v1/search.php",reverseUrl:"https://locationiq.org/v1/reverse.php"}))||this}return i(t,e),t.prototype.parse=function(t){return t.data.error?[]:e.prototype.parse.call(this,t)},t}(B),G=/*#__PURE__*/function(e){function t(){for(var t,r=arguments.length,n=new Array(r),o=0;o<r;o++)n[o]=arguments[o];return(t=e.call.apply(e,[this].concat(n))||this).searchUrl="https://api.opencagedata.com/geocode/v1/json",t}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query,r="string"==typeof t?{q:t}:t;return r.format="json",this.getUrl(this.searchUrl,r)},r.parse=function(e){return e.data.results.map(function(e){return{x:e.geometry.lng,y:e.geometry.lat,label:e.formatted,bounds:[[e.bounds.southwest.lat,e.bounds.southwest.lng],[e.bounds.northeast.lat,e.bounds.northeast.lng]],raw:e}})},r.search=function(t){try{return Promise.resolve(t.query.length<2?[]:e.prototype.search.call(this,t))}catch(e){return Promise.reject(e)}},t}(k),T=/*#__PURE__*/function(e){function t(t){var r;void 0===t&&(t={}),(r=e.call(this,t)||this).searchUrl=void 0,r.reverseUrl=void 0;var n="https://civildefense.fit.vutbr.cz";return r.searchUrl=t.searchUrl||n+"/search",r.reverseUrl=t.reverseUrl||n+"/reverse",r}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query,r=e.type,n="string"==typeof t?{q:t}:t;return n.format="json",this.getUrl(r===f.REVERSE?this.reverseUrl:this.searchUrl,n)},r.parse=function(e){return(Array.isArray(e.data)?e.data:[e.data]).map(function(e){return{x:Number(e.lon),y:Number(e.lat),label:e.display_name,bounds:[[parseFloat(e.boundingbox[0]),parseFloat(e.boundingbox[2])],[parseFloat(e.boundingbox[1]),parseFloat(e.boundingbox[3])]],raw:e}})},t}(k),K=/*#__PURE__*/function(e){function t(t){var r;return void 0===t&&(t={}),(r=e.call(this,t)||this).searchUrl=void 0,r.searchUrl=t.searchUrl||"https://a.tiles.mapbox.com/v4/geocode/mapbox.places/",r}i(t,e);var r=t.prototype;return r.endpoint=function(e){return this.getUrl(""+this.searchUrl+e.query+".json")},r.parse=function(e){return(Array.isArray(e.data.features)?e.data.features:[]).map(function(e){var t=null;return e.bbox&&(t=[[parseFloat(e.bbox[1]),parseFloat(e.bbox[0])],[parseFloat(e.bbox[3]),parseFloat(e.bbox[2])]]),{x:Number(e.center[0]),y:Number(e.center[1]),label:e.place_name?e.place_name:e.text,bounds:t,raw:e}})},t}(k),Z=/*#__PURE__*/function(e){function t(t){var r;void 0===t&&(t={}),(r=e.call(this,t)||this).searchUrl=void 0,r.reverseUrl=void 0;var n="https://api-adresse.data.gouv.fr";return r.searchUrl=t.searchUrl||n+"/search",r.reverseUrl=t.reverseUrl||n+"/reverse",r}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query;return this.getUrl(e.type===f.REVERSE?this.reverseUrl:this.searchUrl,"string"==typeof t?{q:t}:t)},r.parse=function(e){return e.data.features.map(function(e){return{x:e.geometry.coordinates[0],y:e.geometry.coordinates[1],label:e.properties.label,bounds:null,raw:e}})},t}(k),$=/*#__PURE__*/function(e){function t(t){var r;void 0===t&&(t={}),(r=e.call(this,t)||this).searchUrl=void 0,r.reverseUrl=void 0;var n="https://api.geoapify.com/v1/geocode";return r.searchUrl=t.searchUrl||n+"/search",r.reverseUrl=t.reverseUrl||n+"/reverse",r}i(t,e);var r=t.prototype;return r.endpoint=function(e){var t=e.query,r=e.type,n="string"==typeof t?{text:t}:t;return n.format="json",this.getUrl(r===f.REVERSE?this.reverseUrl:this.searchUrl,n)},r.parse=function(e){return(Array.isArray(e.data.results)?e.data.results:[e.data.results]).map(function(e){return{x:Number(e.lon),y:Number(e.lat),label:e.formatted,bounds:[[parseFloat(e.bbox.lat1),parseFloat(e.bbox.lon1)],[parseFloat(e.bbox.lat2),parseFloat(e.bbox.lon2)]],raw:e}})},t}(k);e.AlgoliaProvider=S,e.BingProvider=U,e.CivilDefenseMapProvider=T,e.EsriProvider=C,e.GeoApiFrProvider=Z,e.GeoSearchControl=P,e.GeoapifyProvider=$,e.GeocodeEarthProvider=j,e.GoogleProvider=M,e.HereProvider=q,e.JsonProvider=k,e.LegacyGoogleProvider=_,e.LocationIQProvider=D,e.MapBoxProvider=K,e.OpenCageProvider=G,e.OpenStreetMapProvider=B,e.PeliasProvider=R,e.SearchControl=P,e.SearchElement=b});
//# sourceMappingURL=geosearch.umd.js.map
