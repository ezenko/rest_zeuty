<html>
<head>
<title> PG RealEstate / Install folder Error</title>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
{literal}
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
{/literal}
<body>
<table width=100% cellspacing=0 cellpadding=5>
<tr>
        <td align=center>
                <table class=hint width=100%>
                        <tr><td>Install folder Error!</td></tr>
                </table>
        </td>
</tr>
<tr>
        <td>

                <table class=main width=100%>
                        <tr height=200>
                        <td align=center>
                                <table>
                                        <tr><td align=center><div style="width: 500;" class=error_area>
						Please, remove or rename the installation folder!<br>It's name is "{$folder}". The new name of the folder should exclude "install" word.<br><br>
                                        </div></td></tr>
                                </table>
                        </td>
                        </tr>
                </table>

        </td>
</tr>
</table>
</body>
</html>