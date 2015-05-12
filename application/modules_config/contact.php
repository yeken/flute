<?

$module_name = 'contact';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/contact',
											'main_model' => 'admin/contact_model',
										 	'module_label' => 'Contactos',
											'module_unit' => 'Contacto',
											'restrictions' => 'own_content',
										 );

$config['modules'][$module_name]['fields'] = array(	
								
								'date_created' => array('label' => 'Fecha de ingreso',
														'type' => 'date_time',
														'filter' => 'date_range_filter',
														'visibility' => 'details|list'
														),						
								
								'name' => array(	'label' => 'Nombre y Apellido',
														'type' => 'text',
														'validation' => 'required',
														'visibility' => 'save|details|list'
														),
								'email' => array(		'label' => 'Email',
														'type' => 'text',
														'validation' => 'required',
														'visibility' => 'save|details|list'
														),
								'telephone' => array(	'label' => 'Teléfono',
														'type' => 'text',
														'validation' => 'required',
														'visibility' => 'save|details|list'
														),
								'province' => array(	'label' => 'Provincia',
														'type' => 'text',
														'validation' => '',
														'visibility' => 'details|list|edit'
														),
								'birthday' => array(	'label' => 'Fecha de nacimiento',
														'type' => 'text',
														'validation' => 'required',
														'visibility' => 'save|details|list'
														),
								'fb_uid' => array(		'label' => 'Facebook ID',
														'type' => 'text',
														'validation' => 'required',
														'visibility' => 'save|details|list'
														),																
														);


$config['modules'][$module_name]['top_menu_actions'] = array( 	'contact_list' => array('url' => '#contact/show_list', 'class_name' => 'contact', 'method' => 'show_list', 'icon' => "ui-icon-clipboard", 'label' => "Listado de Contactos"),
																'add_contact' => array('url' => '#contact/create', 'method' => 'create', 'class_name' => 'contact', 'icon' => "ui-icon-plusthick", 'label' => "Agregar Contacto"),
													);

$config['modules'][$module_name]['main_model_tabs'] = array( 	'details' => array( 'label' => 'Detalle',
																					'url' => '#'.$module_name.'/details/'),
																'edit' => array( 	'label' => 'Editar',
																					'url' => '#'.$module_name.'/edit/',
																					),
														);

$config['modules'][$module_name]['datatable_actions'] = array( 	'details' => array( 'label' => 'Detalle',
														'url' => '#'.$module_name.'/details/'),
																'delete' => array( 'label' => 'Borrar',
														'dialog' => 'Borrar el contacto?',
														'url' => '#'.$module_name.'/delete/'),
															);


?>