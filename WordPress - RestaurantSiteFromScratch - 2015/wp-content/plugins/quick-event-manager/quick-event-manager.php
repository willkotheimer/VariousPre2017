<?php
/*
Plugin Name: Quick Event Manager
Plugin URI: http://www.quick-plugins.com/quick-event-manager
Description: A simple event manager. There is nothing to configure, all you need is an event and the shortcode.
Version: 7.0
Author: aerin
Author URI: http://www.quick-plugins.com
Text Domain: quick-event-manager
Domain Path: /languages
*/

require_once( plugin_dir_path( __FILE__ ) . '/quick-event-options.php' );
require_once( plugin_dir_path( __FILE__ ) . '/quick-event-akismet.php' );
require_once( plugin_dir_path( __FILE__ ) . '/quick-event-register.php' );
require_once( plugin_dir_path( __FILE__ ) . '/quick-event-payments.php' );
require_once( plugin_dir_path( __FILE__ ) . '/quick-event-widget.php' );
$qem_calendars = 0;
/*
	Add: wordpress hooks for ajax for calendar
*/
add_action( 'wp_ajax_qem_ajax_calendar', 'qem_ajax_calendar');
add_action( 'wp_ajax_nopriv_qem_ajax_calendar', 'qem_ajax_calendar');

/*
	Add: qem_ajax_calendar
*/
function qem_ajax_calendar() {
	echo qem_show_calendar($_POST['atts']);
}

if (is_admin()) {
    require_once( plugin_dir_path( __FILE__ ) . '/settings.php' );
    require_once( plugin_dir_path( __FILE__ ) . '/quick-event-editor.php' );
}

add_shortcode('qem','qem_event_shortcode');
add_shortcode('qem-calendar', 'qem_show_calendar');
add_shortcode('qemcalendar', 'qem_show_calendar');
add_shortcode('qemnewevent', 'qem_user_event');
add_shortcode('qemregistration', 'qem_loop');

add_action('wp_enqueue_scripts','qem_enqueue_scripts');
add_action('init', 'event_register');
add_action("widgets_init", create_function('', 'return register_widget("qem_widget");') );
add_action("widgets_init", create_function('', 'return register_widget("qem_calendar_widget");') );
add_action('plugins_loaded', 'qem_lang_init');
add_action( 'wp_head', 'qem_head_css' );

add_filter("plugin_action_links","event_plugin_action_links", 10, 2 );
add_filter( 'pre_get_posts', 'qem_add_custom_types' );
add_filter('wp_dropdown_users', 'qem_users');

add_theme_support('post-thumbnails', array('post', 'page', 'event'));

register_activation_hook(__FILE__, 'qem_flush_rules');

function event_register() {
    load_plugin_textdomain('quick-event-manager', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
    qem_generate_csv();
    if (!post_type_exists( 'event' ) ) {
        $labels = array(
            'name'=> _x('Events', 'post type general name', 'quick-event-manager'),
            'singular_name' => _x('Event', 'post type singular name', 'quick-event-manager'),
            'add_new'=> _x('Add New', 'event', 'quick-event-manager'),
            'add_new_item'=> __('Add New Event', 'quick-event-manager'),
            'edit_item'=> __('Edit Event', 'quick-event-manager'),
            'new_item'=> __('New Event', 'quick-event-manager'),
            'view_item'=> __('View Event', 'quick-event-manager'),
            'search_items'=> __('Search event', 'quick-event-manager'),
            'not_found'=>  __('Nothing found', 'quick-event-manager'),
            'not_found_in_trash'=> __('Nothing found in Trash', 'quick-event-manager'),
            'parent_item_colon'=> ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'publicly_queryable'=> true,
            'exclude_from_search'=> false,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => true,
            'show_in_menu' => true,
            'capability_type' => array('event','events'),
            'map_meta_cap' => true,
            'hierarchical' => false,
            'has_archive' => true,
            'menu_position'	=> null,
            'taxonomies' => array('category','post_tag'),
            'supports' => array('title','editor','author','thumbnail','comments','excerpt'),
            'show_ui' => true,
        );
        register_post_type('event',$args);
    }
}

function qem_event_shortcode($atts,$widget) {
    $listlink = $usecategory = $remaining=$all=$i=$monthnumber=$archive=$yearnumber=$daynumber=$thisday=$content='';
    $atts = shortcode_atts(array(
        'fullevent'=>'',
        'id'=> '',
        'posts'=> '99',
        'links'=>'checked',
        'daterange'=>'current',
        'size'=>'',
        'headersize'=>'headtwo',
        'settings'=>'checked',
        'vanillawidget'=>'checked',
        'images'=>'',
        'category'=>'',
        'categoryplaces' => '',
        'order'=>'',
        'fields'=>'',
        'listlink'=>'',
        'listlinkanchor'=>'',
        'listlinkurl'=>'',
        'cb'=>'',
        'y'=>'',
        'vw' => '',
        'categorykeyabove'=>'',
        'categorykeybelow'=>'',
        'usecategory'=>'',
        'event'=>'',
        'popup'=>'',
        'fullevent' => 'summary',
        'fullpopup' => '',
        'calendar' => '',
        'thisisapopup' => false,
        'listplaces' => true,
        'fulllist' => false,
        'grid' => '',
        'eventfull' => ''
    ),$atts,'qem');
    global $post;
    global $_GET;
    $category = $atts['category'];
    if (isset($_GET['category'])) $category = $_GET['category'];
    $display = event_get_stored_display();
    $atts['popup'] = $display['linkpopup'];
    $atts['widget'] = $widget;
    
    if ($display['fullpopup'] && $display['linkpopup']) $atts['fullpopup'] = 'checked';

    if ($atts['fullevent'] == 'on' || $display['fullevent']) $atts['fulllist'] = true;

    $cal = qem_get_stored_calendar();
    $style = qem_get_stored_style();
    if (!$atts['listlinkurl']) $atts['listlinkurl'] = $display['back_to_url'];
    if (!$atts['listlinkanchor']) $atts['listlinkanchor'] = $display['back_to_list_caption'];
    if ($atts['listlink']) $atts['listlink']='checked';
    if ($atts['cb']) $display['cat_border'] = 'checked';
    ob_start();
    if ($display['event_descending'] || $atts['order'] == 'asc')
        $args = array(
        'post_type'=> 'event',
        'orderby' => 'meta_value_num',
        'meta_key' => 'event_date',
        'posts_per_page'=> -1
    );
    else
        $args = array(
        'post_type'=>'event',
        'orderby'=>'meta_value_num',
        'meta_key'=>'event_date',
        'order'=> 'asc',
        'posts_per_page'=> -1
    );
    $the_query = new WP_Query( $args );
    $event_found = false;
    $today = strtotime(date('Y-m-d'));
    $catlabel = str_replace(',',', ',$category);
    if ($atts['usecategory']) $atts['cb'] = 'checked';
    
    if (!$widget && $display['cat_border'] && $display['showkeyabove']) $content .= qem_category_key($cal,$style,'');
    if ($widget && $atts['usecategory'] && $atts['categorykeyabove']) $content .= qem_category_key($cal,$style,'');
    if ($category && $display['showcategory']) $content .= '<h2>'.$display['showcategorycaption'].' '.$catlabel.'</h2>';
    
    if ($display['eventmasonry'] == 'masonry' && !$atts['widget']) $atts['grid'] = 'masonry';
    
    if ($atts['grid'] == 'masonry') $content .= '<div id="qem">';
    
    if ($atts['id'] == 'all') $all = 'all';
    if ($atts['id'] == 'current') $monthnumber = date('n');
    if ($atts['id'] == 'remaining') $remaining = date('n');
    if ($atts['id'] == 'archive') $archive = 'archive';
    if (is_numeric($atts['id'])) $monthnumber = $atts['id'];
    if (is_numeric($atts['id']) && strlen($atts['id']) == 4) $yearnumber = $atts['id'];
    if ($atts['id'] == 'calendar') {
        if (isset($_GET['qemmonth'])) {$monthnumber = $_GET['qemmonth'];}
        else $monthnumber = date('n');
    }
    $thisyear = date('Y');$thismonth = date("M");$currentmonth = date("M");
    if ($atts['id'] == 'today') {
        $daynumber = date("d");
        $todaymonth = $thismonth;
    }
    if (strpos($atts['id'],'D') !== false) {$daynumber = filter_var($atts['id'], FILTER_SANITIZE_NUMBER_INT);}
    if (strpos($atts['id'],'M') !== false) {
        $dm = explode("D",$atts['id']);
        $monthnumber = filter_var($dm[0], FILTER_SANITIZE_NUMBER_INT);
        $daynumber = filter_var($dm[1], FILTER_SANITIZE_NUMBER_INT);
	}
    
    if ($category) $category = explode(',',$category);
    if ($atts['event']) $eventid = explode(',',$atts['event']);
    
    if ( $the_query->have_posts()){
        if ($cal['connect']) $content .='<p><a href="'.$cal['calendar_url'].'">'.$cal['calendar_text'].'</a></p>';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $unixtime = get_post_meta($post->ID, 'event_date', true);
            if (!$unixtime) $unixtime = time();
            $enddate = get_post_meta($post->ID, 'event_end_date', true);
            $hide_event = get_post_meta($post->ID, 'hide_event', true);
            $day = date("d", $unixtime);
            $monthnow = date("n", $unixtime);
            $eventmonth = date("M", $unixtime);
            $eventmonthnumber = date("m", $unixtime);
            $month = ($display['monthtype'] == 'short' ? date_i18n("M", $unixtime) : date_i18n("F", $unixtime));
            $year = date("Y", $unixtime);
            
            if ($atts['y']) {
                $thisyear = $atts['y'];$yearnumber = 0;
            }
            
            if ($atts['event']) {
                $atts['id'] = 'event';
                $event = $post->ID;
                $eventbyid = (in_array($event,$eventid) ? 'checked' : '');
            }
            if ($i < $atts['posts']) {
                if (//All Events
                    (($all ||
                    // Event by ID
                    $atts['event'] && $eventbyid) ||
                    // Archive Events
                    ($archive && $unixtime < $today && $enddate < $today) ||
                    // All if no ID
                    ($atts['id'] == '' && ($unixtime >= $today || $enddate >= $today || $display['event_archive'] == 'checked')) ||
                    // Today only
                    ($daynumber == $day && $todaymonth == $eventmonth && $thisyear == $year) ||
                    // Day and Month
                    ($daynumber == $day && $monthnumber == $eventmonthnumber && $thisyear == $year) ||
                    // This month
                    (!$daynumber &&$monthnumber && $monthnow == $monthnumber && $thisyear == $year) ||
                    // Rest of the month
                    ($remaining && $monthnow == $remaining && $thisyear == $year && ($unixtime >= $today || $enddate >= $today )) ||
                    // This year
                    ($yearnumber && $yearnumber == $year)) &&
                    // This category
                    (in_category($category) || !$category) 
                ) {
                    if (!$atts['grid'] && $display['monthheading'] && ($currentmonth || $month != $thismonth || $year != $thisyear)) {
                        $content .='<h2>'.$month.' '.$year.'</h2>';
                        $thismonth = $month;
                        $thisyear = $year;
                    $currentmonth = '';
                    }
                    if (!$hide_event)
                        $content .= qem_event_construct ($atts)."\r\n";
                    $event_found = true;
                    $i++;
                    if ($display['norepeat']) $thisday = $day;
                }
            }
        }
        
        if ($atts['grid'] == 'masonry') $content .= '</div>';
        if (!$widget && $display['cat_border'] && $display['showkeybelow']) $content .= qem_category_key($cal,$style,'');
        if ($widget && $atts['usecategory'] && $atts['categorykeyabove']) $content .= qem_category_key($cal,$style,'');
        if ($atts['listlink']) $content .= '<p><a href="'.$atts['listlinkurl'].'">'.$atts['listlinkanchor'].'</a></p>';

        echo $content;
    }
    if (!$event_found) echo "<h2>".$display['noevent']."</h2>";
    wp_reset_postdata();
    wp_reset_query();
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}

// Manages the categories

function qem_category_key($cal,$style,$calendar) {
    $cat = array('a','b','c','d','e','f','g','h','i','j');
    $arr = get_categories();
    $display = event_get_stored_display();
    $pageurl = qem_current_page_url();
    $parts = explode("&",$pageurl);
    $pageurl = $parts['0'];
    $link = (strpos($pageurl,'?') ? '&' : '?');
    $uncatkey = $caturl = $allcats = '';
    if ($style['linktocategories']) {
        $catkey = '<style>.qem-category:hover {background: #CCC !important;border-color: #343848 !important;}.qem-category a:hover {color:#383848 !important;}</style>'."\r\n";
    }
    
    $catkey .= ($calendar ? '<p><span class="qem-caption">'.$cal['keycaption'].'</span>' : '<p><span class="qem-caption">'.$display['keycaption'].'</span>');
    
    if ($calendar && $cal['calallevents']) {
        $allcats = $cal['calalleventscaption'];
        $caturl = $cal['calendar_url'];
    }
      if (!$calendar && $display['catallevents']) {
        $allcats = $display['catalleventscaption'];
        $caturl = $display['back_to_url'];
    }
    if ($style['event_background'] == 'bgwhite') $eventbackground = 'background:white;';
	if ($style['event_background'] == 'bgcolor') $eventbackground = 'background:'.$style['event_backgroundhex'].';';
    
    if ($cal['linktocategories'] && $caturl && $allcats) {
        $bg = ($style['date_background'] == 'color' ? $style['date_backgroundhex'] : $style['date_background']);
        $catkey .= '<span class="qem-category" style="border:'.$style['date_border_width'].'px solid '.$style['date_border_colour'].';background:'.$bg.'"><a style="color:'.$style['date_colour'].'" href="'.$caturl.'">'.$allcats.'</a></span>';
    }
    
	$class = 'class="qem-category qem-key"';
    foreach ($cat as $i) {
        foreach($arr as $option) if ($style['cat'.$i] == $option->slug) $thecat = $option->name;
        if ($style['cat'.$i]) {
            if ($calendar) {
                if ($cal['linktocategories']) {
                    $catkey .= '<span '.$class.' style="border:'.$style['date_border_width'].'px solid '.$style['cat'.$i.'text'].';background:'.$style['cat'.$i.'back'].'"><a style="color:'.$style['cat'.$i.'text'].'" href="'.$pageurl.$link.'category='.$thecat.'">'.$thecat.'</a></span>';
                } else {
                    $catkey .= '<span '.$class.' style="border:'.$style['date_border_width'].'px solid '.$cal['cat'.$i.'text'].';background:'.$style['cat'.$i.'back'].';color:'.$style['cat'.$i.'text'].';">'.$thecat.'</span>';
                }
                if ($style['showuncategorised'])
                    $uncatkey = '<span '.$class.' style="border:'.$style['date_border_width'].'px solid '.$style['date_border_colour'].';">Uncategorised</span>';
            } else {
                if ($display['linktocategories']) {
                    $catkey .= '<span '.$class.' style="'.$eventbackground.';border:'.$style['date_border_width'].'px solid '.$style['cat'.$i.'back'].';"><a href="'.$pageurl.$link.'category='.$thecat.'">'.$thecat.'</a></span>';
                } else {
                    $catkey .= '<span '.$class.' style="'.$eventbackground.';border:'.$style['date_border_width'].'px solid '.$style['cat'.$i.'back'].';">'.$thecat.'</span>';
                }
                if ($display['showuncategorised'])
                    $uncatkey = '<span '.$class.' style="border:'.$style['date_border_width'].'px solid '.$style['date_border_colour'].';">Uncategorise
d</span>';
            }
        }
    }
    $catkey .= $uncatkey.'</p><div style="clear:left;"></div>'."\r\n";
    return $catkey;
}

// Builds the event page

function qem_event_construct ($atts) { 
    global $post;
    $event = event_get_stored_options();
    $display = event_get_stored_display();
    $vertical = $display['vertical'];
    $style = qem_get_stored_style();
    $cal = qem_get_stored_calendar();
    $custom = get_post_custom();
    $link = get_post_meta($post->ID, 'event_link', true);
    $endtime = get_post_meta($post->ID, 'event_end_time', true);
    $endmonth=$amalgamated=$target='';
    $unixtime = get_post_meta($post->ID, 'event_date', true);
    $day = date_i18n("d", $unixtime);
    $enddate = get_post_meta($post->ID, 'event_end_date', true);
    $image = get_post_meta($post->ID, 'event_image', true);
    $register = qem_get_stored_register();
    $payment = qem_get_stored_payment();
    $cost = get_post_meta($post->ID, 'event_cost', true);
    $atts['usereg'] = get_post_meta($post->ID, 'event_register', true);
    if ($atts['usereg']) $event['summary']['field12'] = true;
    
    $usecounter = get_post_meta($post->ID, 'event_number', true);
    $usepay = get_post_meta($post->ID, 'event_pay', true);
    $rightcontent = $notfullpop = $hideattendance = '';
    $today = strtotime(date('Y-m-d'));
    $clear = $eventfull = $cutoff = $fullcontent = $titlecat = $datecat = $linkopen = $linkclose = $regform = '';
    
    
    if (!is_singular ('event') && !$atts['widget'] && ($display['eventgrid'] || $atts['grid'])) {
        $grid = '-columns';
        if ($atts['grid'] == 'masonry') $grid = '-masonry';
        $display['map_in_list'] = '';
        $style['vanilla'] = 'checked';
        $nomap = true;
    }
    
    if ($display['fullevent']) {$atts['popup'] = false;}

    if (($display['loginlinks'] && !is_user_logged_in()) || is_singular ('event') || $atts['fulllist']) {
        $display['titlelink'] = true;
        $display['readmorelink'] = true;
    }

    if ($atts['widget'] && !$atts['links']) {
        $display['titlelink'] = true;
        $display['readmorelink'] = true;
    }

    // Build Category Information
    
    $category = get_the_category();
    $cat = ($category && ((!$atts['widget'] && $display['cat_border']) || $atts['cb'])  ? ' '.$category[0]->slug : ' ');
    
    if ($display['showcategory']) {
        if ($display['categorylocation'] == 'title') $titlecat = ' - '.$category[0]->name;
        if ($display['categorylocation'] == 'date') $datecat = ' - '.$category[0]->name;
    }
 
    if ($atts['categoryplaces'] && $category[0]->slug == $atts['categoryplaces']) {
        $register['eventlist'] = 'checked';
    }

    // Hide form for old events    
    
    if ($today > $unixtime && $register['notarchive']) {
        $atts['usereg'] ='';
    }

    // No images    

    if ($atts['images'] == 'off') {
        $image='';
    }

    // Clear Icon styling from widget
    
    if ($atts['vw']) {
        $style['vanillawidget'] = 'checked';
    }

    // Field override

    if ($atts['fields']) {
        foreach (explode( ',',$event['sort']) as $name) $event['summary'][$name] = '';
        $derek = explode( ',',$atts['fields']);
        $event['sort'] = '';
        foreach ($derek as $item) {
            $event['summary']['field'.$item] = 'checked';
            $event['sort'] = $event['sort'].'field'.$item.',';
        }
    }

    // Link externally

    if ($display['external_link'] && $link) {
        add_filter( 'post_type_link', 'qem_external_permalink', 10, 2 );
    }

    // Build pop up

    if ($atts['popup'] && !$display['fullevent']) {
        $popupcontent = get_event_popup($atts);
    }

    // Combine end date

    if (($display['show_end_date'] && $enddate) || ($enddate && $atts['eventfull'])) {
        $join = 'checked';
    } else {
        $join='';
    }

    // Set size of icon

    if($atts['size']) {
        $width = '-'.$atts['size'];
    } else {
        $atts['size'] = $style['calender_size']; 
        $width = '-'.$style['calender_size'];
    }

    // Set header size

    $h = ($atts['headersize'] == 'headthree' ? 'h3' : 'h2');

    // Build title link

    if ($display['catalinkslug']) $category = explode(',',$display['catalinkslug']);
    
    if (!$display['titlelink']) {
        $linkclose = '</a>';
        if ($atts['popup']) {
            $linkopen = '<a href="javascript:xlightbox(\''.$popupcontent.'\'); ">';
        } else {
            if ($category && in_category($category)) {
                $linkopen = '<a href="' . $display['catalinkurl'] . '"'.$target.'>';
            } else {
                $linkopen = '<a href="' . get_permalink() . '"'.$target.'>';
            }
        }
    }

    // Test for date amalgamation

    if ($display['amalgamated']) {
        $month = date_i18n("M", $unixtime);
        $year = date_i18n("Y", $unixtime);
        if ($enddate) {
            $endmonth = date_i18n("M", $enddate);
            $endday = date_i18n("d", $enddate);
            $endyear = date_i18n("Y", $enddate);
        }
        if ($month == $endmonth && $year == $endyear && $endday) {
            $amalgamated = 'checked';
        }
    }

    // Start Content creation
    
    $content = '<div class="qem'.$grid.$cat.'">';

    // Build data icon
    
    if ((!$style['vanilla'] && !$style['vanillawidget']) || (!$style['vanilla'] && $style['vanillawidget'] && !$atts['widget'] )) {
            $content .= '<div class="qem-icon">'.get_event_calendar_icon($atts,'event_date',$join,$style);
            if ($join && !$amalgamated && !$vertical) 
                $content .= '</div><div class="qem-icon">';
            if(($display['show_end_date'] || $atts['eventfull']) && !$amalgamated) 
                $content .= get_event_calendar_icon($atts,'event_end_date','',$style);
            $content .= '</div>';
        $content .= '<div class="qem'.$width.'">';
        $clear = '<div style="clear:both"></div></div>';
    }

    // Add image

    if ($image) {
        $imageclass = ($atts['grid']  ? 'qem-grid-image' : 'qem-list-image');
        if ($display['event_image'] && !is_singular ('event') && !$atts['widget'])
            $rightcontent = $linkopen.'<img class="'.$imageclass.'" src="'.$image.'">'.$linkclose.'<br>';
        if ( $atts['fullevent'] == 'fullevent' || $atts['thisisapopup'] || $atts['fulllist']) 
            $rightcontent = '<img class="qem-image" src="'.$image.'"><br>';
    }

    // Add map

    if (!$nomap && function_exists('file_get_contents') && (($atts['fullevent'] && $atts['thisisapopup']) || $atts['fulllist'] || $display['map_in_list'] || ($display['map_and_image'] && $display['map_in_list']) || (is_singular ('event') && !$atts['widget']))) {
        if (is_singular ('event')) $mapwidth = $display['event_image_width'];
        if ($display['map_in_list'] && !is_singular ('event')) $mapwidth = $display['image_width'];
        $j = preg_split('#(?<=\d)(?=[a-z%])#i', $mapwidth);
        if (!$j[0]) $j[0] = '300';
        $mapwidth = $j[0];
        $rightcontent .= get_event_map($mapwidth);
    }

    // Add form (if on the right)    
    
    if (!$atts['widget'] &&  ($atts['fulllist'] || is_singular ('event')) && !$atts['thisisapopup'] && ((($register['useform'] && $atts['usereg']) || $atts['usereg'] ) && $register['ontheright'])) {
        $rightcontent .= '<div class="qem-rightregister">'.qem_loop().'</div>';
        $thereisaform = true;
    }

    // Build right content
    
    $gridclass = ($atts['grid']  ? 'qemgridright' : 'qemlistright');    
    if ($rightcontent) {
        if ( is_singular ('event') || $atts['thisisapopup'] || $atts['fulllist']) 
            $content .= '<div class="qemright">'."\r\n".$rightcontent."\r\n".'</div>'."\r\n";
        else $content .= '<div class="'.$gridclass.'">'."\r\n".$rightcontent."\r\n".'</div>'."\r\n";
    }

    // Build event title link    
    
    if (!is_singular ('event') || $atts['widget']) {
        $content .= '<'.$h.' class="qem_title">'. $linkopen . $post->post_title . $titlecat . $linkclose . '</'.$h.'>';
    }

    // Build vanilla date
   
    if ($style['vanilla'] || ($style['vanillawidget'] && $atts['widget'])) {
        $content .= '<h3 class="qem_date">'.get_event_calendar_icon($atts,'event_date',$join,$style);
        if(($display['show_end_date'] || $atts['eventfull']) && !$amalgamated) {
            $content .= get_event_calendar_icon($atts,'event_end_date','',$style);
        }
        $content .= $datecat.'</h3>';
    }
    
    // Build event content

    if ($atts['calendar'] && !$atts['fullpopup']) $notfullpop = 'checked';
    if ($atts['listplaces'] && !$atts['fullpopup']) $notfullpop = 'checked';
    
    if  ((is_singular ('event') && !$atts['widget']) || ($atts['thisisapopup'] && !$notfullpop) || $atts['fulllist']) {
        foreach (explode( ',',$event['sort']) as $name) {
            if ($event['active_buttons'][$name]) {
                $content .= qem_build_event($name,$event,$display,$custom,$atts,$register,$payment);
            }
        }
        // if((!is_singular ('event') || $atts['widget']) && $register['eventlist']) $content .= qem_places ($register,$payment,$post->ID,$usecounter,$atts);
        /*
        if (!$atts['popup'] && (($register['useform'] && $usereg) || $usereg ) && !$register['ontheright'] && ( is_singular ('event') && !$atts['widget'])) {
            $content.= qem_loop();
            $thereisaform = true;
        }
        */
    } else {
        foreach (explode( ',',$event['sort']) as $name) {
            if ($event['summary'][$name] == 'checked') {
                $content .= qem_build_event($name,$event,$display,$custom,$atts,$register,$payment);
            }
        }
    }

    // Add ICS button to list and event

    if (($display['uselistics'] && !is_singular ('event')) || ($display['useics'] && !$atts['widget']&& is_singular ('event'))) {
        $content .= qem_ics();
    }

    // Add Read More

    if ((!is_singular ('event') && !$atts['widget']) || ($atts['widget'] && $atts['links'])) {
        
        $number = get_post_meta($post->ID, 'event_number', true);
        $num = qem_numberscoming($post->ID);
        $cutoffdate = get_post_meta($post->ID, 'event_cutoff_date', true);
        
        if ($atts['usereg']) $regform = true;
        
        $gotform = true;
        
        if (!$regform && $atts['thisisapopup'] && $atts['fullpopup']) $gotform = false;
        
        if ($cutoffdate && $cutoffdate < time()) $cutoff = 'checked';
        
        if (($number > 0 && $num == 0 && !$register['waitinglist']) || $cutoff) {
            $eventfull = true;
        }

        if ($atts['thisisapopup'] && $atts['fullpopup'] && $regform && !$eventfull) {
            $display['read_more'] = $register['title'];
        }

        if ($display['fullevent'] || $atts['widget'] || (($atts['fullevent'] == 'summary' || !$eventfull) && !$display['readmorelink'] && $gotform)) {
            if ($atts['popup']) {
                $readmoreopen = '<a href="javascript:xlightbox(\''.$popupcontent.'\'); ">';
            } else {
                $readmoreopen = '<a href="' . get_permalink() . '"'.$target.'>';
            }
            $content .= '<p class="readmore">'.$readmoreopen . $display['read_more'] . '</a></p>';
        }
    }

    // Add back to list link 

    if (!$atts['widget']) {
        if ($display['back_to_list']  && is_singular('event')) {
            if ($display['back_to_url']) {
                $content .= '<p><a href="'.$display['back_to_url'].'">'.$display['back_to_list_caption'].'</a></p>';
            } else {
                $content .= '<p><a href="javascript:history.go(-1)">'.$display['back_to_list_caption'].'</a></p>';
            }
        }
    }

// $content .= '<br>Full popup: '.$atts['fullpopup'].'<br>Widget: '.$atts['widget'].'<br>Is Popup: '.$atts['thisisapopup'].'<br>Not full pop: '.$notfullpop.'<br>Popup: '.$atts['popup'].'<br>Full Event:'.$atts['fullevent'].'<br>Event Full: '.$eventfull.'<br>Full List:'.$atts['fulllist'].'<br>Links: '.$atts['links'].'<br>Link List: '.$atts['listlink'].'<br>Reg form:'.$regform.'<br>Got Form: '.$gotform.'<br>Read More: '.$display['read_more'].'<br>';
   
    $content .= $clear."</div>";
    return $content;
}

// Builds the Calendar Icon

function get_event_calendar_icon($atts,$dateicon,$join,$style) {
    global $post;
    $width = $atts['size'];
    $vw = $atts['vw'];
    $widget = $atts['widget'];
    $display = event_get_stored_display();
    $vertical = $display['vertical'];
    $mrcombi = '2' * $style['date_border_width'].'px';
    $mr = '5' + $style['date_border_width'].'px';
    $mb = ($vertical ? ' 8px' : ' 0');
    $sep=$bor=$boldon=$italicon=$month=$italicoff=$boldoff=$endname=$amalgum=$bar = '';
    $tl = '-webkit-border-top-left-radius:0; -moz-border-top-left-radius:0; border-top-left-radius:0;';
    $tr = '-webkit-border-top-right-radius:0; -moz-border-top-right-radius:0; border-top-right-radius:0;';
    $bl = '-webkit-border-bottom-left-radius:0; -moz-border-bottom-left-radius:0; border-bottom-left-radius:0';
    $br = '-webkit-border-bottom-right-radius:0; -moz-border-bottom-right-radius:0; border-bottom-right-radius:0';
    if ($dateicon == 'event_date' && !$display['combined'] && !$vertical) $mb = ' '.$mr;
    if ($dateicon == 'event_end_date' && $display['combined'] && !$vertical) {
        $bar = $bor = '';
        $bar='style="border-left-width:1px;'.$tl.$bl.'"';
    }
    if ($style['date_bold']) {$boldon = '<b>'; $boldoff = '</b>';}
    if ($style['date_italic']) {$italicon = '<em>'; $italicoff = '</em>';}
    if ($vw) $style['vanillawidget'] = 'checked';
    $unixtime = get_post_meta($post->ID, $dateicon, true);
    $endtime = get_post_meta($post->ID, 'event_end_date', true);

    if ($unixtime){
        $month = date_i18n("M", $unixtime);
        if ($style['vanilla'] && $style['vanillamonth']) $month = date_i18n("F", $unixtime);
        $dayname = date_i18n("D", $unixtime);
        $day = date_i18n("d", $unixtime);
        $year = date_i18n("Y", $unixtime);
        if ($endtime && $display['amalgamated']){
            $endmonth = date_i18n("M", $endtime);
            if ($style['vanilla'] && $style['vanillamonth']) $endmonth = date_i18n("F", $endtime);
            $endday = date_i18n("d", $endtime);
            $endyear = date_i18n("Y", $endtime);
            if ($month == $endmonth && $year == $endyear && $endday && $dateicon != 'event_end_date') {
                if ($style['use_dayname']) $endname = date_i18n("D", $endtime).' ';
                $day = $day.' - '.$endname.$endday;
                $amalgum = 'on';
            }
        }
        if ($dateicon == 'event_date' && $display['combined'] && $join && !$amalgum) {
            $bar = $bor = '';
            $bar = 'style="border-right:none;'.$tr.$br.'"';
            $mr=' 0';
        }
        if ($style['iconorder'] == 'month') {
            $top = $month;
            $middle = $day;
            $bottom = $year;
        } elseif ($style['iconorder'] == 'year') {
            $top = $year;
            $middle = $day;
            $bottom = $month;
        } elseif ($style['iconorder'] == 'dm') {
            $top = $day;
            $middle = $month;
        } elseif ($style['iconorder'] == 'md') {
            $top = $month;
            $middle = $day;
        } else {
            $top = $day;
            $middle = $month;
            $bottom = $year;
        }
        $label = '';
        if ($dateicon == 'event_date' && $endtime && $style['uselabels']) $label = $style['startlabel'].'<br>';
        if ($dateicon == 'event_end_date' && $endtime && $style['uselabels']) $label = $style['finishlabel'].'<br>';
        if ($display['amalgamated'] && $amalgum ) $label= '';
        
        if ($style['vanilla'] || ($style['vanillawidget'] && $widget)) {
            if ($dateicon == 'event_end_date') $sep = '&nbsp; - &nbsp;';
            $content = $sep;
            if ($style['use_dayname']) $content .= $dayname.'&nbsp;';
            $content .= $top.'&nbsp;'.$middle.'&nbsp;'.$bottom;
        } else {
            $content = '<div class="qem-calendar-' . $width . '" style="margin:0 '.$mr.$mb.' 0;"><span class="day" '.$bar.'>'.$label;
            if ($style['use_dayname']) {
                $content .= $dayname;
                $content .= ($style['use_dayname_inline'] ? ' ' : '<br>');
            }
            $content .= $top.'</span><span class="nonday" '.$bar.'><span class="month">' .$boldon.$italicon.$middle.$italicoff.$boldoff. '</span><span class="year">'.$bottom.'</span></span></div>';
        }
        return $content;
    }
}

// Builds the event content

function qem_build_event ($name,$event,$display,$custom,$atts,$register,$payment) {
    global $post;
    $style = $output = $caption = $target = '';
    if ($atts['settings']){
        if ($event['bold'][$name] == 'checked') $style .= 'font-weight: bold; ';
        if ($event['italic'][$name] == 'checked') $style .= 'font-style: italic; ';
        if ($event['colour'][$name]) $style .= 'color: '. $event['colour'][$name] . '; ';
        if ($event['size'][$name]) $style .= 'font-size: ' . $event['size'][$name] . '%; ';
        if ($style) $style = 'style="' . $style . '" ';
    }
    
    switch ( $name ) {
        case 'field1':
        if (!empty($event['description_label'])) 
            $caption = $event['description_label'].' ';
        if (!empty ( $custom['event_desc'][0] ))
            $output .= '<p class="desc" ' . $style . '>' . $caption . $custom['event_desc'][0] . '</p>';
        break;
        case 'field2':
        if (!empty($custom['event_start'][0] )) {
            $output .= '<p class="start" ' . $style . '>' . $event['start_label'] . ' ' . $custom['event_start'][0];
            if (!empty($custom['event_finish'][0])) 
                $output .= ' '.$event['finish_label'].' '. $custom['event_finish'][0];
            if ($display['usetimezone'] && $custom['event_timezone'][0]) 
                $output .= ' '.$display['timezonebefore'].' '.$custom['event_timezone'][0].' '.$display['timezoneafter'];
            $output .= '</p>';}
        break;
        case 'field3':
        if (!empty($event['location_label']))
            $caption = $event['location_label'].' ';
        if (!empty ( $custom['event_location'][0] ))
            $output .= '<p class="location" ' . $style . '>' . $caption . $custom['event_location'][0]  . '</p>';
        break;
        case 'field4':
        if (!empty($event['address_label']))
            $caption = $event['address_label'].' ';
        if (!empty ($custom['event_address'][0] ))
            $output .= '<p class="address" ' . $style . '>' . $caption . $custom['event_address'][0]  . '</p>';
        break;
        case 'field5':
        if (!empty($event['url_label'])) 
            $caption = $event['url_label'].' ';
        if ($display['external_link_target']) 
            $target = 'target="_blank"';
        if (!preg_match("~^(?:f|ht)tps?://~i", $custom['event_link'][0])) 
            $url = 'http://' . $custom['event_link'][0]; 
        else  
            $url = $custom['event_link'][0];
        if (empty($custom['event_anchor'][0])) 
            $custom['event_anchor'][0] = $custom['event_link'][0];
        if (!empty ( $custom['event_link'][0] )) 
            $output .= '<p class="website" '.$style.'>'.$caption.'<a itemprop="url" '.$style .' '.$target.' href="'.$url.'">' .$custom['event_anchor'][0].'</a></p>';
        break;
        case 'field6':
        if (!empty($event['cost_label'])) 
            $caption = $event['cost_label'].' ';
        if (!empty ( $custom['event_cost'][0] )) 
            $output .= '<p ' . $style . '>' . $caption . $custom['event_cost'][0]  . '</p>';
        break;
        case 'field7':
        if (!empty($event['organiser_label'])) 
            $caption = $event['organiser_label'].' ';
        if (!empty ( $custom['event_organiser'][0] )) {
            $output .= '<p class="organisation" ' . $style . '>' . $caption . $custom['event_organiser'][0];
            if (!empty($custom['event_telephone'][0]) && $event['show_telephone']) 
            $output .= ' / ' . $custom['event_telephone'][0];
            $output .= '</p>';
        }
        break;
        case 'field8':
                $output .= get_the_content();
        break;
            
        case 'field9':
            $str = qem_get_the_numbers($post->ID,$payment);
            $number = get_post_meta($post->ID, 'event_number', true);
            if ($number && $str > $number) $str = $number;
            if ($str) {
                if ($str == 1) $str = $event['oneattendingbefore'];
                else $str = $event['numberattendingbefore'].' '.$str.' '.$event['numberattendingafter'];
                $output .= '<p id="whoscoming" class="totalcoming" ' . $style . '>'.$str.'</p>';
            } 
        break;
        case 'field10':
            $number = get_post_meta($post->ID, 'event_number', true);
            $num = $str= $grav = $content = '';
            $whoscoming = get_option('qem_messages_'.$post->ID);
            if ($whoscoming) {
                foreach($whoscoming as $item) {
                    $num = $num + $item['yourplaces'];
                    $ipn = qem_check_ipnblock($payment,$item);
                    if ((!$number || $num <= $number) && 
                        !$item['notattend'] &&
                        !$ipn &&
                        (($register['moderate'] && $item['approved']) || !$register['moderate'])) {
                            $str = $str.$item['yourname'].', ';
                            $grav = $grav.'<img title="'.$item['yourname'].'" src="http://www.gravatar.com/avatar/' . md5($item['youremail']) . '?s=40&&d=identicon" /> ';
                    }
                }
                $str = substr($str, 0, -2);
                if ($str && $event['whoscoming']) $content .= '<p id="whoscoming" ' . $style . '>'.$register['whoscomingmessage'].' '.$str.'</p>';
                if ($event['whosavatar']) $content .= '<p>'.$grav.'</p>';
                $output .= $content;
            }
        break;
        case 'field11':
            $usecounter = get_post_meta($post->ID, 'event_number', true);
            $num = qem_numberscoming($post->ID);
            $placesavailable = 'checked';
            if ($event['iflessthan'] && $num > $event['iflessthan']) $placesavailable = '';
            if ($register['waitinglist'] && $num == 0) {
                $output .= '<p id="whoscoming">'.$event['placesbefore'].' 0 '.$event['placesafter'].' <span id="waitinglistmessage">'.$event['waitinglistmessage'].'</span><p>';
            } elseif ($placesavailable) {
                $output .= '<p class="placesavailable" ' . $style . '>'.qem_places($register,$payment,$post->ID,$usecounter,$atts,$event).'</p>';
            }
        break;
        case 'field12':
            if (!$atts['popup'] && $atts['usereg'] && !$register['ontheright'] &&  is_singular ('event') && !$atts['widget'])
                $output .= qem_loop();
        break;
        case 'field13':
            if (!empty($event['category_label']))
            $caption = $event['category_label'].'&nbsp;';
            $categories = get_the_category();
            foreach($categories as $category) $cat_name = $cat_name.' '.$category->cat_name;
            $output .= '<p ' . $style . '>'.$caption.$cat_name.'</p>';
        break;
    }
    return $output;
}

/*
function qem_totalcoming($pid,$event) {
    $payment = qem_get_stored_payment();
    $str = qem_get_the_numbers($pid,$payment);
    $number = get_post_meta($pid, 'event_number', true);
    if ($number && $str > $number) $str = $number;
    if ($str) {
        $content = $event['numberattendingbefore'].' '.$str.' '.$event['numberattendingafter'];
        return $content;
    }
}
*/

// Check for IPN payment

function qem_check_ipnblock($payment,$item) {
    if	($payment['paypal'] && $payment['ipn'] && $payment['ipnblock'] && $item['ipn'] && $item['ipn'] != 'Paid') {
        return 'checked';
    }
}

// Calculates how many are coming 

function qem_get_the_numbers($pid,$payment) {
    $str = '';
    $register = qem_get_stored_register();    
    $whoscoming = get_option('qem_messages_'.$pid);
    if ($whoscoming)
        foreach($whoscoming as $item) {
        if (!qem_check_ipnblock($payment,$item) && !$item['notattend'] && (($register['moderate'] && $item['approved']) || !$register['moderate'] || ($register['moderate'] && $register['moderateplaces']))) {
            $str = $str + $item['yourplaces'];
        }
    }
    if ($str) return $str;
}

// Calculates how many places available

function qem_numberscoming($pid) {
    $register = get_custom_registration_form ();
    $payment = qem_get_stored_payment();
    $number = get_post_meta($pid, 'event_number', true);
    $attending = qem_get_the_numbers ($pid,$payment);
    $places = $number - $attending;
    if ($places >= 0) {
        return $places;
    } else {
        return 0;
    }
}

// Displays how many places available

function qem_places ($register,$payment,$pid,$usecounter,$atts,$event = array()) {
    $places = qem_numberscoming($pid);
    $cutoff = '';
    $cutoffdate = get_post_meta($pid, 'event_cutoff_date', true);
    if ($cutoffdate && $cutoffdate < time()) $cutoff = 'checked';
    if ($event['iflessthan'] && $places > $event['iflessthan']) return;
    if ($usecounter) {
        if ($places == 0 || $cutoff) {
            $content = $register['eventfullmessage'];
        } elseif ($places == 1) {
            $content = $event['oneplacebefore'];  
        } else {
            $content = $event['placesbefore'].' '.$places.' '.$event['placesafter'];
        }
    }
    return $content;
}

/*
function qem_whoscoming($register,$id,$payment) {
    $number = get_post_meta($id, 'event_number', true);
    $num = $str= $grav = $content = '';
    $whoscoming = get_option('qem_messages_'.$id);
    if ($register['whoscoming'] && $whoscoming) {
        foreach($whoscoming as $item) {
            $num = $num + $item['yourplaces'];
            $ipn = qem_check_ipnblock($payment,$item);
            if ((!$number || $num <= $number) && 
                !$item['notattend'] &&
                !$ipn &&
                (($register['moderate'] && $item['approved']) || !$register['moderate'])) {
                $str = $str.$item['yourname'].', ';
                $grav = $grav.'<img title="'.$item['yourname'].'" src="http://www.gravatar.com/avatar/' . md5($item['youremail']) . '?s=40&&d=identicon" />&nbsp;';
            }
        }
        $str = substr($str, 0, -2);
        if ($str) $content .= '<p id="whoscoming"><b>'.$register['whoscomingmessage'].'</b>'.$str.'</p>';
            if ($register['whosavatar']) {
            $content .= '<p>'.$grav.'</p>';
        }
        return $content;
    }
}
*/

// Generates the map

function get_event_map($mapwidth) {
    global $post;
    $event = event_get_stored_options();
    $display = event_get_stored_display();
    $mapurl = $target = '';
    if ($display['map_target']) $target = ' target="_blank" ';
    $custom = get_post_custom();
    if (($display['show_map'] == 'checked') && (!empty($custom['event_address'][0]))) {
        $map = str_replace(' ' ,'+',$custom['event_address'][0]);
        $mapurl .= '<div class="qemmap"><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=' . $map . '&amp;t=m" '.$target.'><img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $map . '&size=' . $mapwidth . 'x' . $display['map_height'] . '&markers=color:blue%7C'.$map.'&sensor=true" alt="'.$custom['event_address'][0].'" /></a></div>';
    }
    return $mapurl;
}

// Calendar symbol on small screens

function qem_widget_calendar($atts) {
    $arr =array(
        'arrow' => '\25B6',
        'square' => '\25A0',
        'box'=>'\20DE',
        'asterix'=>'\2605',
        'blank'=>' '
    );
    $smallicon = '';
    foreach ($arr as $item => $key) {
        if($item == $atts['smallicon']) {
            $smallicon = '#qem-calendar-widget .qemtrim span {display:none;}#qem-calendar-widget .qemtrim:after{content:"'.$key.'";font-size:150%;text-align:center}';
        }
    } 
    if ($atts['headerstyle']) $headerstyle = '#qem-calendar-widget '.$atts['header'].'{'.$atts['headerstyle'].'}';
    return '<div id="qem-calendar-widget"><style>'.$smallicon.' '.$headerstyle.'</style>'.qem_show_calendar($atts).'</div>'."\r\n";
}

/*
	Added function to normalize this action across most functions
*/
function qem_actual_link() {
	if (isset($_REQUEST['action'])) {
		$actual_link = explode('?',$_SERVER['HTTP_REFERER']);
		$actual_link = $actual_link[0];
	} else {
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}
	return $actual_link;
}

// Generates the months

function qem_calendar_months($cal) {
    $month = date_i18n("n");
    $year = date_i18n("Y");
    
    $actual_link = qem_actual_link();
    $parts = explode("&",$actual_link);
    $actual_link = $parts['0']; 
    $link = (strpos($actual_link,'?') ? '&' : '?');
    $reload = ($cal['jumpto'] ? '#qem_calreload' : '');
    
    $content = '<p>'.$cal['monthscaption'].'</p>
        <p class="clearfix">';
    for ($i = $month; $i<=12; $i++) {
        $monthname = date_i18n("M", mktime(0, 0, 0, $i, 10));
        $content .= '<span class="qem-category qem-month"><a href="'.$actual_link.$link.'qemmonth='. $i . '&amp;qemyear=' . $year . $reload.'">'.$monthname.'</a></span>';
    }
    $year = $year + 1;
    $month = $month -1;
    for ($i = 1; $i<=$month; $i++) {
        $monthname = date_i18n("M", mktime(0, 0, 0, $i, 10));
        $content .= '<span class="qem-category qem-month"><a href="'.$actual_link.$link.'qemmonth='. $i . '&amp;qemyear=' . $year . $reload.'">'.$monthname.'</a></span>';
    }
    $content .=  '</p>';
    return $content;
}

// Builds the calendar page

function qem_show_calendar($atts) {
	global $qem_calendars;
	$c = $qem_calendars++;
    $cal = qem_get_stored_calendar();
    $style = qem_get_stored_style();
	$category = '';
    extract(shortcode_atts($natts = array(
        'category'=>'',
        'navigation' => '',
        'month'=>'',
        'year'=>'',
        'links'=>'on',
        'categorykeyabove'=>'',
        'categorykeybelow'=>'',
        'usecategory'=>'',
        'smallicon'=>'trim',
        'widget'=>'',
        'header' => 'h2',
        'fullpopup' => '',
        'calendar' => 'checked',
        'thisisapopup' => false
    ),$atts));
    global $post;
    global $_GET;
    if (!$widget) $header = $cal['header'];
    if ($cal['hidenavigation']) $navigation = 'off';
    $reload = ($cal['jumpto'] ? '#qem_calreload' : '');
    if (isset($_REQUEST['category'])) $category = $_REQUEST['category']; 
    $args = array(
        'post_type' => 'event',
        'post_status' => 'publish',
        'orderby'=> 'meta_value_num',
        'meta_key' => 'event_date',
        'order' => 'asc',
        'posts_per_page' => -1,
        'category' => '',
        'links' => 'on'
    );
    
    $catarry = explode(",",$category);
    if ($cal['navicon'] == 'arrows') {
        $leftnavicon = '&#9668; ';
        $rightnavicon = ' &#9658;';
    }
    if ($cal['navicon'] == 'unicodes') {
        $leftnavicon = $cal['leftunicode'].' ';
        $rightnavicon = ' '.$cal['rightunicode'];
    }
    $monthnames = array();
    $monthstamp = 0;
    for ($i = 0; $i <= 12; $i++) {
        $monthnames[] = date_i18n('F', $monthstamp);	
        $monthstamp = strtotime('+1 month', $monthstamp);
    }
    if ($cal['startday'] == 'monday') $timestamp = strtotime('next sunday'); 
    if ($cal['startday'] == 'sunday')  $timestamp = strtotime('next saturday'); 
    $days = array();
    for ($i = 0; $i <= 7; $i++) {
        $days[] = date_i18n('D', $timestamp);
        $timestamp = strtotime('+1 day', $timestamp);
    }	
    
    if (!isset($_REQUEST["qemmonth"])) {
        if ($month) $_REQUEST["qemmonth"] = $month;
        else $_REQUEST["qemmonth"] = date_i18n("n");}
    if (!isset($_REQUEST["qemyear"])) {
        if ($year) $_REQUEST["qemyear"] = $year;
        else $_REQUEST["qemyear"] = date_i18n("Y");}
    $currentmonth = $_REQUEST["qemmonth"];
    $currentyear = $_REQUEST["qemyear"];
    $calendar = '<div class="qem_calendar" id="qem_calendar_'.$c.'"><a name="qem_calreload"></a>';
    
	/*
		Build attribute array into json object to use later
	*/
	$calendar .= "\r\n<script type='text/javascript'>\r\n";
	$calendar .= "	qem_calendar_atts[{$c}] = ".json_encode($natts).";\r\n";
	$calendar .= "	qem_month[{$c}] = {$currentmonth};\r\n";
	$calendar .= "	qem_year[{$c}] = {$currentyear};\r\n";
	$calendar .= "	qem_category[{$c}] = '{$category}';\r\n";
	$calendar .= "</script>\r\n";
	
    $p_year = $currentyear;
    $n_year = $currentyear;
    $p_month = $currentmonth-1;
    $n_month = $currentmonth+1;
    if ($p_month == 0 ) {$p_month = 12;$p_year = $currentyear - 1;}
    if ($n_month == 13 ) {$n_month = 1;$n_year = $currentyear + 1;};
    
    $atts['calendar'] = true;
    if ($cal['fullpopup']) $atts['fullpopup'] = 'checked';
    
    $qem_dates = array();
    $eventdate = array();
    $eventenddate = array();
    $eventtitle = array();
    $eventsummary = array();
    $eventlinks = array();
    $eventslug = array();
    $eventimage = array();
    $eventdesc = array();
    $eventx = false;
    $query = new WP_Query( $args );
    if ( $query->have_posts()) {
        while ( $query->have_posts()) {
            $query->the_post();
            if (in_category($catarry) || !$category) {
                $startdate = get_post_meta($post->ID, 'event_date', true);
                if (!$startdate) $startdate = time();
                $testmonth = date("m", $startdate);
                $testyear = date("Y", $startdate);
                
                $startdate = strtotime(date("d M Y", $startdate));
                $enddate = get_post_meta($post->ID, 'event_end_date', true);
                $image = get_post_meta($post->ID, 'event_image', true);
                $desc = get_post_meta($post->ID, 'event_desc', true);
                $link = get_permalink();
                
                $cat = get_the_category();
                if (!$cat) $cat = array();
                $slug = $cat[0]->slug;
                if ($cal['eventlink'] == 'linkpopup') $eventx = get_event_popup($atts);
                $title = get_the_title();
                if ($cal['showmultiple']){
                    do {
                        array_push($eventdate, $startdate);
                        array_push($eventtitle,$title);
                        array_push($eventslug,$slug);
                        array_push($eventsummary, $eventx);
                        array_push($eventlinks,$link);
                        $startdate = $startdate +(24*60*60);
                    } while ($startdate <= $enddate);
                } else {
                    array_push($eventdate, $startdate);
                    array_push($eventtitle,$title);
                    array_push($eventslug,$slug);
                    array_push($eventsummary, $eventx);
                    array_push($eventlinks,$link);
                    array_push($eventimage,$image);
                    array_push($eventdesc,$desc);
                }

            }
        }
    }
    wp_reset_postdata();
    wp_reset_query();

    if ($cal['connect']) 
        $calendar .='<p><a href="'.$cal['eventlist_url'].'">'.$cal['eventlist_text'].'</a></p>';
	
	$actual_link = qem_actual_link();	
    $parts = explode("&",$actual_link);
    $actual_link = $parts['0']; 
    $link = (strpos($actual_link,'?') ? '&' : '?');
    $catkey = qem_category_key($cal,$style,'calendar');
    if ($cal['showkeyabove'] || $categorykeyabove) $calendar .= $catkey;
    if ($cal['showmonthsabove']) $calendar .= qem_calendar_months($cal);
    
    // Build Category Information
    
    $category = $atts['category'];
    if (isset($_GET['category'])) $category = $_GET['category'];
    
    $catlabel = str_replace(',',', ',$category);
    
    if ($category && $display['showcategory']) $calendar .= '<h2>'.$display['showcategorycaption'].' '.$catlabel.'</h2>';
    
    $calendar .='<div id="qem-calendar">
    <table cellspacing="'.$cal['cellspacing'].'" cellpadding="0">
    <tr class="caltop">
    <td colspan="1" >';
    if ($navigation != 'off') $calendar .= '<a class="calnav" href="'.$actual_link.$link.'qemmonth='. $p_month . '&amp;qemyear=' . $p_year .$reload.'">' .$leftnavicon.$cal['prevmonth'].'</a>';
    $calendar .= '</td>
    <td colspan="5" class="calmonth"><'.$header.'>'. $monthnames[$currentmonth-1].' '.$currentyear .'</'.$header.'></td>
    <td colspan="1">';
    if ($navigation != 'off') $calendar .= '<a class="calnav" href="'.$actual_link.$link.'qemmonth='. $n_month . '&amp;qemyear=' . $n_year .$reload.'">'.$cal['nextmonth'].
        $rightnavicon.'</a>';
    $calendar .= '</td>
    </tr>
    <tr>'."\r\n";
    for($i=1;$i<=7;$i++) $calendar .= '<td class="calday">' . $days[$i] . '</td>';
    $calendar .= '</tr>'."\r\n";
    $timestamp = mktime(0,0,0,$currentmonth,1,$currentyear);
    $maxday = date_i18n("t",$timestamp);
    $thismonth = getdate($timestamp);
    if ($cal['startday'] == 'monday') {
        $startday = $thismonth['wday']-1;
        if ($startday=='-1') $startday='6';
    }
    else $startday = $thismonth['wday'];
    $firstday ='';$henry = $startday-1;
    for ($i=0; $i<($maxday+$startday); $i++) {
        $oldday ='';
        $blankday = ($i < $startday ? ' class="blankday" ' : '');
        $firstday = ($i == $startday-1 ? ' class="firstday" ' : '');
        $xxx = mktime(0,0,0,$currentmonth,$i - $startday+1,$currentyear);
        if (date_i18n("d") > $i - $startday+1 && $currentmonth <= date_i18n("n") && $currentyear == date_i18n("Y")) $oldday = 'oldday';
        if ($currentmonth < date_i18n("n") && $currentyear == date_i18n("Y"))
            $oldday = 'oldday';
        if ($currentyear < date_i18n("Y"))
            $oldday = 'oldday';
        if (($cal['archive'] && $oldday) || !$oldday) 
            $show = 'checked';
        else $show ='';
        $tdstart = '<td class="day '.$oldday.' '.$firstday.'"><'.$header.'>'.($i - $startday+1).'</'.$header.'><br>';
        $tdcontent  = $eventcontent = '';
        foreach ($eventdate as $key => $day) {
            $m=date('m', $day);$d=date('d', $day);$y=date('Y', $day);
            $zzz = mktime(0,0,0,$m,$d,$y);
            if($xxx==$zzz && $show) {
                $tdstart = '<td class="eventday '.$oldday.' '.$firstday.'"><'.$header.'>'.($i - $startday+1).'</'.$header.'>';
                $img = ($eventimage[$key] && $cal['eventimage'] && !$widget ? '<br><img src="'.$eventimage[$key].'">' : '');
                if ($cal['usetooltip']) {
                    $desc =  ($eventdesc[$key] ? ' - '.$eventdesc[$key] : '');
                    $tooltip = 'data-tooltip="'.$eventtitle[$key].$desc.'"';
                    $tooltipclass = (($i % 7) == 6 ? ' tooltip-left ' : '');
                    if ($widget) $tooltipclass = (($i % 7) > 2 ? ' tooltip-left ' : '');
                }
                $length = $cal['eventlength'];
                $short = $length - 3;
                $trim = (strlen($eventtitle[$key]) > $length) ? mb_substr($eventtitle[$key],0,$short, "utf-8").'...' : $eventtitle[$key];
                if ($cal['eventlink'] == 'linkpopup' ) {
                    $tdcontent .= '<a '.$tooltip.' class="event ' . $eventslug[$key] .$tooltipclass. '" href="javascript:xlightbox(\''.$eventsummary[$key].'\'); "><div class="qemtrim"><span>'.$trim.'</span>'.$img.'</div></a>';
                } else {
                    $eventcontent = '<a '.$tooltip.' class="'.$eventslug[$key].$tooltipclass.'" href="'.$eventlinks[$key].'"><div class="qemtrim"><span>'.$trim.'</span>'.$img.'</div></a>';
                    $tdcontent .= preg_replace( "/\r|\n/", "", $eventcontent );
                }
            }
        }
        $tdbuilt = $tdstart.$tdcontent.'</td>';
        if(($i % 7) == 0 ) $calendar .= "<tr>\r\t";
        if($i < $startday) $calendar .= '<td'.$firstday.$blankday.'></td>';
        else $calendar .= $tdbuilt;
        if(($i % 7) == 6 ) $calendar .= "</tr>"."\r\n";;
    }
    $calendar .= "</table></div>";
    if ($cal['showkeybelow'] || $categorykeybelow) $calendar .= $catkey;
    if ($cal['showmonthsbelow']) $calendar .= qem_calendar_months($cal);
    $eventdate = remove_empty($eventdate);
    return $calendar."</div>";
}

function remove_empty($array) {return array_filter($array, '_remove_empty_internal');}
function _remove_empty_internal($value) {return !empty($value) || $value === 0;}


// Creates the contant for the popup

function get_event_popup($atts) {
    $atts['links'] = 'checked';
    $atts['popup'] = '';
    $atts['linkpopup'] = '';
    $atts['thisday'] = '';
    $atts['fullevent'] = 'full';
    $atts['thisisapopup'] = true;
    if ($atts['listplaces'] && !$atts['fullpopup']) $atts['fullevent'] = 'summary';
    if ($atts['calendar'] && !$atts['fullpopup']) $atts['fullevent'] = 'summary';
    $output = qem_event_construct ($atts);
    $output = str_replace('"','&quot;',$output);
    $output = str_replace('<','&lt;',$output);
    $output = str_replace('>','&gt;',$output);
    $output = str_replace("'","&#8217;",$output);
    $output = str_replace("&#39;","&#8217;",$output);
    return $output;
}

// Builds the CSS

function qem_generate_css() {
    $style = qem_get_stored_style();
    $cal = qem_get_stored_calendar();
    $display = event_get_stored_display();
    $register = qem_get_stored_register();
    $script=$showeventborder=$formborder=$daycolor=$eventbold=$colour=$eventitalic='';
    if ($style['calender_size'] == 'small') $radius = 7;
    if ($style['calender_size'] == 'medium') $radius = 10;
    if ($style['calender_size'] == 'large') $radius = 15;
    $ssize=50 + (2*$style['date_border_width']).'px';
    $srm = $ssize+5+($style['date_border_width']).'px';
    $msize=70 + (2*$style['date_border_width']).'px';
    $mrm = $msize+5+($style['date_border_width']).'px';
    $lsize=90 + (2*$style['date_border_width']).'px';
    $lrm = $lsize+5+($style['date_border_width']).'px';
    if ($style['date_background'] == 'color') $color = $style['date_backgroundhex'];
    if ($style['date_background'] == 'grey') $color = '#343838';
	if ($style['date_background'] == 'red') $color = 'red';
    if ($style['month_background'] == 'colour') $colour = $style['month_backgroundhex'];
	else $colour = '#FFF';
	
    if ($style['event_background'] == 'bgwhite') $eventbackground = 'background:white;';
	if ($style['event_background'] == 'bgcolor') $eventbackground = 'background:'.$style['event_backgroundhex'].';';
    
    $formwidth = preg_split('#(?<=\d)(?=[a-z%])#i', $register['formwidth']);
    if (!$formwidth[0]) $formwidth[0] = '280';
    if (!$formwidth[1]) $formwidth[1] = 'px';
    $regwidth = $formwidth[0].$formwidth[1];

    $dayborder = 'color:' . $style['date_colour'].';background:'.$color.'; border: '. $style['date_border_width']. 'px solid ' .$style['date_border_colour'].';border-bottom:none;';
    
    $nondayborder = 'border: '. $style['date_border_width']. 'px solid ' .$style['date_border_colour'].';border-top:none;background:'.$colour.';';
    
    $monthcolor = 'span.month {color:'.$style['month_colour'].';}';
    
    $eventborder = 'border: '. $style['date_border_width']. 'px solid ' .$style['date_border_colour'].';';
	
    if ($style['icon_corners'] == 'rounded') {
        $dayborder = $dayborder.'-webkit-border-top-left-radius:'.$radius.'px; -moz-border-top-left-radius:'.$radius.'px; border-top-left-radius:'.$radius.'px; -webkit-border-top-right-radius:'.$radius.'px; -moz-border-top-right-radius:'.$radius.'px; border-top-right-radius:'.$radius.'px;';
        $nondayborder = $nondayborder.'-webkit-border-bottom-left-radius:'.$radius.'px; -moz-border-bottom-left-radius:'.$radius.'px; border-bottom-left-radius:'.$radius.'px; -webkit-border-bottom-right-radius:'.$radius.'px; -moz-border-bottom-right-radius:'.$radius.'px; border-bottom-right-radius:'.$radius.'px;';
        $eventborder = $eventborder.'-webkit-border-radius:'.$radius.'px; -moz-border-radius:'.$radius.'px; border-radius:'.$radius.'px;';
    }
    
    if ($style['event_border']) $showeventborder = 'padding:'.$radius.'px;'.$eventborder;
    if ($register['formborder']) $formborder = "\n.qem-register {".$eventborder."padding:".$radius."px;}";
    if ($style['widthtype'] == 'pixel') $eventwidth = preg_replace("/[^0-9]/", "", $style['width']) . 'px;';
    else $eventwidth = '100%';
    
    $j = preg_split('#(?<=\d)(?=[a-z%])#i', $display['event_image_width']);
    if (!$j[0]) $j[0] = '300';
    $i = $j[0].'px';
    
    if ($cal['eventbold']) $eventbold = 'font-weight:bold;';
    if ($cal['eventitalic']) $eventitalic = 'font-style:italic;';
    $ec = ($cal['event_corner'] == 'square' ? 0 : 3); 
    $script .= '.qem {width:'.$eventwidth.';'.$style['event_margin'].';}
.qem p {'.$style['line_margin'].';}
.qem p, .qem h2 {margin: 0 0 8px 0;padding:0;}'."\n";
    if ($style['font'] == 'plugin') {
        $script .= ".qem p {font-family: ".$style['font-family']."; font-size: ".$style['font-size'].";}
.qem h2, .qem h2 a {font-size: ".$style['header-size']." !important;color:".$style['header-colour']." !important}\n";
    }
    $arr =array('arrow' => '\25B6','square' => '\25A0','box'=>'\20DE','asterix'=>'\2605','blank'=>' ');    
    foreach ($arr as $item => $key)
        if($item == $cal['smallicon'])
            $script .= '#qem-calendar-widget h2 {font-size: 1em;}
#qem-calendar-widget .qemtrim span {display:none;}
#qem-calendar-widget .qemtrim:after{content:"'.$key.'";font-size:150%;}
@media only screen and (max-width: 480px) {
    .qemtrim span {display:none;}.qemtrim:after{content:"'.$key.'";font-size:150%;}
}'."\n";
        $script .= '.qem-small, .qem-medium, .qem-large {'.$showeventborder.$eventbackground.'}'
.$formborder.
".qem-register{max-width:".$regwidth.";}
.qem-register #submit {background: ".$register['submitbackground'].";}
.qem-register #submit:hover {background: ".$register['hoversubmitbackground'].";}
.qemright {max-width:".$display['max_width']."%;width:".$i.";height:auto;overflow:hidden;}
.qemlistright {max-width:".$display['max_width']."%;width:".$display['image_width']."px;height:auto;overflow:hidden;}
img.qem-image {width:100%;height:auto;overflow:hidden;}
img.qem-list-image {width:100%;height:auto;overflow:hidden;}
.qem-category {".$eventborder."}
.qem-icon .qem-calendar-small {width:".$ssize.";}
.qem-small {margin-left:".$srm.";}
.qem-icon .qem-calendar-medium {width:".$msize.";}
.qem-medium {margin-left:".$mrm.";}
.qem-icon .qem-calendar-large {width:".$lsize.";}
.qem-large {margin-left:".$lrm.";}
.qem-calendar-small .nonday, .qem-calendar-medium .nonday, .qem-calendar-large .nonday {display:block;".$nondayborder."}
.qem-calendar-small .day, .qem-calendar-medium .day, .qem-calendar-large .day {display:block;".$daycolor.$dayborder."}
.qem-calendar-small .month, .qem-calendar-medium .month, .qem-calendar-large .month {color:".$style['month_colour']."}
.qem-error { border-color: red !important; }
.qem-error-header { color: red !important; }
.qem-columns, .qem-masonry {border:".$display['eventgridborder'].";}
#qem-calendar ".$cal['header']." {margin: 0 0 8px 0;padding:0;".$cal['headerstyle']."}
#qem-calendar .calmonth {text-align:center;}
#qem-calendar .calday {background:".$cal['calday']."; color:".$cal['caldaytext']."}
#qem-calendar .day {background:".$cal['day'].";}
#qem-calendar .eventday {background:".$cal['eventday'].";}
#qem-calendar .eventday a {-webkit-border-radius:".$ec."px; -moz-border-radius:".$ec."px; border-radius:".$ec."px;color:".$cal['eventtext']." !important;background:".$cal['eventbackground']." !important;border:".$cal['eventborder']." !important;}
#qem-calendar .oldday {background:".$cal['oldday'].";}
#qem-calendar table border-collapse: separate;border-spacing:".$cal['cellspacing'].";}
#qem-calendar td a:hover {background:".$cal['eventhover']." !important;}
.qemtrim span {".$eventbold.$eventitalic."}
@media only screen and (max-width: 700px) {.qemtrim img {display:none;}}
@media only screen and (max-width: 480px) {.qem-large, .qem-medium {margin-left: 50px;}
    .qem-icon .qem-calendar-large, .qem-icon .qem-calendar-medium  {font-size: 80%;width: 40px;margin: 0 0 10px 0;padding: 0 0 2px 0;}
    .qem-icon .qem-calendar-large .day, .qem-icon .qem-calendar-medium .day {padding: 2px 0;}
    .qem-icon .qem-calendar-large .month, .qem-icon .qem-calendar-medium .month {font-size: 140%;padding: 2px 0;}
}";
    if ($cal['tdborder']) {
        if ($cal['cellspacing'] > 0) {
            $script .='#qem-calendar td.day, #qem-calendar td.eventday, #qem-calendar td.calday {border: '.$cal['tdborder'].';}';
        } else {
            $script .='#qem-calendar td.day, #qem-calendar td.eventday, #qem-calendar td.calday {border-left:none;border-top:none;border-right: '.$cal['tdborder'].';border-bottom: '.$cal['tdborder'].';}
#qem-calendar tr td.day:first-child,#qem-calendar tr td.eventday:first-child,#qem-calendar tr td.calday:first-child{border-left: '.$cal['tdborder'].';}'."\n".'
#qem-calendar tr td.calday{border-top: '.$cal['tdborder'].';}
#qem-calendar tr td.blankday {border-bottom: '.$cal['tdborder'].';}
#qem-calendar tr td.firstday {border-right: '.$cal['tdborder'].';border-bottom: '.$cal['tdborder'].';}';
        }
    }
    $lbmargin = $display['lightboxwidth']/2;
    $script .= '#xlightbox {width:'.$display['lightboxwidth'].'%;margin-left:-'.$lbmargin.'%;}
@media only screen and (max-width: 480px) {#xlightbox {width:90%;margin-left:-45%;}}';
    if ($register['ontheright'])
        $script .='.qem-register {width:100%;} .qem-rightregister {max-width:'.$i.'px;}';
    if ($style['use_custom'] == 'checked')
        $script .= $style['custom'];
    $cat = array('a','b','c','d','e','f','g','h','i','j');
    foreach ($cat as $i) {
        if ($style['cat'.$i]) {
            $eb = ($cal['fixeventborder'] || $cal['eventborder'] == 'none' ? '' : 'border:1px solid '.$style['cat'.$i.'text'].' !important;');
            $script .="#qem-calendar a.".$style['cat'.$i]." {background:".$style['cat'.$i.'back']." !important;color:".$style['cat'.$i.'text']." !important;".$eb."}";
            $script .='.'.$style['cat'.$i].' .qem-small, .'.$style['cat'.$i].' .qem-medium, .'.$style['cat'.$i].' .qem-large {border-color:'.$style['cat'.$i.'back'].';}
.'.$style['cat'.$i].' .qem-calendar-small .day, .'.$style['cat'.$i].' .qem-calendar-medium .day, .'.$style['cat'.$i].' .qem-calendar-large .day, .'.$style['cat'.$i].' .qem-calendar-small .nonday, .'.$style['cat'.$i].' .qem-calendar-medium .nonday, .'.$style['cat'.$i].' .qem-calendar-large .nonday {border-color:'.$style['cat'.$i.'back'].';}';
        }
    }
    return $script;
}

// Adds the CSS to the page head

function qem_head_css () {
    $data = '<style type="text/css" media="screen">'.qem_generate_css().'</style><script type="text/javascript">ajaxurl = "'.admin_url('admin-ajax.php').'"; qem_calendar_atts = []; qem_year = []; qem_month = []; qem_category = [];</script>';
    echo $data;
}

function dateToCal($timestamp) {
    if ($timestamp) return date('Ymd\THis', $timestamp);
}

function escapeString($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
}

function qem_time ($starttime) {
    $starttime = str_replace('AM','',strtoupper($starttime));
    $needle = $minutes = $afternoon = '';
	if (strpos($starttime,':')) $needle = ':';
	if (strpos($starttime,'.')) $needle = '.';
	if (strpos($starttime,' ')) $needle = ' ';
    if (strpos(strtoupper($starttime),'PM') && substr($starttime, 0, 2) != '12') $afternoon = 43200;
	if ($needle) list($hours, $minutes) = explode($needle, $starttime);
    else $hours = $starttime;
    if (strlen($starttime) == 4 && is_numeric($starttime)) {$hours = substr($starttime, 0, 2);$minutes = substr($starttime, 2);}
    $seconds=$hours*3600+$minutes*60+$afternoon;
	return $seconds;
	}

// Builds the ICS files

function qem_ics() {
    global $post;
    $display = event_get_stored_display();
    $summary = $post->post_title;
    $eventstart = get_post_meta($post->ID, 'event_date', true);
    if (!$eventstart) $eventstart = time();
    $start = get_post_meta($post->ID, 'event_start', true);
    $date = date('Ymd\T', $eventstart);
    $time = qem_time($start);
    $time = date('His',$time);
    $datestart = $date.$time;
    $dateend = get_post_meta($post->ID, 'event_end_date', true);
    $address = get_post_meta($post->ID, 'event_address', true);
    $url = get_permalink();
    $description = get_post_meta($post->ID, 'event_desc', true);
    $filename = $post->post_title.'.ics';
    if (!$dateend) {
        $dateend = $eventstart;
        $finish = get_post_meta($post->ID, 'event_finish', true);
        $date = date('Ymd\T', $eventstart);
        $time = qem_time($finish);
        $time = date('His',$time);
        $dateend = $date.$time;
    } else {
        $finish = get_post_meta($post->ID, 'event_finish', true);
        $date = date('Ymd\T', $dateend);
        $time = qem_time($finish);
        $time = date('His',$time);
        $dateend = $date.$time;
    }
$ics = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
UID:'.uniqid().'
DTSTAMP:'.dateToCal(time()).'
DTSTART:'.$datestart.'
DTEND:'.$dateend.'
LOCATION:'.$address.'
DESCRIPTION:'.$description.'
URL;VALUE=URI:'.$url.'
SUMMARY:'.$summary.'
END:VEVENT
END:VCALENDAR';
    qem_generate_csv();
    $content = '<form method="post" action="">
    <input type="hidden" name="qem_ics" value="'.$ics.'">
    <input type="hidden" name="qem_filename" value="'.$filename.'">
    <input type="submit" name="qem_create_ics" id="submit" value="'.$display['useicsbutton'].'" /></form>';
    return $content;
}

// Generates and downloads the CSV fiule

function qem_generate_csv() {
    if (isset($_POST['qem_create_ics'])) {
        $ics = $_POST['qem_ics'];
        $filename = $_POST['qem_filename'];
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename="'.$filename.'"');
        header( 'Content-Type: text/csv');$fh = fopen("php://output",'w');
        fwrite($fh, $ics);
        fclose($fh);
        exit;
    }
    if(isset($_POST['qem_download_csv'])) {
        $event = $_POST['qem_download_form'];
        $title = $_POST['qem_download_title'];
        $register = qem_get_stored_register();
        $filename = urlencode($title.'.csv');
        if (!$title) $filename = urlencode('default.csv');
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename="'.$filename.'"');
        header( 'Content-Type: text/csv');$outstream = fopen("php://output",'w');
        $message = get_option( 'qem_messages_'.$event );
        if(!is_array($message))$message = array();
        $headerrow = array();
        if ($register['usename']) array_push($headerrow, $register['yourname']);
        if ($register['usemail']) array_push($headerrow, $register['youremail']);
        if ($register['usetelephone']) array_push($headerrow, $register['yourtelephone']);
        if ($register['useplaces']) array_push($headerrow, $register['yourplaces']);
        if ($register['usemorenames']) array_push($headerrow, $register['morenames']);
        if ($register['usemessage']) array_push($headerrow, $register['yourmessage']);
        if ($register['useblank1']) array_push($headerrow, $register['yourblank1']);
        if ($register['useblank2']) array_push($headerrow, $register['yourblank2']);
        if ($register['usedropdown']) array_push($headerrow, $register['yourdropdown']);
        if ($register['useselector']) array_push($headerrow, $register['yourselector']);
        if ($register['usemorenames']) array_push($headerrow, $register['morenames']);
        array_push($headerrow,'Date Sent');
        fputcsv($outstream,$headerrow, ',', '"');
        foreach($message as $value) {
            $cells = array();
            $value['morenames'] = preg_replace( "/\r|\n/", ", ", $value['morenames'] );
            if ($register['usename']) array_push($cells,$value['yourname']);
            if ($register['usemail']) array_push($cells, $value['youremail']);
            if ($register['usetelephone']) array_push($cells, $value['yourtelephone']);
            if ($register['useplaces']) array_push($cells, $value['yourplaces']);
            if ($register['usemorenames']) array_push($cells, $value['morenames']);
            if ($register['usemessage']) array_push($cells, $value['yourmessage']);
            if ($register['useblank1']) array_push($cells, $value['yourblank1']);
            if ($register['useblank2']) array_push($cells, $value['yourblank2']);
            if ($register['usedropdown']) array_push($cells, $value['yourdropdown']);
            if ($register['useselector']) array_push($cells, $value['yourselector']);
            if ($register['usenumber1']) array_push($cells, $value['yournumber1']);
            if ($register['usemorenames']) array_push($headerrow, $value['morenames']);
            array_push($cells,$value['sentdate']);
            fputcsv($outstream,$cells, ',', '"');
        }
        fclose($outstream); 
        exit;
    }
}

add_action('admin_menu', 'event_page_init');
add_filter('the_content', 'get_event_content');

function qem_add_role_caps() {
    qem_add_role();
    $roles = array('administrator','editor','event-manager');
    foreach ($roles as $item) {
        $role = get_role($item);
        $role->add_cap( 'read' );
        $role->add_cap( 'read_event');
        $role->add_cap( 'read_private_event' );
        $role->add_cap( 'edit_event' );
        $role->add_cap( 'edit_events' );
        $role->add_cap( 'edit_others_events' );
        $role->add_cap( 'edit_published_events' );
        $role->add_cap( 'publish_events' );
        $role->add_cap( 'delete_events' );
        $role->add_cap( 'delete_others_events');
        $role->add_cap( 'delete_private_events');
        $role->add_cap( 'delete_published_events');
        $role->add_cap( 'manage_categories');
        $role->add_cap( 'upload_files');
    }
}

function qem_users($output) {
    global $post;
    if($post->post_type == 'event') {
        $users = get_users();
        $output = "<select id='post_author_override' name='post_author_override' class=''>";
        foreach($users as $user) {
            $sel = ($post->post_author == $user->ID)?"selected='selected'":'';
            $output .= '<option value="'.$user->ID.'"'.$sel.'>'.$user->user_login.'</option>';
        }
        $output .= "</select>";
    }
    return $output;
}

function qem_add_role() {
    remove_role( 'event-manager' );
    add_role(
        'event-manager',
        'Event Manager',
        array( 'read' => true,'edit_posts' => false,'edit_event' => true, 'edit_events' => true,'publish_events' => true,'delete_events' => true )
    );
}

register_activation_hook( __FILE__, 'qem_add_role' );

add_action( 'template_redirect', 'qem_ipn' );

function qem_ipn () {
    $payment = qem_get_stored_payment();
    if (!$payment['ipn'])
        return;
    define("DEBUG", 1);
    define("LOG_FILE", "./ipn.log");
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }
    
    if ($payment['sandbox']) {
        $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    } else {
        $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
    }

    $ch = curl_init($paypal_url);
    if ($ch == FALSE) {
        return FALSE;
    }

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

    if(DEBUG == true) {
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
    }

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

    $res = curl_exec($ch);

    if (curl_errno($ch) != 0) // cURL error
    {
        if(DEBUG == true) {	
            error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
        }
        curl_close($ch);
        exit;
    } else {
        if(DEBUG == true) {
            error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
            error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
        }
        curl_close($ch);
    }
    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));

    if (strcmp ($res, "VERIFIED") == 0) {
        
        $custom = $_POST['custom'];
        $args = array('post_type'=>'event','posts_per_page' => -1);
        query_posts( $args );
        if ( have_posts()) {
            while (have_posts()) {
                the_post();
                $id = get_the_id();
                $message = get_option('qem_messages_'.$id);
                if ($message) {
                    $count = count($message);
                    for($i = 0; $i <= $count; $i++) {
                        if ($message[$i]['ipn'] == $custom) {
                            $message[$i]['ipn'] = 'Paid';
                    $auto = qem_get_stored_autoresponder($item);
                    if ($auto['enable'] && $message[$i]['youremail'] &&  $auto['whenconfirm'] == 'afterpayment') {
$register = get_custom_registration_form ();
                        $values = array(
                            'yourname' => $message[$i]['yourname'],
                            'youremail' => $message[$i]['youremail'],
                            'yourtelephone' => $message[$i]['yourtelephone'],
                            'yourmessage' => $message[$i]['yourmessage'],
                            'yourplaces' => $message[$i]['yourplaces'],
                            'yourblank1' => $message[$i]['yourblank1'],
                            'yourdropdown' => $message[$i]['yourdropdown'],
                            'yourselector' => $message[$i]['yourselector'],
'yournumber1' => $message[$i]['yournumber1'],
'morenames' => $message[$i]['morenames'],
'ignore' => $message[$i]['ignore'],
                        );
 $content = qem_build_event_message($values,$register);
qem_send_confirmation ($auto,$values,$content,$register,$id);
}
                            update_option('qem_messages_'.$id,$message);
                        }
                    }    
                }
            }
        }
        if(DEBUG == true) {
            error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
        }
    } else if (strcmp ($res, "INVALID") == 0) {
        if(DEBUG == true) {
            error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
        }
    }
}

function qem_add_custom_types( $query ) {
    if( !is_admin() && $query->is_category() || $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', array('post', 'event','nav_menu_item'));
        return $query;
    }
}

function event_plugin_action_links($links, $file) {
    if ( $file == plugin_basename( __FILE__ ) ) {
        $event_links = '<a href="'.get_admin_url().'options-general.php?page=quick-event-manager/settings.php">'.__('Settings', 'quick-event-manager').'</a>';
        array_unshift( $links, $event_links );
    }
    return $links;
}

function qem_flush_rules() {
    event_register();
    flush_rewrite_rules();
}

$display = event_get_stored_display();
if ($display['recentposts']) add_filter( 'widget_posts_args', 'qem_recent_posts_args');

function qem_recent_posts_args($args) {
    $args['post_type'] = array('post', 'event');
    return $args;
}

function qem_enqueue_scripts() {
    wp_enqueue_style('event_style',plugins_url('quick-event-manager.css', __FILE__),20);
    wp_enqueue_script('event_script',plugins_url('quick-event-manager.js', __FILE__),array('jquery'),false,true);
    wp_enqueue_script('event_lightbox',plugins_url('quick-event-lightbox.js', __FILE__ ), array( 'jquery' ), false, true );
}

function qem_lang_init() {
    load_plugin_textdomain( 'quick-event-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function qem_external_permalink( $link, $post ) {
    $meta = get_post_meta( $post->ID, 'event_link', TRUE );
    $url  = esc_url( filter_var( $meta, FILTER_VALIDATE_URL ) );
    return $url ? $url : $link;
}

function get_event_content($content) {
    global $post;
    $atts = array(
        'links' => 'off',
        'size' => '',
        'headersize' => '',
        'settings' => 'checked',
        'fullevent' => 'fullevent',
        'images' => '',
        'fields' => '',
        'widget' => '',
        'cb' => '',
        'vanillawidget' => '',
        'linkpopup' => '',
        'thisday' => '',
        'popup' => '',
        'vw' => '',
        'categoryplaces' => '',
        'fulllist' =>'',
        'thisisapopup' => '',
        'listplaces' => true,
        'calendar' => false,
        'fullpopup' => false
    );
    if (is_singular ('event') ) {
        $content = qem_event_construct ($atts);
    }
    return $content;
}