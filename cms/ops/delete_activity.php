<?php

/**********************************/
/* Pika CMS (C) 2008 Aaron Worley */
/* http://pikasoftware.com        */
/**********************************/

chdir('../');

require_once ('pika-danio.php');
pika_init();

require_once('pikaActivity.php');
require_once('pikaTempLib.php');

// VARIABLES
$act_id = pl_grab_post('act_id');
$base_url = pl_settings_get('base_url');

$cancel = pl_grab_post('cancel');


// BEGIN MAIN CODE...
$act_date = date("Y-m-d");
$case_id = null;
if(is_numeric($act_id) && !$cancel) {
	$activity = new pikaActivity($act_id);
	$act_date = $activity->act_date;
	if(is_numeric($activity->case_id)) {
		$case_id = $activity->case_id;
	}
	
	if ((pl_settings_get('db_name') == 'legalaidnebraska' && pika_authorize('edit_act', $act_row))
			|| pika_authorize('delete_act', array()))
	{
		$activity->delete();
	}
	
	else {
		header("Location: {$base_url}/activity.php?act_id={$act_id}");
		exit();
	}
}




if ($cancel) {
	header("Location: {$base_url}/activity.php?act_id={$act_id}");
} else if ($case_id && is_numeric($case_id)) {
		header("Location: {$base_url}/case.php?case_id={$case_id}");
} else {
	header("Location: {$base_url}/cal_day.php?cal_date={$act_date}");
}



exit();

?>