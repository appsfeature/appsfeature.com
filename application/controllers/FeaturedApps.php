<?php

class FeaturedApps extends CI_Controller{

	private $itemShow = 5;
	public function __construct(){
		parent::__construct();
		$this->load->model('FeaturedAppsModel');
		$this->load->helper('url');
	}


	public function index($pageNo=1){
		$data["controller"]="FeaturedApps/"; 
		$data['itemShow'] = $this->itemShow;
		$data['appsList'] = $this->FeaturedAppsModel->getAppsList($pageNo,$this->itemShow);

		$result = $this->FeaturedAppsModel->getCountAppCategory();
		$appCount = $result[0]['count'];
		
		$data['pages'] = ceil($appCount/$this->itemShow);
		
		$this->load->view('apps/header');
		$this->load->view('apps/home',$data);
		$this->load->view('apps/footer');
		$this->load->view('apps/scripts/IndexScript',$data);
	}

	public function applicationList($pageNo=null)
	{
		$appList = $this->FeaturedAppsModel->getAppsList($pageNo,$this->itemShow);
		if($appList)
		{
			echo json_encode($appList);
		}
		else
		{
			echo "";
		}
	}


	public function details($appsName=Null){
		$this->load->helper('url');
		$this->load->model('FeaturedAppsModel');
		$data['appsDetails'] = $this->FeaturedAppsModel->getAppsDetails($appsName); 
		$data['appsScreens'] = $this->FeaturedAppsModel->getAppsScreens($data['appsDetails'][0]['app_id']);
		$data['apps'] = $this->FeaturedAppsModel->getAppsByAppId($data['appsDetails'][0]['app_id']);
		$this->load->view('apps/header');
		$this->load->view('apps/feature',$data);
		$this->load->view('apps/footer');

	}

	public function test(){
		echo "hello";
	}

	public function sendContactDetails()
	{
		if(isset($_POST['Name']) && isset($_POST['Email']) && isset($_POST['Subject']) && isset($_POST['Message']))
		{
			$data = Array(
				'Name' => $_POST['Name'],
				'Email' =>$_POST['Email'],
				'Subject' => $_POST['Subject'],
				'Message' => $_POST['Message']
			);

			$this->FeaturedAppsModel->contactDetails($data);
		}

		redirect('FeaturedApps');
	}
}

?>
