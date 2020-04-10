<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "flightinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$flight_delete = NULL; // Initialize page object first

class cflight_delete extends cflight {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'flight';

	// Page object name
	var $PageObjName = 'flight_delete';

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

		// Table object (flight)
		if (!isset($GLOBALS["flight"])) {
			$GLOBALS["flight"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["flight"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'flight', TRUE);

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
		$this->FlightID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("flightlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in flight class, flightinfo.php

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
		$this->FlightID->setDbValue($rs->fields('FlightID'));
		$this->DepartureAirport->setDbValue($rs->fields('DepartureAirport'));
		$this->ArrivalLocation->setDbValue($rs->fields('ArrivalLocation'));
		$this->DepartureTime->setDbValue($rs->fields('DepartureTime'));
		$this->ArrivalTime->setDbValue($rs->fields('ArrivalTime'));
		$this->DepartureDate->setDbValue($rs->fields('DepartureDate'));
		$this->ArrivalDate->setDbValue($rs->fields('ArrivalDate'));
		$this->Price->setDbValue($rs->fields('Price'));
		$this->Company->setDbValue($rs->fields('Company'));
		$this->Capacity->setDbValue($rs->fields('Capacity'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->FlightID->DbValue = $row['FlightID'];
		$this->DepartureAirport->DbValue = $row['DepartureAirport'];
		$this->ArrivalLocation->DbValue = $row['ArrivalLocation'];
		$this->DepartureTime->DbValue = $row['DepartureTime'];
		$this->ArrivalTime->DbValue = $row['ArrivalTime'];
		$this->DepartureDate->DbValue = $row['DepartureDate'];
		$this->ArrivalDate->DbValue = $row['ArrivalDate'];
		$this->Price->DbValue = $row['Price'];
		$this->Company->DbValue = $row['Company'];
		$this->Capacity->DbValue = $row['Capacity'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->Price->FormValue == $this->Price->CurrentValue && is_numeric(ew_StrToFloat($this->Price->CurrentValue)))
			$this->Price->CurrentValue = ew_StrToFloat($this->Price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// FlightID
		// DepartureAirport
		// ArrivalLocation
		// DepartureTime
		// ArrivalTime
		// DepartureDate
		// ArrivalDate
		// Price
		// Company
		// Capacity

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// FlightID
			$this->FlightID->ViewValue = $this->FlightID->CurrentValue;
			$this->FlightID->ViewCustomAttributes = "";

			// DepartureAirport
			$this->DepartureAirport->ViewValue = $this->DepartureAirport->CurrentValue;
			$this->DepartureAirport->ViewCustomAttributes = "";

			// ArrivalLocation
			$this->ArrivalLocation->ViewValue = $this->ArrivalLocation->CurrentValue;
			$this->ArrivalLocation->ViewCustomAttributes = "";

			// DepartureTime
			$this->DepartureTime->ViewValue = $this->DepartureTime->CurrentValue;
			$this->DepartureTime->ViewCustomAttributes = "";

			// ArrivalTime
			$this->ArrivalTime->ViewValue = $this->ArrivalTime->CurrentValue;
			$this->ArrivalTime->ViewCustomAttributes = "";

			// DepartureDate
			$this->DepartureDate->ViewValue = $this->DepartureDate->CurrentValue;
			$this->DepartureDate->ViewValue = ew_FormatDateTime($this->DepartureDate->ViewValue, 5);
			$this->DepartureDate->ViewCustomAttributes = "";

			// ArrivalDate
			$this->ArrivalDate->ViewValue = $this->ArrivalDate->CurrentValue;
			$this->ArrivalDate->ViewValue = ew_FormatDateTime($this->ArrivalDate->ViewValue, 5);
			$this->ArrivalDate->ViewCustomAttributes = "";

			// Price
			$this->Price->ViewValue = $this->Price->CurrentValue;
			$this->Price->ViewCustomAttributes = "";

			// Company
			$this->Company->ViewValue = $this->Company->CurrentValue;
			$this->Company->ViewCustomAttributes = "";

			// Capacity
			$this->Capacity->ViewValue = $this->Capacity->CurrentValue;
			$this->Capacity->ViewCustomAttributes = "";

			// FlightID
			$this->FlightID->LinkCustomAttributes = "";
			$this->FlightID->HrefValue = "";
			$this->FlightID->TooltipValue = "";

			// DepartureAirport
			$this->DepartureAirport->LinkCustomAttributes = "";
			$this->DepartureAirport->HrefValue = "";
			$this->DepartureAirport->TooltipValue = "";

			// ArrivalLocation
			$this->ArrivalLocation->LinkCustomAttributes = "";
			$this->ArrivalLocation->HrefValue = "";
			$this->ArrivalLocation->TooltipValue = "";

			// DepartureTime
			$this->DepartureTime->LinkCustomAttributes = "";
			$this->DepartureTime->HrefValue = "";
			$this->DepartureTime->TooltipValue = "";

			// ArrivalTime
			$this->ArrivalTime->LinkCustomAttributes = "";
			$this->ArrivalTime->HrefValue = "";
			$this->ArrivalTime->TooltipValue = "";

			// DepartureDate
			$this->DepartureDate->LinkCustomAttributes = "";
			$this->DepartureDate->HrefValue = "";
			$this->DepartureDate->TooltipValue = "";

			// ArrivalDate
			$this->ArrivalDate->LinkCustomAttributes = "";
			$this->ArrivalDate->HrefValue = "";
			$this->ArrivalDate->TooltipValue = "";

			// Price
			$this->Price->LinkCustomAttributes = "";
			$this->Price->HrefValue = "";
			$this->Price->TooltipValue = "";

			// Company
			$this->Company->LinkCustomAttributes = "";
			$this->Company->HrefValue = "";
			$this->Company->TooltipValue = "";

			// Capacity
			$this->Capacity->LinkCustomAttributes = "";
			$this->Capacity->HrefValue = "";
			$this->Capacity->TooltipValue = "";
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
				$sThisKey .= $row['FlightID'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "flightlist.php", $this->TableVar);
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
if (!isset($flight_delete)) $flight_delete = new cflight_delete();

// Page init
$flight_delete->Page_Init();

// Page main
$flight_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$flight_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var flight_delete = new ew_Page("flight_delete");
flight_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = flight_delete.PageID; // For backward compatibility

// Form object
var fflightdelete = new ew_Form("fflightdelete");

// Form_CustomValidate event
fflightdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fflightdelete.ValidateRequired = true;
<?php } else { ?>
fflightdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($flight_delete->Recordset = $flight_delete->LoadRecordset())
	$flight_deleteTotalRecs = $flight_delete->Recordset->RecordCount(); // Get record count
if ($flight_deleteTotalRecs <= 0) { // No record found, exit
	if ($flight_delete->Recordset)
		$flight_delete->Recordset->Close();
	$flight_delete->Page_Terminate("flightlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $flight_delete->ShowPageHeader(); ?>
<?php
$flight_delete->ShowMessage();
?>
<form name="fflightdelete" id="fflightdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="flight">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($flight_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_flightdelete" class="ewTable ewTableSeparate">
<?php echo $flight->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($flight->FlightID->Visible) { // FlightID ?>
		<td><span id="elh_flight_FlightID" class="flight_FlightID"><?php echo $flight->FlightID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->DepartureAirport->Visible) { // DepartureAirport ?>
		<td><span id="elh_flight_DepartureAirport" class="flight_DepartureAirport"><?php echo $flight->DepartureAirport->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->ArrivalLocation->Visible) { // ArrivalLocation ?>
		<td><span id="elh_flight_ArrivalLocation" class="flight_ArrivalLocation"><?php echo $flight->ArrivalLocation->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->DepartureTime->Visible) { // DepartureTime ?>
		<td><span id="elh_flight_DepartureTime" class="flight_DepartureTime"><?php echo $flight->DepartureTime->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->ArrivalTime->Visible) { // ArrivalTime ?>
		<td><span id="elh_flight_ArrivalTime" class="flight_ArrivalTime"><?php echo $flight->ArrivalTime->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->DepartureDate->Visible) { // DepartureDate ?>
		<td><span id="elh_flight_DepartureDate" class="flight_DepartureDate"><?php echo $flight->DepartureDate->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->ArrivalDate->Visible) { // ArrivalDate ?>
		<td><span id="elh_flight_ArrivalDate" class="flight_ArrivalDate"><?php echo $flight->ArrivalDate->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->Price->Visible) { // Price ?>
		<td><span id="elh_flight_Price" class="flight_Price"><?php echo $flight->Price->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->Company->Visible) { // Company ?>
		<td><span id="elh_flight_Company" class="flight_Company"><?php echo $flight->Company->FldCaption() ?></span></td>
<?php } ?>
<?php if ($flight->Capacity->Visible) { // Capacity ?>
		<td><span id="elh_flight_Capacity" class="flight_Capacity"><?php echo $flight->Capacity->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$flight_delete->RecCnt = 0;
$i = 0;
while (!$flight_delete->Recordset->EOF) {
	$flight_delete->RecCnt++;
	$flight_delete->RowCnt++;

	// Set row properties
	$flight->ResetAttrs();
	$flight->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$flight_delete->LoadRowValues($flight_delete->Recordset);

	// Render row
	$flight_delete->RenderRow();
?>
	<tr<?php echo $flight->RowAttributes() ?>>
<?php if ($flight->FlightID->Visible) { // FlightID ?>
		<td<?php echo $flight->FlightID->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_FlightID" class="control-group flight_FlightID">
<span<?php echo $flight->FlightID->ViewAttributes() ?>>
<?php echo $flight->FlightID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->DepartureAirport->Visible) { // DepartureAirport ?>
		<td<?php echo $flight->DepartureAirport->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_DepartureAirport" class="control-group flight_DepartureAirport">
<span<?php echo $flight->DepartureAirport->ViewAttributes() ?>>
<?php echo $flight->DepartureAirport->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->ArrivalLocation->Visible) { // ArrivalLocation ?>
		<td<?php echo $flight->ArrivalLocation->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_ArrivalLocation" class="control-group flight_ArrivalLocation">
<span<?php echo $flight->ArrivalLocation->ViewAttributes() ?>>
<?php echo $flight->ArrivalLocation->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->DepartureTime->Visible) { // DepartureTime ?>
		<td<?php echo $flight->DepartureTime->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_DepartureTime" class="control-group flight_DepartureTime">
<span<?php echo $flight->DepartureTime->ViewAttributes() ?>>
<?php echo $flight->DepartureTime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->ArrivalTime->Visible) { // ArrivalTime ?>
		<td<?php echo $flight->ArrivalTime->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_ArrivalTime" class="control-group flight_ArrivalTime">
<span<?php echo $flight->ArrivalTime->ViewAttributes() ?>>
<?php echo $flight->ArrivalTime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->DepartureDate->Visible) { // DepartureDate ?>
		<td<?php echo $flight->DepartureDate->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_DepartureDate" class="control-group flight_DepartureDate">
<span<?php echo $flight->DepartureDate->ViewAttributes() ?>>
<?php echo $flight->DepartureDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->ArrivalDate->Visible) { // ArrivalDate ?>
		<td<?php echo $flight->ArrivalDate->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_ArrivalDate" class="control-group flight_ArrivalDate">
<span<?php echo $flight->ArrivalDate->ViewAttributes() ?>>
<?php echo $flight->ArrivalDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->Price->Visible) { // Price ?>
		<td<?php echo $flight->Price->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_Price" class="control-group flight_Price">
<span<?php echo $flight->Price->ViewAttributes() ?>>
<?php echo $flight->Price->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->Company->Visible) { // Company ?>
		<td<?php echo $flight->Company->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_Company" class="control-group flight_Company">
<span<?php echo $flight->Company->ViewAttributes() ?>>
<?php echo $flight->Company->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($flight->Capacity->Visible) { // Capacity ?>
		<td<?php echo $flight->Capacity->CellAttributes() ?>>
<span id="el<?php echo $flight_delete->RowCnt ?>_flight_Capacity" class="control-group flight_Capacity">
<span<?php echo $flight->Capacity->ViewAttributes() ?>>
<?php echo $flight->Capacity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$flight_delete->Recordset->MoveNext();
}
$flight_delete->Recordset->Close();
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
fflightdelete.Init();
</script>
<?php
$flight_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$flight_delete->Page_Terminate();
?>
