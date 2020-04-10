<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "dialogue_rulesinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$dialogue_rules_delete = NULL; // Initialize page object first

class cdialogue_rules_delete extends cdialogue_rules {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'dialogue_rules';

	// Page object name
	var $PageObjName = 'dialogue_rules_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (dialogue_rules)
		if (!isset($GLOBALS["dialogue_rules"])) {
			$GLOBALS["dialogue_rules"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dialogue_rules"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dialogue_rules', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->DrID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("dialogue_ruleslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in dialogue_rules class, dialogue_rulesinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->DrTrigger->setDbValue($rs->fields('DrTrigger'));
		$this->DrResponse->setDbValue($rs->fields('DrResponse'));
		$this->DrType->setDbValue($rs->fields('DrType'));
		$this->DrValidationResponse->setDbValue($rs->fields('DrValidationResponse'));
		$this->DrStage->setDbValue($rs->fields('DrStage'));
		$this->DrID->setDbValue($rs->fields('DrID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->DrTrigger->DbValue = $row['DrTrigger'];
		$this->DrResponse->DbValue = $row['DrResponse'];
		$this->DrType->DbValue = $row['DrType'];
		$this->DrValidationResponse->DbValue = $row['DrValidationResponse'];
		$this->DrStage->DbValue = $row['DrStage'];
		$this->DrID->DbValue = $row['DrID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// DrTrigger
		// DrResponse
		// DrType
		// DrValidationResponse
		// DrStage
		// DrID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// DrType
			if (strval($this->DrType->CurrentValue) <> "") {
				switch ($this->DrType->CurrentValue) {
					case $this->DrType->FldTagValue(1):
						$this->DrType->ViewValue = $this->DrType->FldTagCaption(1) <> "" ? $this->DrType->FldTagCaption(1) : $this->DrType->CurrentValue;
						break;
					case $this->DrType->FldTagValue(2):
						$this->DrType->ViewValue = $this->DrType->FldTagCaption(2) <> "" ? $this->DrType->FldTagCaption(2) : $this->DrType->CurrentValue;
						break;
					case $this->DrType->FldTagValue(3):
						$this->DrType->ViewValue = $this->DrType->FldTagCaption(3) <> "" ? $this->DrType->FldTagCaption(3) : $this->DrType->CurrentValue;
						break;
					default:
						$this->DrType->ViewValue = $this->DrType->CurrentValue;
				}
			} else {
				$this->DrType->ViewValue = NULL;
			}
			$this->DrType->ViewCustomAttributes = "";

			// DrStage
			if (strval($this->DrStage->CurrentValue) <> "") {
				switch ($this->DrStage->CurrentValue) {
					case $this->DrStage->FldTagValue(1):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(1) <> "" ? $this->DrStage->FldTagCaption(1) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(2):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(2) <> "" ? $this->DrStage->FldTagCaption(2) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(3):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(3) <> "" ? $this->DrStage->FldTagCaption(3) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(4):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(4) <> "" ? $this->DrStage->FldTagCaption(4) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(5):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(5) <> "" ? $this->DrStage->FldTagCaption(5) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(6):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(6) <> "" ? $this->DrStage->FldTagCaption(6) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(7):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(7) <> "" ? $this->DrStage->FldTagCaption(7) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(8):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(8) <> "" ? $this->DrStage->FldTagCaption(8) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(9):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(9) <> "" ? $this->DrStage->FldTagCaption(9) : $this->DrStage->CurrentValue;
						break;
					case $this->DrStage->FldTagValue(10):
						$this->DrStage->ViewValue = $this->DrStage->FldTagCaption(10) <> "" ? $this->DrStage->FldTagCaption(10) : $this->DrStage->CurrentValue;
						break;
					default:
						$this->DrStage->ViewValue = $this->DrStage->CurrentValue;
				}
			} else {
				$this->DrStage->ViewValue = NULL;
			}
			$this->DrStage->ViewCustomAttributes = "";

			// DrID
			$this->DrID->ViewValue = $this->DrID->CurrentValue;
			$this->DrID->ViewCustomAttributes = "";

			// DrType
			$this->DrType->LinkCustomAttributes = "";
			$this->DrType->HrefValue = "";
			$this->DrType->TooltipValue = "";

			// DrStage
			$this->DrStage->LinkCustomAttributes = "";
			$this->DrStage->HrefValue = "";
			$this->DrStage->TooltipValue = "";

			// DrID
			$this->DrID->LinkCustomAttributes = "";
			$this->DrID->HrefValue = "";
			$this->DrID->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['DrID'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "dialogue_ruleslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($dialogue_rules_delete)) $dialogue_rules_delete = new cdialogue_rules_delete();

// Page init
$dialogue_rules_delete->Page_Init();

// Page main
$dialogue_rules_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dialogue_rules_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dialogue_rules_delete = new ew_Page("dialogue_rules_delete");
dialogue_rules_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = dialogue_rules_delete.PageID; // For backward compatibility

// Form object
var fdialogue_rulesdelete = new ew_Form("fdialogue_rulesdelete");

// Form_CustomValidate event
fdialogue_rulesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdialogue_rulesdelete.ValidateRequired = true;
<?php } else { ?>
fdialogue_rulesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($dialogue_rules_delete->Recordset = $dialogue_rules_delete->LoadRecordset())
	$dialogue_rules_deleteTotalRecs = $dialogue_rules_delete->Recordset->RecordCount(); // Get record count
if ($dialogue_rules_deleteTotalRecs <= 0) { // No record found, exit
	if ($dialogue_rules_delete->Recordset)
		$dialogue_rules_delete->Recordset->Close();
	$dialogue_rules_delete->Page_Terminate("dialogue_ruleslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $dialogue_rules_delete->ShowPageHeader(); ?>
<?php
$dialogue_rules_delete->ShowMessage();
?>
<form name="fdialogue_rulesdelete" id="fdialogue_rulesdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="dialogue_rules">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($dialogue_rules_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_dialogue_rulesdelete" class="ewTable ewTableSeparate">
<?php echo $dialogue_rules->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($dialogue_rules->DrType->Visible) { // DrType ?>
		<td><span id="elh_dialogue_rules_DrType" class="dialogue_rules_DrType"><?php echo $dialogue_rules->DrType->FldCaption() ?></span></td>
<?php } ?>
<?php if ($dialogue_rules->DrStage->Visible) { // DrStage ?>
		<td><span id="elh_dialogue_rules_DrStage" class="dialogue_rules_DrStage"><?php echo $dialogue_rules->DrStage->FldCaption() ?></span></td>
<?php } ?>
<?php if ($dialogue_rules->DrID->Visible) { // DrID ?>
		<td><span id="elh_dialogue_rules_DrID" class="dialogue_rules_DrID"><?php echo $dialogue_rules->DrID->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$dialogue_rules_delete->RecCnt = 0;
$i = 0;
while (!$dialogue_rules_delete->Recordset->EOF) {
	$dialogue_rules_delete->RecCnt++;
	$dialogue_rules_delete->RowCnt++;

	// Set row properties
	$dialogue_rules->ResetAttrs();
	$dialogue_rules->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$dialogue_rules_delete->LoadRowValues($dialogue_rules_delete->Recordset);

	// Render row
	$dialogue_rules_delete->RenderRow();
?>
	<tr<?php echo $dialogue_rules->RowAttributes() ?>>
<?php if ($dialogue_rules->DrType->Visible) { // DrType ?>
		<td<?php echo $dialogue_rules->DrType->CellAttributes() ?>>
<span id="el<?php echo $dialogue_rules_delete->RowCnt ?>_dialogue_rules_DrType" class="control-group dialogue_rules_DrType">
<span<?php echo $dialogue_rules->DrType->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dialogue_rules->DrStage->Visible) { // DrStage ?>
		<td<?php echo $dialogue_rules->DrStage->CellAttributes() ?>>
<span id="el<?php echo $dialogue_rules_delete->RowCnt ?>_dialogue_rules_DrStage" class="control-group dialogue_rules_DrStage">
<span<?php echo $dialogue_rules->DrStage->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrStage->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dialogue_rules->DrID->Visible) { // DrID ?>
		<td<?php echo $dialogue_rules->DrID->CellAttributes() ?>>
<span id="el<?php echo $dialogue_rules_delete->RowCnt ?>_dialogue_rules_DrID" class="control-group dialogue_rules_DrID">
<span<?php echo $dialogue_rules->DrID->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$dialogue_rules_delete->Recordset->MoveNext();
}
$dialogue_rules_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdialogue_rulesdelete.Init();
</script>
<?php
$dialogue_rules_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dialogue_rules_delete->Page_Terminate();
?>
