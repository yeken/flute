<?

$module_name = 'team';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/team',
											'main_model' => 'admin/team_model',
										 	'module_label' => 'Equipos',
											'module_unit' => 'Equipo',
										 );

$config['modules'][$module_name]['fields'] = array(	
										'name' => array(	'label' => 'Nombre',
															'type' => 'text',
															'class' => 'title',
															'validation' => 'required',
															'visibility' => 'save|details|list'
														),
										'abbr_name' => array(	'label' => 'Nombre Abreviado',
															'type' => 'text',
															'class' => 'title',
															'validation' => 'required',
															'visibility' => 'save|details|list'
														),				
										'flag_image' => array(	'label' => 'Bandera',
																	'type' => 'image',
																	'tag' => 'flag_image',
																	'validation' => '',
																	'visibility' => 'details|save',
																	),
										'pj'	=> array(	'label' => 'PJ',
																'type' => 'text',
																'description' => "Partidos Jugados",
																'visibility' => 'save|details|list'
																),
										'pg'	=> array(	'label' => 'PG',
																'type' => 'text',
																'description' => "Partidos Ganados",
																'visibility' => 'save|details|list'
																),
										'pe'	=> array(	'label' => 'PE',
																'type' => 'text',
																'description' => "Partidos Empatados",
																'visibility' => 'save|details|list'
																),
										'pp'	=> array(	'label' => 'PP',
																'type' => 'text',
																'description' => "Partidos Perdidos",
																'visibility' => 'save|details|list'
																),
										'pts'	=> array(	'label' => 'Pts',
																'type' => 'text',
																'description' => "Puntos",
																'visibility' => 'save|details|list'
																),
										'qualyfied'	=> array(	'label' => 'Clasificado',
																'type' => 'checkbox',
																'value' => 0,
																'visibility' => 'save|details|list'
																),
										'final_pos'	=> array(	'label' => 'Posición final',
																'type' => 'text',
																'class' => 'title',
																'visibility' => 'save|details|list'
																),						
										/*
										'main_image' => array(	'label' => 'Logo Equipo',
																	'type' => 'image',
																	'tag' => 'main_image',
																	'validation' => '',
																	'visibility' => 'details|save',
																	),
										
										'zone' => array( 'label' => 'Grupo / Zona',
													'type' => 'select',
													'options' => array(
																		0 => array("value" => "Grupo A", "label" => "Grupo A"),
																		1 => array("value" => "Grupo B", "label" => "Grupo B"),
																		2 => array("value" => "Grupo C", "label" => "Grupo C"),
																		3 => array("value" => "Grupo D", "label" => "Grupo D"),
																		4 => array("value" => "Grupo E", "label" => "Grupo E"),
																		5 => array("value" => "Grupo F", "label" => "Grupo F"),
																		6 => array("value" => "Grupo G", "label" => "Grupo G"),
																		7 => array("value" => "Grupo H", "label" => "Grupo H")
																		),
													'visibility' => 'details|save|list',
													),													
										*/
										'active'	=> array(	'label' => 'Activo',
																'type' => 'checkbox',
																'value' => 1,
																'visibility' => 'save|details|list'
																)						
										);

$config['modules'][$module_name]['top_menu_actions'] = array( 	'teams_list' => array(	'url' => '#team/show_list',
																						'method' => 'show_list',
																						'class_name' => $config['modules'][$module_name], 
																						'icon' => "ui-icon-clipboard", 
																						'label' => "Listado de ".$config['modules'][$module_name]['module_label']),
																'add_team' => array('url' => '#team/create',
																					'method' => 'create', 
																					'class_name' => $config['modules'][$module_name], 
																					'icon' => "ui-icon-plusthick", 'label' => "Agregar ".$config['modules'][$module_name]['module_unit']));

$config['modules'][$module_name]['main_model_tabs'] = array( 	'details' => array( 'label' => 'Detalle',
								  			  	 				'url' => '#'.$module_name.'/details/'),
											'edit' => array( 	'label' => 'Editar',
											 					'url' => '#'.$module_name.'/edit/',
																),
															);

$config['modules'][$module_name]['datatable_actions'] = array( 	'details' => array( 'label' => 'Detalle',
								  			  	 				'url' => '#'.$module_name.'/details/'),
											'edit' => array( 	'label' => 'Editar',
											 					'url' => '#'.$module_name.'/edit/'),
											'delete' => array( 	'label' => 'Borrar',
																'dialog' => 'Borrar equipo?',
											 					'url' => '#'.$module_name.'/delete/'),
											);
?>