<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "repliesinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$replies_view = NULL; // Initialize page object first

class creplies_view extends creplies {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'replies';

	// Page object name
	var $PageObjName = 'replies_view';

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

		// Table object (replies)
		if (!isset($GLOBALS["replies"])) {
			$GLOBALS["replies"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["replies"];
		}
		$KeyUrl = "";
		if (@$_GET["ReplyID"] <> "") {
			$this->RecKey["ReplyID"] = $_GET["ReplyID"];
			$KeyUrl .= "&ReplyID=" . urlencode($this->RecKey["ReplyID"]);
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
			define("EW_TABLE_NAME", 'replies', TRUE);

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
		$this->ReplyID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["ReplyID"] <> "") {
				$this->ReplyID->setQueryStringValue($_GET["ReplyID"]);
				$this->RecKey["ReplyID"] = $this->ReplyID->QueryStringValue;
			} else {
				$sReturnUrl = "replieslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "replieslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "replieslist.php"; // Not page request, return to list
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
		$this->ReplyTrigger->setDbValue($rs->fields('ReplyTrigger'));
		$this->ReplyResponse->setDbValue($rs->fields('ReplyResponse'));
		$this->ReplyType->setDbValue($rs->fields('ReplyType'));
		$this->ReplyValidationResponse->setDbValue($rs->fields('ReplyValidationResponse'));
		$this->ReplyStage->setDbValue($rs->fields('ReplyStage'));
		$this->ReplyID->setDbValue($rs->fields('ReplyID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ReplyTrigger->DbValue = $row['ReplyTrigger'];
		$this->ReplyResponse->DbValue = $row['ReplyResponse'];
		$this->ReplyType->DbValue = $row['ReplyType'];
		$this->ReplyValidationResponse->DbValue = $row['ReplyValidationResponse'];
		$this->ReplyStage->DbValue = $row['ReplyStage'];
		$this->ReplyID->DbValue = $row['ReplyID'];
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
		// ReplyTrigger
		// ReplyResponse
		// ReplyType
		// ReplyValidationResponse
		// ReplyStage
		// ReplyID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// ReplyTrigger
			$this->ReplyTrigger->ViewValue = $this->ReplyTrigger->CurrentValue;
			$this->ReplyTrigger->ViewCustomAttributes = "";

			// ReplyResponse
			$this->ReplyResponse->ViewValue = $this->ReplyResponse->CurrentValue;
			$this->ReplyResponse->ViewCustomAttributes = "";

			// ReplyType
			if (strval($this->ReplyType->CurrentValue) <> "") {
				switch ($this->ReplyType->CurrentValue) {
					case $this->ReplyType->FldTagValue(1):
						$this->ReplyType->ViewValue = $this->ReplyType->FldTagCaption(1) <> "" ? $this->ReplyType->FldTagCaption(1) : $this->ReplyType->CurrentValue;
						break;
					case $this->ReplyType->FldTagValue(2):
						$this->ReplyType->ViewValue = $this->ReplyType->FldTagCaption(2) <> "" ? $this->ReplyType->FldTagCaption(2) : $this->ReplyType->CurrentValue;
						break;
					case $this->ReplyType->FldTagValue(3):
						$this->ReplyType->ViewValue = $this->ReplyType->FldTagCaption(3) <> "" ? $this->ReplyType->FldTagCaption(3) : $this->ReplyType->CurrentValue;
						break;
					default:
						$this->ReplyType->ViewValue = $this->ReplyType->CurrentValue;
				}
			} else {
				$this->ReplyType->ViewValue = NULL;
			}
			$this->ReplyType->ViewCustomAttributes = "";

			// ReplyValidationResponse
			$this->ReplyValidationResponse->ViewValue = $this->ReplyValidationResponse->CurrentValue;
			$this->ReplyValidationResponse->ViewCustomAttributes = "";

			// ReplyStage
			if (strval($this->ReplyStage->CurrentValue) <> "") {
				switch ($this->ReplyStage->CurrentValue) {
					case $this->ReplyStage->FldTagValue(1):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(1) <> "" ? $this->ReplyStage->FldTagCaption(1) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(2):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(2) <> "" ? $this->ReplyStage->FldTagCaption(2) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(3):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(3) <> "" ? $this->ReplyStage->FldTagCaption(3) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(4):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(4) <> "" ? $this->ReplyStage->FldTagCaption(4) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(5):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(5) <> "" ? $this->ReplyStage->FldTagCaption(5) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(6):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(6) <> "" ? $this->ReplyStage->FldTagCaption(6) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(7):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(7) <> "" ? $this->ReplyStage->FldTagCaption(7) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(8):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(8) <> "" ? $this->ReplyStage->FldTagCaption(8) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(9):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(9) <> "" ? $this->ReplyStage->FldTagCaption(9) : $this->ReplyStage->CurrentValue;
						break;
					case $this->ReplyStage->FldTagValue(10):
						$this->ReplyStage->ViewValue = $this->ReplyStage->FldTagCaption(10) <> "" ? $this->ReplyStage->FldTagCaption(10) : $this->ReplyStage->CurrentValue;
						break;
					default:
						$this->ReplyStage->ViewValue = $this->ReplyStage->CurrentValue;
				}
			} else {
				$this->ReplyStage->ViewValue = NULL;
			}
			$this->ReplyStage->ViewCustomAttributes = "";

			// ReplyID
			$this->ReplyID->ViewValue = $this->ReplyID->CurrentValue;
			$this->ReplyID->ViewCustomAttributes = "";

			// ReplyTrigger
			$this->ReplyTrigger->LinkCustomAttributes = "";
			$this->ReplyTrigger->HrefValue = "";
			$this->ReplyTrigger->TooltipValue = "";

			// ReplyResponse
			$this->ReplyResponse->LinkCustomAttributes = "";
			$this->ReplyResponse->HrefValue = "";
			$this->ReplyResponse->TooltipValue = "";

			// ReplyType
			$this->ReplyType->LinkCustomAttributes = "";
			$this->ReplyType->HrefValue = "";
			$this->ReplyType->TooltipValue = "";

			// ReplyValidationResponse
			$this->ReplyValidationResponse->LinkCustomAttributes = "";
			$this->ReplyValidationResponse->HrefValue = "";
			$this->ReplyValidationResponse->TooltipValue = "";

			// ReplyStage
			$this->ReplyStage->LinkCustomAttributes = "";
			$this->ReplyStage->HrefValue = "";
			$this->ReplyStage->TooltipValue = "";

			// ReplyID
			$this->ReplyID->LinkCustomAttributes = "";
			$this->ReplyID->HrefValue = "";
			$this->ReplyID->TooltipValue = "";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "replieslist.php", $this->TableVar);
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
if (!isset($replies_view)) $replies_view = new creplies_view();

// Page init
$replies_view->Page_Init();

// Page main
$replies_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$replies_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var replies_view = new ew_Page("replies_view");
replies_view.PageID = "view"; // Page ID
var EW_PAGE_ID = replies_view.PageID; // For backward compatibility

// Form object
var frepliesview = new ew_Form("frepliesview");

// Form_CustomValidate event
frepliesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepliesview.ValidateRequired = true;
<?php } else { ?>
frepliesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $replies_view->ExportOptions->Render("body") ?>
<?php if (!$replies_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($replies_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $replies_view->ShowPageHeader(); ?>
<?php
$replies_view->ShowMessage();
?>
<form name="frepliesview" id="frepliesview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="replies">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_repliesview" class="table table-bordered table-striped">
<?php if ($replies->ReplyTrigger->Visible) { // ReplyTrigger ?>
	<tr id="r_ReplyTrigger">
		<td><span id="elh_replies_ReplyTrigger"><?php echo $replies->ReplyTrigger->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyTrigger->CellAttributes() ?>>
<span id="el_replies_ReplyTrigger" class="control-group">
<span<?php echo $replies->ReplyTrigger->ViewAttributes() ?>>
<?php echo $replies->ReplyTrigger->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyResponse->Visible) { // ReplyResponse ?>
	<tr id="r_ReplyResponse">
		<td><span id="elh_replies_ReplyResponse"><?php echo $replies->ReplyResponse->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyResponse->CellAttributes() ?>>
<span id="el_replies_ReplyResponse" class="control-group">
<span<?php echo $replies->ReplyResponse->ViewAttributes() ?>>
<?php echo $replies->ReplyResponse->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyType->Visible) { // ReplyType ?>
	<tr id="r_ReplyType">
		<td><span id="elh_replies_ReplyType"><?php echo $replies->ReplyType->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyType->CellAttributes() ?>>
<span id="el_replies_ReplyType" class="control-group">
<span<?php echo $replies->ReplyType->ViewAttributes() ?>>
<?php echo $replies->ReplyType->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyValidationResponse->Visible) { // ReplyValidationResponse ?>
	<tr id="r_ReplyValidationResponse">
		<td><span id="elh_replies_ReplyValidationResponse"><?php echo $replies->ReplyValidationResponse->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyValidationResponse->CellAttributes() ?>>
<span id="el_replies_ReplyValidationResponse" class="control-group">
<span<?php echo $replies->ReplyValidationResponse->ViewAttributes() ?>>
<?php echo $replies->ReplyValidationResponse->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyStage->Visible) { // ReplyStage ?>
	<tr id="r_ReplyStage">
		<td><span id="elh_replies_ReplyStage"><?php echo $replies->ReplyStage->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyStage->CellAttributes() ?>>
<span id="el_replies_ReplyStage" class="control-group">
<span<?php echo $replies->ReplyStage->ViewAttributes() ?>>
<?php echo $replies->ReplyStage->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyID->Visible) { // ReplyID ?>
	<tr id="r_ReplyID">
		<td><span id="elh_replies_ReplyID"><?php echo $replies->ReplyID->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyID->CellAttributes() ?>>
<span id="el_replies_ReplyID" class="control-group">
<span<?php echo $replies->ReplyID->ViewAttributes() ?>>
<?php echo $replies->ReplyID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
frepliesview.Init();
</script>
<?php
$replies_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$replies_view->Page_Terminate();
?>
