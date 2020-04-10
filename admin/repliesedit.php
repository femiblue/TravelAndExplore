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

$replies_edit = NULL; // Initialize page object first

class creplies_edit extends creplies {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'replies';

	// Page object name
	var $PageObjName = 'replies_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["ReplyID"] <> "") {
			$this->ReplyID->setQueryStringValue($_GET["ReplyID"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->ReplyID->CurrentValue == "")
			$this->Page_Terminate("replieslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("replieslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
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
		if (!$this->ReplyID->FldIsDetailKey)
			$this->ReplyID->setFormValue($objForm->GetValue("x_ReplyID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->ReplyTrigger->CurrentValue = $this->ReplyTrigger->FormValue;
		$this->ReplyResponse->CurrentValue = $this->ReplyResponse->FormValue;
		$this->ReplyType->CurrentValue = $this->ReplyType->FormValue;
		$this->ReplyValidationResponse->CurrentValue = $this->ReplyValidationResponse->FormValue;
		$this->ReplyStage->CurrentValue = $this->ReplyStage->FormValue;
		$this->ReplyID->CurrentValue = $this->ReplyID->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// ReplyID
			$this->ReplyID->EditCustomAttributes = "";
			$this->ReplyID->EditValue = $this->ReplyID->CurrentValue;
			$this->ReplyID->ViewCustomAttributes = "";

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

			// ReplyID
			$this->ReplyID->HrefValue = "";
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// ReplyTrigger
			$this->ReplyTrigger->SetDbValueDef($rsnew, $this->ReplyTrigger->CurrentValue, NULL, $this->ReplyTrigger->ReadOnly);

			// ReplyResponse
			$this->ReplyResponse->SetDbValueDef($rsnew, $this->ReplyResponse->CurrentValue, "", $this->ReplyResponse->ReadOnly);

			// ReplyType
			$this->ReplyType->SetDbValueDef($rsnew, $this->ReplyType->CurrentValue, "", $this->ReplyType->ReadOnly);

			// ReplyValidationResponse
			$this->ReplyValidationResponse->SetDbValueDef($rsnew, $this->ReplyValidationResponse->CurrentValue, NULL, $this->ReplyValidationResponse->ReadOnly);

			// ReplyStage
			$this->ReplyStage->SetDbValueDef($rsnew, $this->ReplyStage->CurrentValue, 0, $this->ReplyStage->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "replieslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($replies_edit)) $replies_edit = new creplies_edit();

// Page init
$replies_edit->Page_Init();

// Page main
$replies_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$replies_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var replies_edit = new ew_Page("replies_edit");
replies_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = replies_edit.PageID; // For backward compatibility

// Form object
var frepliesedit = new ew_Form("frepliesedit");

// Validate form
frepliesedit.Validate = function() {
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
frepliesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepliesedit.ValidateRequired = true;
<?php } else { ?>
frepliesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $replies_edit->ShowPageHeader(); ?>
<?php
$replies_edit->ShowMessage();
?>
<form name="frepliesedit" id="frepliesedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="replies">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_repliesedit" class="table table-bordered table-striped">
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
<?php if ($replies->ReplyID->Visible) { // ReplyID ?>
	<tr id="r_ReplyID">
		<td><span id="elh_replies_ReplyID"><?php echo $replies->ReplyID->FldCaption() ?></span></td>
		<td<?php echo $replies->ReplyID->CellAttributes() ?>>
<span id="el_replies_ReplyID" class="control-group">
<span<?php echo $replies->ReplyID->ViewAttributes() ?>>
<?php echo $replies->ReplyID->EditValue ?></span>
</span>
<input type="hidden" data-field="x_ReplyID" name="x_ReplyID" id="x_ReplyID" value="<?php echo ew_HtmlEncode($replies->ReplyID->CurrentValue) ?>">
<?php echo $replies->ReplyID->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
frepliesedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$replies_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$replies_edit->Page_Terminate();
?>
