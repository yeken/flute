<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['admin'] = "admin/user";
$route['default_controller'] = "home";
$route['404_override'] = '';

$route['usuario/editar-perfil'] = "usuario/editar_perfil";
$route['muro'] = "home/wall";
$route['muro/(:num)'] = "home/wall/$1";
$route['mi-cuenta'] = "home/profile/";
$route['perfil/(:num)/(:any)'] = "home/profile/$1/$2";
$route['editar-cuenta'] = "home/edit_profile/";
$route['primer-ingreso'] = "home/first_login/";
$route['completar-registro'] = "home/complete_register/";
$route['log-in'] = "home/login/";
$route['log-out'] = "home/logout/";
$route['olvide-mi-clave'] = "front_user/forgot_password/";
$route['generame-la-clave/(:any)'] = "front_user/recover_password/$1";
$route['confirmar-cuenta/(:any)'] = "home/confirm_account/$1";
$route['cambiar-idioma/(:any)'] = "home/language/$1";

$route['admin/olvide-la-clave'] = "admin/user/forgot_password/";
$route['admin/generame-la-clave/(:any)'] = "admin/user/recover_password/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */