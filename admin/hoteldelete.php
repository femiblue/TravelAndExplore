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

$hotel_delete = NULL; // Initialize page object first

class chotel_delete extends chotel {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'hotel';

	// Page object name
	var $PageObjName = 'hotel_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("hotellist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in hotel class, hotelinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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

			// NumberOfRooms
			$this->NumberOfRooms->LinkCustomAttributes = "";
			$this->NumberOfRooms->HrefValue = "";
			$this->NumberOfRooms->TooltipValue = "";

			// Ranking
			$this->Ranking->LinkCustomAttributes = "";
			$this->Ranking->HrefValue = "";
			$this->Ranking->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['HotelID'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "hotellist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($hotel_delete)) $hotel_delete = new chotel_delete();

// Page init
$hotel_delete->Page_Init();

// Page main
$hotel_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hotel_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hotel_delete = new ew_Page("hotel_delete");
hotel_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = hotel_delete.PageID; // For backward compatibility

// Form object
var fhoteldelete = new ew_Form("fhoteldelete");

// Form_CustomValidate event
fhoteldelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoteldelete.ValidateRequired = true;
<?php } else { ?>
fhoteldelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($hotel_delete->Recordset = $hotel_delete->LoadRecordset())
	$hotel_deleteTotalRecs = $hotel_delete->Recordset->RecordCount(); // Get record count
if ($hotel_deleteTotalRecs <= 0) { // No record found, exit
	if ($hotel_delete->Recordset)
		$hotel_delete->Recordset->Close();
	$hotel_delete->Page_Terminate("hotellist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $hotel_delete->ShowPageHeader(); ?>
<?php
$hotel_delete->ShowMessage();
?>
<form name="fhoteldelete" id="fhoteldelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="hotel">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($hotel_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_hoteldelete" class="ewTable ewTableSeparate">
<?php echo $hotel->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($hotel->HotelID->Visible) { // HotelID ?>
		<td><span id="elh_hotel_HotelID" class="hotel_HotelID"><?php echo $hotel->HotelID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($hotel->Country->Visible) { // Country ?>
		<td><span id="elh_hotel_Country" class="hotel_Country"><?php echo $hotel->Country->FldCaption() ?></span></td>
<?php } ?>
<?php if ($hotel->Title->Visible) { // Title ?>
		<td><span id="elh_hotel_Title" class="hotel_Title"><?php echo $hotel->Title->FldCaption() ?></span></td>
<?php } ?>
<?php if ($hotel->HotelCity->Visible) { // HotelCity ?>
		<td><span id="elh_hotel_HotelCity" class="hotel_HotelCity"><?php echo $hotel->HotelCity->FldCaption() ?></span></td>
<?php } ?>
<?php if ($hotel->NumberOfRooms->Visible) { // NumberOfRooms ?>
		<td><span id="elh_hotel_NumberOfRooms" class="hotel_NumberOfRooms"><?php echo $hotel->NumberOfRooms->FldCaption() ?></span></td>
<?php } ?>
<?php if ($hotel->Ranking->Visible) { // Ranking ?>
		<td><span id="elh_hotel_Ranking" class="hotel_Ranking"><?php echo $hotel->Ranking->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$hotel_delete->RecCnt = 0;
$i = 0;
while (!$hotel_delete->Recordset->EOF) {
	$hotel_delete->RecCnt++;
	$hotel_delete->RowCnt++;

	// Set row properties
	$hotel->ResetAttrs();
	$hotel->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$hotel_delete->LoadRowValues($hotel_delete->Recordset);

	// Render row
	$hotel_delete->RenderRow();
?>
	<tr<?php echo $hotel->RowAttributes() ?>>
<?php if ($hotel->HotelID->Visible) { // HotelID ?>
		<td<?php echo $hotel->HotelID->CellAttributes() ?>>
<span id="el<?php echo $hotel_delete->RowCnt ?>_hotel_HotelID" class="control-group hotel_HotelID">
<span<?php echo $hotel->HotelID->ViewAttributes() ?>>
<?php echo $hotel->HotelID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hotel->Country->Visible) { // Country ?>
		<td<?php echo $hotel->Country->CellAttributes() ?>>
<span id="el<?php echo $hotel_delete->RowCnt ?>_hotel_Country" class="control-group hotel_Country">
<span<?php echo $hotel->Country->ViewAttributes() ?>>
<?php echo $hotel->Country->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hotel->Title->Visible) { // Title ?>
		<td<?php echo $hotel->Title->CellAttributes() ?>>
<span id="el<?php echo $hotel_delete->RowCnt ?>_hotel_Title" class="control-group hotel_Title">
<span<?php echo $hotel->Title->ViewAttributes() ?>>
<?php echo $hotel->Title->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hotel->HotelCity->Visible) { // HotelCity ?>
		<td<?php echo $hotel->HotelCity->CellAttributes() ?>>
<span id="el<?php echo $hotel_delete->RowCnt ?>_hotel_HotelCity" class="control-group hotel_HotelCity">
<span<?php echo $hotel->HotelCity->ViewAttributes() ?>>
<?php echo $hotel->HotelCity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hotel->NumberOfRooms->Visible) { // NumberOfRooms ?>
		<td<?php echo $hotel->NumberOfRooms->CellAttributes() ?>>
<span id="el<?php echo $hotel_delete->RowCnt ?>_hotel_NumberOfRooms" class="control-group hotel_NumberOfRooms">
<span<?php echo $hotel->NumberOfRooms->ViewAttributes() ?>>
<?php echo $hotel->NumberOfRooms->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hotel->Ranking->Visible) { // Ranking ?>
		<td<?php echo $hotel->Ranking->CellAttributes() ?>>
<span id="el<?php echo $hotel_delete->RowCnt ?>_hotel_Ranking" class="control-group hotel_Ranking">
<span<?php echo $hotel->Ranking->ViewAttributes() ?>>
<?php echo $hotel->Ranking->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$hotel_delete->Recordset->MoveNext();
}
$hotel_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fhoteldelete.Init();
</script>
<?php
$hotel_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hotel_delete->Page_Terminate();
?>
