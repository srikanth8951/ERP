<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['privacy-policy'] = 'Privacy_policy/index';
$route['terms-and-condition'] = 'Terms_and_condition/index';

$route['forgot_password'] = 'admin/Password/forgot';

//Contract/Job
$route['admin/contract_job'] = 'admin/contract_job/Contract_job/index';
$route['admin/contract_job/add'] = 'admin/contract_job/Contract_job/add';
$route['admin/contract_job/update/:num'] = 'admin/contract_job/Contract_job/update';
$route['admin/contract_job/renew/:num'] = 'admin/contract_job/Contract_job/renew';
$route['admin/contract_job/operation_and_maintenance'] = 'admin/contract_job/Operation_and_maintenance/index';
$route['admin/contract_job/operation_and_maintenance/add'] = 'admin/contract_job/Operation_and_maintenance/add';
$route['admin/contract_job/operation_and_maintenance/update/:num'] = 'admin/contract_job/Operation_and_maintenance/update';
$route['admin/contract_job/operation_and_maintenance/renew/:num'] = 'admin/contract_job/Operation_and_maintenance/renew';

$route['admin/national_head'] = 'admin/employee/National_head/index';
$route['admin/national_head/add'] = 'admin/employee/National_head/add';
$route['admin/national_head/edit/:num'] = 'admin/employee/National_head/edit';

$route['admin/aisd_head'] = 'admin/employee/All_india_service_delivery_head/index';
$route['admin/aisd_head/add'] = 'admin/employee/All_india_service_delivery_head/add';
$route['admin/aisd_head/view/:num'] = 'admin/employee/All_india_service_delivery_head/view';
$route['admin/aisd_head/edit/:num'] = 'admin/employee/All_india_service_delivery_head/edit';

$route['admin/regional_head'] = 'admin/employee/region_head/index';
$route['admin/regional_head/add'] = 'admin/employee/region_head/add';
$route['admin/regional_head/view/:num'] = 'admin/employee/region_head/view';
$route['admin/regional_head/edit/:num'] = 'admin/employee/region_head/edit';

$route['admin/rsd_head'] = 'admin/employee/Regional_service_delivery_head/index';
$route['admin/rsd_head/add'] = 'admin/employee/Regional_service_delivery_head/add';
$route['admin/rsd_head/view/:num'] = 'admin/employee/Regional_service_delivery_head/view';
$route['admin/rsd_head/edit/:num'] = 'admin/employee/Regional_service_delivery_head/edit';

$route['admin/asd_head'] = 'admin/employee/Area_service_delivery_head/index';
$route['admin/asd_head/add'] = 'admin/employee/Area_service_delivery_head/add';
$route['admin/asd_head/view/:num'] = 'admin/employee/Area_service_delivery_head/view';
$route['admin/asd_head/edit/:num'] = 'admin/employee/Area_service_delivery_head/edit';

$route['admin/area_head'] = 'admin/employee/area_head/index';
$route['admin/area_head/add'] = 'admin/employee/area_head/add';
$route['admin/area_head/view/:num'] = 'admin/employee/area_head/view';
$route['admin/area_head/edit/:num'] = 'admin/employee/area_head/edit';

$route['admin/cam'] = 'admin/employee/Client_account_manager/index';
$route['admin/cam/add'] = 'admin/employee/Client_account_manager/add';
$route['admin/cam/view/:num'] = 'admin/employee/Client_account_manager/view';
$route['admin/cam/edit/:num'] = 'admin/employee/Client_account_manager/edit';

$route['admin/dmt'] = 'admin/employee/Data_management_team/index';
$route['admin/dmt/add'] = 'admin/employee/Data_management_team/add';
$route['admin/dmt/view/:num'] = 'admin/employee/Data_management_team/view';
$route['admin/dmt/edit/:num'] = 'admin/employee/Data_management_team/edit';

$route['admin/engineer'] = 'admin/employee/Engineer/index';
$route['admin/engineer/add'] = 'admin/employee/Engineer/add';
$route['admin/engineer/view/:num'] = 'admin/employee/Engineer/view';
$route['admin/engineer/edit/:num'] = 'admin/employee/Engineer/edit';

$route['admin/manager'] = 'admin/employee/Manager/index';
$route['admin/manager/add'] = 'admin/employee/Manager/add';
$route['admin/manager/view/:num'] = 'admin/employee/Manager/view';
$route['admin/manager/edit/:num'] = 'admin/employee/Manager/edit';

$route['admin/technician'] = 'admin/employee/Technician/index';
$route['admin/technician/add'] = 'admin/employee/Technician/add';
$route['admin/technician/view/:num'] = 'admin/employee/Technician/view';
$route['admin/technician/edit/:num'] = 'admin/employee/Technician/edit';

$route['admin/supervisor'] = 'admin/employee/Supervisor/index';
$route['admin/supervisor/add'] = 'admin/employee/Supervisor/add';
$route['admin/supervisor/view/:num'] = 'admin/employee/Supervisor/view';
$route['admin/supervisor/edit/:num'] = 'admin/employee/Supervisor/edit';

// Checklist
$route['admin/catalog/checklist'] = 'admin/catalog/checklist/index';
$route['admin/catalog/checklist/ppm'] = 'admin/catalog/checklist/index';
$route['admin/catalog/checklist/daily'] = 'admin/catalog/checklist/index';
$route['admin/catalog/checklist/view/:num'] = 'admin/catalog/checklist/view';
$route['admin/catalog/checklist/ppm/view/:num'] = 'admin/catalog/checklist/view';
$route['admin/catalog/checklist/daily/view/:num'] = 'admin/catalog/checklist/view';

$route['admin/contract_job_log/:num'] = 'admin/Contract_job_log/index';

$route['admin/store/requests/view/:num'] = 'admin/store/Request/view';

// $route['admin/store/users'] = 'admin/store/employee/nationalHead/users/index';
// $route['admin/store/employee/nationalHead/users/add'] = 'admin/store/employee/nationalHead/users/add';
// $route['admin/store/employee/nationalHead/users/view/:num'] = 'admin/store/employee/nationalHead/users/view';
// $route['admin/store/employee/nationalHead/users/edit/:num'] = 'admin/store/employee/nationalHead/users/edit';

$route['employee'] = 'employee/login';
$route['employee/dmt'] = 'employee/dmt/index';
$route['employee/dmt/contract_job_log/:num'] = 'employee/dmt/Contract_job_log/index';

$route['employee/aisd'] = 'employee/aisd/index';
$route['employee/aisd/edit/:num'] = 'employee/aisd/edit';
$route['employee/aisd/contract_job_log/:num'] = 'employee/aisd/Contract_job_log/index';

$route['employee/regionHead'] = 'employee/regionHead/index';
$route['employee/regionHead/edit/:num'] = 'employee/regionHead/edit';
$route['employee/regionHead/contract_job_log/:num'] = 'employee/regionHead/Contract_job_log/index';

$route['employee/rsd'] = 'employee/rsd/index';
$route['employee/rsd/edit/:num'] = 'employee/rsd/edit';
$route['employee/rsd/contract_job_log/:num'] = 'employee/rsd/Contract_job_log/index';

$route['employee/asd'] = 'employee/asd/index';
$route['employee/asd/edit/:num'] = 'employee/asd/edit';
$route['employee/asd/contract_job_log/:num'] = 'employee/asd/Contract_job_log/index';

$route['employee/areaHead'] = 'employee/areaHead/index';
$route['employee/areaHead/edit/:num'] = 'employee/areaHead/edit';
$route['employee/areaHead/contract_job_log/:num'] = 'employee/areaHead/Contract_job_log/index';

$route['employee/nationalHead'] = 'employee/nationalHead/index';
$route['employee/nationalHead/edit/:num'] = 'employee/nationalHead/edit';
$route['employee/nationalHead/contract_job_log/:num'] = 'employee/nationalHead/Contract_job_log/index';

$route['employee/cam'] = 'employee/cam/index';
$route['employee/cam/edit/:num'] = 'employee/cam/edit';
$route['employee/cam/contract_job_log/:num'] = 'employee/cam/Contract_job_log/index';

$route['employee/engineer'] = 'employee/engineer/index';
$route['employee/engineer/edit/:num'] = 'employee/engineer/edit';
$route['employee/engineer/contract_job_log/:num'] = 'employee/engineer/Contract_job_log/index';

$route['employee/customer'] = 'employee/customer/index';
$route['employee/customer/edit/:num'] = 'employee/customer/edit';

$route['employee/store/requests/view/:num'] = 'employee/store/Request/view';

$route['admin/attributegroup'] = 'admin/AttributeGroup/index';

// employee list under users login

// employee list under national Head
$route['employee/nationalHead/users/aisd_head'] = 'employee/nationalHead/users/All_india_service_delivery_head/index';
$route['employee/nationalHead/users/regional_head'] = 'employee/nationalHead/users/region_head/index';
$route['employee/nationalHead/users/regional_head/view/:num'] = 'employee/nationalHead/users/region_head/view';
$route['employee/nationalHead/users/rsd_head'] = 'employee/nationalHead/users/Regional_service_delivery_head/index';
$route['employee/nationalHead/users/rsd_head/view/:num'] = 'employee/nationalHead/users/Regional_service_delivery_head/view';
$route['employee/nationalHead/users/asd_head'] = 'employee/nationalHead/users/Area_service_delivery_head/index';
$route['employee/nationalHead/users/asd_head/view/:num'] = 'employee/nationalHead/users/Area_service_delivery_head/view';
$route['employee/nationalHead/users/area_head'] = 'employee/nationalHead/users/area_head/index';
$route['employee/nationalHead/users/area_head/view/:num'] = 'employee/nationalHead/users/area_head/view';
$route['employee/nationalHead/users/cam'] = 'employee/nationalHead/users/Client_account_manager/index';
$route['employee/nationalHead/users/cam/view/:num'] = 'employee/nationalHead/users/Client_account_manager/view';
$route['employee/nationalHead/users/engineer'] = 'employee/nationalHead/users/Engineer/index';
$route['employee/nationalHead/users/engineer/view/:num'] = 'employee/nationalHead/users/Engineer/view';

// employee list under All India service delivery Head
$route['employee/aisd/users/regional_head'] = 'employee/aisd/users/region_head/index';
$route['employee/aisd/users/regional_head/view/:num'] = 'employee/aisd/users/Region_head/view';
$route['employee/aisd/users/rsd_head'] = 'employee/aisd/users/Regional_service_delivery_head/index';
$route['employee/aisd/users/rsd_head/view/:num'] = 'employee/aisd/users/Regional_service_delivery_head/view';
$route['employee/aisd/users/asd_head'] = 'employee/aisd/users/Area_service_delivery_head/index';
$route['employee/aisd/users/asd_head/view/:num'] = 'employee/aisd/users/Area_service_delivery_head/view';
$route['employee/aisd/users/area_head'] = 'employee/aisd/users/area_head/index';
$route['employee/aisd/users/area_head/view/:num'] = 'employee/aisd/users/area_head/view';
$route['employee/aisd/users/cam'] = 'employee/aisd/users/Client_account_manager/index';
$route['employee/aisd/users/cam/view/:num'] = 'employee/aisd/users/Client_account_manager/view';
$route['employee/aisd/users/engineer'] = 'employee/aisd/users/Engineer/index';
$route['employee/aisd/users/engineer/view/:num'] = 'employee/aisd/users/Engineer/view';

// employee list under Regional Head
$route['employee/regionHead/users/rsd_head'] = 'employee/regionHead/users/Regional_service_delivery_head/index';
$route['employee/regionHead/users/rsd_head/view/:num'] = 'employee/regionHead/users/Regional_service_delivery_head/view';
$route['employee/regionHead/users/asd_head'] = 'employee/regionHead/users/Area_service_delivery_head/index';
$route['employee/regionHead/users/asd_head/view/:num'] = 'employee/regionHead/users/Area_service_delivery_head/view';
$route['employee/regionHead/users/area_head'] = 'employee/regionHead/users/area_head/index';
$route['employee/regionHead/users/area_head/view/:num'] = 'employee/regionHead/users/area_head/view';
$route['employee/regionHead/users/cam'] = 'employee/regionHead/users/Client_account_manager/index';
$route['employee/regionHead/users/cam/view/:num'] = 'employee/regionHead/users/Client_account_manager/view';
$route['employee/regionHead/users/engineer'] = 'employee/regionHead/users/Engineer/index';
$route['employee/regionHead/users/engineer/view/:num'] = 'employee/regionHead/users/Engineer/view';

// employee list under Regional service delivery Head
$route['employee/rsd/users/asd_head'] = 'employee/rsd/users/Area_service_delivery_head/index';
$route['employee/rsd/users/asd_head/view/:num'] = 'employee/rsd/users/Area_service_delivery_head/view';
$route['employee/rsd/users/area_head'] = 'employee/rsd/users/area_head/index';
$route['employee/rsd/users/area_head/view/:num'] = 'employee/rsd/users/area_head/view';
$route['employee/rsd/users/cam'] = 'employee/rsd/users/Client_account_manager/index';
$route['employee/rsd/users/cam/view/:num'] = 'employee/rsd/users/Client_account_manager/view';
$route['employee/rsd/users/engineer'] = 'employee/rsd/users/Engineer/index';
$route['employee/rsd/users/engineer/view/:num'] = 'employee/rsd/users/Engineer/view';

// employee list under Area service delivery Head
$route['employee/asd/users/asd_head'] = 'employee/asd/users/Area_service_delivery_head/index';
$route['employee/asd/users/asd_head/view/:num'] = 'employee/asd/users/Area_service_delivery_head/view';
$route['employee/asd/users/area_head'] = 'employee/asd/users/area_head/index';
$route['employee/asd/users/area_head/view/:num'] = 'employee/asd/users/area_head/view';
$route['employee/asd/users/cam'] = 'employee/asd/users/Client_account_manager/index';
$route['employee/asd/users/cam/view/:num'] = 'employee/asd/users/Client_account_manager/view';
$route['employee/asd/users/engineer'] = 'employee/asd/users/Engineer/index';
$route['employee/asd/users/engineer/view/:num'] = 'employee/asd/users/Engineer/view';

// employee list under Client Account Manager
$route['employee/cam/users/cam'] = 'employee/cam/users/Client_account_manager/index';
$route['employee/cam/users/cam/view/:num'] = 'employee/cam/users/Client_account_manager/view';
$route['employee/cam/users/engineer'] = 'employee/cam/users/Engineer/index';
$route['employee/cam/users/engineer/view/:num'] = 'employee/cam/users/Engineer/view';