<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "filterinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$filter_delete = NULL; // Initialize page object first

class cfilter_delete extends cfilter {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'filter';

	// Page object name
	var $PageObjName = 'filter_delete';

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

		// Table object (filter)
		if (!isset($GLOBALS["filter"])) {
			$GLOBALS["filter"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["filter"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'filter', TRUE);

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
		$this->FilterID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("filterlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in filter class, filterinfo.php

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
		$this->FilterID->setDbValue($rs->fields('FilterID'));
		$this->Date1->setDbValue($rs->fields('Date1'));
		$this->Date2->setDbValue($rs->fields('Date2'));
		$this->Continent->setDbValue($rs->fields('Continent'));
		$this->Environment->setDbValue($rs->fields('Environment'));
		$this->Season->setDbValue($rs->fields('Season'));
		$this->Weather->setDbValue($rs->fields('Weather'));
		$this->Age->setDbValue($rs->fields('Age'));
		$this->Activities->setDbValue($rs->fields('Activities'));
		$this->ActivityPrice->setDbValue($rs->fields('ActivityPrice'));
		$this->Country->setDbValue($rs->fields('Country'));
		$this->City->setDbValue($rs->fields('City'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->FilterID->DbValue = $row['FilterID'];
		$this->Date1->DbValue = $row['Date1'];
		$this->Date2->DbValue = $row['Date2'];
		$this->Continent->DbValue = $row['Continent'];
		$this->Environment->DbValue = $row['Environment'];
		$this->Season->DbValue = $row['Season'];
		$this->Weather->DbValue = $row['Weather'];
		$this->Age->DbValue = $row['Age'];
		$this->Activities->DbValue = $row['Activities'];
		$this->ActivityPrice->DbValue = $row['ActivityPrice'];
		$this->Country->DbValue = $row['Country'];
		$this->City->DbValue = $row['City'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->ActivityPrice->FormValue == $this->ActivityPrice->CurrentValue && is_numeric(ew_StrToFloat($this->ActivityPrice->CurrentValue)))
			$this->ActivityPrice->CurrentValue = ew_StrToFloat($this->ActivityPrice->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// FilterID
		// Date1
		// Date2
		// Continent
		// Environment
		// Season
		// Weather
		// Age
		// Activities
		// ActivityPrice
		// Country
		// City

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// FilterID
			$this->FilterID->ViewValue = $this->FilterID->CurrentValue;
			$this->FilterID->ViewCustomAttributes = "";

			// Date1
			$this->Date1->ViewValue = $this->Date1->CurrentValue;
			$this->Date1->ViewValue = ew_FormatDateTime($this->Date1->ViewValue, 5);
			$this->Date1->ViewCustomAttributes = "";

			// Date2
			$this->Date2->ViewValue = $this->Date2->CurrentValue;
			$this->Date2->ViewValue = ew_FormatDateTime($this->Date2->ViewValue, 5);
			$this->Date2->ViewCustomAttributes = "";

			// Continent
			if (strval($this->Continent->CurrentValue) <> "") {
				switch ($this->Continent->CurrentValue) {
					case $this->Continent->FldTagValue(1):
						$this->Continent->ViewValue = $this->Continent->FldTagCaption(1) <> "" ? $this->Continent->FldTagCaption(1) : $this->Continent->CurrentValue;
						break;
					case $this->Continent->FldTagValue(2):
						$this->Continent->ViewValue = $this->Continent->FldTagCaption(2) <> "" ? $this->Continent->FldTagCaption(2) : $this->Continent->CurrentValue;
						break;
					case $this->Continent->FldTagValue(3):
						$this->Continent->ViewValue = $this->Continent->FldTagCaption(3) <> "" ? $this->Continent->FldTagCaption(3) : $this->Continent->CurrentValue;
						break;
					case $this->Continent->FldTagValue(4):
						$this->Continent->ViewValue = $this->Continent->FldTagCaption(4) <> "" ? $this->Continent->FldTagCaption(4) : $this->Continent->CurrentValue;
						break;
					case $this->Continent->FldTagValue(5):
						$this->Continent->ViewValue = $this->Continent->FldTagCaption(5) <> "" ? $this->Continent->FldTagCaption(5) : $this->Continent->CurrentValue;
						break;
					case $this->Continent->FldTagValue(6):
						$this->Continent->ViewValue = $this->Continent->FldTagCaption(6) <> "" ? $this->Continent->FldTagCaption(6) : $this->Continent->CurrentValue;
						break;
					default:
						$this->Continent->ViewValue = $this->Continent->CurrentValue;
				}
			} else {
				$this->Continent->ViewValue = NULL;
			}
			$this->Continent->ViewCustomAttributes = "";

			// Season
			if (strval($this->Season->CurrentValue) <> "") {
				switch ($this->Season->CurrentValue) {
					case $this->Season->FldTagValue(1):
						$this->Season->ViewValue = $this->Season->FldTagCaption(1) <> "" ? $this->Season->FldTagCaption(1) : $this->Season->CurrentValue;
						break;
					case $this->Season->FldTagValue(2):
						$this->Season->ViewValue = $this->Season->FldTagCaption(2) <> "" ? $this->Season->FldTagCaption(2) : $this->Season->CurrentValue;
						break;
					case $this->Season->FldTagValue(3):
						$this->Season->ViewValue = $this->Season->FldTagCaption(3) <> "" ? $this->Season->FldTagCaption(3) : $this->Season->CurrentValue;
						break;
					case $this->Season->FldTagValue(4):
						$this->Season->ViewValue = $this->Season->FldTagCaption(4) <> "" ? $this->Season->FldTagCaption(4) : $this->Season->CurrentValue;
						break;
					default:
						$this->Season->ViewValue = $this->Season->CurrentValue;
				}
			} else {
				$this->Season->ViewValue = NULL;
			}
			$this->Season->ViewCustomAttributes = "";

			// Age
			if (strval($this->Age->CurrentValue) <> "") {
				switch ($this->Age->CurrentValue) {
					case $this->Age->FldTagValue(1):
						$this->Age->ViewValue = $this->Age->FldTagCaption(1) <> "" ? $this->Age->FldTagCaption(1) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(2):
						$this->Age->ViewValue = $this->Age->FldTagCaption(2) <> "" ? $this->Age->FldTagCaption(2) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(3):
						$this->Age->ViewValue = $this->Age->FldTagCaption(3) <> "" ? $this->Age->FldTagCaption(3) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(4):
						$this->Age->ViewValue = $this->Age->FldTagCaption(4) <> "" ? $this->Age->FldTagCaption(4) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(5):
						$this->Age->ViewValue = $this->Age->FldTagCaption(5) <> "" ? $this->Age->FldTagCaption(5) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(6):
						$this->Age->ViewValue = $this->Age->FldTagCaption(6) <> "" ? $this->Age->FldTagCaption(6) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(7):
						$this->Age->ViewValue = $this->Age->FldTagCaption(7) <> "" ? $this->Age->FldTagCaption(7) : $this->Age->CurrentValue;
						break;
					case $this->Age->FldTagValue(8):
						$this->Age->ViewValue = $this->Age->FldTagCaption(8) <> "" ? $this->Age->FldTagCaption(8) : $this->Age->CurrentValue;
						break;
					default:
						$this->Age->ViewValue = $this->Age->CurrentValue;
				}
			} else {
				$this->Age->ViewValue = NULL;
			}
			$this->Age->ViewCustomAttributes = "";

			// ActivityPrice
			$this->ActivityPrice->ViewValue = $this->ActivityPrice->CurrentValue;
			$this->ActivityPrice->ViewCustomAttributes = "";

			// Country
			$this->Country->ViewValue = $this->Country->CurrentValue;
			$this->Country->ViewCustomAttributes = "";

			// City
			$this->City->ViewValue = $this->City->CurrentValue;
			$this->City->ViewCustomAttributes = "";

			// FilterID
			$this->FilterID->LinkCustomAttributes = "";
			$this->FilterID->HrefValue = "";
			$this->FilterID->TooltipValue = "";

			// Date1
			$this->Date1->LinkCustomAttributes = "";
			$this->Date1->HrefValue = "";
			$this->Date1->TooltipValue = "";

			// Date2
			$this->Date2->LinkCustomAttributes = "";
			$this->Date2->HrefValue = "";
			$this->Date2->TooltipValue = "";

			// Continent
			$this->Continent->LinkCustomAttributes = "";
			$this->Continent->HrefValue = "";
			$this->Continent->TooltipValue = "";

			// Season
			$this->Season->LinkCustomAttributes = "";
			$this->Season->HrefValue = "";
			$this->Season->TooltipValue = "";

			// Age
			$this->Age->LinkCustomAttributes = "";
			$this->Age->HrefValue = "";
			$this->Age->TooltipValue = "";

			// ActivityPrice
			$this->ActivityPrice->LinkCustomAttributes = "";
			$this->ActivityPrice->HrefValue = "";
			$this->ActivityPrice->TooltipValue = "";

			// Country
			$this->Country->LinkCustomAttributes = "";
			$this->Country->HrefValue = "";
			$this->Country->TooltipValue = "";

			// City
			$this->City->LinkCustomAttributes = "";
			$this->City->HrefValue = "";
			$this->City->TooltipValue = "";
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
				$sThisKey .= $row['FilterID'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "filterlist.php", $this->TableVar);
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
if (!isset($filter_delete)) $filter_delete = new cfilter_delete();

// Page init
$filter_delete->Page_Init();

// Page main
$filter_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$filter_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var filter_delete = new ew_Page("filter_delete");
filter_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = filter_delete.PageID; // For backward compatibility

// Form object
var ffilterdelete = new ew_Form("ffilterdelete");

// Form_CustomValidate event
ffilterdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffilterdelete.ValidateRequired = true;
<?php } else { ?>
ffilterdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($filter_delete->Recordset = $filter_delete->LoadRecordset())
	$filter_deleteTotalRecs = $filter_delete->Recordset->RecordCount(); // Get record count
if ($filter_deleteTotalRecs <= 0) { // No record found, exit
	if ($filter_delete->Recordset)
		$filter_delete->Recordset->Close();
	$filter_delete->Page_Terminate("filterlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $filter_delete->ShowPageHeader(); ?>
<?php
$filter_delete->ShowMessage();
?>
<form name="ffilterdelete" id="ffilterdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="filter">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($filter_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_filterdelete" class="ewTable ewTableSeparate">
<?php echo $filter->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($filter->FilterID->Visible) { // FilterID ?>
		<td><span id="elh_filter_FilterID" class="filter_FilterID"><?php echo $filter->FilterID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->Date1->Visible) { // Date1 ?>
		<td><span id="elh_filter_Date1" class="filter_Date1"><?php echo $filter->Date1->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->Date2->Visible) { // Date2 ?>
		<td><span id="elh_filter_Date2" class="filter_Date2"><?php echo $filter->Date2->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->Continent->Visible) { // Continent ?>
		<td><span id="elh_filter_Continent" class="filter_Continent"><?php echo $filter->Continent->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->Season->Visible) { // Season ?>
		<td><span id="elh_filter_Season" class="filter_Season"><?php echo $filter->Season->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->Age->Visible) { // Age ?>
		<td><span id="elh_filter_Age" class="filter_Age"><?php echo $filter->Age->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->ActivityPrice->Visible) { // ActivityPrice ?>
		<td><span id="elh_filter_ActivityPrice" class="filter_ActivityPrice"><?php echo $filter->ActivityPrice->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->Country->Visible) { // Country ?>
		<td><span id="elh_filter_Country" class="filter_Country"><?php echo $filter->Country->FldCaption() ?></span></td>
<?php } ?>
<?php if ($filter->City->Visible) { // City ?>
		<td><span id="elh_filter_City" class="filter_City"><?php echo $filter->City->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$filter_delete->RecCnt = 0;
$i = 0;
while (!$filter_delete->Recordset->EOF) {
	$filter_delete->RecCnt++;
	$filter_delete->RowCnt++;

	// Set row properties
	$filter->ResetAttrs();
	$filter->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$filter_delete->LoadRowValues($filter_delete->Recordset);

	// Render row
	$filter_delete->RenderRow();
?>
	<tr<?php echo $filter->RowAttributes() ?>>
<?php if ($filter->FilterID->Visible) { // FilterID ?>
		<td<?php echo $filter->FilterID->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_FilterID" class="control-group filter_FilterID">
<span<?php echo $filter->FilterID->ViewAttributes() ?>>
<?php echo $filter->FilterID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->Date1->Visible) { // Date1 ?>
		<td<?php echo $filter->Date1->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_Date1" class="control-group filter_Date1">
<span<?php echo $filter->Date1->ViewAttributes() ?>>
<?php echo $filter->Date1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->Date2->Visible) { // Date2 ?>
		<td<?php echo $filter->Date2->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_Date2" class="control-group filter_Date2">
<span<?php echo $filter->Date2->ViewAttributes() ?>>
<?php echo $filter->Date2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->Continent->Visible) { // Continent ?>
		<td<?php echo $filter->Continent->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_Continent" class="control-group filter_Continent">
<span<?php echo $filter->Continent->ViewAttributes() ?>>
<?php echo $filter->Continent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->Season->Visible) { // Season ?>
		<td<?php echo $filter->Season->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_Season" class="control-group filter_Season">
<span<?php echo $filter->Season->ViewAttributes() ?>>
<?php echo $filter->Season->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->Age->Visible) { // Age ?>
		<td<?php echo $filter->Age->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_Age" class="control-group filter_Age">
<span<?php echo $filter->Age->ViewAttributes() ?>>
<?php echo $filter->Age->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->ActivityPrice->Visible) { // ActivityPrice ?>
		<td<?php echo $filter->ActivityPrice->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_ActivityPrice" class="control-group filter_ActivityPrice">
<span<?php echo $filter->ActivityPrice->ViewAttributes() ?>>
<?php echo $filter->ActivityPrice->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->Country->Visible) { // Country ?>
		<td<?php echo $filter->Country->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_Country" class="control-group filter_Country">
<span<?php echo $filter->Country->ViewAttributes() ?>>
<?php echo $filter->Country->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($filter->City->Visible) { // City ?>
		<td<?php echo $filter->City->CellAttributes() ?>>
<span id="el<?php echo $filter_delete->RowCnt ?>_filter_City" class="control-group filter_City">
<span<?php echo $filter->City->ViewAttributes() ?>>
<?php echo $filter->City->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$filter_delete->Recordset->MoveNext();
}
$filter_delete->Recordset->Close();
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
ffilterdelete.Init();
</script>
<?php
$filter_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$filter_delete->Page_Terminate();
?>
