<?php

namespace App\Controllers;
use App\Service\InventoryService;
use App\Service\SessionService;

class Home extends BaseController
{
	// public $session;
	// public function __construct(){
	// 	$session = \Config\Services::session();
	// }

	public function index()
	{
		$data = array(
			"page_name" => "index_search",
			"page_data" => array(
				
			)
		);
		return view('template',$data);
	}

	public function scanqr(){
		$data = array(
			"page_name" => "scan_qr",
			"page_data" => array(
				
			)
		);
		return view('template',$data);
	}
	
	public function VendingMachine($macid){
		//echo 'ffff';
		//exit(1);
		// echo $session->get('created') ."</br>" ;bv
		// echo $session->get('vmid')."</br>" ;
		// exit(1);
		// var_dump($session);

		$session = session();//\Config\Services::session();

		// $isGoodSession = $session->has('appdata') && $session->has('vmid') && $session->has('created');

		// var_dump($session);

		// exit(1);
		//  echo $session->has('appdata') . $session->has('vmid') . $session->has('created');


		if(!isset($macid) ){
			$data = array(
				"page_name" => "select_vm",
				"page_data" => array()
			);
			return view('template',$data);
		}
		$session->remove("orderid","cartid","vmid","appdata","created");

		if (!($session->has('appdata') && $session->has('vmid') && $session->has('created'))  || $session->get('created')+86400 <= time() ){
			// echo "ok";
			// exit(1);
			$sessionid = session_id();
			// $session->destroy(); 
			$sessionService = new SessionService;
			$sessionResult = $sessionService->GetNewSession($sessionid);
			
			if ($sessionResult==null || $sessionResult["body"]==null){
				return view('errors/html/production');
			}

			$newdata = $sessionResult["body"]["Data"];
			$session->set("appdata",$newdata);
			$session->set("vmid",$macid);
			$session->set('created',time());
			$session->sess_expiration = '14400'; // seconds
			// echo "---</br>";
			// print_r($sessionResult);
			// exit(1); 	
		} 

		// exit(2);
		// $session->remove("orderid","cartid","vmid","appdata","created");

		$invService = new InventoryService;
		$invResult = $invService -> GetInventory($macid);		

		if($invResult["body"]["Status"] == 404){
			$data = array(
				"page_name" => "inventory404",
				"page_data" => array(
					
				)
			);
			return view('template',$data);
		}

		//print_r($invResult);
		$products = [];
		foreach($invResult["body"]["Data"] as $key => $inv){
			$products[$key] = [
			"id"=>$inv["id"],
			"name"=>$inv["product_name"],
			"price"=>$inv["product_price"],
			"currency"=> "Rs", //$inv["id"]
			"description"=>$inv["product_name"],
			"img_url"=>base_url()."/img/product-tea.png",
			];				 
		}

		$data = array(
			"page_name" => "vm_choose_options",
			"page_data" => array(
				"products"=> $products,
			)
		);
		return view('template',$data);
	
	}
	
}
