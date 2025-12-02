<?php
class DB
{
	protected $conn;
	public function __construct()
	{
		$host="localhost";
		$username="RBUH9jPnna";
		$password="BYdxh5JIu!";
		$db="asc_datastudio_reportingnew";

		$this->conn=new mysqli($host,$username,$password,$db);
		if($this->conn->connect_error)
		{
			die("Error in DB connection.");	
		}
		
	}

	public function db_error($query)
	{
		$dateTime=date("Y-m-d H:i:s");
		$arrError=[];

		$arrBacktrace=debug_backtrace(0);
		$jsonBacktrace=json_encode($arrBacktrace);

		$jsonArguments="";
		if(isset($arrBacktrace[1]['args']))
		{
			$arrArguments=$arrBacktrace[1]['args'];
			$jsonArguments=json_encode($arrArguments);
		}

		$arrError['error_type']="DB";
		$arrError['error_no']=$this->conn->errno;
		$arrError['error']=$this->conn->error;
		$arrError['file']=$arrBacktrace[1]['file'];
		$arrError['line']=$arrBacktrace[1]['line'];
		$arrError['class']=$arrBacktrace[1]['class'];
		$arrError['function']=$arrBacktrace[1]['function'];
		$arrError['query']=$query;
		$arrError['arguments']=$jsonArguments;
		$arrError['backtrace']=$jsonBacktrace;
		$arrError['created_at']=$dateTime;

		$columns="";
		$values="";
		foreach ($arrError as $column_name => $column_value) 
		{
			$columns.=",".$column_name;
			$values.=",'".addslashes($column_value)."'";
		}
		$columns=trim($columns,",");
		$values=trim($values,",");

		// Built Actual query
		$qryIns="INSERT INTO error_log(".$columns.") ";
		$qryIns.="values(".$values.");";

		$rsltInsert=$this->conn->query($qryIns);
		if(!$rsltInsert)
		{
			// Log to the file
			$file=__DIR__."/logs/error_log.php";
			$flLogFile = fopen($file, "a");

			$content=json_encode($arrError);
			$content=$content."\n";

			fwrite($flLogFile, $content);
			fclose($flLogFile);
		}
		die("Stopped because of error.");
	}

	// A common insert function for all tables
	public function common_insert($table,$arrData)
	{	

		$columns="";
		$values="";
		foreach ($arrData as $column_name => $column_value) 
		{
			$column_value=trim($column_value);

			$columns.=",".$column_name;
			$values.=",'".addslashes($column_value)."'";
		}
		$columns=trim($columns,",");
		$values=trim($values,",");

		// Built Actual query
		$qryIns="INSERT INTO {$table}(".$columns.") ";
		$qryIns.="values(".$values.");";

		// Execute query
		$rsltInsert=$this->conn->query($qryIns);
		if(!$rsltInsert)
		{
			$this->db_error($qryIns);
		}
		return $rsltInsert;
	}

	// A common update function for all table
	public function common_update($table,$arrData,$whereClause,$arrExtraParameters=null)
	{	
		$setClause="SET ";
		foreach ($arrData as $column_name => $column_value) 
		{
			$column_value=trim($column_value);
			$setClause.="{$column_name}='".addslashes($column_value)."',";
		}

		$setClause=trim($setClause,',');

		$qryUpd="UPDATE {$table} ";
		$qryUpd.=$setClause;
		$qryUpd.="{$whereClause}";
		
		$rsltUpd=$this->conn->query($qryUpd);
		if($rsltUpd)
		{
			// if update successfull than log the changes in update_log table
			if($arrExtraParameters!=null)
			{
				$strChanges=print_r($arrExtraParameters['changes'],true);
				$arrUpdateLog=[];
				$arrUpdateLog['table_name']=$table;
				$arrUpdateLog['unique_id']=$arrExtraParameters['id'];
				$arrUpdateLog['execution_log_id']=$arrExtraParameters['execution_log_id'];
				$arrUpdateLog['changes']=$strChanges;
				$arrUpdateLog['created_at']=date("Y-m-d H:i:s");

				$this->common_insert('update_log',$arrUpdateLog);
			}
			return $rsltUpd;	
		}
		else
		{
			$this->db_error($qryUpd);
			return false;
		}
	}
	public function fetchTime(){
		$select = "SELECT * FROM is_partner_sample_data";
		$rsltselect=$this->conn->query($select);
		return $rsltselect;
	}
	public function error_log($log)
	{
		
	}
}
?>