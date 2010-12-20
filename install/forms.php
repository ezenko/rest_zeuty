<?php


function template_top($hint){
        /*-------------------------------------------------------------*/
        ?>
                        <html>
                        <head>
                        <title> Ready PG RealEstate Site Installation </title>
                        <meta http-equiv="Content-Language" content="ru">
                        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
                        </head>

                        <style>
						input, select, div, textarea {
							font: 11px tahoma, verdana, arial;
						}
						input.text, select {
							width: 100%;
						}
						fieldset {
							margin-bottom: 10px;
						}
						body{
							overflow: auto;
						}
						table, tr, td p {
                                font-size: 12px;
                                color: #000000;
                                font-family: Verdana, Tahoma, sans-serif;
								cursor: default;
						}
                        .hint{
                                background-color: #FFCC00;
                                border: solid 1px #999966;
                                font-size: 11px;
                                color: #660033;
                                font-family: Verdana, Tahoma, sans-serif;
                                text-align: center;
                        }
                        .main{
                                background-color: #FFFFEE;
                                border: solid 1px #DDDDDD;
                                font-size: 11px;
                                color: #660033;
                                font-family: Verdana, Tahoma, sans-serif;
                                text-align: center;
                        }
                        .error_area{
                                background-color: #FFFFFF;
                                border: solid 1px #DDDDDD;
                                padding: 5px;
                                font-size: 11px;
                                color: #FF6600;
                                font-family: Verdana, Tahoma, sans-serif;
                                text-align: left;
                        }
                        .table_fonts{
                                border-bottom: solid 1px #DDDDDD;
                                padding: 2px;
                                font-size: 11px;
                                color: #660033;
                                font-family: Verdana, Tahoma, sans-serif;
                        }
                        .sub_table_fonts{
                                padding: 0px;
                                padding-left: 20px;
                                font-size: 11px;
                                color: #664254;
                                font-family: Verdana, Tahoma, sans-serif;
                        }
                        .button {
                                font-family: Verdana, Tahoma, sans-serif;
                                font-size: 11px;
                                border: solid 1px;
                                color:#660033;
                                background-color:#DDDDDD
                        }
                        .form_fields {
                                font-family: Verdana, Tahoma, sans-serif;
                                font-size: 11px;
                                color:#660033;
                                text-align: right;
                        }
                        .form_input {
                                font-family: Verdana, Tahoma, sans-serif;
                                font-size: 11px;
                                color:#660033;
                                border: solid 1px #DDDDDD;
                                width: 200px;
                        }
                        h4{
                                font-family: Verdana, Tahoma, sans-serif;
                                font-size: 11px;
                                font-weight: bold;
                                color: #FF9966;
                                text-align: left;
                                padding-left: 20px;
                        }
                        </style>

                        <body>
                        <table width=100% cellspacing=0 cellpadding=5>
                        <tr>
                                <td align=center>
                                        <table class=hint width=100%>
                                                <tr><td><?=$hint?></td></tr>
                                        </table>
                                </td>
                        </tr>
                        <tr>
                                <td>
        <?php
        /*-------------------------------------------------------------*/
}

function template_bottom(){
        /*-------------------------------------------------------------*/
        ?>
                        </td>
                </tr>
                </table>
                <script type="text/javascript">
                function CheckLicense(val){
                	if (document.getElementById('check_box').checked == true ){
                		document.getElementById('sub_but').disabled = false;
                	} else {
                		document.getElementById('sub_but').disabled = true;
                	}
					return;
                }
                </script>
                </body>
                </html>
        <?php
        /*-------------------------------------------------------------*/
}

function template_lisence_page($str,$next_step){
	global $install;
	$form = "License Agreement Page";
	template_top($form);
	?>
		<table class="main" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center">
				<table width="400" cellpadding="5" cellspacing="0" style="margin: 20px; border: 1px solid #000000" align="center">
		        <tr>
		        	<td align="center">
		        	<textarea readonly cols="90" rows="40"><?php echo $str; ?></textarea>
		        	</td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" width="15"><input type="checkbox" onclick="CheckLicense();" value="1" id="check_box"></td>
							<td width="100%" align="left">I accept the terms of the license agreement</td>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td align="left"><input type="button" onclick="javascript: location.href='./index.php?sel=first'" value="next>>" class=button disabled id="sub_but"></td>
				</tr>
		        </table>
			</td>
		</tr>
		</table>
        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}

function template_first_page($str,$next_step){
        global $install;
        $form = "Step 1: Your Server Environment";
        template_top($form);
        /*-------------------------------------------------------------*/
        ?>

                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
    								<div align=left style="width: 600;">In this step, the PG RealEstate installer will determine if your system meets the requirements for the server environment. To use PG RealEstate system you must have PHP with MySQL support, and write-permissions on certain files.<br><br>
									<?php if($next_step){ ?>
									Congratulations! You may continue the installation. Click 'next' button to continue. &nbsp;
									<input type="button" onclick="javascript: location.href='./index.php?sel=1'" value="next>>" class=button>
									<?php }else{ ?>
									The installer has detected some problems with your server environment, which will not allow PG RealEstate to operate correctly.<br /><br />Please correct these issues and then refresh the page to re-check your environment.
									<?php } ?>
								<br><br>
								</div>
                            <table>
                                        <tr>
											<td align=center>
											<div style="width: 500;" class=error_area>
												<table width="100%">
												<?php
															echo $str;
												?>
												</table>
                                                <br><br>
											</div>
											</td>
										</tr>
                                </table>
                        </td>
                        </tr>
                </table>

        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}
function permission_str($str, $good, $sub=0){
	$ret_str = "<tr>\n";
	if($sub == 1)
		$ret_str .= "	<td class=\"sub_table_fonts\">".$str."</td>\n";
	else
		$ret_str .= "	<td class=\"table_fonts\">".$str."</td>\n";
	if($good==1){
		$s = "<font color=\"#339900\">+</font>";
	}else{
		$s = "<font color=\"#FF0000\">-</font>";
	}
	if($sub == 1)
		$ret_str .= "	<td align=center width=60 style=\"font-size:  16px;\"><b>".$s."</b></td>\n";
	else
		$ret_str .= "	<td class=\"table_fonts\" align=center width=60 style=\"font-size:  18px;\"><b>".$s."</b></td>\n";
	$ret_str .= "</tr>\n";
	return $ret_str;
}
function blank_str(){
	$ret_str = "<tr>\n";
	$ret_str .= "	<td class=\"table_fonts\" colspan=2 style=\"font-size: 2px\">&nbsp;</td>\n";
	$ret_str .= "</tr>\n";
	return $ret_str;
}
function template_database_server_form($err="", $data=""){
        global $install;
        $form = "Step 2: Database & Server Configuration";
        template_top($form);

        $doc_root = $_SERVER["DOCUMENT_ROOT"];
        $data["site_path"] = $install["install_path"];
        if($doc_root)
                $data["site_root"] = substr($install["install_path"], strlen($doc_root));
        switch ($install["system"]) {
                case "unix":
                        if(substr($data["site_path"], -1) == "/"){
                                $data["site_path"] = substr($data["site_path"], 0, -1);
                        }
                        if(substr($data["site_root"], -1) == "/"){
                                $data["site_root"] = substr($data["site_root"], 0, -1);
                        }
                break;
                case "win":
                        if(substr($data["site_path"], -1) == "\\"){
                                $data["site_path"] = substr($data["site_path"], 0, -1);
                        }
                        if(substr($data["site_root"], -1) == "\\"){
                                $data["site_root"] = substr($data["site_root"], 0, -1);
                        }
                break;
        }

        /*-------------------------------------------------------------*/
        ?>

                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
								<div align=left style="width: 600;">The PG RealEstate installer needs some information about your database to finish the installation. If you do not know this information, then please contact your website host or administrator. Please note that this is probably NOT the same as your FTP login information!
								<br><br>
								</div>
                                <form action="index.php" method=post>
                                <table>
                                        <tr>
										<td align=center>
										<div style="width: 500; " class=error_area>
                                                <?php if($err){?>
												<table width="100%">
                                                <tr>
                                                        <td class=error_area><?php echo $err; ?></td>
                                                </tr>
                                                </table><?php } ?>
                                                <input type=hidden name=sel value=2>
                                                <h4>Database Info</h4>
                                                <table width="100%">
                                                <tr>
                                                        <td width="40%" class=form_fields>db host: </td>
                                                        <td width="60%"><input type=text name="dbhost" value="<?=$data["dbhost"]?$data["dbhost"]:"localhost"?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>db name: </td>
                                                        <td width="60%"><input type=text name="dbname" value="<?=$data["dbname"]?$data["dbname"]:"realestate"?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>db user: </td>
                                                        <td width="60%"><input type=text name="dbuser" value="<?=$data["dbuser"]?$data["dbuser"]:"root"?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>db password: </td>
                                                        <td width="60%"><input type=text name="dbpass" value="<?=$data["dbpass"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>db Prefix: </td>
                                                        <td width="60%"><input type=text name="dbprefix" value="<?=$data["dbprefix"]?$data["dbprefix"]:"re_"?>" class=form_input></td>
                                                </tr>
                                                </table>
                                                <h4>Server Info</h4>
                                                <table width="100%">
                                                <tr>
                                                        <td width="40%" class=form_fields>Server Name: </td>
                                                        <td width="60%"><input type=text name="server" value="<?=$data["server"]?$data["server"]:"http://".$_SERVER["SERVER_NAME"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>Site Root: </td>
                                                        <td width="60%"><input type=text name="site_root" value="<?=$data["site_root"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>Site Path: </td>
                                                        <td width="60%"><input type=text name="site_path" value="<?=$data["site_path"]?>" class=form_input></td>
                                                </tr>
                                                </table>

                                        </div></td></tr>
                                        <tr><td align=right><br><input type=submit name="next" value="next>>" class=button></td></tr>
                                </table>
                                </form>
                        </td>
                        </tr>
                </table>

        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}
function template_db_restore($top_comment, $location){
        global $install;
        template_top($top_comment);
        /*-------------------------------------------------------------*/
?>
		<table class=main width=100%>
		<tr height=300>
			<td align=center>
				<br><br>

		<div align="center">
		<TABLE WIDTH=500 BORDER=0 CELLSPACING=0 CELLPADDING=0>
		<TR>
			<TD VALIGN=TOP STYLE="border: 1px solid #919B9C;">
			<TABLE WIDTH=100% HEIGHT=100% BORDER=0 CELLSPACING=1 CELLPADDING=0>
				<TR>
				<FORM NAME=skb METHOD=POST ACTION="{$form.action}">
				<TD class="main_content_text" VALIGN=TOP BGCOLOR=#F4F3EE STYLE="FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#FCFBFE,endColorStr=#F4F3EE); padding: 8px 8px;">
					<FIELDSET>
					<LEGEND  class="main_content_text">Creating Database&nbsp;</LEGEND>
					<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=2>
					<TR><TD class="main_content_text" COLSPAN=2><DIV ID=logarea STYLE="width: 100%; height: 140px; border: 1px solid #7F9DB9; padding: 3px; overflow: auto;"></DIV></TD></TR>
					<TR><TD class="main_content_text" WIDTH=31%>Table Status:</TD><TD WIDTH=69%><TABLE WIDTH=100% BORDER=1 CELLPADDING=0 CELLSPACING=0>
					<TR><TD  class="main_content_text" BGCOLOR=#FFFFFF><TABLE WIDTH=1 BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR=#5555CC ID=st_tab
					STYLE="FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#B7CEF2,endColorStr=#0045B5);
					border-right: 1px solid #AAAAAA"><TR><TD HEIGHT=12></TD></TR></TABLE></TD></TR></TABLE></TD></TR>
					<TR><TD  class="main_content_text">DB Status:</TD><TD><TABLE WIDTH=100% BORDER=1 CELLSPACING=0 CELLPADDING=0>
					<TR><TD BGCOLOR=#FFFFFF  class="main_content_text"><TABLE WIDTH=1 BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR=#00AA00 ID=so_tab
					STYLE="FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#CCFFCC,endColorStr=#00AA00);
					border-right: 1px solid #AAAAAA"><TR><TD HEIGHT=12></TD></TR></TABLE></TD>
					</TR></TABLE></TD></TR></TABLE>
					</FIELDSET>
					<SCRIPT>
					var WidthLocked = false;
					function s(st, so){
						document.getElementById('st_tab').width = st ? st + '%' : '1';
						document.getElementById('so_tab').width = so ? so + '%' : '1';
					}
					function l(str, color){
						switch(color){
							case 2: color = 'navy'; break;
							case 3: color = 'red'; break;
							default: color = 'black';
						}
						with(document.getElementById('logarea')){
							if (!WidthLocked){
								style.width = clientWidth;
								WidthLocked = true;
							}
							str = '<FONT COLOR=' + color + '>' + str + '</FONT>';
							innerHTML += innerHTML ? "<BR>\n" + str : str;
							scrollTop += 14;
						}
					}
					</SCRIPT>
					<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=2>
					<TR>
					<TD class="main_content_text" STYLE='color: #CECECE' ID=timer></TD>
					<TD ALIGN=RIGHT >
					</TD>
					</TR>
					</TABLE>
				</TD>
				</FORM>
				</TR>
			</TABLE>
			</TD>
		</TR>
		</TABLE>
</div>
<div width="600"><br><br><INPUT ID=back TYPE=button VALUE='<<Back' DISABLED onClick="window.close();opener.focus();" class=button> <INPUT ID=next TYPE=button VALUE='Next>>' DISABLED onClick="javascript: location.href='<?php echo $location?>'" class=button></div>
			</td>
		</tr>
		</table>

<?php
        /*-------------------------------------------------------------*/
        template_bottom();
}
function template_misc_parametrs_form($err="", $data=""){
        global $install;
        $form = "Step 3: Site Settings";
        template_top($form);

        /*-------------------------------------------------------------*/
        ?>
                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
                                <form action="index.php" method=post>
                                <table>
                                        <tr><td align=center><div style="width: 500; " class=error_area>
                                                <?php if($err){?><table width="100%">
                                                <tr>
                                                        <td class=error_area><?php echo $err; ?></td>
                                                </tr>
                                                </table><?php } ?>
                                                <input type=hidden name=sel value=4>
                                                <h4>Administrator Info</h4>
                                                <table width="100%">
                                                <tr>
                                                        <td width="40%" class=form_fields>administrator name: </td>
                                                        <td width="60%"><input type=text name="admin_name" value="<?=$data["admin_name"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>admin email: </td>
                                                        <td width="60%"><input type=text name="admin_email" value="<?=$data["admin_email"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>administrator login: </td>
                                                        <td width="60%"><input type=text name="admin_login" value="<?=$data["admin_login"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>password: </td>
                                                        <td width="60%"><input type=password name="admin_pass" value="<?=$data["admin_pass"]?>" class=form_input></td>
                                                </tr>
                                                <tr>
                                                        <td width="40%" class=form_fields>re-password: </td>
                                                        <td width="60%"><input type=password name="admin_repass" value="<?=$data["admin_repass"]?>" class=form_input></td>
                                                </tr>
                                                </table><br>

                                        </div></td></tr>
                                        <tr><td align=right><input type=submit name="next" value="next>>" class=button></td></tr>
                                </table>
                                </form>
                        </td>
                        </tr>
                </table>
        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}


function template_last_page(){
        global $install;
        $form = "Welcome to the Ready PG RealEstate Site Installation Program!";
        template_top($form);
        /*-------------------------------------------------------------*/
        ?>

                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
                                <table>
                                        <tr><td align=center><div style="width: 500;" class=error_area>
                                                PG RealEstate Site is successfully installed!<br><br>
                                                Please don't forget to set the following file to cronjobs:<br>
                                                /admin/admin_cron_birthdays.php <br>
												(file responsible for automatic members' congratulation on birthday)<br>
												/admin/admin_cron_feeds.php <br>
												(file responsible for automatic update of rss feeds)<br>                          												/admin/admin_cron_funds_end.php <br>
												 (file responsible for automatic sending notification for users which are about to run out of funds on their account)<br>                          
												/admin/admin_cron_match.php <br>
												 (necessary for users to receive system notifications, etc.)<br>
												/admin/admin_cron_unactive_ads.php <br>
												(file responsible for automatic listings deactivation which active period expired if site uses period of listing activity on the site)<br><br>

                                                Also don't forget to install countries which will be used on your site. Install path:<br>
                                                /install/countries
                                        </div></td></tr>
                                        <tr><td align=right><input type=submit name="next" value="Install countries>>" class=button onclick="javascript: location.href='countries/index.php'"></td></tr>
                                </table>
                        </td>
                        </tr>
                </table>

        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}

function template_permission_check_page($str,$next_step){
        $form = "File permissions";
        template_top($form);
        /*-------------------------------------------------------------*/
        ?>

                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
    								<div align=left style="width: 600;">
									<?if($err){?>
									<table width="100%">
                                    <tr><td class=error_area><? echo $err; ?></td></tr>
                                    </table>
									<?}?>
									
									On this step you will need to check some file permissions to close access to them for third parties. Please, don't pass this step as it is important for your site security. We recommend to set file permissions 644.<br><br>
									<? if($next_step){ ?>
									Congratulations! You finished the installation. Click 'Finish' button to continue. &nbsp;
									<input type="button" onclick="javascript: location.href='index.php?sel=6'" value="Finish" class=button>
									<? }else{ ?>
									Some files have incorrect permissions that may cause your site insecurity.
									<? } ?>
								<br><br>
								</div>
                            <table>
                                        <tr>
											<td align=center>
											<div style="width: 500;" class=error_area>
												<table width="100%">
												<?
															echo $str;
												?>
												</table>
                                                <br><br>
											</div>
											</td>
										</tr>
                                </table>
                        </td>
                        </tr>
                </table>

        <?
        /*-------------------------------------------------------------*/
        template_bottom();
}
?>