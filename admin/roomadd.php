<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "roominfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$room_add = NULL; // Initialize page object first

class croom_add extends croom {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'room';

	// Page object name
	var $PageObjName = 'room_add';

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

		// Table object (room)
		if (!isset($GLOBALS["room"])) {
			$GLOBALS["room"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["room"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'room', TRUE);

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
			if (@$_GET["RoomId"] != "") {
				$this->RoomId->setQueryStringValue($_GET["RoomId"]);
				$this->setKey("RoomId", $this->RoomId->CurrentValue); // Set up key
			} else {
				$this->setKey("RoomId", ""); // Clear key
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
					$this->Page_Terminate("roomlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "roomview.php")
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
		$this->RoomNumber->CurrentValue = NULL;
		$this->RoomNumber->OldValue = $this->RoomNumber->CurrentValue;
		$this->Price->CurrentValue = NULL;
		$this->Price->OldValue = $this->Price->CurrentValue;
		$this->Type->CurrentValue = NULL;
		$this->Type->OldValue = $this->Type->CurrentValue;
		$this->Hotel->CurrentValue = NULL;
		$this->Hotel->OldValue = $this->Hotel->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->RoomNumber->FldIsDetailKey) {
			$this->RoomNumber->setFormValue($objForm->GetValue("x_RoomNumber"));
		}
		if (!$this->Price->FldIsDetailKey) {
			$this->Price->setFormValue($objForm->GetValue("x_Price"));
		}
		if (!$this->Type->FldIsDetailKey) {
			$this->Type->setFormValue($objForm->GetValue("x_Type"));
		}
		if (!$this->Hotel->FldIsDetailKey) {
			$this->Hotel->setFormValue($objForm->GetValue("x_Hotel"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->RoomNumber->CurrentValue = $this->RoomNumber->FormValue;
		$this->Price->CurrentValue = $this->Price->FormValue;
		$this->Type->CurrentValue = $this->Type->FormValue;
		$this->Hotel->CurrentValue = $this->Hotel->FormValue;
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
		$this->RoomId->setDbValue($rs->fields('RoomId'));
		$this->RoomNumber->setDbValue($rs->fields('RoomNumber'));
		$this->Price->setDbValue($rs->fields('Price'));
		$this->Type->setDbValue($rs->fields('Type'));
		$this->Hotel->setDbValue($rs->fields('Hotel'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->RoomId->DbValue = $row['RoomId'];
		$this->RoomNumber->DbValue = $row['RoomNumber'];
		$this->Price->DbValue = $row['Price'];
		$this->Type->DbValue = $row['Type'];
		$this->Hotel->DbValue = $row['Hotel'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("RoomId")) <> "")
			$this->RoomId->CurrentValue = $this->getKey("RoomId"); // RoomId
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

		if ($this->Price->FormValue == $this->Price->CurrentValue && is_numeric(ew_StrToFloat($this->Price->CurrentValue)))
			$this->Price->CurrentValue = ew_StrToFloat($this->Price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// RoomId
		// RoomNumber
		// Price
		// Type
		// Hotel

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// RoomId
			$this->RoomId->ViewValue = $this->RoomId->CurrentValue;
			$this->RoomId->ViewCustomAttributes = "";

			// RoomNumber
			$this->RoomNumber->ViewValue = $this->RoomNumber->CurrentValue;
			$this->RoomNumber->ViewCustomAttributes = "";

			// Price
			$this->Price->ViewValue = $this->Price->CurrentValue;
			$this->Price->ViewCustomAttributes = "";

			// Type
			if (strval($this->Type->CurrentValue) <> "") {
				switch ($this->Type->CurrentValue) {
					case $this->Type->FldTagValue(1):
						$this->Type->ViewValue = $this->Type->FldTagCaption(1) <> "" ? $this->Type->FldTagCaption(1) : $this->Type->CurrentValue;
						break;
					case $this->Type->FldTagValue(2):
						$this->Type->ViewValue = $this->Type->FldTagCaption(2) <> "" ? $this->Type->FldTagCaption(2) : $this->Type->CurrentValue;
						break;
					case $this->Type->FldTagValue(3):
						$this->Type->ViewValue = $this->Type->FldTagCaption(3) <> "" ? $this->Type->FldTagCaption(3) : $this->Type->CurrentValue;
						break;
					case $this->Type->FldTagValue(4):
						$this->Type->ViewValue = $this->Type->FldTagCaption(4) <> "" ? $this->Type->FldTagCaption(4) : $this->Type->CurrentValue;
						break;
					default:
						$this->Type->ViewValue = $this->Type->CurrentValue;
				}
			} else {
				$this->Type->ViewValue = NULL;
			}
			$this->Type->ViewCustomAttributes = "";

			// Hotel
			if (strval($this->Hotel->CurrentValue) <> "") {
				$sFilterWrk = "`HotelID`" . ew_SearchString("=", $this->Hotel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `HotelID`, `Title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hotel`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Hotel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Hotel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Hotel->ViewValue = $this->Hotel->CurrentValue;
				}
			} else {
				$this->Hotel->ViewValue = NULL;
			}
			$this->Hotel->ViewCustomAttributes = "";

			// RoomNumber
			$this->RoomNumber->LinkCustomAttributes = "";
			$this->RoomNumber->HrefValue = "";
			$this->RoomNumber->TooltipValue = "";

			// Price
			$this->Price->LinkCustomAttributes = "";
			$this->Price->HrefValue = "";
			$this->Price->TooltipValue = "";

			// Type
			$this->Type->LinkCustomAttributes = "";
			$this->Type->HrefValue = "";
			$this->Type->TooltipValue = "";

			// Hotel
			$this->Hotel->LinkCustomAttributes = "";
			$this->Hotel->HrefValue = "";
			$this->Hotel->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// RoomNumber
			$this->RoomNumber->EditCustomAttributes = "";
			$this->RoomNumber->EditValue = ew_HtmlEncode($this->RoomNumber->CurrentValue);
			$this->RoomNumber->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->RoomNumber->FldCaption()));

			// Price
			$this->Price->EditCustomAttributes = "";
			$this->Price->EditValue = ew_HtmlEncode($this->Price->CurrentValue);
			$this->Price->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Price->FldCaption()));
			if (strval($this->Price->EditValue) <> "" && is_numeric($this->Price->EditValue)) $this->Price->EditValue = ew_FormatNumber($this->Price->EditValue, -2, -1, -2, 0);

			// Type
			$this->Type->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Type->FldTagValue(1), $this->Type->FldTagCaption(1) <> "" ? $this->Type->FldTagCaption(1) : $this->Type->FldTagValue(1));
			$arwrk[] = array($this->Type->FldTagValue(2), $this->Type->FldTagCaption(2) <> "" ? $this->Type->FldTagCaption(2) : $this->Type->FldTagValue(2));
			$arwrk[] = array($this->Type->FldTagValue(3), $this->Type->FldTagCaption(3) <> "" ? $this->Type->FldTagCaption(3) : $this->Type->FldTagValue(3));
			$arwrk[] = array($this->Type->FldTagValue(4), $this->Type->FldTagCaption(4) <> "" ? $this->Type->FldTagCaption(4) : $this->Type->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Type->EditValue = $arwrk;

			// Hotel
			$this->Hotel->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `HotelID`, `Title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `hotel`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Hotel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Hotel->EditValue = $arwrk;

			// Edit refer script
			// RoomNumber

			$this->RoomNumber->HrefValue = "";

			// Price
			$this->Price->HrefValue = "";

			// Type
			$this->Type->HrefValue = "";

			// Hotel
			$this->Hotel->HrefValue = "";
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
		if (!$this->RoomNumber->FldIsDetailKey && !is_null($this->RoomNumber->FormValue) && $this->RoomNumber->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->RoomNumber->FldCaption());
		}
		if (!ew_CheckInteger($this->RoomNumber->FormValue)) {
			ew_AddMessage($gsFormError, $this->RoomNumber->FldErrMsg());
		}
		if (!$this->Price->FldIsDetailKey && !is_null($this->Price->FormValue) && $this->Price->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Price->FldCaption());
		}
		if (!ew_CheckNumber($this->Price->FormValue)) {
			ew_AddMessage($gsFormError, $this->Price->FldErrMsg());
		}
		if (!$this->Type->FldIsDetailKey && !is_null($this->Type->FormValue) && $this->Type->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Type->FldCaption());
		}
		if (!$this->Hotel->FldIsDetailKey && !is_null($this->Hotel->FormValue) && $this->Hotel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Hotel->FldCaption());
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

		// RoomNumber
		$this->RoomNumber->SetDbValueDef($rsnew, $this->RoomNumber->CurrentValue, 0, FALSE);

		// Price
		$this->Price->SetDbValueDef($rsnew, $this->Price->CurrentValue, 0, FALSE);

		// Type
		$this->Type->SetDbValueDef($rsnew, $this->Type->CurrentValue, "", FALSE);

		// Hotel
		$this->Hotel->SetDbValueDef($rsnew, $this->Hotel->CurrentValue, 0, FALSE);

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
			$this->RoomId->setDbValue($conn->Insert_ID());
			$rsnew['RoomId'] = $this->RoomId->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "roomlist.php", $this->TableVar);
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
if (!isset($room_add)) $room_add = new croom_add();

// Page init
$room_add->Page_Init();

// Page main
$room_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$room_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var room_add = new ew_Page("room_add");
room_add.PageID = "add"; // Page ID
var EW_PAGE_ID = room_add.PageID; // For backward compatibility

// Form object
var froomadd = new ew_Form("froomadd");

// Validate form
froomadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_RoomNumber");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($room->RoomNumber->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_RoomNumber");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($room->RoomNumber->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Price");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($room->Price->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($room->Price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Type");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($room->Type->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Hotel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($room->Hotel->FldCaption()) ?>");

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
froomadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
froomadd.ValidateRequired = true;
<?php } else { ?>
froomadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
froomadd.Lists["x_Hotel"] = {"LinkField":"x_HotelID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Title","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $room_add->ShowPageHeader(); ?>
<?php
$room_add->ShowMessage();
?>
<form name="froomadd" id="froomadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="room">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_roomadd" class="table table-bordered table-striped">
<?php if ($room->RoomNumber->Visible) { // RoomNumber ?>
	<tr id="r_RoomNumber">
		<td><span id="elh_room_RoomNumber"><?php echo $room->RoomNumber->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $room->RoomNumber->CellAttributes() ?>>
<span id="el_room_RoomNumber" class="control-group">
<input type="text" data-field="x_RoomNumber" name="x_RoomNumber" id="x_RoomNumber" size="30" placeholder="<?php echo $room->RoomNumber->PlaceHolder ?>" value="<?php echo $room->RoomNumber->EditValue ?>"<?php echo $room->RoomNumber->EditAttributes() ?>>
</span>
<?php echo $room->RoomNumber->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($room->Price->Visible) { // Price ?>
	<tr id="r_Price">
		<td><span id="elh_room_Price"><?php echo $room->Price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $room->Price->CellAttributes() ?>>
<span id="el_room_Price" class="control-group">
<input type="text" data-field="x_Price" name="x_Price" id="x_Price" size="70" placeholder="<?php echo $room->Price->PlaceHolder ?>" value="<?php echo $room->Price->EditValue ?>"<?php echo $room->Price->EditAttributes() ?>>
</span>
<?php echo $room->Price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($room->Type->Visible) { // Type ?>
	<tr id="r_Type">
		<td><span id="elh_room_Type"><?php echo $room->Type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $room->Type->CellAttributes() ?>>
<span id="el_room_Type" class="control-group">
<select data-field="x_Type" id="x_Type" name="x_Type"<?php echo $room->Type->EditAttributes() ?>>
<?php
if (is_array($room->Type->EditValue)) {
	$arwrk = $room->Type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($room->Type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $room->Type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($room->Hotel->Visible) { // Hotel ?>
	<tr id="r_Hotel">
		<td><span id="elh_room_Hotel"><?php echo $room->Hotel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $room->Hotel->CellAttributes() ?>>
<span id="el_room_Hotel" class="control-group">
<select data-field="x_Hotel" id="x_Hotel" name="x_Hotel"<?php echo $room->Hotel->EditAttributes() ?>>
<?php
if (is_array($room->Hotel->EditValue)) {
	$arwrk = $room->Hotel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($room->Hotel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
froomadd.Lists["x_Hotel"].Options = <?php echo (is_array($room->Hotel->EditValue)) ? ew_ArrayToJson($room->Hotel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $room->Hotel->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
froomadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$room_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$room_add->Page_Terminate();
?>
