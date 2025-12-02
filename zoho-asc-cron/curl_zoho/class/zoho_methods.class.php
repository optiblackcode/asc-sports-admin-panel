<?php 
class ZOHO_METHODS
{
	private $accessToken;
	private $refreshToken;
	private $clientId="1000.YDORUT79ONLA08179XC7H2L2590Z4W";
	private $client_secret="307fe79164c27aa9142c175577d6387ba8827348b1";
	private $timeout=20000;
	public function checkTokens()
	{
		$accessToken_time= file_get_contents(__DIR__.'/../tokens/zoho_accesstoken.txt');
		if(empty($accessToken_time))
		{
			$txt="Access Token is empty.";
			$this->errorLog($txt);
			return false;
		}
		else
		{
			$arrAccessToken=explode("_#|#_",$accessToken_time);
			$accessToken=$arrAccessToken[0];
			$expireTime=$arrAccessToken[1];
			$currentTime=time();
			if($currentTime<$expireTime)
			{
				$this->accessToken=$accessToken;
				return true;
			}
			else
			{
				if($this->generateAccessTokenFromRefreshToken())
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	public function generateAccessTokenFromGrantToken($grantToken)
	{
		if($grantToken!="")
		{
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			$arrCurlOpt=array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
			    CURLOPT_POST => 1,
			    CURLOPT_POSTFIELDS => array(
			        'code' => $grantToken,
			        'redirect_uri' => 'http://31.220.55.121/zoho.php',
			        'client_id' => $this->clientId,
			        'client_secret' => $this->client_secret,
			        'grant_type' => 'authorization_code'
				)
			);
			$strCurlOpt=json_encode($arrCurlOpt);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt_array($curl, $arrCurlOpt);
			$resp = curl_exec($curl);
			curl_close($curl);
			$arrResponse=json_decode($resp,true);
			if(isset($arrResponse['error']))
			{
				$this->errorLog("Failed to generate access token from grant token.\tCurl : {$strCurlOpt}\tError : ".$resp);
				return false;
			}
			else
			{
				$accessToken=$arrResponse['access_token'];
				$refreshToken=$arrResponse['refresh_token'];

				$time=time();
				$time=$time+3500;
				$accessToken_time=$accessToken."_#|#_".$time;
				file_put_contents(__DIR__."/../tokens/zoho_accesstoken.txt",$accessToken_time);
				file_put_contents(__DIR__."/../tokens/zoho_refreshtoken.txt",$refreshToken);
				$txt="Access token generated from Grant token.";
				return true;
			}
		}
		else
		{
			$this->errorLog("Grant token not provided.");
			return false;
		}
	}
	public function generateAccessTokenFromRefreshToken()
	{
		$refreshToken=file_get_contents(__DIR__.'/../tokens/zoho_refreshtoken.txt');
		$refreshToken = "1000.3f2090f335af8de1799ffd43e20d3068.fd4a67e6b50ea0f62125a15239863f5f";
		if(empty($refreshToken))
		{
			$txt="Refresh Token is empty.";
			$this->errorLog($txt);
			return false;
		}
		else
		{
			$curl = curl_init();
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
			    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			    CURLOPT_POST => 1,
			    CURLOPT_TIMEOUT_MS=>$this->timeout,
			    CURLOPT_POSTFIELDS => array(
			        'refresh_token' => $refreshToken,
			        'client_id' => $this->clientId,
			        'client_secret' => $this->client_secret,
			        'grant_type' => 'refresh_token'
			    )
			));
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			$resp = curl_exec($curl);
			
			
			
			$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$curlError=curl_error($curl);
			curl_close($curl);
			$arrResponse=json_decode($resp,true);
			if(isset($arrResponse['access_token']))
			{
				$accessToken=$arrResponse['access_token'];
				$this->accessToken=$accessToken;
				$time=time();
				$time=$time+3500;
				$accessToken_time=$accessToken."_#|#_".$time;
				file_put_contents(__DIR__."/../tokens/zoho_accesstoken.txt",$accessToken_time);
				$txt="Access token generated from refresh token.\tStatus : ".$status."\tResponse : ".$resp;
				$this->activityLog($txt);
				return true;
			}
			else
			{
				$txt="Failed to generate access token from refresh token.\tStatus : ".$status."\tResponse : ".$resp;
				$txt.="\tCurl Error : ".$curlError;
				$this->errorLog($txt);

				if($status==0)
				{
					$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
					throw new Exception($exceptionMessage);
				}
				return false;
			}
		}
	}
	public function getRecordById($module,$id)
	{
		// ***************** Curl Request ************************
		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;
		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module."/".$id,
		    CURLOPT_POST => 0,
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);

		curl_setopt_array($curl, $arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		if($status==200)
		{
			
			$arrResponse=json_decode($resp,true);

			// ****************** Check response **********************
			if(isset($arrResponse['data']))
			{
				return $arrResponse;
			}
			else
			{
				$txt="Api request failed : getRecordById()\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{	
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : getRecordById()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		
		return false;
	}

	public function insertRecord($module,$arrParams,$arrTrigger=[])
	{
		$arrCrmLog=[];
		$success=true;
    	$arrCrmLog['module']=$module;	

		$arrData=[];
		$arrData['data']=$arrParams;
		$arrData['trigger']=$arrTrigger;
		$jsonData=json_encode($arrData);

		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;

		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module,
		    CURLOPT_POST => 1,
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_POSTFIELDS=>$jsonData,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl, $arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);  // Fire curl request
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);

		/*echo "Status : ".$status;
		print_r($resp);*/
		if($status==201 || $status==200 || $status==202)
		{
		
			$arrResponse=json_decode($resp,true);
			// ****************** Check response **********************
			if(isset($arrResponse['data']))
			{
				foreach ($arrResponse['data'] as $key => $value) 
				{
					if($value['code']=="SUCCESS")
					{

					}
					else
					{
						$success=false;
					}
				}

				if($success)
				{
					$arrCrmLog['action']="Inserted";
				}
				else
				{
					$arrCrmLog['action']="Insert-Failed";	
				}
				$arrCrmLog['json_input']=$jsonData;
				$arrCrmLog['json_response']=$resp;
				$this->crmLog($arrCrmLog,$success);
				return $arrResponse;
			}
			else
			{
				$strCurlOptions=json_encode($arrCurlOptions);
				$txt="Api request failed : insertRecord()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : insertRecord()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}
	public function updateRecord($module,$id,$arrParams,$arrTrigger=[])
	{
		$arrCrmLog=[];
		$success=true;
    	$arrCrmLog['module']=$module;
    	$arrCrmLog['id']=$id;

		$arrData=[];
		$arrData['data']=$arrParams;
		$arrData['trigger']=$arrTrigger;
		$jsonData=json_encode($arrData);

		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;

		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module.'/'.$id,
		    CURLOPT_CUSTOMREQUEST => "PUT",
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_POSTFIELDS=>$jsonData,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl, $arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);  // Fire curl request
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		
		if($status==201 || $status==200 || $status==202)
		{
			$arrResponse=json_decode($resp,true);
			// ****************** Check response **********************
			if(isset($arrResponse['data']))
			{
				foreach ($arrResponse['data'] as $key => $value) 
				{
					if($value['code']=="SUCCESS")
					{

					}
					else
					{
						$success=false;
					}
				}

				if($success)
				{
					$arrCrmLog['action']="Updated";
				}
				else
				{
					$arrCrmLog['action']="Update-Failed";	
				}
				$arrCrmLog['json_input']=$jsonData;
				$arrCrmLog['json_response']=$resp;
				$this->crmLog($arrCrmLog,$success);
				return $arrResponse;
			}
			else
			{
				$strCurlOptions=json_encode($arrCurlOptions);
				$txt="Api request failed : updateRecord()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : updateRecord()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}
	public function bulkUpdateRecords($module,$arrParams,$arrTrigger=[])
	{
		$arrCrmLog=[];
		$success=true;
    	$arrCrmLog['module']=$module;
    	

		$arrData=[];
		$arrData['data']=$arrParams;
		$arrData['trigger']=$arrTrigger;
		$jsonData=json_encode($arrData);

		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;

		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module,
		    CURLOPT_CUSTOMREQUEST => "PUT",
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_POSTFIELDS=>$jsonData,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl, $arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);  // Fire curl request
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		
		if($status==201 || $status==200 || $status==202)
		{
			$arrResponse=json_decode($resp,true);
			// ****************** Check response **********************
			if(isset($arrResponse['data']))
			{
				foreach ($arrResponse['data'] as $key => $value) 
				{
					if($value['code']=="SUCCESS")
					{

					}
					else
					{
						$success=false;
					}
				}

				if($success)
				{
					$arrCrmLog['action']="Updated";
				}
				else
				{
					$arrCrmLog['action']="Update-Failed";	
				}
				$arrCrmLog['json_input']=$jsonData;
				$arrCrmLog['json_response']=$resp;
				$this->crmLog($arrCrmLog,$success);
				return $arrResponse;
			}
			else
			{
				$strCurlOptions=json_encode($arrCurlOptions);
				$txt="Api request failed : bulkUpdateRecords()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : bulkUpdateRecords()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}
	public function deleteRecord($module,$id)
	{
		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;

		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module.'/'.$id,
		    CURLOPT_CUSTOMREQUEST => "DELETE",
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl, $arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);  // Fire curl request
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		
		if($status==201 || $status==200)
		{
			$arrResponse=json_decode($resp,true);
			// ****************** Check response **********************
			if(isset($arrResponse['data']))
			{
				return $arrResponse;
			}
			else
			{
				$txt="Api request failed : deleteRecord()\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : deleteRecord()\tStatus : ".$status."\tCurl : ".$strCurlOptions;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}
	public function getRecords($module,$arrParams=[])
	{
		$strParams=$this->makeUrl($arrParams);
		// ***************** Curl Request ************************
		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;
		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module."?".$strParams,
		    CURLOPT_POST => 0,
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl,$arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		$arrResponse=json_decode($resp,true);

		// ****************** Check response **********************
		if($status==200 || $status==204)
		{
			if(isset($arrResponse['data']) && $status==200)
			{
				return $arrResponse;
			}
			else if($status==204)
			{
				$arrResponse['data']=[];
				$arrResponse['info']=[];
				return $arrResponse;
			}
			else
			{
				$txt="Api request failed : getRecords()\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : getRecords()\tStatus : ".$status."\tCurl : ".$strCurlOptions;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}

	// https://www.zohoapis.com/crm/v2/{module_api_name}/{record_id}/{related_list_apiname}
	//https://www.zohoapis.com/crm/v2/Leads/410888000000698006/Notes
	public function getRelatedRecords($module,$id,$relatedList,$arrParams=[])
	{
		$strParams=$this->makeUrl($arrParams);
		// ***************** Curl Request ************************
		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;
		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module.'/'.$id.'/'.$relatedList,
		    CURLOPT_POST => 0,
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl,$arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		$arrResponse=json_decode($resp,true);

		// ****************** Check response **********************
		if($status==200 || $status==204)
		{
			if(isset($arrResponse['data']) && $status==200)
			{
				return $arrResponse;
			}
			else if($status==204)
			{
				$arrResponse['data']=[];
				$arrResponse['info']=[];
				return $arrResponse;
			}
			else
			{
				$strCurlOptions=json_encode($arrCurlOptions);
				$txt="Api request failed : getRelatedRecords()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : getRelatedRecords()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}

	/*
		- Available Parameters for search
		1. criteria= (({apiname}:{starts_with|equals}:{value}) and ({apiname}:{starts_with|equals}:{value}))
		2. email
		3. phone
		4. word
		5. page (Default is 1)
		6. per_page (Default is 200)
	*/
	public function searchRecords($module,$arrParams)
	{
		$strParams=$this->makeUrl($arrParams);
		// ***************** Curl Request ************************
		$curl = curl_init();
		$request_headers = array();
		$request_headers[] = 'Authorization: Zoho-oauthtoken '.$this->accessToken;
		$arrCurlOptions=array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/'.$module.'/search?'.$strParams,
		    CURLOPT_POST => 0,
		    CURLOPT_HTTPHEADER=> $request_headers,
		    CURLOPT_TIMEOUT_MS=>$this->timeout
		);
		curl_setopt_array($curl, $arrCurlOptions);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$resp = curl_exec($curl);
		$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError=curl_error($curl);
		curl_close($curl);
		$arrResponse=json_decode($resp,true);

		// ****************** Check response **********************
		if($status==200 || $status==204)
		{
			if(isset($arrResponse['data']) && $status==200)
			{
				return $arrResponse;
			}
			else if($status==204)
			{
				$arrResponse['data']=[];
				$arrResponse['info']=[];
				return $arrResponse;
			}
			else
			{
				$txt="Api request failed : searchRecords()\tResponse : ".$resp;
				$this->errorLog($txt);
			}
		}
		else
		{
			$strCurlOptions=json_encode($arrCurlOptions);
			$txt="Api request failed : searchRecords()\tStatus : ".$status."\tCurl : ".$strCurlOptions."\tResponse : ".$resp;
			$txt.="\tCurl Error : ".$curlError;
			$this->errorLog($txt);

			if($status==0)
			{
				$exceptionMessage="(Function : ".__FUNCTION__." - Curl Error : ".$curlError.")";
				throw new Exception($exceptionMessage);
			}
		}
		return false;
	}
	public function errorLog($txt)
	{
		$dateTime=date("Y-m-d H:i:s");
		// Write to error.log file
		$myfile = fopen(__DIR__."/../logs/error.log", "a");
		$txt = $dateTime."\t".$txt."\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}
	public function activityLog($txt)
	{
		$dateTime=date("Y-m-d H:i:s");
		// Write to error.log file
		$myfile = fopen(__DIR__."/../logs/activity_log.log", "a");
		$txt = $dateTime."\t".$txt."\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}
	public function makeUrl($arrParameters)
	{
		$arrTemp=[];
		foreach($arrParameters as $key=>$value)
		{
			$arrTemp[]=$key."=".rawurlencode($value);
		}
		$strUrl=implode("&",$arrTemp);
		return $strUrl;
	}
	function crmLog($arrValues,$status="success")
	{
		// Status
		if($status=="success")
		{
			$file="zoho_crm_success_log.log";
		}
		else
		{
			$file="zoho_crm_error_log.log";
		}
		
		$crm_log="";

		// Action
		if(isset($arrValues['action']))
		{
			$crm_log.=$arrValues['action']."\t";
		}
		else
		{
			$crm_log.="Blank\t";	
		}

		// Module
		if(isset($arrValues['module']))
		{
			$crm_log.=$arrValues['module']."\t";
		}
		else
		{
			$crm_log.="Blank\t";	
		}

		// Id
		if(isset($arrValues['id']))
		{
			$crm_log.=$arrValues['id']."\t";
		}
		else
		{
			$crm_log.="Blank\t";	
		}

		// Json input
		if(isset($arrValues['json_input']))
		{
			$crm_log.=$arrValues['json_input']."\t";
		}
		else
		{
			$crm_log.="Blank\t";	
		}

		// Response
		if(isset($arrValues['json_response']))
		{
			$crm_log.=$arrValues['json_response']."\t";
		}
		else
		{
			$crm_log.="Blank\t";	
		}
		// Status

		$dateTime=date("Y-m-d H:i:s");
		// Write to error.log file
		$myfile = fopen(__DIR__."/../logs/".$file, "a");
		$crm_log = $dateTime."\t".$crm_log."\n";
		fwrite($myfile, $crm_log);
		fclose($myfile);
	}
}
?>