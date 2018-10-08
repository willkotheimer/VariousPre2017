<?php
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
	global $wpdb;

	$table_name  =  $wpdb->prefix . "ritcalendar_manager";
	$table_name3 =  $wpdb->prefix . "ritcalendar_url";

	if(isset($_POST['RIT_sub2_save']))
	{
		$RIT_Select=sanitize_text_field($_POST['RIT_Select']);
		$RIT_Date=sanitize_text_field($_POST['RIT_Date']);
		$RIT_URL=sanitize_text_field($_POST['RIT_URL']);
		$RIT_ONT=sanitize_text_field($_POST['RIT_ONT']);

		$wpdb->query($wpdb->prepare("INSERT INTO $table_name3 (id, CalendarID, URLDate, URL, ONT) VALUES (%d, %s, %s, %s, %s)", '', $RIT_Select, $RIT_Date, $RIT_URL, $RIT_ONT));
	}
	$RIT_Calendar_Themes=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id > %d",0));
	$RIT_Calendar_URLs=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id > %d",0));
?>
<style type="text/css">
	.RIT_Cal_Sub2_Fieldset
	{
		border: 1px solid #6f6e6e;
  		padding: 5px;
  		margin-top: 15px;
		width: 99%;
		float: left;
	}
	.RIT_Sub2_table
	{
		width: 100%;
		background-color: #d3d2d2;
	}
	.RIT_Sub2_table tr:nth-child(odd)
	{
		background:#f0f0f0 !important;
		color:#717171;
		text-align: center;
		font-size: 14px;
		height: 30px;	
	}
	.RIT_Sub2_table tr:nth-child(even)
	{
		background:#e4e3e3 !important;
		color:#717171;
		text-align: center;
		font-size: 14px;
		height: 30px;
	}
	.RIT_Sub2_table td:nth-child(1)
	{
		width:30%;
	}
	.RIT_Sub2_table td:nth-child(2)
	{
		width:20%;
	}	
	.RIT_Sub2_table td:nth-child(3)
	{
		width:40%;
	}
	.RIT_Sub2_table td:nth-child(4)
	{
		width:10%;
	}
	.RIT_Select
	{
		width: 80%;
	}
	.RIT_Date
	{
		border-radius:5px;
		border:none;
		text-align:center;
	}
	.RIT_URL
	{
		width:98%;
	}
	.RIT_Cal_Sub2_Main_Table
	{
		margin-top:110px !important; 
		height:40px;
		border:1px solid #6f6e6e;
		padding: 2px;
		width:99.5%;

	}
	.RIT_Cal_Sub2_first_row
	{
		background:#d3d2d2 !important;
		color:#6f6e6e;
		text-align: center;
		font-family: Gabriola;
		font-size: 20px;
	}
	.RIT_Cal_Sub2_Table
	{
		margin-top:10px !important; 
		border:1px solid #6f6e6e;
		background:#d3d2d2 !important;
		padding: 2px;
		width:99.5%;
		text-align: center;
	}
	.RIT_Cal_Sub2_Table tr:nth-child(odd)
	{
		background:#f0f0f0 !important;
		color:#717171;
		font-size: 14px;
		height: 30px;	
	}
	.RIT_Cal_Sub2_Table tr:nth-child(even)
	{
		background:#e4e3e3 !important;
		color:#717171;
		font-size: 14px;
		height: 30px;	
	}
	.RIT_Cal_Sub2_main_id,.RIT_Cal_Sub2_id
	{
		width: 5%;
	}
	.RIT_Cal_Sub2_main_cal,.RIT_Cal_Sub2_cal
	{
		width:15%;
	}
	.RIT_Cal_Sub2_main_date,.RIT_Cal_Sub2_date
	{
		width: 15%;
	}
	.RIT_Cal_Sub2_main_url,.RIT_Cal_Sub2_url
	{
		width:50%;
	}
	.RIT_Cal_Sub2_main_del,.RIT_Cal_Sub2_del
	{
		width:15%;
		color:#0073aa;
		text-decoration: underline;
		cursor: pointer;
	}
	.RIT_Cal_Sub2_del:hover
	{
		color:#f68935;
	}
	.RIT_Calendar_Event_Submenu_Diva
	{
		margin-top: 90px;
	}
</style>
<form method="POST">
	<div class="RIT_Calendar_Theme_Submenu1_Footer_Div">
		<a href="http://robo-it.esy.es" target="_blank" title="Click to Visit"><img src="<?php echo plugins_url('/Images/Robo-IT-Logo.png',__FILE__);?>" class="RIT_Logo_Orange"></a>
		<div class="RIT_Calendar_Event_Submenu_Diva">	
			<input type="submit" class="Create_New_Theme_Calendar_button" name="RIT_sub2_save" value="Save">
		</div>	
	</div>	
	<fieldset class="RIT_Cal_Sub2_Fieldset">
		<table class="RIT_Sub2_table">
			<tr>
				<td>Choose Calendar</td>
				<td>Date</td>				
				<td>URL</td>
				<td>Open in New Tab</td>
			</tr>
			<tr>
				<td>
					<select class="RIT_Select" id="RIT_Select" name="RIT_Select">
						<?php for($i=0;$i<count($RIT_Calendar_Themes);$i++){?>
							<option value="<?php echo $RIT_Calendar_Themes[$i]->id;?>"><?php echo $RIT_Calendar_Themes[$i]->RIT_CalThemeTitle;?></option>
						<?php }?>						
					</select>
				</td>
				<td>
					<input type="date" id="RIT_Date" class="RIT_Date" name="RIT_Date" value="<?php echo date("Y-m-d");?>">
				</td>
				<td>
					<input type="text" id="RIT_URL" class="RIT_URL" name="RIT_URL" required placeholder=" * Required">
				</td>
				<td>
					<select class="RIT_ONT" id="RIT_ONT" name="RIT_ONT">
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</td>
			</tr>			
		</table>		
	</fieldset>
	<table class='RIT_Cal_Sub2_Main_Table'>
		<tr class="RIT_Cal_Sub2_first_row">
			<td class='RIT_Cal_Sub2_main_id'><B><I>No</I></B></td>
			<td class='RIT_Cal_Sub2_main_cal'><B><I>Calendar Name</I></B></td>
			<td class='RIT_Cal_Sub2_main_date'><B><I>Date</I></B></td>
			<td class='RIT_Cal_Sub2_main_url'><B><I>URL</I></B></td>
			<td class='RIT_Cal_Sub2_main_del'></td>
		</tr>
	</table>
	<table class='RIT_Cal_Sub2_Table'>
		<?php for($i=0;$i<count($RIT_Calendar_URLs);$i++){
			$Cal_Name=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$RIT_Calendar_URLs[$i]->CalendarID));?>
			<tr>
				<td class='RIT_Cal_Sub2_id'><B><I><?php echo $i+1;?></I></B></td>
				<td class='RIT_Cal_Sub2_cal'><B><I><?php echo $Cal_Name[0]->RIT_CalThemeTitle;?></I></B></td>
				<td class='RIT_Cal_Sub2_date'><B><I><?php echo $RIT_Calendar_URLs[$i]->URLDate;?></I></B></td>
				<td class='RIT_Cal_Sub2_url'><B><I><?php echo $RIT_Calendar_URLs[$i]->URL;?></I></B></td>
				<td class='RIT_Cal_Sub2_del' onclick="Delete_RIT_URL(<?php echo $RIT_Calendar_URLs[$i]->id;?>)"><B><I>Delete</I></B></td>
			</tr>
		<?php }?>
	</table>
</form>