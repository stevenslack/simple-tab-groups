"document"in self&&!("classList"in document.createElement("_"))&&!function(a){"use strict";if("Element"in a){var b="classList",c="prototype",d=a.Element[c],e=Object,f=String[c].trim||function(){return this.replace(/^\s+|\s+$/g,"")},g=Array[c].indexOf||function(a){for(var b=0,c=this.length;c>b;b++)if(b in this&&this[b]===a)return b;return-1},h=function(a,b){this.name=a,this.code=DOMException[a],this.message=b},i=function(a,b){if(""===b)throw new h("SYNTAX_ERR","An invalid or illegal string was specified");if(/\s/.test(b))throw new h("INVALID_CHARACTER_ERR","String contains an invalid character");return g.call(a,b)},j=function(a){for(var b=f.call(a.getAttribute("class")||""),c=b?b.split(/\s+/):[],d=0,e=c.length;e>d;d++)this.push(c[d]);this._updateClassName=function(){a.setAttribute("class",this.toString())}},k=j[c]=[],l=function(){return new j(this)};if(h[c]=Error[c],k.item=function(a){return this[a]||null},k.contains=function(a){return a+="",-1!==i(this,a)},k.add=function(){var a,b=arguments,c=0,d=b.length,e=!1;do a=b[c]+"",-1===i(this,a)&&(this.push(a),e=!0);while(++c<d);e&&this._updateClassName()},k.remove=function(){var a,b=arguments,c=0,d=b.length,e=!1;do{a=b[c]+"";var f=i(this,a);-1!==f&&(this.splice(f,1),e=!0)}while(++c<d);e&&this._updateClassName()},k.toggle=function(a,b){a+="";var c=this.contains(a),d=c?b!==!0&&"remove":b!==!1&&"add";return d&&this[d](a),!c},k.toString=function(){return this.join(" ")},e.defineProperty){var m={get:l,enumerable:!0,configurable:!0};try{e.defineProperty(d,b,m)}catch(n){-2146823252===n.number&&(m.enumerable=!1,e.defineProperty(d,b,m))}}else e[c].__defineGetter__&&d.__defineGetter__(b,l)}}(self),function(a,b){"function"==typeof define&&define.amd?define([],b(a)):"object"==typeof exports?module.exports=b(a):a.tabby=b(a)}("undefined"!=typeof global?global:this.window||this.global,function(a){"use strict";var b,c={},d=!!document.querySelector&&!!a.addEventListener,e={toggleActiveClass:"active",contentActiveClass:"active",initClass:"js-tabby",callbackBefore:function(){},callbackAfter:function(){}},f=function(a,b,c){if("[object Object]"===Object.prototype.toString.call(a))for(var d in a)Object.prototype.hasOwnProperty.call(a,d)&&b.call(c,a[d],d,a);else for(var e=0,f=a.length;f>e;e++)b.call(c,a[e],e,a)},g=function(a,b){var c={};return f(a,function(b,d){c[d]=a[d]}),f(b,function(a,d){c[d]=b[d]}),c},h=function(a,b){for(var c=b.charAt(0);a&&a!==document;a=a.parentNode)if("."===c){if(a.classList.contains(b.substr(1)))return a}else if("#"===c){if(a.id===b.substr(1))return a}else if("["===c&&a.hasAttribute(b.substr(1,b.length-2)))return a;return!1},i=function(a){for(var b=[],c=a.parentNode.firstChild;c;c=c.nextSibling)1==c.nodeType&&c!=a&&b.push(c);return b},j=function(a,b){if(!a.classList.contains(b)){var c=a.querySelector("iframe"),d=a.querySelector("video");if(c){var e=c.src;c.src=e}d&&d.pause()}},k=function(a,b,c){var d="li"===a.parentNode.tagName.toLowerCase()?!0:!1,e=i(d?a.parentNode:a),g=i(b);f(e,function(a){a.classList.remove(c.toggleActiveClass),d&&a.querySelector("[data-tab]").classList.remove(c.toggleActiveClass)}),f(g,function(a){a.classList.contains(c.contentActiveClass)&&(j(a),a.classList.remove(c.contentActiveClass))})},l=function(a,b,c){var d=a.parentNode;a.classList.add(c.toggleActiveClass),d&&"li"===d.tagName.toLowerCase()&&d.classList.add(c.toggleActiveClass),f(b,function(a){a.classList.add(c.contentActiveClass)})};c.toggleTab=function(a,b,c,d){var f=g(f||e,c||{}),h=document.querySelectorAll(b);f.callbackBefore(a,b),k(a,h[0],f),l(a,h,f),f.callbackAfter(a,b)};var m=function(a){var d=h(a.target,"[data-tab]");d&&(a.preventDefault(),c.toggleTab(d,d.getAttribute("data-tab"),b))};return c.destroy=function(){b&&(document.documentElement.classList.remove(b.initClass),document.removeEventListener("click",m,!1),b=null)},c.init=function(a){d&&(c.destroy(),b=g(e,a||{}),document.documentElement.classList.add(b.initClass),document.addEventListener("click",m,!1))},c});