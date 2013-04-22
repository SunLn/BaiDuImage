<?php
/*
 * Created on 2013-4
 *
 * A DBClass for BaiDuImage
 */
?>
<?php
   $BaiDuImage = new db();
   class db   {
	   var $db_host;
	   var $db_name;
	   var $db_user;
	   var $password;
	   var $sql;
	   var $result;
	   var $linkID;
	   function __construct(){
	   	   $this->sql = "";
	   	   $this->db_name="BaiDuImage";
		   $this->db_user="root";
		   $this->password="123";
		   $this->db_host="localhost";
		   $this->result = ""; 
		   $this->linkID=0;
		   $this->Db_Connect();
	   	
	   }
	   function db(){   
			$this->__construct();
	   }
	   function Db_Connect() {
			 $this->linkID=@mysql_connect($this->db_host,$this->db_user,$this->password); 	
			 if(!$this->linkID)
			 {
				 $this->DisplayError("连接失败");exit();
			 }
			 $this->Db_Select();
			 return true;
	   }
	   function Db_Select()	{
		   $select=mysql_select_db($this->db_name);
		   if(!$select)	 {
			  	$this->DisplayError("选择数据库失败");exit();

		   }		 
	   }
	   function Db_Query($sql){	
		   if($sql)  $this->sql=$sql;
		   //echo $this->sql;
		   if(!($this->result=mysql_query($this->sql,$this->linkID))) { 
		     $this->DisplayError("SQL无效");
		     return 0;
		   }else {
		      return $this->result;		 
		   }	
	   }
	   function Db_Fetch_Array(){		   	  
		    return mysql_fetch_array($this->result);
	   }
	   function Db_Fetch_Row(){		   	  
		    return mysql_fetch_row($this->result);
	   }
	   function Db_Fetch_All() {
		   $num=mysql_num_rows($this->result);
		   for($i=0;$i<$num;$i++)
		     $all[$i]=mysql_fetch_array($this->result);
		   return $all;
	   }

	   function Db_Fetch_One_Array($sql) {
           $this->result =  $this->Db_Query($sql);
           return $this->Db_Fetch_Array($this->result);
       }
     
	   function Db_Num_Rows() {	   
		   return mysql_num_rows($this->result);
	   }
	   //INSERT、UPDATE 、DELETE 
	   function Db_Affected_Rows(){
           return mysql_affected_rows();

       }
      function Db_Free_Result()  {
	      	if(!is_array($this->result)) return "";
			foreach($this->result as $kk => $vv)
			{
				if($vv) @mysql_free_result($vv);
			}
      }
      function IsTable($tbname)	{
		 $result = mysql_list_tables($this->db_name,$this->linkID);
		 while ($row = mysql_fetch_array($result)) {
			 if(strtolower($row[0])==strtolower($tbname)) {
				mysql_free_result($result);
				return true;
			}
		}
		mysql_free_result($result);
		return false;
	 }
      function Db_Insert_Id()  {
            if (!mysql_insert_id())  {
              $this->DisplayError("fail to get mysql_insert_id");
            }
            return mysql_insert_id();
      }
      function Db_Close() {
		  @mysql_close($this->linkID);
		  $this->Db_Free_Result();
	  }   
	  
	  function DisplayError($msg){
		echo "<html>\r\n";
		echo "<head>\r\n";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>\r\n";
		echo "<title>错误提示</title>\r\n";
		echo "</head>\r\n";
		echo "<body>\r\n<p style='line-helght:150%;font-size:10pt'>\r\n";
		echo $msg;
		echo "<br/><br/>";
		echo "</p>\r\n</body>\r\n";
		echo "</html>";
		//$this->Close();
		//exit();
		}
   }
   $BaiDuImage->Db_Query("set character set 'utf8'");//读库 
   $BaiDuImage->Db_Query("set names 'utf8'");//写库 
?>