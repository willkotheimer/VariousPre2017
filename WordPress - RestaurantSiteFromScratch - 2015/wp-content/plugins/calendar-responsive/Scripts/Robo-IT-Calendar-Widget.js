jQuery(document).ready(function(){

	function RITCalendar11(id, year, month) {
		var ritid1=id.split('ritcalendar11_')[1];
		var RIT_CalFsD=jQuery('#RIT_CAL_FD_'+ritid1).val();
		var RITDlast1 = new Date(year,month+1,0).getDate(),
		RITD1 = new Date(year,month,RITDlast1),
		RITDNlast1 = new Date(RITD1.getFullYear(),RITD1.getMonth(),RITDlast1).getDay(),
		RITDNfirst1 = new Date(RITD1.getFullYear(),RITD1.getMonth(),1).getDay(),
		ritcalendar1 = "<tr>",
		month=["January","February","March","April","May","June","July","August","September","October","November","December"];
		if(RIT_CalFsD=='Sunday')
		{
			if (RITDNfirst1 != 6) 
			{
			  for(var  i = 0; i < RITDNfirst1; i++) ritcalendar1 += '<td>';
			}
			else
			{
			  for(var  i = 0; i < 6; i++) ritcalendar1 += '<td>';
			}
		}
		else
		{
			if (RITDNfirst1 != 0) 
			{
			  for(var  i = 1; i < RITDNfirst1; i++) ritcalendar1 += '<td>';
			}
			else
			{
			  for(var  i = 0; i < 6; i++) ritcalendar1 += '<td>';
			}
		}
		for(var  i = 1; i <= RITDlast1; i++) 
		{
		  if (i == new Date().getDate() && RITD1.getFullYear() == new Date().getFullYear() && RITD1.getMonth() == new Date().getMonth()) 
		  {
		    ritcalendar1 += '<td class="ritwithout1 ritwithout1_'+ritid1+' rittoday1_'+ritid1+' ritday_'+ritid1+'_'+i+'" id="'+RITD1.getFullYear()+'_'+parseInt(parseInt(RITD1.getMonth())+1)+'_'+i+'_'+ritid1+'">'+ i;
		  }
		  else
		  {
		  	if(new Date(RITD1.getFullYear(),RITD1.getMonth(),i).getDay() == 0)
		  	{
		    	ritcalendar1 += '<td class="ritwithout1 ritwithout1_'+ritid1+' ritsunday11_'+ritid1+' ritday_'+ritid1+'_'+i+'" id="'+RITD1.getFullYear()+'_'+parseInt(parseInt(RITD1.getMonth())+1)+'_'+i+'_'+ritid1+'">' + i;
		  	}
		  	else if(new Date(RITD1.getFullYear(),RITD1.getMonth(),i).getDay() == 6)
		  	{
		    	ritcalendar1 += '<td class="ritwithout1 ritwithout1_'+ritid1+' ritsaturday11_'+ritid1+' ritday_'+ritid1+'_'+i+'" id="'+RITD1.getFullYear()+'_'+parseInt(parseInt(RITD1.getMonth())+1)+'_'+i+'_'+ritid1+'">' + i;
		  	}
		  	else
		  	{
		    	ritcalendar1 += '<td class="ritwithout1 ritwithout1_'+ritid1+' ritday_'+ritid1+'_'+i+'" id="'+RITD1.getFullYear()+'_'+parseInt(parseInt(RITD1.getMonth())+1)+'_'+i+'_'+ritid1+'">' + i;
		  	}
		  }
		    if(RIT_CalFsD=='Sunday')
			{
				if (new Date(RITD1.getFullYear(),RITD1.getMonth(),i).getDay() == 6) 
				{
				   ritcalendar1 += "<tr>";
				}
			}
			else
			{
				if (new Date(RITD1.getFullYear(),RITD1.getMonth(),i).getDay() == 0) 
				{
				   ritcalendar1 += "<tr>";
				}
			}	
		}
		jQuery('#RIT_CAL_hidden_text_'+ritid1).val(RITDlast1);
		if(RIT_CalFsD=='Sunday')
		{
			for(var  i = RITDNlast1; i < 6; i++) ritcalendar1 += "<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;";
		}
		else
		{
			for(var  i = RITDNlast1; i < 7; i++) ritcalendar1 += "<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;";
		}
		document.querySelector('#'+id+' tbody').innerHTML = ritcalendar1+'</tbody>';
		document.querySelector('#'+id+' thead td:nth-child(2)').innerHTML = month[RITD1.getMonth()] +' '+ RITD1.getFullYear();
		document.querySelector('#'+id+' thead td:nth-child(2)').dataset.month = RITD1.getMonth();
		document.querySelector('#'+id+' thead td:nth-child(2)').dataset.year = RITD1.getFullYear();
		if(RIT_CalFsD=='Sunday')
		{
			if (document.querySelectorAll('#'+id+' tbody tr').length <7)  // чтобы при перелистывании месяцев не "подпрыгивала" вся страница, добавляется ряд пустых клеток. Итог: всегда 6 строк для цифр
			{  
			    document.querySelector('#'+id+' tbody').innerHTML += "<tr><td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;";
			}
		}
		else
		{
			if (document.querySelectorAll('#'+id+' tbody tr').length < 6)  // чтобы при перелистывании месяцев не "подпрыгивала" вся страница, добавляется ряд пустых клеток. Итог: всегда 6 строк для цифр
			{  
			    document.querySelector('#'+id+' tbody').innerHTML += "<tr><td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;<td class='ritempty1 ritempty1_"+ritid1+"'>&nbsp;";
			}
		}
	}
	jQuery('.RIT_Cal').each(function(){
		var RIT_Cal_ID=jQuery(this).attr('id');
		var RIT_Cal_Prev=jQuery(this).find('.ritprev11').attr('id');
		var RIT_Cal_Month=jQuery(this).find('.ritmonth11').attr('id');
		var RIT_Cal_Next=jQuery(this).find('.ritnext11').attr('id');
		var ritid2=RIT_Cal_ID.split('ritcalendar11_')[1];
		// переключатель минус месяц
			document.querySelector('#'+RIT_Cal_Prev).onclick = function() {
			  RITCalendar11(RIT_Cal_ID, document.querySelector('#'+RIT_Cal_Month).dataset.year, parseFloat(document.querySelector('#'+RIT_Cal_Month).dataset.month)-1);
			}
		// переключатель плюс месяц
			document.querySelector('#'+RIT_Cal_Next).onclick = function() {
			  RITCalendar11(RIT_Cal_ID, document.querySelector('#'+RIT_Cal_Month).dataset.year, parseFloat(document.querySelector('#'+RIT_Cal_Month).dataset.month)+1);
			}
		RITCalendar11(RIT_Cal_ID,new Date().getFullYear(),new Date().getMonth());

		var Sel_RIT_cal=jQuery('#RIT_CAL_sel_'+ritid2).val();

		var ajaxurl = object.ajaxurl;
		var data = {
		action: 'Sel_RIT_cal', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
		foobar: Sel_RIT_cal, // translates into $_POST['foobar'] in PHP
		};
		jQuery.post(ajaxurl, data, function(response) {
			var hidden_text=jQuery('#RIT_CAL_hidden_text_'+ritid2).val();

			var RITcalendar=response.split('*&^%^&*');

			for(h=0; h<RITcalendar.length-1; h++)
			{	
				var RITsplit=RITcalendar[h].split('$^&^$');		
				var w=RITsplit[0].split('_');

				if(w[1][0]==0)
				{
					w[1]=w[1][1];
				}
				if(w[2][0]==0)
				{
					w[2]=w[2][1];
				}
				
				for(k=1; k<=hidden_text; k++)
				{
					var day_id=jQuery('.ritday_'+ritid2+'_'+k).attr('id');
					var split_day_id=day_id.split('_');
					if(w[0]==split_day_id[0] && w[1]==split_day_id[1] && w[2]==split_day_id[2] && ritid2==split_day_id[3])
					{
						if(RITsplit[2]=='Yes')
						{
							jQuery('.ritday_'+ritid2+'_'+k).html('<a href="'+RITsplit[1]+'" target="_blank" class="robowithurla_'+ritid2+'">'+k+'</a>');
						}
						else if(RITsplit[2]=='No')
						{
							jQuery('.ritday_'+ritid2+'_'+k).html('<a href="'+RITsplit[1]+'" class="robowithurla_'+ritid2+'">'+k+'</a>');
						}
						jQuery('.ritday_'+ritid2+'_'+k).addClass("robowithurl_"+ritid2);
					}
				}							
			}
			jQuery('#RIT_Cal_P_'+ritid2).click(function()  
			{
				var hidden_text=jQuery('#RIT_CAL_hidden_text_'+ritid2).val();
				
				for(h=0; h<RITcalendar.length-1; h++)
				{	
					var RITsplit=RITcalendar[h].split('$^&^$');		
					var w=RITsplit[0].split('_');

					if(w[1][0]==0)
					{
						w[1]=w[1][1];
					}
					if(w[2][0]==0)
					{
						w[2]=w[2][1];
					}
					
					for(k=1; k<=hidden_text; k++)
					{
						var day_id=jQuery('.ritday_'+ritid2+'_'+k).attr('id');
						var split_day_id=day_id.split('_');
						if(w[0]==split_day_id[0] && w[1]==split_day_id[1] && w[2]==split_day_id[2] && ritid2==split_day_id[3])
						{
							if(RITsplit[2]=='Yes')
							{
								jQuery('.ritday_'+ritid2+'_'+k).html('<a href="'+RITsplit[1]+'" target="_blank" class="robowithurla_'+ritid2+'">'+k+'</a>');
							}
							else if(RITsplit[2]=='No')
							{
								jQuery('.ritday_'+ritid2+'_'+k).html('<a href="'+RITsplit[1]+'" class="robowithurla_'+ritid2+'">'+k+'</a>');
							}
							jQuery('.ritday_'+ritid2+'_'+k).addClass("robowithurl_"+ritid2);
						}
					}							
				}
			})
			jQuery('#RIT_Cal_N_'+ritid2).click(function()  
			{
				var hidden_text=jQuery('#RIT_CAL_hidden_text_'+ritid2).val();
				
				for(h=0; h<RITcalendar.length-1; h++)
				{	
					var RITsplit=RITcalendar[h].split('$^&^$');		
					var w=RITsplit[0].split('_');

					if(w[1][0]==0)
					{
						w[1]=w[1][1];
					}
					if(w[2][0]==0)
					{
						w[2]=w[2][1];
					}
					
					for(k=1; k<=hidden_text; k++)
					{
						var day_id=jQuery('.ritday_'+ritid2+'_'+k).attr('id');
						var split_day_id=day_id.split('_');
						if(w[0]==split_day_id[0] && w[1]==split_day_id[1] && w[2]==split_day_id[2] && ritid2==split_day_id[3])
						{
							if(RITsplit[2]=='Yes')
							{
								jQuery('.ritday_'+ritid2+'_'+k).html('<a href="'+RITsplit[1]+'" target="_blank" class="robowithurla_'+ritid2+'">'+k+'</a>');
							}
							else if(RITsplit[2]=='No')
							{
								jQuery('.ritday_'+ritid2+'_'+k).html('<a href="'+RITsplit[1]+'" class="robowithurla_'+ritid2+'">'+k+'</a>');
							}
							jQuery('.ritday_'+ritid2+'_'+k).addClass("robowithurl_"+ritid2);
						}
					}							
				}
			})	
		});	
	})	
})
jQuery(document).ready(function ($) {
    var RIT_CAL_options = {
      $AutoPlay: false,
    };

    jQuery('.RIT_Cal_Div').each(function(){
    	var RIT_Cal_Div_ID=jQuery(this).attr('id');
    	var RIT_CAL_slider = new $JssorSlider$(RIT_Cal_Div_ID, RIT_CAL_options);
    	var RIT_CAL_Hidden=jQuery(this).find('.RIT_CAL_Hidden').val().split('px')[0];
    
	    //responsive code begin
	    //you can remove responsive code if you don't want the slider scales while window resizing
	    function ScaleSlider() {
	        var refSize = RIT_CAL_slider.$Elmt.parentNode.clientWidth;
	        if (refSize) {
	            refSize = Math.min(refSize, parseInt(RIT_CAL_Hidden));
	            RIT_CAL_slider.$ScaleWidth(refSize);
	        }
	        else {
	            window.setTimeout(ScaleSlider, 30);
	        }
	    }
	    ScaleSlider();
	    $(window).bind("load", ScaleSlider);
	    $(window).bind("resize", ScaleSlider);
	    $(window).bind("orientationchange", ScaleSlider);
	    //responsive code end
    })   
});