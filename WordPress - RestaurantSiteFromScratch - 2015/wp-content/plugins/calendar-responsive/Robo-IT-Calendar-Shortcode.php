<?php
	function Robo_IT_Calendar_GET_Shortcode_ID($atts, $content = null)
	{
		$atts=shortcode_atts(
			array(
				"id"=>"1"
			),$atts
		);

		return Robo_IT_Calendar_Draw_Shortcode($atts['id']);
	}
	add_shortcode('Robo_IT_Calendar', 'Robo_IT_Calendar_GET_Shortcode_ID');
	function Robo_IT_Calendar_Draw_Shortcode($RCid)
	{
		ob_start();	
			$args = shortcode_atts(array('name' => 'Widget Area','id'=>'hopar_1','description'=>'','class'=>'','before_widget'=>'','after_widget'=>'','before_title'=>'','AFTER_TITLE'=>'','widget_id'=>'1','widget_name'=>''), $atts, 'RIT_Calendar' );
			$Robo_IT_Calendar=new RIT_Calendar;
			global $wpdb;
			$instance=array('RIT_CalThemeTitle'=>$RCid);
			$Robo_IT_Calendar->widget($args,$instance);	
			$cont[]= ob_get_contents();
		ob_end_clean();	
		return $cont[0];		
	}
?>