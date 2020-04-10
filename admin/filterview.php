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

$filter_view = NULL; // Initialize page object first

class cfilter_view extends cfilter {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'filter';

	// Page object name
	var $PageObjName = 'filter_view';

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

		// Table object (filter)
		if (!isset($GLOBALS["filter"])) {
			$GLOBALS["filter"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["filter"];
		}
		$KeyUrl = "";
		if (@$_GET["FilterID"] <> "") {
			$this->RecKey["FilterID"] = $_GET["FilterID"];
			$KeyUrl .= "&FilterID=" . urlencode($this->RecKey["FilterID"]);
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
			define("EW_TABLE_NAME", 'filter', TRUE);

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
			if (@$_GET["FilterID"] <> "") {
				$this->FilterID->setQueryStringValue($_GET["FilterID"]);
				$this->RecKey["FilterID"] = $this->FilterID->QueryStringValue;
			} else {
				$sReturnUrl = "filterlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "filterlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "filterlist.php"; // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// Environment
			$this->Environment->ViewValue = $this->Environment->CurrentValue;
			$this->Environment->ViewCustomAttributes = "";

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

			// Weather
			$this->Weather->ViewValue = $this->Weather->CurrentValue;
			$this->Weather->ViewCustomAttributes = "";

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

			// Activities
			$this->Activities->ViewValue = $this->Activities->CurrentValue;
			$this->Activities->ViewCustomAttributes = "";

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

			// Environment
			$this->Environment->LinkCustomAttributes = "";
			$this->Environment->HrefValue = "";
			$this->Environment->TooltipValue = "";

			// Season
			$this->Season->LinkCustomAttributes = "";
			$this->Season->HrefValue = "";
			$this->Season->TooltipValue = "";

			// Weather
			$this->Weather->LinkCustomAttributes = "";
			$this->Weather->HrefValue = "";
			$this->Weather->TooltipValue = "";

			// Age
			$this->Age->LinkCustomAttributes = "";
			$this->Age->HrefValue = "";
			$this->Age->TooltipValue = "";

			// Activities
			$this->Activities->LinkCustomAttributes = "";
			$this->Activities->HrefValue = "";
			$this->Activities->TooltipValue = "";

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "filterlist.php", $this->TableVar);
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
if (!isset($filter_view)) $filter_view = new cfilter_view();

// Page init
$filter_view->Page_Init();

// Page main
$filter_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$filter_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var filter_view = new ew_Page("filter_view");
filter_view.PageID = "view"; // Page ID
var EW_PAGE_ID = filter_view.PageID; // For backward compatibility

// Form object
var ffilterview = new ew_Form("ffilterview");

// Form_CustomValidate event
ffilterview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffilterview.ValidateRequired = true;
<?php } else { ?>
ffilterview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $filter_view->ExportOptions->Render("body") ?>
<?php if (!$filter_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($filter_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $filter_view->ShowPageHeader(); ?>
<?php
$filter_view->ShowMessage();
?>
<form name="ffilterview" id="ffilterview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="filter">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_filterview" class="table table-bordered table-striped">
<?php if ($filter->FilterID->Visible) { // FilterID ?>
	<tr id="r_FilterID">
		<td><span id="elh_filter_FilterID"><?php echo $filter->FilterID->FldCaption() ?></span></td>
		<td<?php echo $filter->FilterID->CellAttributes() ?>>
<span id="el_filter_FilterID" class="control-group">
<span<?php echo $filter->FilterID->ViewAttributes() ?>>
<?php echo $filter->FilterID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Date1->Visible) { // Date1 ?>
	<tr id="r_Date1">
		<td><span id="elh_filter_Date1"><?php echo $filter->Date1->FldCaption() ?></span></td>
		<td<?php echo $filter->Date1->CellAttributes() ?>>
<span id="el_filter_Date1" class="control-group">
<span<?php echo $filter->Date1->ViewAttributes() ?>>
<?php echo $filter->Date1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Date2->Visible) { // Date2 ?>
	<tr id="r_Date2">
		<td><span id="elh_filter_Date2"><?php echo $filter->Date2->FldCaption() ?></span></td>
		<td<?php echo $filter->Date2->CellAttributes() ?>>
<span id="el_filter_Date2" class="control-group">
<span<?php echo $filter->Date2->ViewAttributes() ?>>
<?php echo $filter->Date2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Continent->Visible) { // Continent ?>
	<tr id="r_Continent">
		<td><span id="elh_filter_Continent"><?php echo $filter->Continent->FldCaption() ?></span></td>
		<td<?php echo $filter->Continent->CellAttributes() ?>>
<span id="el_filter_Continent" class="control-group">
<span<?php echo $filter->Continent->ViewAttributes() ?>>
<?php echo $filter->Continent->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Environment->Visible) { // Environment ?>
	<tr id="r_Environment">
		<td><span id="elh_filter_Environment"><?php echo $filter->Environment->FldCaption() ?></span></td>
		<td<?php echo $filter->Environment->CellAttributes() ?>>
<span id="el_filter_Environment" class="control-group">
<span<?php echo $filter->Environment->ViewAttributes() ?>>
<?php echo $filter->Environment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Season->Visible) { // Season ?>
	<tr id="r_Season">
		<td><span id="elh_filter_Season"><?php echo $filter->Season->FldCaption() ?></span></td>
		<td<?php echo $filter->Season->CellAttributes() ?>>
<span id="el_filter_Season" class="control-group">
<span<?php echo $filter->Season->ViewAttributes() ?>>
<?php echo $filter->Season->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Weather->Visible) { // Weather ?>
	<tr id="r_Weather">
		<td><span id="elh_filter_Weather"><?php echo $filter->Weather->FldCaption() ?></span></td>
		<td<?php echo $filter->Weather->CellAttributes() ?>>
<span id="el_filter_Weather" class="control-group">
<span<?php echo $filter->Weather->ViewAttributes() ?>>
<?php echo $filter->Weather->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Age->Visible) { // Age ?>
	<tr id="r_Age">
		<td><span id="elh_filter_Age"><?php echo $filter->Age->FldCaption() ?></span></td>
		<td<?php echo $filter->Age->CellAttributes() ?>>
<span id="el_filter_Age" class="control-group">
<span<?php echo $filter->Age->ViewAttributes() ?>>
<?php echo $filter->Age->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Activities->Visible) { // Activities ?>
	<tr id="r_Activities">
		<td><span id="elh_filter_Activities"><?php echo $filter->Activities->FldCaption() ?></span></td>
		<td<?php echo $filter->Activities->CellAttributes() ?>>
<span id="el_filter_Activities" class="control-group">
<span<?php echo $filter->Activities->ViewAttributes() ?>>
<?php echo $filter->Activities->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->ActivityPrice->Visible) { // ActivityPrice ?>
	<tr id="r_ActivityPrice">
		<td><span id="elh_filter_ActivityPrice"><?php echo $filter->ActivityPrice->FldCaption() ?></span></td>
		<td<?php echo $filter->ActivityPrice->CellAttributes() ?>>
<span id="el_filter_ActivityPrice" class="control-group">
<span<?php echo $filter->ActivityPrice->ViewAttributes() ?>>
<?php echo $filter->ActivityPrice->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->Country->Visible) { // Country ?>
	<tr id="r_Country">
		<td><span id="elh_filter_Country"><?php echo $filter->Country->FldCaption() ?></span></td>
		<td<?php echo $filter->Country->CellAttributes() ?>>
<span id="el_filter_Country" class="control-group">
<span<?php echo $filter->Country->ViewAttributes() ?>>
<?php echo $filter->Country->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($filter->City->Visible) { // City ?>
	<tr id="r_City">
		<td><span id="elh_filter_City"><?php echo $filter->City->FldCaption() ?></span></td>
		<td<?php echo $filter->City->CellAttributes() ?>>
<span id="el_filter_City" class="control-group">
<span<?php echo $filter->City->ViewAttributes() ?>>
<?php echo $filter->City->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
ffilterview.Init();
</script>
<?php
$filter_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$filter_view->Page_Terminate();
?>
