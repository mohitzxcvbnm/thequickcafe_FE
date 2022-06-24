<?php

// namespace App\Models;
// class Cart 
// {
// 	public $Status; //int
// 	public $Error; //String
// 	public $Data; //Data

// 	public function __construct(){
// 			$this->Data = new CartDataOuter();
// 	}
// }

// 	class CartItems {
// 	public $invid; //int
// 	public $count; //int
// 	public $itemName; //String
// 	public $price; //int
// 	public $subtotal; //int
// 	}
// 	class CartDataInner {
// 	public $items; //array( Items )
// 	public $total; //int
// 	public $uniqueitemcount; //int
// 	public $back_sess_id; //String
// 	public function __construct($items){
// 		$this->items = $items;
// 	}
// 	}
// 	class CartDataOuter {
// 	use CartDataInner;
// 	public $id; //int
// 	public $session_id; //String
// 	public $data; //Data
// 	public $created; //int
// 	public $status; //String

// 	public function __construct(){
// 			$this->data = new CartDataInner();
// 	}

// 	}
