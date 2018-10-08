<?php
/*
	Add: wordpress hooks for ajax
*/
add_action( 'wp_ajax_qem_validate_form', 'qem_ajax_validation');
add_action( 'wp_ajax_nopriv_qem_validate_form', 'qem_ajax_validation');

/*
	Add: qem_ajax_validation
*/
function qem_ajax_validation() {
	global $post;
	$event = $_POST['id'];
	$args = array(
		'p' => $event,
		'post_type' => 'any');
	
	$json = array(
		'success' => false,
		'errors' => array()
	);
	// Start "The Loop"
	$query = new WP_Query($args);
	$formvalues = $_POST;
	$formerrors = array();
	
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			
			$register = get_custom_registration_form();
			$verify = qem_verify_form($formvalues, $formerrors, true);

			/*
				Build required objects
			*/
			$payment = qem_get_stored_payment();
			$id = ( isset( $post->ID ) ? get_the_ID() : NULL );
			$usecounter = get_post_meta($id, 'event_number', true);
			$number = get_post_meta($id, 'event_number', true);
			$json['coming'] = qem_places($register,$payment,$id,$usecounter,null);
			$num = qem_numberscoming($register,$id,$payment);
			$json['places'] = '';
			
			if ($register['placesavailable'] && $number) {
				$json['places'] .= '<p id="whoscoming">'.$register['placesbefore'].' '.$num.' '.$register['placesafter'].'<p>';
			}
			
			if (!$verify) {
				
				if (isset($formerrors['alreadyregistered'])) {
					if ($formerrors['alreadyregistered'] == 'checked') $json['title'] = $register['alreadyregistered'];
					else $json['title'] = $register['nameremoved']; 
					
					if ($register['useread_more']) {
						$json['form'] = '<p><a href="' . get_permalink() . '">' . $register['read_more'] . '</a></p>';
					}
				} else {
					$json['title'] = $register['error'];
				}

				/* 
					Format error array 
				*/
				$errors = array();
				
				foreach ($formerrors as $k => $v) {
					array_push($errors,array('name' => $k, 'error' => $v));
				}
				$json['errors'] = $errors;
				
			} else {
				// qem_ajax_submit($formvalues);
				qem_process_form($formvalues, true);
				$id = ( isset( $post->ID ) ? get_the_ID() : NULL );
				$cost = get_post_meta($id, 'event_cost', true);
				$number = get_post_meta($event, 'event_number', true);
				$paypal = get_post_meta($id, 'event_paypal', true);
				if ($paypal && $cost) $payment['paypal'] = 'checked';
				
				$payment = qem_get_stored_payment();
				$usecounter = get_post_meta($id, 'event_number', true);
				$json['coming'] = qem_places($register,$payment,$event,$usecounter,null);
				$num = qem_numberscoming($register,$event,$payment);
				$json['places'] = '';
				
				$json['form'] = '';
				
				$json['form'] .=  '<a id="qem_reload"></a>';				

				if (function_exists('qpp_loop') && $cost && $payment['useqpp']) {
					
					$id = $payment['qppform'];
					$title = get_the_title();
					$args = array('form' => $id, 'id' => $title, 'amount' => $cost);
					$json['form'] = qpp_loop($args);
				
				} elseif ((($payment['paypal'] && !$paypal) || $paypal=='checked') && $cost && !$formvalues['ignore']) {
					$json['form'] .= qem_process_payment_form($formvalues);
				
				} elseif ($register['useread_more']) {
					$json['form'] .= '<p><a href="' . get_permalink() . '">' . $register['read_more'] . '</a></p>';
				}
				
				if (empty($formerrors)) {
                    if ($num == 0 && $register['placesavailable'] && $number && $register['waitinglist']) {
                        $register['replyblurb'] = $register['waitinglistreply'];
                    }
        
                    if ($register['moderate']) $register['replyblurb'] = $register['moderatereply'];
					
                    $json['title'] = $register['replytitle']; 
					$json['blurb'] = $register['replyblurb']; 
				}
				
				$globalredirect = $register['redirectionurl'];
                $eventredirect = get_post_meta($post->ID, 'event_redirect', true);
    
                $redirect = ($eventredirect ? $eventredirect : $globalredirect);
				$redirect_id = get_post_meta($id, 'event_redirect_id', true);
				$redirecting = false;
				if ($redirect) {
					if ($redirect_id) {
						if (substr($redirect, -1) != '/') $redirect = $redirect.'/';
						$id = get_the_ID();
						$redirect = $redirect."?event=".$id;
					}
					$redirecting = true;
				}
				$json['redirect'] = array('redirect' => $redirecting, 'url' => $redirect);
				$json['success'] = true;
			}
		}
	}
	echo json_encode($json);
	exit;
}

function qem_loop() {
    global $post;
    $event=get_the_ID();
    if (!empty($_POST['qemregister'.$event])) {
        $formvalues = $_POST;
        $formerrors = array();
        if (!qem_verify_form($formvalues, $formerrors)) {
            return qem_display_form($formvalues, $formerrors,null);
        } else {
            qem_process_form($formvalues);
            return qem_display_form($formvalues, null,'checked');
        }
    } else {
        $values = get_custom_registration_form ();
        $payment = qem_get_stored_payment(); 
        if ( is_user_logged_in() && $values['showuser']) {
            $current_user = wp_get_current_user();
            $values['yourname'] = $current_user->user_login;
            $values['youremail'] = $current_user->user_email;
        }
        $values['yourplaces'] = '1';
        $values['yournumber1'] = '';
        $values['yourcoupon'] = $payment['couponcode'];
        $values['ipn'] = md5(mt_rand());
        $digit1 = mt_rand(1,10);
        $digit2 = mt_rand(1,10);
        if( $digit2 >= $digit1 ) {
            $values['thesum'] = "$digit1 + $digit2";
            $values['answer'] = $digit1 + $digit2;
        } else {
            $values['thesum'] = "$digit1 - $digit2";
            $values['answer'] = $digit1 - $digit2;
        }
        if ( (is_user_logged_in() && $values['registeredusers']) || !$values['registeredusers'] ) 
            return qem_display_form( $values ,null,null);
    }
}

function get_custom_registration_form () {
    global $post;
    $id = ( isset( $post->ID ) ? get_the_ID() : NULL );
    $register = qem_get_stored_register();
    $usecustomform = get_post_meta($id, 'usecustomform', true);
    if ($usecustomform) {
        $arr = array(
            'usename',
            'usemail',
            'usetelephone',
            'useplaces',
            'usemessage',
            'useattend',
            'useblank1',
            'useblank2',
            'usedropdown',
            'useselector',
            'usenumber1',
            'useaddinfo',
            'usemorenames',
            'useterms',
            'usecaptcha'
        );
        foreach ($arr as $item) $register[$item] = get_post_meta($id, $item,true);
    };
    return $register;
};

function qem_display_form( $values, $errors, $registered ) {
    $register = get_custom_registration_form();
    $payment = qem_get_stored_payment();
    $num = $placesleft = '';
    global $post;
    $id = ( isset( $post->ID ) ? get_the_ID() : NULL );
    $event=get_the_ID();
    
    $cutoffdate = get_post_meta($id, 'event_cutoff_date', true);
    
    $cost = get_post_meta($id, 'event_cost', true);
    $number = get_post_meta($event, 'event_number', true);
    $paypal = get_post_meta($id, 'event_paypal', true);
    if ($paypal && $cost) $payment['paypal'] = 'checked';
    $usecustomform = get_post_meta($id, 'usecustomform', true);
    $usecounter = get_post_meta($id, 'event_number', true);
    $cutoff = '';
    
    if ($usecustomform) {
        $arr = array(
            'usename',
            'usemail',
            'usetelephone',
            'useplaces',
            'usemessage',
            'useattend',
            'useblank1',
            'useblank2',
            'usedropdown',
            'useselector',
            'usenumber1',
            'useaddinfo',
            'usemorenames',
            'useterms',
            'usecaptcha'
        );
        foreach ($arr as $item) $register[$item] = get_post_meta($id, $item,true);
    };
    
    if ($cutoffdate && $cutoffdate < time()) $cutoff = 'checked';

    $num = qem_numberscoming($event);
	
    $content = "<script type='text/javascript'>ajaxurl = '".admin_url('admin-ajax.php')."';</script>";
    
    if (function_exists('qem_whosnotcoming')) 
        $content .= qem_whosnotcoming();   
    
    if ($errors['spam']) {
        $errors['alreadyregistered'] = 'checked';
        $register['alreadyregistered'] = $register['spam'];
    
    } elseif ($registered) {
        
        if (!empty($register['replytitle'])) {
            $register['replytitle'] = '<h2>' . $register['replytitle'] . '</h2>';
        }
        
        if ($num == 0 && $register['placesavailable'] && $number && $register['waitinglist']) {
            $register['replyblurb'] = $register['waitinglistreply'];
        }
        
        if ($register['moderate']) $register['replyblurb'] = $register['moderatereply'];
        
        if (!empty($register['replyblurb'])) {
            $register['replyblurb'] = '<p>' . $register['replyblurb'] . '</p>';
        }
        $content .= $register['replytitle'].$register['replyblurb'];
        
        if ((($payment['paypal'] && !$paypal) || $paypal=='checked') && $cost && !$values['ignore']) {
            $content .=  '<a id="qem_reload"></a>';
            $content .= '<script type="text/javascript" language="javascript">
        document.querySelector("#qem_reload").scrollIntoView();
        </script>';
            $content .= qem_process_payment_form($values);
        
        } elseif ($register['useread_more']) {
            $content .= '<p><a href="' . get_permalink() . '">' . $register['read_more'] . '</a></p>';
        }
        
        $content .=  '<a id="qem_reload"></a>';
        
    } elseif (($number > 0 && $num == 0 && !$register['waitinglist']) || $cutoff) {
        $content .= '';
        $num= '';
    } elseif ($errors['alreadyregistered'] == 'checked') {
        $content .= "<div class='places'>".$placesleft.'</div><h2>' . $register['alreadyregistered'] . '</h2>';
        if ($register['useread_more']) $content .= '<p><a href="' . get_permalink() . '">' . $register['read_more'] . '</a></p>';
        $content .=  '<a id="qem_reload"></a>';
    } elseif ($errors['alreadyregistered'] == 'removed') {
        $content .= "<div class='places'>".$placesleft.'</div><h2>' . $register['nameremoved'] . '</h2>';
        if ($register['useread_more']) $content .= '<p><a href="' . get_permalink() . '">' . $register['read_more'] . '</a></p>';
        $content .=  '<a id="qem_reload"></a>';
    } else {
        if (!empty($register['title'])) {
            $register['title'] = '<h2>' . $register['title'] . '</h2>';
        }
        if (!empty($register['blurb'])) {
            $register['blurb'] = '<p>' . $register['blurb'] . '</p>';
        }
        $content .= '<div class="qem-register">';
        if (count($errors) > 0) {
            $content .= "<h2 class='qem-error-header'>" . $register['error'] . "</h2>\r\t";
            $arr = array(
                'yourname',
                'youremail',
                'yourtelephone',
                'yourplaces',
                'yourmessage',
                'youranswer',
                'yourblank1',
                'yourblank2',
                'yourdropdown',
                'yourcoupon'
            );
            foreach ($arr as $item) {
                $content .= '>'.$item.'/'.$errors[$item].'<br>';
                if ($errors[$item] == 'error') $errors[$item] = ' class="qem-error"';   
            }
            if ($errors['yourcoupon']) $register['blurb'] = '<p>Invalid Coupon Code</p>';
            if ($errors['yourplaces']) $errors['yourplaces'] = 'border:1px solid red;';
            if ($errors['yournumber1']) $errors['yournumber1'] = 'border:1px solid red;';
            if ($errors['youranswer']) $errors['youranswer'] = 'border:1px solid red;';
        } else {
            $content .= $register['title'] . $register['blurb'];
        }
        $content .= "<div class='places'>".$placesleft."</div>";
		
        $content .= '<div class="qem-form"><form action="" method="POST" enctype="multipart/form-data">';
        $content .= '<input type="hidden" name="id" value="'.$id.'" />';
        foreach (explode( ',',$register['sort']) as $name) {
            switch ( $name ) {
                case 'field1':
                if ($register['usename'])
                    $content .= '<input id="yourname" name="yourname"'.$errors['yourname'].' type="text" value="'.$values['yourname'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yourname'].'\';}" onfocus="if (this.value == \''.$values['yourname'].'\') {this.value = \'\';}" />'."\n";
                break;
                case 'field2':
                if ($register['usemail']) 
                    $content .= '<input id="email" name="youremail"'.$errors['youremail'].' type="text" value="'.$values['youremail'].'" onblur="if (this.value == \'\') {this.value = \''.$values['youremail'].'\';}" onfocus="if (this.value == \''.$values['youremail'].'\') {this.value = \'\';}" />';
                break;
                case 'field3':        
                if ($register['useattend']) 
                $content .= '<p><input type="checkbox" name="notattend" value="checked" '.$values['notattend'].' /> '.$register['yourattend'].'</p>';
                break;
                case 'field4':
                if ($register['usetelephone']) 
                    $content .= '<input id="email" name="yourtelephone"'.$errors['yourtelephone'].' type="text" value="'.$values['yourtelephone'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yourtelephone'].'\';}" onfocus="if (this.value == \''.$values['yourtelephone'].'\') {this.value = \'\';}" />';
                break;
                case 'field5':
                if ($register['useplaces']) 
                    $content .= '<p><input id="yourplaces" name="yourplaces" type="text"'.$errors['yourplaces'].' style="width:3em;margin-right:5px" value="'.$values['yourplaces'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yourplaces'].'\';}" onfocus="if (this.value == \''.$values['yourplaces'].'\') {this.value = \'\';}" />'.$register['yourplaces'].'</p>';
                else 
                    $content .= '<input type="hidden" name="yourplaces" value="1">';
                if ($register['usemorenames']) 
                    $content .= '<div id="morenames" hidden="hidden"><p>'.$register['morenames'].'</p>
                    <textarea rows="4" label="message" name="morenames"></textarea></div>';
                break;
                case 'field6':
                if ($register['usemessage']) 
                    $content .= '<textarea rows="4" label="message" name="yourmessage"'.$errors['yourmessage'].' onblur="if (this.value == \'\') {this.value = \''.$values['yourmessage'].'\';}" onfocus="if (this.value == \''.$values['yourmessage'].'\') {this.value = \'\';}" />' . stripslashes($values['yourmessage']) . '</textarea>';
                break;
                case 'field7':
                if ($register['usecaptcha']) 
                    $content .= $values['thesum'].' = <input id="youranswer" name="youranswer" type="text"'.$errors['youranswer'].' style="width:3em;"  value="'.$values['youranswer'].'" onblur="if (this.value == \'\') {this.value = \''.$values['youranswer'].'\';}" onfocus="if (this.value == \''.$values['youranswer'].'\') {this.value = \'\';}" /><input type="hidden" name="answer" value="' . strip_tags($values['answer']) . '" />
<input type="hidden" name="thesum" value="' . strip_tags($values['thesum']) . '" />';
                break;
                case 'field8':
                if ($register['usecopy']) {
                    if ($register['copychecked']) $copychecked = 'checked';
                    $content .= '<p><input type="checkbox" name="qem-copy" value="checked" '.$values['qem-copy'].' '.$copychecked.' /> '.$register['copyblurb'].'</p>';
                }
                break;
                case 'field9':
                if ($register['useblank1']) 
                    $content .= '<input id="yourblank1" name="yourblank1"'.$errors['yourblank1'].' type="text" value="'.$values['yourblank1'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yourblank1'].'\';}" onfocus="if (this.value == \''.$values['yourblank1'].'\') {this.value = \'\';}" />';
                break;
                case 'field10':
                if ($register['useblank2']) 
                    $content .= '<input id="yourblank2" name="yourblank2"'.$errors['yourblank2'].' type="text" value="'.$values['yourblank2'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yourblank2'].'\';}" onfocus="if (this.value == \''.$values['yourblank2'].'\') {this.value = \'\';}" />';
                break;
                case 'field11':
                if ($register['usedropdown']) {
                    $content .= '<select'.$errors['yourdropdown'].' name="yourdropdown">';
                    $arr = explode(",",$register['yourdropdown']);
                    foreach ($arr as $item) {
                        $selected = '';
                        if ($values['yourdropdown'] == $item) $selected = 'selected';
                        $content .= '<option value="' .  $item . '" ' . $selected .'>' .  $item . '</option>';
                    }
                    $content .= '</select>';
                }
                break;
                case 'field12':
                if ($register['usenumber1']) 
                    $content .= $register['yournumber1'].'&nbsp;<input id="yournumber1" name="yournumber1"'.$errors['yournumber1'].' type="text" style="'.$errors['yournumber1'].'width:3em;margin-right:5px" value="'.$values['yournumber1'].'" value="'.$values['yournumber1'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yournumber1'].'\';}" onfocus="if (this.value == \''.$values['yournumber1'].'\') {this.value = \'\';}" />';
                break;
                case 'field13';
                if ($register['useaddinfo'])
                    $content .= '<p>'.$register['addinfo'].'</p>';
                break;
                case 'field14':
                if ($register['useselector']) {
                    $content .= '<select '.$errors['yourselector'].' name="yourselector">';
                    $arr = explode(",",$register['yourselector']);
                    foreach ($arr as $item) {
                        $selected = '';
                        if ($values['yourselector'] == $item) $selected = 'selected';
                        $content .= '<option value="' .  $item . '" ' . $selected .'>' .  $item . '</option>';
                    }
                    $content .= '</select>';
                }
                break;
                }
            }
        if ($register['useterms']) {
            if ($errors['terms']) {
                $termstyle = ' style="border:1px solid red;"';
                $termslink = ' style="color:red;"';
            }
            if ($register['termstarget']) $target = ' target="_blank"';
            $content .= '<p><input type="checkbox" name="terms" value="checked" '.$termstyle.$values['terms'].' /> <a href="'.$register['termsurl'].'"'.$target.$termslink.'>'.$register['termslabel'].'</a></p>';
        }   
        if ($register['ignorepayment'] && ((($payment['paypal'] && !$paypal) || $paypal=='checked') && $cost)) {
            $content .= '<p><input type="checkbox" name="ignore" value="checked" '.$values['ignore'].' />'.$register['ignorepaymentlabel'].'</p>';
        }    
        if ((($payment['paypal'] && !$paypal) || $paypal=='checked') && $cost) {
            $register['qemsubmit'] = $payment['qempaypalsubmit'];
            if ($payment['usecoupon']) {
                $content .= '<input name="yourcoupon" type="text"'.$errors['yourcoupon'].' value="'.$values['yourcoupon'].'" onblur="if (this.value == \'\') {this.value = \''.$values['yourcoupon'].'\';}" onfocus="if (this.value == \''.$values['yourcoupon'].'\') {this.value = \'\';}" />';
            }
        }
        $content .= '<input type="hidden" name="ipn" value="'.$values['ipn'].'">
<input type="submit" value="'.$register['qemsubmit'].'" id="submit" name="qemregister'.$event.'" />
        </form></div>
        <div style="clear:both;"></div></div>';
    }
    /*
		Remove This since this throws an error since it doesn't exist at that moment
	
	$content .= '<script type="text/javascript" language="javascript">
        document.querySelector("#qem_reload").scrollIntoView();
        </script>';
	*/
    return $content;
}

function qem_search_array($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && qem_search_array($needle, $element))
               return 'error';
     }
}

function qem_verify_form(&$values, &$errors, $ajax = false) {
    $event = get_the_ID();
    $whoscoming = get_option('qem_messages_'.$event);
    if (!$whoscoming) $whoscoming = array();
    $register = get_custom_registration_form ();
    $payment = qem_get_stored_payment();
    $apikey = get_option('qem-akismet');
    if ($apikey) {
        $blogurl = get_site_url();
        $akismet = new qem_akismet($blogurl ,$apikey);
        $akismet->setCommentAuthor($values['yourname']);
        $akismet->setCommentAuthorEmail($values['youremail']);
        $akismet->setCommentContent($values['yourmessage']);
        if($akismet->isCommentSpam()) $errors['spam'] = $register['spam'];
    }
    
    // Checks against CSV
    
    if (function_exists('qem_check_email')) {
        $errors = qem_check_email($errors,$values);
        if ($errors['alreadyregistered']) $alreadyregistered = true;
    } elseif (!$register['usemail'] && $register['usename'] && !$register['allowmultiple'] && $values['yourname']) {
        $alreadyregistered = qem_search_array($values['yourname'], $whoscoming);
    } elseif ($register['usemail'] && !$register['allowmultiple'] && $values['youremail']) {
        $alreadyregistered = qem_search_array($values['youremail'], $whoscoming);
    }
    
    if ($alreadyregistered) {
        if ($register['checkremoval'] && $values['notattend'] && $values['youremail'] && $register['usemail']) {
            $message = get_option('qem_messages_'.$event);
            for($i = 0; $i <= count($message); $i++) {
                if ($message[$i]['youremail'] == $values['youremail']) {
                    unset($message[$i]);
                    $errors['alreadyregistered'] = 'removed';
                }
            }
            $message = array_values($message);
            update_option('qem_messages_'.$event, $message );
            if (!$register['nonotifications']) qem_sendremovalemail($register,$values);
        } else {
            $errors['alreadyregistered'] = 'checked';
        }
    } else {
        if ($register['usemail'] && $register['reqmail'] && !filter_var($values['youremail'], FILTER_VALIDATE_EMAIL))
            $errors['youremail'] = 'error';
        
        $values['yourname'] = filter_var($values['yourname'], FILTER_SANITIZE_STRING);
        if (($register['usename'] && $register['reqname']) && (empty($values['yourname']) || $values['yourname'] == $register['yourname']))
            $errors['yourname'] = 'error';
        $values['youremail'] = filter_var($values['youremail'], FILTER_SANITIZE_STRING);
        if (($register['usemail'] && $register['reqmail']) && (empty($values['youremail']) || $values['youremail'] == $register['youremail']))
            $errors['youremail'] = 'error';
    
        $values['yourtelephone'] = filter_var($values['yourtelephone'], FILTER_SANITIZE_STRING);
        if (($register['usetelephone'] && $register['reqtelephone']) && (empty($values['yourtelephone']) || $values['yourtelephone'] == $register['yourtelephone'])) 
            $errors['yourtelephone'] = 'error';
    
        $values['yourplaces'] = preg_replace ( '/[^0-9]/', '', $values['yourplaces']);
        if ($register['useplaces'] && empty($values['yourplaces'])) 
            $values['yourplaces'] = '1';
    
        $values['morenames'] = filter_var($values['morenames'], FILTER_SANITIZE_STRING);
        
        $values['yourmessage'] = filter_var($values['yourmessage'], FILTER_SANITIZE_STRING);
        if (($register['usemessage'] && $register['reqmessage']) && (empty($values['yourmessage']) || $values['yourmessage'] == $register['yourmessage'])) 
            $errors['yourmessage'] = 'error';
        
        $values['yourblank1'] = filter_var($values['yourblank1'], FILTER_SANITIZE_STRING);
        if (($register['useblank1'] && $register['reqblank1']) && (empty($values['yourblank1']) || $values['yourblank1'] == $register['yourblank1'])) 
            $errors['yourblank1'] = 'error';
    
        $values['yourblank2'] = filter_var($values['yourblank2'], FILTER_SANITIZE_STRING);
        if (($register['useblank2'] && $register['reqblank2']) && (empty($values['yourblank2']) || $values['yourblank2'] == $register['yourblank2'])) 
            $errors['yourblank2'] = 'error';
        
        $values['yourdropdown'] = filter_var($values['yourdropdown'], FILTER_SANITIZE_STRING);
        $values['yourselector'] = filter_var($values['yourselector'], FILTER_SANITIZE_STRING);

        $values['yournumber1'] = filter_var($values['yournumber1'], FILTER_SANITIZE_STRING);
        if (($register['usenumber1'] && $register['reqnumber1']) && (empty($values['yournumber1']) || $values['yournumber1'] == $register['yournumber1'])) 
            $errors['yournumber1'] = 'error';
        
        if ($register['useterms'] && (empty($values['terms']))) 
            $errors['terms'] = 'error';

        if ($register['usecaptcha'] && (empty($values['youranswer']) || $values['youranswer'] <> $values['answer'])) 
            $errors['youranswer'] = 'error';
			$values['youranswer'] = filter_var($values['youranswer'], FILTER_SANITIZE_STRING);
        
        if($register['useplaces'] && get_post_meta($event, 'event_number', true) && !$register['waitinglist']) {
            $event = get_the_ID();
            $attending = qem_get_the_numbers($event,$payment);
            $number = $attending + $values['yourplaces'];
            $places = get_post_meta($event, 'event_number', true);
            if ($attending && $places < $number) 
                $errors['yourplaces'] = 'error';
        }
    }
    return (count($errors) == 0);	
}

function qem_process_form($values, $ajax = false) {
    global $post;
    $id = get_the_ID();
    $date = get_post_meta($post->ID, 'event_date', true);
    $enddate = get_post_meta($post->ID, 'event_end_date', true);
    $content='';
    $places = get_post_meta($post->ID, 'event_number', true);
    $date = date_i18n("d M Y", $date);
	$register = get_custom_registration_form ();
    $auto = qem_get_stored_autoresponder();
	$payment = qem_get_stored_payment();
    $event = get_the_ID();
    $qem_messages = get_option('qem_messages_'.$event);
    if(!is_array($qem_messages)) $qem_messages = array();
    $sentdate = date_i18n('d M Y');
    $newmessage = array();
    $arr = array(
        'yourname',
        'youremail',
        'yourtelephone',
        'yourmessage',
        'yourplaces',
        'yourblank1',
        'yourblank2',
        'yourdropdown',
        'yourselector',
        'yournumber1',
        'morenames',
        'ignore',
    );
    
    foreach ($arr as $item) {
        if ($values[$item] != $register[$item]) $newmessage[$item] = $values[$item];
    }
    $newmessage['notattend'] = $values['notattend'];
    if ($values['notattend']) $values['yourplaces'] = '';
    $newmessage['sentdate'] = $sentdate;
    $newmessage['ipn'] = $values['ipn'];
    $qem_messages[] = $newmessage;
    
    if ($values['notattend']) {
        $qem_removal = get_option('qem_removal');
        $newmessage['title'] = get_the_title();
        $newmessage['date'] = $date;
        $qem_removal[] = $newmessage;
        update_option('qem_removal',$qem_removal); 
    }
    
    update_option('qem_messages_'.$event,$qem_messages);
    
    if (function_exists('qem_update_csv')) qem_update_csv($values);
    
    if (empty($register['sendemail'])) {
        global $current_user;
        get_currentuserinfo();
        $qem_email = $current_user->user_email;
    } else {
        $qem_email = $register['sendemail'];
    }
    
    $subject = $auto['subject'];
    if ($auto['subjecttitle']) $subject = $subject.' '.get_the_title();
    if ($autor['subjectdate']) $subject = $subject.' '.$date;
    if (empty($subject)) $subject = 'Event Registration';
    $notificationsubject = 'New Registration for '.get_the_title().' on '.$date;
    $content = qem_build_event_message($values,$register);
    
    if (!$register['nonotifications']) {
        $headers = "From: ".$values['yourname']." <".$values['youremail'].">\r\n"
    . "MIME-Version: 1.0\r\n"
    . "Content-Type: text/html; charset=\"utf-8\"\r\n";	
        $message = '<html>'.$content.'</html>';
        wp_mail($qem_email, $notificationsubject, $message, $headers);
    }
    if (($auto['enable'] || $values['qem-copy']) && !$register['moderate'] && $auto['whenconfirm'] == 'aftersubmission') {
        qem_send_confirmation ($auto,$values,$content,$register,$id);
    }
    if (($payment['paypal'] && !get_post_meta($post->ID, 'event_paypal',true)) || get_post_meta($post->ID, 'event_paypal',true) =='checked') {
        return 'checked';
    }
    $globalredirect = $register['redirectionurl'];
    $eventredirect = get_post_meta($post->ID, 'event_redirect', true);
    
    $redirect = ($eventredirect ? $eventredirect : $globalredirect);
    $redirect_id = get_post_meta($post->ID, 'event_redirect_id', true);
    
    if ($redirect && !$ajax) {
        if ($redirect_id) {
            if (substr($redirect, -1) != '/') $redirect = $redirect.'/';
            $id = get_the_ID();
            $redirect = $redirect."?event=".$id;
        }
        echo "<meta http-equiv='refresh' content='0;url=$redirect' />";
        exit();
    }
}

function qem_sendremovalemail($register,$values){
    global $post;
    if (empty($register['sendemail'])) {
        global $current_user;
        get_currentuserinfo();
        $qem_email = $current_user->user_email;
    } else {
        $qem_email = $register['sendemail'];
    }
    $date = get_post_meta($post->ID, 'event_date', true);
    $date = date_i18n("d M Y", $date);
    $notificationsubject = 'Registration Removal for '.get_the_title().' on '.$date;
    $headers = "From: ".$values['yourname']." <".$values['youremail'].">\r\n"
. "MIME-Version: 1.0\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
    $content = $values['yourname'] .' ('.$values['youremail'].') is no longer attending '.get_the_title().' on '.$date;
    $message = '<html>'.$content.'</html>';
    wp_mail($qem_email, $notificationsubject, $message, $headers);
    
    $qem_removal = get_option('qem_removals');
    if(!is_array($qem_removal)) $qem_removal = array();
    $sentdate = date_i18n('d M Y');
    $newmessage = array();
    $arr = array(
        'yourname',
        'youremail',
        'notattend',
        'yourtelephone',
        'yourmessage',
        'yourplaces',
        'yourblank1',
        'yourblank2',
        'yourdropdown',
        'yourselector',
        'yournumber1',
        'morenames',
        'ignore',
    );
    
    foreach ($arr as $item) {
        if ($values[$item] != $register[$item]) $newmessage[$item] = $values[$item];
    }
    $newmessage['sentdate'] = $sentdate;
    $newmessage['title'] = get_the_title();
    $newmessage['date'] = $date;
    $qem_removal[] = $newmessage;
    
    update_option('qem_removal',$qem_removal);
}

function qem_send_confirmation ($auto,$values,$content,$register,$id) {
    $rcm = get_post_meta($id, 'event_registration_message', true);
    $date = get_post_meta($id, 'event_date', true);
    $enddate = get_post_meta($id, 'event_end_date', true);
    $date = date_i18n("d M Y", $date);
    $subject = $auto['subject'];
    if ($auto['subjecttitle']) $subject = $subject.' '.get_the_title($id);
    if ($autor['subjectdate']) $subject = $subject.' '.$date;
    if (empty($subject)) $subject = 'Event Registration';
    
    global $current_user;
    get_currentuserinfo();
    if (!$auto['fromemail']) $auto['fromemail'] = $current_user->user_email;
    if (!$auto['fromname']) $auto['fromname'] = get_bloginfo('name');

    $msg = ($rcm ? $rcm : $auto['message']);
    $msg = str_replace('[name]', $values['yourname'], $msg);
    $msg = str_replace('[places]', $values['yourplaces'], $msg);
    $msg = str_replace('[event]', get_the_title($id), $msg);
    $msg = str_replace('[date]', $date, $msg);
    $copy .= '<html>' . $msg;
    if ($auto['useregistrationdetails'] || $values['qem-copy']) {
        if($auto['registrationdetailsblurb']) {
            $copy .= '<h2>'.$auto['registrationdetailsblurb'].'</h2>';
            $copy .= qem_build_event_message($values,$register);
        }
    }
    
    if ($auto['useeventdetails']) {
        if ($auto['eventdetailsblurb']) $details .= '<h2>'.$auto['eventdetailsblurb'].'</h2>';
        $details .= '<p>'.get_the_title($event).'</p><p>'.$date;
        if ($enddate) {
            $enddate = date_i18n("d M Y", $enddate);
            $details .= ' - '.$enddate;
        }
        $details .= '</p>';
    }
    
    if ($auto['permalink']) $close .= '<p><a href="' . get_permalink($id) . '">' . get_permalink($id) . '</a></p>';
    $message = $copy.$details.$close.'</html>';
    $headers = "From: ".$auto['fromname']." <{$auto['fromemail']}>\r\n"
. "MIME-Version: 1.0\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
    wp_mail($values['youremail'], $subject, $message, $headers);
}

function qem_build_event_message($values,$register) {
    $content = '';
    if ($register['usename']) $content .= '<p><b>' . $register['yourname'] . ': </b>' . strip_tags(stripslashes($values['yourname'])) . '</p>';
    if ($register['usemail']) $content .= '<p><b>' . $register['youremail'] . ': </b>' . strip_tags(stripslashes($values['youremail'])) . '</p>';
    if ($register['useattend'] && $values['notattend']) $content .= '<p><b>' . $register['yourattend'] . ': </b></p>';
    if ($register['usetelephone']) $content .= '<p><b>' . $register['yourtelephone'] . ': </b>' . strip_tags(stripslashes($values['yourtelephone'])) . '</p>';
    if ($register['useplaces']  && !$values['notattend']) $content .= '<p><b>' . $register['yourplaces'] . ': </b>' . strip_tags(stripslashes($values['yourplaces'])) . '</p>';
    elseif (!$register['useplaces']  && !$values['notattend']) $values['yourplaces'] = '1'; 
    else $values['yourplaces'] = '';
    if ($register['usemorenames'] && $values['yourplaces'] > 1) $content .= '<p><b>' . $register['morenames'] . ': </b>' . strip_tags(stripslashes($values['morenames'])) . '</p>';
    if ($register['usemessage']) $content .= '<p><b>' . $register['yourmessage'] . ': </b>' . strip_tags(stripslashes($values['yourmessage'])) . '</p>';
    if ($register['useblank1']) $content .= '<p><b>' . $register['yourblank1'] . ': </b>' . strip_tags(stripslashes($values['yourblank1'])) . '</p>';
    if ($register['useblank2']) $content .= '<p><b>' . $register['yourblank2'] . ': </b>' . strip_tags(stripslashes($values['yourblank2'])) . '</p>';
    if ($register['usedropdown']) {
        $arr = explode(",",$register['yourdropdown']);
        $content .= '<p><b>' . $arr[0] . ': </b>' . strip_tags(stripslashes($values['yourdropdown'])) . '</p>';
    }
    if ($register['useselector']) {
        $arr = explode(",",$register['yourselector']);
        $content .= '<p><b>' . $arr[0] . ': </b>' . strip_tags(stripslashes($values['yourselector'])) . '</p>';
    }
    if ($register['usenumber1']) $content .= '<p><b>' . $register['usenumber1'] . ': </b>' . strip_tags(stripslashes($values['usenumber1'])) . '</p>';
    if ($register['ignorepayment']) $content .= '<p><b>' . $register['ignorepaymentlabel'] . ': </b>' . strip_tags(stripslashes($values['ignore'])) . '</p>';
    return $content;
}

function qem_registration_report($atts) {
    extract(shortcode_atts(array('event'=>''),$atts));
    $message = get_option('qem_messages_'.$event);
    $register = get_custom_registration_form ();
    ob_start();
    $content ='<div id="qem-widget">
    <h2><a href="'.get_permalink($event).'">'.get_the_title($event).'</a></h2>';
    $content .= qem_build_registration_table ($register,$message,'report',$event);
    $content .='</div>';
    echo $content;
    $output_string=ob_get_contents();
    ob_end_clean();
    return $output_string;
}

function qem_build_registration_table ($register,$message,$report,$event) {
    $payment = qem_get_stored_payment();
    $number = get_post_meta($event, 'event_number', true);
    $span=$charles=$content='';
    $delete=array();$i=0;
    $dashboard = '<table cellspacing="0">
    <tr>';
    if ($register['usename']) $dashboard .= '<th>'.$register['yourname'].'</th>';
    if ($register['usemail']) $dashboard .= '<th>'.$register['youremail'].'</th>';
    if ($register['useattend']) $dashboard .= '<th>'.$register['yourattend'].'</th>';
    if ($register['usetelephone']) $dashboard .= '<th>'.$register['yourtelephone'].'</th>';
    if ($register['useplaces']) $dashboard .= '<th>'.$register['yourplaces'].'</th>';
    if ($register['usemorenames']) $dashboard .= '<th>'.$register['morenames'].'</th>';
    if ($register['usemessage']) $dashboard .= '<th>'.$register['yourmessage'].'</th>';
    if ($register['useblank1']) $dashboard .= '<th>'.$register['yourblank1'].'</th>';
    if ($register['useblank2']) $dashboard .= '<th>'.$register['yourblank2'].'</th>';
    if ($register['usedropdown']) {
        $arr = explode(",",$register['yourdropdown']);
        $dashboard .= '<th>'.$arr[0].'</th>';
    }
    if ($register['useselector']) {
        $arr = explode(",",$register['yourselector']);
        $dashboard .= '<th>'.$arr[0].'</th>';
    }
    if ($register['usenumber1']) $dashboard .= '<th>'.$register['yournumber1'].'</th>';
    if ($register['ignorepayment']) $dashboard .= '<th>'.$register['ignorepaymentlabel'].'</th>';
    $dashboard .= '<th>Date Sent</th>';
    if ($payment['ipn']) $dashboard .= '<th>'.$payment['title'].'</th>';
    $del = ($register['moderate'] ? 'Delete/Approve' : 'Delete');
    if (!$report) $dashboard .= '<th>'.$del.'</th>';
    $dashboard .= '</tr>';
	
    foreach($message as $value) {
        $num = $num + $value['yourplaces'];
        $span='';
        if ($number && $num > $number) $span = 'color:#CCC;';
        if (!$value['approved'] && $register['moderate']) $span = $span.'font-style:italic;';
        if ($span) $span = ' style="'.$span.'" ';
        $content .= '<tr'.$span.'>';
        if ($register['usename']) $content .= '<td>'.$value['yourname'].'</td>';
        if ($register['usemail']) $content .= '<td>'.$value['youremail'].'</td>';
        if ($register['useattend']) $content .= '<td>'.$value['notattend'].'</td>';
        if ($register['usetelephone']) $content .= '<td>'.$value['yourtelephone'].'</td>';
        if ($register['useplaces'] && empty($value['notattend'])) $content .= '<td>'.$value['yourplaces'].'</td>';
        elseif ($register['useplaces']) $content .= '<td></td>';
        if ($register['usemorenames']) $content .= '<td>'.$value['morenames'].'</td>';
        if ($register['usemessage']) $content .= '<td>'.$value['yourmessage'].'</td>';
        if ($register['useblank1']) $content .= '<td>'.$value['yourblank1'].'</td>';
        if ($register['useblank2']) $content .= '<td>'.$value['yourblank2'].'</td>';
        if ($register['usedropdown']) $content .= '<td>'.$value['yourdropdown'].'</td>';
        if ($register['useselector']) $content .= '<td>'.$value['yourselector'].'</td>';
        if ($register['usenumber1']) $content .= '<td>'.$value['yournumber1'].'</td>';
        if ($register['ignorepayment']) $content .= '<td>'.$value['ignore'].'</td>';
        if ($value['yourname']) $charles = 'messages';
        $content .= '<td>'.$value['sentdate'].'</td>';
        if ($payment['ipn']) {
            $ipn = ($payment['sandbox'] ? $value['ipn'] : '');
            $content .= ($value['ipn'] == "Paid" ? '<td>'.$payment['paid'].'</td>' : '<td>'.$ipn.'</td>');
        }
        if (!$report)  $content .= '<td><input type="checkbox" name="'.$i.'" value="checked" /></td>';
        $content .= '</tr>';
        $i++;
    }	
    $dashboard .= $content.'</table>';
    if ($register['placesavailable'] && $number) {
        $num = qem_numberscoming($register,$event,$payment);
        $places = ($num ? $num : '0');
        $dashboard .= '<p id="whoscoming">'.$register['placesbefore'].' '.$places.' '.$register['placesafter'].'<p>';
    }
    if ($charles) return $dashboard;
}

function qem_qpp_places () {
    global $post;
    $payment = qem_get_stored_payment();
    if ($payment['qppcounter']) {
        $event = get_the_ID();
        $values = array('yourplaces' => 1);
        qem_place_number ($event,$values);
    }
}

function qem_place_number ($event,$values,$payment) {
    $attending = qem_get_the_numbers($event,$payment);
    $number = get_post_meta($event, 'event_number', true);
    if (!$number) return;
    if (!is_numeric($values['yourplaces'])) $values['yourplaces'] = 1;
    $attending = $eventnumber - $values['yourplaces'];
    if ($eventnumber < 1) $eventnumber = 'full';
    update_option( $event.'places', $eventnumber );
}


function qem_messages() {
    $event=$title='';
    global $_GET;
    $event = (isset($_GET["event"]) ? $_GET["event"] : null);
    $title = (isset($_GET["title"]) ? $_GET["title"] : null);
    $unixtime = get_post_meta($event, 'event_date', true);
    $date = date_i18n("d M Y", $unixtime);
    $noregistration = '<p>No event selected</p>';
    $register = get_custom_registration_form ();
    $category = 'All Categories';
    if( isset( $_POST['qem_reset_message'])) {
        $event= $_POST['qem_download_form'];
        $title = get_the_title($event);
        delete_option('qem_messages_'.$event);
        delete_option($event);
        qem_admin_notice('Registrants for '.$title.' have been deleted.');
        $eventnumber = get_post_meta($event, 'event_number', true);
        update_option($event.'places',$eventnumber);
    }
    
    if( isset( $_POST['category']) ) {
        $category = $_POST["category"];
    }
    
    if( isset( $_POST['select_event'])  || isset( $_POST['eventid'])) {
        $event = $_POST["eventid"];
        if ($event) {
            $unixtime = get_post_meta($event, 'event_date', true);
            $date = date_i18n("d M Y", $unixtime);
            $title = get_the_title($event);
            $noregistration = '<h2>'.$title.' | '.$date.'</h2><p>Nobody has registered for '.$title.' yet</p>';
        } else {
            $noregistration = '<p>No event selected</p>';
        }
    }
    
    if( isset( $_POST['changeoptions'])) {
        $options = array( 'showevents','category');
        foreach ( $options as $item) $messageoptions[$item] = stripslashes($_POST[$item]);
        $category = $messageoptions['category'];
        update_option( 'qem_messageoptions', $messageoptions );
    }
    
    if( isset($_POST['qem_delete_selected'])) {
        $event = $_POST["qem_download_form"];
        $message = get_option('qem_messages_'.$event);
        for($i = 0; $i <= 100; $i++) {
            if ($_POST[$i] == 'checked') {
                $num = ($message[$i]['yourplaces'] ? $message[$i]['yourplaces'] : 1);
                unset($message[$i]);
            }
        }
        $message = array_values($message);
        update_option('qem_messages_'.$event, $message );
        qem_admin_notice('Selected registrations have been deleted.');
    }

    if( isset($_POST['qem_approve_selected'])) {
        $event = $_POST["qem_download_form"];
        $message = get_option('qem_messages_'.$event);
        $auto = qem_get_stored_autoresponder();
        for($i = 0; $i <= 100; $i++) {
            if ($_POST[$i] == 'checked') {
                $num = ($message[$i]['yourplaces'] ? $message[$i]['yourplaces'] : 1);
                $message[$i]['approved'] = 'checked';
                qem_send_confirmation ($auto,$message[$i],$content,$register,$event);
            }
        }
        $message = array_values($message);
        update_option('qem_messages_'.$event, $message ); 
        qem_admin_notice('Selected registrations have been approved.');
    }

if( isset($_POST['qem_emaillist'])) {
    $event = $_POST["qem_download_form"];
    $title = $_POST["qem_download_title"];
    $message = get_option('qem_messages_'.$event);
    $register = get_custom_registration_form ();
    $number = get_post_meta($event, 'event_number', true);
    $content = qem_build_registration_table ($register,$message,'','','','');
    global $current_user;
    get_currentuserinfo();
    $qem_email = $current_user->user_email;
    $headers = "From: {<{$qem_email}>\r\n"
. "MIME-Version: 1.0\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
    wp_mail($qem_email, $title, $content, $headers);
    qem_admin_notice('Registration list has been sent to '.$qem_email.'.');
}
    
qem_generate_csv();

$content=$current=$all='';
$messageoptions = qem_get_stored_msg();
$$messageoptions['showevents'] = "checked";
$message = get_option('qem_messages_'.$event);
$places = get_option($event.'places');
if(!is_array($message)) $message = array();
$dashboard = '<div class="wrap">
<h1>Event Registation Report</h1>
<p><form method="post" action="">'.
qem_message_categories($category).'
&nbsp;&nbsp;'.
qem_get_eventlist ($event,$register,$messageoptions,$category).'
&nbsp;&nbsp;<b>Show:</b> <input style="margin:0; padding:0; border:none;" type="radio" name="showevents" value="all" ' . $all . ' /> All Events <input style="margin:0; padding:0; border:none;" type="radio" name="showevents" value="current" ' . $current . ' /> Current Events&nbsp;&nbsp;<input type="submit" name="changeoptions" class="button-secondary" value="Update options" />
</form>
</p>
<div id="qem-widget">
<form method="post" id="qem_download_form" action="">';
$content = qem_build_registration_table ($register,$message,'',$event);
if ($content) {
    $dashboard .= '<h2>'.$title.' | '.$date.'</h2>';
    $dashboard .= '<p>Event ID: '.$event.'</p>';
    $dashboard .= $content;
    $dashboard .='<input type="hidden" name="qem_download_form" value = "'.$event.'" />
    <input type="hidden" name="qem_download_title" value = "'.$title.'" />
    <input type="submit" name="qem_download_csv" class="button-primary" value="Export to CSV" />
    <input type="submit" name="qem_emaillist" class="button-primary" value="Email List" />
    <input type="submit" name="qem_reset_message" class="button-secondary" value="Delete All Registrants" onclick="return window.confirm( \'Are you sure you want to delete all the registrants for '.$title.'?\' );"/>
    <input type="submit" name="qem_delete_selected" class="button-secondary" value="Delete Selected" onclick="return window.confirm( \'Are you sure you want to delete the selected registrants?\' );"/>';
if ($register['moderate']) $dashboard .= '<input type="submit" name="qem_approve_selected" class="button-secondary" value="Approve Selected" onclick="return window.confirm( \'Are you sure you want to approve the selected registrants?\' );"/>';
$dashboard .= '</form>';
}
else $dashboard .= $noregistration;
$dashboard .= '</div></div>';		
echo $dashboard;
}

function qem_get_eventlist ($event,$register,$messageoptions,$thecat) {
    global $post;
    $arr = get_categories();
    $content=$slug='';
    foreach($arr as $option) if ($thecat == $option->slug) $slug = $option->slug;
    $content .= '<select name="eventid" onchange="this.form.submit()"><option value="">Select an Event</option>'."\r\t";
    $args = array('post_type'=> 'event','orderby'=>'title','order'=>'ASC','posts_per_page'=> -1,'category_name'=>$slug);
    $today = strtotime(date('Y-m-d'));
    query_posts( $args );
    if ( have_posts()){
        while (have_posts()) {
            the_post();
            $title = get_the_title();
            $id = get_the_id();
            $unixtime = get_post_meta($post->ID, 'event_date', true);
            $date = date_i18n("d M Y", $unixtime);
            if ($register['useform'] || get_event_field("event_register") && ($messageoptions['showevents'] == 'all' || $unixtime >= $today) ) 
                $content .= '<option value="'.$id.'">'.$title.' | '.$date.'</option>';
        }
        $content .= '</select>
        <noscript><input type="submit" name="select_event" class="button-primary" value="Select Event" /></noscript>';
    }
    return $content;
}

function qem_message_categories ($thecat) {
    $arr = get_categories();
    $content = '<select name="category" onchange="this.form.submit()">
<option value="">All Categories</option>';
    foreach($arr as $option) {
        if ($thecat == $option->slug) $selected = 'selected'; else $selected = '';
        $content .= '<option value="'.$option->slug.'" '.$selected.'>'.$option->name.'</option>';
    }
    $content .= '</select>';
    return $content;
}

function qem_get_stored_msg () {
    $messageoptions = get_option('qem_messageoptions');
    if(!is_array($messageoptions)) $messageoptions = array();
    $default = array(
        'showevents' => 'current',
        'messageorder' => 'newest'
    );
    $messageoptions = array_merge($default, $messageoptions);
    return $messageoptions;
}