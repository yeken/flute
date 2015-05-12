<?

$module_name = 'user';

$config['modules'][$module_name] = array(
										 	'controller_name' => 'admin/files',
											'main_model' => 'user_model',
										 	'module_label' => 'Archivos',
											'module_unit' => 'Archivo',
											'views_folder' => 'admin/auth',
											'list_condition' => 'group_id = 3',
											'admin_only' => 'false',
										 );


$config['modules'][$module_name]['fields'] = array(	
									'username' => array('label' => 'Usuario',
														'type' => 'text',
														'validation' => 'required',
														'visibility' => 'save|details|list'
														),
									'email' => array(	'label' => 'Email',
														'type' => 'text',
														'validation' => 'required|valid_email',
														'visibility' => 'save|details|list'
														),
									'password' => array('label' => 'Contrase&ntilde;a',
														'type' => 'password',
														'validation' => 'required|matches[passconf]',
														'visibility' => 'create|edit_password'
														),
									'passconf' => array('label' => 'Repetir contrase&ntilde;a',
														'type' => 'password',
														'validation' => 'required',
														'visibility' => 'create|edit_password'
														),
									
									'media_gallery' => array('label' => 'Archivos',
														'type' => 'file',
														'upload_label' => 'Seleccionar archivo',
														'groups' => array('Factura','Recibo','Comprobante de retención'),
														'groups_label' => 'Tipo',
														'validation' => '',
														'visibility' => 'media_gallery'
														),
									
									'fullname' => array(	'label' => 'Nombre completo',
															'type' => 'text',
															'visibility' => 'save|details|list'
															),
									
									'active'	=> array(	'label' => 'Activo',
															'type' => 'checkbox',
															'value' => 1,
															'visibility' => 'save|details|list',
															),
									'group_id' => array( 'label' => 'Rol',
														'type' => 'hidden',
														'value' => 2,
												));	



$config['modules'][$module_name]['top_menu_actions'] = array( 	'users_list' => array(	'method' => 'view_files',
																						'url' => '#files/view_files',
																						'icon' => "ui-icon-clipboard", 
																						'label' => "Lista"));

$config['modules'][$module_name]['main_model_tabs'] = array( 	'details' => array( 'label' => 'Detalle',
														'url' => '#'.$module_name.'/details/'),
									'edit' => array( 	'label' => 'Editar',
														'url' => '#'.$module_name.'/edit/'),
									'media_gallery' => array( 	'label' => 'Archivos',
														'url' => '#'.$module_name.'/media_gallery/file/'),
									'edit_password' => array( 	'label' => 'Modificar Password',
														'url' => '#'.$module_name.'/edit_password/'));

$config['modules'][$module_name]['datatable_actions'] = array( 
									'files' => array( 	'label' => 'Archivos',
														'url' => '#'.$module_name.'/media_gallery/file/'),
									
									'details' => array( 'label' => 'Detalle',
														'url' => '#'.$module_name.'/details/'),
									'edit' => array( 	'label' => 'Editar',
														'url' => '#'.$module_name.'/edit/'),
									
									'delete' => array( 	'label' => 'Borrar',
														'dialog' => 'borrar el Usuario?',
														'url' => '#user/delete/'),					
									
														);


?>