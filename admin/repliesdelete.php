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

$replies_delete = NULL; // Initialize page object first

class creplies_delete extends creplies {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{75D8DC58-3D90-4206-8D81-1D4FF2853B57}";

	// Table name
	var $TableName = 'replies';

	// Page object name
	var $PageObjName = 'replies_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("replieslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in replies class, repliesinfo.php

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

			// ReplyType
			$this->ReplyType->LinkCustomAttributes = "";
			$this->ReplyType->HrefValue = "";
			$this->ReplyType->TooltipValue = "";

			// ReplyStage
			$this->ReplyStage->LinkCustomAttributes = "";
			$this->ReplyStage->HrefValue = "";
			$this->ReplyStage->TooltipValue = "";

			// ReplyID
			$this->ReplyID->LinkCustomAttributes = "";
			$this->ReplyID->HrefValue = "";
			$this->ReplyID->TooltipValue = "";
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
				$sThisKey .= $row['ReplyID'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "replieslist.php", $this->TableVar);
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
if (!isset($replies_delete)) $replies_delete = new creplies_delete();

// Page init
$replies_delete->Page_Init();

// Page main
$replies_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$replies_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var replies_delete = new ew_Page("replies_delete");
replies_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = replies_delete.PageID; // For backward compatibility

// Form object
var frepliesdelete = new ew_Form("frepliesdelete");

// Form_CustomValidate event
frepliesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepliesdelete.ValidateRequired = true;
<?php } else { ?>
frepliesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($replies_delete->Recordset = $replies_delete->LoadRecordset())
	$replies_deleteTotalRecs = $replies_delete->Recordset->RecordCount(); // Get record count
if ($replies_deleteTotalRecs <= 0) { // No record found, exit
	if ($replies_delete->Recordset)
		$replies_delete->Recordset->Close();
	$replies_delete->Page_Terminate("replieslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $replies_delete->ShowPageHeader(); ?>
<?php
$replies_delete->ShowMessage();
?>
<form name="frepliesdelete" id="frepliesdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="replies">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($replies_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_repliesdelete" class="ewTable ewTableSeparate">
<?php echo $replies->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($replies->ReplyType->Visible) { // ReplyType ?>
		<td><span id="elh_replies_ReplyType" class="replies_ReplyType"><?php echo $replies->ReplyType->FldCaption() ?></span></td>
<?php } ?>
<?php if ($replies->ReplyStage->Visible) { // ReplyStage ?>
		<td><span id="elh_replies_ReplyStage" class="replies_ReplyStage"><?php echo $replies->ReplyStage->FldCaption() ?></span></td>
<?php } ?>
<?php if ($replies->ReplyID->Visible) { // ReplyID ?>
		<td><span id="elh_replies_ReplyID" class="replies_ReplyID"><?php echo $replies->ReplyID->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$replies_delete->RecCnt = 0;
$i = 0;
while (!$replies_delete->Recordset->EOF) {
	$replies_delete->RecCnt++;
	$replies_delete->RowCnt++;

	// Set row properties
	$replies->ResetAttrs();
	$replies->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$replies_delete->LoadRowValues($replies_delete->Recordset);

	// Render row
	$replies_delete->RenderRow();
?>
	<tr<?php echo $replies->RowAttributes() ?>>
<?php if ($replies->ReplyType->Visible) { // ReplyType ?>
		<td<?php echo $replies->ReplyType->CellAttributes() ?>>
<span id="el<?php echo $replies_delete->RowCnt ?>_replies_ReplyType" class="control-group replies_ReplyType">
<span<?php echo $replies->ReplyType->ViewAttributes() ?>>
<?php echo $replies->ReplyType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($replies->ReplyStage->Visible) { // ReplyStage ?>
		<td<?php echo $replies->ReplyStage->CellAttributes() ?>>
<span id="el<?php echo $replies_delete->RowCnt ?>_replies_ReplyStage" class="control-group replies_ReplyStage">
<span<?php echo $replies->ReplyStage->ViewAttributes() ?>>
<?php echo $replies->ReplyStage->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($replies->ReplyID->Visible) { // ReplyID ?>
		<td<?php echo $replies->ReplyID->CellAttributes() ?>>
<span id="el<?php echo $replies_delete->RowCnt ?>_replies_ReplyID" class="control-group replies_ReplyID">
<span<?php echo $replies->ReplyID->ViewAttributes() ?>>
<?php echo $replies->ReplyID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$replies_delete->Recordset->MoveNext();
}
$replies_delete->Recordset->Close();
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
frepliesdelete.Init();
</script>
<?php
$replies_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$replies_delete->Page_Terminate();
?>
