<?php

$routes->group('employee', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee'], function($routes) {
  // Common
  $routes->post('localisation/country/list', 'Localisation::autocompleteCountry');
  $routes->post('localisation/state/list', 'Localisation::autocompleteState');
  $routes->post('localisation/city/list', 'Localisation::autocompleteCity');
  $routes->post('localisation/currency/autocomplete', 'Localisation::autocompleteCurrency');
});

$routes->group('employee/engineer', ['filter' => 'auth', 'namespace' => 'App\Controllers\Employee\engineer'], function($routes) {
  // engineer User
    $routes->post('profile', 'Profile::index');
    $routes->post('profile/detail', 'Profile::getEmployee');
    $routes->post('profile/edit', 'Profile::editEmployee');
    
    // Contract job
    $routes->post('contract_job/list', 'ContractJob::index');
    $routes->post('contract_job/detail', 'ContractJob::getContractJob');
    $routes->post('contract_job/asset/list', 'ContractJob::getContractJobAssets');
    $routes->post('contract_job/asset/checklists', 'ContractJob::getContractJobAssetChecklists');
    $routes->post('contract_job/status/update', 'ContractJob::setContractJobStatus');

});