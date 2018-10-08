function RIT_Calendar_Theme_Sub_Buttons(number)
{
	jQuery('.RIT_Cal_AI').fadeOut();
	var RIT_Cal_Hid_ID=jQuery('.RIT_Cal_Hid_ID').val();
	setTimeout(function(){
		if(RIT_Cal_Hid_ID=='1')
		{
			jQuery('#RIT_Cal_AI_'+number).fadeIn();
		}
		else if(RIT_Cal_Hid_ID=='2')
		{
			jQuery('#RIT_Cal_AI_'+parseInt(parseInt(number)+3)).fadeIn();
		}
		else if(RIT_Cal_Hid_ID=='3')
		{
			jQuery('#RIT_Cal_AI_'+parseInt(parseInt(number)+6)).fadeIn();
		}
	},500)
}
function RIT_Cal_Theme_Sub_BacK_Button()
{
	location.reload();
}
function RIT_Calendar_Search_Theme_Clicked()
{
	setInterval(function(){
		var RIT_Calendar_search_theme_field=jQuery('#RIT_Calendar_search_theme_field').val();
		if(RIT_Calendar_search_theme_field!='')
		{
			var ajaxurl = object.ajaxurl;
			var data = {
			action: 'RIT_Calendar_search_theme_field', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
			foobar: RIT_Calendar_search_theme_field, // translates into $_POST['foobar'] in PHP
			};
			jQuery.post(ajaxurl, data, function(response){
				if(response=='ccc')
				{
					jQuery('#searched_theme_calendar_does_not_exist').html('* Requested Calendar does not exist!');
					jQuery('.RIT_Calendar_Theme_Sub_Table1').hide();
					jQuery('.RIT_Calendar_Theme_Sub_Table').show();
				}
				else
				{
					jQuery('#searched_theme_calendar_does_not_exist').html('');
					jQuery('.RIT_Calendar_Theme_Sub_Table').hide();
					jQuery('.RIT_Calendar_Theme_Sub_Table1').show();
					jQuery('.RIT_Calendar_Theme_Sub_Table1').empty();
					var Shortpart1='[Robo_IT_Calendar id="';
					var Shortpart2='"]';						
					var searched_params=response.split(')*^*(');
					for(i=0;i<parseInt(searched_params.length-1);i++)
					{
						searched_params_callback=searched_params[i].split(')&*&(');	

						jQuery('.RIT_Calendar_Theme_Sub_Table1').append("<tr><td class='RIT_Calendar_Theme_Sub_id_item'><B><I>"+parseInt(parseInt(i)+1)+"</I></B></td><td class='RIT_Calendar_Theme_Sub_name_item'><B><I>"+searched_params_callback[1]+"</I></B></td><td class='RIT_Calendar_Theme_Sub_shortcode_item'><B><I>"+Shortpart1+searched_params_callback[0]+Shortpart2+"</I></B></td><td class='RIT_Calendar_Theme_Sub_edit_item' onclick='Edit_RIT_Calendar_Theme("+searched_params_callback[0]+")'><B><I>Edit</I></B></td><td><B><I>Delete</I></B></td></tr>");
					}
				}
			});
		}
		else
		{
			jQuery('.RIT_Calendar_Theme_Sub_Table1').hide();
			jQuery('.RIT_Calendar_Theme_Sub_Table').show();
			jQuery('#searched_theme_calendar_does_not_exist').html('');
		}
	}, 500);
}
function RIT_Calendar_Reset_Theme_Button_Clicked()
{
	jQuery('#RIT_Calendar_search_theme_field').val('');
	jQuery('#searched_theme_calendar_does_not_exist').html('');
	jQuery('.RIT_Calendar_Theme_Sub_Table1').hide();
	jQuery('.RIT_Calendar_Theme_Sub_Table').show();
}
function Edit_RIT_Calendar_Theme(Theme_ID)
{
	jQuery('.RIT_Calendar_Event_Submenu_Div').fadeOut();
	jQuery('.RIT_Calendar_Theme_Sub_Main_Table').fadeOut();
	jQuery('.RIT_Calendar_Theme_Sub_Table').fadeOut();
	jQuery('.RIT_Cal_Hid_ID').val(Theme_ID);
	setTimeout(function(){
		jQuery('.RIT_Calendar_Event_Submenu_Div1').fadeIn();
		if(Theme_ID=='1')
		{
			jQuery('#RIT_Cal_AI_1').fadeIn();
		}
		else if(Theme_ID=='2')
		{
			jQuery('#RIT_Cal_AI_4').fadeIn();
		}
		else if(Theme_ID=='3')
		{
			jQuery('#RIT_Cal_AI_7').fadeIn();
		}
	},500)	
}
function Delete_RIT_URL(Del_ID)
{
	var ajaxurl = object.ajaxurl;
	var data = {
	action: 'Delete_RIT_Cal_URL', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
	foobar: Del_ID, // translates into $_POST['foobar'] in PHP
	};
	jQuery.post(ajaxurl, data, function(response) {
		location.reload();
	})
}