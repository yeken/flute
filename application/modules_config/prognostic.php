<?

$module_name = 'prognostic';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/prognostic',
											'main_model' => 'admin/prognostic_model',
										 	'module_label' => 'Pronosticos',
											'module_unit' => 'Pronostico',
										 );

$config['modules'][$module_name]['fields'] = array(	

										'tournament' => array(	'label' => 'Torneo',
															'type' => 'text',
															'class' => 'title',
															'visibility' => 'list'
														),
										'tournament_id' => array('label' => 'Torneo',
															'type' => 'select',
															'class' => 'title',
															'source_table' => 'tournaments',
															'source_condition' => "",
															'source_index_id' => 'tournament_id',
															'source_fields' => array('name'),
															'visibility' => 'save|details'
														),
										'match_id' => array('label' => 'Partido',
															'type' => 'select',
															'source_table' => 'matches',
															'source_index_id' => 'match_id',
															'source_fields' => array('zone','team1_name','team2_name'),
															'visibility' => 'save'
														),
										'matchname' => array(	'label' => 'Partido',
															'type' => 'text',
															'disabled' => true,
															'class' => 'title',
															'visibility' => 'list|details'
														),
										'user_id' => array(	'label' => 'Jugador',
															'type' => 'select',
															'class' => 'title',
															'source_table' => 'bitauth_users',
															'source_condition' => "group_id = '3'",
															'source_index_id' => 'user_id',
															'source_fields' => array('username'),
															'visibility' => 'save'
														),
										'result' => array(	'label' => 'Resultado',
															'type' => 'radio',
															'options' => array( 
																			    1 => array("value" => "-1","label" => "Gana equipo 1"),
																				2 => array("value" => "0","label" => "Empate"),
																				3 => array("value" => "1","label" => "Gana equipo 2"),
																				),
															'validation' => '',
															'visibility' => 'details|list'
														),				
										'username' => array(	'label' => 'Jugador',
															'type' => 'text',
															'class' => 'title',
															'visibility' => 'list|details'
														),
										'team1_goals' => array(	'label' => 'Goles equipo 1',
															'type' => 'text',
															'class' => 'spinner',
															'validation' => 'required|is_natural',
															'visibility' => 'save|details|list'
														),
										'team2_goals' => array(	'label' => 'Goles equipo 2',
															'type' => 'text',
															'class' => 'spinner',
															'validation' => 'required|is_natural',
															'visibility' => 'save|details|list'
														),	
										);

$config['modules'][$module_name]['top_menu_actions'] = array( 	'prognostics_list' => array('url' => '#prognostic/show_list','method' => 'show_list', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-clipboard", 'label' => "Listado de ".$config['modules'][$module_name]['module_label']),
																'add_prognostic' => array('url' => '#prognostic/create','method' => 'create', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-plusthick", 'label' => "Agregar ".$config['modules'][$module_name]['module_unit']));

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
																'dialog' => 'Borrar jugador?',
											 					'url' => '#'.$module_name.'/delete/'),
											);
?>