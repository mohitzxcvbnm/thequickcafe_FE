<?php
namespace App\Service;
use App\Models\Cart as cartmodel;
class CartService extends BaseService
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

    


	public function AddToCart($items,$total,$uniqueueItemCount,$BackendSessionId)
	{
        // do cart data validation here 

        $body = array(
            "total"=>$total,
            "uniqueitemcount"=>$uniqueueItemCount,
            "back_sess_id"=>$BackendSessionId,
            "items"=>$items
        ); 
       

        return $response = $this->callAPI("POST",$this->baseURL."/cart/add",$body,array("x-user-data"=>"data",'Content-Type'=>'application/json'),"");
	}

    public function GetCartById($cartid)
	{
        // do cart data validation here 
        return $response = $this->callAPI("GET",$this->baseURL."/cart/".$cartid,null,array("x-user-data"=>"data",'Content-Type'=>'application/json'),"class");
	}    
}