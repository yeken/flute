<?

$module_name = 'db configurator';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/db_configurator',
											'main_model' => 'admin/dashboard_model',
										 	'module_label' => 'DB Configurator',
											'module_unit' => 'DB Configurator',
											'restrictions' => 'admin_only',
										 );

$config['modules'][$module_name]['fields'] = array(	);


$config['modules'][$module_name]['top_menu_actions'] = array( 'show_reports' => array('url' => 'show_reports', 'method' => 'create', 'class_name' => 'dashboard', 'icon' => "ui-icon-plusthick", 'label' => "Reportes")
													);


?>