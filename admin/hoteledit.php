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

$hotel_edit = NULL; // Initialize page object first

class chotel_edit extends chotel {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'hotel';

	// Page object name
	var $PageObjName = 'hotel_edit';

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

		// Table object (hotel)
		if (!isset($GLOBALS["hotel"])) {
			$GLOBALS["hotel"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hotel"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'hotel', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["HotelID"] <> "") {
			$this->HotelID->setQueryStringValue($_GET["HotelID"]);
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
		if ($this->HotelID->CurrentValue == "")
			$this->Page_Terminate("hotellist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("hotellist.php"); // No matching record, return to list
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
		if (!$this->HotelID->FldIsDetailKey)
			$this->HotelID->setFormValue($objForm->GetValue("x_HotelID"));
		if (!$this->Country->FldIsDetailKey) {
			$this->Country->setFormValue($objForm->GetValue("x_Country"));
		}
		if (!$this->Title->FldIsDetailKey) {
			$this->Title->setFormValue($objForm->GetValue("x_Title"));
		}
		if (!$this->HotelCity->FldIsDetailKey) {
			$this->HotelCity->setFormValue($objForm->GetValue("x_HotelCity"));
		}
		if (!$this->Address->FldIsDetailKey) {
			$this->Address->setFormValue($objForm->GetValue("x_Address"));
		}
		if (!$this->NumberOfRooms->FldIsDetailKey) {
			$this->NumberOfRooms->setFormValue($objForm->GetValue("x_NumberOfRooms"));
		}
		if (!$this->Ranking->FldIsDetailKey) {
			$this->Ranking->setFormValue($objForm->GetValue("x_Ranking"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->HotelID->CurrentValue = $this->HotelID->FormValue;
		$this->Country->CurrentValue = $this->Country->FormValue;
		$this->Title->CurrentValue = $this->Title->FormValue;
		$this->HotelCity->CurrentValue = $this->HotelCity->FormValue;
		$this->Address->CurrentValue = $this->Address->FormValue;
		$this->NumberOfRooms->CurrentValue = $this->NumberOfRooms->FormValue;
		$this->Ranking->CurrentValue = $this->Ranking->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// HotelID
			$this->HotelID->EditCustomAttributes = "";
			$this->HotelID->EditValue = $this->HotelID->CurrentValue;
			$this->HotelID->ViewCustomAttributes = "";

			// Country
			$this->Country->EditCustomAttributes = "";
			$this->Country->EditValue = ew_HtmlEncode($this->Country->CurrentValue);
			$this->Country->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Country->FldCaption()));

			// Title
			$this->Title->EditCustomAttributes = "";
			$this->Title->EditValue = ew_HtmlEncode($this->Title->CurrentValue);
			$this->Title->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Title->FldCaption()));

			// HotelCity
			$this->HotelCity->EditCustomAttributes = "";
			$this->HotelCity->EditValue = ew_HtmlEncode($this->HotelCity->CurrentValue);
			$this->HotelCity->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->HotelCity->FldCaption()));

			// Address
			$this->Address->EditCustomAttributes = "";
			$this->Address->EditValue = $this->Address->CurrentValue;
			$this->Address->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Address->FldCaption()));

			// NumberOfRooms
			$this->NumberOfRooms->EditCustomAttributes = "";
			$this->NumberOfRooms->EditValue = ew_HtmlEncode($this->NumberOfRooms->CurrentValue);
			$this->NumberOfRooms->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->NumberOfRooms->FldCaption()));

			// Ranking
			$this->Ranking->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Ranking->FldTagValue(1), $this->Ranking->FldTagCaption(1) <> "" ? $this->Ranking->FldTagCaption(1) : $this->Ranking->FldTagValue(1));
			$arwrk[] = array($this->Ranking->FldTagValue(2), $this->Ranking->FldTagCaption(2) <> "" ? $this->Ranking->FldTagCaption(2) : $this->Ranking->FldTagValue(2));
			$arwrk[] = array($this->Ranking->FldTagValue(3), $this->Ranking->FldTagCaption(3) <> "" ? $this->Ranking->FldTagCaption(3) : $this->Ranking->FldTagValue(3));
			$arwrk[] = array($this->Ranking->FldTagValue(4), $this->Ranking->FldTagCaption(4) <> "" ? $this->Ranking->FldTagCaption(4) : $this->Ranking->FldTagValue(4));
			$arwrk[] = array($this->Ranking->FldTagValue(5), $this->Ranking->FldTagCaption(5) <> "" ? $this->Ranking->FldTagCaption(5) : $this->Ranking->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Ranking->EditValue = $arwrk;

			// Edit refer script
			// HotelID

			$this->HotelID->HrefValue = "";

			// Country
			$this->Country->HrefValue = "";

			// Title
			$this->Title->HrefValue = "";

			// HotelCity
			$this->HotelCity->HrefValue = "";

			// Address
			$this->Address->HrefValue = "";

			// NumberOfRooms
			$this->NumberOfRooms->HrefValue = "";

			// Ranking
			$this->Ranking->HrefValue = "";
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
		if (!$this->Country->FldIsDetailKey && !is_null($this->Country->FormValue) && $this->Country->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Country->FldCaption());
		}
		if (!$this->Title->FldIsDetailKey && !is_null($this->Title->FormValue) && $this->Title->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Title->FldCaption());
		}
		if (!$this->HotelCity->FldIsDetailKey && !is_null($this->HotelCity->FormValue) && $this->HotelCity->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->HotelCity->FldCaption());
		}
		if (!$this->Address->FldIsDetailKey && !is_null($this->Address->FormValue) && $this->Address->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Address->FldCaption());
		}
		if (!$this->NumberOfRooms->FldIsDetailKey && !is_null($this->NumberOfRooms->FormValue) && $this->NumberOfRooms->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->NumberOfRooms->FldCaption());
		}
		if (!ew_CheckInteger($this->NumberOfRooms->FormValue)) {
			ew_AddMessage($gsFormError, $this->NumberOfRooms->FldErrMsg());
		}
		if (!$this->Ranking->FldIsDetailKey && !is_null($this->Ranking->FormValue) && $this->Ranking->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Ranking->FldCaption());
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

			// Country
			$this->Country->SetDbValueDef($rsnew, $this->Country->CurrentValue, "", $this->Country->ReadOnly);

			// Title
			$this->Title->SetDbValueDef($rsnew, $this->Title->CurrentValue, "", $this->Title->ReadOnly);

			// HotelCity
			$this->HotelCity->SetDbValueDef($rsnew, $this->HotelCity->CurrentValue, "", $this->HotelCity->ReadOnly);

			// Address
			$this->Address->SetDbValueDef($rsnew, $this->Address->CurrentValue, "", $this->Address->ReadOnly);

			// NumberOfRooms
			$this->NumberOfRooms->SetDbValueDef($rsnew, $this->NumberOfRooms->CurrentValue, 0, $this->NumberOfRooms->ReadOnly);

			// Ranking
			$this->Ranking->SetDbValueDef($rsnew, $this->Ranking->CurrentValue, 0, $this->Ranking->ReadOnly);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "hotellist.php", $this->TableVar);
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
if (!isset($hotel_edit)) $hotel_edit = new chotel_edit();

// Page init
$hotel_edit->Page_Init();

// Page main
$hotel_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hotel_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hotel_edit = new ew_Page("hotel_edit");
hotel_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = hotel_edit.PageID; // For backward compatibility

// Form object
var fhoteledit = new ew_Form("fhoteledit");

// Validate form
fhoteledit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Country");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($hotel->Country->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Title");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($hotel->Title->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_HotelCity");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($hotel->HotelCity->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Address");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($hotel->Address->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_NumberOfRooms");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($hotel->NumberOfRooms->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_NumberOfRooms");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hotel->NumberOfRooms->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Ranking");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($hotel->Ranking->FldCaption()) ?>");

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
fhoteledit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoteledit.ValidateRequired = true;
<?php } else { ?>
fhoteledit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $hotel_edit->ShowPageHeader(); ?>
<?php
$hotel_edit->ShowMessage();
?>
<form name="fhoteledit" id="fhoteledit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="hotel">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_hoteledit" class="table table-bordered table-striped">
<?php if ($hotel->HotelID->Visible) { // HotelID ?>
	<tr id="r_HotelID">
		<td><span id="elh_hotel_HotelID"><?php echo $hotel->HotelID->FldCaption() ?></span></td>
		<td<?php echo $hotel->HotelID->CellAttributes() ?>>
<span id="el_hotel_HotelID" class="control-group">
<span<?php echo $hotel->HotelID->ViewAttributes() ?>>
<?php echo $hotel->HotelID->EditValue ?></span>
</span>
<input type="hidden" data-field="x_HotelID" name="x_HotelID" id="x_HotelID" value="<?php echo ew_HtmlEncode($hotel->HotelID->CurrentValue) ?>">
<?php echo $hotel->HotelID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($hotel->Country->Visible) { // Country ?>
	<tr id="r_Country">
		<td><span id="elh_hotel_Country"><?php echo $hotel->Country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $hotel->Country->CellAttributes() ?>>
<span id="el_hotel_Country" class="control-group">
<input type="text" data-field="x_Country" name="x_Country" id="x_Country" size="70" maxlength="255" placeholder="<?php echo $hotel->Country->PlaceHolder ?>" value="<?php echo $hotel->Country->EditValue ?>"<?php echo $hotel->Country->EditAttributes() ?>>
</span>
<?php echo $hotel->Country->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($hotel->Title->Visible) { // Title ?>
	<tr id="r_Title">
		<td><span id="elh_hotel_Title"><?php echo $hotel->Title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $hotel->Title->CellAttributes() ?>>
<span id="el_hotel_Title" class="control-group">
<input type="text" data-field="x_Title" name="x_Title" id="x_Title" size="70" maxlength="255" placeholder="<?php echo $hotel->Title->PlaceHolder ?>" value="<?php echo $hotel->Title->EditValue ?>"<?php echo $hotel->Title->EditAttributes() ?>>
</span>
<?php echo $hotel->Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($hotel->HotelCity->Visible) { // HotelCity ?>
	<tr id="r_HotelCity">
		<td><span id="elh_hotel_HotelCity"><?php echo $hotel->HotelCity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $hotel->HotelCity->CellAttributes() ?>>
<span id="el_hotel_HotelCity" class="control-group">
<input type="text" data-field="x_HotelCity" name="x_HotelCity" id="x_HotelCity" size="70" maxlength="255" placeholder="<?php echo $hotel->HotelCity->PlaceHolder ?>" value="<?php echo $hotel->HotelCity->EditValue ?>"<?php echo $hotel->HotelCity->EditAttributes() ?>>
</span>
<?php echo $hotel->HotelCity->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($hotel->Address->Visible) { // Address ?>
	<tr id="r_Address">
		<td><span id="elh_hotel_Address"><?php echo $hotel->Address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $hotel->Address->CellAttributes() ?>>
<span id="el_hotel_Address" class="control-group">
<textarea data-field="x_Address" name="x_Address" id="x_Address" cols="70" rows="8" placeholder="<?php echo $hotel->Address->PlaceHolder ?>"<?php echo $hotel->Address->EditAttributes() ?>><?php echo $hotel->Address->EditValue ?></textarea>
</span>
<?php echo $hotel->Address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($hotel->NumberOfRooms->Visible) { // NumberOfRooms ?>
	<tr id="r_NumberOfRooms">
		<td><span id="elh_hotel_NumberOfRooms"><?php echo $hotel->NumberOfRooms->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $hotel->NumberOfRooms->CellAttributes() ?>>
<span id="el_hotel_NumberOfRooms" class="control-group">
<input type="text" data-field="x_NumberOfRooms" name="x_NumberOfRooms" id="x_NumberOfRooms" size="30" placeholder="<?php echo $hotel->NumberOfRooms->PlaceHolder ?>" value="<?php echo $hotel->NumberOfRooms->EditValue ?>"<?php echo $hotel->NumberOfRooms->EditAttributes() ?>>
</span>
<?php echo $hotel->NumberOfRooms->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($hotel->Ranking->Visible) { // Ranking ?>
	<tr id="r_Ranking">
		<td><span id="elh_hotel_Ranking"><?php echo $hotel->Ranking->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $hotel->Ranking->CellAttributes() ?>>
<span id="el_hotel_Ranking" class="control-group">
<select data-field="x_Ranking" id="x_Ranking" name="x_Ranking"<?php echo $hotel->Ranking->EditAttributes() ?>>
<?php
if (is_array($hotel->Ranking->EditValue)) {
	$arwrk = $hotel->Ranking->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hotel->Ranking->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $hotel->Ranking->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fhoteledit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$hotel_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hotel_edit->Page_Terminate();
?>
