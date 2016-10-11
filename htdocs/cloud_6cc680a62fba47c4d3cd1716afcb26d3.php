<?php
/**
 * 用于DISCUZ X站长注册的工具
 *
 * $Id: discuzx_utility.php 12116 2012-03-12 07:07:22Z yexinhao $
 */

require './source/class/class_core.php';

$cachelist = array();
$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init_cron = false;
$discuz->init_setting = true;
$discuz->init_user = false;
$discuz->init_session = false;

$discuz->init();

if ($_POST) {
	$fromCloud = $_POST['fromCloud'];
	if ($fromCloud) {
		if (is_file(DISCUZ_ROOT . './data/discuzx_utility.lock')) {
			echo "LOCK";
		} else {
			echo "OK";
		}
		exit;
	}

	$siteUrl = $_POST['siteUrl'];

	$action = $_POST['action'];
	if ($action == 'restore') {
		$my_siteid = $_POST['my_siteid'];
		$my_sitekey = $_POST['my_sitekeys'][$my_siteid];
		$cloud_status = intval($_POST['cloud_status'][$my_siteid]);

		if (!$my_siteid || !$my_sitekey) {
			echo "无效的my_siteid或my_sitekey";
			exit;
		}

		DB::query("REPLACE INTO " . DB::table('common_setting') . " (skey, svalue) VALUES ('my_siteid', '{$my_siteid}'), ('my_sitekey', '{$my_sitekey}'), ('cloud_status', $cloud_status)");
//		C::t('common_setting')->update_batch(array('my_siteid' => $my_siteid, 'my_sitekey' =>$my_sitekey ,'cloud_status' => $cloud_status));
		require_once libfile('function/cache');
		updatecache('setting');
		my_show_message("Discuz!云平台站点信息恢复成功 (请立即到管理后台云平台同步站点信息)");
	}
} else {
	if ($_GET['q'] == 'forgot') {
		$sites = my_site_restore();
		my_forgot($sites);

	} else {
		my_index();
	}
}

function my_header() {
	header("Content-Type: text/html; charset=utf-8");
	echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DISCUZ X站点注册工具 | 找回(恢复)站点信息、删除站点信息</title>
		<style type="text/css">
			body {
				width: 70%;
			}
			.msg, .warning {
				padding: 10px;
				border: 1px solid #06c;
				background-color: #c6dff9;
			}

			.warning {
				border-color: #ffd700;
				background-color: #ffc;
			}
			.op {
				line-height: 2em;
			}
			.op a {
				font-size: 1.2em;
			}
		</style>
	</head>
	<body class="sidebars">\n
EOT;
}

function my_footer() {
	global $_G;
	echo <<<EOT
			<hr />
			<div class="warn">
				<h3>注意事项</h3>
				<ul>
					<li>请勿随意公开该文件地址</li>
					<li>使用完毕之后，请<strong>立即删除</strong>该文件</li>
				</ul>
			</div>
			<p><a href="{$_G['siteurl']}">返回我的网站</a> | <a href="http://www.discuz.net/" target="_blank">支持论坛</a></p>
	</body>
</html>\n
EOT;
}

function my_index() {
	global $_G;
	$siteUrl = $_G['siteurl'];
	$my_siteid = $_G['setting']['my_siteid'] ? $_G['setting']['my_siteid'] : '无';
	$my_sitekey = $_G['setting']['my_sitekey'] ? $_G['setting']['my_sitekey'] : '无';
	$cloud_status = $_G['setting']['cloud_status'] ? ($_G['setting']['cloud_status'] ? '开启': '关闭') : '无';;
	$uri = my_get_uri();
	my_header();
	echo <<<EOT
		<form method="POST">
			<dl>
				<dt>当前站点地址:</dt>
				<dd>$siteUrl</dd>

				<dt>当前站点ID:</dt>
				<dd>$my_siteid</dd>

				<dt>当前站点Key:</dt>
				<dd>$my_sitekey</dd>

				<dt>当前云平台状态:</dt>
				<dd>$cloud_status</dd>
			</dl>
			<p class="op">
				如果您要恢复Discuz!云平台数据，请<a href="$uri?q=forgot">点这里继续</a>
			</p>
			<p class="warning">以上操作可能导致Discuz!云平台中当前站点服务无法使用、用户信息丢失！</p>
		</form>
EOT;
	my_footer();
}

function my_forgot($sites) {
	global $_G;
	my_header();
	my_title('恢复Discuz!云平台上的站点信息');
	$siteUrl = $_G['siteurl'];
	$table = "<table border=\"1\">
		<tr>
			<th></th>
			<th>站点ID</th>
			<th>站点Key</th>
			<th>站点uniqueid</th>
			<th>创建时间</th>
			<th>状态</th>
		</tr>";
	foreach($sites as $site) {
		$sId = $site['sId'];
		$table .= "<tr>
				<td><input type='radio' name='my_siteid' value='$sId' /></td>
				<td>{$site['sId']}</td>
				<td>{$site['sKey']}</td>
				<td>{$site['sSiteKey']}</td>
				<td>{$site['sCreated']}</td>
				<td>{$site['status']}</td>
				<input type='hidden' name='my_sitekeys[$sId]' value='$site[sKey]' />
				<input type='hidden' name='cloud_status[$sId]' value='$site[cloudStatus]' />
			</tr>";
	}
	$table .= "</table>\n";
	echo <<<EOT
		<form method="POST">
			$table
			<p>
				<input type="hidden" name="action" value="restore" />
				<input type="submit" name="submit" value="恢复云平台数据" />
			</p>
		</form>
EOT;
	my_footer();
}

function my_title($title = 'index') {
	echo "<h2>$title</h2>\n";
	return true;
}

function my_site_restore() {

    if (class_exists('Cloud')) {
        // X2.5 修复
        $cloudClient = Cloud::loadClass('Service_Client_Cloud');

        try {
            $result = $cloudClient->resume();
        } catch (Exception $e) {
            $msg = sprintf('操作失败：%s (#%s)', $e->getMessage(), $e->getCode());
            my_show_message($msg);
        }
    } else {
        // X2 修复
        require_once DISCUZ_ROOT.'/api/manyou/Manyou.php';
        $cloudClient = new Discuz_Cloud_Client();

        $result = $cloudClient->resume();

        if(!$result || $cloudClient->errno) {
            $msg = sprintf('操作失败：%s (#%s)', $cloudClient->errmsg, $cloudClient->errno);
            my_show_message($msg);
        }
    }

	// lock file
	$fp = fopen(DISCUZ_ROOT . './data/discuzx_utility.lock', 'w');
	if ($fp === false) {
		my_show_message(sprintf('请确保 <strong>%s/data/discuzx_utility.lock</strong> 文件可写!', DISCUZ_ROOT));
	}
	fclose($fp);
	return $result;

}

function my_show_message($msg) {
	my_header();
	printf('<p class="msg">%s</p>', $msg);
	my_footer();
	exit;
}

function my_get_uri() {
	$uri = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
	return $uri;
}

?>
