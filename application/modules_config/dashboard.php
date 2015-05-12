<?

$module_name = 'dashboard';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/dashboard',
											'main_model' => 'admin/dashboard_model',
										 	'module_label' => 'Dashboard',
											'module_unit' => 'Dashboard',
											'restrictions' => 'own_content',
										 );

$config['modules'][$module_name]['fields'] = array(	);


$config['modules'][$module_name]['top_menu_actions'] = array( 'show_reports' => array('url' => 'show_reports', 'method' => 'create', 'class_name' => 'dashboard', 'icon' => "ui-icon-plusthick", 'label' => "Reportes")
													);


?>