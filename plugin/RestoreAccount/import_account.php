<script language="javascript" type="text/javascript">
function submitImportForm() {
  document.getElementById('loader').style.display = 'block';
  document.getElementById('RestoreAccount__eventImportAccount').submit();
  document.getElementById('loader').style.display = '';
}
</script>
<div class="import_cont3" id="loader" style="display:none;"><img src="/images/ajax-loader1.gif" border="0" /></div>
<?php 
include_once("config.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

if($_SESSION['in_page_message'] != "") {
  echo '<div class="import_cont3"><b>'.$_SESSION['in_page_message'].'</b></div>';
}
$import_xml = new Event("RestoreAccount->eventImportAccount"); 
$import_xml->addParam("goto",$_SERVER["PHP_SELF"]);
$import_xml->setGotFile(true);
$import_xml->setSecure(true);

$htmlform = $import_xml->getFormHeader();
$htmlform .= $import_xml->getFormEvent();
$htmlform .= '<div class="import_cont3"><b>File Location: &nbsp; </b>';
$htmlform .= '<input type="file" name="fields[import_account]">';
//$htmlform .= $import_xml->getFormFooter("Import").'</div>';
$htmlform .= '<input type="button" name="button" value="Import" onclick="javascript:submitImportForm();"/>';
$htmlform .= '</form></div>';
$htmlform .= "\n";
echo $htmlform;
$_SESSION['in_page_message'] = "";
?>

