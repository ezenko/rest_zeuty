{include file="$gentemplates/site_top.tpl"}
    <div id="middle-container">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <!--
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	-->
	<td class="main" valign="top">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		{if $banner.center}
		<tr>
			<td>
			 <!-- banner center -->
		  	
				<div align="left">{$banner.center}</div>
			
			 <!-- /banner center -->
			</td>
		</tr>
		{/if}
		<tr><td class="header"><h2>{$section.caption}</h2></td></tr>
		<tr><td><hr></td></tr>
		<tr>
			<td class="page_content">{$section.content}</td>
		</tr>
		{if $subsections}
		{foreach from=$subsections item=subsect}
		<tr>
			<td>
			<ul class="info_subsection">
			
				<li><a href="#" onclick="ShowHideDiv('subsection_id_{$subsect.id}');">{$subsect.caption}</a></li>
			
			</ul>
			</td>
		</tr>
		{/foreach}
		{/if}
		{if $subsections}
		<tr>
			<td style="padding-left: 15px; padding-top: 10px;">
				{foreach from=$subsections item=subsect}
				<div id="subsection_id_{$subsect.id}" style="display: {if $subsection_id && $subsection_id==$subsect.id}block{else}none{/if};">
				<div align="right" style="padding-bottom: 10px;"><a href="#" onclick="ShowHideDiv('subsection_id_{$subsect.id}');">{$lang.default_select.hide}</a></div>
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td class="subheader"><b>{$subsect.caption}</b></td>
						</tr>
						<tr>
							<td style="padding-top: 10px;">{$subsect.content}</td>
						</tr>
					</table>
				</div>
				{/foreach}
			</td>
		</tr>
		{/if}
		</table>
	</td>
</tr>
</table>
</div>
{literal}
<script language="javascript">
	function ShowHideDiv(elem_id) {
		var subsections_array = new Array({/literal}{foreach from=$subsections item=subsection key=key name=subsection}"{$subsection.id}"{if !$smarty.foreach.subsection.last},{/if}{/foreach}{literal});

		var subsections_size = subsections_array.length;

		for (i=0; i<subsections_size; i++) {
			var name = "subsection_id_" + subsections_array[i];
			var elem = document.getElementById(name);
			if (name == elem_id) {
				if (elem.style.display == 'block') {
					elem.style.display = 'none';
				} else {
					elem.style.display = 'block';
				}
			} else {
				elem.style.display = 'none';
			}
		}
	}
</script>
{/literal}
{include file="$gentemplates/site_footer.tpl"}