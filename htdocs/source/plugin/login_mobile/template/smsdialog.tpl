<div id="smswindiv" class="fwinmask" style="position:fixed; z-index:999;top:35%;left:50%;display:none;margin-left:-208px;">
  <table cellpadding="0" cellspacing="0" class="fwin">
    <tr>
      <td class="t_l"></td>
      <td class="t_c"></td>
      <td class="t_r"></td>
    </tr>
    <tr>
      <td class="m_l"></td>
      <td class="m_c" id="fwin_content_login" fwin="login">
        <div id="main_messaqge_LqcK7" fwin="login">
          <div class="rfm bw0" style="width:400px;"></div>
          <div id="layer_lostpw_LqcK7" fwin="login">
            <h3 class="flb" id="fctrl_login" style="cursor: move;">
              <em id="returnmessage3_LqcK7" fwin="login">发送短信验证码</em>
              <span><a href="javascript:sms_hide_window();" class="flbc" title="关闭">关闭</a></span>
            </h3>
            <div class="c cl">
              <table style='width:100%;'>
                <tr>
                  <th width='80' style='text-align:right;vertical-align:top;padding-right:10px;'>验证码: </th>
                  <td style='padding-bottom:10px;'>
                    <input id="sms_seccode_txt" type="text" style="ime-mode:disabled;width:100px" class="txt px vm"/> 
                    <a href="javascript:sms_new_seccode();" class="xi2">换一个</a><br>
                    <span>输入下图中的字符</span><br>
                    <img id="sms_seccode_img" onclick="sms_new_seccode();" width="100" height="30" src="<%plugin_path%>/index.php?version=4&module=seccode"
                         class="vm" alt="" style="cursor:pointer;"/><br>
                    <b id='sms_error_msg' style='margin-top:5px;color:red'></b>
                  </td>
                </tr>
                <tr style='border-top:1px dotted #CDCDCD'>
                  <td></td>
                  <td style='padding-top:10px;'><a class="pn pnc" href="javascript:sms_submit();" style='padding:4px 5px;'><strong>提交</strong></a></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </td>
      <td class="m_r"></td>
    </tr>
    <tr>
      <td class="b_l"></td>
      <td class="b_c"></td>
      <td class="b_r"></td>
    </tr>
  </table>
</div>
<script>
  var sms_phone;
  var sms_regist;
  var sms_callback;
  var sms_seccode_url = "<%plugin_path%>/index.php?version=4&module=seccode";
  function sms_new_seccode() {
      var url = sms_seccode_url+"&tm="+new Date().getTime();
      document.getElementById("sms_seccode_img").src=url;
  }
  function sms_hide_window() {
      document.getElementById('smswindiv').style.display = "none";
  }
  function sms_open_window(phone, regist, callback) {
      sms_phone  = phone;
      sms_regist = regist;
      sms_callback = callback;
      document.getElementById('smswindiv').style.display = "block";
      var secdom = document.getElementById('sms_seccode_txt');
	  secdom.value = "";
	  secdom.focus();
  }
  function sms_trim_string(str) {
      return str.replace(/^\s+/g,"").replace(/\s+$/g,"");
  }
  function sms_submit() {
      var errmsgdom = document.getElementById('sms_error_msg');
      errmsgdom.innerHTML = "";
      var secdom = document.getElementById('sms_seccode_txt');
      var seccode = sms_trim_string(secdom.value);
      if (seccode=="") {
          errmsgdom.innerHTML = "请输入验证码";
          secdom.value = "";
          secdom.focus();
          return;
      }
      var params = {
          seccode: seccode,
          phone: sms_phone,
          regist: sms_regist
      };
      console.log(params);
      jQuery.ajax({
          type: "post",
          async: true,
          url: "<%ajax_api%>&module=smscode",
          data: params,
          dataType: "json",
          complete: function(res) {},
          success: function (res) {
              if (res.retcode!=0) {
                  errmsgdom.innerHTML = res.retmsg;
              } else {
                  sms_hide_window();
                  sms_callback();
              }
          },
          error: function (data) {
              errmsgdom.innerHTML = "error: "+data;
          }
      });
  }
</script>
