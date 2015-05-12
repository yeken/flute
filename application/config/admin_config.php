<?

$config['general']['admin'] = array('home_controller' => 'admin/x/',
									'user_session' => 'user',
									'start_page' => 'matches/show_list',
									'login_page' => 'admin/user/login',
									'login_logo' => 'chepire_big.png',
									'admin_logo' => 'chepire_small.png'
									);

$config['general']['admin']['sections'] = array(
									'user' =>array('url' => '#user/show_list', 'name' => 'Clientes', 'admin_only' => true, 'icon' => 'fa-user'),
									'client_files' =>array('url' => '#user/media_gallery/file', 'name' => 'Archivos', 'client_only' => true, 'icon' => 'fa-file-text-o'),
									//'documents' =>array('url' => '#documents/show_list', 'name' => 'Facturas', 'icon' => 'fa-file-text-o'),
									);

$config['general']['admin']['table_icons'] = array(	'details' => 'fa fa-eye',
													'save' => 'fa fa-pencil',
													'edit' => 'fa fa-pencil',
													'files' => 'fa fa-file-text-o',
													'delete' => 'fa fa-trash-o');
													
foreach(glob( dirname(__FILE__)."/../modules_config/*.php") as $filename)
{
    include $filename;
}

?>