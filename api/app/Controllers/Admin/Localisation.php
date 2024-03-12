<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\LocalisationModel;

class Localisation extends ResourceController
{

    protected function validatePermission($permission_name)
	{
		$permission = AuthUser::checkPermission($permission_name);
		if (! $permission) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_permission')
			);
		
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

    public function autocompleteCountry()
	{
		$this->validatePermission('view_Localisation');	// Check permission
		$modelLocalisation = new LocalisationModel(); // Load model

		$filter_data = array(
			// 'status' => 1,
			'sort' => 'country_id',
			'order' => 'asc'
		);
		$localisationArray = array();
		$localisations = $modelLocalisation->getCountries($filter_data);
		if ($localisations) {
			foreach($localisations as $localisation) {
				$localisationArray[] = array(
					'id' => (int)$localisation->country_id,
					'name' => html_entity_decode($localisation->name),
					'code' => $localisation->code,
					'dial_code' => $localisation->phonecode
				);
			}
		}
		$response = array(
			'status' => 'success',
			'localisation' => [
				'countries' => $localisationArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

    public function autocompleteState()
	{
		$modelLocalisation = new LocalisationModel(); // Load model

        $countryID = $this->request->getVar('country_id');
		$filter_data = array(
			// 'status' => 1,
			'sort' => 'state_id',
			'order' => 'asc'
		);
		$localisationArray = array();
		$localisations = $modelLocalisation->getStates($countryID, $filter_data);
		if ($localisations) {
			foreach($localisations as $localisation) {
				$localisationArray[] = array(
					'id' => (int)$localisation->state_id,
					'name' => html_entity_decode($localisation->name),
				);
			}
		}
		$response = array(
			'status' => 'success',
			'localisation' => [
				'states' => $localisationArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	public function autocompleteCity()
	{
		$modelLocalisation = new LocalisationModel(); // Load modell

		$state_id = $this->request->getVar('state_id');
		// print_r($state_id);
		$filter_data = array(
			// 'status' => 1,
			'sort' => 'state_id',
			'order' => 'asc'
		);
		$cityArray = array();
		$cities = $modelLocalisation->getCities($state_id, $filter_data);
		if ($cities) {
			foreach ($cities as $city) {
				$cityArray[] = array(
					'id' => (int)$city->city_id,
					'name' => html_entity_decode($city->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'localisation' => [
				'cities' => $cityArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	public function autocompleteCurrency()
	{
		$this->validatePermission('view_Localisation');	// Check permission
		$modelLocalisation = new LocalisationModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1,
			'sort' => 'currency_id',
			'order' => 'asc'
		);
		$localisationArray = array();
		$localisations = $modelLocalisation->getCurrencies($filter_data);
		if ($localisations) {
			foreach($localisations as $localisation) {
				$localisationArray[] = array(
					'id' => (int)$localisation->currency_id,
					'name' => html_entity_decode($localisation->name),
					'code' => $localisation->code,
					'symbol' => $localisation->symbol
				);
			}
		}
		$response = array(
			'status' => 'success',
			'localisation' => [
				'currencies' => $localisationArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}