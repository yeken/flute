<?

$module_name = 'match';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/match',
											'main_model' => 'admin/match_model',
										 	'module_label' => 'Partidos',
											'module_unit' => 'Partido',
										 );

$config['modules'][$module_name]['fields'] = array(
										'bet' => array( 'label' => 'Apuestas',
													'type' => 'text',
													'description' => "paga equipo1|empate|paga equipo 2 (ej: 4/3|2|5/3)",
													'visibility' => 'details|list|save',
													),
										'date_played' => array( 'label' => 'Día de juego',
													'type' => 'date_time',
													'validation' => 'required',
													'visibility' => 'details|list|save',
													),
										/*
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
																		7 => array("value" => "Grupo H", "label" => "Grupo H"),
																		8 => array("value" => "Octavos", "label" => "Octavos de Final"),
																		9 => array("value" => "Cuartos", "label" => "Cuartos de Final"),
																		10 => array("value" => "Semifinal", "label" => "Semifinal"),
																		11 => array("value" => "Final", "label" => "Final"),
																		12 => array("value" => "3er_y_4to", "label" => "3er y 4to puesto")
																		),
													'visibility' => 'details|save|list',
													),
										*/
										'phase' => array( 'label' => 'Fase',
													'type' => 'select',
													'options' => array(
																		0 => array("value" => "zone", "label" => "Zonas"),
																		1 => array("value" => "8", "label" => "8vos de final"),
																		2 => array("value" => "4", "label" => "4tos de final"),
																		3 => array("value" => "semi", "label" => "Semifinal"),
																		4 => array("value" => "3y4", "label" => "3er y 4to Puesto"),
																		5 => array("value" => "final", "label" => "Final"),
																		),
													'visibility' => 'details|save|list',
													),
										'tournament_date' => array( 'label' => 'Fecha (grupos)',
													'type' => 'text',
													'description' => 'Válido para la fase grupos. 1, 2 o 3',
													'visibility' => 'details|save|list',
													),	
										'phase_order' => array( 'label' => 'Orden',
													'type' => 'text',
													'description' => 'Campo válido para fase torneo únicamente',
													'visibility' => 'details|save|list',
													),								
										'team1_id' => array( 'label' => 'Equipo 1',
													'type' => 'select',
													'validation' => 'required',
													'visibility' => 'save',
													'source_table' => 'teams',
													'source_condition' => "",
													'source_index_id' => 'team_id',
													'source_fields' => array('name')
													),
										'team1_name' => array( 'label' => 'Equipo 1',
													'type' => 'text',
													'visibility' => 'list|details',
													),
										'team2_id' => array(	'label' => 'Equipo 2',
													'type' => 'select',
													'validation' => 'required',
													'visibility' => 'save',
													'source_table' => 'teams',
													'source_condition' => "",
													'source_index_id' => 'team_id',
													'source_fields' => array('name')
													),
										'team2_name' => array( 'label' => 'Equipo 2',
													'type' => 'text',
													'visibility' => 'list|details',
													),
										'team1_goals' => array(	'label' => 'Goles equipo 1',
															'type' => 'text',
															'class' => 'number',
															'validation' => '',
															'visibility' => 'save|details|list'
													),
										'team2_goals' => array(	'label' => 'Goles equipo 2',
															'type' => 'text',
															'class' => 'number',
															'validation' => '',
															'visibility' => 'save|details|list'
													),
										'penalties' => array(	'label' => 'Penales',
												'type' => 'text',
												'class' => 'number',
												'validation' => '',
												'description' => 'ej: 4-2', 
												'visibility' => 'save|details|list'
										),
										'result' => array(	'label' => 'Resultado',
															'type' => 'radio',
															'options' => array( 0 => array("value" => "-2","label" => "Por Jugar"),
																			    1 => array("value" => "-1","label" => "Ganó equipo 1"),
																				2 => array("value" => "0","label" => "Empate"),
																				3 => array("value" => "1","label" => "Ganó equipo 2"),
																				),
															'validation' => '',
															'value' => '-2',
															'visibility' => 'save|details|list'
														),
										'active'	=> array(	'label' => 'Activo',
																'type' => 'hidden',
																'value' => 1,
																'visibility' => 'save|details'
																)						
										);

$config['modules'][$module_name]['top_menu_actions'] = array( 	'matchs_list' => array('url' => '#match/show_list','method' => 'show_list', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-clipboard", 'label' => "Listado de ".$config['modules'][$module_name]['module_label']),
																'add_match' => array('url' => '#match/create','method' => 'create', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-plusthick", 'label' => "Agregar ".$config['modules'][$module_name]['module_unit']));

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