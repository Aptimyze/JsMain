
<?php
class newjs_bharat extends TABLE
{
	public function __construct($dbname=""){
    		parent::__construct($dbname);
  	}

	public function select($name){

			$sql = "SELECT * from newjs.bharat where name = :name";
			
			$q = $this->db->prepare($sql);
			
			$q->execute([':name' => $name]);
			
			$q->setFetchMode(PDO::FETCH_ASSOC);
			
			while($r = $q->fetch()){
				$res[] = $r;
			}
			return $res;
		}

		public function delete($name){
			$sql = " DELETE from newjs.bharat where name = :name";
			$q = $this->db->prepare($sql);
			$q->execute([':name' => $name]);
			echo "Deleted Successfully";
		}

		public function insert($name,$email,$website,$comments,$gender){
			$sql = "INSERT INTO newjs.bharat (name,email,website,comments,gender) 
				values ('$name','$email','$website','$comments','$gender')";
			$this->db->exec($sql);
		}

}

?>
