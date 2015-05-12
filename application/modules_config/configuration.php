<?

$module_name = 'configuration';
$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/configuration',
											'main_model' => 'admin/configuration_model',
										 	'module_label' => 'Configuracion',
											'module_unit' => 'Item',
											'restrictions' => 'own_content'
										 );
										 
$config['modules'][$module_name]['fields'] = array(	

										'address' => array(	'label' => 'Direcci&oacute;n',
															'type' => 'text',
															'validation' => '',
															'visibility' => 'edit_conf|details'
															),

										'telephone' => array('label' => 'Tel&eacute;fono',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details|list'
																),

										'text_footer' => array('label' => 'Texto footer',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details'
																),

										'url_facebook' => array('label' => 'Facebook URL',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details|list'
																),

										'url_twitter' => array('label' => 'Twitter URL',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details|list'
																),
										
										'url_googleplus' => array('label' => 'Google Plus URL',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details'
																),
										
										'url_youtube' => array('label' => 'Youtube URL',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details'
																),

										'email' => array(		'label' => 'Email',
																'type' => 'text',
																'validation' => 'required',
																'visibility' => 'edit_conf|details|list'
																),
																
										'form_emails' => array(	'label' => 'Emails recibo de formulario',
																'type' => 'text',
																'validation' => '',
																'visibility' => 'edit_conf|details|list'
																),
																);


$config['modules'][$module_name]['top_menu_actions'] = array( 	'configuration_list' => array('url' => '#user/show_list', 'class_name' => 'configuration', 'method' => 'show_list', 'icon' => "ui-icon-clipboard", 'label' => "Listado de Usuarios"),
							'add_user' => array('url' => '#user/create', 'method' => 'create', 'icon' => "ui-icon-plusthick", 'label' => "Agregar Usuario")
												
												);


$config['modules'][$module_name]['main_model_tabs'] = array( 	'details' => array( 'label' => 'Detalle',
														'url' => '#user/details/'),
									'edit' => array( 	'label' => 'Editar',
														'url' => '#user/edit/'),
									'edit_conf' => array( 	'label' => 'Editar Configuracion',
														'url' => '#'.$module_name.'/edit_conf/'),
									
									'edit_password' => array( 	'label' => 'Modificar Contrase&ntilde;a',
														'url' => '#user/edit_password/'));