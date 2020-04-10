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

$dialogue_rules_add = NULL; // Initialize page object first

class cdialogue_rules_add extends cdialogue_rules {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'dialogue_rules';

	// Page object name
	var $PageObjName = 'dialogue_rules_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
			if (@$_GET["DrID"] != "") {
				$this->DrID->setQueryStringValue($_GET["DrID"]);
				$this->setKey("DrID", $this->DrID->CurrentValue); // Set up key
			} else {
				$this->setKey("DrID", ""); // Clear key
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
					$this->Page_Terminate("dialogue_ruleslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "dialogue_rulesview.php")
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
		$this->DrTrigger->CurrentValue = NULL;
		$this->DrTrigger->OldValue = $this->DrTrigger->CurrentValue;
		$this->DrResponse->CurrentValue = NULL;
		$this->DrResponse->OldValue = $this->DrResponse->CurrentValue;
		$this->DrType->CurrentValue = "holiday";
		$this->DrValidationResponse->CurrentValue = NULL;
		$this->DrValidationResponse->OldValue = $this->DrValidationResponse->CurrentValue;
		$this->DrStage->CurrentValue = NULL;
		$this->DrStage->OldValue = $this->DrStage->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->DrTrigger->FldIsDetailKey) {
			$this->DrTrigger->setFormValue($objForm->GetValue("x_DrTrigger"));
		}
		if (!$this->DrResponse->FldIsDetailKey) {
			$this->DrResponse->setFormValue($objForm->GetValue("x_DrResponse"));
		}
		if (!$this->DrType->FldIsDetailKey) {
			$this->DrType->setFormValue($objForm->GetValue("x_DrType"));
		}
		if (!$this->DrValidationResponse->FldIsDetailKey) {
			$this->DrValidationResponse->setFormValue($objForm->GetValue("x_DrValidationResponse"));
		}
		if (!$this->DrStage->FldIsDetailKey) {
			$this->DrStage->setFormValue($objForm->GetValue("x_DrStage"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->DrTrigger->CurrentValue = $this->DrTrigger->FormValue;
		$this->DrResponse->CurrentValue = $this->DrResponse->FormValue;
		$this->DrType->CurrentValue = $this->DrType->FormValue;
		$this->DrValidationResponse->CurrentValue = $this->DrValidationResponse->FormValue;
		$this->DrStage->CurrentValue = $this->DrStage->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("DrID")) <> "")
			$this->DrID->CurrentValue = $this->getKey("DrID"); // DrID
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// DrTrigger
			$this->DrTrigger->EditCustomAttributes = "";
			$this->DrTrigger->EditValue = $this->DrTrigger->CurrentValue;
			$this->DrTrigger->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->DrTrigger->FldCaption()));

			// DrResponse
			$this->DrResponse->EditCustomAttributes = "";
			$this->DrResponse->EditValue = $this->DrResponse->CurrentValue;
			$this->DrResponse->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->DrResponse->FldCaption()));

			// DrType
			$this->DrType->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->DrType->FldTagValue(1), $this->DrType->FldTagCaption(1) <> "" ? $this->DrType->FldTagCaption(1) : $this->DrType->FldTagValue(1));
			$arwrk[] = array($this->DrType->FldTagValue(2), $this->DrType->FldTagCaption(2) <> "" ? $this->DrType->FldTagCaption(2) : $this->DrType->FldTagValue(2));
			$arwrk[] = array($this->DrType->FldTagValue(3), $this->DrType->FldTagCaption(3) <> "" ? $this->DrType->FldTagCaption(3) : $this->DrType->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->DrType->EditValue = $arwrk;

			// DrValidationResponse
			$this->DrValidationResponse->EditCustomAttributes = "";
			$this->DrValidationResponse->EditValue = $this->DrValidationResponse->CurrentValue;
			$this->DrValidationResponse->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->DrValidationResponse->FldCaption()));

			// DrStage
			$this->DrStage->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->DrStage->FldTagValue(1), $this->DrStage->FldTagCaption(1) <> "" ? $this->DrStage->FldTagCaption(1) : $this->DrStage->FldTagValue(1));
			$arwrk[] = array($this->DrStage->FldTagValue(2), $this->DrStage->FldTagCaption(2) <> "" ? $this->DrStage->FldTagCaption(2) : $this->DrStage->FldTagValue(2));
			$arwrk[] = array($this->DrStage->FldTagValue(3), $this->DrStage->FldTagCaption(3) <> "" ? $this->DrStage->FldTagCaption(3) : $this->DrStage->FldTagValue(3));
			$arwrk[] = array($this->DrStage->FldTagValue(4), $this->DrStage->FldTagCaption(4) <> "" ? $this->DrStage->FldTagCaption(4) : $this->DrStage->FldTagValue(4));
			$arwrk[] = array($this->DrStage->FldTagValue(5), $this->DrStage->FldTagCaption(5) <> "" ? $this->DrStage->FldTagCaption(5) : $this->DrStage->FldTagValue(5));
			$arwrk[] = array($this->DrStage->FldTagValue(6), $this->DrStage->FldTagCaption(6) <> "" ? $this->DrStage->FldTagCaption(6) : $this->DrStage->FldTagValue(6));
			$arwrk[] = array($this->DrStage->FldTagValue(7), $this->DrStage->FldTagCaption(7) <> "" ? $this->DrStage->FldTagCaption(7) : $this->DrStage->FldTagValue(7));
			$arwrk[] = array($this->DrStage->FldTagValue(8), $this->DrStage->FldTagCaption(8) <> "" ? $this->DrStage->FldTagCaption(8) : $this->DrStage->FldTagValue(8));
			$arwrk[] = array($this->DrStage->FldTagValue(9), $this->DrStage->FldTagCaption(9) <> "" ? $this->DrStage->FldTagCaption(9) : $this->DrStage->FldTagValue(9));
			$arwrk[] = array($this->DrStage->FldTagValue(10), $this->DrStage->FldTagCaption(10) <> "" ? $this->DrStage->FldTagCaption(10) : $this->DrStage->FldTagValue(10));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->DrStage->EditValue = $arwrk;

			// Edit refer script
			// DrTrigger

			$this->DrTrigger->HrefValue = "";

			// DrResponse
			$this->DrResponse->HrefValue = "";

			// DrType
			$this->DrType->HrefValue = "";

			// DrValidationResponse
			$this->DrValidationResponse->HrefValue = "";

			// DrStage
			$this->DrStage->HrefValue = "";
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
		if (!$this->DrResponse->FldIsDetailKey && !is_null($this->DrResponse->FormValue) && $this->DrResponse->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DrResponse->FldCaption());
		}
		if (!$this->DrType->FldIsDetailKey && !is_null($this->DrType->FormValue) && $this->DrType->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DrType->FldCaption());
		}
		if (!$this->DrStage->FldIsDetailKey && !is_null($this->DrStage->FormValue) && $this->DrStage->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DrStage->FldCaption());
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

		// DrTrigger
		$this->DrTrigger->SetDbValueDef($rsnew, $this->DrTrigger->CurrentValue, NULL, FALSE);

		// DrResponse
		$this->DrResponse->SetDbValueDef($rsnew, $this->DrResponse->CurrentValue, "", FALSE);

		// DrType
		$this->DrType->SetDbValueDef($rsnew, $this->DrType->CurrentValue, "", strval($this->DrType->CurrentValue) == "");

		// DrValidationResponse
		$this->DrValidationResponse->SetDbValueDef($rsnew, $this->DrValidationResponse->CurrentValue, NULL, FALSE);

		// DrStage
		$this->DrStage->SetDbValueDef($rsnew, $this->DrStage->CurrentValue, 0, FALSE);

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
			$this->DrID->setDbValue($conn->Insert_ID());
			$rsnew['DrID'] = $this->DrID->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "dialogue_ruleslist.php", $this->TableVar);
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
if (!isset($dialogue_rules_add)) $dialogue_rules_add = new cdialogue_rules_add();

// Page init
$dialogue_rules_add->Page_Init();

// Page main
$dialogue_rules_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dialogue_rules_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dialogue_rules_add = new ew_Page("dialogue_rules_add");
dialogue_rules_add.PageID = "add"; // Page ID
var EW_PAGE_ID = dialogue_rules_add.PageID; // For backward compatibility

// Form object
var fdialogue_rulesadd = new ew_Form("fdialogue_rulesadd");

// Validate form
fdialogue_rulesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_DrResponse");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($dialogue_rules->DrResponse->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DrType");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($dialogue_rules->DrType->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DrStage");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($dialogue_rules->DrStage->FldCaption()) ?>");

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
fdialogue_rulesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdialogue_rulesadd.ValidateRequired = true;
<?php } else { ?>
fdialogue_rulesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $dialogue_rules_add->ShowPageHeader(); ?>
<?php
$dialogue_rules_add->ShowMessage();
?>
<form name="fdialogue_rulesadd" id="fdialogue_rulesadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="dialogue_rules">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_dialogue_rulesadd" class="table table-bordered table-striped">
<?php if ($dialogue_rules->DrTrigger->Visible) { // DrTrigger ?>
	<tr id="r_DrTrigger">
		<td><span id="elh_dialogue_rules_DrTrigger"><?php echo $dialogue_rules->DrTrigger->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrTrigger->CellAttributes() ?>>
<span id="el_dialogue_rules_DrTrigger" class="control-group">
<textarea data-field="x_DrTrigger" name="x_DrTrigger" id="x_DrTrigger" cols="70" rows="8" placeholder="<?php echo $dialogue_rules->DrTrigger->PlaceHolder ?>"<?php echo $dialogue_rules->DrTrigger->EditAttributes() ?>><?php echo $dialogue_rules->DrTrigger->EditValue ?></textarea>
</span>
<?php echo $dialogue_rules->DrTrigger->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrResponse->Visible) { // DrResponse ?>
	<tr id="r_DrResponse">
		<td><span id="elh_dialogue_rules_DrResponse"><?php echo $dialogue_rules->DrResponse->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $dialogue_rules->DrResponse->CellAttributes() ?>>
<span id="el_dialogue_rules_DrResponse" class="control-group">
<textarea data-field="x_DrResponse" name="x_DrResponse" id="x_DrResponse" cols="70" rows="8" placeholder="<?php echo $dialogue_rules->DrResponse->PlaceHolder ?>"<?php echo $dialogue_rules->DrResponse->EditAttributes() ?>><?php echo $dialogue_rules->DrResponse->EditValue ?></textarea>
</span>
<?php echo $dialogue_rules->DrResponse->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrType->Visible) { // DrType ?>
	<tr id="r_DrType">
		<td><span id="elh_dialogue_rules_DrType"><?php echo $dialogue_rules->DrType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $dialogue_rules->DrType->CellAttributes() ?>>
<span id="el_dialogue_rules_DrType" class="control-group">
<select data-field="x_DrType" id="x_DrType" name="x_DrType"<?php echo $dialogue_rules->DrType->EditAttributes() ?>>
<?php
if (is_array($dialogue_rules->DrType->EditValue)) {
	$arwrk = $dialogue_rules->DrType->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dialogue_rules->DrType->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $dialogue_rules->DrType->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrValidationResponse->Visible) { // DrValidationResponse ?>
	<tr id="r_DrValidationResponse">
		<td><span id="elh_dialogue_rules_DrValidationResponse"><?php echo $dialogue_rules->DrValidationResponse->FldCaption() ?></span></td>
		<td<?php echo $dialogue_rules->DrValidationResponse->CellAttributes() ?>>
<span id="el_dialogue_rules_DrValidationResponse" class="control-group">
<textarea data-field="x_DrValidationResponse" name="x_DrValidationResponse" id="x_DrValidationResponse" cols="70" rows="80" placeholder="<?php echo $dialogue_rules->DrValidationResponse->PlaceHolder ?>"<?php echo $dialogue_rules->DrValidationResponse->EditAttributes() ?>><?php echo $dialogue_rules->DrValidationResponse->EditValue ?></textarea>
</span>
<?php echo $dialogue_rules->DrValidationResponse->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($dialogue_rules->DrStage->Visible) { // DrStage ?>
	<tr id="r_DrStage">
		<td><span id="elh_dialogue_rules_DrStage"><?php echo $dialogue_rules->DrStage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $dialogue_rules->DrStage->CellAttributes() ?>>
<span id="el_dialogue_rules_DrStage" class="control-group">
<select data-field="x_DrStage" id="x_DrStage" name="x_DrStage"<?php echo $dialogue_rules->DrStage->EditAttributes() ?>>
<?php
if (is_array($dialogue_rules->DrStage->EditValue)) {
	$arwrk = $dialogue_rules->DrStage->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dialogue_rules->DrStage->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $dialogue_rules->DrStage->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fdialogue_rulesadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$dialogue_rules_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dialogue_rules_add->Page_Terminate();
?>
