<?php

namespace App\Service;

use App\Models\Order;
use App\Models\Payment;
use App\Helper\ArrayToClass;

class OrderService extends BaseService
{
    private $baseURL;
    public function __construct(Array $properties=array()) {
        $this->baseURL = getenv('BASE_DOMAIN')."/api/v1";
    }

	public function CreateOrder($orderData)
	{
        return $response = $this->callAPI("POST",$this->baseURL."/order/create",$orderData,array("x-user-data"=>"data",'Content-Type'=>'application/json'),"");

        // if ($response["error"] != null){
        //     return array("body"=>null,"header"=>null,"error"=>"error calling backend apis");
        // }

        // json$response["body"]

		// return array("body"=>$body,"header"=>$headers,"error"=>null);
	}

    public function GetOrder($orderid)
	{
       // echo  "gggggggggggggggggggggggg----------".$orderid;
        $response = $this->callAPI("GET",$this->baseURL.'/'. 'order/'.$orderid,null,array("x-user-data"=>"data",'Content-Type'=>'application/json'),"");
        if ($response['body']['Data'] != null){
        $orderModel = new Order($response['body']['Data']);
        }else{
            return null;
        } 
        // $orderModel->id =  $response['body']['Data']['id'];
        // $orderModel->uuid =  $response['body']['Data']['uuid'];
        // $orderModel->session_uuid =  $response['body']['Data']['session_uuid'];
        // $orderModel->amount =  $response['body']['Data']['amount'];
        // $orderModel->created =  $response['body']['Data']['created'];
        // $orderModel->delivery_status =  $response['body']['Data']['delivery_status'];
        // $orderModel->cartid =  $response['body']['Data']['cartid'];
        // $orderModel->paymentid =  $response['body']['Data']['paymentid'];
        
        $paymentModel = new Payment($response['body']['Data']['payment_info']);

        $orderModel->payment_info = $paymentModel; //$response['body']['Data']['payment_info'];

        $response['body'] =  $orderModel;
        // $order = new ArrayToClass($response['body']);   

        return $response;

        // if ($response["error"] != null){
        //     return array("body"=>null,"header"=>null,"error"=>"error calling backend apis");
        // }

        // json$response["body"]

		// return array("body"=>$body,"header"=>$headers,"error"=>null);
	}



    public function Conclude($concludeOrderData){
        
        // OrderId     int64
        // PaymentInfo struct {
        //     PaymentID              string
        //     Amount                 float64
        //     ExtPaymentID           string
        //     Channel                string
        //     Gateway                string
        //     PaymentGateWayResponse string
        // }

        $response = $this->callAPI("POST",$this->baseURL.'/order/conclude',$concludeOrderData,array("x-user-data"=>"data",'Content-Type'=>'application/json'),"");
    }

}