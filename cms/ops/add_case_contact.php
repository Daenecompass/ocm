<?php

/**********************************/
/* Pika CMS (C) 2002 Aaron Worley */
/* http://pikasoftware.com        */
/**********************************/

chdir('../');

require_once ('pika-danio.php');
pika_init();

require_once('pikaCase.php');


// VARIABLES
$case_id = pl_grab_post('case_id', 0);
$relation_code = pl_grab_post('relation_code');
$contact_id = pl_grab_post('thiscon');
$screen = pl_grab_var('screen', 'REQUEST', 'act');
$base_url = pl_settings_get('base_url');

// BEGIN MAIN CODE...
// An existing contact record is being added to this case
	
if (is_null($case_id))
{
	trigger_error('Ran into trouble when adding the case contact');
}

if (is_null($relation_code))
{
	trigger_error('Ran into trouble when adding the case contact');
}

$case1 = new pikaCase($case_id);
$case1->addContact($contact_id, $relation_code);
header("Location: {$base_url}/case.php?case_id={$case_id}&screen={$screen}");

exit();

?>