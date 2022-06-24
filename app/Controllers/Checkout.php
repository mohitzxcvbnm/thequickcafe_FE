<?php

namespace App\Controllers;

// Create the Razorpay Order

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Service\CartService;
use App\Service\OrderService;


class Checkout extends BaseController
{
	// public $session;

	public function __construct() {
		// // parent::__construct();
		// $session = \Config\Services::session();

		// // if ($session->get('created')+60*60*24 < time()){
			
		// // }


		include APPPATH . 'ThirdParty/razorpay-php/Razorpay.php';
	}

	public function index()
	{
		
		// var_dump($session);
		// var_dump($session->get("appdata")["session_id"]);
		// var_dump($session->get("cartid"));
		// exit(1);
		$cart = null;
		$session = session();
		
		// echo $_POST['cart_data'];
		if ($session->has("orderid")  && $session->has("cartid")){
			//get order and cart data from backend 			
			$orderService = new OrderService();
			$response = $orderService->GetOrder($session->get("orderid"));
			// if($response == null){
			// 	echo "destroying session";
			// 	exit(1);
			// 	$vmid = $session->get("vmid");
			// 	$session->remove("orderid","cartid","vmid","appdata","created");

			// 	return redirect()->to(base_url()."/?macid=".$vmid);
			// } 
			//echo "\n---";
			//var_dump($response["body"]);	
			$myOrder = $response["body"];
			
			$cartService = new CartService;
			$cartResult = $cartService->GetCartById($session->get("cartid"));			
			//var_dump($cartResult);	
			// populate fields
			
			//if order is complete redirect user to vm choose page 
			if($myOrder->delivery_status=='complete'){
				$session->destroy();
				return redirect()->to(base_url()."/");
			}
			
			// if order is pending payment is complete redirect or render waiting line page 
			if($myOrder->delivery_status=='queued' || $myOrder->delivery_status=='payment_done'){
				// echo "debuger->";
				// var_dump($cartResult['body']);
				// exit(1);
				$data = array(
					"page_name" => "vm_waiting_line",
					"page_data" => array(
						"cart"=> $cartResult['body'],	
						"uniqueitemcount" => $cartResult['body']->Data->data->uniqueitemcount,
						"total" => $cartResult['body']->Data->data->total,
						"order"=>$myOrder,
						"rzpdata"=>array('payment_status'=>'success','paymentid'=>$myOrder->payment_info->ext_payment_id,
						'raw'=>	$_POST,
						// 'vmid'=> $myOrder->
					),
					)
				);
				return view('template',$data);
				
			}
			

			// if order is pending payment is not complete  -. skip create setps and render page as is with payment optn 
			
			if($myOrder->delivery_status=='pending'){
				$rzpdata = $this->razorpay($myOrder->id,$myOrder->amount,$myOrder->id);
				$cartResultArr = json_decode(json_encode($cartResult['body']), true); 
				unset($cartResultArr["Data"]["data"]['uniqueitemcount']);
				unset($cartResultArr["Data"]["data"]['total']);
				$data = array(
					"page_name" => "checkout",
					"page_data" => array(
						"cart"=> $cartResultArr["Data"]["data"]["items"],	
						"uniqueitemcount" => $cartResult['body']->Data->data->uniqueitemcount,
						// "total" => $cartResult['body']->Data->data->total,
						"total" => $myOrder->amount ,
						"rzpdata"=>$rzpdata,
					)
				);
				return view('template',$data);
			}
			return ;
		}
		
		
		
		if (isset($_POST['cart_data'])){
			
			$cart = json_decode($_POST['cart_data'],true);
			
			$items= [];

			$uniqueitemcount = $cart['uniqueitemcount'];
			$total = $cart['total'];

			unset($cart['uniqueitemcount']);
			unset($cart['total']);
			
			foreach($cart as $key=>$value){
			
			array_push($items,	array("invid" => $key,
				"count" => $value["count"],
				"itemName" => $value["itemName"],
				"price" => $value["price"],
				"subtotal" => $value["subtotal"],				  
			));
			}

			// add to cart api 
			$cartService = new CartService;
			$cartResult = $cartService->AddToCart($items,$total,$uniqueitemcount,$session->get("appdata")["session_id"]);

			// create order api 
			$orderRequestData = array(
				"session_uuid"=>$session->get("appdata")["session_id"],
				"amount"=>$total,
				"cartid"=>$cartResult["body"]["Data"]["cartid"],
				"vmid"=> (int)$session->get("vmid"),
			);

			// echo "<br/>req bdy---------<br/>";
			// print_r($orderRequestData);
			// echo "---------<br/>";

			$orderService = new OrderService();
			$createOrderResult = $orderService->CreateOrder($orderRequestData);	
			// echo "<br/>resp bdy---------<br/>";
			// print_r($createOrderResult);
			// echo "<br/>---------<br/>";
			$session->set("orderid",$createOrderResult["body"]["Data"]["order_id"]);
			$session->set("cartid",$cartResult["body"]["Data"]["cartid"]);
		}else{
			//exit(1);
			//$this->load->helper('url');
			// echo base_url();
			// exit(1);
			// redirect(base_url()."/");
			$session->destroy();
			return redirect()->to(base_url()."/");
		}
	
		$viewcart; 
		$i = 0;

	
		$rzpdata = $this->razorpay($createOrderResult["body"]["Data"]["order_id"],$total,$createOrderResult["body"]["Data"]["order_id"]);

		$data = array(
			"page_name" => "checkout",
			"page_data" => array(
				"cart"=> $cart,	
				"uniqueitemcount" => $uniqueitemcount,
				"total" => $total,
				"rzpdata"=>$rzpdata,
			)
		);
		return view('template',$data);
	}


	private function razorpay($receiptNo,$amount,$odrid)
	{
		//echo $receiptNo."-".$amount ."-". $odrid;
			//require('config.php');	
			//require('razorpay-php/Razorpay.php');
		// session_start();
		$keyId = 'rzp_test_FyZMiMHaB5XHEE';
		$keySecret = 'KtRE566QBk4I7AC2cAc8c2Zu';
		$displayCurrency = 'INR';
		

		$api = new Api($keyId, $keySecret);

		//
		// We create an razorpay order using orders api
		// Docs: https://docs.razorpay.com/docs/orders
		//
		$orderData = [
			'receipt'         => $receiptNo,
			'amount'          => $amount * 100, // 2000 rupees in paise
			'currency'        => 'INR',
			'payment_capture' => 1 // auto capture
		];

		$razorpayOrder = $api->order->create($orderData);

		$razorpayOrderId = $razorpayOrder['id'];

		// $_SESSION['razorpay_order_id'] = $razorpayOrderId;

		$displayAmount = $amount = $orderData['amount'];

		if ($displayCurrency !== 'INR')
		{
			$url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
			$exchange = json_decode(file_get_contents($url), true);

			$displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
		}

		$checkout = 'automatic';

		if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
		{
			$checkout = $_GET['checkout'];
		}

		$data = [
			"key"               => $keyId,
			"amount"            => $amount,
			"name"              => "cust_".$odrid,
			"description"       => "cust_".$odrid,
			"image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
			"prefill"           => [
			"name"              => "cust_".$odrid,
			"email"             => "noreply@quickcafe.com",
			"contact"           => "8149173369",
			],
			"notes"             => [
			"address"           => "cust_".$odrid,
			"merchant_order_id" => "orderid-12312321",
			],
			"theme"             => [
			"color"             => "#F37254"
			],
			"order_id"          => $razorpayOrderId,
		];

		if ($displayCurrency !== 'INR')
		{
			$data['display_currency']  = $displayCurrency;
			$data['display_amount']    = $displayAmount;
		}

		$json = json_encode($data);
		//require("checkout/{$checkout}.php");
		return array('data'=>$data,'json'=>$json,"displayCurrency"=>$displayCurrency);
	}

	public function verify()
	{	
		// echo "---<br/>";
		// var_dump($_POST);
		// echo "<br/>---<br/>";
		// 		// require('config.php');

		// session_start();

		// require('razorpay-php/Razorpay.php');
		// use Razorpay\Api\Api;
		$session = session();
		$keyId = 'rzp_test_FyZMiMHaB5XHEE';
		$keySecret = 'KtRE566QBk4I7AC2cAc8c2Zu';
		$displayCurrency = 'INR';

		$success = true;

		$error = "Payment Failed";

		if (empty($_POST['razorpay_payment_id']) === false)
		{
			$api = new Api($keyId, $keySecret);

			try
			{
				// Please note that the razorpay order ID must
				// come from a trusted source (session here, but
				// could be database or something else)
				$attributes = array(
					'razorpay_order_id' => $_POST['razorpay_order_id'],
					'razorpay_payment_id' => $_POST['razorpay_payment_id'],
					'razorpay_signature' => $_POST['razorpay_signature']
				);

				$api->utility->verifyPaymentSignature($attributes);
			}
			catch(SignatureVerificationError $e)
			{
				$success = false;
				$error = 'Razorpay Error : ' . $e->getMessage();
			}
		}

		if ($success === true && $session->has("orderid") && $session->has("cartid"))
		{

			//call conclude order 
			$concludeOrderReq = array(
				"order_id" =>$session->get("orderid"),
				"payment_info"=> array(
					// "payment_id"=>"",
					"amount">$_POST["amount"]/100,
					"ext_payment_id"=>$_POST['razorpay_payment_id'],
					"channel"=>"",
					"gateway"=>"razorpay",
					"payment_gate_way_response"=>json_encode($_POST),	
				)
			);
			$orderService = new OrderService();
			$concludeOrderResult = $orderService->Conclude($concludeOrderReq);

			if($concludeOrderResult["body"]["Status"] = 200){
				echo "error occured during concluding order";
				//var_dump($concludeOrderResult);
			}
			$response = $orderService->GetOrder($session->get("orderid"));
			//echo "\n---";
			//var_dump($response);	
			$myOrder = $response["body"];

			$cartService = new CartService;
			$cartResult = $cartService->GetCartById($session->get("cartid"));		

			$data = array(
				"page_name" => "vm_waiting_line",
				"page_data" => array(
					"cart"=> $cartResult,	
					"conclude_order_data"=>$concludeOrderResult,
					"order"=>$myOrder,
					//"uniqueitemcount" => $uniqueitemcount,
					//"total" => $total,
					"vmid"=>$session->get("vmid"),
					"rzpdata"=>array('payment_status'=>'success','paymentid'=>$_POST['razorpay_payment_id'],
					'raw'=>	$_POST,
				),
				)
			);
			return view('template',$data);

			// $html = "<p>Your payment was successful</p>
			// 		<p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
		}
		else
		{
			return redirect()->to(base_url()."/Checkout");
		}
	}

}
