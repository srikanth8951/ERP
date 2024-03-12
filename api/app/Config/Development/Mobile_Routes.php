<?php

$routes->group('employee', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee'], function ($routes) {
  // Common
  $routes->get('localisation/country/list', 'Localisation::autocompleteCountry');
  $routes->get('localisation/state/list', 'Localisation::autocompleteState');
  $routes->get('localisation/city/list', 'Localisation::autocompleteCity');
  $routes->get('localisation/currency/autocomplete', 'Localisation::autocompleteCurrency');
});

$routes->group('employee/engineer', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\engineer'], function ($routes) {
  // engineer User
  $routes->get('profile/detail', 'Profile::getEmployee');
  $routes->post('profile/edit', 'Profile::editEmployee');

  // Contract job
  $routes->get('contract_job/list', 'ContractJob::index');
  $routes->get('contract_job/detail', 'ContractJob::getContractJob');
  $routes->get('contract_job/asset/list', 'ContractJob::getContractJobAssets');
  $routes->get('contract_job/asset/checklists', 'ContractJob::getContractJobAssetChecklists');
  $routes->get('contract_job/asset/checklist/detail', 'ContractJob::getContractJobAssetChecklist');
  $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');


  $routes->post('contract_job/asset/checklist/submit', 'ContractJob::addChecklistTrack');
  $routes->get('store/category/autocomplete', 'StoreCategory::autocomplete');
  $routes->get('store/sub_category/autocomplete', 'StoreCategory::autocompleteSubCategory');
  $routes->get('store/product/autocomplete', 'StoreProduct::autocomplete');

  // Store request
  $routes->post('store/spare_parts/request', 'StoreProductRequest::addProductRequests');
  $routes->get('store/spare_parts/request/list', 'StoreProductRequest::index');
  $routes->get('store/spare_parts/request/detail', 'StoreProductRequest::getProductRequest');
  $routes->post('store/spare_parts/request/cancel', 'StoreProductRequest::productRequestCancel');

  //test
  // $routes->post('contract_job/ppm_frequency', 'ContractJob::getPPMFrequency');
});


