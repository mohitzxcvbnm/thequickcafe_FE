<?php

namespace App\Models;

class Payment{
    public $id;
    public $amount;
    public $channel;
    public $ext_payment_id;
    public $orderid;
    public $gateway;

    public function __construct(Array $properties=array()){
		foreach($properties as $key => $value){
            $this->{$key} = $value;
        }
	}
} 