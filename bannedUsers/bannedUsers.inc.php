<?php

    class bannedUsers extends module {
        
        public $allowedMethods = array("bannedusers"=>array("type"=>"GET"));
		
		public $pageName = 'Banned Users';
        
        public function constructModule() {
			
			switch (@$this->methodData->bannedusers) {
				case 'rank': 
				default: 
					$order = 'U_userLevel'; 
					$banned = 0;
					$title = "Banned Users"; 
				break;
			}
            
			$select = $this->db->prepare("
				SELECT * FROM users WHERE ".$order." = $banned
			");
			$select->execute();
			
			$users = array();
			
			while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
				$username = new user($row['U_id']);
				$users[] = array(
					"name" => $username->info->U_name, 
					"banned" => $username->info->U_userLevel,
					"id" => $username->info->U_id
				); 
			}
			
            $this->html .= $this->page->buildElement('bannedUsers', array(
            	"title" => $title, 
            	"users" => $users
            ));
            
        }
        
    }

?>