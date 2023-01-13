<?php 

class AppsModel extends CI_Model{

	public function AppsModel(){
		$this->load->database();
	}

	

//get Apps list
	public function getAppsList($pageNo,$limit2){ 
		if($pageNo==1)
		{
			$limit1 = 0;
			$limit2 = $pageNo*5;
		}
		$limit1 = ($pageNo-1)*5;
		
		$data=$this->db->query("SELECT * FROM apps_category ORDER BY priority ASC limit $limit1,$limit2")->result_array();
		return $data?$data:FALSE;
	}
//get Apps features
	public function getAppsDetails($appsName=null){  
		$appsName = $this->db->query("SELECT app_id FROM apps_category where app_package='$appsName'")->result_array()[0]['app_id'];
		$data = $this->db->query("SELECT * FROM app_category_feature where app_id=$appsName")->result_array();
		return $data?$data:FALSE;
	}
//get Apps screens
	public function getAppsScreens($appsId=null){  
		$data = $this->db->query("SELECT * FROM app_category_screens where app_id=$appsId")->result_array();
		return $data?$data:FALSE;
	}
//get app by id
	public function getAppsByAppId($appId){  
		$data=$this->db->query("SELECT * FROM apps_category where app_id='$appId'")->result_array();
		return $data?$data:FALSE;
	}
	public function totalItem(){
		$data=$this->db->query("SELECT count(*) as totalCategory FROM apps_category")->result_array();
		return $data?$data:FALSE;
	}

	public function getCountAppCategory()
	{
		$data = $this->db->query("SELECT count(app_id) as count FROM apps_category")->result_array();
		return $data?$data:FALSE;
	}

	public function contactDetails($data)
	{
		$this->db->insert('contact_us',$data);
		return $this->db->affected_rows() ? true : false;
	}
}  
?>