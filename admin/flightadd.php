<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "flightinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$flight_add = NULL; // Initialize page object first

class cflight_add extends cflight {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'flight';

	// Page object name
	var $PageObjName = 'flight_add';

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

		// Table object (flight)
		if (!isset($GLOBALS["flight"])) {
			$GLOBALS["flight"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["flight"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'flight', TRUE);

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
			if (@$_GET["FlightID"] != "") {
				$this->FlightID->setQueryStringValue($_GET["FlightID"]);
				$this->setKey("FlightID", $this->FlightID->CurrentValue); // Set up key
			} else {
				$this->setKey("FlightID", ""); // Clear key
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
					$this->Page_Terminate("flightlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "flightview.php")
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
		$this->DepartureAirport->CurrentValue = NULL;
		$this->DepartureAirport->OldValue = $this->DepartureAirport->CurrentValue;
		$this->ArrivalLocation->CurrentValue = NULL;
		$this->ArrivalLocation->OldValue = $this->ArrivalLocation->CurrentValue;
		$this->DepartureTime->CurrentValue = NULL;
		$this->DepartureTime->OldValue = $this->DepartureTime->CurrentValue;
		$this->ArrivalTime->CurrentValue = NULL;
		$this->ArrivalTime->OldValue = $this->ArrivalTime->CurrentValue;
		$this->DepartureDate->CurrentValue = NULL;
		$this->DepartureDate->OldValue = $this->DepartureDate->CurrentValue;
		$this->ArrivalDate->CurrentValue = NULL;
		$this->ArrivalDate->OldValue = $this->ArrivalDate->CurrentValue;
		$this->Price->CurrentValue = NULL;
		$this->Price->OldValue = $this->Price->CurrentValue;
		$this->Company->CurrentValue = NULL;
		$this->Company->OldValue = $this->Company->CurrentValue;
		$this->Capacity->CurrentValue = NULL;
		$this->Capacity->OldValue = $this->Capacity->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->DepartureAirport->FldIsDetailKey) {
			$this->DepartureAirport->setFormValue($objForm->GetValue("x_DepartureAirport"));
		}
		if (!$this->ArrivalLocation->FldIsDetailKey) {
			$this->ArrivalLocation->setFormValue($objForm->GetValue("x_ArrivalLocation"));
		}
		if (!$this->DepartureTime->FldIsDetailKey) {
			$this->DepartureTime->setFormValue($objForm->GetValue("x_DepartureTime"));
		}
		if (!$this->ArrivalTime->FldIsDetailKey) {
			$this->ArrivalTime->setFormValue($objForm->GetValue("x_ArrivalTime"));
		}
		if (!$this->DepartureDate->FldIsDetailKey) {
			$this->DepartureDate->setFormValue($objForm->GetValue("x_DepartureDate"));
			$this->DepartureDate->CurrentValue = ew_UnFormatDateTime($this->DepartureDate->CurrentValue, 5);
		}
		if (!$this->ArrivalDate->FldIsDetailKey) {
			$this->ArrivalDate->setFormValue($objForm->GetValue("x_ArrivalDate"));
			$this->ArrivalDate->CurrentValue = ew_UnFormatDateTime($this->ArrivalDate->CurrentValue, 5);
		}
		if (!$this->Price->FldIsDetailKey) {
			$this->Price->setFormValue($objForm->GetValue("x_Price"));
		}
		if (!$this->Company->FldIsDetailKey) {
			$this->Company->setFormValue($objForm->GetValue("x_Company"));
		}
		if (!$this->Capacity->FldIsDetailKey) {
			$this->Capacity->setFormValue($objForm->GetValue("x_Capacity"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->DepartureAirport->CurrentValue = $this->DepartureAirport->FormValue;
		$this->ArrivalLocation->CurrentValue = $this->ArrivalLocation->FormValue;
		$this->DepartureTime->CurrentValue = $this->DepartureTime->FormValue;
		$this->ArrivalTime->CurrentValue = $this->ArrivalTime->FormValue;
		$this->DepartureDate->CurrentValue = $this->DepartureDate->FormValue;
		$this->DepartureDate->CurrentValue = ew_UnFormatDateTime($this->DepartureDate->CurrentValue, 5);
		$this->ArrivalDate->CurrentValue = $this->ArrivalDate->FormValue;
		$this->ArrivalDate->CurrentValue = ew_UnFormatDateTime($this->ArrivalDate->CurrentValue, 5);
		$this->Price->CurrentValue = $this->Price->FormValue;
		$this->Company->CurrentValue = $this->Company->FormValue;
		$this->Capacity->CurrentValue = $this->Capacity->FormValue;
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
		$this->FlightID->setDbValue($rs->fields('FlightID'));
		$this->DepartureAirport->setDbValue($rs->fields('DepartureAirport'));
		$this->ArrivalLocation->setDbValue($rs->fields('ArrivalLocation'));
		$this->DepartureTime->setDbValue($rs->fields('DepartureTime'));
		$this->ArrivalTime->setDbValue($rs->fields('ArrivalTime'));
		$this->DepartureDate->setDbValue($rs->fields('DepartureDate'));
		$this->ArrivalDate->setDbValue($rs->fields('ArrivalDate'));
		$this->Price->setDbValue($rs->fields('Price'));
		$this->Company->setDbValue($rs->fields('Company'));
		$this->Capacity->setDbValue($rs->fields('Capacity'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->FlightID->DbValue = $row['FlightID'];
		$this->DepartureAirport->DbValue = $row['DepartureAirport'];
		$this->ArrivalLocation->DbValue = $row['ArrivalLocation'];
		$this->DepartureTime->DbValue = $row['DepartureTime'];
		$this->ArrivalTime->DbValue = $row['ArrivalTime'];
		$this->DepartureDate->DbValue = $row['DepartureDate'];
		$this->ArrivalDate->DbValue = $row['ArrivalDate'];
		$this->Price->DbValue = $row['Price'];
		$this->Company->DbValue = $row['Company'];
		$this->Capacity->DbValue = $row['Capacity'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("FlightID")) <> "")
			$this->FlightID->CurrentValue = $this->getKey("FlightID"); // FlightID
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
		// FlightID
		// DepartureAirport
		// ArrivalLocation
		// DepartureTime
		// ArrivalTime
		// DepartureDate
		// ArrivalDate
		// Price
		// Company
		// Capacity

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// FlightID
			$this->FlightID->ViewValue = $this->FlightID->CurrentValue;
			$this->FlightID->ViewCustomAttributes = "";

			// DepartureAirport
			$this->DepartureAirport->ViewValue = $this->DepartureAirport->CurrentValue;
			$this->DepartureAirport->ViewCustomAttributes = "";

			// ArrivalLocation
			$this->ArrivalLocation->ViewValue = $this->ArrivalLocation->CurrentValue;
			$this->ArrivalLocation->ViewCustomAttributes = "";

			// DepartureTime
			$this->DepartureTime->ViewValue = $this->DepartureTime->CurrentValue;
			$this->DepartureTime->ViewCustomAttributes = "";

			// ArrivalTime
			$this->ArrivalTime->ViewValue = $this->ArrivalTime->CurrentValue;
			$this->ArrivalTime->ViewCustomAttributes = "";

			// DepartureDate
			$this->DepartureDate->ViewValue = $this->DepartureDate->CurrentValue;
			$this->DepartureDate->ViewValue = ew_FormatDateTime($this->DepartureDate->ViewValue, 5);
			$this->DepartureDate->ViewCustomAttributes = "";

			// ArrivalDate
			$this->ArrivalDate->ViewValue = $this->ArrivalDate->CurrentValue;
			$this->ArrivalDate->ViewValue = ew_FormatDateTime($this->ArrivalDate->ViewValue, 5);
			$this->ArrivalDate->ViewCustomAttributes = "";

			// Price
			$this->Price->ViewValue = $this->Price->CurrentValue;
			$this->Price->ViewCustomAttributes = "";

			// Company
			$this->Company->ViewValue = $this->Company->CurrentValue;
			$this->Company->ViewCustomAttributes = "";

			// Capacity
			$this->Capacity->ViewValue = $this->Capacity->CurrentValue;
			$this->Capacity->ViewCustomAttributes = "";

			// DepartureAirport
			$this->DepartureAirport->LinkCustomAttributes = "";
			$this->DepartureAirport->HrefValue = "";
			$this->DepartureAirport->TooltipValue = "";

			// ArrivalLocation
			$this->ArrivalLocation->LinkCustomAttributes = "";
			$this->ArrivalLocation->HrefValue = "";
			$this->ArrivalLocation->TooltipValue = "";

			// DepartureTime
			$this->DepartureTime->LinkCustomAttributes = "";
			$this->DepartureTime->HrefValue = "";
			$this->DepartureTime->TooltipValue = "";

			// ArrivalTime
			$this->ArrivalTime->LinkCustomAttributes = "";
			$this->ArrivalTime->HrefValue = "";
			$this->ArrivalTime->TooltipValue = "";

			// DepartureDate
			$this->DepartureDate->LinkCustomAttributes = "";
			$this->DepartureDate->HrefValue = "";
			$this->DepartureDate->TooltipValue = "";

			// ArrivalDate
			$this->ArrivalDate->LinkCustomAttributes = "";
			$this->ArrivalDate->HrefValue = "";
			$this->ArrivalDate->TooltipValue = "";

			// Price
			$this->Price->LinkCustomAttributes = "";
			$this->Price->HrefValue = "";
			$this->Price->TooltipValue = "";

			// Company
			$this->Company->LinkCustomAttributes = "";
			$this->Company->HrefValue = "";
			$this->Company->TooltipValue = "";

			// Capacity
			$this->Capacity->LinkCustomAttributes = "";
			$this->Capacity->HrefValue = "";
			$this->Capacity->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// DepartureAirport
			$this->DepartureAirport->EditCustomAttributes = "";
			$this->DepartureAirport->EditValue = ew_HtmlEncode($this->DepartureAirport->CurrentValue);
			$this->DepartureAirport->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->DepartureAirport->FldCaption()));

			// ArrivalLocation
			$this->ArrivalLocation->EditCustomAttributes = "";
			$this->ArrivalLocation->EditValue = ew_HtmlEncode($this->ArrivalLocation->CurrentValue);
			$this->ArrivalLocation->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ArrivalLocation->FldCaption()));

			// DepartureTime
			$this->DepartureTime->EditCustomAttributes = "";
			$this->DepartureTime->EditValue = ew_HtmlEncode($this->DepartureTime->CurrentValue);
			$this->DepartureTime->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->DepartureTime->FldCaption()));

			// ArrivalTime
			$this->ArrivalTime->EditCustomAttributes = "";
			$this->ArrivalTime->EditValue = ew_HtmlEncode($this->ArrivalTime->CurrentValue);
			$this->ArrivalTime->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ArrivalTime->FldCaption()));

			// DepartureDate
			$this->DepartureDate->EditCustomAttributes = "";
			$this->DepartureDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DepartureDate->CurrentValue, 5));
			$this->DepartureDate->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->DepartureDate->FldCaption()));

			// ArrivalDate
			$this->ArrivalDate->EditCustomAttributes = "";
			$this->ArrivalDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->ArrivalDate->CurrentValue, 5));
			$this->ArrivalDate->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ArrivalDate->FldCaption()));

			// Price
			$this->Price->EditCustomAttributes = "";
			$this->Price->EditValue = ew_HtmlEncode($this->Price->CurrentValue);
			$this->Price->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Price->FldCaption()));
			if (strval($this->Price->EditValue) <> "" && is_numeric($this->Price->EditValue)) $this->Price->EditValue = ew_FormatNumber($this->Price->EditValue, -2, -1, -2, 0);

			// Company
			$this->Company->EditCustomAttributes = "";
			$this->Company->EditValue = ew_HtmlEncode($this->Company->CurrentValue);
			$this->Company->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Company->FldCaption()));

			// Capacity
			$this->Capacity->EditCustomAttributes = "";
			$this->Capacity->EditValue = ew_HtmlEncode($this->Capacity->CurrentValue);
			$this->Capacity->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->Capacity->FldCaption()));

			// Edit refer script
			// DepartureAirport

			$this->DepartureAirport->HrefValue = "";

			// ArrivalLocation
			$this->ArrivalLocation->HrefValue = "";

			// DepartureTime
			$this->DepartureTime->HrefValue = "";

			// ArrivalTime
			$this->ArrivalTime->HrefValue = "";

			// DepartureDate
			$this->DepartureDate->HrefValue = "";

			// ArrivalDate
			$this->ArrivalDate->HrefValue = "";

			// Price
			$this->Price->HrefValue = "";

			// Company
			$this->Company->HrefValue = "";

			// Capacity
			$this->Capacity->HrefValue = "";
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
		if (!$this->DepartureAirport->FldIsDetailKey && !is_null($this->DepartureAirport->FormValue) && $this->DepartureAirport->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DepartureAirport->FldCaption());
		}
		if (!$this->ArrivalLocation->FldIsDetailKey && !is_null($this->ArrivalLocation->FormValue) && $this->ArrivalLocation->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ArrivalLocation->FldCaption());
		}
		if (!$this->DepartureTime->FldIsDetailKey && !is_null($this->DepartureTime->FormValue) && $this->DepartureTime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DepartureTime->FldCaption());
		}
		if (!ew_CheckTime($this->DepartureTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->DepartureTime->FldErrMsg());
		}
		if (!$this->ArrivalTime->FldIsDetailKey && !is_null($this->ArrivalTime->FormValue) && $this->ArrivalTime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ArrivalTime->FldCaption());
		}
		if (!ew_CheckTime($this->ArrivalTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->ArrivalTime->FldErrMsg());
		}
		if (!$this->DepartureDate->FldIsDetailKey && !is_null($this->DepartureDate->FormValue) && $this->DepartureDate->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DepartureDate->FldCaption());
		}
		if (!ew_CheckDate($this->DepartureDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->DepartureDate->FldErrMsg());
		}
		if (!$this->ArrivalDate->FldIsDetailKey && !is_null($this->ArrivalDate->FormValue) && $this->ArrivalDate->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ArrivalDate->FldCaption());
		}
		if (!ew_CheckDate($this->ArrivalDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->ArrivalDate->FldErrMsg());
		}
		if (!$this->Price->FldIsDetailKey && !is_null($this->Price->FormValue) && $this->Price->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Price->FldCaption());
		}
		if (!ew_CheckNumber($this->Price->FormValue)) {
			ew_AddMessage($gsFormError, $this->Price->FldErrMsg());
		}
		if (!$this->Company->FldIsDetailKey && !is_null($this->Company->FormValue) && $this->Company->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Company->FldCaption());
		}
		if (!$this->Capacity->FldIsDetailKey && !is_null($this->Capacity->FormValue) && $this->Capacity->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Capacity->FldCaption());
		}
		if (!ew_CheckInteger($this->Capacity->FormValue)) {
			ew_AddMessage($gsFormError, $this->Capacity->FldErrMsg());
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

		// DepartureAirport
		$this->DepartureAirport->SetDbValueDef($rsnew, $this->DepartureAirport->CurrentValue, "", FALSE);

		// ArrivalLocation
		$this->ArrivalLocation->SetDbValueDef($rsnew, $this->ArrivalLocation->CurrentValue, "", FALSE);

		// DepartureTime
		$this->DepartureTime->SetDbValueDef($rsnew, $this->DepartureTime->CurrentValue, ew_CurrentTime(), FALSE);

		// ArrivalTime
		$this->ArrivalTime->SetDbValueDef($rsnew, $this->ArrivalTime->CurrentValue, ew_CurrentTime(), FALSE);

		// DepartureDate
		$this->DepartureDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DepartureDate->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// ArrivalDate
		$this->ArrivalDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->ArrivalDate->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// Price
		$this->Price->SetDbValueDef($rsnew, $this->Price->CurrentValue, 0, FALSE);

		// Company
		$this->Company->SetDbValueDef($rsnew, $this->Company->CurrentValue, "", FALSE);

		// Capacity
		$this->Capacity->SetDbValueDef($rsnew, $this->Capacity->CurrentValue, 0, FALSE);

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
			$this->FlightID->setDbValue($conn->Insert_ID());
			$rsnew['FlightID'] = $this->FlightID->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "flightlist.php", $this->TableVar);
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
if (!isset($flight_add)) $flight_add = new cflight_add();

// Page init
$flight_add->Page_Init();

// Page main
$flight_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$flight_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var flight_add = new ew_Page("flight_add");
flight_add.PageID = "add"; // Page ID
var EW_PAGE_ID = flight_add.PageID; // For backward compatibility

// Form object
var fflightadd = new ew_Form("fflightadd");

// Validate form
fflightadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_DepartureAirport");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->DepartureAirport->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ArrivalLocation");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->ArrivalLocation->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DepartureTime");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->DepartureTime->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DepartureTime");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->DepartureTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ArrivalTime");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->ArrivalTime->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ArrivalTime");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->ArrivalTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DepartureDate");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->DepartureDate->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DepartureDate");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->DepartureDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ArrivalDate");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->ArrivalDate->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ArrivalDate");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->ArrivalDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Price");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->Price->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->Price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Company");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->Company->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Capacity");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($flight->Capacity->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Capacity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->Capacity->FldErrMsg()) ?>");

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
fflightadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fflightadd.ValidateRequired = true;
<?php } else { ?>
fflightadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $flight_add->ShowPageHeader(); ?>
<?php
$flight_add->ShowMessage();
?>
<form name="fflightadd" id="fflightadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="flight">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_flightadd" class="table table-bordered table-striped">
<?php if ($flight->DepartureAirport->Visible) { // DepartureAirport ?>
	<tr id="r_DepartureAirport">
		<td><span id="elh_flight_DepartureAirport"><?php echo $flight->DepartureAirport->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->DepartureAirport->CellAttributes() ?>>
<span id="el_flight_DepartureAirport" class="control-group">
<input type="text" data-field="x_DepartureAirport" name="x_DepartureAirport" id="x_DepartureAirport" size="70" maxlength="255" placeholder="<?php echo $flight->DepartureAirport->PlaceHolder ?>" value="<?php echo $flight->DepartureAirport->EditValue ?>"<?php echo $flight->DepartureAirport->EditAttributes() ?>>
</span>
<?php echo $flight->DepartureAirport->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->ArrivalLocation->Visible) { // ArrivalLocation ?>
	<tr id="r_ArrivalLocation">
		<td><span id="elh_flight_ArrivalLocation"><?php echo $flight->ArrivalLocation->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->ArrivalLocation->CellAttributes() ?>>
<span id="el_flight_ArrivalLocation" class="control-group">
<input type="text" data-field="x_ArrivalLocation" name="x_ArrivalLocation" id="x_ArrivalLocation" size="70" maxlength="255" placeholder="<?php echo $flight->ArrivalLocation->PlaceHolder ?>" value="<?php echo $flight->ArrivalLocation->EditValue ?>"<?php echo $flight->ArrivalLocation->EditAttributes() ?>>
</span>
<?php echo $flight->ArrivalLocation->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->DepartureTime->Visible) { // DepartureTime ?>
	<tr id="r_DepartureTime">
		<td><span id="elh_flight_DepartureTime"><?php echo $flight->DepartureTime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->DepartureTime->CellAttributes() ?>>
<span id="el_flight_DepartureTime" class="control-group">
<input type="text" data-field="x_DepartureTime" name="x_DepartureTime" id="x_DepartureTime" size="30" placeholder="<?php echo $flight->DepartureTime->PlaceHolder ?>" value="<?php echo $flight->DepartureTime->EditValue ?>"<?php echo $flight->DepartureTime->EditAttributes() ?>>
</span>
<?php echo $flight->DepartureTime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->ArrivalTime->Visible) { // ArrivalTime ?>
	<tr id="r_ArrivalTime">
		<td><span id="elh_flight_ArrivalTime"><?php echo $flight->ArrivalTime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->ArrivalTime->CellAttributes() ?>>
<span id="el_flight_ArrivalTime" class="control-group">
<input type="text" data-field="x_ArrivalTime" name="x_ArrivalTime" id="x_ArrivalTime" size="30" placeholder="<?php echo $flight->ArrivalTime->PlaceHolder ?>" value="<?php echo $flight->ArrivalTime->EditValue ?>"<?php echo $flight->ArrivalTime->EditAttributes() ?>>
</span>
<?php echo $flight->ArrivalTime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->DepartureDate->Visible) { // DepartureDate ?>
	<tr id="r_DepartureDate">
		<td><span id="elh_flight_DepartureDate"><?php echo $flight->DepartureDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->DepartureDate->CellAttributes() ?>>
<span id="el_flight_DepartureDate" class="control-group">
<input type="text" data-field="x_DepartureDate" name="x_DepartureDate" id="x_DepartureDate" placeholder="<?php echo $flight->DepartureDate->PlaceHolder ?>" value="<?php echo $flight->DepartureDate->EditValue ?>"<?php echo $flight->DepartureDate->EditAttributes() ?>>
</span>
<?php echo $flight->DepartureDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->ArrivalDate->Visible) { // ArrivalDate ?>
	<tr id="r_ArrivalDate">
		<td><span id="elh_flight_ArrivalDate"><?php echo $flight->ArrivalDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->ArrivalDate->CellAttributes() ?>>
<span id="el_flight_ArrivalDate" class="control-group">
<input type="text" data-field="x_ArrivalDate" name="x_ArrivalDate" id="x_ArrivalDate" placeholder="<?php echo $flight->ArrivalDate->PlaceHolder ?>" value="<?php echo $flight->ArrivalDate->EditValue ?>"<?php echo $flight->ArrivalDate->EditAttributes() ?>>
</span>
<?php echo $flight->ArrivalDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->Price->Visible) { // Price ?>
	<tr id="r_Price">
		<td><span id="elh_flight_Price"><?php echo $flight->Price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->Price->CellAttributes() ?>>
<span id="el_flight_Price" class="control-group">
<input type="text" data-field="x_Price" name="x_Price" id="x_Price" size="70" placeholder="<?php echo $flight->Price->PlaceHolder ?>" value="<?php echo $flight->Price->EditValue ?>"<?php echo $flight->Price->EditAttributes() ?>>
</span>
<?php echo $flight->Price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->Company->Visible) { // Company ?>
	<tr id="r_Company">
		<td><span id="elh_flight_Company"><?php echo $flight->Company->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->Company->CellAttributes() ?>>
<span id="el_flight_Company" class="control-group">
<input type="text" data-field="x_Company" name="x_Company" id="x_Company" size="70" maxlength="255" placeholder="<?php echo $flight->Company->PlaceHolder ?>" value="<?php echo $flight->Company->EditValue ?>"<?php echo $flight->Company->EditAttributes() ?>>
</span>
<?php echo $flight->Company->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($flight->Capacity->Visible) { // Capacity ?>
	<tr id="r_Capacity">
		<td><span id="elh_flight_Capacity"><?php echo $flight->Capacity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $flight->Capacity->CellAttributes() ?>>
<span id="el_flight_Capacity" class="control-group">
<input type="text" data-field="x_Capacity" name="x_Capacity" id="x_Capacity" size="70" placeholder="<?php echo $flight->Capacity->PlaceHolder ?>" value="<?php echo $flight->Capacity->EditValue ?>"<?php echo $flight->Capacity->EditAttributes() ?>>
</span>
<?php echo $flight->Capacity->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fflightadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$flight_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$flight_add->Page_Terminate();
?>
