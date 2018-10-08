<?php
	global $wpdb;

	$table_name  =  $wpdb->prefix . "ritcalendar_manager";
	$table_name2 =  $wpdb->prefix . "ritcalendar_font_family";
	$table_name3 =  $wpdb->prefix . "ritcalendar_url";

	$sql2='CREATE TABLE IF NOT EXISTS ' .$table_name2.' (
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		Font_family VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';

	$sql='CREATE TABLE IF NOT EXISTS ' .$table_name.' (
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		RIT_CalThemeTitle VARCHAR(255) NOT NULL,
		RIT_CalWidth VARCHAR(255) NOT NULL,
		RIT_CalHeight VARCHAR(255) NOT NULL,
		RIT_CalBgColor VARCHAR(255) NOT NULL,
		RIT_CalDaysFontSize VARCHAR(255) NOT NULL,
		RIT_CalBorderRadius VARCHAR(255) NOT NULL,		
		RIT_CalGridColor VARCHAR(255) NOT NULL,
		RIT_CalFsD VARCHAR(255) NOT NULL,
		RIT_Calendar_Show_Title VARCHAR(255) NOT NULL,
		RIT_CalTitleBgColor VARCHAR(255) NOT NULL,
		RIT_CalTitleColor VARCHAR(255) NOT NULL,
		RIT_CalTitleFontSize VARCHAR(255) NOT NULL,
		RIT_CalTitleFontFamily VARCHAR(255) NOT NULL,
		RIT_CalMonthBgColor VARCHAR(255) NOT NULL,
		RIT_CalMonthColor VARCHAR(255) NOT NULL,
		RIT_CalMonthFontSize VARCHAR(255) NOT NULL,
		RIT_CalMonthFontFamily VARCHAR(255) NOT NULL,
		RIT_Calendar_Popup_Icons VARCHAR(255) NOT NULL,
		RIT_CalIconColor VARCHAR(255) NOT NULL,
		RIT_CalIconFontSize VARCHAR(255) NOT NULL,
		RIT_CalWDayBgColor VARCHAR(255) NOT NULL,
		RIT_CalWDayColor VARCHAR(255) NOT NULL,
		RIT_CalWDayFontSize VARCHAR(255) NOT NULL,
		RIT_CalWdayBRad VARCHAR(255) NOT NULL,
		RIT_CalWdayFontFamily VARCHAR(255) NOT NULL,
		RIT_CalSatBgColor VARCHAR(255) NOT NULL,
		RIT_CalSatColor VARCHAR(255) NOT NULL,
		RIT_CalSatFontSize VARCHAR(255) NOT NULL,
		RIT_CalSatBRad VARCHAR(255) NOT NULL,
		RIT_CalSatFontFamily VARCHAR(255) NOT NULL,
		RIT_CalSunBgColor VARCHAR(255) NOT NULL,
		RIT_CalSunColor VARCHAR(255) NOT NULL,
		RIT_CalSunFontSize VARCHAR(255) NOT NULL,
		RIT_CalSunBRad VARCHAR(255) NOT NULL,
		RIT_CalSunFontFamily VARCHAR(255) NOT NULL,
		RIT_CalCurrentBgColor VARCHAR(255) NOT NULL,
		RIT_CalCurrentColor VARCHAR(255) NOT NULL,
		RIT_CalCurrentBorderRadius VARCHAR(255) NOT NULL,
		RIT_CalCurrentBorderColor VARCHAR(255) NOT NULL,
		RIT_CalWEventBgColor VARCHAR(255) NOT NULL,
		RIT_CalWEventColor VARCHAR(255) NOT NULL,
		RIT_CalWEventBorderRadius VARCHAR(255) NOT NULL,
		RIT_CalDSatBgColor VARCHAR(255) NOT NULL,
		RIT_CalDSatColor VARCHAR(255) NOT NULL,
		RIT_CalDSatBorderRadius VARCHAR(255) NOT NULL,
		RIT_CalDSunBgColor VARCHAR(255) NOT NULL,
		RIT_CalDSunColor VARCHAR(255) NOT NULL,
		RIT_CalDSunBorderRadius VARCHAR(255) NOT NULL,
		RIT_CalHoverBgColor VARCHAR(255) NOT NULL,
		RIT_CalHoverColor VARCHAR(255) NOT NULL,
		RIT_CalURLBgColor VARCHAR(255) NOT NULL,
		RIT_CalURLColor VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
	$sql3='CREATE TABLE IF NOT EXISTS ' .$table_name3.' (
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		CalendarID VARCHAR(255) NOT NULL,
		URLDate VARCHAR(255) NOT NULL,
		URL LONGTEXT NOT NULL,
		ONT VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
		
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	dbDelta($sql2);
	dbDelta($sql3);
	
	$family = array('Abadi MT Condensed Light','Aharoni','Aldhabi','Andalus','Angsana New',' AngsanaUPC','Aparajita','Arabic Typesetting','Arial', 'Arial Black','Batang','BatangChe','Browallia New','BrowalliaUPC','Calibri','Calibri Light','Calisto MT','Cambria','Candara','Century Gothic', 'Comic Sans MS','Consolas','Constantia','Copperplate Gothic','Copperplate Gothic Light','Corbel','Cordia New','CordiaUPC','Courier New', 'DaunPenh','David','DFKai-SB','DilleniaUPC','DokChampa','Dotum','DotumChe','Ebrima','Estrangelo Edessa','EucrosiaUPC','Euphemia','FangSong', 'Franklin Gothic Medium','FrankRuehl','FreesiaUPC','Gabriola','Gadugi','Gautami','Georgia','Gisha','Gulim','GulimChe','Gungsuh','GungsuhChe', 'Impact','IrisUPC','Iskoola Pota','JasmineUPC','KaiTi','Kalinga','Kartika','Khmer UI','KodchiangUPC','Kokila','Lao UI','Latha','Leelawadee', 'Levenim MT','LilyUPC','Lucida Console','Lucida Handwriting Italic','Lucida Sans Unicode','Malgun Gothic','Mangal','Manny ITC','Marlett', 'Meiryo','Meiryo UI','Microsoft Himalaya','Microsoft JhengHei','Microsoft JhengHei UI','Microsoft New Tai Lue','Microsoft PhagsPa', 'Microsoft Sans Serif','Microsoft Tai Le','Microsoft Uighur','Microsoft YaHei','Microsoft YaHei UI','Microsoft Yi Baiti','MingLiU_HKSCS', 'MingLiU_HKSCS-ExtB','Miriam','Mongolian Baiti','MoolBoran','MS UI Gothic','MV Boli','Myanmar Text','Narkisim','Nirmala UI','News Gothic MT', 'NSimSun','Nyala','Palatino Linotype','Plantagenet Cherokee','Raavi','Rod','Sakkal Majalla','Segoe Print','Segoe Script','Segoe UI Symbol', 'Shonar Bangla','Shruti','SimHei','SimKai','Simplified Arabic','SimSun','SimSun-ExtB','Sylfaen','Tahoma','Times New Roman','Traditional Arabic', 'Trebuchet MS','Tunga','Utsaah','Vani','Vijaya');
	$RIT_Cal_Count_Fonts=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id>%d",0));
	if(count($RIT_Cal_Count_Fonts)==0)
	{
		for($i=0;$i<count($family);$i++)
		{
			$wpdb->query($wpdb->prepare("INSERT INTO $table_name2 (id, Font_family) VALUES (%d, %s)", '', $family[$i]));
		}
	}
	$RIT_Cal_Count_Themes=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id>%d",0));
	if(count($RIT_Cal_Count_Themes)<3)
	{
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id > %d", 0));

		$wpdb->query($wpdb->prepare("INSERT INTO $table_name (id, RIT_CalThemeTitle, RIT_CalWidth, RIT_CalHeight, RIT_CalBgColor, RIT_CalDaysFontSize, RIT_CalBorderRadius, RIT_CalGridColor, RIT_CalFsD, RIT_Calendar_Show_Title, RIT_CalTitleBgColor, RIT_CalTitleColor, RIT_CalTitleFontSize, RIT_CalTitleFontFamily, RIT_CalMonthBgColor, RIT_CalMonthColor, RIT_CalMonthFontSize, RIT_CalMonthFontFamily, RIT_Calendar_Popup_Icons, RIT_CalIconColor, RIT_CalIconFontSize, RIT_CalWDayBgColor, RIT_CalWDayColor, RIT_CalWDayFontSize, RIT_CalWdayBRad, RIT_CalWdayFontFamily, RIT_CalSatBgColor, RIT_CalSatColor, RIT_CalSatFontSize, RIT_CalSatBRad, RIT_CalSatFontFamily, RIT_CalSunBgColor, RIT_CalSunColor, RIT_CalSunFontSize, RIT_CalSunBRad, RIT_CalSunFontFamily, RIT_CalCurrentBgColor, RIT_CalCurrentColor, RIT_CalCurrentBorderRadius, RIT_CalCurrentBorderColor, RIT_CalWEventBgColor, RIT_CalWEventColor, RIT_CalWEventBorderRadius, RIT_CalDSatBgColor, RIT_CalDSatColor, RIT_CalDSatBorderRadius, RIT_CalDSunBgColor, RIT_CalDSunColor, RIT_CalDSunBorderRadius, RIT_CalHoverBgColor, RIT_CalHoverColor, RIT_CalURLBgColor, RIT_CalURLColor) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', 'Orange Calendar', '230px', '242px', '#dd9933', '14px', '2px', '#dd8b0f', 'Monday', 'No', '#ffffff', '#000000', '16px', 'Arial', '#dd9933', '#ffffff', '19px', 'Vijaya', '2', '#dd6000', '18px', '#dd8b0f', '#ffffff', '19px', '0px', 'Vijaya', '#dd8502', '#ffffff', '19px', '0px', 'Vijaya', '#d88200', '#ffffff', '19px', '0px', 'Vijaya', '#dd6000', '#ffffff', '0px', '#dd9933', '#dd9933', '#ffffff', '0px', '#dd9933', '#ffffff', '0px', '#dd9933', '#bc3e00', '0px', '#bc3e00', '#ddaa5d', '#bc3e00', '#ffffff'));
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name (id, RIT_CalThemeTitle, RIT_CalWidth, RIT_CalHeight, RIT_CalBgColor, RIT_CalDaysFontSize,  RIT_CalBorderRadius, RIT_CalGridColor, RIT_CalFsD, RIT_Calendar_Show_Title, RIT_CalTitleBgColor, RIT_CalTitleColor, RIT_CalTitleFontSize, RIT_CalTitleFontFamily, RIT_CalMonthBgColor, RIT_CalMonthColor, RIT_CalMonthFontSize, RIT_CalMonthFontFamily, RIT_Calendar_Popup_Icons, RIT_CalIconColor, RIT_CalIconFontSize, RIT_CalWDayBgColor, RIT_CalWDayColor, RIT_CalWDayFontSize, RIT_CalWdayBRad, RIT_CalWdayFontFamily, RIT_CalSatBgColor, RIT_CalSatColor, RIT_CalSatFontSize, RIT_CalSatBRad, RIT_CalSatFontFamily, RIT_CalSunBgColor, RIT_CalSunColor, RIT_CalSunFontSize, RIT_CalSunBRad, RIT_CalSunFontFamily, RIT_CalCurrentBgColor, RIT_CalCurrentColor, RIT_CalCurrentBorderRadius, RIT_CalCurrentBorderColor, RIT_CalWEventBgColor, RIT_CalWEventColor, RIT_CalWEventBorderRadius, RIT_CalDSatBgColor, RIT_CalDSatColor, RIT_CalDSatBorderRadius, RIT_CalDSunBgColor, RIT_CalDSunColor, RIT_CalDSunBorderRadius, RIT_CalHoverBgColor, RIT_CalHoverColor, RIT_CalURLBgColor, RIT_CalURLColor) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', 'Theme - 2', '230px', '242px', '#ffffff', '14px', '0px', '#ffffff', 'Monday', 'No', '#ffffff', '#000000', '16px', 'Arial', '#ffffff', '#1e73be', '19px', 'Vijaya', '5', '#0066bf', '18px', '#1e73be', '#ffffff', '19px', '0px', 'Vijaya', '#1e73be', '#ffffff', '19px', '0px', 'Vijaya', '#1e73be', '#ffffff', '19px', '0px', 'Vijaya', '#dd0000', '#ffffff', '4px', '#ffffff', '#1e73be', '#ffffff', '0px', '#1e73be', '#ffffff', '0px', '#1e73be', '#8ba7bf', '0px', '#0066bf', '#ffffff', '#1e73be', '#ff0000'));
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name (id, RIT_CalThemeTitle, RIT_CalWidth, RIT_CalHeight, RIT_CalBgColor, RIT_CalDaysFontSize,  RIT_CalBorderRadius, RIT_CalGridColor, RIT_CalFsD, RIT_Calendar_Show_Title, RIT_CalTitleBgColor, RIT_CalTitleColor, RIT_CalTitleFontSize, RIT_CalTitleFontFamily, RIT_CalMonthBgColor, RIT_CalMonthColor, RIT_CalMonthFontSize, RIT_CalMonthFontFamily, RIT_Calendar_Popup_Icons, RIT_CalIconColor, RIT_CalIconFontSize, RIT_CalWDayBgColor, RIT_CalWDayColor, RIT_CalWDayFontSize, RIT_CalWdayBRad, RIT_CalWdayFontFamily, RIT_CalSatBgColor, RIT_CalSatColor, RIT_CalSatFontSize, RIT_CalSatBRad, RIT_CalSatFontFamily, RIT_CalSunBgColor, RIT_CalSunColor, RIT_CalSunFontSize, RIT_CalSunBRad, RIT_CalSunFontFamily, RIT_CalCurrentBgColor, RIT_CalCurrentColor, RIT_CalCurrentBorderRadius, RIT_CalCurrentBorderColor, RIT_CalWEventBgColor, RIT_CalWEventColor, RIT_CalWEventBorderRadius, RIT_CalDSatBgColor, RIT_CalDSatColor, RIT_CalDSatBorderRadius, RIT_CalDSunBgColor, RIT_CalDSunColor, RIT_CalDSunBorderRadius, RIT_CalHoverBgColor, RIT_CalHoverColor, RIT_CalURLBgColor, RIT_CalURLColor) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', 'Theme - 3', '210px', '220px', '#dd0000', '12px', '30px', '#ffffff', 'Monday', 'No', '#ffffff', '#000000', '16px', 'Arial', '#dd0000', '#ffffff', '19px', 'Vijaya', '5', '#ffffff', '14px', '#dd0000', '#ffffff', '16px', '0px', 'Vijaya', '#dd0000', '#ffffff', '16px', '0px', 'Vijaya', '#dd0000', '#000000', '16px', '0px', 'Vijaya', '#dd0000', '#ffffff', '0px', '#ffffff', '#dd0000', '#ffffff', '0px', '#dd0000', '#ffffff', '0px', '#dd0000', '#000000', '0px', '#bc3e00', '#ddaa5d', '#dd6161', '#ffffff'));
	}
?>