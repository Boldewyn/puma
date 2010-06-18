<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you se t a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.
|
*/

$route['default_controller'] = 'explore';
$route['scaffolding_trigger'] = '';

$route['login/nds:any'] = 'login/nds';
$route['group/(:any)'] = 'usercontroller/group/$1';
$route['option/set/(:any)'] = 'usercontroller/option/set/$1';
$route['option/get/(:any)'] = 'usercontroller/option/get/$1';
$route['user/(:any)'] = 'usercontroller/index/$1';
$route['user'] = 'usercontroller/index';
$route['help/(:any)'] = 'help/index/$1';

$route['wiki/(:any):(:any)'] = 'wiki/dispatch/$1/$2';
$route['wiki/(:any)'] = 'wiki/item/$1';

$route['explore/([a-z]+)s$'] = 'explore/$1';



//__END__
