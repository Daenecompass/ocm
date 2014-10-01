<?php

function case_tabs($field_name = null, $field_value = null, $menu_array = null, $args = null)
{
	
	if (!is_array($menu_array)){
		$menu_array = array();
	}if(!is_array($field_value)) {
		$field_value = array();
	}if(!is_array($args)) {
		$args = array();
	}
	
	$def_args = array(
		// JS Directives
		'onclick' => '', 
		'js_mode' => false,
		// Debug Directives
		'url' => '',
		'view' => 'enabled'
	);
	
	// Allow arg override
	
	$temp_args = pikaTempLib::getPluginArgs($def_args,$args);
	
	$base_url = pl_settings_get('base_url');
	$x = 0;
	$y = ''; // Standard case tabs.
	$yjs = ''; // JavaScript enabled case tabs.
	
	$case_tabs = array();
	
	$autosave = false;
	if(isset($menu_array["case-{$field_name}.php"]['autosave']) && $menu_array["case-{$field_name}.php"]['autosave'])
	{
		$autosave = true;
	}
	
	
	// Custom code for LSNC; it decides which case tab table to use.
	$i = 0;
	if ("Mediator" == $_SESSION['def_group'])
	{
		$menu_array = pl_menu_get('case_tabs_med');
		echo $i++;
	}
	
	else if ("Med Mgr" == $_SESSION['def_group'])
	{
		$menu_array = pl_menu_get('case_tabs_medmgr');
		echo $i++;
	}
	
	else if ("95" == $_SESSION['def_office'])
	{
		echo $i++;
		$menu_array = pl_menu_get('case_tabs_hrh');
	}
	
	else if (("system" == $_SESSION['def_group']) &&  ("80" == $_SESSION['def_office']))
	{
		echo $i++;
		$menu_array = pl_menu_get('case_tabs_medmgr');
	}
	
	else if (("default" == $_SESSION['def_group']) &&  ("80" == $_SESSION['def_office']))
	{
		echo $i++;
		$menu_array = pl_menu_get('case_tabs_slh');
	}
	
	else
	{
		echo $i++;
		$menu_array = pl_menu_get('case_tabs');
	}
echo "hi world;";
	// End custom code for LSNC.
	
	
	
	foreach ($menu_array as $key => $tab)
	{
		//print_r($tab);
		if(!$temp_args['view'] || ($tab['enabled'] && $temp_args['view'] == 'enabled') ||  (!$tab['enabled'] && $temp_args['view'] == 'disabled')) {
			$current = '';
			if ($tab['file'] == "case-{$field_name}.php"){
				$current = ' class="active"';
			}
			$screen_name = $tab['file'];
			if(substr($screen_name,0,5) == 'case-') {
				$screen_name = substr($screen_name,5);
			}
			$ext_location = strpos($screen_name,'.php');
			if($ext_location !== false) {
				$screen_name = substr($screen_name,0,$ext_location);
			}
			$onclick = '';
			if(strlen($temp_args['onclick']) > 0) {
				$onclick .= $temp_args['onclick'];
			}
			if($temp_args['js_mode'] && $autosave) {
				$onclick .= "if(typeof window.setConfirmUnload == 'function') setConfirmUnload(false); document.forms.ws.screen.value='{$screen_name}'; document.forms.ws.submit(); return false;";
			}
			if(!isset($case_tabs[$tab['tab_row']])) { $case_tabs[$tab['tab_row']] = ''; }
			$case_tabs[$tab['tab_row']] .= "<li{$current}>";
			
			if($temp_args['url']) {
				$case_tabs[$tab['tab_row']] .= "<a href=\"{$temp_args['url']}screen={$screen_name}\" onClick=\"{$onclick}\">{$tab['name']}</a>";
			} else {
				$case_tabs[$tab['tab_row']] .= "<a href=\"{$base_url}/case.php?case_id={$field_value['case_id']}&screen={$screen_name}\" onClick=\"{$onclick}\">{$tab['name']}</a>";				
			}
			$case_tabs[$tab['tab_row']] .= "</li>\n";
		}
	}
	ksort($case_tabs);
	$case_tabs_html = "<ul class=\"nav nav-tabs\">";
	$case_tabs_html .= implode("</ul>\n<ul class=\"nav nav-tabs\">",$case_tabs);
	$case_tabs_html .= "</ul>";
	
	
	return $case_tabs_html;
	
}