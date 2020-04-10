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

$room_delete = NULL; // Initialize page object first

class croom_delete extends croom {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'room';

	// Page object name
	var $PageObjName = 'room_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->RoomId->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("roomlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in room class, roominfo.php

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

			// RoomId
			$this->RoomId->LinkCustomAttributes = "";
			$this->RoomId->HrefValue = "";
			$this->RoomId->TooltipValue = "";

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
				$sThisKey .= $row['RoomId'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "roomlist.php", $this->TableVar);
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
if (!isset($room_delete)) $room_delete = new croom_delete();

// Page init
$room_delete->Page_Init();

// Page main
$room_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$room_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var room_delete = new ew_Page("room_delete");
room_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = room_delete.PageID; // For backward compatibility

// Form object
var froomdelete = new ew_Form("froomdelete");

// Form_CustomValidate event
froomdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
froomdelete.ValidateRequired = true;
<?php } else { ?>
froomdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
froomdelete.Lists["x_Hotel"] = {"LinkField":"x_HotelID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Title","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($room_delete->Recordset = $room_delete->LoadRecordset())
	$room_deleteTotalRecs = $room_delete->Recordset->RecordCount(); // Get record count
if ($room_deleteTotalRecs <= 0) { // No record found, exit
	if ($room_delete->Recordset)
		$room_delete->Recordset->Close();
	$room_delete->Page_Terminate("roomlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $room_delete->ShowPageHeader(); ?>
<?php
$room_delete->ShowMessage();
?>
<form name="froomdelete" id="froomdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="room">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($room_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_roomdelete" class="ewTable ewTableSeparate">
<?php echo $room->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($room->RoomId->Visible) { // RoomId ?>
		<td><span id="elh_room_RoomId" class="room_RoomId"><?php echo $room->RoomId->FldCaption() ?></span></td>
<?php } ?>
<?php if ($room->RoomNumber->Visible) { // RoomNumber ?>
		<td><span id="elh_room_RoomNumber" class="room_RoomNumber"><?php echo $room->RoomNumber->FldCaption() ?></span></td>
<?php } ?>
<?php if ($room->Price->Visible) { // Price ?>
		<td><span id="elh_room_Price" class="room_Price"><?php echo $room->Price->FldCaption() ?></span></td>
<?php } ?>
<?php if ($room->Type->Visible) { // Type ?>
		<td><span id="elh_room_Type" class="room_Type"><?php echo $room->Type->FldCaption() ?></span></td>
<?php } ?>
<?php if ($room->Hotel->Visible) { // Hotel ?>
		<td><span id="elh_room_Hotel" class="room_Hotel"><?php echo $room->Hotel->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$room_delete->RecCnt = 0;
$i = 0;
while (!$room_delete->Recordset->EOF) {
	$room_delete->RecCnt++;
	$room_delete->RowCnt++;

	// Set row properties
	$room->ResetAttrs();
	$room->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$room_delete->LoadRowValues($room_delete->Recordset);

	// Render row
	$room_delete->RenderRow();
?>
	<tr<?php echo $room->RowAttributes() ?>>
<?php if ($room->RoomId->Visible) { // RoomId ?>
		<td<?php echo $room->RoomId->CellAttributes() ?>>
<span id="el<?php echo $room_delete->RowCnt ?>_room_RoomId" class="control-group room_RoomId">
<span<?php echo $room->RoomId->ViewAttributes() ?>>
<?php echo $room->RoomId->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($room->RoomNumber->Visible) { // RoomNumber ?>
		<td<?php echo $room->RoomNumber->CellAttributes() ?>>
<span id="el<?php echo $room_delete->RowCnt ?>_room_RoomNumber" class="control-group room_RoomNumber">
<span<?php echo $room->RoomNumber->ViewAttributes() ?>>
<?php echo $room->RoomNumber->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($room->Price->Visible) { // Price ?>
		<td<?php echo $room->Price->CellAttributes() ?>>
<span id="el<?php echo $room_delete->RowCnt ?>_room_Price" class="control-group room_Price">
<span<?php echo $room->Price->ViewAttributes() ?>>
<?php echo $room->Price->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($room->Type->Visible) { // Type ?>
		<td<?php echo $room->Type->CellAttributes() ?>>
<span id="el<?php echo $room_delete->RowCnt ?>_room_Type" class="control-group room_Type">
<span<?php echo $room->Type->ViewAttributes() ?>>
<?php echo $room->Type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($room->Hotel->Visible) { // Hotel ?>
		<td<?php echo $room->Hotel->CellAttributes() ?>>
<span id="el<?php echo $room_delete->RowCnt ?>_room_Hotel" class="control-group room_Hotel">
<span<?php echo $room->Hotel->ViewAttributes() ?>>
<?php echo $room->Hotel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$room_delete->Recordset->MoveNext();
}
$room_delete->Recordset->Close();
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
froomdelete.Init();
</script>
<?php
$room_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$room_delete->Page_Terminate();
?>
