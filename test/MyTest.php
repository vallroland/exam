<?php 

Class MyTest{
	
	private $conn;
	private $api_username = "apimaster";
	private $api_password = "genesis";
	
	function __construct($con) {
		$this->conn = $con;
	}
	public function RunTest(){
		$class = new ReflectionClass( $this );

		
		foreach( $class->GetMethods() as $method ) {
			
			$methodname = $method->getName();
			
			if(substr($methodname,0,4)=="Test"){
			#	echo "\n$methodname";
				$result = $this->{$methodname}();			
				
				$this->flush_test($result);
			
			}
			 
		}
		
		
	}
	
	
	/*
	 * 
	 * test create or add record fut
	 * 
	 */
	
	private function TestCreate(){  
		
		$endpoint = "create";
		$params = array(
				"username" => $this->api_username,
				"password" => $this->api_password,
				"first_name" => "John",
				"middle_name" => "Test",
				"last_name" => "Doe",
				"birth_date" => "1992-01-11",
				"gender" => "Male"
				
		);
		
		$api = new Api($this->conn);
		$result = $api->create($endpoint,$params);
		$result['endpoint'] =  $endpoint;
		
		return $result;
	}
	
	
	/*
	 * 
	 * test update function
	 * 
	 */
	
	private function TestUpdate(){
		$endpoint = "update";
		$id = "18"; #id to update
		$params = array(
				"username" => $this->api_username,
				"password" => $this->api_password,
				"first_name" => "John Test"
		);
		
		$api = new Api($this->conn);
		$result = $api->update($endpoint,$params,$id);
		$result['endpoint'] =  $endpoint;
		return $result;
	
	}
	
	/*
	 * test read function
	 * 
	 */
	
	private function TestRead(){
		$endpoint = "read";
		$id = "18"; #id to update
		$params = array(
			"username" => $this->api_username,
			"password" => $this->api_password,
		);
		
		$api = new Api($this->conn);
		$result = $api->read($endpoint,$params,$id);
		$result['endpoint'] =  $endpoint;
		return $result;
		
	}
	/*
	 * test search function
	 */
	private function TestSearch(){
		
		$endpoint = "search";
		
		$params = array(
				"username" => $this->api_username,
				"password" => $this->api_password,
				"first_name" => "John",
				
		);
		
		$api = new Api($this->conn);
		$result = $api->search($endpoint,$params);
		$result['endpoint'] =  $endpoint;
		return $result;
	}
	
	/*
	 * 
	 * test DeleteFunction
	 */
	private function TestDelete(){
		$endpoint = "delete";
		$id = "17"; #id to delete
		
		$params = array(
			"username" => $this->api_username,
			"password" => $this->api_password,
		);
		
		$api = new Api($this->conn);
		$result = $api->delete($endpoint,$params,$id);
		$result['endpoint'] =  $endpoint;
		
		return $result;
	}
	
	 /*
	  * 
	  * function will flush result of test to browser
	  * 
	  */
	private function flush_test($result){
		 
		if (ob_get_level() == 0) ob_start();
		
		$status = $result['status'];
		$response = json_encode($result);
		$endpoint = $result['endpoint'];
		printf("%10s <br/>", "Test $endpoint: <br/>Was a $status <br/>Response: <br/>$response<br/>");
		echo "<hr>";
		echo "\n";
		ob_flush();
		flush();
		sleep(1);  
		
		ob_end_flush();
	}
	
	
}

?>