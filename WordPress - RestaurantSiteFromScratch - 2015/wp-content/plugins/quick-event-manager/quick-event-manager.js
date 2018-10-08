function pseudo_popup(content) {
    var popup = document.createElement("div");
    popup.innerHTML = content;
    var viewport_width = window.innerWidth;
    var viewport_height = window.innerHeight;
    function add_underlay() {
        var underlay = document.createElement("div");
        underlay.style.position = "fixed";
        popup.style.zIndex = "9997";
        underlay.style.top = "0px";
        underlay.style.left = "0px";
        underlay.style.width = viewport_width + "px";
        underlay.style.height = viewport_height + "px";
        underlay.style.background = "#7f7f7f";
        if( navigator.userAgent.match(/msie/i) ) {
            underlay.style.background = "#7f7f7f";
            underlay.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=50)";
        } else {
            underlay.style.background = "rgba(127, 127, 127, 0.5)";
        }
        underlay.onclick = function() {
            underlay.parentNode.removeChild(underlay);
            popup.parentNode.removeChild(popup);
        };
        document.body.appendChild(underlay);
    }
    add_underlay();
    var x = viewport_width / 2;
    var y = viewport_height / 2;
    popup.style.position = "fixed";
    document.body.appendChild(popup);
    x -= popup.clientWidth / 2;
    y -= popup.clientHeight / 2;
    popup.style.zIndex = "9998";
    popup.style.top = y + "px";
    popup.style.left = x + "px";
    return false;
}

function qem_toggle_state() {
	$(this).attr('clicked','clicked')
}

function qem_calendar_ajax(e) {
	/*
		Get calendar
	*/
	var calendar = $(e).closest('.qem_calendar');
	var cid = Number(calendar.attr('id').replace('qem_calendar_',''));
	var params = 'action=qem_ajax_calendar';
	
	/*
		URL Encode the atts array
	*/
	for (property in qem_calendar_atts[cid]) {
		params += '&atts['+encodeURIComponent(property)+']='+encodeURIComponent(qem_calendar_atts[cid][property]);
	}
	
	params += "&qemmonth="+qem_month[cid]+"&qemyear="+qem_year[cid];
	if (qem_category[cid] != '') params += '&category='+qem_category[cid];
	
	$.post(ajaxurl,params,function(v) {
		calendar.replaceWith($(v));
		qem_calnav();
	},'text');
	
}

function qem_validate_form(ev) {
	var f = $(this);
	var set = f.find('input[clicked=true]');
	var c = set.first();
	
	set.removeAttr('clicked');
	
	// Intercept request and handle with AJAX
	var fd = $(this).serialize();
	var action = $('<input type="text" />');
	action.attr('name','action');
	action.val('qem_validate_form');
	
	c.attr('type','text');
	fd += '&' + c.serialize() + '&' + action.serialize();
	c.attr('type','submit');

	$.ajax(ajaxurl, {
		data:fd,
		type:'POST',
		dataType:'JSON',
		beforeSend:function() { },
		complete:function(e) {
			data = e.responseJSON;
			
			/*
				Handle Redirection
			*/
			if (data.redirect !== undefined && data.redirect.redirect && !data.errors.length) {
				window.location.href = data.redirect.url;
				return;
			}
			
			qem = f.closest('.qem');
			/*
				Update whoscoming and places
			*/
			
			qem.find('.whoscoming').html(data.coming);
			/* f.closest('.qem').find('.places').html(data.places); */
			
			/*
				Deactivate all current errors
			*/
			qem.find('.qem-register').find('.qem-error,.qem-error-header').removeClass('qem-error qem-error-header');
			qem.find('.qem-register').find('h2').text(data.title)
			if (data.blurb !== undefined) {
				qem.find('.qem-register').children('p').first().show().text(data.blurb);
			}
			
			/*
				If errors: Display
			*/
			for (i in data.errors) {
				element = f.find('[name='+data.errors[i].name+']');
				element.addClass('qem-error');
			}
			
			/*
				Change header class to reflect errors being present
			*/
			if (data.errors.length) {
				qem.find('.qem-register').find('h2').addClass('qem-error-header');
				qem.find('.qem-register .places').hide();
				if (data.errors[i].name == 'alreadyregistered') {
					qem.find('.qem-register').children('p').first().hide();
					qem.find('.places').hide();
					qem.find('.qem-form').html(data.form || '');
				}
			} else {
				/*
					Successful validation! 
				*/
				
				qem.find('.places').hide();
				var form = data.form;

				qem.find('.qem-form').html(form);
			}
			
			/*
				Scroll To Top
			*/
			$('html,body').animate({
				scrollTop: Math.max(qem.find('.qem-register').offset().top - 25,0),
			}, 200);
		}
	});
	ev.preventDefault();
	return false;
}

if (jQuery !== undefined) {
	jQuery(document).ready(function() {
		$ = jQuery;
		
		$('.qem-register form').submit(qem_validate_form);
		$('.qem-register form input[type=submit], .qem-register form input[type=button]').click(qem_toggle_state);
		
		/*
			Set up calendar functionality
		*/
		qem_calnav();
        
        $("#yourplaces").keyup(function () {
        var model= document.getElementById('yourplaces');
        var number = $('#yourplaces').val()
        if (number == 1)
                $("#morenames").hide();
            else {
                $("#morenames").show();
            }
        });

	});
}
function qem_calendar_prep(e) {
	var calendar = $(e).closest('.qem_calendar');
	var cid = Number(calendar.attr('id').replace('qem_calendar_',''));
	var params = e.href.split('#')[0].split('?')[1];
	if (params !== undefined) params = params.split('&');
	else params = [];
	var values = {};
	
	/*
		Form params into an object
	*/
	for (i = 0; i < params.length; i++) {
		set = params[i].split('=');
		values[set[0]] = set[1];
	}
	
	/*
		Special case, no parameters at all = reset category
	*/
	if (params.length == 0) values.category = '';
	
	/*
		Set the global variables if the link would have changed them!
	*/
	if (values.qemmonth !== undefined) qem_month[cid] = values.qemmonth;
	if (values.qemyear !== undefined) qem_year[cid] = values.qemyear;
	if (values.category !== undefined) qem_category[cid] = values.category;
}
function qem_calnav() {
	$('.qem_calendar .calnav').click(function(ev) {
			
			ev.preventDefault();
			
			qem_calendar_prep(this);
			qem_calendar_ajax(this);
			
			return false;
			
	});
	
	$('.qem_calendar .qem-category a').click(function(ev) {
			
			ev.preventDefault();
			
			qem_calendar_prep(this);
			qem_calendar_ajax(this);
			
			return false;
			
	});
	
}