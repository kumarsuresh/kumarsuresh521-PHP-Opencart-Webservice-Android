<?php 
class ControllerProductfastapp extends Controller {

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Chat Store ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function setnfstatus() {
		$this->load->model('account/customer');
		
		$response = array();
		
		$data = array();
		
		
		$notification = $this->request->get['ntfstatus'];
		
		$user_id = $this->request->get['uid'];
		
		if( isset($notification) &&  isset($user_id)){
		$data = array(
			'userId' => $user_id,
			'notification' => $notification
		); 
		
				$this->model_account_customer->editNotification($data);  
			
				$response["Message"] = 'Success';
				
			}else{
				$response["Message"] = 'Failure';
			}
					
		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1);
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Chat Store ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function set_lg_status() { 
		$this->load->model('account/customer');
		
		$response = array();
		
		$data = array();
		
		
		$lgstatus = $this->request->get['lgstatus'];
		
		$user_id = $this->request->get['uid'];
		
		if( isset($lgstatus) &&  isset($user_id)){
		$data = array(
			'userId' => $user_id,
			'nf_status' => $lgstatus
		); 
		
				$this->model_account_customer->editlgNotification($data);  
			
				$response["Message"] = 'Success';
				
			}else{
				$response["Message"] = 'Failure';
			}
					
		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1);
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Chat Store ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function mychat() {
		$this->load->model('account/customer');

		$response = array();
		
		$data = array();
		
		if (isset($this->request->get['sid'])) {
			$sellerId = $this->request->get['sid'];
		} else {
			$sellerId = '';
		}
		
		if (isset($this->request->get['uid'])) {
			$userId = $this->request->get['uid'];
		} else {
			$userId = '';
		}
		
		if (isset($this->request->get['msg'])) {
			$message = $this->request->get['msg'];
		} else {
			$message = '';
		}
		
		if (isset($this->request->get['suid'])) {
			$sellerUserId = $this->request->get['suid'];
		} else {
			$sellerUserId = '';
		}

					
			$data = array(
				'userId' => $userId,
				'sellerId' => $sellerId,
				'sellerUserId' => $sellerUserId,
				'message' => $message
			);
			
			$chatId = $this->model_account_customer->addChatApp($data);  
			

			$userInfo = $this->model_account_customer->getCustomer($sellerId); //echo "<pre>"; print_r($userInfo);
			
			$count = $this->model_account_customer->countUnreadChatApp($sellerId); //echo "<pre>"; print_r($count);
			
			if( $userInfo['notification_status'] == 'Y' && $userInfo['nf_status'] == 'A'){
							$msg =$message;
							$time = date('Y-m-d H:i:s');
				
								$url = 'https://android.googleapis.com/gcm/send';
								$data = array( 'message' => 'You have '.$count["count"].' notification message(s).' );
								$registration_ids = array( $userInfo['deviceId'] );
								//echo $postData = "{ \"collapse_key\": \"swapstuff \",  \"time_to_live\": 0,  \"delay_while_idle\": false,  \"data\": {    \"message\": \"" . $msg . "\",    \"time\": \"" .$time. "\" },  \"registration_ids\":[\"\"]}"; exit;
 
								$fields = array(
									'registration_ids' => $registration_ids,
									'data' => $data
								);
						 
								$headers = array(
									'Authorization: key= AIzaSyDXLGSnR0qzgyGFABr2dWbjW-Zvd3chyoU',
									'Content-Type: application/json'
								);
								// Open connection
								$ch = curl_init();
						 
								// Set the url, number of POST vars, POST data
								curl_setopt($ch, CURLOPT_URL, $url);
						 
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						 
								// Disabling SSL Certificate support temporarly
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						 
								curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
						 
								// Execute post
								$result = curl_exec($ch);
								if ($result === FALSE) {
									die('Curl failed: ' . curl_error($ch));
								}
						 
								// Close connection
								curl_close($ch);
								
				if($chatId){
					$response["Message"] = 'Success';
					$response["pushnotification"] = $result;
					$response["count"] = $data;
					
				}else{
					$response["Message"] = 'Failure';
				}
			}else{
				$response["Message"] = 'Success';
			}
		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1); 
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	




//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Chat Store ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function getunread() {
		$this->load->model('account/customer');

		$response = array();
		
		$info = array();
		
		if (isset($this->request->get['uid'])) {
			$sellerId = $this->request->get['uid'];
		} else {
			$sellerId = '';
		}
		
		if(isset($sellerId)){
			
			$userInfo = $this->model_account_customer->getCustomer($sellerId); //echo "<pre>"; print_r($userInfo);
			
			$results = $this->model_account_customer->getUnreadChatApp($sellerId);    //echo "<pre>"; print_r($results);
			$count = $this->model_account_customer->countUnreadChatApp($sellerId); //echo "<pre>"; print_r($count);
			if($results){
		
				foreach($results as $result){
				
				//$this->model_account_customer->editChatApp($result['chatId']);  
				
					$info[] = array(
						'Id' => $result['sellerUserId'],
						'message' => $result['message'],
						'read' => $result['status'],
						'date' => $result['date']
						
						
					);
				}
							//$msg[] = array(
							//		'data' => 'you got a new unread message'
							//	);
							//	$msg= json_encode($msg);  
							//echo "<pre>"; print_r($result); echo $result['sellerUserId']; echo "<br/>"; echo $userId; exit;
					if( $userInfo['notification_status'] == 'Y' && $count['count'] >= 1 && $userInfo['nf_status'] == 'A'){
							$msg = "you have '".$count['count']."' message(s)";
							$time = date('Y-m-d H:i:s');
	//if( $userInfo['notification_status'] == 'Y' ){			 //maintain notification status..
								$url = 'https://android.googleapis.com/gcm/send';
								$data = array( 'message' => 'You have '.$count['count'].' notification message(s).' );
								$registration_ids = array( $userInfo['deviceId'] );
								//echo $postData = "{ \"collapse_key\": \"swapstuff \",  \"time_to_live\": 0,  \"delay_while_idle\": false,  \"data\": {    \"message\": \"" . $msg . "\",    \"time\": \"" .$time. "\" },  \"registration_ids\":[\"\"]}"; exit;
 
								$fields = array(
									'registration_ids' => $registration_ids,
									'data' => $data
								);
						 
								$headers = array(
									'Authorization: key= AIzaSyDXLGSnR0qzgyGFABr2dWbjW-Zvd3chyoU',
									'Content-Type: application/json'
								);
								// Open connection
								$ch = curl_init();
						 
								// Set the url, number of POST vars, POST data
								curl_setopt($ch, CURLOPT_URL, $url);
						 
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						 
								// Disabling SSL Certificate support temporarly
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						 
								curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
						 
								// Execute post
								$presult = curl_exec($ch);
								if ($presult === FALSE) {
									die('Curl failed: ' . curl_error($ch));
								}
						 
								// Close connection
								curl_close($ch);
				}
				$response["Message"] = 'Success';
				$response['info'] = $info;
				
				if($presult){ 
					$response['count'] = $msg;
					$response['pushnotification'] = $presult;
				}
				//$response['count'] = $count['count'];
			}else{
				$response["Message"] = 'Failure';
			}
					
		}else{
			$response["Message"] = 'Failure';
		}

		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1); 
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	






//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Chat Store ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function getchat() {
		$this->load->model('account/customer');
		
		$this->load->model('tool/image');

		$response = array();
		
		$info = array();
		
		$data = array();
		
		if (isset($this->request->get['sid'])) {
			$sellerId = $this->request->get['sid'];
		} else {
			$sellerId = '';
		}
		
		if (isset($this->request->get['uid'])) {
			$userId = $this->request->get['uid'];
		} else {
			$userId = '';
		}
		
		if (isset($this->request->get['date'])) {
			$date = $this->request->get['date'];
		} else {
			$date = '';
		}

		if (isset( $this->request->get['timezone'] )) {
				$timezone = $this->request->get['timezone'];
				
				$abc = str_replace('%20', '', $timezone);
				$abc = str_replace('%2B', '+', $abc);
				$abc = str_replace('%2D', '-', $abc);
				
				/*
				if (preg_match('/^-/', $timezone)) {
					$abc = $timezone;
				} else {
					$abc = '+'.$timezone;
				} */
				
				$abc = str_replace(' ', '', $abc);
				
				$fck = substr($abc,0,3);
				
				$kcf = substr($abc,3,5);
				
				$zone = $fck.':'.$kcf;
				
			} else {
				$zone =SERVER_TIMEZONE;
			} 
		

		if(isset($sellerId) && isset($userId)){
			
			$data = array(
				'userId' => $userId,
				'sellerId' => $sellerId,
				'date' => $date,
				'zone' => $zone
			);
		//	echo "ksadjlf"; //exit;
			$results = $this->model_account_customer->getChatApp($data);  //echo "<pre>"; print_r($results); exit;
			
			$sellerInfo = $this->model_account_customer->getCustomer($sellerId);   //echo "<pre>"; print_r($sellerInfo);
				
				if ( $sellerInfo ) {
					$image = $this->model_tool_image->resize($sellerInfo['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}
				$sinfo[] = array(
						'sellerId' => $sellerInfo['customer_id'],
						'name' => $sellerInfo['firstname'],
						'image' => $image			
					);
			if($results){
		
				foreach($results as $result){ 
					$this->model_account_customer->editChatApp( $userId, $sellerId );

			
					$info[] = array(
						'Id' => $result['userId'],
						'message' => $result['message'],
						'read' => $result['status'],
						'date' => $result['date']
					);
				}
				
				$response["Message"] = 'Success';
				$response['sellerInfo'] = $sinfo;
				$response['info'] = $info;
				
			}else{
				$response["Message"] = 'Failure2';
			}
					
		}else{
			$response["Message"] = 'Failure1';			
		}

		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1);
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	



//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Chat Store ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function mybuddy() {
		$this->load->model('account/customer');
		
		$this->load->model('tool/image');

		$response = array();
		
		$info = array();
		
		$data = array();
		
		if (isset($this->request->get['uid'])) {
			$userId = $this->request->get['uid'];
		} else {
			$userId = '';
		}
		
		if (isset($this->request->get['timezone'])) {
			$timezone = $this->request->get['timezone'];
			$abc = str_replace('%20', '', $timezone);
				$abc = str_replace('%2B', '+', $abc);
				$abc = str_replace('%2D', '-', $abc);
				
				/*
				if (preg_match('/^-/', $timezone)) {
					$abc = $timezone;
				} else {
					$abc = '+'.$timezone;
				} */
				
				$abc = str_replace(' ', '', $abc);
				
				$fck = substr($abc,0,3);
				
				$kcf = substr($abc,3,5);
				
				$zone = $fck.':'.$kcf;
			
		} else {
			$zone =SERVER_TIMEZONE;
		}

		if( isset($userId) ) {
		$data = array(
			'userId' => $userId,
			'zone' => $zone
		);
			
			$results = $this->model_account_customer->getmybuddyUsers($data);  //echo "<pre>"; print_R($results);
			
			if($results){  //echo "<pre>"; print_r($results);
		
				foreach($results as $result){ 
					
						$message = $result['message'];
						$status = $result['status'];
						$date = $result['date'];
					
					
					if( $userId ==  $result['sellerId']){
						$upositeId = $result['userId'];
					}else{
						$upositeId = $result['sellerId'];
					}
					
				$customerInfo = $this->model_account_customer->getCustomer($upositeId);  
				
				if ($customerInfo['image']) {
								$image = $this->model_tool_image->resize($customerInfo['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							} else {
								$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							}
					
					if($result['userId'] == $userId){
						$status = 'Y';
					}
					
					$info[] = array(
						'message' => $message,
						'read' => $status,
						'date' => $date,
						'sellerId' => $upositeId,
						'userFirstName' => $customerInfo['firstname'],
						'userLastName' => $customerInfo['lastname'],
						'userImage' => $image
						
					);
				}
				$response["Message"] = 'Success';
				$response['info'] = $info;
			}else{
				$response["Message"] = 'Failure';
			}
					
		}else{
			$response["Message"] = 'Failure';			
		}

		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1); 
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	




//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ User Registration Process ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	

public function profCust() {
		$this->load->model('account/customer');
		
		$this->load->model('account/address');
		
		$this->load->model('localisation/country');
		
			include("catalog/controller/product/api/SendSMS.php");
			include("catalog/controller/product/api/IncomingFormat.php");
			include("catalog/controller/product/api/ClientPolled.php");
			
			global $sms_username;
			
			global $sms_password;
			
			$data = array();
			
			$dataCust = array();
			
			$response = array();
			
			$sms_username = "platinum1";

			$sms_password = "daniel1";
			
			global $errstr;

		if (isset($this->request->post['name'])) {
			$name = $this->request->post['name'];
		} else {
			$name = '';
		}
		
		if (isset($this->request->post['email'])) {
			$email = $this->request->post['email'];
		} else {
			$email = '';
		}
		
		if (isset($this->request->post['media'])) {
			$media = $this->request->post['media'];
		} else {
			$media = '';
		}
		
		if (isset($this->request->post['street'])) {
			$street = $this->request->post['street'];
		} else {
			$street = '';
		}
		
		if (isset($this->request->post['deviceId'])) {
			$deviceId = $this->request->post['deviceId'];
		} else {
			$deviceId = '';
		}
		
		
		if (isset($this->request->post['city'])) {
			$city = $this->request->post['city'];
		} else {
			$city = '';
		}
		
		if (isset($this->request->post['country'])) {
			$country_id = $this->request->post['country'];
		} else {
			$country_id = '';
		}
		
		
		if (isset($this->request->post['ph'])) {
			$telephone = $this->request->post['ph'];
		}else{
			$telephone = '';
		}
		
		if (isset($this->request->post['social_account'])) {
			$social_account = $this->request->post['social_account'];
		}else{
			$social_account = '';
		}
	
		if (isset($this->request->post['img'])) {
			$img = $this->request->post['img'];
			
			
			$data1 = base64_decode($img);
			$imgId = rand(100000000,999999999);
			$text =str_replace(' ','-', $name); 
			$dir = DIR_IMAGE.'data/demo/customers/'.$text.'-'.$imgId.'.png';
			file_put_contents( $dir, $data1);
			
			$image = 'data/demo/customers/'.$text.'-'.$imgId.'.png';
		} else {
			$image = 'data/demo/no-image.jpg';
		} 
		//$image = 'data/demo/no-image.jpg';
		

		//$phone_no = '+917355169901';
			$digits_needed=4;

			$source_addr=''; // set up a blank string

			$count=0;

			while ( $count < $digits_needed ) {
				$random_digit = mt_rand(0, 9);
				
				$source_addr .= $random_digit;
				$count++;
			} 
		if(!empty($telephone)){
		$replies = send_sms($telephone,(int)$source_addr, "Your FastBuyApp secure code is ".$source_addr) or die ("Error: " . $errstr . "\n");
		if($replies){
			
		$data['scode'] = $source_addr;
		
		$customer_id = $this->model_account_customer->addCustomerApp($data);  
		
		$country = $this->model_localisation_country->getCountry($country_id); 
		$dataCust = array(
			'firstname' => $name,
			'email' => $email,
			'telephone' => $country['country_code'].$telephone,
			'image' => $image,
			'media' => $media,
			'deviceId' => $deviceId,
			'customer_id' => $customer_id,
			'social_account' => $social_account
		);
		
		$this->model_account_customer->editCustomerApp($dataCust);
		
		

					//$address = $street.'+'.$city.'+'.$country['name'];
					$address = $city.'+'.$country['name'];
					$address = str_replace(' ', '+', $address);
					$url = "http://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false";
					$result = file_get_contents($url);
					$result = json_decode($result, true);

					$latitude = $result['results'][0]['geometry']['location']['lat'];
					$longitude = $result['results'][0]['geometry']['location']['lng'];
		
		$addresss = array();
		
		$addresss = array(
			'firstname' => $name,
			'email' => $email,
			'street' => $street,
			'city' => $city,
			'country_id' => $country_id,
			'customer_id' => $customer_id,
			'longitude' => $longitude,
			'latitude' => $latitude
		);
		
		$customer = $this->model_account_customer->getCustomer($customer_id);
		
		if($customer['address_id']){
		
			$this->model_account_address->editAddressApp($customer['address_id'], $addresss); 
			
		}
		
		
		$response["Message"] = 'Success';
		$response["CustomerId"] = $customer_id;
		$response["SecureCode"] = $source_addr;
	} else{
				$response["Message"] = 'Failure';
		}
	} else{
				$response["Message"] = 'Failure';
		}
					
		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1); 
}

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Profile Update ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function eCustomer() {
		$this->load->model('account/customer');
		
		$this->load->model('account/address');
		
		$this->load->model('localisation/country');
		
		$response = array();
		
		$addresss = array();
		
		$data = array();
		
		if (isset($this->request->post['custId'])) {
			$customer_id = $this->request->post['custId'];
		}else{
			$customer_id = '';
		}
		
		if($customer_id){
		if (isset($this->request->post['name'])) {
			$name = $this->request->post['name'];
		} else {
			$name = '';
		}
		
		if (isset($this->request->post['email'])) {
			$email = $this->request->post['email'];
		} else {
			$email = '';
		}
	
		if (isset($this->request->post['street'])) {
			$street = $this->request->post['street'];
		} else {
			$street = '';
		}
		
		
		if (isset($this->request->post['city'])) {
			$city = $this->request->post['city'];
		} else {
			$city = '';
		}
		
		if (isset($this->request->post['country'])) {
			$country_id = $this->request->post['country'];
		} else {
			$country_id = '';
		}
		
		
		if (isset($this->request->post['pass'])) {
			$password = $this->request->post['pass'];
		} else {
			$password = '';
		}
		
		
		if (isset($this->request->post['ph'])) {
			$telephone = $this->request->post['ph'];
		} else {
			$telephone = '';
		}
		
		if (isset($this->request->post['media'])) {
			$media = $this->request->post['media'];
		} else {
			$media = '';
		}
		
		if (isset($this->request->post['pimg'])) {
			$iimg = $this->request->post['pimg'];
			
			
			$data1 = base64_decode($iimg);
			$imgId = rand(100000000,999999999);
			$text =str_replace(' ','-', $name); 
			$dir = DIR_IMAGE.'data/demo/customers/'.$text.'-'.$imgId.'-'.$customer_id.'.png';
			file_put_contents( $dir, $data1);
			
			$image = 'data/demo/customers/'.$text.'-'.$imgId.'-'.$customer_id.'.png';
		} else {
			$image = 'data/demo/no-image.jpg';
		}
		
		
		$data = array(
			'firstname' => $name,
			'email' => $email,
			'media' => $media,
			'telephone' => $telephone,
			'password' => $password,
			'image' => $image,
			'customer_id' => $customer_id
		);
		
		$this->model_account_customer->editCustomerApp($data);
		
		$country = $this->model_localisation_country->getCountry($country_id); 

					//$address = $street.'+'.$city.'+'.$country['name'];
					$address = $city.'+'.$country['name'];
					$address = str_replace(' ', '+', $address);
					$url = "http://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false";
					$result = file_get_contents($url);
					$result = json_decode($result, true);

					$latitude = $result['results'][0]['geometry']['location']['lat'];
					$longitude = $result['results'][0]['geometry']['location']['lng'];
		
		
		$addresss = array(
			'firstname' => $name,
			'email' => $email,
			'password' => $password,
			'street' => $street,
			'city' => $city,
			'country_id' => $country_id,
			'customer_id' => $customer_id,
			'longitude' => $longitude,
			'latitude' => $latitude
		);
		
		$customer = $this->model_account_customer->getCustomer($customer_id);
		
		if($customer['address_id']){
		
			$this->model_account_address->editAddressApp($customer['address_id'], $addresss); 
			
		}
		
			$response["Message"] = 'Success';
			//$response["post"] = $this->request;
			//$response["country"] = $country;
	} else{
				$response["Message"] = 'Failure';
		}
				
		
		$test1= json_encode($response);
		echo $test2=str_replace('\/','/', $test1); 
}

//*******************************************************************************************************************************************************************//
//********************************************************************** CHeck phone is exits or not *********************************************************************************************//
//*******************************************************************************************************************************************************************//

public function isexitsph() { //echo "kumar"; exit;
				$this->load->model('account/customer');
				
				include("catalog/controller/product/api/SendSMS.php");
				include("catalog/controller/product/api/IncomingFormat.php");
				include("catalog/controller/product/api/ClientPolled.php");
				
				global $sms_username;
				
				global $sms_password;
				
				$data = array();
				
				$dataCust = array();
				
				$response = array();
				
				$sms_username = "platinum1";

				$sms_password = "daniel1";
				
				global $errstr;
				
				if ( isset($this->request->get['ph']) ) {
				
					$dest_addr = $this->request->get['ph'];
					
					$media = $this->request->get['media'];
					
					$deviceId = $this->request->get['deviceId'];
					
					$country_id = $this->request->get['country_id'];
					
					$digits_needed=4;

					$source_addr=''; // set up a blank string

					$count=0;

					while ( $count < $digits_needed ) {
						$random_digit = mt_rand(0, 9);
						
						$source_addr .= $random_digit;
						$count++;
					}
				
				$replies = send_sms($dest_addr,(int)$source_addr, "Your FastBuyApp secure code is ".$source_addr) or die ("Error: " . $errstr . "\n");
				
				if($replies){
				
				$data1 = array();
				
				$data1 = array(
					'telephone' => str_replace(' ','+',$dest_addr),
					'media' => $media,
					'country_id' => $country_id
				);
				
				$cust_info = $this->model_account_customer->getCustomerByPhone($data1);
					//echo "<pre>"; print_r($cust_info);
					if($cust_info){
					
					$data = array(
						'customer_id' => $cust_info['customer_id'],
						'scode' => $source_addr
					);
					
					$this->model_account_customer->editSourceCode($data);
					
						$response = array(
							'Message' => 'Success',
							'SecureCode' => $source_addr,
							'CustomerId' => $cust_info['customer_id']
						);
					}else{
						
						$data = array(
							'scode' => $source_addr,
							'telephone' => $dest_addr,
							'deviceId' => $deviceId,
							'country_id' => $country_id
						);
						
						$customer_id = $this->model_account_customer->addCustomerApp($data);
						
						if($customer_id){
							$response = array(
								'CustomerId' => $customer_id,
								'Message' => 'Failure',
								'SecureCode' => $source_addr
							);
						}else{
							$response["Message"] = 'Failure';
						}
					}
				}else{
					$response["Message"] = 'Failure';
				}
			}else{
					$response["Message"] = 'Failure';
				}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1);
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


 public function sms() {
			$this->load->model('account/customer');
			
			include("catalog/controller/product/api/SendSMS.php");
			include("catalog/controller/product/api/IncomingFormat.php");
			include("catalog/controller/product/api/ClientPolled.php");
			
			global $sms_username;
			
			global $sms_password;
			
			$data = array();
			
			$response = array();
			
			$sms_username = "platinum1";

			$sms_password = "daniel1";
			
			global $errstr;
			
			if (isset($_GET['ph'])) {
					$dest_addr = "+".$_GET['ph'];
			}
			
			if (isset($_GET['deviceId'])) {
					$deviceId = $_GET['deviceId'];
			}
			//$phone_no = '+917355169901';
			$digits_needed=4;

			$source_addr=''; // set up a blank string

			$count=0;

			while ( $count < $digits_needed ) {
				$random_digit = mt_rand(0, 9);
				
				$source_addr .= $random_digit;
				$count++;
			} 
		if( isset($dest_addr) && !empty($dest_addr) && isset($deviceId) && !empty($deviceId) ){
		$replies = send_sms($dest_addr,(int)$source_addr, "Your FastBuyApp secure code is ".$source_addr) or die ("Error: " . $errstr . "\n");
		if($replies){
		
		$data['scode'] = $source_addr;
		
		$data['deviceId'] = $deviceId;
		
		$cust_info = $this->model_account_customer->getCustomerByPhone($dest_addr);
		if($cust_info){
			$data = array(
				'customer_id' => $cust_info['customer_id'],
				'scode' => $source_addr
			);
			
			$this->model_account_customer->editSourceCode($data);
			$response = array(
					'Message' => 'Success',
					'SecureCode' => $source_addr,
					'CustomerId' => $cust_info['customer_id']
				);
		}else{
			$customer_id = $this->model_account_customer->addCustomerApp($data); 
			
			if($customer_id){
				$response = array(
					'Message' => 'Failure',
					'SecureCode' => $source_addr,
					'CustomerId' => $customer_id
				);
			}else{
				$response = array(
					'Message' => 'Failure'
				);
			}
		}
			
			}else{
				$response = array(
					'Message' => 'Failure'
				);
			}
			}else{
				$response = array(
					'Message' => 'Failure'
				);
			}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
		
}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Get Latitude And Longitude ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
	public function laglat() {
				$this->load->model('account/customer');
				
				$this->load->model('account/address');
				
				$this->load->model('catalog/product');
				
				$this->load->model('catalog/category');
				
				$this->load->model('catalog/review');
				
				$this->load->model('tool/image');

				$response = array();
				
				$sellerInfo = array();
				
				$sellerPro = array();
				
				if(isset($this->request->get['latitude'])){
					$latitude = $this->request->get['latitude'];
				}else{
					$latitude =  false;
				}
				
				if(isset($this->request->get['longitude'])){
					$longitude = $this->request->get['longitude'];
				}else{
					$longitude =  false;
				}
				
				if(isset($this->request->get['radius'])){
					$radius = $this->request->get['radius'];
				}else{
					$radius =  false;
				}
				
				if($latitude <> '' && $longitude <> ''&& $radius <> ''){
				$addresses = $this->model_account_address->getSellerLocation($latitude,$longitude,$radius);  //echo "<pre>"; print_r($addresses);
					
				foreach($addresses as $address){ 
									
					$products = $this->model_catalog_product->getUserProduct($address['customer_id']); 
					 
					foreach($products as $product){
					$name='';
							if ($product['image']) {
								$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							} else {
								$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							}
							
							$likes = $this->model_catalog_review->getTotalLikeByProductId($product['product_id']);
			
							$comments = $this->model_catalog_review->getTotalCommentsByProductId($product['product_id']);
							
							$categoryInfo = $this->model_catalog_category->getParentCategory($product['category_id']); 
							
						if($categoryInfo){
							$name = $categoryInfo['name'];
						}else{
							$name = '';
						}
						$sellerPro = array(
							'MainCategory' => $name
							//'product_id' => $product['product_id'],
							//'name' => $product['name'],
							//'image' => $image,
							//'TotalLikes' => $likes,
							//'TotalComments' => $comments,
							//'date_modified' => date('Y-m-d',strtotime($product['date_modified']))
						);
						
						}
						$customerInfo = $this->model_account_customer->getCustomer($address['customer_id']); 
						
						if($customerInfo['customer_id']){
						
						if($customerInfo['firstname'] <> '' && $customerInfo['firstname'] <> NULL){
							$firstname = $customerInfo['firstname'];
						}else{
							$firstname = '';
						}
						
						if($address['latitude'] <> '' && $address['latitude'] <> NULL){
							$latitude = $address['latitude'];
						}else{
							$latitude = '';
						}
						
						if($address['longitude'] <> '' && $address['longitude'] <> NULL){
							$longitude = $address['longitude'];
						}else{
							$longitude = '';
						}
						
							$sellerInfo[] = array(
								'SellerUserID' => $customerInfo['customer_id'],
								'SellerFirstName' => $firstname,
								'SellerLat' =>$latitude,
								'SellerLong' => $longitude,
								'SellerProducts' => $name
							);
						}
				}
					if($sellerInfo){
						$response = array(
						'Message' => 'Success',
						'SellerInfo' => $sellerInfo						
					); 
					}else{
						$response = array(
						'Message' => 'Failure'						
					); 
					}
					
				}else{
					$response = array(
						'Message' => 'Failure'						
					); 
				}
				$pInfo = array();
				$pInfo['info'] = $response;
				$test1= json_encode($pInfo);
				echo $test2=str_replace('\/','/', $test1); 
	}
	
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Registration Process ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//		
	public function srcReg() { 
				$this->load->model('account/customer');
				
				$response = array();

				$userId = $_GET['userid'];
				
				$scode = $_GET['scode'];
				
			if($userId != '' && $scode != ''){ 
				$customerInfo = $this->model_account_customer->getCustomerr($userId);
				
				if($customerInfo['scode'] == $scode){
						$customer_id = $this->model_account_customer->editCustomerRegApp($userId); 
						$response = array(
							'Message' => 'Success'
						);
				}else{
					$response = array(
						'Message' => 'Failure2'
					);
				}
			}else{
				$response = array(
					'Message' => 'Failure1'
				);
			}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
		}
		
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show All Categories ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function allCat() {
				$this->load->model('catalog/category');

				$this->load->model('catalog/product');
				
				$this->load->model('tool/image'); 

				$response = array();
				
				$cate = array();
				
				if(isset($this->request->get['catid'])){
					$catid = $this->request->get['catid'];
				}else{
					$catid =  0;
				}

				$categories = $this->model_catalog_category->getCategories($catid);
			if($categories){
				foreach ($categories as $category) {
					$children_data = array();

					$children = $this->model_catalog_category->getCategories($category['category_id']);

					foreach ($children as $child) {
						if ($child['image']) {
							$image = $this->model_tool_image->resize($child['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
						} else {
							$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						$children_data[] = array(
							'category_id' => $child['category_id'],
							'name'        => $child['name'],
							'image'        => $image,
						);		
					}
						
					$response[] = array(
						'category_ID' => $category['category_id'],
						'name'        => $category['name'],
						'children'    => $children_data,
					);	
				}
				$response['Message'] = 'Success';
				//$response['categories'] = $cate;
			}else{
				$response = array(
						'Message' => 'Failure'
						);
			}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show All Categories ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function allCatt() {
				$this->load->model('catalog/category');

				$this->load->model('catalog/product');
				
				$this->load->model('tool/image'); 

				$response = array();
				
				$abc = array();
				
				if(isset($this->request->get['catid'])){
					$catid = $this->request->get['catid'];
				}else{
					$catid =  0;
				}

				$categories = $this->model_catalog_category->getCategories($catid);
			if($categories){
				foreach ($categories as $category) {
					$children_data = array();

					$children = $this->model_catalog_category->getCategories($category['category_id']);

					foreach ($children as $child) {
						if ($child['image']) {
							$image = $this->model_tool_image->resize($child['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
						} else {
							$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						$children_data[] = array(
							'category_id' => $child['category_id'],
							'name'        => $child['name'],
							'image'        => $image,
						);		
					}
					
					$response['item'][] = array(
						'category_ID' => $category['category_id'],
						'name'        => $category['name'],
						'children'    => $children_data,
					);	
				}
				$response['Message'] = 'Success';
				//$response['categories'] = $cate;
			}else{
				$response = array(
						'Message' => 'Failure'
						);
			}
				$abc['category'] =  $response;
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show All Categories Dropdown ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function allCatDrop() {
				$this->load->model('catalog/category');

				$this->load->model('catalog/product');
				
				$categoriesArr = array();

				$categories = $this->model_catalog_category->getCategories(0); 
			if($categories){
				foreach ($categories as $categry) { 
						
					$categoriesArr[] = array(
						'success' => '1',
						'category_id' => $categry['category_id'],
						'name'        => $categry['name'],
					);	
				}
			}else{
				$categoriesArr = array(
						'success' => '0'
						);
			} 
				$test1= json_encode($categoriesArr);
				echo $test2=str_replace('\/','/', $test1); 
			
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show Products By Category Categories ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
		public function catPro() {
				$this->load->model('catalog/product');
				
				$this->load->model('tool/image'); 
				
				$this->load->model('localisation/currency');
				
				$products = array();
				
				$response = array();
			if (isset($this->request->get['path']) && $this->request->get['path'] <> '') { 
				if (isset($this->request->get['path'])) {
					$category_id = $this->request->get['path'];
				}
				
				if (isset($this->request->get['order'])) {
					$order = $this->request->get['order'];
				}else{
					$order = 'ASC';
				}
			
				if($category_id){
				$data = array(
				'filter_category_id' => $category_id,
				'order'              => $order
			);
				
			
				
				$results = $this->model_catalog_product->getProducts($data); 
				if($results){
				foreach ($results as $result) {
				
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					}
					
					
					$currency = $this->model_localisation_currency->getCurrencyByCode($product_info['currency_code']);	
					
					$price = round($result['price'],2);
					
					if($currency['symbol_left']){
						$price = $currency['symbol_left'].$price;
					}else{
						$price = $price.$currency['symbol_right'];
					}
					
					$products[] = array(
						'Product_id'  => $result['product_id'],
						'Product_Image' => $image,
						'Product_Name' => $result['name'],
						'Price' => $price
					);
				}
				$response['Message'] =  'Success';
				$response['products'] = $products;
				}else{
					$response = array(
						'Message' => 'Failure'
					);
				}
				}else{ 
					$response = array(
						'Message' => 'Failure'
					);
				}
				}else{ 
					$response = array(
						'Message' => 'Failure'
					);
				}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
				
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Product Details ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function pro() {
				$this->load->model('catalog/product');
				
				$this->load->model('catalog/review');
				
				$this->load->model('tool/image'); 
				
				$this->load->model('account/customer');
				
				$this->load->model('account/address');
				
				$this->load->model('localisation/currency');
				
				$products = array();
				
				$response = array();
				
				if ( isset($this->request->get['proId']) && $this->request->get['proId'] <> '' ) {
				
				if ( isset($this->request->get['proId']) ) {
					$product_id = $this->request->get['proId'];
				}
				
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if($product_info){  
						$cInfo = array();
						$cust_info = $this->model_account_customer->getCustomer($product_info['customer_id']);  
						//echo "<pre>"; print_r($cust_info);
						$addresses = $this->model_account_address->getAddressesApp($cust_info['customer_id']); 
		 				//echo "<pre>"; print_r($addresses);
						 foreach($addresses as $addre){
							if($addre['city'] <> '' && $addre['city'] <> NULL){
								$address = $addre['city'].', '. $addre['country'];
							}else{
								$address = '';
							}	
						 }						
		
						if($cust_info){
						if($cust_info['firstname'] <> '' && $cust_info['firstname'] <> NULL){
							$firstname = $cust_info['firstname'];
						}else{
							$firstname = '';
						}
						
						if($cust_info['image'] <> '' && $cust_info['image'] <> NULL){
							$image = $this->model_tool_image->resize($cust_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}else{
							$image = '';//$this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						
							$cInfo[] = array(
								'customer_id'     => $cust_info['customer_id'],
								'name'     => $firstname,
								'address' => html_entity_decode($address, ENT_QUOTES, 'UTF-8'),
								'image'       => $image
							);
						}
				
						if($product_info['image'] <> '' && $product_info['image'] <> NULL){
							$imagePro = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
						} else {
							$imagePro = '';//$this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						$images = array();

						$results = $this->model_catalog_product->getProductImages($product_id);
                        $images[] = array(
                        'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'))
                        );
						foreach ($results as $result) {
						if($result['image']){
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
						}else{
							$image = '';//$this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
							$images[] = array(
								'thumb' => $image
							);
						}
                        
						
						$reviews = array();

						$results = $this->model_catalog_review->getReviewsByProductId($product_id,0, 200);
						
						foreach ($results as $result) { 
						
								$customer_info = $this->model_account_customer->getCustomer($result['customer_id']); 
								
								if($result['text']){
									if($customer_info['firstname'] <> '' && $customer_info['firstname'] <> NULL){
										$firstname = $customer_info['firstname'];
									}else{
										$firstname =  '';
									}
									
									if($customer_info['image'] <> '' && $customer_info['image'] <> NULL){
										$image = $this->model_tool_image->resize($customer_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
									}else{
										$image = '';//$this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
									}
									
									$reviews[] = array(
										'customer_id'     => $customer_info['customer_id'],
										'name'     => $customer_info['firstname'],
										'image'       => $image,
										'text'       => html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8')
									);
								}
						}
						
					$likes = $this->model_catalog_review->getTotalLikeByProductId($product_id);
			
					$comments = $this->model_catalog_review->getTotalCommentsByProductId($product_id);
					
					$currency = $this->model_localisation_currency->getCurrencyByCode($product_info['currency_code']);	
					
					$price = round($product_info['price'],2);
					
					if($currency['symbol_left']){
						$price = $currency['symbol_left'].$price;
					}else{
						$price = $price.$currency['symbol_right'];
					}
					
					$products[] = array(
						'Product_Id' => $product_info['product_id'],
						'Product_Name'    	 => $product_info['name'],
						'Product_Year'    	 => $product_info['model'],
						'Product_Price'   	 => $price,
						'Product_Image'   	 => $imagePro,
						'Product_Images'   	 => $images,
						'ProductLikes'     => $likes,
						'ProductComments'    => $comments,
						'reviews'    => $reviews,
						'SellerInfo'    => $cInfo
					);
					
				$response['Message'] = 'Success';
				
				$response['ProductDetail'] = $products;
				
				}else{
					$response['Message'] = 'Failure';
				}
				}else{
					$response['Message'] = 'Failure';
				}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//



//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Add Review(LIKE)) ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	

public function addLike() {
		$this->load->model('catalog/product');
				
		$this->load->model('catalog/review');
		
		$this->load->model('account/customer');
		
		$this->load->model('tool/image'); 
			
		if (isset($this->request->get['custId'])) {
			$customer_id = $this->request->get['custId'];
		}
		
		if (isset($this->request->get['proId'])) {
			$product_id = $this->request->get['proId'];
		}
		
		if (isset($this->request->get['text'])) {
			$text = $this->request->get['text'];
		}else{
			$text = '';
		}
		
		if (isset($this->request->get['rat'])) {
			$rating = 5;
		}else{
			$rating = 0;
		}
		
		$review = array();
		
		if( isset($customer_id) && isset($product_id) ) {
		
			$customer_info = $this->model_account_customer->getCustomer($customer_id); 
			
			if($customer_info){
			
			$data = array();			
			
			$data = array(
				'name' => $customer_info['firstname'],
				'customer_id' => $customer_id,
				'text' => $text,
				'rating' =>$rating
			);
		if($rating == 5){
			$record = $this->model_catalog_review->getReviewsByProIdCustIdApp($product_id, $customer_id); 
			if(!$record){
				$a = $this->model_catalog_review->addReviewApp($product_id, $data);
			}
		}else{
				$a = $this->model_catalog_review->addReviewApp($product_id, $data);
		}
			
			
			$likes = $this->model_catalog_review->getTotalLikeByProductId($product_id);
			
			$comments = $this->model_catalog_review->getTotalCommentsByProductId($product_id);
		
			if (isset($this->request->get['text'])) {
			
			$customer = array();
			if($customer_info['image'] <> '' && $customer_info['image'] <> NULL){ 
				$image = $this->model_tool_image->resize($customer_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')); 
			}else{ 
				$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			}
			
			$customer = array(
					'CommentBy' => $customer_info['firstname'],
					'ProfilePic' => $image,
					'Message' => $this->request->get['text']
				);
	
				$review = array(
					'Comments' => $customer,
					'CommentsCount' => $comments
				);
				
			}else{
				$review = array(
					'LikeCount' => $likes
				);
			}
			$review['Message'] = 	'Success';
		} else{
				$review = array(
					'Message' => 'Failure'
				);
			}
		}else{
			$review = array(
				'Message' => 'Failure'
			);
		}
		$test1= json_encode($review);
		echo $test2=str_replace('\/','/', $test1); 
		
}

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//




//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Product Search ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	

		public function getS() {
				$this->load->model('catalog/product');

				$this->load->model('tool/image'); 
				
				if (isset($this->request->get['tag'])) {
					$tag = $this->request->get['tag'];
				} else {
					$tag = '';
				}
		
				$products = array();
				
				$response = array();

				if (isset($tag)) {
					$data = array(
						'filter_name'         => $tag, 
						'filter_tag'          => $tag, 
						'filter_description'  => $tag
					);

					$results = $this->model_catalog_product->getProducts($data);
					
					if($results){
					foreach ($results as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$products[] = array(
							'product_id'  => $result['product_id'],
							'thumb'       => $image,
							'name'        => $result['name']
						);
						
					}
					$response['Message'] = 'Success';
					$response['item'] = $products;
				}else{
					$response = array(
						'Message' => 'Failure'
					);
				}
				}else{
					$response = array(
						'Message' => 'Failure'
					);
				}
                
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
	}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//







//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Delete Products ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

	public function delPro() {
				$this->load->model('catalog/product');

				if (isset($this->request->get['proId'])) {
					$proId = $this->request->get['proId'];
				} else {
					$proId = '';
				}
				if($this->request->get['proId']){
				$product_info = $this->model_catalog_product->getProduct($proId);
				if($product_info){
						$results = $this->model_catalog_product->deleteProduct($proId);
						
						$response = array();
						
						$response["Message"] = 'Success';
					}else{
					$response["Message"] = 'Failure';
				}
				} else{
					$response["Message"] = 'Failure';
				}
					
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1); 
	}
	
	
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Sell Products ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

public function sellPro() {
				$this->load->model('catalog/product');
				
				$response = array();
				
				if (isset($this->request->post['custId']) && $this->request->post['custId'] !='' && isset($this->request->post['catId']) && $this->request->post['catId'] !='' && isset($this->request->post['name']) && $this->request->post['name'] !='' && isset($this->request->post['prc']) && $this->request->post['prc'] !='' ) { 
				
				if (isset($this->request->post['catId'])) {
					$category_id = $this->request->post['catId'];
				} else {
					$category_id = '';
				}
				
				if (isset($this->request->post['custId'])) {
					$customer_id = $this->request->post['custId'];
				} else {
					$customer_id = '';
				}
				
				if (isset($this->request->post['subcatId'])) {
					$sub_category_id = $this->request->post['subcatId'];
				} else {
					$sub_category_id = '';
				}
				
				if (isset($this->request->post['name'])) {
					$product_name = $this->request->post['name'];
				} else {
					$product_name = '';
				}
				$name = str_replace(" ","-",$product_name);
				if (isset($this->request->post['des'])) {
					$product_description = $this->request->post['des'];
				} else {
					$product_description = '';
				}
				
				if (isset($this->request->post['prc'])) {
					$product_price = $this->request->post['prc'];
				} else {
					$product_price = '';
				}
				
				if (isset($this->request->post['tag'])) {
					$product_tag = $this->request->post['tag'];
				} else {
					$product_tag = '';
				}
				
				if (isset($this->request->post['corn'])) {
					$currency_code = $this->request->post['corn'];
				} else {
					$currency_code = '';
				}
				
				$nname =str_replace(' ','-', $name); 
				
				if (isset($this->request->post['img1']) && !empty($this->request->post['img1']) ) {
				
					$data1 = $this->request->post['img1'];
					$data1 = base64_decode($data1);
					$imgId = rand(100000000,999999999);
					$dir = DIR_IMAGE.'data/demo/apro/'.$nname.'-'.$imgId.'-'.$sub_category_id.'.png';
					file_put_contents( $dir, $data1);
					
					$imgName = 'data/demo/apro/'.$nname.'-'.$imgId.'-'.$sub_category_id.'.png';
					
					$product_img1 = $imgName;
				} else {
					$product_img1 = '';//'data/demo/no-image.jpg';
				}
				
				if (isset($this->request->post['img2']) && !empty($this->request->post['img2']) ) {
					$data2 = $this->request->post['img2'];
					$data2 = base64_decode($data2);
					$imgId2 = rand(100000000,999999999);
					$dir = DIR_IMAGE.'data/demo/apro/'.$nname.'-'.$imgId2.'-'.$sub_category_id.'.png';
					file_put_contents( $dir, $data2);
					
					$imgName2 = 'data/demo/apro/'.$nname.'-'.$imgId2.'-'.$sub_category_id.'.png';
					
					$product_img2 = $imgName2;
				} else {
					$product_img2 = '';//'data/demo/no-image.jpg';
				}
				
				if (isset($this->request->post['img3'])  && !empty($this->request->post['img3']) ) {
					$data3 = $this->request->post['img3'];
					$data3 = base64_decode($data3);
					$imgId3 = rand(100000000,999999999);
					$dir = DIR_IMAGE.'data/demo/apro/'.$nname.'-'.$imgId3.'-'.$sub_category_id.'.png';
					file_put_contents( $dir, $data3);
					
					$imgName3 = 'data/demo/apro/'.$nname.'-'.$imgId3.'-'.$sub_category_id.'.png';
					
					$product_img3 = $imgName3;
				} else {
					$product_img3 = '';//'data/demo/no-image.jpg';
				}
				
				if (isset($this->request->post['img4']) &&  !empty($this->request->post['img4']) ) {
					$data4 = $this->request->post['img4'];
					$data4 = base64_decode($data4);
					$imgId4 = rand(100000000,999999999);
					$dir = DIR_IMAGE.'data/demo/apro/'.$nname.'-'.$imgId4.'-'.$sub_category_id.'.png';
					file_put_contents( $dir, $data4);
					
					$imgName4 = 'data/demo/apro/'.$nname.'-'.$imgId4.'-'.$sub_category_id.'.png';
					
					$product_img4 = $imgName4;
				} else {
					$product_img4 = '';//'data/demo/no-image.jpg';
				}
				
				if (isset($this->request->post['img5']) &&  !empty($this->request->post['img5']) ) {
					$data5 = $this->request->post['img5'];
					$data5 = base64_decode($data5);
					$imgId5 = rand(100000000,999999999);
					$dir = DIR_IMAGE.'data/demo/apro/'.$nname.'-'.$imgId5.'-'.$sub_category_id.'.png';
					file_put_contents( $dir, $data5);
					
					$imgName5 = 'data/demo/apro/'.$nname.'-'.$imgId5.'-'.$sub_category_id.'.png';
					
					$product_img5 = $imgName5;
				} else {
					$product_img5 = '';//'data/demo/no-image.jpg';
				}
				
				if (isset($this->request->post['img6']) &&  !empty($this->request->post['img6']) ) {
					$data6 = $this->request->post['img6'];
					$data6 = base64_decode($data6);
					$imgId6 = rand(100000000,999999999);
					$dir = DIR_IMAGE.'data/demo/apro/'.$nname.'-'.$imgId6.'-'.$sub_category_id.'.png';
					file_put_contents( $dir, $data6);
					
					$imgName6 = 'data/demo/apro/'.$nname.'-'.$imgId6.'-'.$sub_category_id.'.png';
					
					$product_img6 = $imgName6;
				} else {
					$product_img6 = '';//'data/demo/no-image.jpg';
				}
				
				$product_des = array();
				
				$product_des[] = array(
					'name' =>$product_name,
					'description' =>$product_description,
					'tag' => $product_tag
				);
				
				$images = array();
				
				$images[0] = array(
					'image' =>$product_img2,
					'sort_order' => 1
				);
				
				$images[1] = array(
					'image' =>$product_img3,
					'sort_order' => 2
				);
				
				$images[2] = array(
					'image' =>$product_img4,
					'sort_order' => 3
				);
				
				$images[3] = array(
					'image' =>$product_img5,
					'sort_order' => 4
				);
				
				$images[4] = array(
					'image' =>$product_img6,
					'sort_order' => 5
				);
				
				$category = array();
				
				$category[0] = array(
					'category_id' =>$sub_category_id
				);
				
				$product_store = array();
				
				$product_store[0] = array(
					'store_id' => 0
				);
				
				$data = array();
				
				$data = array(
					'customer_id' => $customer_id,
					'product_description' => $product_des,
					'product_image' => $images,
					'product_category' => $sub_category_id,
					'product_store' => $product_store,
					'currency_code' => $currency_code,
					'price' =>$product_price,
					'image' =>$product_img1,
					'status' => 1
				);
				
				$results = $this->model_catalog_product->addProduct($data);
				
				if($results){
					$response["Message"] = 'Success';
				}else{
					$response["Message"] = 'Failure';
				}
				
				
		} else{
				$response["Message"] = 'Failure';
		}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1);
}


//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Get User Info ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

public function profDtl() {
				$this->load->model('catalog/product');
				
				$this->load->model('catalog/review');
				
				$this->load->model('tool/image'); 
				
				$this->load->model('account/customer');
				
				$this->load->model('account/address');

				$response = array();
				
				if (isset($this->request->get['uid'])) {
					$customerId = $this->request->get['uid'];
				} else if(isset($this->request->get['social_account'])){
					$social_account = $this->request->get['social_account'];
					
					$cust_social = $this->model_account_customer->getCustomerSocial($social_account);  
//echo "<pre>"; print_r($cust_social);
					$customerId = $cust_social['customer_id'];
				} else if (isset($this->request->get['phone'])) {
					$phone = $this->request->get['phone'];
					
					$phone = str_replace(' ','',$phone);
					
					$phone = "+".$phone;
					
					$cust_phone = $this->model_account_customer->getCustomerPhone($phone);
//echo "<pre>"; print_r($cust_phone);
					$customerId = $cust_phone['customer_id'];
				}
					
					$customer_info = array();
						$cust_info = $this->model_account_customer->getCustomer($customerId);  
						
						$addresses = $this->model_account_address->getAddressApp($cust_info['address_id'], $cust_info['customer_id']); 

						$street = $addresses['address_1'];
						$city = $addresses['city'];
						$country = $addresses['country'];
						 
						if($cust_info){
							if($cust_info['firstname'] <> '' && $cust_info['firstname'] <> NULL){
								$firstname = $cust_info['firstname'];
							}else{
								$firstname = '';
							}
							
							if($cust_info['image'] <> '' && $cust_info['image'] <> NULL){
								$image = $this->model_tool_image->resize($cust_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							} else {
								$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							}
							
							$customer_info = array(
								'customer_id'     => $cust_info['customer_id'],
								'address'     => html_entity_decode($city.', '.$country, ENT_QUOTES, 'UTF-8'),
								'name'     => $firstname,
								'image'       => $image
							);
							
					$product_info = array();
					
					$products = $this->model_catalog_product->getUserProduct($customerId);
					
					if($products){

					foreach($products as $product){
							
							if ($product['image']) {
								$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							} else {
								$image = '';//$this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							}
						
						$likes = $this->model_catalog_review->getTotalLikeByProductId($product['product_id']);
			
						$comments = $this->model_catalog_review->getTotalCommentsByProductId($product['product_id']);
						
							$product_info[] = array(
								'product_id' => $product['product_id'],
								'name' => $product['name'],
								'image' => $image,
								'TotalLikes' => $likes,
								'TotalComments' => $comments,
								'date_modified' => date('Y-m-d',strtotime($product['date_modified']))
							);
						}
					}
					
					$response = array(
						'Message' => 'Success',
						'SellerInfo' => $customer_info,
						'product_info' => $product_info
					);
					
				}else{
					$response["Message"] = 'Failure1';
				}
				
				$abc['info'] = $response;
				$test1= json_encode($abc);
				echo $test2=str_replace('\/','/', $test1);
}

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Get User Info ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

public function userdlt() {
				$this->load->model('account/customer');
			
				$response = array();
				
				if (isset($this->request->get['uid'])) {
					$customerId = $this->request->get['uid'];
					$this->model_account_customer->deleteCustomer($customerId);  
					$response["Message"] = 'Success';
				}else{
					$response["Message"] = 'Failure';
				}
				echo json_encode($response);
}

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//



//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Get User Info ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//

public function getDtl() {
				$this->load->model('tool/image'); 
				
				$this->load->model('account/customer');
				
				$this->load->model('account/address');

				$response = array();
				
				if (isset($this->request->get['custId'])) {
					$customerId = $this->request->get['custId'];
					
					$customer_info = array();
						$cust_info = $this->model_account_customer->getCustomer($customerId);  // echo "<pre>"; print_R($cust_info);
						
						$addresses = $this->model_account_address->getAddressApp($cust_info['address_id'], $cust_info['customer_id']); 
//echo "<pre>"; print_R($addresses);
						$street = $addresses['address_1'];
						$city = $addresses['city'];
						$country = $addresses['country'];
						 
						if($cust_info){// echo "<pre>"; print_r($cust_info);
							if($cust_info['firstname'] <> '' && $cust_info['firstname'] <> NULL){
								$firstname = $cust_info['firstname'];
							}else{
								$firstname = '';
							}
							
							if($cust_info['image'] <> '' && $cust_info['image'] <> NULL){
								$image = $this->model_tool_image->resize($cust_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							} else {
								$image = $this->model_tool_image->resize('data/demo/no-image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							}
						// get currency	
							$currency = $this->model_localisation_currency->getCurrencyByCountryId($addresses['country_id']);
				
							if($currency){
					
								if( $currency['symbol_left'] ){
									$symbol = $currency['symbol_left'];
								} else{
									$symbol = $currency['symbol_right'];
								}
								
								if ($currency['status']) {
										$title = $currency['title'];
										$code = $currency['code'];							
								}
							}
							$response = array(
							'Message'     => 'Success',
								'customer_id'     => $cust_info['customer_id'],
								'name'     => $cust_info['firstname'],
								'email'     => $cust_info['email'],
								'image'     => $image,
								'city'     => $city,
								'country'     => $country,
								'country_id'     => $addresses['country_id'],
								'phone'       => $cust_info['telephone'],
								'CurrencyTitle' => $title,
								'CurrencyCode' => $code,
								'CurrencySymbol' => $symbol
							);
					
				}else{
					$response["Message"] = 'Failure';
				}
				
}else{
					$response["Message"] = 'Failure';
				}
				$abc['info'] = $response;
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1);
}

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


public function isexits() { echo "kumar"; exit;
				$this->load->model('account/customer');
				
				$response = array();
				
				if (isset($this->request->get['email']) && isset($this->request->get['media']) ) {
					$email = $this->request->get['email'];
					
					$media = $this->request->get['media'];
					
					$data = array(
						'email' => $email,
						'media' => $media
					);
					
					$customer_info = array();
						$cust_info = $this->model_account_customer->getCustomerByEmail($data);  
					if($cust_info){ 
						$response = array(
							'Message' => 'Success',
							'CustomerId' => $cust_info['customer_id']
						);
					}else{
						$response = array(
							'Message' => 'user does not exits'
						);
					}
				}else{
					$response["Message"] = 'emial parameter is empty/null';
				}
				$test1= json_encode($response);
				echo $test2=str_replace('\/','/', $test1);


}



//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Update device id ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
public function updevice() {
				$this->load->model('account/customer');
				
				$response = array();
				
				$data = array();
				
				if (isset($this->request->get['uid']) && isset($this->request->get['deviceId'])) {
					$data['customer_id'] = $this->request->get['uid'];
					
					$data['deviceId'] = $this->request->get['deviceId'];
					
					$this->model_account_customer->editDeviceId($data);  

						$response = array(
							'Message' => 'Success'
						);
				}else{
					$response["Message"] = 'Failure';
				}
				echo $test1= json_encode($response);
}

//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show All countries ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function country() {
				$this->load->model('localisation/country');

				$countriesArr = array();
				
				$countArr = array();

				$countries = $this->model_localisation_country->getCountries();  
			if($countries){
				foreach ($countries as $country) { 
						//echo "<pre>"; print_r($country);
					$countArr[] = array(
						'country_id' =>$country['country_id'],
						'name' => $country['name'],
						'country_code'  => $country['country_code']
					);	
				}
				$countriesArr['Message'] = 'Success';
				$countriesArr['Countries'] = $countArr;
			}else{
				$countriesArr['Message'] = 'Failure';
			} 
				$abc['info'] = $countriesArr;
				$test1= json_encode($abc);
				echo $test2=str_replace('\/','/', $test1);
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//


//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show All currency ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function currency() {
				$this->load->model('localisation/currency');

				$currencies = array();
				
				$response = array();

				$results = $this->model_localisation_currency->getCurrencies();	
				if($results){
					foreach ($results as $result) {
					if( $result['symbol_left'] ){
						$symbol = $result['symbol_left'];
					} else{
						$symbol = $result['symbol_right'];
					}
					
						if ($result['status']) {
							$currencies[] = array(
								'title' => $result['title'],
								'code' => $result['code'],
								'symbol' => $symbol
							);
						}
					}
					$response['Message'] = 'Success';
					$response['Items'] = $currencies;
				}else{
					$response['Message'] = 'Failure';
				}
				 echo $test1= json_encode($response);
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//



//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//++++++++++++++++++++++++++++++++++++++++++++++++++ Show All currency ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//	
		public function ucurr() {
				$this->load->model('localisation/currency');
				
				$this->load->model('localisation/country');
				
				$this->load->model('account/address');

				$currencies = array();
				
				$response = array();
				
				if (isset($this->request->get['userid'])) {
				
				$userId = $this->request->get['userid'];
				
				$addresses = $this->model_account_address->getAddressesApp( $userId ); 
				
				foreach($addresses as $address){
					if($address['country']){
						$ucountry = $address['country'];
						$ucountryId = $address['country_id'];
					}
				}
				//echo "<pre>"; print_r($addresses);

				$result = $this->model_localisation_currency->getCurrencyByCountryId($ucountryId);
				
					if($result){
			
						if( $result['symbol_left'] ){
							$symbol = $result['symbol_left'];
						} else{
							$symbol = $result['symbol_right'];
						}
						
						$contr = $this->model_localisation_country->getCountry($ucountryId);
						
							if ($result['status']) {
								
									$title = $result['title'];
									$code = $result['code'];							
							}
				
						
						$response['Message'] = 'Success';
						$response['CountryName'] = $ucountry;
						$response['CountryId'] = $ucountryId;
						$response['CurrencyTitle'] = $title;
						$response['CurrencyCode'] = $code;
						$response['CurrencySymbol'] = $symbol;
					}else{
						$response['Message'] = 'Failure';
					}
				}else{
					$response['Message'] = 'Failure';
				}
				 echo $test1= json_encode($response);
		}
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
//*******************************************************************************************************************************************************************//
}
?>
