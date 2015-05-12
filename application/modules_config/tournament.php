<?

$module_name = 'tournament';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/tournament',
											'main_model' => 'admin/tournament_model',
										 	'module_label' => 'Torneos',
											'module_unit' => 'Torneo',
										 );

$config['modules'][$module_name]['fields'] = array(	
										'name' => array(	'label' => 'Nombre',
															'type' => 'text',
															'class' => 'title',
															'validation' => 'required',
															'visibility' => 'save|details|list'
														),
										'description'=> array(	'label' => 'Detalle',
															'type' => 'textarea',
															'class' => 'tinymce',
															'validation' => '',
															'visibility' => 'save|details|list'
														),
										'main_image' => array(	'label' => 'Imagen selección',
																	'type' => 'image',
																	'tag' => 'main_image',
																	'validation' => '',
																	'visibility' => 'details|save',
																	),
										'active'	=> array(	'label' => 'Activo',
																'type' => 'checkbox',
																'value' => 1,
																'visibility' => 'save|details|list'
																)						
										);

$config['modules'][$module_name]['top_menu_actions'] = array( 	'tournaments_list' => array('url' => '#tournament/show_list','method' => 'show_list', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-clipboard", 'label' => "Lista"),
																'add_tournament' => array('url' => '#tournament/create','method' => 'create', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-plusthick", 'label' => "Agregar"));

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
																'dialog' => 'Borrar torneo?',
											 					'url' => '#'.$module_name.'/delete/'),
											);
?>