<?php
/**
 *  插件设置页面
 */
function wpftp_setting_page() {
// 如果当前用户权限不足
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient privileges!');
	}

	$wpftp_options = get_option('wpftp_options');
	if ($wpftp_options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
		if($_POST['type'] == 'cos_info_set') {

            $wpftp_options['no_local_file'] = (isset($_POST['no_local_file'])) ? True : False;
            $wpftp_options['ftp_server'] = (isset($_POST['ftp_server'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_server']))) : '';
            $wpftp_options['ftp_user_name'] = (isset($_POST['ftp_user_name'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_user_name']))) : '';
            $wpftp_options['ftp_user_pass'] = (isset($_POST['ftp_user_pass'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_user_pass']))) : '';
            $wpftp_options['ftp_basedir'] = (isset($_POST['ftp_basedir'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_basedir']))) : '/';

			// 不管结果变没变，有提交则直接以提交的数据 更新wpftp_options
			update_option('wpftp_options', $wpftp_options);

			# 替换 upload_url_path 的值
			update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path'])))));

			?>
            <div style="font-size: 25px;color: red; margin-top: 20px;font-weight: bold;"><p>WPFTP插件设置保存完毕!!!</p></div>

			<?php

		}
}

?>

    <style>
        table {
            border-collapse: collapse;
        }

        table, td, th {border: 1px solid #cccccc;padding:5px;}
        .buttoncss {background-color: #4CAF50;
            border: none;cursor:pointer;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;border-radius: 5px;
            font-size: 12px;font-weight: bold;
        }
        .buttoncss:hover {
            background-color: #008CBA;
            color: white;
        }
        input{border: 1px solid #ccc;padding: 5px 0px;border-radius: 3px;padding-left:5px;}
    </style>
<div style="margin:5px;">
    <h2>WordPress FTP（WPFTP）自建FTP存储设置</h2>
    <hr/>
    
        <p>WordPress FTP（简称:WPFTP），基于自建FTP存储与WordPress实现静态资源到FTP存储中。提高网站项目的访问速度，以及静态资源的安全存储功能。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2186.html" target="_blank">WPCOS发布页面地址</a> / <a href="https://www.laobuluo.com/2196.html" target="_blank"> <font color="red">WPCOS安装详细教程</font></a></p>
        <p>优惠促销： <a href="https://www.laobuluo.com/tengxunyun/" target="_blank">最新腾讯云优惠汇总</a> / <a href="https://www.laobuluo.com/goto/qcloud-cos" target="_blank">腾讯云COS资源包优惠</a></p>
        <p>站长互助QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（宗旨：多做事，少说话，效率至上）</p>
   
      <hr/>
    <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPFTP_BASEFOLDER . '/actions.php'); ?>" name="wpcosform" method="post">
        <table>
            <tr>
                 <td style="text-align:right;">
                    <b>FTP服务器地址：</b>
               </td>
                <td>
                    <input type="text" name="ftp_server" value="<?php echo esc_attr($wpftp_options['ftp_server']); ?>" size="50"
                           placeholder="FTP服务器地址"/>
                    <p>直接填写我们存储桶所属地区，示例：ap-shanghai</p>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>FTP用户名：</b>
                </td>
                <td>
                    <input type="text" name="ftp_user_name" value="<?php echo esc_attr($wpftp_options['ftp_user_name']); ?>" size="50"
                           placeholder="FTP用户名"/>

                    
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>FTP密码：</b>
                 </td>
                <td><input type="text" name="ftp_user_pass" value="<?php echo esc_attr($wpftp_options['ftp_user_pass']); ?>" size="50" placeholder="FTP密码"/></td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>远程URL：</b>
                </td>
                <td>
                    <input type="text" name="upload_url_path" value="<?php echo esc_url(get_option('upload_url_path')); ?>" size="50"
                           placeholder="请输入远程URL"/>

                    <p><b>设置注意事项：</b></p>

                    <p>1. 一般我们是以：<code>http://{cos域名}/{本地文件夹}</code>，同样不要用"/"结尾。</p>

                    <p>2. <code>{cos域名}</code> 是需要在设置的存储桶中查看的。"存储桶列表"，当前存储桶的"基础配置"的"访问域名"中。</p>

                    <p>3. 如果我们自定义域名的，<code>{cos域名}</code> 则需要用到我们自己自定义的域名。</p>
                    <p>4. 示范1： <code>https://laobuluo-xxxxxxx.cos.ap-shanghai.myqcloud.com/wp-content/uploads</code></p>
                    <p>5. 示范2： <code>https://cos.laobuluo.com/wp-content/uploads</code></p>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>FTP存储子目录(非必填,默认为/)：</b>
                </td>
                <td><input type="text" name="ftp_basedir" value="<?php echo esc_attr($wpftp_options['ftp_basedir']); ?>" size="50" placeholder="FTP存储子目录(非必填,默认为/)"/></td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>不在本地保存：</b>
                </td>
                <td>
                    <input type="checkbox"
                           name="no_local_file"
                        <?php
                            if ($wpftp_options['no_local_file']) {
                                echo 'checked="TRUE"';
                            }
					    ?>
                    />

                    <p>如果不想同步在服务器中备份静态文件就 "勾选"。我个人喜欢只存储在腾讯云COS中，这样缓解服务器存储量。</p>
                </td>
            </tr>
            
            <tr>
                <th>
                    
                </th>
                <td><input type="submit" name="submit" value="保存WPFTP设置" class="buttoncss" /></td>

            </tr>
        </table>
        
        <input type="hidden" name="type" value="cos_info_set">
    </form>
</div>
<?php
}
?>