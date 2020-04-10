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

$filter_add = NULL; // Initialize page object first

class cfilter_add extends cfilter {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'filter';

	// Page object name
	var $PageObjName = 'filter_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["FilterID"] != "") {
				$this->FilterID->setQueryStringValue($_GET["FilterID"]);
				$this->setKey("FilterID", $this->FilterID->CurrentValue); // Set up key
			} else {
				$this->setKey("FilterID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("filterlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "filterview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Date1->CurrentValue = NULL;
		$this->Date1->OldValue = $this->Date1->CurrentValue;
		$this->Date2->CurrentValue = NULL;
		$this->Date2->OldValue = $this->Date2->CurrentValue;
		$this->Continent->CurrentValue = NULL;
		$this->Continent->OldValue = $this->Continent->CurrentValue;
		$this->Environment->CurrentValue = NULL;
		$this->Environment->OldValue = $this->Environment->CurrentValue;
		$this->Season->CurrentValue = NULL;
		$this->Season->OldValue = $this->Season->CurrentValue;
		$this->Weather->CurrentValue = NULL;
		$this->Weather->OldValue = $this->Weather->CurrentValue;
		$this->Age->CurrentValue = NULL;
		$this->Age->OldValue = $this->Age->CurrentValue;
		$this->Activities->CurrentValue = NULL;
		$this->Activities->OldValue = $this->Activities->CurrentValue;
		$this->ActivityPrice->CurrentValue = NULL;
		$this->ActivityPrice->OldValue = $this->ActivityPrice->CurrentValue;
		$this->Country->CurrentValue = NULL;
		$this->Country->OldValue = $this->Country->CurrentValue;
		$this->City->CurrentValue = NULL;
		$this->City->OldValue = $this->City->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Date1->FldIsDetailKey) {
			$this->Date1->setFormValue($objForm->GetValue("x_Date1"));
			$this->Date1->CurrentValue = ew_UnFormatDateTime($this->Date1->CurrentValue, 5);
		}
		if (!$this->Date2->FldIsDetailKey) {
			$this->Date2->setFormValue($objForm->GetValue("x_Date2"));
			$this->Date2->CurrentValue = ew_UnFormatDateTime($this->Date2->CurrentValue, 5);
		}
		if (!$this->Continent->FldIsDetailKey) {
			$this->Continent->setFormValue($objForm->GetValue("x_Continent"));
		}
		if (!$this->Environment->FldIsDetailKey) {
			$this->Environment->setFormValue($objForm->GetValue("x_Environment"));
		}
		if (!$this->Season->FldIsDetailKey) {
			$this->Season->setFormValue($objForm->GetValue("x_Season"));
		}
		if (!$this->Weather->FldIsDetailKey) {
			$this->Weather->setFormValue($objForm->GetValue("x_Weather"));
		}
		if (!$this->Age->FldIsDetailKey) {
			$this->Age->setFormValue($objForm->GetValue("x_Age"));
		}
		if (!$this->Activities->FldIsDetailKey) {
			$this->Activities->setFormValue($objForm->GetValue("x_Activities"));
		}
		if (!$this->ActivityPrice->FldIsDetailKey) {
			$this->ActivityPrice->setFormValue($objForm->GetValue("x_ActivityPrice"));
		}
		if (!$this->Country->FldIsDetailKey) {
			$this->Country->setFormValue($objForm->GetValue("x_Country"));
		}
		if (!$this->City->FldIsDetailKey) {
			$this->City->setFormValue($objForm->GetValue("x_City"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Date1->CurrentValue = $this->Date1->FormValue;
		$this->Date1->CurrentValue = ew_UnFormatDateTime($this->Date1->CurrentValue, 5);
		$this->Date2->CurrentValue = $this->Date2->FormValue;
		$this->Date2->CurrentValue = ew_UnFormatDateTime($this->Date2->CurrentValue, 5);
		$this->Continent->CurrentValue = $this->Continent->FormValue;
		$this->Environment->CurrentValue = $this->Environment->FormValue;
		$this->Season->CurrentValue = $this->Season->FormValue;
		$this->Weather->CurrentValue = $this->Weather->FormValue;
		$this->Age->CurrentValue = $this->Age->FormValue;
		$this->Activities->CurrentValue = $this->Activities->FormValue;
		$this->ActivityPrice->CurrentValue = $this->ActivityPrice->FormValue;
		$this->Country->CurrentValue = $this->Country->FormValue;
		$this->City->CurrentValue = $this->City->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("FilterID")) <> "")
			$this->FilterID->CurrentValue = $this->getKey("FilterID"); // FilterID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Date1
			$this->Date1->EditCustomAttributes = "";
			$this->Date1->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Date1->CurrentValue, 5));
			$this->Date1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Date1->FldCaption()));

			// Date2
			$this->Date2->EditCustomAttributes = "";
			$this->Date2->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Date2->CurrentValue, 5));
			$this->Date2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Date2->FldCaption()));

			// Continent
			$this->Continent->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Continent->FldTagValue(1), $this->Continent->FldTagCaption(1) <> "" ? $this->Continent->FldTagCaption(1) : $this->Continent->FldTagValue(1));
			$arwrk[] = array($this->Continent->FldTagValue(2), $this->Continent->FldTagCaption(2) <> "" ? $this->Continent->FldTagCaption(2) : $this->Continent->FldTagValue(2));
			$arwrk[] = array($this->Continent->FldTagValue(3), $this->Continent->FldTagCaption(3) <> "" ? $this->Continent->FldTagCaption(3) : $this->Continent->FldTagValue(3));
			$arwrk[] = array($this->Continent->FldTagValue(4), $this->Continent->FldTagCaption(4) <> "" ? $this->Continent->FldTagCaption(4) : $this->Continent->FldTagValue(4));
			$arwrk[] = array($this->Continent->FldTagValue(5), $this->Continent->FldTagCaption(5) <> "" ? $this->Continent->FldTagCaption(5) : $this->Continent->FldTagValue(5));
			$arwrk[] = array($this->Continent->FldTagValue(6), $this->Continent->FldTagCaption(6) <> "" ? $this->Continent->FldTagCaption(6) : $this->Continent->FldTagValue(6));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Continent->EditValue = $arwrk;

			// Environment
			$this->Environment->EditCustomAttributes = "";
			$this->Environment->EditValue = $this->Environment->CurrentValue;
			$this->Environment->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Environment->FldCaption()));

			// Season
			$this->Season->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Season->FldTagValue(1), $this->Season->FldTagCaption(1) <> "" ? $this->Season->FldTagCaption(1) : $this->Season->FldTagValue(1));
			$arwrk[] = array($this->Season->FldTagValue(2), $this->Season->FldTagCaption(2) <> "" ? $this->Season->FldTagCaption(2) : $this->Season->FldTagValue(2));
			$arwrk[] = array($this->Season->FldTagValue(3), $this->Season->FldTagCaption(3) <> "" ? $this->Season->FldTagCaption(3) : $this->Season->FldTagValue(3));
			$arwrk[] = array($this->Season->FldTagValue(4), $this->Season->FldTagCaption(4) <> "" ? $this->Season->FldTagCaption(4) : $this->Season->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Season->EditValue = $arwrk;

			// Weather
			$this->Weather->EditCustomAttributes = "";
			$this->Weather->EditValue = $this->Weather->CurrentValue;
			$this->Weather->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Weather->FldCaption()));

			// Age
			$this->Age->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Age->FldTagValue(1), $this->Age->FldTagCaption(1) <> "" ? $this->Age->FldTagCaption(1) : $this->Age->FldTagValue(1));
			$arwrk[] = array($this->Age->FldTagValue(2), $this->Age->FldTagCaption(2) <> "" ? $this->Age->FldTagCaption(2) : $this->Age->FldTagValue(2));
			$arwrk[] = array($this->Age->FldTagValue(3), $this->Age->FldTagCaption(3) <> "" ? $this->Age->FldTagCaption(3) : $this->Age->FldTagValue(3));
			$arwrk[] = array($this->Age->FldTagValue(4), $this->Age->FldTagCaption(4) <> "" ? $this->Age->FldTagCaption(4) : $this->Age->FldTagValue(4));
			$arwrk[] = array($this->Age->FldTagValue(5), $this->Age->FldTagCaption(5) <> "" ? $this->Age->FldTagCaption(5) : $this->Age->FldTagValue(5));
			$arwrk[] = array($this->Age->FldTagValue(6), $this->Age->FldTagCaption(6) <> "" ? $this->Age->FldTagCaption(6) : $this->Age->FldTagValue(6));
			$arwrk[] = array($this->Age->FldTagValue(7), $this->Age->FldTagCaption(7) <> "" ? $this->Age->FldTagCaption(7) : $this->Age->FldTagValue(7));
			$arwrk[] = array($this->Age->FldTagValue(8), $this->Age->FldTagCaption(8) <> "" ? $this->Age->FldTagCaption(8) : $this->Age->FldTagValue(8));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Age->EditValue = $arwrk;

			// Activities
			$this->Activities->EditCustomAttributes = "";
			$this->Activities->EditValue = $this->Activities->CurrentValue;
			$this->Activities->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Activities->FldCaption()));

			// ActivityPrice
			$this->ActivityPrice->EditCustomAttributes = "";
			$this->ActivityPrice->EditValue = ew_HtmlEncode($this->ActivityPrice->CurrentValue);
			$this->ActivityPrice->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ActivityPrice->FldCaption()));
			if (strval($this->ActivityPrice->EditValue) <> "" && is_numeric($this->ActivityPrice->EditValue)) $this->ActivityPrice->EditValue = ew_FormatNumber($this->ActivityPrice->EditValue, -2, -1, -2, 0);

			// Country
			$this->Country->EditCustomAttributes = "";
			$this->Country->EditValue = ew_HtmlEncode($this->Country->CurrentValue);
			$this->Country->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Country->FldCaption()));

			// City
			$this->City->EditCustomAttributes = "";
			$this->City->EditValue = ew_HtmlEncode($this->City->CurrentValue);
			$this->City->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->City->FldCaption()));

			// Edit refer script
			// Date1

			$this->Date1->HrefValue = "";

			// Date2
			$this->Date2->HrefValue = "";

			// Continent
			$this->Continent->HrefValue = "";

			// Environment
			$this->Environment->HrefValue = "";

			// Season
			$this->Season->HrefValue = "";

			// Weather
			$this->Weather->HrefValue = "";

			// Age
			$this->Age->HrefValue = "";

			// Activities
			$this->Activities->HrefValue = "";

			// ActivityPrice
			$this->ActivityPrice->HrefValue = "";

			// Country
			$this->Country->HrefValue = "";

			// City
			$this->City->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Date1->FldIsDetailKey && !is_null($this->Date1->FormValue) && $this->Date1->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Date1->FldCaption());
		}
		if (!ew_CheckDate($this->Date1->FormValue)) {
			ew_AddMessage($gsFormError, $this->Date1->FldErrMsg());
		}
		if (!$this->Date2->FldIsDetailKey && !is_null($this->Date2->FormValue) && $this->Date2->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Date2->FldCaption());
		}
		if (!ew_CheckDate($this->Date2->FormValue)) {
			ew_AddMessage($gsFormError, $this->Date2->FldErrMsg());
		}
		if (!$this->Continent->FldIsDetailKey && !is_null($this->Continent->FormValue) && $this->Continent->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Continent->FldCaption());
		}
		if (!$this->Environment->FldIsDetailKey && !is_null($this->Environment->FormValue) && $this->Environment->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Environment->FldCaption());
		}
		if (!$this->Season->FldIsDetailKey && !is_null($this->Season->FormValue) && $this->Season->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Season->FldCaption());
		}
		if (!$this->Weather->FldIsDetailKey && !is_null($this->Weather->FormValue) && $this->Weather->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Weather->FldCaption());
		}
		if (!$this->Age->FldIsDetailKey && !is_null($this->Age->FormValue) && $this->Age->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Age->FldCaption());
		}
		if (!$this->Activities->FldIsDetailKey && !is_null($this->Activities->FormValue) && $this->Activities->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Activities->FldCaption());
		}
		if (!$this->ActivityPrice->FldIsDetailKey && !is_null($this->ActivityPrice->FormValue) && $this->ActivityPrice->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ActivityPrice->FldCaption());
		}
		if (!ew_CheckNumber($this->ActivityPrice->FormValue)) {
			ew_AddMessage($gsFormError, $this->ActivityPrice->FldErrMsg());
		}
		if (!$this->Country->FldIsDetailKey && !is_null($this->Country->FormValue) && $this->Country->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Country->FldCaption());
		}
		if (!$this->City->FldIsDetailKey && !is_null($this->City->FormValue) && $this->City->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->City->FldCaption());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Date1
		$this->Date1->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Date1->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// Date2
		$this->Date2->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Date2->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// Continent
		$this->Continent->SetDbValueDef($rsnew, $this->Continent->CurrentValue, "", FALSE);

		// Environment
		$this->Environment->SetDbValueDef($rsnew, $this->Environment->CurrentValue, "", FALSE);

		// Season
		$this->Season->SetDbValueDef($rsnew, $this->Season->CurrentValue, "", FALSE);

		// Weather
		$this->Weather->SetDbValueDef($rsnew, $this->Weather->CurrentValue, "", FALSE);

		// Age
		$this->Age->SetDbValueDef($rsnew, $this->Age->CurrentValue, "", FALSE);

		// Activities
		$this->Activities->SetDbValueDef($rsnew, $this->Activities->CurrentValue, "", FALSE);

		// ActivityPrice
		$this->ActivityPrice->SetDbValueDef($rsnew, $this->ActivityPrice->CurrentValue, 0, FALSE);

		// Country
		$this->Country->SetDbValueDef($rsnew, $this->Country->CurrentValue, "", FALSE);

		// City
		$this->City->SetDbValueDef($rsnew, $this->City->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->FilterID->setDbValue($conn->Insert_ID());
			$rsnew['FilterID'] = $this->FilterID->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "filterlist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($filter_add)) $filter_add = new cfilter_add();

// Page init
$filter_add->Page_Init();

// Page main
$filter_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$filter_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var filter_add = new ew_Page("filter_add");
filter_add.PageID = "add"; // Page ID
var EW_PAGE_ID = filter_add.PageID; // For backward compatibility

// Form object
var ffilteradd = new ew_Form("ffilteradd");

// Validate form
ffilteradd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Date1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Date1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Date1");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($filter->Date1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Date2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Date2->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Date2");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($filter->Date2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Continent");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Continent->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Environment");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Environment->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Season");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Season->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Weather");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Weather->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Age");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Age->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Activities");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Activities->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ActivityPrice");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->ActivityPrice->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ActivityPrice");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($filter->ActivityPrice->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Country");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->Country->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_City");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($filter->City->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ffilteradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffilteradd.ValidateRequired = true;
<?php } else { ?>
ffilteradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $filter_add->ShowPageHeader(); ?>
<?php
$filter_add->ShowMessage();
?>
<form name="ffilteradd" id="ffilteradd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="filter">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_filteradd" class="table table-bordered table-striped">
<?php if ($filter->Date1->Visible) { // Date1 ?>
	<tr id="r_Date1">
		<td><span id="elh_filter_Date1"><?php echo $filter->Date1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Date1->CellAttributes() ?>>
<span id="el_filter_Date1" class="control-group">
<input type="text" data-field="x_Date1" name="x_Date1" id="x_Date1" placeholder="<?php echo $filter->Date1->PlaceHolder ?>" value="<?php echo $filter->Date1->EditValue ?>"<?php echo $filter->Date1->EditAttributes() ?>>
</span>
<?php echo $filter->Date1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Date2->Visible) { // Date2 ?>
	<tr id="r_Date2">
		<td><span id="elh_filter_Date2"><?php echo $filter->Date2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Date2->CellAttributes() ?>>
<span id="el_filter_Date2" class="control-group">
<input type="text" data-field="x_Date2" name="x_Date2" id="x_Date2" placeholder="<?php echo $filter->Date2->PlaceHolder ?>" value="<?php echo $filter->Date2->EditValue ?>"<?php echo $filter->Date2->EditAttributes() ?>>
</span>
<?php echo $filter->Date2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Continent->Visible) { // Continent ?>
	<tr id="r_Continent">
		<td><span id="elh_filter_Continent"><?php echo $filter->Continent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Continent->CellAttributes() ?>>
<span id="el_filter_Continent" class="control-group">
<select data-field="x_Continent" id="x_Continent" name="x_Continent"<?php echo $filter->Continent->EditAttributes() ?>>
<?php
if (is_array($filter->Continent->EditValue)) {
	$arwrk = $filter->Continent->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($filter->Continent->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $filter->Continent->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Environment->Visible) { // Environment ?>
	<tr id="r_Environment">
		<td><span id="elh_filter_Environment"><?php echo $filter->Environment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Environment->CellAttributes() ?>>
<span id="el_filter_Environment" class="control-group">
<textarea data-field="x_Environment" name="x_Environment" id="x_Environment" cols="70" rows="8" placeholder="<?php echo $filter->Environment->PlaceHolder ?>"<?php echo $filter->Environment->EditAttributes() ?>><?php echo $filter->Environment->EditValue ?></textarea>
</span>
<?php echo $filter->Environment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Season->Visible) { // Season ?>
	<tr id="r_Season">
		<td><span id="elh_filter_Season"><?php echo $filter->Season->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Season->CellAttributes() ?>>
<span id="el_filter_Season" class="control-group">
<select data-field="x_Season" id="x_Season" name="x_Season"<?php echo $filter->Season->EditAttributes() ?>>
<?php
if (is_array($filter->Season->EditValue)) {
	$arwrk = $filter->Season->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($filter->Season->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $filter->Season->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Weather->Visible) { // Weather ?>
	<tr id="r_Weather">
		<td><span id="elh_filter_Weather"><?php echo $filter->Weather->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Weather->CellAttributes() ?>>
<span id="el_filter_Weather" class="control-group">
<textarea data-field="x_Weather" name="x_Weather" id="x_Weather" cols="70" rows="8" placeholder="<?php echo $filter->Weather->PlaceHolder ?>"<?php echo $filter->Weather->EditAttributes() ?>><?php echo $filter->Weather->EditValue ?></textarea>
</span>
<?php echo $filter->Weather->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Age->Visible) { // Age ?>
	<tr id="r_Age">
		<td><span id="elh_filter_Age"><?php echo $filter->Age->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Age->CellAttributes() ?>>
<span id="el_filter_Age" class="control-group">
<select data-field="x_Age" id="x_Age" name="x_Age"<?php echo $filter->Age->EditAttributes() ?>>
<?php
if (is_array($filter->Age->EditValue)) {
	$arwrk = $filter->Age->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($filter->Age->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $filter->Age->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Activities->Visible) { // Activities ?>
	<tr id="r_Activities">
		<td><span id="elh_filter_Activities"><?php echo $filter->Activities->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Activities->CellAttributes() ?>>
<span id="el_filter_Activities" class="control-group">
<textarea data-field="x_Activities" name="x_Activities" id="x_Activities" cols="70" rows="8" placeholder="<?php echo $filter->Activities->PlaceHolder ?>"<?php echo $filter->Activities->EditAttributes() ?>><?php echo $filter->Activities->EditValue ?></textarea>
</span>
<?php echo $filter->Activities->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->ActivityPrice->Visible) { // ActivityPrice ?>
	<tr id="r_ActivityPrice">
		<td><span id="elh_filter_ActivityPrice"><?php echo $filter->ActivityPrice->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->ActivityPrice->CellAttributes() ?>>
<span id="el_filter_ActivityPrice" class="control-group">
<input type="text" data-field="x_ActivityPrice" name="x_ActivityPrice" id="x_ActivityPrice" size="30" placeholder="<?php echo $filter->ActivityPrice->PlaceHolder ?>" value="<?php echo $filter->ActivityPrice->EditValue ?>"<?php echo $filter->ActivityPrice->EditAttributes() ?>>
</span>
<?php echo $filter->ActivityPrice->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->Country->Visible) { // Country ?>
	<tr id="r_Country">
		<td><span id="elh_filter_Country"><?php echo $filter->Country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->Country->CellAttributes() ?>>
<span id="el_filter_Country" class="control-group">
<input type="text" data-field="x_Country" name="x_Country" id="x_Country" size="70" maxlength="255" placeholder="<?php echo $filter->Country->PlaceHolder ?>" value="<?php echo $filter->Country->EditValue ?>"<?php echo $filter->Country->EditAttributes() ?>>
</span>
<?php echo $filter->Country->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($filter->City->Visible) { // City ?>
	<tr id="r_City">
		<td><span id="elh_filter_City"><?php echo $filter->City->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $filter->City->CellAttributes() ?>>
<span id="el_filter_City" class="control-group">
<input type="text" data-field="x_City" name="x_City" id="x_City" size="70" maxlength="255" placeholder="<?php echo $filter->City->PlaceHolder ?>" value="<?php echo $filter->City->EditValue ?>"<?php echo $filter->City->EditAttributes() ?>>
</span>
<?php echo $filter->City->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ffilteradd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$filter_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$filter_add->Page_Terminate();
?>
