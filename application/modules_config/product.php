<?

$module_name = 'product';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/product',
											'main_model' => 'admin/product_model',
										 	'module_label' => 'Vehículos',
											'module_unit' => 'Vehículo',
										 );

$config['modules'][$module_name]['fields'] = array(	

										'name' => array(	'label' => 'Nombre',
															'type' => 'text',
															'class' => 'title',
															'validation' => 'required',
															'visibility' => 'save|details|list'
														),
										'description'=> array(	'label' => 'Detalle',
															'type' => 'text',
															'class' => 'title',
															'validation' => 'required',
															'visibility' => 'save|details|list'
														),
										'main_image' => array(	'label' => 'Imagen selección',
																	'type' => 'image',
																	'tag' => 'main_image',
																	'validation' => '',
																	'visibility' => 'details|save',
																	),
										'complete_image' => array(	'label' => 'Imagen completa',
																	'type' => 'image',
																	'tag' => 'complete_image',
																	'validation' => '',
																	'visibility' => 'details|save',
																	),							
						
										'active'	=> array(	'label' => 'Activo',
																'type' => 'checkbox',
																'value' => 1,
																'visibility' => 'save|details|list'
																),
										'hits'	=> array(	'label' => 'Votos',
															'type' => 'text',
															'class' => 'number',
															'visibility' => 'details|list'
																),						
										);

$config['modules'][$module_name]['top_menu_actions'] = array( 	'products_list' => array('url' => '#product/show_list','method' => 'show_list', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-clipboard", 'label' => "Listado de ".$config['modules'][$module_name]['module_label']),
																'add_product' => array('url' => '#product/create','method' => 'create', 'class_name' => $config['modules'][$module_name], 'icon' => "ui-icon-plusthick", 'label' => "Agregar ".$config['modules'][$module_name]['module_unit']));

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