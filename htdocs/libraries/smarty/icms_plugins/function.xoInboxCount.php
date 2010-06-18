<?php



function smarty_function_xoInboxCount( $params, &$smarty ) {
	global $xoopsUser;

	if ( !isset($xoopsUser) || !is_object($xoopsUser) ) {
		return;
	}
	$time = time();
	if ( isset( $_SESSION['xoops_inbox_count'] ) && @$_SESSION['xoops_inbox_count_expire'] > $time ) {
		$count = (int) $_SESSION['xoops_inbox_count'] ;
	} else {
        $pm_handler =& xoops_gethandler( 'privmessage' );
        $criteria = new core_CriteriaCompo( new core_Criteria('read_msg', 0) );
        $criteria->add( new core_Criteria( 'to_userid', $xoopsUser->getVar('uid') ) );
        $count = (int) $pm_handler->getCount($criteria) ;
        $_SESSION['xoops_inbox_count'] = $count;
        $_SESSION['xoops_inbox_count_expire'] = $time + 60;
	}
	if ( !@empty( $params['assign'] ) ) {
		$smarty->assign( $params['assign'], $count );
	} else {
		echo $count;
	}
}

?>