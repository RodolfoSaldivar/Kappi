(function(a,b){if(typeof define==="function"&&define.amd){define(b)}else{if(typeof exports==="object"){module.exports=b()}else{a.ResizeSensor=b()}}}(this,function(){var c=window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||function(d){return window.setTimeout(d,20)};function b(g,k){var f=Object.prototype.toString.call(g);var h=("[object Array]"===f||("[object NodeList]"===f)||("[object HTMLCollection]"===f)||("undefined"!==typeof jQuery&&g instanceof jQuery)||("undefined"!==typeof Elements&&g instanceof Elements));var e=0,d=g.length;if(h){for(;e<d;e++){k(g[e])}}else{k(g)}}var a=function(d,h){function g(){var m=[];this.add=function(i){m.push(i)};var l,k;this.call=function(){for(l=0,k=m.length;l<k;l++){m[l].call()}};this.remove=function(j){var i=[];for(l=0,k=m.length;l<k;l++){if(m[l]!==j){i.push(m[l])}}m=i};this.length=function(){return m.length}}function f(i,j){if(i.currentStyle){return i.currentStyle[j]}else{if(window.getComputedStyle){return window.getComputedStyle(i,null).getPropertyValue(j)}else{return i.style[j]}}}function e(r,t){if(!r.resizedAttached){r.resizedAttached=new g();r.resizedAttached.add(t)}else{if(r.resizedAttached){r.resizedAttached.add(t);return}}r.resizeSensor=document.createElement("div");r.resizeSensor.className="resize-sensor";var l="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;";var o="position: absolute; left: 0; top: 0; transition: 0s;";r.resizeSensor.style.cssText=l;r.resizeSensor.innerHTML='<div class="resize-sensor-expand" style="'+l+'"><div style="'+o+'"></div></div><div class="resize-sensor-shrink" style="'+l+'"><div style="'+o+' width: 200%; height: 200%"></div></div>';r.appendChild(r.resizeSensor);if(f(r,"position")=="static"){r.style.position="relative"}var x=r.resizeSensor.childNodes[0];var m=x.childNodes[0];var q=r.resizeSensor.childNodes[1];var u=function(){m.style.width=100000+"px";m.style.height=100000+"px";x.scrollLeft=100000;x.scrollTop=100000;q.scrollLeft=100000;q.scrollTop=100000};u();var k=false;var n=function(){if(!r.resizedAttached){return}if(k){r.resizedAttached.call();k=false}c(n)};c(n);var j,v;var s,i;var w=function(){if((s=r.offsetWidth)!=j||(i=r.offsetHeight)!=v){k=true;j=s;v=i}u()};var p=function(A,z,y){if(A.attachEvent){A.attachEvent("on"+z,y)}else{A.addEventListener(z,y)}};p(x,"scroll",w);p(q,"scroll",w)}b(d,function(i){e(i,h)});this.detach=function(i){a.detach(d,i)}};a.detach=function(d,e){b(d,function(f){if(f.resizedAttached&&typeof e=="function"){f.resizedAttached.remove(e);if(f.resizedAttached.length()){return}}if(f.resizeSensor){if(f.contains(f.resizeSensor)){f.removeChild(f.resizeSensor)}delete f.resizeSensor;delete f.resizedAttached}})};return a}));