<?php
	add_action( 'wp_ajax_Delete_RIT_Calendar_Theme', 'Delete_RIT_Calendar_Theme_Callback' );
	add_action( 'wp_ajax_nopriv_Delete_RIT_Calendar_Theme', 'Delete_RIT_Calendar_Theme_Callback' );
	
	function Delete_RIT_Calendar_Theme_Callback()
	{
		$Del_Calendar_Theme_ID=sanitize_text_field($_POST['foobar']);

	 	global $wpdb;
		$table_name = $wpdb->prefix . "ritcalendar_manager";

		$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id=%d",$Del_Calendar_Theme_ID));
		die();
	}

	add_action( 'wp_ajax_RIT_Calendar_search_theme_field', 'RIT_Calendar_search_theme_field_Callback' );
	add_action( 'wp_ajax_nopriv_RIT_Calendar_search_theme_field', 'RIT_Calendar_search_theme_field_Callback' );

	function RIT_Calendar_search_theme_field_Callback()
	{
		$RIT_Calendar_search_theme_field=strtolower(sanitize_text_field($_POST['foobar']));

		global $wpdb;		

		$table_name = $wpdb->prefix . "ritcalendar_manager";

		$Searched_RIT_Calendar_Theme=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id>%d",0));

		for($i=0;$i<count($Searched_RIT_Calendar_Theme);$i++)
		{
			if(strpos(strtolower($Searched_RIT_Calendar_Theme[$i]->RIT_CalThemeTitle),$RIT_Calendar_search_theme_field)!='')
			{
				echo $Searched_RIT_Calendar_Theme[$i]->id . ')&*&(' . $Searched_RIT_Calendar_Theme[$i]->RIT_CalThemeTitle . ')*^*(';
			}
			else
			{
				echo "ccc";
			}			
		}
		die();
	}

	add_action( 'wp_ajax_Delete_RIT_Cal_URL', 'Delete_RIT_Cal_URL_Callback' );
	add_action( 'wp_ajax_nopriv_Delete_RIT_Cal_URL', 'Delete_RIT_Cal_URL_Callback' );
	
	function Delete_RIT_Cal_URL_Callback()
	{
		$Del_Cal_URL_ID=sanitize_text_field($_POST['foobar']);

	 	global $wpdb;
		$table_name3 =  $wpdb->prefix . "ritcalendar_url";

		$wpdb->query($wpdb->prepare("DELETE FROM $table_name3 WHERE id=%d",$Del_Cal_URL_ID));
		die();
	}

	add_action( 'wp_ajax_Sel_RIT_cal', 'Sel_RIT_cal_Callback' );
	add_action( 'wp_ajax_nopriv_Sel_RIT_cal', 'Sel_RIT_cal_Callback' );
	
	function Sel_RIT_cal_Callback()
	{
		$Sel_RIT_cal = sanitize_text_field($_POST['foobar']);

		global $wpdb;
		$table_name  =  $wpdb->prefix . "ritcalendar_manager";
		$table_name2 =  $wpdb->prefix . "ritcalendar_font_family";
		$table_name3 =  $wpdb->prefix . "ritcalendar_url";

		$Sel_cal_url_date=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE CalendarID=%s", $Sel_RIT_cal));
		
		for($i=0; $i<count($Sel_cal_url_date); $i++)
		{
			$u=explode('-', $Sel_cal_url_date[$i]->URLDate);
			$Data=implode('_', $u);
			echo $Data . '$^&^$' . $Sel_cal_url_date[$i]->URL . '$^&^$' . $Sel_cal_url_date[$i]->ONT . '*&^%^&*';
		}
		die();
	}
?>