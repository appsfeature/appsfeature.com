<?php 

class FeaturedGamesModel extends CI_Model{

	public function FeaturedGamesModel(){
		$this->load->database();
	}

//get Apps list
	public function getAppsList(){ 
		$data=$this->db->query("SELECT * FROM top_games_category ORDER BY priority ASC")->result_array();
		return $data?$data:FALSE;
	}

//get Apps features
	public function getAppsDetails($appsName=null){  
		$appsName = $this->db->query("SELECT app_id FROM top_games_category where app_package='$appsName'")->result_array()[0]['app_id'];
		$data = $this->db->query("SELECT * FROM top_games_cat_feature where app_id=$appsName")->result_array();
		return $data?$data:FALSE;
	}
//get Apps screens
	public function getAppsScreens($appsId=null){  
		$data = $this->db->query("SELECT * FROM top_games_cat_screens where app_id=$appsId")->result_array();
		return $data?$data:FALSE;
	}
//get app by id
	public function getAppsByAppId($appId){  
		$data=$this->db->query("SELECT * FROM top_games_category where app_id='$appId'")->result_array();
		return $data?$data:FALSE;
	}

	public function getCountAppCategory()
	{
		$data = $this->db->query("SELECT count(app_id) as count FROM top_games_category")->result_array();
		return $data?$data:FALSE;
	}

	public function contactDetails($data)
	{
		$this->db->insert('contact_us',$data);
		return $this->db->affected_rows() ? true : false;
	}

}  
?>