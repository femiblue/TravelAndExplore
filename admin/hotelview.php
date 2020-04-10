<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "hotelinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$hotel_view = NULL; // Initialize page object first

class chotel_view extends chotel {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'hotel';

	// Page object name
	var $PageObjName = 'hotel_view';

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

		// Table object (hotel)
		if (!isset($GLOBALS["hotel"])) {
			$GLOBALS["hotel"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hotel"];
		}
		$KeyUrl = "";
		if (@$_GET["HotelID"] <> "") {
			$this->RecKey["HotelID"] = $_GET["HotelID"];
			$KeyUrl .= "&HotelID=" . urlencode($this->RecKey["HotelID"]);
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
			define("EW_TABLE_NAME", 'hotel', TRUE);

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
		$this->HotelID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["HotelID"] <> "") {
				$this->HotelID->setQueryStringValue($_GET["HotelID"]);
				$this->RecKey["HotelID"] = $this->HotelID->QueryStringValue;
			} else {
				$sReturnUrl = "hotellist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "hotellist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "hotellist.php"; // Not page request, return to list
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
		$this->HotelID->setDbValue($rs->fields('HotelID'));
		$this->Country->setDbValue($rs->fields('Country'));
		$this->Title->setDbValue($rs->fields('Title'));
		$this->HotelCity->setDbValue($rs->fields('HotelCity'));
		$this->Address->setDbValue($rs->fields('Address'));
		$this->NumberOfRooms->setDbValue($rs->fields('NumberOfRooms'));
		$this->Ranking->setDbValue($rs->fields('Ranking'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->HotelID->DbValue = $row['HotelID'];
		$this->Country->DbValue = $row['Country'];
		$this->Title->DbValue = $row['Title'];
		$this->HotelCity->DbValue = $row['HotelCity'];
		$this->Address->DbValue = $row['Address'];
		$this->NumberOfRooms->DbValue = $row['NumberOfRooms'];
		$this->Ranking->DbValue = $row['Ranking'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// HotelID
		// Country
		// Title
		// HotelCity
		// Address
		// NumberOfRooms
		// Ranking

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// HotelID
			$this->HotelID->ViewValue = $this->HotelID->CurrentValue;
			$this->HotelID->ViewCustomAttributes = "";

			// Country
			$this->Country->ViewValue = $this->Country->CurrentValue;
			$this->Country->ViewCustomAttributes = "";

			// Title
			$this->Title->ViewValue = $this->Title->CurrentValue;
			$this->Title->ViewCustomAttributes = "";

			// HotelCity
			$this->HotelCity->ViewValue = $this->HotelCity->CurrentValue;
			$this->HotelCity->ViewCustomAttributes = "";

			// Address
			$this->Address->ViewValue = $this->Address->CurrentValue;
			$this->Address->ViewCustomAttributes = "";

			// NumberOfRooms
			$this->NumberOfRooms->ViewValue = $this->NumberOfRooms->CurrentValue;
			$this->NumberOfRooms->ViewCustomAttributes = "";

			// Ranking
			if (strval($this->Ranking->CurrentValue) <> "") {
				switch ($this->Ranking->CurrentValue) {
					case $this->Ranking->FldTagValue(1):
						$this->Ranking->ViewValue = $this->Ranking->FldTagCaption(1) <> "" ? $this->Ranking->FldTagCaption(1) : $this->Ranking->CurrentValue;
						break;
					case $this->Ranking->FldTagValue(2):
						$this->Ranking->ViewValue = $this->Ranking->FldTagCaption(2) <> "" ? $this->Ranking->FldTagCaption(2) : $this->Ranking->CurrentValue;
						break;
					case $this->Ranking->FldTagValue(3):
						$this->Ranking->ViewValue = $this->Ranking->FldTagCaption(3) <> "" ? $this->Ranking->FldTagCaption(3) : $this->Ranking->CurrentValue;
						break;
					case $this->Ranking->FldTagValue(4):
						$this->Ranking->ViewValue = $this->Ranking->FldTagCaption(4) <> "" ? $this->Ranking->FldTagCaption(4) : $this->Ranking->CurrentValue;
						break;
					case $this->Ranking->FldTagValue(5):
						$this->Ranking->ViewValue = $this->Ranking->FldTagCaption(5) <> "" ? $this->Ranking->FldTagCaption(5) : $this->Ranking->CurrentValue;
						break;
					default:
						$this->Ranking->ViewValue = $this->Ranking->CurrentValue;
				}
			} else {
				$this->Ranking->ViewValue = NULL;
			}
			$this->Ranking->ViewCustomAttributes = "";

			// HotelID
			$this->HotelID->LinkCustomAttributes = "";
			$this->HotelID->HrefValue = "";
			$this->HotelID->TooltipValue = "";

			// Country
			$this->Country->LinkCustomAttributes = "";
			$this->Country->HrefValue = "";
			$this->Country->TooltipValue = "";

			// Title
			$this->Title->LinkCustomAttributes = "";
			$this->Title->HrefValue = "";
			$this->Title->TooltipValue = "";

			// HotelCity
			$this->HotelCity->LinkCustomAttributes = "";
			$this->HotelCity->HrefValue = "";
			$this->HotelCity->TooltipValue = "";

			// Address
			$this->Address->LinkCustomAttributes = "";
			$this->Address->HrefValue = "";
			$this->Address->TooltipValue = "";

			// NumberOfRooms
			$this->NumberOfRooms->LinkCustomAttributes = "";
			$this->NumberOfRooms->HrefValue = "";
			$this->NumberOfRooms->TooltipValue = "";

			// Ranking
			$this->Ranking->LinkCustomAttributes = "";
			$this->Ranking->HrefValue = "";
			$this->Ranking->TooltipValue = "";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "hotellist.php", $this->TableVar);
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
if (!isset($hotel_view)) $hotel_view = new chotel_view();

// Page init
$hotel_view->Page_Init();

// Page main
$hotel_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hotel_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hotel_view = new ew_Page("hotel_view");
hotel_view.PageID = "view"; // Page ID
var EW_PAGE_ID = hotel_view.PageID; // For backward compatibility

// Form object
var fhotelview = new ew_Form("fhotelview");

// Form_CustomValidate event
fhotelview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhotelview.ValidateRequired = true;
<?php } else { ?>
fhotelview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $hotel_view->ExportOptions->Render("body") ?>
<?php if (!$hotel_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($hotel_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $hotel_view->ShowPageHeader(); ?>
<?php
$hotel_view->ShowMessage();
?>
<form name="fhotelview" id="fhotelview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="hotel">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_hotelview" class="table table-bordered table-striped">
<?php if ($hotel->HotelID->Visible) { // HotelID ?>
	<tr id="r_HotelID">
		<td><span id="elh_hotel_HotelID"><?php echo $hotel->HotelID->FldCaption() ?></span></td>
		<td<?php echo $hotel->HotelID->CellAttributes() ?>>
<span id="el_hotel_HotelID" class="control-group">
<span<?php echo $hotel->HotelID->ViewAttributes() ?>>
<?php echo $hotel->HotelID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hotel->Country->Visible) { // Country ?>
	<tr id="r_Country">
		<td><span id="elh_hotel_Country"><?php echo $hotel->Country->FldCaption() ?></span></td>
		<td<?php echo $hotel->Country->CellAttributes() ?>>
<span id="el_hotel_Country" class="control-group">
<span<?php echo $hotel->Country->ViewAttributes() ?>>
<?php echo $hotel->Country->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hotel->Title->Visible) { // Title ?>
	<tr id="r_Title">
		<td><span id="elh_hotel_Title"><?php echo $hotel->Title->FldCaption() ?></span></td>
		<td<?php echo $hotel->Title->CellAttributes() ?>>
<span id="el_hotel_Title" class="control-group">
<span<?php echo $hotel->Title->ViewAttributes() ?>>
<?php echo $hotel->Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hotel->HotelCity->Visible) { // HotelCity ?>
	<tr id="r_HotelCity">
		<td><span id="elh_hotel_HotelCity"><?php echo $hotel->HotelCity->FldCaption() ?></span></td>
		<td<?php echo $hotel->HotelCity->CellAttributes() ?>>
<span id="el_hotel_HotelCity" class="control-group">
<span<?php echo $hotel->HotelCity->ViewAttributes() ?>>
<?php echo $hotel->HotelCity->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hotel->Address->Visible) { // Address ?>
	<tr id="r_Address">
		<td><span id="elh_hotel_Address"><?php echo $hotel->Address->FldCaption() ?></span></td>
		<td<?php echo $hotel->Address->CellAttributes() ?>>
<span id="el_hotel_Address" class="control-group">
<span<?php echo $hotel->Address->ViewAttributes() ?>>
<?php echo $hotel->Address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hotel->NumberOfRooms->Visible) { // NumberOfRooms ?>
	<tr id="r_NumberOfRooms">
		<td><span id="elh_hotel_NumberOfRooms"><?php echo $hotel->NumberOfRooms->FldCaption() ?></span></td>
		<td<?php echo $hotel->NumberOfRooms->CellAttributes() ?>>
<span id="el_hotel_NumberOfRooms" class="control-group">
<span<?php echo $hotel->NumberOfRooms->ViewAttributes() ?>>
<?php echo $hotel->NumberOfRooms->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hotel->Ranking->Visible) { // Ranking ?>
	<tr id="r_Ranking">
		<td><span id="elh_hotel_Ranking"><?php echo $hotel->Ranking->FldCaption() ?></span></td>
		<td<?php echo $hotel->Ranking->CellAttributes() ?>>
<span id="el_hotel_Ranking" class="control-group">
<span<?php echo $hotel->Ranking->ViewAttributes() ?>>
<?php echo $hotel->Ranking->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fhotelview.Init();
</script>
<?php
$hotel_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hotel_view->Page_Terminate();
?>
