<?php 
class ZOHOAPI{
	private $auth="af2e7a66d96f3fe1f53fa3ad399ec97f";
	private $scope="crmapi";

	public function updateRecord($moduleName,$arrRecords,$id){

		echo $xmlRecords=$this->XMLfy($arrRecords,$moduleName);


		//print_r($arrRecords);
		//die();
		$curl_url="https://crm.zoho.com/crm/private/json/".$moduleName."/updateRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&xmlData=".$xmlRecords."&id=".$id."&wfTrigger=true";

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;

	}

	public function getAllLeads(){
		$curl_url="https://crm.zoho.com/crm/private/json/Leads/getRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function getAllModules(){
		$curl_url="https://crm.zoho.com/crm/private/json/Info/getModules";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function getRecordById($moduleName,$recordId){
		$curl_url="https://crm.zoho.com/crm/private/json/".$moduleName."/getRecordById";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&id=".$recordId;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function insertRecord($moduleName,$arrRecords){

		$xmlRecords=$this->XMLfy($arrRecords,$moduleName);

		/*echo $xmlRecords;
		die();*/
		/*print_r($arrRecords);
		die();*/
		$curl_url="https://crm.zoho.com/crm/private/json/".$moduleName."/insertRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&wfTrigger=true&xmlData=".$xmlRecords;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function insertNotes($moduleName,$arrRecords){

		$xmlRecords=$this->XMLfy($arrRecords,"Notes");

		/*echo $xmlRecords;
		die();*/
		/*print_r($arrRecords);
		die();*/
		$curl_url="https://crm.zoho.com/crm/private/xml/Notes/insertRecords";
		$curl_post_fields="newFormat=1&authtoken=".$this->auth."&scope=".$this->scope."&xmlData=".$xmlRecords;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function getNotes($module,$recordId){
		$curl_url="https://crm.zoho.com/crm/private/xml/Notes/getRelatedRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&id=".$recordId."&parentModule=".$module;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function searchRecords($moduleName,$searchCriteria){
		
		$curl_url="https://crm.zoho.com/crm/private/json/".$moduleName."/searchRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&criteria=".$searchCriteria;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function getRelatedRecords($module,$parentModule,$id){

		$curl_url="https://crm.zoho.com/crm/private/json/".$module."/getRelatedRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&id=".$id."&parentModule=".$parentModule;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function getRecords($module){
		$curl_url="https://crm.zoho.com/crm/private/json/".$module."/getRecords";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function getsearchrecordsbypdc($module,$column,$value){
		$curl_url="https://crm.zoho.com/crm/private/json/".$module."/getSearchRecordsByPDC";
		$curl_post_fields="authtoken=".$this->auth."&scope=".$this->scope."&selectColumns=All&searchColumn=".$column."&searchValue=".$value;

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$curl_url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $curl_post_fields);
		$response=curl_exec($ch);
		curl_close($ch);
		return $response;
	}


	public function XMLfy ($arr, $openingBracket) {
        $xml = "<$openingBracket>";
        $no = 1;
        foreach ($arr as $a) {
            $xml .= "<row no=\"$no\">";
            foreach ($a as $key => $val) {
                $xml .= "<FL val=\"$key\"><![CDATA[" . trim($val) . "]]></FL>";
            }
            $xml .= "</row>";
            $no += 1;
        }
        $xml .= "</$openingBracket>";

        return $xml;
    }

}
?>
