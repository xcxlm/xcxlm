// 登录注册框
function show_fieldset(list)
{
    var code = "<fieldset style='margin-top:10px;'><div class='am-form jay-form'>";

    for(var i=0;i<list.length;++i) {
        var im=list[i];
        switch(im.type) {
          case 'text':
          case 'password':
            var divid = im.divid ? "id=\""+im.divid+"\" style=\"display:none;\"" : "";
		    code += "<div class='am-input-group am-input-group-default' "+divid+">"+
				"<span class='am-input-group-label'><i class='"+im.icon+" am-icon-fw'></i></span>"+
				"<input id='"+im.id+"' type='"+im.type+"' class='am-form-field' placeholder='"+im.placeholder+"'>"+
			"</div>";
            break;
          case "button":
            var icon = '';
            if (im.icon && im.icon!='') icon = "<i class='am-icon-"+im.icon+"'></i>";
            var cls = im.cls ? im.cls : 'am-btn-success';
            if (im.icon=='qq') cls = 'am-btn-primary';
            else if (im.icon=='weixin') cls = 'am-btn-success';
            else if (im.icon=='weibo') cls = 'am-btn-warning';
            else if (im.icon=='link') cls = 'am-btn-danger';
            var click = '';
            if (im.url && im.url!='') {
                click = 'onclick="window.location=\''+im.url+'\';"';
            }
            code += "<button id='"+im.id+"' type='button' class='am-btn "+cls+" am-btn-block' "+click+">"+icon+"&nbsp;&nbsp;"+im.text+"</button>";
            break;
          case "select":
            code += "<div class='am-input-group am-input-group-default'>"+
                "<span class='am-input-group-label'><i class='"+im.icon+" am-icon-fw'></i></span>"+
                "<select id='"+im.id+"' class='am-form-field'>";
            for(var k=0; k<im.options.length; ++k) {
                var option=im.options[k];
                code += "<option value='"+option.value+"'>"+option.name+"</option>";
            }
            code += "</select></div>";
            break;
          case "seccode":
		    code += "<div class='am-input-group am-input-group-default'>"+
				"<span class='am-input-group-label'><i class='"+im.icon+" am-icon-fw'></i></span>"+
				"<input id='"+im.id+"' type='text' class='am-form-field' placeholder='"+im.placeholder+"'>"+
                "<span class='am-input-group-btn'>"+
                  "<button class='am-btn am-btn-default' style='margin-top:0;padding:0 0px;' type='button'>"+
                  "<img id='imgcode' src='"+ajaxapi+"?version=4&module=seccode'/></button>"+
                "</span>"+
			"</div>";
            break;
          case "pcode":
            code += "<div class='am-input-group am-input-group-default'>"+
                "<span class='am-input-group-label'><i class='"+im.icon+" am-icon-fw'></i></span>"+
                "<input id='"+im.id+"' type='text' class='am-form-field' placeholder='"+im.placeholder+"'>"+
                "<span class='am-input-group-btn'>"+
                  "<button class='am-btn am-btn-default' style='margin-top:0;font-size:13px;width:122px;' type='button' id='pcode-btn'>发送短信验证码</button>"+
                "</span>"+
			"</div>";
            break;
          default:
            code += im.html;
            break;
        }
    }
    code += "</fieldset>";
    return code;
}


// 按钮
function show_am_button(im)
{
    var style = im.style ? "style='"+im.style+"'" : "";
    var code = "<div class='widget-list'>"+
          "<button id='"+im.id+"' type='button' class='am-btn am-btn-block "+im.cls+"' "+style+">"+im.title+"</button>"+
       "</div>";
    return code;
}


/* 判断是否是手机号 */
function is_phone(a)
{
    if(!(/^1[1|2|3|4|5|6|7|8|9][0-9]\d{8}$/.test(a))){ 
        return false;
    }
    return true;
}

/* 获取url中的请求参数 */
function getQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

/* 获取refer */
function getRefer()
{
    try {
		var refer=getQueryString("refer");
		if (!refer && window.history.length>1 && document.referrer!="") {
			var refer = document.referrer;
		}
		if (!refer) throw new Error("null");
			var filter_refers = [
				"login_mobile/fe/login.html",
				"login_mobile/fe/regist.html",
				"login_mobile/fe/findpass.html",
				"login_mobile/fe/bind.html"
					];
		for (var i=0;i<filter_refers.length; ++i) {
			if (refer.indexOf(filter_refers[i])>0) {
				throw new Error(refer+" [filter]");
			}
		}
		console.log("refer: "+refer);
		return refer;
    } catch (e) {
		console.log("refer: "+e.message);
        return null;
    }
}

/* 获取url带的refer */
function getUrlSubfix()
{
    var refer = getRefer();
    if(refer) return "?refer="+encodeURIComponent(refer);
    return "";
}

/* 欢迎页面 */
function welcome(res) 
{
    $("#frame-body").css("background-color","#fff");
    var fields = [
        {type:'html',html:"<p align='center' style='margin:5px;color:gray;'>欢迎回来，<b>"+res.username+"</b></p>"},
	    {type:'html',html:"<p align='center' style='color:gray;'><a href='"+getSiteUrl()+"' style='color:#07c;'>返回首页</a></p><br>"},
        {type:'button',id:"logout-btn",text:"退出登录"}
    ];
    var code = show_fieldset(fields);
    $("#frame-body").html(code);
    $("#logout-btn").click(function(res){
        if (!window.confirm("确定要退出登录吗？")) return;
        require("ajax").post("login&action=logout",{},function(res){
            window.location.reload();
        });
    });
}

function getSiteUrl()
{
    var lochref = window.location.href;
    var idx = lochref.indexOf("source/plugin/login_mobile");
    var siteurl = lochref.substr(0,idx);
    console.log("siteurl: "+siteurl);
    return siteurl;
}

