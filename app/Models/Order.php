<?php

namespace App\Models;
class Order
{
    public $id;
	public $uuid;
	public $session_uuid;
	public $amount;
	public $created;
	public $delivery_status;
	public $cartid;
	public $paymentid;
    public $payment_info;

	public function __construct(Array $properties=array()){
		foreach($properties as $key => $value){
            $this->{$key} = $value;
        }
	}
}

