<?php 
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
	$config_handler =& xoops_gethandler('config');
	$xoopsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
	include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php';
		echo'<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/libraries/jscalendar/aqua/style.css" />
<script type="text/javascript" src="'.XOOPS_URL.'/libraries/jscalendar/calendar.js"></script>
<script type="text/javascript" src="'.XOOPS_URL.'/libraries/jscalendar/calendar-setup.js"></script>
';
	if (defined('_EXT_DATE_FUNC') && $xoopsConfig['use_ext_date'] == 1 && _EXT_DATE_FUNC && file_exists(ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/ext/ext_date_function.js')){
		echo'<script type="text/javascript" src="'.XOOPS_URL.'/language/'.$xoopsConfig['language'].'/ext/ext_date_function.js"></script>';
}
?>
<script type="text/javascript">
<!--
Calendar._DN = new Array
("<?php echo _CAL_SUNDAY;?>",
 "<?php echo _CAL_MONDAY;?>",
 "<?php echo _CAL_TUESDAY;?>",
 "<?php echo _CAL_WEDNESDAY;?>",
 "<?php echo _CAL_THURSDAY;?>",
 "<?php echo _CAL_FRIDAY;?>",
 "<?php echo _CAL_SATURDAY;?>",
 "<?php echo _CAL_SUNDAY;?>");

Calendar._SDN = new Array
("<?php echo _CAL_SUN;?>",
 "<?php echo _CAL_MON;?>",
 "<?php echo _CAL_TUE;?>",
 "<?php echo _CAL_WED;?>",
 "<?php echo _CAL_THU;?>",
 "<?php echo _CAL_FRI;?>",
 "<?php echo _CAL_SAT;?>",
 "<?php echo _CAL_SUN;?>");

Calendar._FD = <?php echo _CAL_FIRSTDAY;?>;

Calendar._MN = new Array
("<?php echo _CAL_JANUARY;?>",
 "<?php echo _CAL_FEBRUARY;?>",
 "<?php echo _CAL_MARCH;?>",
 "<?php echo _CAL_APRIL;?>",
 "<?php echo _CAL_MAY;?>",
 "<?php echo _CAL_JUNE;?>",
 "<?php echo _CAL_JULY;?>",
 "<?php echo _CAL_AUGUST;?>",
 "<?php echo _CAL_SEPTEMBER;?>",
 "<?php echo _CAL_OCTOBER;?>",
 "<?php echo _CAL_NOVEMBER;?>",
 "<?php echo _CAL_DECEMBER;?>");

Calendar._SMN = new Array
("<?php echo _CAL_JAN;?>",
 "<?php echo _CAL_FEB;?>",
 "<?php echo _CAL_MAR;?>",
 "<?php echo _CAL_APR;?>",
 "<?php echo _CAL_MAY;?>",
 "<?php echo _CAL_JUN;?>",
 "<?php echo _CAL_JUL;?>",
 "<?php echo _CAL_AUG;?>",
 "<?php echo _CAL_SEP;?>",
 "<?php echo _CAL_OCT;?>",
 "<?php echo _CAL_NOV;?>",
 "<?php echo _CAL_DEC;?>");


 // full month names
Calendar._JMN = new Array
("<?php echo _CAL_Far;?>",
 "<?php echo _CAL_Ord;?>",
 "<?php echo _CAL_Kho;?>",
 "<?php echo _CAL_Tir;?>",
 "<?php echo _CAL_Mor;?>",
 "<?php echo _CAL_Sha;?>",
 "<?php echo _CAL_Meh;?>",
 "<?php echo _CAL_Aba;?>",
 "<?php echo _CAL_Aza;?>",
 "<?php echo _CAL_Dey;?>",
 "<?php echo _CAL_Bah;?>",
 "<?php echo _CAL_Esf;?>");
// short month names
Calendar._JSMN = new Array
("<?php echo _CAL_Far;?>",
 "<?php echo _CAL_Ord;?>",
 "<?php echo _CAL_Kho;?>",
 "<?php echo _CAL_Tir;?>",
 "<?php echo _CAL_Mor;?>",
 "<?php echo _CAL_Sha;?>",
 "<?php echo _CAL_Meh;?>",
 "<?php echo _CAL_Aba;?>",
 "<?php echo _CAL_Aza;?>",
 "<?php echo _CAL_Dey;?>",
 "<?php echo _CAL_Bah;?>",
 "<?php echo _CAL_Esf;?>");


 
// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "About the calendar";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n"
/*"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +*/
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "<?php echo _CAL_PREVYR;?>";
Calendar._TT["PREV_MONTH"] = "<?php echo _CAL_PREVMNTH;?>";
Calendar._TT["GO_TODAY"] = "<?php echo _CAL_GOTODAY;?>";
Calendar._TT["NEXT_MONTH"] = "<?php echo _CAL_NXTMNTH;?>";
Calendar._TT["NEXT_YEAR"] = "<?php echo _CAL_NEXTYR;?>";
Calendar._TT["SEL_DATE"] = "<?php echo _CAL_SELDATE;?>";
Calendar._TT["DRAG_TO_MOVE"] = "<?php echo _CAL_DRAGMOVE;?>";
Calendar._TT["PART_TODAY"] = "(<?php echo _CAL_TODAY;?>)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "<?php echo _CAL_DSPFIRST;?>";

Calendar._TT["WEEKEND"] = "<?php echo _CAL_WEEKEND;?>";

Calendar._TT["CLOSE"] = "<?php echo _CLOSE;?>";
Calendar._TT["TODAY"] = "<?php echo _CAL_TODAY;?>";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "<?php echo _CAL_WK;?>";
Calendar._TT["TIME"] = "<?php echo _CAL_TIME;?> : ";

Calendar._TT["LAM"] = "<?php echo _CAL_AM;?>";
Calendar._TT["AM"] = "<?php echo _CAL_AM_CAPS;?>";
Calendar._TT["LPM"] = "<?php echo _CAL_PM;?>";
Calendar._TT["PM"] = "<?php echo _CAL_PM_CAPS;?>";

Calendar._NUMBERS = [<?php echo _CAL_NUMS_ARRAY;?>];

Calendar._DIR = '<?php echo _CAL_DIRECTION;?>';

</script>