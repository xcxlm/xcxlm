function PlatLogin()
{
    var store;

    function show_login_list()
    {
        var html = '';
        var options = [
            ['qq','QQ登录'],
            ['weixin','微信登录'],
            ['weibo','新浪微博登录'],
            ['link','其他']
        ];
        for (var i=0; i<store.size(); ++i) {
            var item = store.get(i);
            var sel = "<select name='iconsel' data-idx='"+i+"'>";
            for (var k=0; k<options.length; ++k) {
                var active = options[k][0]==item.icon ? 'selected' : '';
                sel += "<option value='"+options[k][0]+"' "+active+">"+options[k][1]+"</option>";
            }
            html += "<tr>"+
                "<td><input type='text' name='ordertxt' data-idx='"+i+"' value='"+item.displayorder+"' class='txt' style='width:40px;'></td>"+
                "<td>"+sel+"</td>"+
                "<td><input type='text' name='texttxt' data-idx='"+i+"' value='"+item.text+"' class='txt'></td>"+
                "<td><input type='text' name='urltxt' data-idx='"+i+"' value='"+item.url+"' class='txt' style='width:300px;'></td>"+
                "<td><a href='javascript:void(0);' name='delbtn' data-idx='"+i+"' style='cursor:pointer;'>删除</a></td>"+
              "</tr>";
        }
        jQuery('#loginstbody').html(html);
        jQuery("[name=urltxt]").change(function(){
            var idx = jQuery(this).data('idx');
            var im = store.get(idx);
            im.url = jQuery(this).val();
			jQuery("#resmsg").html('');
        });
        jQuery("[name=texttxt]").change(function(){
            var idx = jQuery(this).data('idx');
            var im = store.get(idx);
            im.text = jQuery(this).val();
			jQuery("#resmsg").html('');
        });
        jQuery("[name=ordertxt]").change(function(){
            var idx = jQuery(this).data('idx');
            var im = store.get(idx);
            im.displayorder = jQuery(this).val();
			jQuery("#resmsg").html('');
        });
        jQuery("[name=iconsel]").change(function(){
            var idx = jQuery(this).data('idx');
            var im = store.get(idx);
            im.icon = jQuery(this).children('option:selected').val();
			jQuery("#resmsg").html('');
        });
        jQuery("[name=delbtn]").click(function(){
            var idx = jQuery(this).data('idx');
            //var im = store.get(idx);
            //im.displayorder = jQuery(this).children('option:selected').val();
            store.remove(idx);
			jQuery("#resmsg").html('');
        });
        
    }

    this.init = function() {
        store = new MWT.Store({
            "url": "../../ui/ajax.php"
        });
        store.on('load',show_login_list);

        jQuery('#addbtn').click(function(){
            var item = {
                'displayorder': store.size(),
                'icon' : 'qq',
                'text' : 'QQ登录',
                'url'  : v.qqloginurl
            };
            store.push(item);
			jQuery("#resmsg").html('');
        });
        jQuery('#subbtn').click(function(){
			jQuery("#resmsg").html('');
            var list = [];
            for (var i=0; i<store.size(); ++i) {
                var item = store.get(i);
                list.push({
                    displayorder: item.displayorder,
                    icon : item.icon,
                    text : item.text,
                    url  : item.url
                });
            }
            jQuery("#subbtn").attr("disabled","disabled");   
            jQuery.ajax({
                type     : "post",
                async    : false,
                url      : v.adminapi+'setlogins',
                data     : {list:list},
                dataType : "json",
                complete : function(res) {
                    jQuery("#subbtn").removeAttr("disabled");
                },
                success: function (res) {
                    if (res.retcode==0) {
                        var s = "<span style='color:darkgreen'>设置已保存</span>";
					    jQuery("#resmsg").html(s);
                    } else {
                        var s = "<span style='color:red'>"+res.retmsg+"</span>";
					    jQuery("#resmsg").html(s);
                    }
                },
                error: function (data) {
			        var s = "<span style='color:red'>您提交的请求中含有非法字符</span>";
			        jQuery("#resmsg").html(s);
                }
            });
        });

        store.load(v.platlogins);
    };
}
var plat_login = new PlatLogin();
