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

$dialogue_rules_view = NULL; // Initialize page object first

class cdialogue_rules_view extends cdialogue_rules {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'dialogue_rules';

	// Page object name
	var $PageObjName = 'dialogue_rules_view';

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

		// Table object (dialogue_rules)
		if (!isset($GLOBALS["dialogue_rules"])) {
			$GLOBALS["dialogue_rules"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dialogue_rules"];
		}
		$KeyUrl = "";
		if (@$_GET["DrID"] <> "") {
			$this->RecKey["DrID"] = $_GET["DrID"];
			$KeyUrl .= "&DrID=" . urlencode($this->RecKey["DrID"]);
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
			define("EW_TABLE_NAME", 'dialogue_rules', TRUE);

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
			if (@$_GET["DrID"] <> "") {
				$this->DrID->setQueryStringValue($_GET["DrID"]);
				$this->RecKey["DrID"] = $this->DrID->QueryStringValue;
			} else {
				$sReturnUrl = "dialogue_ruleslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "dialogue_ruleslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "dialogue_ruleslist.php"; // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// DrTrigger
			$this->DrTrigger->ViewValue = $this->DrTrigger->CurrentValue;
			$this->DrTrigger->ViewCustomAttributes = "";

			// DrResponse
			$this->DrResponse->ViewValue = $this->DrResponse->CurrentValue;
			$this->DrResponse->ViewCustomAttributes = "";

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

			// DrValidationResponse
			$this->DrValidationResponse->ViewValue = $this->DrValidationResponse->CurrentValue;
			$this->DrValidationResponse->ViewCustomAttributes = "";

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

			// DrTrigger
			$this->DrTrigger->LinkCustomAttributes = "";
			$this->DrTrigger->HrefValue = "";
			$this->DrTrigger->TooltipValue = "";

			// DrResponse
			$this->DrResponse->LinkCustomAttributes = "";
			$this->DrResponse->HrefValue = "";
			$this->DrResponse->TooltipValue = "";

			// DrType
			$this->DrType->LinkCustomAttributes = "";
			$this->DrType->HrefValue = "";
			$this->DrType->TooltipValue = "";

			// DrValidationResponse
			$this->DrValidationResponse->LinkCustomAttributes = "";
			$this->DrValidationResponse->HrefValue = "";
			$this->DrValidationResponse->TooltipValue = "";

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "dialogue_ruleslist.php", $this->TableVar);
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
if (!isset($dialogue_rules_view)) $dialogue_rules_view = new cdialogue_rules_view();

// Page init
$dialogue_rules_view->Page_Init();

// Page main
$dialogue_rules_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dialogue_rules_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dialogue_rules_view = new ew_Page("dialogue_rules_view");
dialogue_rules_view.PageID = "view"; // Page ID
var EW_PAGE_ID = dialogue_rules_view.PageID; // For backward compatibility

// Form object
var fdialogue_rulesview = new ew_Form("fdialogue_rulesview");

// Form_CustomValidate event
fdialogue_rulesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdialogue_rulesview.ValidateRequired = true;
<?php } else { ?>
fdialogue_rulesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $dialogue_rules_view->ExportOptions->Render("body") ?>
<?php if (!$dialogue_rules_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($dialogue_rules_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $dialogue_rules_view->ShowPageHeader(); ?>
<?php
$dialogue_rules_view->ShowMessage();
?>
<form name="fdialogue_rulesview" id="fdialogue_rulesview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="dialogue_rules">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_dialogue_rulesview" class="table table-bordered table-striped">
<?php if ($dialogue_rules->DrTrigger->Visible) { // DrTrigger ?>
	<tr id="r_DrTrigger">
		<td><span id="elh_dialogue_rules_DrTrigger"><?php echo $dialogue_rules->DrTrigger->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrTrigger->CellAttributes() ?>>
<span id="el_dialogue_rules_DrTrigger" class="control-group">
<span<?php echo $dialogue_rules->DrTrigger->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrTrigger->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrResponse->Visible) { // DrResponse ?>
	<tr id="r_DrResponse">
		<td><span id="elh_dialogue_rules_DrResponse"><?php echo $dialogue_rules->DrResponse->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrResponse->CellAttributes() ?>>
<span id="el_dialogue_rules_DrResponse" class="control-group">
<span<?php echo $dialogue_rules->DrResponse->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrResponse->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrType->Visible) { // DrType ?>
	<tr id="r_DrType">
		<td><span id="elh_dialogue_rules_DrType"><?php echo $dialogue_rules->DrType->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrType->CellAttributes() ?>>
<span id="el_dialogue_rules_DrType" class="control-group">
<span<?php echo $dialogue_rules->DrType->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrType->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrValidationResponse->Visible) { // DrValidationResponse ?>
	<tr id="r_DrValidationResponse">
		<td><span id="elh_dialogue_rules_DrValidationResponse"><?php echo $dialogue_rules->DrValidationResponse->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrValidationResponse->CellAttributes() ?>>
<span id="el_dialogue_rules_DrValidationResponse" class="control-group">
<span<?php echo $dialogue_rules->DrValidationResponse->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrValidationResponse->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrStage->Visible) { // DrStage ?>
	<tr id="r_DrStage">
		<td><span id="elh_dialogue_rules_DrStage"><?php echo $dialogue_rules->DrStage->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrStage->CellAttributes() ?>>
<span id="el_dialogue_rules_DrStage" class="control-group">
<span<?php echo $dialogue_rules->DrStage->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrStage->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrID->Visible) { // DrID ?>
	<tr id="r_DrID">
		<td><span id="elh_dialogue_rules_DrID"><?php echo $dialogue_rules->DrID->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrID->CellAttributes() ?>>
<span id="el_dialogue_rules_DrID" class="control-group">
<span<?php echo $dialogue_rules->DrID->ViewAttributes() ?>>
<?php echo $dialogue_rules->DrID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fdialogue_rulesview.Init();
</script>
<?php
$dialogue_rules_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dialogue_rules_view->Page_Terminate();
?>
