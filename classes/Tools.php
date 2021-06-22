<?php 

Class Tools {
	 
	
	/**
	 * this function will avoid sql injection
	 * @param string $string
	 * @return string
	 */
	
	public function clean($string){
		
		$string = trim($string);
		$string = addslashes($string);
		
		
		return $string;
	}
	
	/**
	  this function is to validate the parameters base on endpoint	 
	
	 */
	
	public function validate($endpoint,$params,$id=0){
		$valid = true;
		$err = "";
		
		$id = intval($id);

		$api_username = $params['username'];
		$api_password = $params['password'];
		unset($params['password']);
		unset($params['username']);

		$valid = $this->validate_api_login($api_username,$api_password);
		
		if($valid){
			
			if($endpoint=="create"){
				
				$required_params = array(
						"first_name",
						"last_name",
						"birth_date",
						"gender"
				);
				
				
				foreach ($required_params as $required_param_item) {
					
					if(empty($params[$required_param_item])){
						$valid = false;
						$err = "Missing Parameters $required_param_item";
						#Throw New Exception($err);
						break;
					}
				}
				
				
				
			}elseif($endpoint=="update"){
				
				if($id){
					
					$required_params = array(
							"first_name",
							"last_name",
							"middle_name",
							"birth_date",
							"gender"
					);
					
					
					
					foreach ($params as $field => $value) {
						
						if(!in_array($field,$required_params)){
							$valid = false;
							$err = "Error. Field \"$field\" not found.";
							#Throw New Exception($err);
							break;
							
						}
						
					}
					
					
					if(count($params)==0){
						$valid = false;
						$err = "Missing parameters";
					}
					
					
					$exist = $this->check_if_record_existing($id);
					
					if(!$exist){
						$valid = false;
						$err = "ID Does not exist in the record";
					}
					
					
					
				} else {
					$valid = false;
					$err = "Invalid Url Please. Id is required";
					#Throw New Exception($err);
					
				}
				
				
			}elseif($endpoint=="delete" || $endpoint=="read"){
				
				if($id){
					$exist = $this->check_if_record_existing($id);
					
					if(!$exist){
						$valid = false;
						$err = "ID Does not exist in the record";
					}
					
				} else {
					$valid = false;
					$err = "Invalid URL Request. Id is required";
					#Throw New Exception($err);
				}
				
				
				
			}elseif($endpoint=="search"){
				
				
				$required_params = array(
						"first_name",
						"last_name",
						"middle_name",
						"birth_date",
						"gender"
				);
				
				
				
				foreach ($params as $field => $value) {
					
					if(!in_array($field,$required_params)){
						$valid = false;
						$err = "Error. Field \"$field\" not found.";
						#Throw New Exception($err);
						break;
						
					}
					
				}
				
				
			}
			
		} else {
			
			$valid = false;
			$err = "Invalid Api Username or Password";
			
		}
		
		
		
		
		return array("valid"=>$valid,"message"=>$err);
	}
	

}


?>