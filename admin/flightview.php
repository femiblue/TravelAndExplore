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

$flight_view = NULL; // Initialize page object first

class cflight_view extends cflight {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'flight';

	// Page object name
	var $PageObjName = 'flight_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["FlightID"] <> "") {
			$this->RecKey["FlightID"] = $_GET["FlightID"];
			$KeyUrl .= "&FlightID=" . urlencode($this->RecKey["FlightID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'flight', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["FlightID"] <> "") {
				$this->FlightID->setQueryStringValue($_GET["FlightID"]);
				$this->RecKey["FlightID"] = $this->FlightID->QueryStringValue;
			} else {
				$sReturnUrl = "flightlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "flightlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "flightlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "flightlist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($flight_view)) $flight_view = new cflight_view();

// Page init
$flight_view->Page_Init();

// Page main
$flight_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$flight_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var flight_view = new ew_Page("flight_view");
flight_view.PageID = "view"; // Page ID
var EW_PAGE_ID = flight_view.PageID; // For backward compatibility

// Form object
var fflightview = new ew_Form("fflightview");

// Form_CustomValidate event
fflightview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fflightview.ValidateRequired = true;
<?php } else { ?>
fflightview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $flight_view->ExportOptions->Render("body") ?>
<?php if (!$flight_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($flight_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $flight_view->ShowPageHeader(); ?>
<?php
$flight_view->ShowMessage();
?>
<form name="fflightview" id="fflightview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="flight">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_flightview" class="table table-bordered table-striped">
<?php if ($flight->FlightID->Visible) { // FlightID ?>
	<tr id="r_FlightID">
		<td><span id="elh_flight_FlightID"><?php echo $flight->FlightID->FldCaption() ?></span></td>
		<td<?php echo $flight->FlightID->CellAttributes() ?>>
<span id="el_flight_FlightID" class="control-group">
<span<?php echo $flight->FlightID->ViewAttributes() ?>>
<?php echo $flight->FlightID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->DepartureAirport->Visible) { // DepartureAirport ?>
	<tr id="r_DepartureAirport">
		<td><span id="elh_flight_DepartureAirport"><?php echo $flight->DepartureAirport->FldCaption() ?></span></td>
		<td<?php echo $flight->DepartureAirport->CellAttributes() ?>>
<span id="el_flight_DepartureAirport" class="control-group">
<span<?php echo $flight->DepartureAirport->ViewAttributes() ?>>
<?php echo $flight->DepartureAirport->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->ArrivalLocation->Visible) { // ArrivalLocation ?>
	<tr id="r_ArrivalLocation">
		<td><span id="elh_flight_ArrivalLocation"><?php echo $flight->ArrivalLocation->FldCaption() ?></span></td>
		<td<?php echo $flight->ArrivalLocation->CellAttributes() ?>>
<span id="el_flight_ArrivalLocation" class="control-group">
<span<?php echo $flight->ArrivalLocation->ViewAttributes() ?>>
<?php echo $flight->ArrivalLocation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->DepartureTime->Visible) { // DepartureTime ?>
	<tr id="r_DepartureTime">
		<td><span id="elh_flight_DepartureTime"><?php echo $flight->DepartureTime->FldCaption() ?></span></td>
		<td<?php echo $flight->DepartureTime->CellAttributes() ?>>
<span id="el_flight_DepartureTime" class="control-group">
<span<?php echo $flight->DepartureTime->ViewAttributes() ?>>
<?php echo $flight->DepartureTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->ArrivalTime->Visible) { // ArrivalTime ?>
	<tr id="r_ArrivalTime">
		<td><span id="elh_flight_ArrivalTime"><?php echo $flight->ArrivalTime->FldCaption() ?></span></td>
		<td<?php echo $flight->ArrivalTime->CellAttributes() ?>>
<span id="el_flight_ArrivalTime" class="control-group">
<span<?php echo $flight->ArrivalTime->ViewAttributes() ?>>
<?php echo $flight->ArrivalTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->DepartureDate->Visible) { // DepartureDate ?>
	<tr id="r_DepartureDate">
		<td><span id="elh_flight_DepartureDate"><?php echo $flight->DepartureDate->FldCaption() ?></span></td>
		<td<?php echo $flight->DepartureDate->CellAttributes() ?>>
<span id="el_flight_DepartureDate" class="control-group">
<span<?php echo $flight->DepartureDate->ViewAttributes() ?>>
<?php echo $flight->DepartureDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->ArrivalDate->Visible) { // ArrivalDate ?>
	<tr id="r_ArrivalDate">
		<td><span id="elh_flight_ArrivalDate"><?php echo $flight->ArrivalDate->FldCaption() ?></span></td>
		<td<?php echo $flight->ArrivalDate->CellAttributes() ?>>
<span id="el_flight_ArrivalDate" class="control-group">
<span<?php echo $flight->ArrivalDate->ViewAttributes() ?>>
<?php echo $flight->ArrivalDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->Price->Visible) { // Price ?>
	<tr id="r_Price">
		<td><span id="elh_flight_Price"><?php echo $flight->Price->FldCaption() ?></span></td>
		<td<?php echo $flight->Price->CellAttributes() ?>>
<span id="el_flight_Price" class="control-group">
<span<?php echo $flight->Price->ViewAttributes() ?>>
<?php echo $flight->Price->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->Company->Visible) { // Company ?>
	<tr id="r_Company">
		<td><span id="elh_flight_Company"><?php echo $flight->Company->FldCaption() ?></span></td>
		<td<?php echo $flight->Company->CellAttributes() ?>>
<span id="el_flight_Company" class="control-group">
<span<?php echo $flight->Company->ViewAttributes() ?>>
<?php echo $flight->Company->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($flight->Capacity->Visible) { // Capacity ?>
	<tr id="r_Capacity">
		<td><span id="elh_flight_Capacity"><?php echo $flight->Capacity->FldCaption() ?></span></td>
		<td<?php echo $flight->Capacity->CellAttributes() ?>>
<span id="el_flight_Capacity" class="control-group">
<span<?php echo $flight->Capacity->ViewAttributes() ?>>
<?php echo $flight->Capacity->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fflightview.Init();
</script>
<?php
$flight_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$flight_view->Page_Terminate();
?>
