(function(e){function n(){var t=r(this);if(!isNaN(t.datetime)){e(this).text(i(t.datetime))}return this}function r(n){n=e(n);if(!n.data("timeago")){n.data("timeago",{datetime:t.datetime(n)});var r=e.trim(n.text());if(r.length>0){n.attr("title",r)}}return n.data("timeago")}function i(e){return t.inWords(s(e))}function s(e){return(new Date).getTime()-e.getTime()}e.timeago=function(t){if(t instanceof Date){return i(t)}else if(typeof t==="string"){return i(e.timeago.parse(t))}else{return i(e.timeago.datetime(t))}};var t=e.timeago;e.extend(e.timeago,{settings:{refreshMillis:1e3,allowFuture:false,strings:{prefixAgo:null,prefixFromNow:null,suffixAgo:"ago",suffixFromNow:"from now",seconds:"a few seconds ago",minute:"about a minute",minutes:"%d minutes",hour:"about an hour",hours:"about %d hours",day:"a day",days:"%d days",month:"about a month",months:"%d months",year:"about a year",years:"%d years",numbers:[]}},inWords:function(t){function l(r,i){var s=e.isFunction(r)?r(i,t):r;var o=n.numbers&&n.numbers[i]||i;return s.replace(/%d/i,o)}var n=this.settings.strings;var r=n.prefixAgo;var i=n.suffixAgo;if(this.settings.allowFuture){if(t<0){r=n.prefixFromNow;i=n.suffixFromNow}}var s=Math.abs(t)/1e3;var o=s/60;var u=o/60;var a=u/24;var f=a/365;var c=s<45&&l(n.seconds,Math.round(s))||s<90&&l(n.minute,1)||o<45&&l(n.minutes,Math.round(o))||o<90&&l(n.hour,1)||u<24&&l(n.hours,Math.round(u))||u<48&&l(n.day,1)||a<30&&l(n.days,Math.floor(a))||a<60&&l(n.month,1)||a<365&&l(n.months,Math.floor(a/30))||f<2&&l(n.year,1)||l(n.years,Math.floor(f));return e.trim([r,c,i].join(" "))},parse:function(t){var n=e.trim(t);n=n.replace(/\.\d\d\d+/, "");n=n.replace(/-/, "/").replace(/-/, "/");n=n.replace(/T/, " ").replace(/Z/, " UTC");n=n.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2");return new Date(n)},datetime:function(n){var r=e(n).get(0).tagName.toLowerCase()==="time";var i=r?e(n).attr("datetime"):e(n).attr("title");return t.parse(i)}});e.fn.timeago=function(){var e=this;e.each(n);var r=t.settings;if(r.refreshMillis>0){setInterval(function(){e.each(n)},r.refreshMillis)}return e};document.createElement("abbr");document.createElement("time")})(jQuery);
function localize(t){
 t=t.replace(/\-/g, "/");
 if(t.match('EST')){
  return t.replace('EST', "");
 }else{
  var d=new Date(t+" EST");
  return d.toJSON();
 }
}
setInterval(function(){
 $(".time").each(function(){
 	/* Timeago on each .time elems */
  	if($(this).data('timeago') != "ran"){
   	loc	= localize($(this).text());
   	date	= new Date(loc);
   	/* Only do timeago if the date is valid */
   	if(date != "Invalid Date"){
    		$(this).attr("title", loc);
    		$(this).text(date.toString());
    		$(this).timeago();
    		$(this).data('timeago', "ran");
   	}
  	}
 });
},1000);