{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter" valign="top">&nbsp;</td>
	<td class="main" valign="top">
	
			
		<TABLE cellpadding="0" cellspacing="0" border="0" width="100%">		
		<tr valign="top">
			<td class="header"><b>{$lang.headers.rental_ad_edit}&nbsp;|&nbsp;			
			{$lang.content.have_room_big}
			</b></td>
		</tr>
		<tr valign="top">
			<td class="subheader"><b>
			{if $par == "add_event"}<b>{$lang.content.adding_event}</b>
			{elseif $par == "edited_event"}<b>{$lang.content.editing_event}</b>
			{/if}</b>
			</td>
		</tr>		
		<tr><td>&nbsp;</td></tr>
		</TABLE>
		{if $error}
		<TABLE>
		<tr>
			<td style="padding: 0px 0px 10px 5px;">
			<div class="error">*&nbsp;{$error}</div>
			</td>			
		</tr>
		</TABLE>
		{/if}
		<TABLE width="100%" cellpadding="0" cellspacing="2" border="0">
		<tr>
				<td colspan="3" align="right" style="padding-left:3px;padding-bottom: 5px;">
					<div style="background-color:gray; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.na_days}
					&nbsp;<div style="background-color:#FFD8D8; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.reserve_days}
					&nbsp;<div style="background-color:#CCFF99; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.empty_days}</td>
		</tr>	
		</TABLE>
		<TABLE width="100%" cellpadding="2" cellspacing="0" border="0" class="calendar_month_head">
		
		<TR><!-- Calendar menu -->
			<TD align="left" valign="middle" style="padding-left: 15px;">
			{strip}
				<A href="{$file_name}?sel=calendar&par={$par}&id_ad={$id_ad}&start_month={$date.display.prev_mon}&start_year={$date.display.prev_year}" class="calendar_back_next">
					&larr;&nbsp;{$lang.content.prev_month}
				</A>				
			{/strip}
			</TD>	
			{foreach from=$date.display.month item=item key=key}
				<TD align="center" valign="middle" width="">								
				<b class="subheader">{$date.months[$item]}&nbsp;{$date.display.year[$key]}</b>
				</TD>			
			{/foreach}
			<TD align="right" valign="middle" style="padding-right: 15px;">
			{strip}
				<A href="{$file_name}?sel=calendar&par={$par}&id_ad={$id_ad}&start_month={$date.display.next_mon}&start_year={$date.display.next_year}" class="calendar_back_next">
					&rarr;&nbsp;{$lang.content.next_month}
				</A>
				&nbsp;&nbsp;				
			{/strip}
			</TD>
			
		</TR><!-- /Calendar menu -->
		
		<TR><!-- Calendar header -->
		
			<td>
			</td>
			{foreach from=$date.display.calendar.month item=item key=index_month}
			<td width="" valign="top" style="padding-top:5px">
				<table align="center" style="background-color: #cccccc" cellpadding="2" cellspacing="1">
				<tr>
				{foreach from=$date.day_of_week item=day_of_week key=key}			
					<TD width="12%" align="center" style="background-color:white;">
						{assign var=cur value=$key+1 }																		
						{$date.day_of_week[$cur]}
						{if $cur == 7}
						{$date.day_of_week[0]}
						{/if}						
					</TD>				
				{/foreach}				
				
				</tr>
				{foreach from=$item item=week}
				<tr>		
					{foreach from=$date.day_of_week item=day_of_week key=key}				
						{assign var=cur value=$key+1 }	
						{if $cur == 7}
							{assign var=cur value=0}	
						{/if}
						<TD width="12%" align="center" style="
							{if $week[$cur].current_day == 'true'}border:1px solid red;padding:1px;{/if}
							{if $week[$cur].reserved_day == 'true'}background-color:#FFD8D8
							{elseif $week[$cur].reserved_day == 'not_available'}background-color:gray
							{else}background-color:#CCFF99{/if};
							{if $week[$cur].reserved_day == 'half_tf'} cursor: pointer; background-image: url('{$half_tf_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
							{elseif $week[$cur].reserved_day == 'half_ft'} cursor: pointer; background-image: url('{$half_ft_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
							{elseif $week[$cur].reserved_day == 'half_tft'} cursor: pointer; background-image: url('{$half_tft_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
							{else}"{/if}
							>							
							{if $week[$cur].wday == $cur && $week[$cur].mday>0}{$week[$cur].mday}{/if}																				
						</TD>				
					{/foreach}							
				</tr>				
				{/foreach}				
				</table>
				<table align="center">
				<tr>					
					<td id="choose_date" align="center"><div id='id_text_on_date{$index_month}' style="display:none;"></div></td>
				</tr>
				</table>
			</td>
			
			{/foreach}				
			<td>
			</td>
		
		</TR><!-- /Calendar header -->
		</TABLE>
	{if $par == "add_event" || $par == "edited_event"}
		
		<form name="add_event_form" action="{$file_name}" method="post">
		<input type=hidden name="sel" value="{$par}">
		<input type=hidden name="id_ad" value="{$id_ad}">		
		{if $par == "edited_event"}<input type=hidden name="id_event" value="{$id_event}">{/if}			
							
		<table align="left" style="padding-left:10px;">
			
			<tr>
			<td colspan="4" valign="top" style="padding-bottom:10px;">
				{if $par == "add_event"}<b>{$lang.content.add_time_period}:</b>
				{elseif $par == "edited_event"}<b>{$lang.content.edit_time_period}:</b>
				{/if}							
			</td>
			
			</tr>
			<tr>
				<td>
				{$lang.content.start_date}:
				</td>					
				<td>
					<select id="id_day_select_from" name="id_day_select_from" onchange="">
						{foreach from=$date.display.calendar.month.cur item=week}	
							{foreach from=$date.day_of_week item=day_of_week key=key}				
								{assign var=cur value=$key+1 }	
								{if $cur == 7}
									{assign var=cur value=0}	
								{/if}
								{if $week[$cur].mday>0}															
								<option value={$week[$cur].mday} style="
background-color:{if $week[$cur].reserved_day == 'true'}#FFD8D8{else}#CCFF99{/if};
"{if $date.now_date.mday == $week[$cur].mday}selected{/if}>{$week[$cur].mday}</option>
								{/if}
							{/foreach}	
						{/foreach}
						
					</select>
					<select id="id_month_select_from" name="id_month_select_from" onchange="GetFreeDays('id_day_select_from', '{$file_name}?sel=get_free_days&id_ad={$id_ad}&month='+this.value+'&year='+document.getElementById('id_year_select_from').value, document.getElementById('id_day_select_from').value, 'id_month_select_from', 'id_year_select_from')">
						{foreach from=$date.months item=item key=key}
						<option value={$key} {if $date.now_date.mon == $key}selected{/if}>{$item}</option>
						{/foreach}						
					</select>
					<select id="id_year_select_from" name="id_year_select_from" onchange="GetFreeDays('id_day_select_from', '{$file_name}?sel=get_free_days&id_ad={$id_ad}&month='+document.getElementById('id_month_select_from').value+'&year='+this.value, document.getElementById('id_day_select_from').value, 'id_month_select_from', 'id_year_select_from')">
						{foreach from=$date.display.calendar.year item=item key=key}
						<option value={$item} {if $date.now_date.year == $item}selected{/if}>{$item}</option>
						{/foreach}						
					</select>
				</td>	
				<td style="padding-left:10px;">
				{$lang.content.time}:
				</td>	
				<td>
				<select id="id_hour_select_from" name="id_hour_select_from">
						{foreach from=$date.hours item=item key=key}
						<option value={$key} {if $date.now_date.hours == ($item) && $par == 'edited_event'}selected{/if}{if $date.now_date.hours == ($item-1) && $par == 'add_event'}selected{/if}>{$item}</option>						
						{/foreach}						
				</select>				
				:
				<select id="id_minute_select_from" name="id_minute_select_from">
						{foreach from=$date.minutes item=item key=key}
						<option value={$key}>{$item}</option>
						{/foreach}						
				</select>				
				</td>
				
			</tr>
			<tr>
				<td>
				{$lang.content.end_date}:
				</td>					
				<td>
					<select id="id_day_select_to" name="id_day_select_to" onchange="">
						{foreach from=$date.display.calendar.month.cur item=week}	
							{foreach from=$date.day_of_week item=day_of_week key=key}				
								{assign var=cur value=$key+1 }	
								{if $cur == 7}
									{assign var=cur value=0}	
								{/if}
								{if $week[$cur].mday>0}															
								<option value={$week[$cur].mday} style="background-color:{if $week[$cur].reserved_day == 'true'}#FFD8D8{else}#CCFF99{/if};"{if $date.end_date.mday == $week[$cur].mday}selected{/if}>{$week[$cur].mday}</option>
								{/if}
							{/foreach}	
						{/foreach}
						
					</select>
					<select id="id_month_select_to" name="id_month_select_to" onchange="GetFreeDays('id_day_select_to', '{$file_name}?sel=get_free_days&id_ad={$id_ad}&month='+this.value+'&year='+document.getElementById('id_year_select_to').value, document.getElementById('id_day_select_to').value, 'id_month_select_to', 'id_year_select_to')">
						{foreach from=$date.months item=item key=key}
						<option value={$key} {if $date.end_date.mon == $key}selected{/if}>{$item}</option>
						{/foreach}						
					</select>
					<select id="id_year_select_to" name="id_year_select_to" onchange="GetFreeDays('id_day_select_to', '{$file_name}?sel=get_free_days&id_ad={$id_ad}&month='+document.getElementById('id_month_select_to').value+'&year='+this.value, document.getElementById('id_day_select_to').value, 'id_month_select_to', 'id_year_select_to')">
						{foreach from=$date.display.calendar.year item=item key=key}
						<option value={$item} {if $date.end_date.year == $item}selected{/if}>{$item}</option>
						{/foreach}						
					</select>
				</td>	
				<td style="padding-left:10px;">
				{$lang.content.time}:
				</td>	
				<td>
				<select id="id_hour_select_to" name="id_hour_select_to">
						{foreach from=$date.hours item=item key=key}		
						<option value={$key} {if $date.end_date.hours == ($item) && $par == 'edited_event'}selected{/if}{if $date.end_date.hours == ($item-1) && $par == 'add_event'}selected{/if}>{$item}</option>						
						{/foreach}						
				</select>				
				:
				<select id="id_minute_select_to" name="id_minute_select_to">
						{foreach from=$date.minutes item=item key=key}
						<option value={$key}>{$item}</option>
						{/foreach}						
				</select>				
				</td>	
				
			</tr>
			<tr>
				<td colspan="4" align="left" style="padding-top:10px;">
				{if $par == "add_event"}
				<input type="button" value="{$lang.buttons.mark}" class="btn_small" onclick="javascript: document.add_event_form.submit();">
				{elseif $par == "edited_event"}
				<input type="button" value="{$lang.buttons.save}" class="btn_small" onclick="javascript: document.add_event_form.submit();">
				{/if}				
				</td>				
			</tr>	
			<tr>
				<td colspan="4" align="left" style="padding-top:10px;">
					<input type="button" value="{$lang.content.back_to_edit}" class="btn_small" onclick="javascript: document.location.href='{$file_name}?sel=my_ad&id_ad={$id_ad}';">
				</td>
			</tr>		
		</table>		
		</form>

	{/if}		
	</td>	
</tr>
</table>

{include file="$gentemplates/site_footer.tpl"}
{literal}
<script>


GetFreeDays('id_day_select_from','{/literal}{$file_name}{literal}?sel=get_free_days&id_ad={/literal}{$id_ad}{literal}&month='+document.getElementById('id_month_select_from').value+'&year='+document.getElementById('id_year_select_from').value,'{/literal}{$date.now_date.mday}{literal}', 'id_month_select_from', 'id_year_select_from');

GetFreeDays('id_day_select_to','{/literal}{$file_name}{literal}?sel=get_free_days&id_ad={/literal}{$id_ad}{literal}&month='+document.getElementById('id_month_select_to').value+'&year='+document.getElementById('id_year_select_to').value,'{/literal}{$date.end_date.mday}{literal}', 'id_month_select_to', 'id_year_select_to');


function get_http(){
    var xmlhttp;	
	try{
		// Opera 8.0+, Firefox, Safari
		xmlhttp = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    return xmlhttp;
}

function GetFreeDays(id_day_select, url, now_day, id_month_select, id_year_select) {                
        this.http = get_http();                           
        if (this.http) {
            var http = this.http;       
            this.http.open("GET", url, true);            
            
            this.http.onreadystatechange = function() {	
                if (http.readyState == 4) {
                    fill(id_day_select,http.responseText, now_day, id_month_select, id_year_select);                    
                  }else{                   
                  }
            }
            
            this.http.send(null);
        }
        if(!this.http){
              alert('Error XMLHTTP creating!')
        }
}

function GetHourByDate(day, mon, year, id_text){
	this.http = get_http();                           
	url='{/literal}{$file_name}?sel=get_hour_by_date&id_ad={$id_ad}&day={literal}'+day+'{/literal}&month={literal}'+mon+'{/literal}&year={literal}'+year;
	if (mon.length < 2){
		date = year+'-0'+mon;		
	}else{
		date = year+'-'+mon;
	}
	if (day.length < 2){
		date = date+'-0'+day;		
	}else{
		date = date+'-'+day;
	}
    if (this.http) {
        var http = this.http;       
        this.http.open("GET", url, true);                    
        this.http.onreadystatechange = function() {	
            if (http.readyState == 4) {            	
                show_text(id_text, date, http.responseText);                    
              }else{                   
              }
        }
        
        this.http.send(null);
    }
    if(!this.http){
          alert('Error XMLHTTP creating!')
    }	
}

function fill (select_id, data, now_day, select_id_month, select_id_year){    
    var select = document.getElementById(select_id);
    
    select.options.length = 0;    
    if(data.length == 0) return;    
    
    var arr = data.split('|');
    
    for(var i in arr){        
        val = arr[i].split('_');  
        
        if (now_day == val[0]){        	
       	select.options[select.options.length]= new Option(val[0],val[0], true, true);
        }else{
        	select.options[select.options.length]= new Option(val[0],val[0], false);
        }
        
        var option  = document.getElementById(select_id).options[i];
        if (val[1] == "r"){
        	option.style.backgroundColor='#FFD8D8';
        	
        }else if (val[1] == "na"){
        	option.style.backgroundColor='gray';        	
        }else {
        	option.style.backgroundColor='#CCFF99';
        }
                
    }
    {/literal}{if $date.now_date.hours == 23 && $par == "add_event"}     
    {literal}
    	if (document.getElementById(select_id).selectedIndex+1 != document.getElementById(select_id).length){
    		document.getElementById(select_id).selectedIndex = document.getElementById(select_id).selectedIndex + 1;    	    	
    	}else{
    		document.getElementById(select_id).selectedIndex = 0;  
    		if (document.getElementById(select_id_month).selectedIndex+1 != document.getElementById(select_id_month).length){
    			document.getElementById(select_id_month).selectedIndex = document.getElementById(select_id_month).selectedIndex + 1;
    		}else{
    			document.getElementById(select_id_month).selectedIndex = 0;  
    			document.getElementById(select_id_year).selectedIndex = document.getElementById(select_id_year).selectedIndex + 1;
    		}
    		
    	}
    {/literal}	
    {/if}{literal};
}

function show_text(id_text,date,data){
	document.getElementById(id_text).innerHTML = "<b>"+date+"</b>";	
	document.getElementById(id_text).style.display = 'inline';	
	var arr = data.split('|');
	for(var i in arr){        
       document.getElementById(id_text).innerHTML = document.getElementById(id_text).innerHTML+"<br>"+arr[i];	                
    }
}

</script>
{/literal}