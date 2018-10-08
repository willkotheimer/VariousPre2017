<?php
	class  RIT_Calendar extends WP_Widget
	{
		function __construct()
 	  	{
 			$params=array('name'=>'Robo IT Calendar','description'=>'This is the widget of Robo IT Calendar plugin');
			parent::__construct('Robo_IT_Calendar','',$params);
 	  	}
 	  	function form($instance)
 		{
 			$defaults = array('RIT_CalThemeTitle'=>'');
		    $instance = wp_parse_args((array)$instance, $defaults);

		   	$RITCalendar = $instance['RIT_CalThemeTitle'];
		   	?>
		   	<div>			  
			   	<p>
			   		Calendar Title:
			   		<select name="<?php echo $this->get_field_name('RIT_CalThemeTitle'); ?>" class="widefat">
				   		<?php
				   			global $wpdb;
							$table_name = $wpdb->prefix . "ritcalendar_manager";
							$RITCalendar_Title=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id > %d", 0));
				   			
				   			foreach ($RITCalendar_Title as $RITTitle1)
				   			{
				   				?> <option value="<?php echo $RITTitle1->id; ?>"> <?php echo $RITTitle1->RIT_CalThemeTitle; ?> </option> <?php 
				   			}
				   		?>
			   		</select>
			   	</p>
		   	</div>
		   	<?php
 		}
 		function widget($args,$instance)
 		{
 			extract($args);
 		 	$RITCalendar = empty($instance['RIT_CalThemeTitle']) ? '' : $instance['RIT_CalThemeTitle'];
 		 	global $wpdb;
			$table_name  =  $wpdb->prefix . "ritcalendar_manager";
			$RITTheme_Params=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$RITCalendar));

			if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==1)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-arrow-circle-o-left';
 		 		$RIT_Calendar_Right_Icon='roboiticons-arrow-circle-o-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==2)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-arrow-left';
				$RIT_Calendar_Right_Icon='roboiticons-arrow-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==3)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-chevron-left';
				$RIT_Calendar_Right_Icon='roboiticons-chevron-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==4)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-toggle-left';
				$RIT_Calendar_Right_Icon='roboiticons-toggle-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==5)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-chevron-circle-left';
				$RIT_Calendar_Right_Icon='roboiticons-chevron-circle-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==6)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-arrow-circle-left';
				$RIT_Calendar_Right_Icon='roboiticons-arrow-circle-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==7)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-angle-double-left';
				$RIT_Calendar_Right_Icon='roboiticons-angle-double-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==8)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-caret-left';
				$RIT_Calendar_Right_Icon='roboiticons-caret-right';
 		 	}
 		 	else if($RITTheme_Params[0]->RIT_Calendar_Popup_Icons==9)
 		 	{
 		 		$RIT_Calendar_Left_Icon='roboiticons-mail-reply';
				$RIT_Calendar_Right_Icon='roboiticons-mail-forward';
 		 	}
 		 	echo $before_widget;
 		 	?>
 		 	<style type="text/css">
 		 		#ritcalendar11_<?php echo $RITCalendar;?>
 		 		{
 		 			width:<?php echo $RITTheme_Params[0]->RIT_CalWidth;?>;
 		 			height:<?php echo $RITTheme_Params[0]->RIT_CalHeight;?>;
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalBgColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalDaysFontSize;?>;
 		 			border: 1px solid <?php echo $RITTheme_Params[0]->RIT_CalBgColor;?>;
 		 			border-radius:<?php if($RITTheme_Params[0]->RIT_Calendar_Show_Title=='Yes'){ echo $RITTheme_Params[0]->RIT_CalBorderRadius;}?> !important;
 		 			border-collapse:initial;
 		 		}
 		 		#ritcalendar11_<?php echo $RITCalendar;?> tr td
 		 		{
 		 			border-color:<?php echo $RITTheme_Params[0]->RIT_CalGridColor;?> ;
 		 			border-top: 1px solid <?php echo $RITTheme_Params[0]->RIT_CalGridColor;?>;
 		 		}
 		 		#rittitle_name_<?php echo $RITCalendar;?>
 		 		{
 		 			border-top-left-radius:<?php echo $RITTheme_Params[0]->RIT_CalBorderRadius;?> !important;
 		 			border-top-right-radius:<?php echo $RITTheme_Params[0]->RIT_CalBorderRadius;?> !important;
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalTitleBgColor;?>;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalTitleColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalTitleFontSize;?>;
 		 			font-family:<?php echo $RITTheme_Params[0]->RIT_CalTitleFontFamily;?>;
 		 			<?php if($RITTheme_Params[0]->RIT_Calendar_Show_Title=='Yes'){?>border-top:0px !important;<?php }?>
 		 		}
 		 		.roboitescal_<?php echo $RITCalendar;?>
 		 		{
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalIconColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalIconFontSize;?>;
 		 		}
 		 		#RIT_Cal_M_<?php echo $RITCalendar;?>
 		 		{
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalMonthBgColor;?>;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalMonthColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalMonthFontSize;?>;
 		 			font-family:<?php echo $RITTheme_Params[0]->RIT_CalMonthFontFamily;?>;
 		 		}
 		 		.RIT_Cal_WDN_<?php echo $RITCalendar;?>
 		 		{
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalWDayBgColor;?>;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalWDayColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalWDayFontSize;?>;
 		 			font-family:<?php echo $RITTheme_Params[0]->RIT_CalWdayFontFamily;?>;
 		 			border-radius:<?php echo $RITTheme_Params[0]->RIT_CalWdayBRad;?>;
 		 		}
 		 		.RIT_Cal_Sat_<?php echo $RITCalendar;?>
 		 		{
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalSatBgColor;?>;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalSatColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalSatFontSize;?>;
 		 			border-radius:<?php echo $RITTheme_Params[0]->RIT_CalSatBRad;?>;
 		 			font-family:<?php echo $RITTheme_Params[0]->RIT_CalSatFontFamily;?>;
 		 		}
 		 		.RIT_Cal_Sun_<?php echo $RITCalendar;?>
 		 		{
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalSunBgColor;?>;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalSunColor;?>;
 		 			font-size:<?php echo $RITTheme_Params[0]->RIT_CalSunFontSize;?>;
 		 			border-radius:<?php echo $RITTheme_Params[0]->RIT_CalSunBRad;?>;
 		 			font-family:<?php echo $RITTheme_Params[0]->RIT_CalSunFontFamily;?>;
 		 		}
 		 		.rittoday1_<?php echo $RITCalendar;?>
 		 		{
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalCurrentBgColor;?> !important;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalCurrentColor;?> !important;
 		 			border-radius:<?php echo $RITTheme_Params[0]->RIT_CalCurrentBorderRadius;?> !important;
 		 			border-color:<?php echo $RITTheme_Params[0]->RIT_CalCurrentBorderColor;?> !important;		
 		 		}
 		 		.ritwithout1_<?php echo $RITCalendar;?>
 		 		{
 		 			background-color:<?php echo $RITTheme_Params[0]->RIT_CalWEventBgColor;?>;
 		 			color:<?php echo $RITTheme_Params[0]->RIT_CalWEventColor;?>;
					border-radius:<?php echo $RITTheme_Params[0]->RIT_CalWEventBorderRadius;?>;
				}
				.ritsaturday11_<?php echo $RITCalendar;?>
				{
					background-color:<?php echo $RITTheme_Params[0]->RIT_CalDSatBgColor;?> !important;
					color:<?php echo $RITTheme_Params[0]->RIT_CalDSatColor;?> !important;
					border-radius:<?php echo $RITTheme_Params[0]->RIT_CalDSatBorderRadius;?> !important;		
				}
				.ritsunday11_<?php echo $RITCalendar;?>
				{
					background-color:<?php echo $RITTheme_Params[0]->RIT_CalDSunBgColor;?> !important;
					color:<?php echo $RITTheme_Params[0]->RIT_CalDSunColor;?> !important;
					border-radius:<?php echo $RITTheme_Params[0]->RIT_CalDSunBorderRadius;?> !important;
				}
				.ritwithout1_<?php echo $RITCalendar;?>:hover
				{
					background-color:<?php echo $RITTheme_Params[0]->RIT_CalHoverBgColor;?> !important;
					color:<?php echo $RITTheme_Params[0]->RIT_CalHoverColor;?> !important;
				}
				.robowithurl_<?php echo $RITCalendar;?>
				{
					background-color: <?php echo $RITTheme_Params[0]->RIT_CalURLBgColor;?> !important;
				}
				.robowithurla_<?php echo $RITCalendar;?>
				{
					color: <?php echo $RITTheme_Params[0]->RIT_CalURLColor;?> !important;
				}
				.ritempty1_<?php echo $RITCalendar;?>
				{
					background-color:<?php echo $RITTheme_Params[0]->RIT_CalBgColor;?>;
				}
				#RIT_Cal_P_<?php echo $RITCalendar;?>,#RIT_Cal_N_<?php echo $RITCalendar;?>
				{
					background-color:<?php echo $RITTheme_Params[0]->RIT_CalBgColor;?>;
					cursor: pointer;
				}
				#RIT_Cal_P_<?php echo $RITCalendar;?>,#RIT_Cal_N_<?php echo $RITCalendar;?>,#RIT_Cal_M_<?php echo $RITCalendar;?>
				{
					<?php if($RITTheme_Params[0]->RIT_Calendar_Show_Title=='No'){?>border-top:0px !important;<?php }?>
				}
 		 	</style>
 		 	<div class="RIT_Cal_Div" id="RIT_Cal_Div_<?php echo $RITCalendar;?>" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: <?php echo $RITTheme_Params[0]->RIT_CalWidth;?>; height: <?php echo $RITTheme_Params[0]->RIT_CalHeight;?>; ">
 		 		<input type="text" style="display: none;" class="RIT_CAL_Hidden" id="RIT_CAL_Hidden_<?php echo $RITCalendar;?>" value="<?php echo $RITTheme_Params[0]->RIT_CalWidth;?>">
 		 		<input type="text" style="display: none;" id="RIT_CAL_sel_<?php echo $RITCalendar;?>" value="<?php echo $RITCalendar;?>">
 		 		<input type="text" style="display: none;" id="RIT_CAL_FD_<?php echo $RITCalendar;?>" value="<?php echo $RITTheme_Params[0]->RIT_CalFsD;?>">
 		 		<input type="text" style="display: none;" id="RIT_CAL_hidden_text_<?php echo $RITCalendar;?>">
		        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: <?php echo $RITTheme_Params[0]->RIT_CalWidth;?>; height: <?php echo $RITTheme_Params[0]->RIT_CalHeight;?>; overflow: hidden;">
		            <div data-p="112.50" style="display: none;">
		               	<table data-u="image" class="RIT_Cal" id="ritcalendar11_<?php echo $RITCalendar;?>">
					  		<thead >
					  			<?php if($RITTheme_Params[0]->RIT_Calendar_Show_Title=='Yes'){?>
				 		 			<tr>
				 		 				<td id='rittitle_name_<?php echo $RITCalendar;?>' class="rittitle_name1" colspan="7"><?php echo $RITTheme_Params[0]->RIT_CalThemeTitle;?></td>
				 		 			</tr>
			 		 			<?php }?>
					    		<tr>
					    			<td class='ritprev11' id="RIT_Cal_P_<?php echo $RITCalendar;?>"><i class="roboitescal_<?php echo $RITCalendar;?> roboitescal roboiticons-style <?php echo $RIT_Calendar_Left_Icon;?>"></i></td>
					    			<td class='ritmonth11' id="RIT_Cal_M_<?php echo $RITCalendar;?>" colspan="5"></td>
					    			<td class='ritnext11' id="RIT_Cal_N_<?php echo $RITCalendar;?>"><i class="roboitescal_<?php echo $RITCalendar;?> roboitescal roboiticons-style <?php echo $RIT_Calendar_Right_Icon;?>"></i></td>
					    		</tr>
					   			<tr>
					   				<?php if($RITTheme_Params[0]->RIT_CalFsD=='Sunday'){?>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?> RIT_Cal_Sun_<?php echo $RITCalendar;?>'>Su</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Mo</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Tu</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>We</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Th</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Fr</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?> RIT_Cal_Sat_<?php echo $RITCalendar;?>'>Sa</td>
					   				<?php } else {?>
					   					<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Mo</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Tu</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>We</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Th</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?>'>Fr</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?> RIT_Cal_Sat_<?php echo $RITCalendar;?>'>Sa</td>
						   				<td class='ritweek_day11 RIT_Cal_WDN_<?php echo $RITCalendar;?> RIT_Cal_Sun_<?php echo $RITCalendar;?>'>Su</td>
					   				<?php }?>
					   			</tr>
					   		</thead>
					  		<tbody>
						</table>
		            </div>
		        </div>
		    </div>
 		 	<?php
 		 	echo $after_widget;
 		}
	}
?>