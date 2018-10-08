<?php
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
	global $wpdb;
	$table_name  =  $wpdb->prefix . "ritcalendar_manager";

	$RIT_Calendar_Themes=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id > %d",0));
?>
<form method="POST">
	<div id="RIT_Calendar_Main_Div"> 
		<div class="RIT_Calendar_Theme_Submenu1_Footer_Div">
			<a href="http://robo-it.esy.es" target="_blank" title="Click to Visit"><img src="<?php echo plugins_url('/Images/Robo-IT-Logo.png',__FILE__);?>" class="RIT_Logo_Orange"></a>
			<a href="http://robo-it.esy.es" target="_blank" title="Click to Buy"><div class="Full_Version"><i class="roboitcal roboiticons-style roboiticons-cart-arrow-down"></i>Get The Full Version</div></a>
			<div class="Full_Version_Span">
				This is the free version of the plugin. Click "GET THE FULL VERSION" for more advanced options.<br> We appreciate every customer.
			</div>
			<div class="RIT_Calendar_Event_Submenu_Div">	
				<span class="Theme_Title_Span">Calendar Title:</span> 
				<input type="text"   class="RIT_Calendar_search_theme_field" id="RIT_Calendar_search_theme_field" onclick="RIT_Calendar_Search_Theme_Clicked()" placeholder="Search">
				<input type="button" class="RIT_Calendar_Reset_Theme_button" value="Reset" onclick="RIT_Calendar_Reset_Theme_Button_Clicked()">
				<span class="searched_theme_calendar_does_not_exist" id="searched_theme_calendar_does_not_exist"></span>
			</div>	
			<div class="RIT_Calendar_Event_Submenu_Div1">
				<input type="button" class="RIT_Cal_Them_Sub_Tab_Buttons" value="General Parameters" onclick="RIT_Calendar_Theme_Sub_Buttons(1)">
				<input type="button" class="RIT_Cal_Them_Sub_Tab_Buttons" value="Header Parameters" onclick="RIT_Calendar_Theme_Sub_Buttons(2)">
				<input type="button" class="RIT_Cal_Them_Sub_Tab_Buttons" value="Body Parameters" onclick="RIT_Calendar_Theme_Sub_Buttons(3)">

				<input type="button" class="Create_New_Theme_Calendar_button" value="Back" onclick="RIT_Cal_Theme_Sub_BacK_Button()">
				<input type="text" style="display: none;" class="RIT_Cal_Hid_ID">
			</div>	
		</div>	
		<table class='RIT_Calendar_Theme_Sub_Main_Table'>
			<tr class="RIT_Calendar_Theme_Sub_first_row">
				<td class='RIT_Calendar_Theme_Sub_main_id_item'><B><I>No</I></B></td>
				<td class='RIT_Calendar_Theme_Sub_main_name_item'><B><I>Calendar Name</I></B></td>				
				<td class='RIT_Calendar_Theme_Sub_main_actions_item'><B><I>Actions</I></B></td>
				<td class='RIT_Calendar_Theme_Sub_main_shortcode_item'><B><I>Shortcode</I></B></td>
			</tr>
		</table>
		<table class='RIT_Calendar_Theme_Sub_Table'>
			<?php for($i=0;$i<count($RIT_Calendar_Themes);$i++){?>
				<tr>
					<td class='RIT_Calendar_Theme_Sub_id_item'><B><I><?php echo $i+1 ;?></I></B></td>
					<td class='RIT_Calendar_Theme_Sub_name_item'><B><I><?php echo $RIT_Calendar_Themes[$i]->RIT_CalThemeTitle;?></I></B></td>
					<td class='RIT_Calendar_Theme_Sub_edit_item' onclick="Edit_RIT_Calendar_Theme(<?php echo $i+1;?>)"><B><I>Edit</I></B></td>
					<td><B><I>Delete</I></B></td>
					<td class='RIT_Calendar_Theme_Sub_shortcode_item'><B><I><?php echo '[Robo_IT_Calendar id="'.$RIT_Calendar_Themes[$i]->id.'"]';?></I></B></td>
				</tr>
			<?php } ?>
		</table>
		<table class='RIT_Calendar_Theme_Sub_Table1'></table>
	</div>	

	<img src="<?php echo plugins_url('/Images/General.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_1">
	<img src="<?php echo plugins_url('/Images/Header.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_2">
	<img src="<?php echo plugins_url('/Images/Body.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_3">

	<img src="<?php echo plugins_url('/Images/Theme 2 general.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_4">
	<img src="<?php echo plugins_url('/Images/Theme 2 header.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_5">
	<img src="<?php echo plugins_url('/Images/Theme 2 body.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_6">

	<img src="<?php echo plugins_url('/Images/Theme 3 general.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_7">
	<img src="<?php echo plugins_url('/Images/Theme 3 header.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_8">
	<img src="<?php echo plugins_url('/Images/Theme 3 body.png',__FILE__);?>" class="RIT_Cal_AI" id="RIT_Cal_AI_9">
</form>