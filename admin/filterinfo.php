<?php

// Global variable for table object
$filter = NULL;

//
// Table class for filter
//
class cfilter extends cTable {
	var $FilterID;
	var $Date1;
	var $Date2;
	var $Continent;
	var $Environment;
	var $Season;
	var $Weather;
	var $Age;
	var $Activities;
	var $ActivityPrice;
	var $Country;
	var $City;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'filter';
		$this->TableName = 'filter';
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

		// FilterID
		$this->FilterID = new cField('filter', 'filter', 'x_FilterID', 'FilterID', '`FilterID`', '`FilterID`', 3, -1, FALSE, '`FilterID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->FilterID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['FilterID'] = &$this->FilterID;

		// Date1
		$this->Date1 = new cField('filter', 'filter', 'x_Date1', 'Date1', '`Date1`', 'DATE_FORMAT(`Date1`, \'%Y-%m-%d\')', 135, 5, FALSE, '`Date1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Date1->FldDefaultErrMsg = str_replace("%s", "-", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['Date1'] = &$this->Date1;

		// Date2
		$this->Date2 = new cField('filter', 'filter', 'x_Date2', 'Date2', '`Date2`', 'DATE_FORMAT(`Date2`, \'%Y-%m-%d\')', 135, 5, FALSE, '`Date2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Date2->FldDefaultErrMsg = str_replace("%s", "-", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['Date2'] = &$this->Date2;

		// Continent
		$this->Continent = new cField('filter', 'filter', 'x_Continent', 'Continent', '`Continent`', '`Continent`', 200, -1, FALSE, '`Continent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Continent'] = &$this->Continent;

		// Environment
		$this->Environment = new cField('filter', 'filter', 'x_Environment', 'Environment', '`Environment`', '`Environment`', 201, -1, FALSE, '`Environment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Environment'] = &$this->Environment;

		// Season
		$this->Season = new cField('filter', 'filter', 'x_Season', 'Season', '`Season`', '`Season`', 200, -1, FALSE, '`Season`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Season'] = &$this->Season;

		// Weather
		$this->Weather = new cField('filter', 'filter', 'x_Weather', 'Weather', '`Weather`', '`Weather`', 201, -1, FALSE, '`Weather`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Weather'] = &$this->Weather;

		// Age
		$this->Age = new cField('filter', 'filter', 'x_Age', 'Age', '`Age`', '`Age`', 200, -1, FALSE, '`Age`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Age'] = &$this->Age;

		// Activities
		$this->Activities = new cField('filter', 'filter', 'x_Activities', 'Activities', '`Activities`', '`Activities`', 201, -1, FALSE, '`Activities`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Activities'] = &$this->Activities;

		// ActivityPrice
		$this->ActivityPrice = new cField('filter', 'filter', 'x_ActivityPrice', 'ActivityPrice', '`ActivityPrice`', '`ActivityPrice`', 5, -1, FALSE, '`ActivityPrice`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ActivityPrice->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['ActivityPrice'] = &$this->ActivityPrice;

		// Country
		$this->Country = new cField('filter', 'filter', 'x_Country', 'Country', '`Country`', '`Country`', 200, -1, FALSE, '`Country`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Country'] = &$this->Country;

		// City
		$this->City = new cField('filter', 'filter', 'x_City', 'City', '`City`', '`City`', 200, -1, FALSE, '`City`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['City'] = &$this->City;
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
		return "`filter`";
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
	var $UpdateTable = "`filter`";

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
			if (array_key_exists('FilterID', $rs))
				ew_AddFilter($where, ew_QuotedName('FilterID') . '=' . ew_QuotedValue($rs['FilterID'], $this->FilterID->FldDataType));
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
		return "`FilterID` = @FilterID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->FilterID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@FilterID@", ew_AdjustSql($this->FilterID->CurrentValue), $sKeyFilter); // Replace key value
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
			return "filterlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "filterlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("filterview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("filterview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "filteradd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("filteredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("filteradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("filterdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->FilterID->CurrentValue)) {
			$sUrl .= "FilterID=" . urlencode($this->FilterID->CurrentValue);
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
			$arKeys[] = @$_GET["FilterID"]; // FilterID

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
			$this->FilterID->CurrentValue = $key;
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
		$this->FilterID->setDbValue($rs->fields('FilterID'));
		$this->Date1->setDbValue($rs->fields('Date1'));
		$this->Date2->setDbValue($rs->fields('Date2'));
		$this->Continent->setDbValue($rs->fields('Continent'));
		$this->Environment->setDbValue($rs->fields('Environment'));
		$this->Season->setDbValue($rs->fields('Season'));
		$this->Weather->setDbValue($rs->fields('Weather'));
		$this->Age->setDbValue($rs->fields('Age'));
		$this->Activities->setDbValue($rs->fields('Activities'));
		$this->ActivityPrice->setDbValue($rs->fields('ActivityPrice'));
		$this->Country->setDbValue($rs->fields('Country'));
		$this->City->setDbValue($rs->fields('City'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// FilterID
		// Date1
		// Date2
		// Continent
		// Environment
		// Season
		// Weather
		// Age
		// Activities
		// ActivityPrice
		// Country
		// City
		// FilterID

		$this->FilterID->ViewValue = $this->FilterID->CurrentValue;
		$this->FilterID->ViewCustomAttributes = "";

		// Date1
		$this->Date1->ViewValue = $this->Date1->CurrentValue;
		$this->Date1->ViewValue = ew_FormatDateTime($this->Date1->ViewValue, 5);
		$this->Date1->ViewCustomAttributes = "";

		// Date2
		$this->Date2->ViewValue = $this->Date2->CurrentValue;
		$this->Date2->ViewValue = ew_FormatDateTime($this->Date2->ViewValue, 5);
		$this->Date2->ViewCustomAttributes = "";

		// Continent
		if (strval($this->Continent->CurrentValue) <> "") {
			switch ($this->Continent->CurrentValue) {
				case $this->Continent->FldTagValue(1):
					$this->Continent->ViewValue = $this->Continent->FldTagCaption(1) <> "" ? $this->Continent->FldTagCaption(1) : $this->Continent->CurrentValue;
					break;
				case $this->Continent->FldTagValue(2):
					$this->Continent->ViewValue = $this->Continent->FldTagCaption(2) <> "" ? $this->Continent->FldTagCaption(2) : $this->Continent->CurrentValue;
					break;
				case $this->Continent->FldTagValue(3):
					$this->Continent->ViewValue = $this->Continent->FldTagCaption(3) <> "" ? $this->Continent->FldTagCaption(3) : $this->Continent->CurrentValue;
					break;
				case $this->Continent->FldTagValue(4):
					$this->Continent->ViewValue = $this->Continent->FldTagCaption(4) <> "" ? $this->Continent->FldTagCaption(4) : $this->Continent->CurrentValue;
					break;
				case $this->Continent->FldTagValue(5):
					$this->Continent->ViewValue = $this->Continent->FldTagCaption(5) <> "" ? $this->Continent->FldTagCaption(5) : $this->Continent->CurrentValue;
					break;
				case $this->Continent->FldTagValue(6):
					$this->Continent->ViewValue = $this->Continent->FldTagCaption(6) <> "" ? $this->Continent->FldTagCaption(6) : $this->Continent->CurrentValue;
					break;
				default:
					$this->Continent->ViewValue = $this->Continent->CurrentValue;
			}
		} else {
			$this->Continent->ViewValue = NULL;
		}
		$this->Continent->ViewCustomAttributes = "";

		// Environment
		$this->Environment->ViewValue = $this->Environment->CurrentValue;
		$this->Environment->ViewCustomAttributes = "";

		// Season
		if (strval($this->Season->CurrentValue) <> "") {
			switch ($this->Season->CurrentValue) {
				case $this->Season->FldTagValue(1):
					$this->Season->ViewValue = $this->Season->FldTagCaption(1) <> "" ? $this->Season->FldTagCaption(1) : $this->Season->CurrentValue;
					break;
				case $this->Season->FldTagValue(2):
					$this->Season->ViewValue = $this->Season->FldTagCaption(2) <> "" ? $this->Season->FldTagCaption(2) : $this->Season->CurrentValue;
					break;
				case $this->Season->FldTagValue(3):
					$this->Season->ViewValue = $this->Season->FldTagCaption(3) <> "" ? $this->Season->FldTagCaption(3) : $this->Season->CurrentValue;
					break;
				case $this->Season->FldTagValue(4):
					$this->Season->ViewValue = $this->Season->FldTagCaption(4) <> "" ? $this->Season->FldTagCaption(4) : $this->Season->CurrentValue;
					break;
				default:
					$this->Season->ViewValue = $this->Season->CurrentValue;
			}
		} else {
			$this->Season->ViewValue = NULL;
		}
		$this->Season->ViewCustomAttributes = "";

		// Weather
		$this->Weather->ViewValue = $this->Weather->CurrentValue;
		$this->Weather->ViewCustomAttributes = "";

		// Age
		if (strval($this->Age->CurrentValue) <> "") {
			switch ($this->Age->CurrentValue) {
				case $this->Age->FldTagValue(1):
					$this->Age->ViewValue = $this->Age->FldTagCaption(1) <> "" ? $this->Age->FldTagCaption(1) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(2):
					$this->Age->ViewValue = $this->Age->FldTagCaption(2) <> "" ? $this->Age->FldTagCaption(2) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(3):
					$this->Age->ViewValue = $this->Age->FldTagCaption(3) <> "" ? $this->Age->FldTagCaption(3) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(4):
					$this->Age->ViewValue = $this->Age->FldTagCaption(4) <> "" ? $this->Age->FldTagCaption(4) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(5):
					$this->Age->ViewValue = $this->Age->FldTagCaption(5) <> "" ? $this->Age->FldTagCaption(5) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(6):
					$this->Age->ViewValue = $this->Age->FldTagCaption(6) <> "" ? $this->Age->FldTagCaption(6) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(7):
					$this->Age->ViewValue = $this->Age->FldTagCaption(7) <> "" ? $this->Age->FldTagCaption(7) : $this->Age->CurrentValue;
					break;
				case $this->Age->FldTagValue(8):
					$this->Age->ViewValue = $this->Age->FldTagCaption(8) <> "" ? $this->Age->FldTagCaption(8) : $this->Age->CurrentValue;
					break;
				default:
					$this->Age->ViewValue = $this->Age->CurrentValue;
			}
		} else {
			$this->Age->ViewValue = NULL;
		}
		$this->Age->ViewCustomAttributes = "";

		// Activities
		$this->Activities->ViewValue = $this->Activities->CurrentValue;
		$this->Activities->ViewCustomAttributes = "";

		// ActivityPrice
		$this->ActivityPrice->ViewValue = $this->ActivityPrice->CurrentValue;
		$this->ActivityPrice->ViewCustomAttributes = "";

		// Country
		$this->Country->ViewValue = $this->Country->CurrentValue;
		$this->Country->ViewCustomAttributes = "";

		// City
		$this->City->ViewValue = $this->City->CurrentValue;
		$this->City->ViewCustomAttributes = "";

		// FilterID
		$this->FilterID->LinkCustomAttributes = "";
		$this->FilterID->HrefValue = "";
		$this->FilterID->TooltipValue = "";

		// Date1
		$this->Date1->LinkCustomAttributes = "";
		$this->Date1->HrefValue = "";
		$this->Date1->TooltipValue = "";

		// Date2
		$this->Date2->LinkCustomAttributes = "";
		$this->Date2->HrefValue = "";
		$this->Date2->TooltipValue = "";

		// Continent
		$this->Continent->LinkCustomAttributes = "";
		$this->Continent->HrefValue = "";
		$this->Continent->TooltipValue = "";

		// Environment
		$this->Environment->LinkCustomAttributes = "";
		$this->Environment->HrefValue = "";
		$this->Environment->TooltipValue = "";

		// Season
		$this->Season->LinkCustomAttributes = "";
		$this->Season->HrefValue = "";
		$this->Season->TooltipValue = "";

		// Weather
		$this->Weather->LinkCustomAttributes = "";
		$this->Weather->HrefValue = "";
		$this->Weather->TooltipValue = "";

		// Age
		$this->Age->LinkCustomAttributes = "";
		$this->Age->HrefValue = "";
		$this->Age->TooltipValue = "";

		// Activities
		$this->Activities->LinkCustomAttributes = "";
		$this->Activities->HrefValue = "";
		$this->Activities->TooltipValue = "";

		// ActivityPrice
		$this->ActivityPrice->LinkCustomAttributes = "";
		$this->ActivityPrice->HrefValue = "";
		$this->ActivityPrice->TooltipValue = "";

		// Country
		$this->Country->LinkCustomAttributes = "";
		$this->Country->HrefValue = "";
		$this->Country->TooltipValue = "";

		// City
		$this->City->LinkCustomAttributes = "";
		$this->City->HrefValue = "";
		$this->City->TooltipValue = "";

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
				if ($this->FilterID->Exportable) $Doc->ExportCaption($this->FilterID);
				if ($this->Date1->Exportable) $Doc->ExportCaption($this->Date1);
				if ($this->Date2->Exportable) $Doc->ExportCaption($this->Date2);
				if ($this->Continent->Exportable) $Doc->ExportCaption($this->Continent);
				if ($this->Environment->Exportable) $Doc->ExportCaption($this->Environment);
				if ($this->Season->Exportable) $Doc->ExportCaption($this->Season);
				if ($this->Weather->Exportable) $Doc->ExportCaption($this->Weather);
				if ($this->Age->Exportable) $Doc->ExportCaption($this->Age);
				if ($this->Activities->Exportable) $Doc->ExportCaption($this->Activities);
				if ($this->ActivityPrice->Exportable) $Doc->ExportCaption($this->ActivityPrice);
				if ($this->Country->Exportable) $Doc->ExportCaption($this->Country);
				if ($this->City->Exportable) $Doc->ExportCaption($this->City);
			} else {
				if ($this->FilterID->Exportable) $Doc->ExportCaption($this->FilterID);
				if ($this->Date1->Exportable) $Doc->ExportCaption($this->Date1);
				if ($this->Date2->Exportable) $Doc->ExportCaption($this->Date2);
				if ($this->Continent->Exportable) $Doc->ExportCaption($this->Continent);
				if ($this->Season->Exportable) $Doc->ExportCaption($this->Season);
				if ($this->Age->Exportable) $Doc->ExportCaption($this->Age);
				if ($this->ActivityPrice->Exportable) $Doc->ExportCaption($this->ActivityPrice);
				if ($this->Country->Exportable) $Doc->ExportCaption($this->Country);
				if ($this->City->Exportable) $Doc->ExportCaption($this->City);
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
					if ($this->FilterID->Exportable) $Doc->ExportField($this->FilterID);
					if ($this->Date1->Exportable) $Doc->ExportField($this->Date1);
					if ($this->Date2->Exportable) $Doc->ExportField($this->Date2);
					if ($this->Continent->Exportable) $Doc->ExportField($this->Continent);
					if ($this->Environment->Exportable) $Doc->ExportField($this->Environment);
					if ($this->Season->Exportable) $Doc->ExportField($this->Season);
					if ($this->Weather->Exportable) $Doc->ExportField($this->Weather);
					if ($this->Age->Exportable) $Doc->ExportField($this->Age);
					if ($this->Activities->Exportable) $Doc->ExportField($this->Activities);
					if ($this->ActivityPrice->Exportable) $Doc->ExportField($this->ActivityPrice);
					if ($this->Country->Exportable) $Doc->ExportField($this->Country);
					if ($this->City->Exportable) $Doc->ExportField($this->City);
				} else {
					if ($this->FilterID->Exportable) $Doc->ExportField($this->FilterID);
					if ($this->Date1->Exportable) $Doc->ExportField($this->Date1);
					if ($this->Date2->Exportable) $Doc->ExportField($this->Date2);
					if ($this->Continent->Exportable) $Doc->ExportField($this->Continent);
					if ($this->Season->Exportable) $Doc->ExportField($this->Season);
					if ($this->Age->Exportable) $Doc->ExportField($this->Age);
					if ($this->ActivityPrice->Exportable) $Doc->ExportField($this->ActivityPrice);
					if ($this->Country->Exportable) $Doc->ExportField($this->Country);
					if ($this->City->Exportable) $Doc->ExportField($this->City);
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
