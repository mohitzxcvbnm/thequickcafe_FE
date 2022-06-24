<?php

namespace App\Service;
class SessionService extends BaseService
{
    private $baseURL;

    public function __construct() {
        $this->baseURL = getenv('BASE_DOMAIN')."/api/v1";
    }


    // type CartData struct {
    //     Items []struct {
    //         InvID    int64   `json:"invid"`
    //         Count    int     `json:"count"`
    //         ItemName string  `json:"itemName"`
    //         Price    float32 `json:"price"`
    //         Subtotal int     `json:"subtotal"`
    //     } `json:"items"`
    //     Total           float32 `json:"total"`
    //     Uniqueitemcount int     `json:"uniqueitemcount"`
    //     BackSessionID   string  `json:"back_sess_id"`
    // }

    


	public function GetNewSession($phpsession)
	{
        // do cart data validation here 
        return $response = $this->callAPI("GET",$this->baseURL."/session/new"."?phpSessionId=".$phpsession,"",array("x-user-data"=>"data",'Content-Type'=>'application/json'),"");
	}
}