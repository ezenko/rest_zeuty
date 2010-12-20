{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<!-- RENTAL CONTENT -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="header"><b>{$lang.headers.rentals}</b></td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			{if $error}
			<tr><td class="error_div" style="padding-top: 15px; padding-left:15px;">
					*&nbsp;{$error}
			</td></tr>
			{/if}
		</table>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td style="padding-left: 15px;">{$lang.content.list_text_1}&nbsp;<b>{if $user_type eq 1}{$lang.content.user_type_1}.{else}{$lang.content.user_type_2}.{/if}</b>&nbsp;{$lang.content.list_text_2}&nbsp;<a href="./account.php?sel=edit_profile">{$lang.content.list_text_3}</a></td>
			</tr>
			<tr>
				<td  style="padding-left: 15px;">{$lang.content.list_text_4_1}<a href='./rentals.php?sel=add_rent'>{$lang.content.list_text_4_2}</a></td>
			</tr>
			{if $user_type eq 2}
			<tr>
				<td  style="padding-left: 12px;">
					<table cellpadding="3" cellspacing="0">
					{if $num_records>0}
					<tr>
						<td><a href="{$file_name}?sel=list_ads">{$lang.content.list_text_5}&nbsp;{$num_records}&nbsp;{$lang.content.list_text_6}</td>
					</tr>
					{/if}
					<tr>
						<td>{$lang.content.list_text_7}</td>
					</tr>
					<tr>
						<td>
							<form method="post" name="db_form" id="db_form" action="" enctype="multipart/form-data">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td>{$lang.content.list_text_8}:&nbsp;</td>
									<td>										
										<div class="fileinputs">
											<input type="file" name="db_file" id="db_file" class="file" onchange="document.getElementById('file_text_db_file').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_db_file').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
											<div class="fakefile">
												<table cellpadding="0" cellspacing="0">
												<tr>												
													<td>	
														<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
													</td>			
													<td style="padding-left:10px;">
														<span id='file_img_db_file'></span>
													</td>								
													<td style="padding-left:4px;">
														<span id='file_text_db_file'></span>	
													</td>
												</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
								<tr>										
									<td colspan="2" style="padding-top:5px;"><input class="btn_small" type="button" value="{$lang.buttons.load}" onclick="javascript: {literal} UploadBase( document.getElementById('db_file'), document.getElementById('loading_div'), {/literal}'{$lang.content.list_text_9}'{literal} ); {/literal}"></td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2"><div id="loading_div">&nbsp;</div></td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
							</table>
							</form>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			{/if}
			<tr>
				<td><hr></td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="padding-left: 15px;"><a href="{$add_rent_link}"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
				<td><a href="{$add_rent_link}">{$lang.content.add_rent_link_text}</a></td>
			</tr>
		</table>
		<!--END OF RENTAL CONTENT -->
	</td>
</tr>
</table>
{literal}
<script type="text/javascript">
var req = null;

function InitXMLHttpRequest() {
	// Make a new XMLHttp object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
}

function UploadBase(file, destination, text) {
	if (file.value!=''){
		InitXMLHttpRequest();
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else  {
					destination.innerHTML = "<div style=\" position: absolute; width: 400px; height: 70px; left: 40%; background-color: #ffffff; border : 1px solid #000000;\"><div><font class=\"error_div\">"+text+"</font></div><div align=\"center\"><img align=\"center\" src=\"{/literal}{$site_root}{$template_root}{$template_images_root}/indicator.gif{literal}\" alt=\"\"></div></div>";
				}
			}
			req.open("GET", "location.php?sec=hp&sel=upload_base&file=" + file, true);
			req.send(null);
		} else {
			destination.innerHTML = 'Browser unable to create XMLHttp Object';
		}
	} else {
		destination.innerHTML = '<font class=\"error\">{/literal}{$lang.content.no_file_selected}{literal}</font>';
	}
	return;
}

function ChangeIcon(type){	
    switch (type.toLowerCase())
    {
        case 'bmp':         
        case 'jpg':  
        case 'png':
        case 'gif':
        case 'tiff':
        type = type.toLowerCase();
        break;
        case 'jpeg':
        	type = 'jpg';
        	break;
        case 'tif':
        	type = 'tiff';
        	break;
        case 'mp3':
        case 'wav':
        case 'ogg':
        	type = 'mp3';
         break;
        case 'avi':
        case 'wmv':
        case 'flv':
        	type = 'avi';
        	 break;
        
        default: type = 'other'; break;
    };   
    return "<img src='{/literal}{$site_root}{$template_root}/images/file_types/{literal}" + type +".png'>";           
}
</script>
{/literal}
{include file="$gentemplates/site_footer.tpl"}