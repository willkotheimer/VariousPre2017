<?php
/*
	Plugin Name: Robo-IT Calendar 
	Plugin URI: http://robo-it.esy.es
	Description: Robo-IT Calendar is very easily used plugin.
	Version: 1.0.5
	Author: Robo-IT
	Author URI: http://robo-it.esy.es
	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

	add_action('widgets_init', function() {
	 	register_widget('RIT_Calendar');
	});
	require_once('Robo-IT-Calendar-Widget.php');
	require_once('Robo-IT-Calendar-Ajax.php');
	require_once('Robo-IT-Calendar-Shortcode.php');

	add_action('wp_enqueue_scripts','RIT_Calendar_Style');

	function RIT_Calendar_Style()
	{
		wp_register_style('Robo_IT_Calendar', plugins_url('Style/Robo-IT-Calendar-Widget.css',__FILE__ ));
		wp_enqueue_style('Robo_IT_Calendar');
		wp_register_style('fontawesome-css',plugins_url('/Style/roboiticons.css', __FILE__)); 
	    wp_enqueue_style('fontawesome-css');	

		wp_register_script('Robo_IT_Calendar', plugins_url('Scripts/Robo-IT-Calendar-Widget.js',__FILE__),array('jquery','jquery-ui-core'));
		wp_localize_script('Robo_IT_Calendar', 'object', array('ajaxurl'=>admin_url('admin-ajax.php')));
		wp_enqueue_script('cwp-main', plugins_url('/Scripts/jssor.slider.mini.js', __FILE__), array('jquery', 'jquery-ui-core'));
		wp_enqueue_script('Robo_IT_Calendar');
	}

	add_action("admin_menu", 'Robo_IT_Calendar_Admin_Menu');

	function Robo_IT_Calendar_Admin_Menu() 
	{
		add_menu_page('Robo_IT_Calendar_Admin_Menu','Robo IT Calendar','manage_options','Robo_IT_Calendar_Admin_Menu','Manage_Robo_IT_Calendar_Admin_Menu',plugins_url('/Images/admin-icon.png',__FILE__));

 		add_submenu_page( 'Robo_IT_Calendar_Admin_Menu', 'Robo_IT_Calendar_Admin_Menu_Add', 'Add Calendar', 'manage_options', 'Robo_IT_Calendar_Admin_Menu', 'Manage_Robo_IT_Calendar_Admin_Menu');
 		add_submenu_page( 'Robo_IT_Calendar_Admin_Menu', 'Robo_IT_Calendar_Admin_Menu_Add_URL', 'Add URL', 'manage_options', 'Robo_IT_Calendar_Admin_Menu_URL', 'Manage_Robo_IT_Calendar_Admin_Menu_URL');
	}

	function Manage_Robo_IT_Calendar_Admin_Menu()
	{
		require_once('Robo-IT-Calendar-Add.php');
	}
	function Manage_Robo_IT_Calendar_Admin_Menu_URL()
	{
		require_once('Robo-IT-Calendar-Add-URL.php');
	}

	add_action('admin_init', function() {
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		
		wp_register_script('Robo_IT_Calendar', plugins_url('Scripts/Robo-IT-Calendar-Admin.js',__FILE__),array('jquery','jquery-ui-core'));
		wp_localize_script('Robo_IT_Calendar', 'object', array('ajaxurl'=>admin_url('admin-ajax.php')));
		wp_enqueue_script('Robo_IT_Calendar');

		wp_register_style('Robo_IT_Calendar', plugins_url('Style/Robo-IT-Calendar-Admin.css', __FILE__ ));
		wp_enqueue_style('Robo_IT_Calendar');	 
	});

	register_activation_hook(__FILE__,'Easy_calendar_wp_activate');

	function Easy_calendar_wp_activate()
	{
		require_once('Robo-IT-Calendar-Install.php');
	}
?>