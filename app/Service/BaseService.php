<?php

namespace App\Service;

class BaseService
{
	// reffer https://weichie.com/blog/curl-api-calls-with-php/ for knowning how to do POST GET and other
	// public $basedomain = '';
	// function __construct(){
	// 	$basedomain = getenv('BASE_DOMAIN'); 
	// }

	function callAPI($method, $url, $data,$reqHeader,$respBdyInType){
		$curl = curl_init();
		switch ($method){
		   case "POST":
			curl_setopt($curl, CURLOPT_POST, true);
			  if ($data){

				if (is_array($reqHeader) && isset($reqHeader['Content-Type']) && $reqHeader['Content-Type']=='application/json'){
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
					print_r($data);
					echo json_encode($data);
				}else{
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				}
			  }

			  break;
		   case "PUT":
			  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			  if ($data)
				 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			  break;
		   default:
			  if (is_array($data)){
				$url = sprintf("%s?%s", $url, http_build_query($data));
			  }else{
				$url = sprintf("%s", $url);
			  }
		}
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		$finalReqHeader = [];
		foreach($reqHeader as $key => $value){
			array_push($finalReqHeader,$key.": ".$value);
		}
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, $finalReqHeader);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$headers = [];

		curl_setopt($curl, CURLOPT_HEADERFUNCTION,
			function ($curl, $header) use (&$headers) {
				$len = strlen($header);
				$header = explode(':', $header, 2);
				if (count($header) < 2) // ignore invalid headers
					return $len;
		
				$headers[strtolower(trim($header[0]))][] = trim($header[1]);
		
				return $len;
			}
		);
		// uncomment bellow two lines for basic auth
		// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// curl_setopt($curl, CURLOPT_USERPWD, "username:password");
		
		// EXECUTE:
		$result = curl_exec($curl);
		if(!$result){
			curl_close($curl);
			return array("body"=>null,"header"=>null,"error"=>"some thing went wrong");
		}
		

		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		// $header = substr($result, 0, $header_size);
		// $body = substr($result, $header_size);

		curl_close($curl);

		if( strpos($headers["content-type"][0],"application/json") !== false)
		{	
			if ($respBdyInType == "class"){
				$bodyfinal = json_decode($result,false);
			}else{
				$bodyfinal = json_decode($result,true);
			}
			// echo $result;
			// var_dump($bodyfinal);
		}
		
		
		return array("body"=>$bodyfinal,"header"=>$headers,"error"=>null);
	 }
}
