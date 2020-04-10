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

$replies_add = NULL; // Initialize page object first

class creplies_add extends creplies {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'replies';

	// Page object name
	var $PageObjName = 'replies_add';

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

		// Table object (replies)
		if (!isset($GLOBALS["replies"])) {
			$GLOBALS["replies"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["replies"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'replies', TRUE);

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
			if (@$_GET["ReplyID"] != "") {
				$this->ReplyID->setQueryStringValue($_GET["ReplyID"]);
				$this->setKey("ReplyID", $this->ReplyID->CurrentValue); // Set up key
			} else {
				$this->setKey("ReplyID", ""); // Clear key
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
					$this->Page_Terminate("replieslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "repliesview.php")
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
		$this->ReplyTrigger->CurrentValue = NULL;
		$this->ReplyTrigger->OldValue = $this->ReplyTrigger->CurrentValue;
		$this->ReplyResponse->CurrentValue = NULL;
		$this->ReplyResponse->OldValue = $this->ReplyResponse->CurrentValue;
		$this->ReplyType->CurrentValue = "holiday";
		$this->ReplyValidationResponse->CurrentValue = NULL;
		$this->ReplyValidationResponse->OldValue = $this->ReplyValidationResponse->CurrentValue;
		$this->ReplyStage->CurrentValue = NULL;
		$this->ReplyStage->OldValue = $this->ReplyStage->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ReplyTrigger->FldIsDetailKey) {
			$this->ReplyTrigger->setFormValue($objForm->GetValue("x_ReplyTrigger"));
		}
		if (!$this->ReplyResponse->FldIsDetailKey) {
			$this->ReplyResponse->setFormValue($objForm->GetValue("x_ReplyResponse"));
		}
		if (!$this->ReplyType->FldIsDetailKey) {
			$this->ReplyType->setFormValue($objForm->GetValue("x_ReplyType"));
		}
		if (!$this->ReplyValidationResponse->FldIsDetailKey) {
			$this->ReplyValidationResponse->setFormValue($objForm->GetValue("x_ReplyValidationResponse"));
		}
		if (!$this->ReplyStage->FldIsDetailKey) {
			$this->ReplyStage->setFormValue($objForm->GetValue("x_ReplyStage"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ReplyTrigger->CurrentValue = $this->ReplyTrigger->FormValue;
		$this->ReplyResponse->CurrentValue = $this->ReplyResponse->FormValue;
		$this->ReplyType->CurrentValue = $this->ReplyType->FormValue;
		$this->ReplyValidationResponse->CurrentValue = $this->ReplyValidationResponse->FormValue;
		$this->ReplyStage->CurrentValue = $this->ReplyStage->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ReplyID")) <> "")
			$this->ReplyID->CurrentValue = $this->getKey("ReplyID"); // ReplyID
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ReplyTrigger
			$this->ReplyTrigger->EditCustomAttributes = "";
			$this->ReplyTrigger->EditValue = $this->ReplyTrigger->CurrentValue;
			$this->ReplyTrigger->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ReplyTrigger->FldCaption()));

			// ReplyResponse
			$this->ReplyResponse->EditCustomAttributes = "";
			$this->ReplyResponse->EditValue = $this->ReplyResponse->CurrentValue;
			$this->ReplyResponse->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ReplyResponse->FldCaption()));

			// ReplyType
			$this->ReplyType->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ReplyType->FldTagValue(1), $this->ReplyType->FldTagCaption(1) <> "" ? $this->ReplyType->FldTagCaption(1) : $this->ReplyType->FldTagValue(1));
			$arwrk[] = array($this->ReplyType->FldTagValue(2), $this->ReplyType->FldTagCaption(2) <> "" ? $this->ReplyType->FldTagCaption(2) : $this->ReplyType->FldTagValue(2));
			$arwrk[] = array($this->ReplyType->FldTagValue(3), $this->ReplyType->FldTagCaption(3) <> "" ? $this->ReplyType->FldTagCaption(3) : $this->ReplyType->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ReplyType->EditValue = $arwrk;

			// ReplyValidationResponse
			$this->ReplyValidationResponse->EditCustomAttributes = "";
			$this->ReplyValidationResponse->EditValue = $this->ReplyValidationResponse->CurrentValue;
			$this->ReplyValidationResponse->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ReplyValidationResponse->FldCaption()));

			// ReplyStage
			$this->ReplyStage->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ReplyStage->FldTagValue(1), $this->ReplyStage->FldTagCaption(1) <> "" ? $this->ReplyStage->FldTagCaption(1) : $this->ReplyStage->FldTagValue(1));
			$arwrk[] = array($this->ReplyStage->FldTagValue(2), $this->ReplyStage->FldTagCaption(2) <> "" ? $this->ReplyStage->FldTagCaption(2) : $this->ReplyStage->FldTagValue(2));
			$arwrk[] = array($this->ReplyStage->FldTagValue(3), $this->ReplyStage->FldTagCaption(3) <> "" ? $this->ReplyStage->FldTagCaption(3) : $this->ReplyStage->FldTagValue(3));
			$arwrk[] = array($this->ReplyStage->FldTagValue(4), $this->ReplyStage->FldTagCaption(4) <> "" ? $this->ReplyStage->FldTagCaption(4) : $this->ReplyStage->FldTagValue(4));
			$arwrk[] = array($this->ReplyStage->FldTagValue(5), $this->ReplyStage->FldTagCaption(5) <> "" ? $this->ReplyStage->FldTagCaption(5) : $this->ReplyStage->FldTagValue(5));
			$arwrk[] = array($this->ReplyStage->FldTagValue(6), $this->ReplyStage->FldTagCaption(6) <> "" ? $this->ReplyStage->FldTagCaption(6) : $this->ReplyStage->FldTagValue(6));
			$arwrk[] = array($this->ReplyStage->FldTagValue(7), $this->ReplyStage->FldTagCaption(7) <> "" ? $this->ReplyStage->FldTagCaption(7) : $this->ReplyStage->FldTagValue(7));
			$arwrk[] = array($this->ReplyStage->FldTagValue(8), $this->ReplyStage->FldTagCaption(8) <> "" ? $this->ReplyStage->FldTagCaption(8) : $this->ReplyStage->FldTagValue(8));
			$arwrk[] = array($this->ReplyStage->FldTagValue(9), $this->ReplyStage->FldTagCaption(9) <> "" ? $this->ReplyStage->FldTagCaption(9) : $this->ReplyStage->FldTagValue(9));
			$arwrk[] = array($this->ReplyStage->FldTagValue(10), $this->ReplyStage->FldTagCaption(10) <> "" ? $this->ReplyStage->FldTagCaption(10) : $this->ReplyStage->FldTagValue(10));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ReplyStage->EditValue = $arwrk;

			// Edit refer script
			// ReplyTrigger

			$this->ReplyTrigger->HrefValue = "";

			// ReplyResponse
			$this->ReplyResponse->HrefValue = "";

			// ReplyType
			$this->ReplyType->HrefValue = "";

			// ReplyValidationResponse
			$this->ReplyValidationResponse->HrefValue = "";

			// ReplyStage
			$this->ReplyStage->HrefValue = "";
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
		if (!$this->ReplyResponse->FldIsDetailKey && !is_null($this->ReplyResponse->FormValue) && $this->ReplyResponse->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ReplyResponse->FldCaption());
		}
		if (!$this->ReplyType->FldIsDetailKey && !is_null($this->ReplyType->FormValue) && $this->ReplyType->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ReplyType->FldCaption());
		}
		if (!$this->ReplyStage->FldIsDetailKey && !is_null($this->ReplyStage->FormValue) && $this->ReplyStage->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ReplyStage->FldCaption());
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

		// ReplyTrigger
		$this->ReplyTrigger->SetDbValueDef($rsnew, $this->ReplyTrigger->CurrentValue, NULL, FALSE);

		// ReplyResponse
		$this->ReplyResponse->SetDbValueDef($rsnew, $this->ReplyResponse->CurrentValue, "", FALSE);

		// ReplyType
		$this->ReplyType->SetDbValueDef($rsnew, $this->ReplyType->CurrentValue, "", strval($this->ReplyType->CurrentValue) == "");

		// ReplyValidationResponse
		$this->ReplyValidationResponse->SetDbValueDef($rsnew, $this->ReplyValidationResponse->CurrentValue, NULL, FALSE);

		// ReplyStage
		$this->ReplyStage->SetDbValueDef($rsnew, $this->ReplyStage->CurrentValue, 0, FALSE);

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
			$this->ReplyID->setDbValue($conn->Insert_ID());
			$rsnew['ReplyID'] = $this->ReplyID->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "replieslist.php", $this->TableVar);
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
if (!isset($replies_add)) $replies_add = new creplies_add();

// Page init
$replies_add->Page_Init();

// Page main
$replies_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$replies_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var replies_add = new ew_Page("replies_add");
replies_add.PageID = "add"; // Page ID
var EW_PAGE_ID = replies_add.PageID; // For backward compatibility

// Form object
var frepliesadd = new ew_Form("frepliesadd");

// Validate form
frepliesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ReplyResponse");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($replies->ReplyResponse->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ReplyType");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($replies->ReplyType->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ReplyStage");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($replies->ReplyStage->FldCaption()) ?>");

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
frepliesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepliesadd.ValidateRequired = true;
<?php } else { ?>
frepliesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $replies_add->ShowPageHeader(); ?>
<?php
$replies_add->ShowMessage();
?>
<form name="frepliesadd" id="frepliesadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="replies">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_repliesadd" class="table table-bordered table-striped">
<?php if ($replies->ReplyTrigger->Visible) { // ReplyTrigger ?>
	<tr id="r_ReplyTrigger">
		<td><span id="elh_replies_ReplyTrigger"><?php echo $replies->ReplyTrigger->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyTrigger->CellAttributes() ?>>
<span id="el_replies_ReplyTrigger" class="control-group">
<textarea data-field="x_ReplyTrigger" name="x_ReplyTrigger" id="x_ReplyTrigger" cols="70" rows="8" placeholder="<?php echo $replies->ReplyTrigger->PlaceHolder ?>"<?php echo $replies->ReplyTrigger->EditAttributes() ?>><?php echo $replies->ReplyTrigger->EditValue ?></textarea>
</span>
<?php echo $replies->ReplyTrigger->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyResponse->Visible) { // ReplyResponse ?>
	<tr id="r_ReplyResponse">
		<td><span id="elh_replies_ReplyResponse"><?php echo $replies->ReplyResponse->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $replies->ReplyResponse->CellAttributes() ?>>
<span id="el_replies_ReplyResponse" class="control-group">
<textarea data-field="x_ReplyResponse" name="x_ReplyResponse" id="x_ReplyResponse" cols="70" rows="8" placeholder="<?php echo $replies->ReplyResponse->PlaceHolder ?>"<?php echo $replies->ReplyResponse->EditAttributes() ?>><?php echo $replies->ReplyResponse->EditValue ?></textarea>
</span>
<?php echo $replies->ReplyResponse->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyType->Visible) { // ReplyType ?>
	<tr id="r_ReplyType">
		<td><span id="elh_replies_ReplyType"><?php echo $replies->ReplyType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $replies->ReplyType->CellAttributes() ?>>
<span id="el_replies_ReplyType" class="control-group">
<select data-field="x_ReplyType" id="x_ReplyType" name="x_ReplyType"<?php echo $replies->ReplyType->EditAttributes() ?>>
<?php
if (is_array($replies->ReplyType->EditValue)) {
	$arwrk = $replies->ReplyType->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($replies->ReplyType->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $replies->ReplyType->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyValidationResponse->Visible) { // ReplyValidationResponse ?>
	<tr id="r_ReplyValidationResponse">
		<td><span id="elh_replies_ReplyValidationResponse"><?php echo $replies->ReplyValidationResponse->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyValidationResponse->CellAttributes() ?>>
<span id="el_replies_ReplyValidationResponse" class="control-group">
<textarea data-field="x_ReplyValidationResponse" name="x_ReplyValidationResponse" id="x_ReplyValidationResponse" cols="70" rows="80" placeholder="<?php echo $replies->ReplyValidationResponse->PlaceHolder ?>"<?php echo $replies->ReplyValidationResponse->EditAttributes() ?>><?php echo $replies->ReplyValidationResponse->EditValue ?></textarea>
</span>
<?php echo $replies->ReplyValidationResponse->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($replies->ReplyStage->Visible) { // ReplyStage ?>
	<tr id="r_ReplyStage">
		<td><span id="elh_replies_ReplyStage"><?php echo $replies->ReplyStage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $replies->ReplyStage->CellAttributes() ?>>
<span id="el_replies_ReplyStage" class="control-group">
<select data-field="x_ReplyStage" id="x_ReplyStage" name="x_ReplyStage"<?php echo $replies->ReplyStage->EditAttributes() ?>>
<?php
if (is_array($replies->ReplyStage->EditValue)) {
	$arwrk = $replies->ReplyStage->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($replies->ReplyStage->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $replies->ReplyStage->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
frepliesadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$replies_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$replies_add->Page_Terminate();
?>
