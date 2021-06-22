<?php 

Class Api Extends Tools{
	
	protected $conn;
	private $table = "profile";
	public $errorMsg;
	
	
	/*
	 pass database PDO connection
	 */
	function __construct($con) {
		$this->conn = $con;
	}
	
	
	public function execute($request_uri,$params){
		
		$data = explode("/",$request_uri);
		$endpoint = $data[2];
		$id = $data[3];

		
		//if endpoint is allowed execute
		$result = array();
		 
		#$this->log(print_r($params,true),$endpoint);
		
		
		if( $this->is_register_function($endpoint)){
		
			$result = $this->{$endpoint}($endpoint,$params,$id);
			
		} else {
			//return false;
			$result = array(
					"status" => "Failed",
					"message" => "Endpoint not found"
			);
			
		}
		
		
		
		return $result;
		
		
	}
	
	
	/*
	 * function create or add new record
	 * 
	 */
	
	public function create($endpoint,$params){
		
		$validate_data = $this->validate('create',$params);
		unset($params['password']);
		unset($params['username']);
		
		$valid = $validate_data['valid'];
		$message = $validate_data['message'];
		
		//insert here
		if($valid){
			try {
				$now = date("Y-m-d H:i:s");
				
				$first_name = $this->clean($params['first_name']);
				$last_name= $this->clean($params['last_name']);
				$middle_name= $this->clean($params['middle_name']);
				$birth_date= $this->clean($params['birth_date']);
				#$birth_date = $now;
				$gender= $this->clean($params['gender']);
				$date_created= $now;
				$date_updated= $now;
				
				$stmt = $this->conn->prepare("INSERT INTO {$this->table} (first_name, last_name, middle_name, birth_date,gender,date_created,date_update) VALUES(?,?,?,?,?,?,?)");
			
				try {
					$this->conn->beginTransaction();
					$stmt->execute( array($first_name, $last_name,$middle_name,$birth_date,$gender,$date_created,$date_updated) );
					
					$this->conn->commit();
					$insert_id = $this->conn->lastInsertId();
					$result = array("status"=>"Success","message"=>"Record created");
				} catch(PDOException $e) {
					$this->conn->rollback();
					$result = array("status"=>"Failed","message"=>$e->getMessage() );
				}
			} catch( PDOException $e ) {
				$result = array("status"=>"Failed","message"=>$e->getMessage() );
			}
		} else {
			$result = array("status"=>"Failed","message"=>$message);
		}
		return $result;
	}
	
	/*
	 this function will show specific record based on id 
	 */
	public function read($endpoint,$params,$id=0){
		$id = $this->clean($id);
		$validate_data = $this->validate($endpoint,$params,$id);
		unset($params['password']);
		unset($params['username']);
		
		$valid = $validate_data['valid'];
		$message = $validate_data['message'];
		
		
		$data = array();
		
		if($valid){
			try {
				$sql = "SELECT * FROM $this->table WHERE id='$id' LIMIT 1";
				$rs =  $this->conn->query($sql);
				$data = array();
				while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
				
				if(count($data)){
					$result = array("status"=>"Success","data"=>$data);
					
				} else {
					$result = array("status"=>"Failed","message"=>"No data found");
					
				}
				
				
			} catch( PDOException $e ) {
				
				#print "Error!: " . $e->getMessage() . "</br>";
				$result = array("status"=>"Failed","message"=>$e->getMessage() );
				
			}
			
		} else {
			$result = array("status"=>"Failed","message"=>$message);
			
		}
			
	
		return $result;
		
	}
	
	/*
	 * this function will update the record based on the params and id you pass
	 * 
	 */
	public function update($endpoint,$params,$id){
		
		
		$id = $this->clean($id);
		
		#validate if fields on params in included in database field
		$validate_data = $this->validate($endpoint,$params,$id);
		unset($params['password']);
		unset($params['username']);
		
		$valid = $validate_data['valid'];
		$message = $validate_data['message'];
		
		if($valid){
			try {
				
				
				$now = date("Y-m-d H:i:s");

				$sql = "UPDATE $this->table SET ";
				
				$fields = array();
				$values = array();
				foreach ($params as $field => $value) {
					$values[] = $this->clean($value);
					$sql.="{$field}=?, ";
				}
			
				$values[] = $now;
				$sql.="date_update=? WHERE id=? ";
				$values[] = $id;
			
				$stmt = $this->conn->prepare($sql);
				
				try {
					$this->conn->beginTransaction();
					$stmt->execute($values);
					$this->conn->commit();
					$affected = $stmt->rowCount();
					if($affected){
						$result = array("status"=>"Success","message"=>"Affected rows ".$affected,"id"=>$id);
						
					} else {
						$result = array("status"=>"Failed","message"=>"Unable to Update","id"=>$id);
						
					}
					
				} catch(PDOException $e) {
					$this->conn->rollback();
					$result = array("status"=>"Failed","message"=>$e->getMessage() );
				}
				
				
				
			} catch( PDOException $e ) {
				$result = array("status"=>"Failed","message"=>$e->getMessage() );
			}
		} else {
			$result = array("status"=>"Failed","message"=>$message);
		}
		
		return $result;
	}
	
	/*
	 * delete specific record base on id
	 * 
	 */
	public function delete($endpoint,$params,$id=0){

		$validate_data = $this->validate($endpoint,$params,$id);
		unset($params['password']);
		unset($params['username']);
		
		$valid = $validate_data['valid'];
		$message = $validate_data['message'];
		
		if($valid){
			try {
				
				$id = $this->clean($id);
				
				$sql = "DELETE FROM $this->table WHERE id=? ";
				
				$stmt = $this->conn->prepare($sql);
				
				try {
					$this->conn->beginTransaction();
					$stmt->execute(array($id));
					$this->conn->commit();
					$delete_ct = $stmt->rowCount();
					
					if($delete_ct){
						$result = array("status"=>"Success","message"=>"Delete Success","id"=>$id);
						
					} else {
						$result = array("status"=>"Failed","message"=>"Unable to Delete","id"=>$id);
						
					}
					
				} catch(PDOException $e) {
					$this->conn->rollback();
					$result = array("status"=>"Failed","message"=>$e->getMessage() );
				}
				
				
				
			} catch( PDOException $e ) {
				$result = array("status"=>"Failed","message"=>$e->getMessage() );
			}
		} else {
			$result = array("status"=>"Failed","message"=>$message);
		}
		
		return $result;
		
	}
	
	
	/*
	 * 
	 * search item base on params
	 * if not parameters pass it will show all existing record
	 */
	
	public function search($endpoint,$params){
		
		#Throw New Exception("Test lang");
		$data = array();
		$validate_data = $this->validate($endpoint,$params);
		unset($params['password']);
		unset($params['username']);
		
		$valid = $validate_data['valid'];
		$message = $validate_data['message'];
		
		if($valid){
			
			
			try {
				
				$adn = "";
				if(count($params)>0){
					$adn = "WHERE ";
				}
				
				foreach ($params as $field => $value) {
					$value = $this->clean($value);
					$field= $this->clean($field);
					
					//strtolower and lower to for case insensetive
					$value = strtolower($value);
					
					$adn.=" LOWER($field) LIKE '%$value%' "; 
					
				}
				
				
				
				$sql = "SELECT * FROM $this->table $adn ";
				$rs =  $this->conn->query($sql);
				$data = array();
				while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}
				
				if(count($data)){
					$result = array("status"=>"Success","data"=>$data);
					
				} else {
					$result = array("status"=>"Failed","message"=>"No record found");
				}
				
				
			} catch( PDOException $e ) {
				
				#print "Error!: " . $e->getMessage() . "</br>";
				$result = array("status"=>"Failed","message"=>$e->getMessage() );
				
			}
			
			
		} else {
			$result = array("status"=>"Failed","message"=>$message );
			
		}
		
	
		
		return $result;
  		
  	}
	
	
	
	
	/*
	
	validate api credentials
	
	 */
	
  	protected function validate_api_login($username,$password){
  		
  		
  		
  		$data = array();
  		$valid = false;
  		 
  		try {
  			$username= $this->clean($username);
  			$password= $this->clean($password);
  			
  			$password_encrypted = md5($password);
  			
  			$sql = "SELECT * FROM api_user WHERE username='$username' AND password_encrypted='$password_encrypted'  LIMIT 1";
  			$rs =  $this->conn->query($sql);
  			$data = array();
  			while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
  				if($row['id']){
  					$data[] = $row;
  				}
  			}
  			
  			if(count($data)){
  				$valid= true;
  				
  			} else {
  				$valid= false;
  				
  			}
  			
  			
  		} catch( PDOException $e ) {
  			
  			#print "Error!: " . $e->getMessage() . "</br>";
  			$valid= false;
  			
  		}
  		
  		return $valid;
  		
  	}
	
	
	
	
	
	/*
	 check if endpoint is allowed to execute to a function
	 @params endpoint
	 */
	private function is_register_function($endpoint){
		
		$allowed_endpoint =array(
				"create",
				"read",
				"update",
				"delete",
				"search"
				
		);
		
		//check if method exist
		if(method_exists($this,$endpoint)){
			return in_array($endpoint,$allowed_endpoint);
			
		} else {
			 false;
		}
		
	}
	
	/*
	 * validation check if id exist in the record
	 * 
	 */
	protected function check_if_record_existing($id){
		
		$id = $this->clean($id);
		
		$sql = "SELECT * FROM $this->table WHERE id='$id' LIMIT 1";
		
		
		try {
			$rs =  $this->conn->query($sql);
			$data = array();
			while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
				if($row['id']){
					$data[] = $row;
				}
			}
			
			if(count($data)){
				$valid= true;
				
			} else {
				$valid= false;
				
			}
		} catch(PDOException $e) {
			$valid= false;
		}
		
		return $valid;
		
	}
	
	
	
	
	
}






?>