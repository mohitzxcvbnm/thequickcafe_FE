<?php

namespace App\Service;

class InventoryService extends BaseService
{
    private $baseURL;

    public function __construct() {
        $this->baseURL = getenv('BASE_DOMAIN')."/api/v1";
    }

	public function GetInventory($vmid)
	{
        return $response = $this->callAPI("GET",$this->baseURL."/invetory/byvmid/".$vmid,"",array("x-user-data"=>"data"),"");

        // if ($response["error"] != null){
        //     return array("body"=>null,"header"=>null,"error"=>"error calling backend apis");
        // }

        // json$response["body"]

		// return array("body"=>$body,"header"=>$headers,"error"=>null);
	}
}