//var ajaxapi='http://10.3.70.15:8008/discuz/source/plugin/login_mobile/index.php';
var lochref = window.location.href;
var idx = lochref.indexOf("source/plugin/login_mobile");
var siteurl = lochref.substr(0,idx);
var ajaxapi = siteurl+"source/plugin/login_mobile/index.php";
console.log({siteurl:siteurl,ajaxapi:ajaxapi});
