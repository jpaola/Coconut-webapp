<?php
	class Artist {

		private $con;
		private $id;

		// pass on the 'connection' to the construct
		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;
        }
        
        public function getId() {
            return $this->id;
        }

        public function getName() {
            $artistQuery = mysqli_query($this->con, "SELECT name FROM artists WHERE id = '$this->id'");
            $artist = mysqli_fetch_array($artistQuery);
            return $artist['name'];
		}

		public function getSongIds() {
            $query = mysqli_query($this->con, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");
            
            //create an array to store these..
            $array = array();

            while($row = mysqli_fetch_array($query)) {
                array_push($array, $row['id']); // this is the id column of the row you are currently on
            }
            return $array; // now return our list
        }
	}
?>