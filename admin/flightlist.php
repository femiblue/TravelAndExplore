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

$flight_list = NULL; // Initialize page object first

class cflight_list extends cflight {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'flight';

	// Page object name
	var $PageObjName = 'flight_list';

	// Grid form hidden field names
	var $FormName = 'fflightlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (flight)
		if (!isset($GLOBALS["flight"])) {
			$GLOBALS["flight"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["flight"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "flightadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "flightdelete.php";
		$this->MultiUpdateUrl = "flightupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'flight', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->FlightID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->FlightID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->FlightID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->DepartureAirport, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ArrivalLocation, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->Company, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->FlightID); // FlightID
			$this->UpdateSort($this->DepartureAirport); // DepartureAirport
			$this->UpdateSort($this->ArrivalLocation); // ArrivalLocation
			$this->UpdateSort($this->DepartureTime); // DepartureTime
			$this->UpdateSort($this->ArrivalTime); // ArrivalTime
			$this->UpdateSort($this->DepartureDate); // DepartureDate
			$this->UpdateSort($this->ArrivalDate); // ArrivalDate
			$this->UpdateSort($this->Price); // Price
			$this->UpdateSort($this->Company); // Company
			$this->UpdateSort($this->Capacity); // Capacity
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->FlightID->setSort("");
				$this->DepartureAirport->setSort("");
				$this->ArrivalLocation->setSort("");
				$this->DepartureTime->setSort("");
				$this->ArrivalTime->setSort("");
				$this->DepartureDate->setSort("");
				$this->ArrivalDate->setSort("");
				$this->Price->setSort("");
				$this->Company->setSort("");
				$this->Capacity->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->FlightID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fflightlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// FlightID
			$this->FlightID->LinkCustomAttributes = "";
			$this->FlightID->HrefValue = "";
			$this->FlightID->TooltipValue = "";

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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($flight_list)) $flight_list = new cflight_list();

// Page init
$flight_list->Page_Init();

// Page main
$flight_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$flight_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var flight_list = new ew_Page("flight_list");
flight_list.PageID = "list"; // Page ID
var EW_PAGE_ID = flight_list.PageID; // For backward compatibility

// Form object
var fflightlist = new ew_Form("fflightlist");
fflightlist.FormKeyCountName = '<?php echo $flight_list->FormKeyCountName ?>';

// Form_CustomValidate event
fflightlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fflightlist.ValidateRequired = true;
<?php } else { ?>
fflightlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fflightlistsrch = new ew_Form("fflightlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($flight_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $flight_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$flight_list->TotalRecs = $flight->SelectRecordCount();
	} else {
		if ($flight_list->Recordset = $flight_list->LoadRecordset())
			$flight_list->TotalRecs = $flight_list->Recordset->RecordCount();
	}
	$flight_list->StartRec = 1;
	if ($flight_list->DisplayRecs <= 0 || ($flight->Export <> "" && $flight->ExportAll)) // Display all records
		$flight_list->DisplayRecs = $flight_list->TotalRecs;
	if (!($flight->Export <> "" && $flight->ExportAll))
		$flight_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$flight_list->Recordset = $flight_list->LoadRecordset($flight_list->StartRec-1, $flight_list->DisplayRecs);
$flight_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($flight->Export == "" && $flight->CurrentAction == "") { ?>
<form name="fflightlistsrch" id="fflightlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fflightlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fflightlistsrch_SearchGroup" href="#fflightlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fflightlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fflightlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="flight">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($flight_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $flight_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($flight_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($flight_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($flight_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</td></tr></table>
</form>
<?php } ?>
<?php } ?>
<?php $flight_list->ShowPageHeader(); ?>
<?php
$flight_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fflightlist" id="fflightlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="flight">
<div id="gmp_flight" class="ewGridMiddlePanel">
<?php if ($flight_list->TotalRecs > 0) { ?>
<table id="tbl_flightlist" class="ewTable ewTableSeparate">
<?php echo $flight->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$flight_list->RenderListOptions();

// Render list options (header, left)
$flight_list->ListOptions->Render("header", "left");
?>
<?php if ($flight->FlightID->Visible) { // FlightID ?>
	<?php if ($flight->SortUrl($flight->FlightID) == "") { ?>
		<td><div id="elh_flight_FlightID" class="flight_FlightID"><div class="ewTableHeaderCaption"><?php echo $flight->FlightID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->FlightID) ?>',1);"><div id="elh_flight_FlightID" class="flight_FlightID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->FlightID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->FlightID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->FlightID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->DepartureAirport->Visible) { // DepartureAirport ?>
	<?php if ($flight->SortUrl($flight->DepartureAirport) == "") { ?>
		<td><div id="elh_flight_DepartureAirport" class="flight_DepartureAirport"><div class="ewTableHeaderCaption"><?php echo $flight->DepartureAirport->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->DepartureAirport) ?>',1);"><div id="elh_flight_DepartureAirport" class="flight_DepartureAirport">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->DepartureAirport->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($flight->DepartureAirport->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->DepartureAirport->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->ArrivalLocation->Visible) { // ArrivalLocation ?>
	<?php if ($flight->SortUrl($flight->ArrivalLocation) == "") { ?>
		<td><div id="elh_flight_ArrivalLocation" class="flight_ArrivalLocation"><div class="ewTableHeaderCaption"><?php echo $flight->ArrivalLocation->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->ArrivalLocation) ?>',1);"><div id="elh_flight_ArrivalLocation" class="flight_ArrivalLocation">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->ArrivalLocation->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($flight->ArrivalLocation->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->ArrivalLocation->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->DepartureTime->Visible) { // DepartureTime ?>
	<?php if ($flight->SortUrl($flight->DepartureTime) == "") { ?>
		<td><div id="elh_flight_DepartureTime" class="flight_DepartureTime"><div class="ewTableHeaderCaption"><?php echo $flight->DepartureTime->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->DepartureTime) ?>',1);"><div id="elh_flight_DepartureTime" class="flight_DepartureTime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->DepartureTime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->DepartureTime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->DepartureTime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->ArrivalTime->Visible) { // ArrivalTime ?>
	<?php if ($flight->SortUrl($flight->ArrivalTime) == "") { ?>
		<td><div id="elh_flight_ArrivalTime" class="flight_ArrivalTime"><div class="ewTableHeaderCaption"><?php echo $flight->ArrivalTime->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->ArrivalTime) ?>',1);"><div id="elh_flight_ArrivalTime" class="flight_ArrivalTime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->ArrivalTime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->ArrivalTime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->ArrivalTime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->DepartureDate->Visible) { // DepartureDate ?>
	<?php if ($flight->SortUrl($flight->DepartureDate) == "") { ?>
		<td><div id="elh_flight_DepartureDate" class="flight_DepartureDate"><div class="ewTableHeaderCaption"><?php echo $flight->DepartureDate->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->DepartureDate) ?>',1);"><div id="elh_flight_DepartureDate" class="flight_DepartureDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->DepartureDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->DepartureDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->DepartureDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->ArrivalDate->Visible) { // ArrivalDate ?>
	<?php if ($flight->SortUrl($flight->ArrivalDate) == "") { ?>
		<td><div id="elh_flight_ArrivalDate" class="flight_ArrivalDate"><div class="ewTableHeaderCaption"><?php echo $flight->ArrivalDate->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->ArrivalDate) ?>',1);"><div id="elh_flight_ArrivalDate" class="flight_ArrivalDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->ArrivalDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->ArrivalDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->ArrivalDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->Price->Visible) { // Price ?>
	<?php if ($flight->SortUrl($flight->Price) == "") { ?>
		<td><div id="elh_flight_Price" class="flight_Price"><div class="ewTableHeaderCaption"><?php echo $flight->Price->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->Price) ?>',1);"><div id="elh_flight_Price" class="flight_Price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->Price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->Price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->Price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->Company->Visible) { // Company ?>
	<?php if ($flight->SortUrl($flight->Company) == "") { ?>
		<td><div id="elh_flight_Company" class="flight_Company"><div class="ewTableHeaderCaption"><?php echo $flight->Company->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->Company) ?>',1);"><div id="elh_flight_Company" class="flight_Company">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->Company->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($flight->Company->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->Company->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($flight->Capacity->Visible) { // Capacity ?>
	<?php if ($flight->SortUrl($flight->Capacity) == "") { ?>
		<td><div id="elh_flight_Capacity" class="flight_Capacity"><div class="ewTableHeaderCaption"><?php echo $flight->Capacity->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $flight->SortUrl($flight->Capacity) ?>',1);"><div id="elh_flight_Capacity" class="flight_Capacity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $flight->Capacity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($flight->Capacity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($flight->Capacity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$flight_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($flight->ExportAll && $flight->Export <> "") {
	$flight_list->StopRec = $flight_list->TotalRecs;
} else {

	// Set the last record to display
	if ($flight_list->TotalRecs > $flight_list->StartRec + $flight_list->DisplayRecs - 1)
		$flight_list->StopRec = $flight_list->StartRec + $flight_list->DisplayRecs - 1;
	else
		$flight_list->StopRec = $flight_list->TotalRecs;
}
$flight_list->RecCnt = $flight_list->StartRec - 1;
if ($flight_list->Recordset && !$flight_list->Recordset->EOF) {
	$flight_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $flight_list->StartRec > 1)
		$flight_list->Recordset->Move($flight_list->StartRec - 1);
} elseif (!$flight->AllowAddDeleteRow && $flight_list->StopRec == 0) {
	$flight_list->StopRec = $flight->GridAddRowCount;
}

// Initialize aggregate
$flight->RowType = EW_ROWTYPE_AGGREGATEINIT;
$flight->ResetAttrs();
$flight_list->RenderRow();
while ($flight_list->RecCnt < $flight_list->StopRec) {
	$flight_list->RecCnt++;
	if (intval($flight_list->RecCnt) >= intval($flight_list->StartRec)) {
		$flight_list->RowCnt++;

		// Set up key count
		$flight_list->KeyCount = $flight_list->RowIndex;

		// Init row class and style
		$flight->ResetAttrs();
		$flight->CssClass = "";
		if ($flight->CurrentAction == "gridadd") {
		} else {
			$flight_list->LoadRowValues($flight_list->Recordset); // Load row values
		}
		$flight->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$flight->RowAttrs = array_merge($flight->RowAttrs, array('data-rowindex'=>$flight_list->RowCnt, 'id'=>'r' . $flight_list->RowCnt . '_flight', 'data-rowtype'=>$flight->RowType));

		// Render row
		$flight_list->RenderRow();

		// Render list options
		$flight_list->RenderListOptions();
?>
	<tr<?php echo $flight->RowAttributes() ?>>
<?php

// Render list options (body, left)
$flight_list->ListOptions->Render("body", "left", $flight_list->RowCnt);
?>
	<?php if ($flight->FlightID->Visible) { // FlightID ?>
		<td<?php echo $flight->FlightID->CellAttributes() ?>>
<span<?php echo $flight->FlightID->ViewAttributes() ?>>
<?php echo $flight->FlightID->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->DepartureAirport->Visible) { // DepartureAirport ?>
		<td<?php echo $flight->DepartureAirport->CellAttributes() ?>>
<span<?php echo $flight->DepartureAirport->ViewAttributes() ?>>
<?php echo $flight->DepartureAirport->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->ArrivalLocation->Visible) { // ArrivalLocation ?>
		<td<?php echo $flight->ArrivalLocation->CellAttributes() ?>>
<span<?php echo $flight->ArrivalLocation->ViewAttributes() ?>>
<?php echo $flight->ArrivalLocation->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->DepartureTime->Visible) { // DepartureTime ?>
		<td<?php echo $flight->DepartureTime->CellAttributes() ?>>
<span<?php echo $flight->DepartureTime->ViewAttributes() ?>>
<?php echo $flight->DepartureTime->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->ArrivalTime->Visible) { // ArrivalTime ?>
		<td<?php echo $flight->ArrivalTime->CellAttributes() ?>>
<span<?php echo $flight->ArrivalTime->ViewAttributes() ?>>
<?php echo $flight->ArrivalTime->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->DepartureDate->Visible) { // DepartureDate ?>
		<td<?php echo $flight->DepartureDate->CellAttributes() ?>>
<span<?php echo $flight->DepartureDate->ViewAttributes() ?>>
<?php echo $flight->DepartureDate->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->ArrivalDate->Visible) { // ArrivalDate ?>
		<td<?php echo $flight->ArrivalDate->CellAttributes() ?>>
<span<?php echo $flight->ArrivalDate->ViewAttributes() ?>>
<?php echo $flight->ArrivalDate->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->Price->Visible) { // Price ?>
		<td<?php echo $flight->Price->CellAttributes() ?>>
<span<?php echo $flight->Price->ViewAttributes() ?>>
<?php echo $flight->Price->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->Company->Visible) { // Company ?>
		<td<?php echo $flight->Company->CellAttributes() ?>>
<span<?php echo $flight->Company->ViewAttributes() ?>>
<?php echo $flight->Company->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($flight->Capacity->Visible) { // Capacity ?>
		<td<?php echo $flight->Capacity->CellAttributes() ?>>
<span<?php echo $flight->Capacity->ViewAttributes() ?>>
<?php echo $flight->Capacity->ListViewValue() ?></span>
<a id="<?php echo $flight_list->PageObjName . "_row_" . $flight_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$flight_list->ListOptions->Render("body", "right", $flight_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($flight->CurrentAction <> "gridadd")
		$flight_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($flight->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($flight_list->Recordset)
	$flight_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($flight->CurrentAction <> "gridadd" && $flight->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($flight_list->Pager)) $flight_list->Pager = new cPrevNextPager($flight_list->StartRec, $flight_list->DisplayRecs, $flight_list->TotalRecs) ?>
<?php if ($flight_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($flight_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $flight_list->PageUrl() ?>start=<?php echo $flight_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($flight_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $flight_list->PageUrl() ?>start=<?php echo $flight_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $flight_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($flight_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $flight_list->PageUrl() ?>start=<?php echo $flight_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($flight_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $flight_list->PageUrl() ?>start=<?php echo $flight_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $flight_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $flight_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $flight_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $flight_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($flight_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($flight_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fflightlistsrch.Init();
fflightlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$flight_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$flight_list->Page_Terminate();
?>
