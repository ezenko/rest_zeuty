<?php        

function template_top($hint){
        /*-------------------------------------------------------------*/
        ?>
                        <html>
                        <head>
                        <title> PG RealEstate Installation / Countries Install</title>
                        <meta http-equiv="Content-Language" content="ru">
                        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
                        </head>

                        <style>
						input, select, div {
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
                                                <tr><td><?php echo($hint); ?></td></tr>
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
                </body>
                </html>
        <?php
        /*-------------------------------------------------------------*/
}

function template_first_page($str,$next_step,$err=""){
        global $install;
        $form = "Step 1: Your Server Environment";
        template_top($form);
        /*-------------------------------------------------------------*/
        ?>

                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
    								<div align=left style="width: 600;">
									<?php if($err){ ?>
									<table width="100%">
                                    <tr><td class=error_area><?php echo $err; ?></td></tr>
                                    </table>
									<?php } ?>

									In this step, the installer will determine if your system meets the requirements for the server environment. To continue install process you must have PHP with MySQL support, and write-permissions on certain files.<br><br>
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
function template_indicate_countries_form($str,$countries){
        global $install, $country;
        $form = "Step 2: Indicate Countries Form";
        template_top($form);
        /*-------------------------------------------------------------*/
        ?>
		<form action="index.php?sel=2" method=post name="cform" onsubmit="return check_fields()" enctype="application/x-www-form-urlencoded">
                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
    								<div align=left style="width: 600;">
									<?php if($err){ ?>
									<table width="100%">
                                    <tr><td class=error_area><?php echo $err; ?></td></tr>
                                    </table>
									<?php } ?>

									In this step, you must determine what countries will be used on your site<br><br>
									Please, pay attention to the fact that the more cities are checked, the more time installation will take and the more room you will need on your server. The total of all cities' information is about 300Mb.
									<br>
									We don't recommend you checking countries which will not be used in the future. City table includes comparatively small regions alongside with cities that's why there is no need to indicate user city name manually.
									<br><br>
									After checking needed countries, please, click 'next' button to continue. &nbsp;
									<input type="submit" value="next>>" class=button>
									<br><br>
									<?php if(count($countries)){ ?>I have some countries allready installed and I do not want to install them again <input name="do_not_reinstall" type="checkbox" onclick="javascript: if (this.checked) disableCountries(true); else disableCountries(false);"><br><br><?php } ?>
								</div>
                            <table>
                                        <tr>
											<td align=center>
											<div style="width: 800;" class=error_area>
												<table width="100%" align=center>
												<tr>
												 <td align=center><a class=error_area href="#" onclick="javascript:setCheckboxes(1)">Select All</a></td>
												 <td align=center><a class=error_area href="#" onclick="javascript:setCheckboxes(0)">Unselect All</a></td>
												 <td align=center><a class=error_area href="#" onclick="javascript:setUSA()">Select USA</a></td>
												 <td align=center><a class=error_area href="#" onclick="javascript:setCanada()">Select Canada</a></td>
												 <td align=center><a class=error_area href="#" onclick="javascript:setAustralia()">Select Australia</a></td>
												 <td align=center><a class=error_area href="#" onclick="javascript:setEuropa()">Select Europa</a></td>
												</tr>
												</table>
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
		</form>

	<script>

	function setCheckboxes(do_check)
	{	    
	    for (var i = 1; i <= <?php echo count($country); ?>; i++) {
			elts = document.cform.elements['sel_country['+ i +']'];
			//alert();
			if (typeof(elts) != 'undefined') elts.checked = do_check;
	    } // end for
	    return true;
	}
	function setUSA() {
		document.cform.elements['sel_country[251]'].checked = 1;
	}
	function setCanada() {
		document.cform.elements['sel_country[43]'].checked = 1;
	}
	function setAustralia() {
		document.cform.elements['sel_country[14]'].checked = 1;
	}
	function setEuropa() {
		europa = new Array(2,173,30,15,24,37,231,63,64,90,75,71,223,81,82,250,119,144,137,162,172,195,196,201,230,218,217,215,229,248);
		for (i=0; i < europa.length; i++){
			document.cform.elements['sel_country['+europa[i]+']'].checked = 1;
		}
	}
	function disableCountries(flag) {
		<?php if(count($countries) == 1){ ?>
		document.cform.elements['sel_country[<?php echo $countries[0]?>]'].disabled = flag;
		<?php } else { ?>
		dis_country = new Array(<?php echo implode(",", $countries); ?>);
		for (i=0; i < dis_country.length; i++){
			document.cform.elements['sel_country['+dis_country[i]+']'].disabled = flag;
		}
		<?php } ?>
	}

	function check_fields() {
		is_c = 0;
		for (var i = 1; i <= <?php echo count($country); ?>; i++) {
			elts = document.cform.elements['sel_country['+ i +']'];
			if (typeof(elts) != 'undefined') {
				if (elts.checked) {
					is_c = 1;
					break;
				}
			}
		}
		if (!is_c) {
		   	alert("Please choose countries");
		    	return false;
		}
		else return true;
		return false;
	}

	</script>

        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}
function permission_str($str, $good, $sub=0, $green_plus=true){
	if($green_plus){
		$color_red="#FF0000";
		$color_green="#339900";
	}else{
		$color_red="#339900";
		$color_green="#FF0000";
	}

	$ret_str = "<tr>\n";
	if($sub == 1)
		$ret_str .= "	<td class=\"sub_table_fonts\">".$str."</td>\n";
	else
		$ret_str .= "	<td class=\"table_fonts\">".$str."</td>\n";
	if($good==1){
		$s = "<font color=\"".$color_green."\">+</font>";
	}else{
		$s = "<font color=\"".$color_red."\">-</font>";
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
					<LEGEND  class="main_content_text">Updating Database&nbsp;</LEGEND>
					<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=2>
					<TR><TD class="main_content_text" COLSPAN=2><DIV ID=logarea STYLE="width: 100%; height: 140px; border: 1px solid #7F9DB9; padding: 3px; overflow: auto;"></DIV></TD></TR>
					</TABLE>
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


function template_last_page(){
        global $install;
        $form = "Step 3: Finish!";
        template_top($form);
        /*-------------------------------------------------------------*/
        ?>

                <table class=main width=100%>
                        <tr height=300>
                        <td align=center>
                                <table>
                                        <tr><td align=center><div style="width: 500;" class=error_area>
                                                Selected countries are successfully installed!<br><br>
                                        </div></td></tr>
                                        <tr><td align=right><input type=submit name="next" value="next>>" class=button onclick="javascript: location.href='../../index.php'"></td></tr>
                                </table>
                        </td>
                        </tr>
                </table>

        <?php
        /*-------------------------------------------------------------*/
        template_bottom();
}

?>