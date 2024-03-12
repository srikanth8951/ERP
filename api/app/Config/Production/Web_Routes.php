<?php

// Admin
$routes->post('login', 'Account::login');
$routes->post('forgot_password/init_reset_by_email', 'ForgotPassword::sendPasswordResetMail');
$routes->post('forgot_password/recover_by_email', 'ForgotPassword::recoverPasswordByEmail');
$routes->post('forgot_password/reset_by_email', 'ForgotPassword::resetPasswordByEmail');
$routes->post('change_password', 'Account::changePassword', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->post('checkLoggedin', 'Admin\Account::checkLoggedin');
    
    $routes->post('logout', 'Admin\Account::logout');
    $routes->get('localisation/country/list', 'Admin\Localisation::autocompleteCountry');
    $routes->get('localisation/state/list', 'Admin\Localisation::autocompleteState');
    $routes->get('localisation/city/list', 'Admin\Localisation::autocompleteCity');
    $routes->get('localisation/currency/autocomplete', 'Admin\Localisation::autocompleteCurrency');
    $routes->get('contract_type/autocomplete', 'Admin\ContractType::autocomplete');
    $routes->get('contract_status/autocomplete', 'Admin\ContractStatus::autocomplete');

    // Contract job
    $routes->get('contract_job/list', 'Admin\ContractJob::index');
    $routes->get('contract_job/detail', 'Admin\ContractJob::getContractJob');
    $routes->post('contract_job/add', 'Admin\ContractJob::addContractJob');
    $routes->post('contract_job/renew', 'Admin\ContractJob::renewContractJob');
    $routes->post('contract_job/update', 'Admin\ContractJob::updateContractJob');
    $routes->post('contract_job/delete', 'Admin\ContractJob::deleteContractJob');
    $routes->post('contract_job/status/update', 'Admin\ContractJob::setContractJobStatus');

    $routes->get('contract_job/asset/list', 'Admin\ContractJob::getContractJobAssets');
    $routes->get('contract_job/asset/checklists', 'Admin\ContractJob::getContractJobAssetChecklists');

    //Profile update
    $routes->post('profile/detail', 'Admin\Profile::getEmployee');
    $routes->post('profile/edit', 'Admin\Profile::editEmployee');

    // Region
    $routes->get('region/list', 'Admin\Region::index');
    $routes->get('region/detail', 'Admin\Region::getRegion');
    $routes->post('region/add', 'Admin\Region::addRegion');
    $routes->post('region/edit', 'Admin\Region::editRegion');
    $routes->post('region/delete', 'Admin\Region::deleteRegion');
    $routes->get('region/autocomplete', 'Admin\Region::autocomplete');

    // Area
    $routes->get('area/list', 'Admin\Area::index');
    $routes->get('area/detail', 'Admin\Area::getArea');
    $routes->post('area/add', 'Admin\Area::addArea');
    $routes->post('area/edit', 'Admin\Area::editArea');
    $routes->post('area/delete', 'Admin\Area::deleteArea');
    $routes->get('area/autocomplete', 'Admin\Area::autocomplete');
    $routes->get('area/downloadSample', 'Admin\Area::downloadSample');

    // Branch
    $routes->get('branch/list', 'Admin\Branch::index');
    $routes->get('branch/detail', 'Admin\Branch::getBranch');
    $routes->post('branch/add', 'Admin\Branch::addBranch');
    $routes->post('branch/edit', 'Admin\Branch::editBranch');
    $routes->post('branch/delete', 'Admin\Branch::deleteBranch');
    $routes->get('branch/autocomplete', 'Admin\Branch::autocomplete');
    $routes->get('branch/downloadSample', 'Admin\Branch::downloadSample');

    // Designation
    $routes->get('designation/list', 'Admin\Designation::index');
    $routes->get('designation/detail', 'Admin\Designation::getDesignation');
    $routes->post('designation/add', 'Admin\Designation::addDesignation');
    $routes->post('designation/edit', 'Admin\Designation::editDesignation');
    $routes->post('designation/delete', 'Admin\Designation::deleteDesignation');
    $routes->get('designation/autocomplete', 'Admin\Designation::autocomplete');

    // Department
    $routes->get('department/list', 'Admin\Department::index');
    $routes->get('department/detail', 'Admin\Department::getDepartment');
    $routes->post('department/add', 'Admin\Department::addDepartment');
    $routes->post('department/edit', 'Admin\Department::editDepartment');
    $routes->post('department/delete', 'Admin\Department::deleteDepartment');
    $routes->get('department/autocomplete', 'Admin\Department::autocomplete');

    // Nature of Content
    $routes->get('contract_nature/list', 'Admin\ContractNature::index');
    $routes->get('contract_nature/detail', 'Admin\ContractNature::getContractNature');
    $routes->post('contract_nature/add', 'Admin\ContractNature::addContractNature');
    $routes->post('contract_nature/edit', 'Admin\ContractNature::editContractNature');
    $routes->post('contract_nature/delete', 'Admin\ContractNature::deleteContractNature');
    $routes->get('contract_nature/autocomplete', 'Admin\ContractNature::autocomplete');

    // Payment Terms
    $routes->get('payment_term/list', 'Admin\PaymentTerm::index');
    $routes->get('payment_term/detail', 'Admin\PaymentTerm::getPaymentTerm');
    $routes->post('payment_term/add', 'Admin\PaymentTerm::addPaymentTerm');
    $routes->post('payment_term/edit', 'Admin\PaymentTerm::editPaymentTerm');
    $routes->post('payment_term/delete', 'Admin\PaymentTerm::deletePaymentTerm');
    $routes->get('payment_term/autocomplete', 'Admin\PaymentTerm::autocomplete');

    // Work Expertise
    $routes->get('work_expertise/list', 'Admin\WorkExpertise::index');
    $routes->get('work_expertise/detail', 'Admin\WorkExpertise::getWorkExpertise');
    $routes->post('work_expertise/add', 'Admin\WorkExpertise::addWorkExpertise');
    $routes->post('work_expertise/edit', 'Admin\WorkExpertise::editWorkExpertise');
    $routes->post('work_expertise/delete', 'Admin\WorkExpertise::deleteWorkExpertise');
    $routes->get('work_expertise/autocomplete', 'Admin\WorkExpertise::autocomplete');

    // Asset Group
    $routes->get('asset/group/list', 'Admin\AssetGroup::index');
    $routes->get('asset/group/sub_group/list', 'Admin\AssetGroup::getSubGroupDetails');
    $routes->get('asset/group/detail', 'Admin\AssetGroup::getGroup');
    $routes->post('asset/group/add', 'Admin\AssetGroup::addGroup');
    $routes->post('asset/group/edit', 'Admin\AssetGroup::editGroup');
    $routes->post('asset/group/delete', 'Admin\AssetGroup::deleteGroup');
    $routes->get('asset/group/autocomplete', 'Admin\AssetGroup::autocomplete');

    // Store Category
    $routes->get('store/category/list', 'Admin\Store\Category::index');
    $routes->get('store/category/sub_category/list', 'Admin\Store\Category::getSubCategoryDetails');
    $routes->get('store/category/detail', 'Admin\Store\Category::getCategory');
    $routes->post('store/category/add', 'Admin\Store\Category::addCategory');
    $routes->post('store/category/edit', 'Admin\Store\Category::editCategory');
    $routes->post('store/category/delete', 'Admin\Store\Category::deleteCategory');
    $routes->get('store/category/autocomplete', 'Admin\Store\Category::autocomplete');

    // Store Attribute group
    $routes->get('store/attribute_group/list', 'Admin\Store\AttributeGroup::index');
    $routes->get('store/attribute_group/detail', 'Admin\Store\AttributeGroup::getAttributeGroup');
    $routes->post('store/attribute_group/add', 'Admin\Store\AttributeGroup::addAttributeGroup');
    $routes->post('store/attribute_group/edit', 'Admin\Store\AttributeGroup::editAttributeGroup');
    $routes->post('store/attribute_group/delete', 'Admin\Store\AttributeGroup::deleteAttributeGroup');
    $routes->get('store/attribute_group/autocomplete', 'Admin\Store\AttributeGroup::autocomplete');

    // Store Attribute
    $routes->get('store/attribute/list', 'Admin\Store\Attribute::index');
    $routes->get('store/attribute/detail', 'Admin\Store\Attribute::getAttribute');
    $routes->post('store/attribute/add', 'Admin\Store\Attribute::addAttribute');
    $routes->post('store/attribute/edit', 'Admin\Store\Attribute::editAttribute');
    $routes->post('store/attribute/delete', 'Admin\Store\Attribute::deleteAttribute');
    $routes->get('store/attribute/autocomplete', 'Admin\Store\Attribute::autocomplete');

    // Asset
    $routes->get('asset/list', 'Admin\Asset::index');
    $routes->get('asset/detail', 'Admin\Asset::getAsset');
    $routes->post('asset/add', 'Admin\Asset::addAsset');
    $routes->post('asset/edit', 'Admin\Asset::editAsset');
    $routes->post('asset/delete', 'Admin\Asset::deleteAsset');
    $routes->get('asset/autocomplete', 'Admin\Asset::autocomplete');

    // Customer Sector
    $routes->get('customer_sector/list', 'Admin\CustomerSector::index');
    $routes->get('customer_sector/detail', 'Admin\CustomerSector::getCustomerSector');
    $routes->post('customer_sector/add', 'Admin\CustomerSector::addCustomerSector');
    $routes->post('customer_sector/edit', 'Admin\CustomerSector::editCustomerSector');
    $routes->post('customer_sector/delete', 'Admin\CustomerSector::deleteCustomerSector');
    $routes->get('customer_sector/autocomplete', 'Admin\CustomerSector::autocomplete');
    $routes->get('customer_sector_type/autocomplete', 'Admin\CustomerSector::typeAutocomplete');

    // Customer user
    $routes->get('customer/list', 'Admin\Customer::index');
    $routes->get('customer/detail', 'Admin\Customer::getCustomer');
    $routes->post('customer/add', 'Admin\Customer::addCustomer');
    $routes->post('customer/edit', 'Admin\Customer::editCustomer');
    $routes->post('customer/delete', 'Admin\Customer::deleteCustomer');
    $routes->post('customer/status/update', 'Admin\Customer::setCustomerStatus');
    $routes->post('customer/autocomplete', 'Admin\Customer::autocomplete');

    // Vendor user
    $routes->get('vendor/list', 'Admin\Vendor::index');
    $routes->get('vendor/detail', 'Admin\Vendor::getVendor');
    $routes->post('vendor/add', 'Admin\Vendor::addVendor');
    $routes->post('vendor/edit', 'Admin\Vendor::editVendor');
    $routes->post('vendor/delete', 'Admin\Vendor::deleteVendor');
    $routes->post('vendor/status/update', 'Admin\Vendor::setVendorStatus');
    $routes->post('vendor/autocomplete', 'Admin\Vendor::autocomplete');
    $routes->post('vendor/evaluation/delete', 'Admin\Vendor::deleteVendorEvaluation');

    // Checklist
    $routes->get('checklist/list', 'Admin\Checklist::index');
    $routes->get('checklist/detail', 'Admin\Checklist::getChecklist');
    $routes->post('checklist/add', 'Admin\Checklist::addChecklist');
    $routes->post('checklist/edit', 'Admin\Checklist::editChecklist');
    $routes->post('checklist/delete', 'Admin\Checklist::deleteChecklist');
    $routes->get('checklist/autocomplete', 'Admin\Checklist::autocomplete');

    $routes->get('checklist/task/list', 'Admin\ChecklistTask::getTaskList');
    $routes->get('checklist/task/detail', 'Admin\ChecklistTask::getTask');
    $routes->post('checklist/task/add', 'Admin\ChecklistTask::addTask');
    $routes->post('checklist/task/edit', 'Admin\ChecklistTask::editTask');
    $routes->post('checklist/task/delete', 'Admin\ChecklistTask::deleteTask');

    $routes->get('checklist/division/list', 'Admin\ChecklistTask::getDivisionList');
    $routes->get('checklist/division/detail', 'Admin\ChecklistTask::getDivision');
    $routes->post('checklist/division/add', 'Admin\ChecklistTask::addDivision');
    $routes->post('checklist/division/edit', 'Admin\ChecklistTask::editDivision');
    $routes->post('checklist/division/delete', 'Admin\ChecklistTask::deleteDivision');
    $routes->get('checklist/division/task/list', 'Admin\ChecklistTask::getDivisionTaskList');
    $routes->get('checklist/division/task/detail', 'Admin\ChecklistTask::getDivisionTask');
    $routes->post('checklist/division/task/add', 'Admin\ChecklistTask::addDivisionTask');
    $routes->post('checklist/division/task/delete', 'Admin\ChecklistTask::deleteDivisionTask');
});

// Employee
$routes->group('admin/employee', ['filter' => 'auth', 'namespace' => 'App\Controllers\Admin\Employee'], function($routes) {

    // National head
    $routes->get('national_head', 'NationalHead::index');
    $routes->get('national_head/detail', 'NationalHead::getEmployee');
    $routes->post('national_head/add', 'NationalHead::addEmployee');
    $routes->post('national_head/edit', 'NationalHead::editEmployee');
    $routes->post('national_head/delete', 'NationalHead::deleteEmployee');
    $routes->post('national_head/status/update', 'NationalHead::setEmployeeStatus');
    $routes->get('national_head/autocomplete', 'NationalHead::autocomplete');

    // AllIndiaServiceDeliveryHead
    $routes->get('aisd_head/list', 'AllIndiaServiceDeliveryHead::index');
    $routes->get('aisd_head/detail', 'AllIndiaServiceDeliveryHead::getEmployee');
    $routes->post('aisd_head/add', 'AllIndiaServiceDeliveryHead::addEmployee');
    $routes->post('aisd_head/edit', 'AllIndiaServiceDeliveryHead::editEmployee');
    $routes->post('aisd_head/delete', 'AllIndiaServiceDeliveryHead::deleteEmployee');
    $routes->post('aisd_head/status/update', 'AllIndiaServiceDeliveryHead::setEmployeeStatus');
    $routes->get('aisd_head/autocomplete', 'AllIndiaServiceDeliveryHead::autocomplete');
    $routes->get('aisd_head/export', 'AllIndiaServiceDeliveryHead::export');

    //RegionalServiceDeliveryHead
    $routes->get('rsd_head/list', 'RegionalServiceDeliveryHead::index');
    $routes->get('rsd_head/detail', 'RegionalServiceDeliveryHead::getEmployee');
    $routes->post('rsd_head/add', 'RegionalServiceDeliveryHead::addEmployee');
    $routes->post('rsd_head/edit', 'RegionalServiceDeliveryHead::editEmployee');
    $routes->post('rsd_head/delete', 'RegionalServiceDeliveryHead::deleteEmployee');
    $routes->post('rsd_head/status/update', 'RegionalServiceDeliveryHead::setEmployeeStatus');
    $routes->get('rsd_head/autocomplete', 'RegionalServiceDeliveryHead::autocomplete');

    //AreaServiceDeliveryHead
    $routes->get('asd_head/list', 'AreaServiceDeliveryHead::index');
    $routes->get('asd_head/detail', 'AreaServiceDeliveryHead::getEmployee');
    $routes->post('asd_head/add', 'AreaServiceDeliveryHead::addEmployee');
    $routes->post('asd_head/edit', 'AreaServiceDeliveryHead::editEmployee');
    $routes->post('asd_head/delete', 'AreaServiceDeliveryHead::deleteEmployee');
    $routes->post('asd_head/status/update', 'AreaServiceDeliveryHead::setEmployeeStatus');
    $routes->get('asd_head/autocomplete', 'AreaServiceDeliveryHead::autocomplete');
    
     
    // Region head
    $routes->get('region_head/list', 'RegionHead::index');
    $routes->get('region_head/detail', 'RegionHead::getEmployee');
    $routes->post('region_head/add', 'RegionHead::addEmployee');
    $routes->post('region_head/edit', 'RegionHead::editEmployee');
    $routes->post('region_head/delete', 'RegionHead::deleteEmployee');
    $routes->post('region_head/status/update', 'RegionHead::setEmployeeStatus');
    $routes->get('region_head/autocomplete', 'RegionHead::autocomplete');

    // Area head
    $routes->get('area_head/list', 'AreaHead::index');
    $routes->get('area_head/detail', 'AreaHead::getEmployee');
    $routes->post('area_head/add', 'AreaHead::addEmployee');
    $routes->post('area_head/edit', 'AreaHead::editEmployee');
    $routes->post('area_head/delete', 'AreaHead::deleteEmployee');
    $routes->post('area_head/status/update', 'AreaHead::setEmployeeStatus');
    $routes->get('area_head/autocomplete', 'AreaHead::autocomplete');

    // Manager head
    $routes->get('manager/list', 'Manager::index');
    $routes->get('manager/detail', 'Manager::getEmployee');
    $routes->post('manager/add', 'Manager::addEmployee');
    $routes->post('manager/edit', 'Manager::editEmployee');
    $routes->post('manager/delete', 'Manager::deleteEmployee');
    $routes->post('manager/status/update', 'Manager::setEmployeeStatus');
    $routes->get('manager/autocomplete', 'Manager::autocomplete');

     // engineer head
     $routes->get('engineer/list', 'Engineer::index');
     $routes->get('engineer/detail', 'Engineer::getEmployee');
     $routes->post('engineer/add', 'Engineer::addEmployee');
     $routes->post('engineer/edit', 'Engineer::editEmployee');
     $routes->post('engineer/delete', 'Engineer::deleteEmployee');
    $routes->post('engineer/status/update', 'Engineer::setEmployeeStatus');
    $routes->get('engineer/autocomplete', 'Engineer::autocomplete');
    $routes->get('engineer/getdetails', 'Engineer::getEmployeeDetails');


     // Supervisor head
     $routes->get('supervisor/list', 'Supervisor::index');
     $routes->get('supervisor/detail', 'Supervisor::getEmployee');
     $routes->post('supervisor/add', 'Supervisor::addEmployee');
     $routes->post('supervisor/edit', 'Supervisor::editEmployee');
     $routes->post('supervisor/delete', 'Supervisor::deleteEmployee');
    $routes->post('supervisor/status/update', 'Supervisor::setEmployeeStatus');
    $routes->get('supervisor/autocomplete', 'Supervisor::autocomplete');

    // Technician head
    $routes->get('technician/list', 'Technician::index');
    $routes->get('technician/detail', 'Technician::getEmployee');
    $routes->post('technician/add', 'Technician::addEmployee');
    $routes->post('technician/edit', 'Technician::editEmployee');
    $routes->post('technician/delete', 'Technician::deleteEmployee');
    $routes->post('technician/status/update', 'Technician::setEmployeeStatus');
    $routes->get('technician/autocomplete', 'Technician::autocomplete');

    // Data management head
    $routes->get('data_management/list', 'DataManagement::index');
    $routes->get('data_management/detail', 'DataManagement::getEmployee');
    $routes->post('data_management/add', 'DataManagement::addEmployee');
    $routes->post('data_management/edit', 'DataManagement::editEmployee');
    $routes->post('data_management/delete', 'DataManagement::deleteEmployee');
    $routes->post('data_management/status/update', 'DataManagement::setEmployeeStatus');
    $routes->get('data_management/autocomplete', 'DataManagement::autocomplete');

    // Client Account Manager
    $routes->get('client_account_manager/list', 'ClientAccountManager::index');
    $routes->get('client_account_manager/detail', 'ClientAccountManager::getEmployee');
    $routes->post('client_account_manager/add', 'ClientAccountManager::addEmployee');
    $routes->post('client_account_manager/edit', 'ClientAccountManager::editEmployee');
    $routes->post('client_account_manager/delete', 'ClientAccountManager::deleteEmployee');
    $routes->post('client_account_manager/status/update', 'ClientAccountManager::setEmployeeStatus');
    $routes->get('client_account_manager/autocomplete', 'ClientAccountManager::autocomplete');
    $routes->post('client_account_manager/autocompleteCam', 'ClientAccountManager::autocompleteCam');

});


$routes->post('employee/login', 'Employee\Account::login');
$routes->post('employee/logout', 'Employee\Account::logout');
$routes->post('employee/checkLoggedin', 'Employee\Account::checkLoggedin');
$routes->post('employee/forgot_password/init_reset_by_email', 'Employee\ForgotPassword::sendPasswordResetMail');
$routes->post('employee/forgot_password/recover_by_email', 'Employee\ForgotPassword::recoverPasswordByEmail');
$routes->post('employee/forgot_password/reset_by_email', 'Employee\ForgotPassword::resetPasswordByEmail');


$routes->group('employee/dmt', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\dmt'], function($routes) {
// DMT User
  $routes->get('profile', 'Profile::index');
  $routes->get('profile/detail', 'Profile::getEmployee');
  $routes->post('profile/edit', 'Profile::editEmployee');
  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/add', 'ContractJob::addContractJob');
  $routes->post('contract_job/edit', 'ContractJob::editContractJob');
  $routes->post('contract_job/delete', 'ContractJob::deleteContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');

});


$routes->group('employee/aisd', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\aisd'], function($routes) {
  // AISD User
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});


$routes->group('employee/regionalHead', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\regionalHead'], function($routes) {
  // RH User
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});

$routes->group('employee/rsd', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\rsd'], function($routes) {
  // RSD User
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});

$routes->group('employee/asd', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\asd'], function($routes) {
  // ASD User
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});

$routes->group('employee/areaHead', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\areaHead'], function($routes) {
  // areaHead User
  $routes->get('profile', 'Profile::index');
  $routes->get('profile/detail', 'Profile::getEmployee');
  $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});

$routes->group('employee/cam', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\cam'], function($routes) {
  // cam User
  $routes->get('profile', 'Profile::index');
  $routes->get('profile/detail', 'Profile::getEmployee');
  $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');

});

$routes->group('employee/nationalHead', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\nationalHead'], function($routes) {
  // cam User
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});

$routes->group('employee/customer', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\customer'], function($routes) {
  // cam User
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');  

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');
});