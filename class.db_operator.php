 <?php
 require_once ("classes/KavenegarApi.php");
/**
 *
 */
class DB_Operator
{
  public $arr_fields;
  public $arr_values;
  public $tableName;
  public $fieldName;
  //public $id;
  public $type;
  //public $quantity;
  //public $db = "6230079035e386d19f30385055589947";
  //public $db = "6725239295eb88956762c01078412571";
  public $db;
  public $db2;
  public $sender;
  public $receptor;
  public $message;
  public $api;
  public $token1;
  private $sqlQuery;
  ///////////////////////
  public function __construct($db)
  {
    $this->db = $db;
    $g = new G();
    $g->loadClass('pmFunctions');
  }
  //////////////////////
  public function send_notify($db2, $receptor, $invoice = 0){
    $this->receptor = $receptor;
    $this->db2 = $db2;
    $this->sender = "1000596446";
    //$this->sender = "100047778";
    $this->api = new \Kavenegar\KavenegarApi("31574D6D425979756448564B646A48566F63566445666C52546152756C375553743858632F7477536B5A383D");
    if ($invoice == 0) {
      $this->sqlQuery = "SELECT `USR_PHONE` FROM `users` WHERE `USR_UID` = '".$this->receptor."'";
      $result = executeQuery($this->sqlQuery, $this->db2);
      $this->receptor = $result[1]['USR_PHONE'];
      //$this->token1 = $this->receptor;
      $this->message = "ضمن عرض خسته نباشید, شما در کارتابل همکاران داناک خود پیغام جدید دریافت کرده اید\n http://109.125.132.64 \n".$this->receptor;
      $this->token1 = "09134566646";
      //$this->api->Send($this->sender,$this->receptor,$this->message);
      $this->api->VerifyLookup($this->receptor,$this->token1, NULL, NULL, 'inboxChecking');
    }
    else {
      $this->sqlQuery = "SELECT `Serial_Number` FROM `invoice` WHERE `Id` = '".$invoice."'";
      $result = executeQuery($this->sqlQuery, $this->db);
      $serialNumber = $result[1]['Serial_Number'];
      //////////////////////////////
      $this->sqlQuery = "SELECT `Mobile` FROM `vendor` INNER JOIN `Invoice` ON invoice.Vendor = vendor.Id WHERE `invoice`.`Id` = '".$invoice."'";
      $result = executeQuery($this->sqlQuery, $this->db);
      $mobile = $result[1]['Mobile'];
      /////////////////
      $this->sqlQuery = "SELECT `USR_PHONE` FROM `users` WHERE `USR_UID` = '".$this->receptor."'";
      $result = executeQuery($this->sqlQuery, $this->db2);
      $this->receptor = $result[1]['USR_PHONE'];
      $this->message = "ضمن عرض وقت بخیر, فاکتور شماره ".$serialNumber."پرداخت گردید\n واحد امور مالی شرکت داناک";
      $this->api->VerifyLookup($this->receptor,$serialNumber, NULL, NULL, 'paymentChecking');
      /////
      $this->api->VerifyLookup($mobile, $serialNumber, NULL, NULL, 'paymentChecking');
    }
  }
  //////////////////////
  public function insert($tableName, $arr_fields, $arr_values){
    foreach ($arr_values as &$row) {
      mysqli_real_escape_string($row);
      //$row = preg_replace('/[^A-Za-z0-9\-]/', '', $row);
    }
    $this->sqlQuery = "INSERT INTO ".$tableName." (".implode(', ', $arr_fields).") VALUES(".implode(', ', $arr_values).")";
    executeQuery($this->sqlQuery, $this->db);
    $result = executeQuery("SELECT LAST_INSERT_ID()", $this->db);
    return $result[1]['LAST_INSERT_ID()'];
  }
  //////////////////////
  public function update($id, $tableName, $fieldName, $newValue)
  {
    $this->sqlQuery = "UPDATE `".$tableName."` SET `".$fieldName."` = '".$newValue."' WHERE `Id` = '".$id."'";
    executeQuery($this->sqlQuery, $this->db);
  }
  //////////////////////
  public function select($isGrid, $type, $arr_fields, $goal_field, $from, $joins, $where){
    $space1 = $space2 = " ";
    if ($type == '') {
      $space1 = '';
    }
    if ($joins == '') {
      $space2 = '';
    }
    $this->sqlQuery = "SELECT ".$type.$space1.implode(', ',$arr_fields)." FROM ".$from.$space2.$joins." WHERE ".$where;
    $this->item = executeQuery($this->sqlQuery, $this->db);
    if ($isGrid == 1) {
      return $this->item;
    }
    elseif ($isGrid == 0) {
      $result = $this->item[1][$goal_field];
      return $result;
    }
  }
  //////////////////////
  public function insert_case_log($application, $date, $time1, $time2, $activity, $resource)
  {
    //$application = $this->get_application();
    $this->sqlQuery = "INSERT INTO `case_log`(`Id`, `Application`, `Date`, `Time_1`, `Time_2`, `Activity`, `Resource`) VALUES(NULL, '$application', '$date', '$time1', '$time2', '$activity', '$resource')";
    executeQuery($this->sqlQuery, $this->db);
    $result = executeQuery("SELECT LAST_INSERT_ID()", $this->db);
    return $result[1]['LAST_INSERT_ID()'];
  }
  ////////////////////////
  public function update_case_log_Time2($caseLogId, $time2)
  {
    $this->sqlQuery = "UPDATE `case_log` SET `Time_2` = '".$time2."' WHERE `Id` = '".$caseLogId."'";
    executeQuery($this->sqlQuery, $this->db);
  }
  //////////////////////
  /*public function get_supplier_Dsp()
  {
    $this->sqlQuery = "SELECT CONCAT(USR_FIRSTNAME, ' ', USR_LASTNAME) AS `Purchase_Receipt_Form_supplier` FROM `purchase_receipt_form` INNER JOIN `bitnami_pm`.`users` ON `Supplier` = `USR_UID` WHERE `purchase_receipt_form`.`Id` ='".$this->id."'";
    $result = executeQuery($this->sqlQuery, $this->db);
    $this->supplier_Dsp = $result[1]['Purchase_Receipt_Form_supplier'];
    return $this->supplier_Dsp;
  }*/
  /*
  require_once "classes/class.db.php";

$db = "87019998360277e34b8b149051374029";
@&dbOperator = new DB_Operator($db);

//$tableName = "`pmt_article_unit`";
//$arr_fields = array('`UNIT`');
//$arr_values = array("'aasaassswli'");

// (FOR NULL ID) $arr_fields = array('`ID`', '`UNIT`');
//(FOR NULL ID) $arr_values = array(12, "'aasaassswli'");

//@&dbOperator->insert($tableName, $arr_fields, $arr_values);

////////////////////
//$arr_fields = array('`pmt_article_unit`.`ID` AS `ID`', '`pmt_article_unit`.`UNIT` AS `UNIT`');
//@=unit = @&dbOperator->select(1, '', $arr_fields, '', '`pmt_article_unit`', '', '`pmt_article_unit`.`ID` = 41');
////////////////
//$arr_fields = array('`pmt_article_unit`.`ID` AS `ID`', '`pmt_article_unit`.`UNIT` AS `UNIT`');
//@=unit = @&dbOperator->select(1, '', $arr_fields, '', '`pmt_article_unit`', '', '1');
////////////////
//@=name = @&dbOperator->get(41, 'pmt_article_unit', 'UNIT');
////////////////
//$arr_fields = array('`pmt_article_unit`.`ID` AS `ID`', '`pmt_article_unit`.`UNIT` AS `UNIT`');
//@=name = @&dbOperator->select(0, '', $arr_fields, 'UNIT', '`pmt_article_unit`', '', '1');
////////////////
//$arr_fields = array('`pmt_article`.`NAME` AS `ID`', '`pmt_article_unit`.`UNIT` AS `UNIT`');
//@=unit = @&dbOperator->select(1, '', $arr_fields, '', '`pmt_article`', 'INNER JOIN `pmt_article_unit` ON `pmt_article`.`UNIT` = `pmt_article_unit`.`ID`', '1');
////////////////
//@&dbOperator->update(22, 'pmt_article_unit', 'UNIT', 'سیشسیasda');
  */
}
 ?>
