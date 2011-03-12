{include file="$gentemplates/site_top.tpl"}
    <div id="middle-container">
    {literal}
    <style>
    #middle-container .entertaiment_tbl td { border: none;}
    </style>
    {/literal}
<table cellpadding="0" cellspacing="0" border="0" class="entertaiment_tbl">
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
		{if $section.image}
      <tr><td style="text-align: center;"><img src="/uploades/entertaiments/{$section.image}" style="max-width: 350px;"/></td></tr>
    {/if}
    <tr><td><hr></td></tr>
		<tr>
			<td class="page_content" style="text-align: left;">{$section.content}</td>
		</tr>
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