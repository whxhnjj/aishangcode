<?PHP
class mesqli
{
	private $queryNum;
	private $hostname;//服务器
	private $dbusername;//用户名
	private $dbpassword;//密码
	private $port=3306;//端口
	private $link_identifier;//连接标识符
	public $insert_id;
	public $affected_rows;
	public $error;
	public $errno;

	//构造函数
	public function __construct($hostname='',$dbusername='',$dbpassword='')
	{
		if ($hostname != '')
		{
		$this->hostname=$hostname;//服务器
		$this->dbusername=$dbusername;//用户名
		$this->dbpassword=$dbpassword;//密码
		$this->link_identifier = mysql_connect($this->hostname.':'.$this->port,$this->dbusername,$this->dbpassword) or dir("数据库连接失败".mysql_error());  //连接
		}
	}
	
	//connect函数
	public function connect($hostname,$dbusername,$dbpassword)
	{  
		$this->hostname=$hostname;//服务器
		$this->dbusername=$dbusername;//用户名
		$this->dbpassword=$dbpassword;//密码
		$this->link_identifier = mysql_connect($this->hostname.':'.$this->port,$this->dbusername,$this->dbpassword) or dir("数据库连接失败".mysql_error());  //连接
	}
	
	//select_db函数
	public function select_db($dbname)
	{
		mysql_select_db($dbname,$this->link_identifier) or die("选择数据库失败".mysql_error());
	}
	
	//query函数
	public function query($query)
	{
		$this->queryNum++;
		$return=mysql_query($query,$this->link_identifier);
		
		if(strpos(strtoupper($query),'INSERT')!==false) //取得上一步INSERT操作产生的 ID 
		{
			$this->insert_id = mysql_insert_id($this->link_identifier);
		}
		
		if(strpos(strtoupper($query),'INSERT')!==false || strpos(strtoupper($query),'DELETE')!==false ||strpos(strtoupper($query),'UPDATE')!==false) //取得上一步受影响的行数 
		{
			$this->affected_rows = mysql_affected_rows($this->link_identifier);
		}
		
		if($return===false) return false;
		elseif($return===true) return true;
		else //仅对 SELECT，SHOW，EXPLAIN 或 DESCRIBE 语句返回一个资源标识符
		{ 
			$resource_identifier=$return;
			return new ResultSet($resource_identifier);
		}
	}
 
	//error函数
	public function error()
	{
	$this->error = mysql_error($this->link_identifier);
	return mysql_error($this->link_identifier);
	}
	
	//error函数
	public function errno()
	{
	$this->errno = mysql_errno($this->link_identifier);
	return mysql_errno($this->link_identifier);
	}
 
	//close函数
	public function close()
	{
	mysql_close($this->link_identifier);
	}
}


//ResultSet类
class ResultSet
{
	public $resource;
	public $num_rows;

	//构造函数
	public function __construct($resource_identifier)
	{
		$this->resource=$resource_identifier;
		$this->num_rows = mysql_num_rows($this->resource);
	}
 
	//将释放所有与结果标识符 result 所关联的内存
	public function free_result()
	{
		mysql_free_result($this->resource);
	}
 
	public function fetch_assoc()
	{
		return mysql_fetch_assoc($this->resource);
	}
	
	public function fetch_row()
	{
		return mysql_fetch_row($this->resource);
	}

	public function fetch_array($array_type=MYSQL_BOTH)
	{
		return mysql_fetch_array($this->resource,$array_type);
	}
 
	public function free()
	{
		return mysql_free_result($this->resource);
	}
}
?>