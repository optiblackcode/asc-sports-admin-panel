<?php
require_once __DIR__."/db.php";
class EXECUTION_LOGS extends DB
{
	public $log_id="";
	private $start_time="";
	private $end_time="";
	private $arrExtraColumns=[];

	// **************************** Start Log *******************************
	public function __construct()
	{
		parent::__construct();
		$this->start_time=date("Y-m-d H:i:s");

		$arrExecutionLog=[];
		$arrExecutionLog['file']=$_SERVER['PHP_SELF'];
		$arrExecutionLog['start_time']=$this->start_time;
		$arrExecutionLog['error']='Y';

		$rsltInsLog=$this->insertLog($arrExecutionLog);
		if($rsltInsLog)
		{
			$this->log_id=$rsltInsLog;
		}
		else
		{
			echo "Error in inserting log.";
		}
	}

	// **************************** end Log *******************************
	public function __destruct()
	{
		$this->end_time=date("Y-m-d H:i:s");

		$arrExecutionLog=[];
		$arrExecutionLog['end_time']=$this->end_time;
		$arrExecutionLog['duration']=strtotime($this->end_time)-strtotime($this->start_time);
		$arrExecutionLog['error']="N";

		$arrExecutionLog=array_merge($this->arrExtraColumns,$arrExecutionLog);

		$rsltUpdLog=$this->updateLog($this->log_id, $arrExecutionLog);
		if($rsltUpdLog)
		{

		}
		else
		{
			echo "Error in updating log.";
		}
	}

	// ***************** Function to insert extra values ***********************
	public function insertExtraColumns($arrExtraValues)
	{
		$this->arrExtraColumns=array_merge($this->arrExtraColumns,$arrExtraValues);
	}

	// ***************** Function to insert a new log ***********************
	public function insertLog($arrLogs)
	{
		$columns="";
		$values="";
		foreach ($arrLogs as $column_name => $column_value) 
		{
			$columns.=",".$column_name;
			$values.=",'".addslashes($column_value)."'";
		}
		$columns=trim($columns,",");
		$values=trim($values,",");

		// Built Actual query
		$qryIns="INSERT INTO execution_logs(".$columns.") ";
		$qryIns.="values(".$values.");";

		$rsltInsert=$this->conn->query($qryIns);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			$this->db_error($qryIns);
			return false;
		}
	}

	// *********************** Update log ******************************
	public function updateLog($id,$arrLogs)
	{
		$setClause="SET ";
		foreach ($arrLogs as $column_name => $column_value) 
		{
			$setClause.="{$column_name}='".addslashes($column_value)."',";
		}

		$setClause=trim($setClause,',');

		$qryUpd="UPDATE execution_logs ";
		$qryUpd.=$setClause;
		$qryUpd.=" WHERE rec_id='{$id}'";

		$rsltUpd=$this->conn->query($qryUpd);
		if($rsltUpd)
		{
			return $rsltUpd;
		}
		else
		{
			$this->db_error($qryUpd);
			return false;
		}
	}
}

?>