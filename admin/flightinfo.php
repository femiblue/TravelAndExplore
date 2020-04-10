<?php

// Global variable for table object
$flight = NULL;

//
// Table class for flight
//
class cflight extends cTable {
	var $FlightID;
	var $DepartureAirport;
	var $ArrivalLocation;
	var $DepartureTime;
	var $ArrivalTime;
	var $DepartureDate;
	var $ArrivalDate;
	var $Price;
	var $Company;
	var $Capacity;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'flight';
		$this->TableName = 'flight';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// FlightID
		$this->FlightID = new cField('flight', 'flight', 'x_FlightID', 'FlightID', '`FlightID`', '`FlightID`', 3, -1, FALSE, '`FlightID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->FlightID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['FlightID'] = &$this->FlightID;

		// DepartureAirport
		$this->DepartureAirport = new cField('flight', 'flight', 'x_DepartureAirport', 'DepartureAirport', '`DepartureAirport`', '`DepartureAirport`', 200, -1, FALSE, '`DepartureAirport`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DepartureAirport'] = &$this->DepartureAirport;

		// ArrivalLocation
		$this->ArrivalLocation = new cField('flight', 'flight', 'x_ArrivalLocation', 'ArrivalLocation', '`ArrivalLocation`', '`ArrivalLocation`', 200, -1, FALSE, '`ArrivalLocation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ArrivalLocation'] = &$this->ArrivalLocation;

		// DepartureTime
		$this->DepartureTime = new cField('flight', 'flight', 'x_DepartureTime', 'DepartureTime', '`DepartureTime`', 'DATE_FORMAT(`DepartureTime`, \'%Y-%m-%d\')', 134, -1, FALSE, '`DepartureTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->DepartureTime->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['DepartureTime'] = &$this->DepartureTime;

		// ArrivalTime
		$this->ArrivalTime = new cField('flight', 'flight', 'x_ArrivalTime', 'ArrivalTime', '`ArrivalTime`', 'DATE_FORMAT(`ArrivalTime`, \'%Y-%m-%d\')', 134, -1, FALSE, '`ArrivalTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ArrivalTime->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['ArrivalTime'] = &$this->ArrivalTime;

		// DepartureDate
		$this->DepartureDate = new cField('flight', 'flight', 'x_DepartureDate', 'DepartureDate', '`DepartureDate`', 'DATE_FORMAT(`DepartureDate`, \'%Y-%m-%d\')', 133, 5, FALSE, '`DepartureDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->DepartureDate->FldDefaultErrMsg = str_replace("%s", "-", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['DepartureDate'] = &$this->DepartureDate;

		// ArrivalDate
		$this->ArrivalDate = new cField('flight', 'flight', 'x_ArrivalDate', 'ArrivalDate', '`ArrivalDate`', 'DATE_FORMAT(`ArrivalDate`, \'%Y-%m-%d\')', 133, 5, FALSE, '`ArrivalDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ArrivalDate->FldDefaultErrMsg = str_replace("%s", "-", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['ArrivalDate'] = &$this->ArrivalDate;

		// Price
		$this->Price = new cField('flight', 'flight', 'x_Price', 'Price', '`Price`', '`Price`', 5, -1, FALSE, '`Price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Price->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Price'] = &$this->Price;

		// Company
		$this->Company = new cField('flight', 'flight', 'x_Company', 'Company', '`Company`', '`Company`', 200, -1, FALSE, '`Company`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Company'] = &$this->Company;

		// Capacity
		$this->Capacity = new cField('flight', 'flight', 'x_Capacity', 'Capacity', '`Capacity`', '`Capacity`', 3, -1, FALSE, '`Capacity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Capacity->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Capacity'] = &$this->Capacity;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`flight`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`flight`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('FlightID', $rs))
				ew_AddFilter($where, ew_QuotedName('FlightID') . '=' . ew_QuotedValue($rs['FlightID'], $this->FlightID->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`FlightID` = @FlightID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->FlightID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@FlightID@", ew_AdjustSql($this->FlightID->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "flightlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "flightlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("flightview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("flightview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "flightadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("flightedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("flightadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("flightdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->FlightID->CurrentValue)) {
			$sUrl .= "FlightID=" . urlencode($this->FlightID->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["FlightID"]; // FlightID

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->FlightID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->FlightID->Exportable) $Doc->ExportCaption($this->FlightID);
				if ($this->DepartureAirport->Exportable) $Doc->ExportCaption($this->DepartureAirport);
				if ($this->ArrivalLocation->Exportable) $Doc->ExportCaption($this->ArrivalLocation);
				if ($this->DepartureTime->Exportable) $Doc->ExportCaption($this->DepartureTime);
				if ($this->ArrivalTime->Exportable) $Doc->ExportCaption($this->ArrivalTime);
				if ($this->DepartureDate->Exportable) $Doc->ExportCaption($this->DepartureDate);
				if ($this->ArrivalDate->Exportable) $Doc->ExportCaption($this->ArrivalDate);
				if ($this->Price->Exportable) $Doc->ExportCaption($this->Price);
				if ($this->Company->Exportable) $Doc->ExportCaption($this->Company);
				if ($this->Capacity->Exportable) $Doc->ExportCaption($this->Capacity);
			} else {
				if ($this->FlightID->Exportable) $Doc->ExportCaption($this->FlightID);
				if ($this->DepartureAirport->Exportable) $Doc->ExportCaption($this->DepartureAirport);
				if ($this->ArrivalLocation->Exportable) $Doc->ExportCaption($this->ArrivalLocation);
				if ($this->DepartureTime->Exportable) $Doc->ExportCaption($this->DepartureTime);
				if ($this->ArrivalTime->Exportable) $Doc->ExportCaption($this->ArrivalTime);
				if ($this->DepartureDate->Exportable) $Doc->ExportCaption($this->DepartureDate);
				if ($this->ArrivalDate->Exportable) $Doc->ExportCaption($this->ArrivalDate);
				if ($this->Price->Exportable) $Doc->ExportCaption($this->Price);
				if ($this->Company->Exportable) $Doc->ExportCaption($this->Company);
				if ($this->Capacity->Exportable) $Doc->ExportCaption($this->Capacity);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->FlightID->Exportable) $Doc->ExportField($this->FlightID);
					if ($this->DepartureAirport->Exportable) $Doc->ExportField($this->DepartureAirport);
					if ($this->ArrivalLocation->Exportable) $Doc->ExportField($this->ArrivalLocation);
					if ($this->DepartureTime->Exportable) $Doc->ExportField($this->DepartureTime);
					if ($this->ArrivalTime->Exportable) $Doc->ExportField($this->ArrivalTime);
					if ($this->DepartureDate->Exportable) $Doc->ExportField($this->DepartureDate);
					if ($this->ArrivalDate->Exportable) $Doc->ExportField($this->ArrivalDate);
					if ($this->Price->Exportable) $Doc->ExportField($this->Price);
					if ($this->Company->Exportable) $Doc->ExportField($this->Company);
					if ($this->Capacity->Exportable) $Doc->ExportField($this->Capacity);
				} else {
					if ($this->FlightID->Exportable) $Doc->ExportField($this->FlightID);
					if ($this->DepartureAirport->Exportable) $Doc->ExportField($this->DepartureAirport);
					if ($this->ArrivalLocation->Exportable) $Doc->ExportField($this->ArrivalLocation);
					if ($this->DepartureTime->Exportable) $Doc->ExportField($this->DepartureTime);
					if ($this->ArrivalTime->Exportable) $Doc->ExportField($this->ArrivalTime);
					if ($this->DepartureDate->Exportable) $Doc->ExportField($this->DepartureDate);
					if ($this->ArrivalDate->Exportable) $Doc->ExportField($this->ArrivalDate);
					if ($this->Price->Exportable) $Doc->ExportField($this->Price);
					if ($this->Company->Exportable) $Doc->ExportField($this->Company);
					if ($this->Capacity->Exportable) $Doc->ExportField($this->Capacity);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>