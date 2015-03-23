<?php

// Global variable for table object
$users = NULL;

//
// Table class for users
//
class cusers extends cTable {
	var $usu_id;
	var $usu_nombre;
	var $usu_login;
	var $usu_pass;
	var $emp_id;
	var $per_id;
	var $usu_vigente;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'users';
		$this->TableName = 'users';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// usu_id
		$this->usu_id = new cField('users', 'users', 'x_usu_id', 'usu_id', '`usu_id`', '`usu_id`', 3, -1, FALSE, '`usu_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->usu_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['usu_id'] = &$this->usu_id;

		// usu_nombre
		$this->usu_nombre = new cField('users', 'users', 'x_usu_nombre', 'usu_nombre', '`usu_nombre`', '`usu_nombre`', 200, -1, FALSE, '`usu_nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['usu_nombre'] = &$this->usu_nombre;

		// usu_login
		$this->usu_login = new cField('users', 'users', 'x_usu_login', 'usu_login', '`usu_login`', '`usu_login`', 200, -1, FALSE, '`usu_login`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['usu_login'] = &$this->usu_login;

		// usu_pass
		$this->usu_pass = new cField('users', 'users', 'x_usu_pass', 'usu_pass', '`usu_pass`', '`usu_pass`', 200, -1, FALSE, '`usu_pass`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['usu_pass'] = &$this->usu_pass;

		// emp_id
		$this->emp_id = new cField('users', 'users', 'x_emp_id', 'emp_id', '`emp_id`', '`emp_id`', 3, -1, FALSE, '`emp_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->emp_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['emp_id'] = &$this->emp_id;

		// per_id
		$this->per_id = new cField('users', 'users', 'x_per_id', 'per_id', '`per_id`', '`per_id`', 3, -1, FALSE, '`per_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->per_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['per_id'] = &$this->per_id;

		// usu_vigente
		$this->usu_vigente = new cField('users', 'users', 'x_usu_vigente', 'usu_vigente', '`usu_vigente`', '`usu_vigente`', 16, -1, FALSE, '`usu_vigente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->usu_vigente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['usu_vigente'] = &$this->usu_vigente;
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
		return "`users`";
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
		return TRUE;
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
	var $UpdateTable = "`users`";

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
	function Update(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			$sql .= ew_QuotedName('usu_id') . '=' . ew_QuotedValue($rs['usu_id'], $this->usu_id->FldDataType) . ' AND ';
		}
		if (substr($sql, -5) == " AND ") $sql = substr($sql, 0, -5);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " AND " . $filter;
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`usu_id` = @usu_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->usu_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@usu_id@", ew_AdjustSql($this->usu_id->CurrentValue), $sKeyFilter); // Replace key value
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
			return "userslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "userslist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("usersview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "usersadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("usersedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("usersadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("usersdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->usu_id->CurrentValue)) {
			$sUrl .= "usu_id=" . urlencode($this->usu_id->CurrentValue);
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
			$arKeys[] = @$_GET["usu_id"]; // usu_id

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
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
			$this->usu_id->CurrentValue = $key;
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
		$this->usu_id->setDbValue($rs->fields('usu_id'));
		$this->usu_nombre->setDbValue($rs->fields('usu_nombre'));
		$this->usu_login->setDbValue($rs->fields('usu_login'));
		$this->usu_pass->setDbValue($rs->fields('usu_pass'));
		$this->emp_id->setDbValue($rs->fields('emp_id'));
		$this->per_id->setDbValue($rs->fields('per_id'));
		$this->usu_vigente->setDbValue($rs->fields('usu_vigente'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// usu_id
		// usu_nombre
		// usu_login
		// usu_pass
		// emp_id
		// per_id
		// usu_vigente
		// usu_id

		$this->usu_id->ViewValue = $this->usu_id->CurrentValue;
		$this->usu_id->ViewCustomAttributes = "";

		// usu_nombre
		$this->usu_nombre->ViewValue = $this->usu_nombre->CurrentValue;
		$this->usu_nombre->ViewCustomAttributes = "";

		// usu_login
		$this->usu_login->ViewValue = $this->usu_login->CurrentValue;
		$this->usu_login->ViewCustomAttributes = "";

		// usu_pass
		$this->usu_pass->ViewValue = "********";
		$this->usu_pass->ViewCustomAttributes = "";

		// emp_id
		if (strval($this->emp_id->CurrentValue) <> "") {
			$sFilterWrk = "`emp_id`" . ew_SearchString("=", $this->emp_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `emp_id`, `emp_nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresa`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `emp_nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->emp_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->emp_id->ViewValue = $this->emp_id->CurrentValue;
			}
		} else {
			$this->emp_id->ViewValue = NULL;
		}
		$this->emp_id->ViewCustomAttributes = "";

		// per_id
		if (strval($this->per_id->CurrentValue) <> "") {
			$sFilterWrk = "`per_id`" . ew_SearchString("=", $this->per_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `per_id`, `per_nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `perfil`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `per_nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->per_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->per_id->ViewValue = $this->per_id->CurrentValue;
			}
		} else {
			$this->per_id->ViewValue = NULL;
		}
		$this->per_id->ViewCustomAttributes = "";

		// usu_vigente
		if (strval($this->usu_vigente->CurrentValue) <> "") {
			switch ($this->usu_vigente->CurrentValue) {
				case $this->usu_vigente->FldTagValue(1):
					$this->usu_vigente->ViewValue = $this->usu_vigente->FldTagCaption(1) <> "" ? $this->usu_vigente->FldTagCaption(1) : $this->usu_vigente->CurrentValue;
					break;
				case $this->usu_vigente->FldTagValue(2):
					$this->usu_vigente->ViewValue = $this->usu_vigente->FldTagCaption(2) <> "" ? $this->usu_vigente->FldTagCaption(2) : $this->usu_vigente->CurrentValue;
					break;
				default:
					$this->usu_vigente->ViewValue = $this->usu_vigente->CurrentValue;
			}
		} else {
			$this->usu_vigente->ViewValue = NULL;
		}
		$this->usu_vigente->ViewCustomAttributes = "";

		// usu_id
		$this->usu_id->LinkCustomAttributes = "";
		$this->usu_id->HrefValue = "";
		$this->usu_id->TooltipValue = "";

		// usu_nombre
		$this->usu_nombre->LinkCustomAttributes = "";
		$this->usu_nombre->HrefValue = "";
		$this->usu_nombre->TooltipValue = "";

		// usu_login
		$this->usu_login->LinkCustomAttributes = "";
		$this->usu_login->HrefValue = "";
		$this->usu_login->TooltipValue = "";

		// usu_pass
		$this->usu_pass->LinkCustomAttributes = "";
		$this->usu_pass->HrefValue = "";
		$this->usu_pass->TooltipValue = "";

		// emp_id
		$this->emp_id->LinkCustomAttributes = "";
		$this->emp_id->HrefValue = "";
		$this->emp_id->TooltipValue = "";

		// per_id
		$this->per_id->LinkCustomAttributes = "";
		$this->per_id->HrefValue = "";
		$this->per_id->TooltipValue = "";

		// usu_vigente
		$this->usu_vigente->LinkCustomAttributes = "";
		$this->usu_vigente->HrefValue = "";
		$this->usu_vigente->TooltipValue = "";

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
				if ($this->usu_id->Exportable) $Doc->ExportCaption($this->usu_id);
				if ($this->usu_nombre->Exportable) $Doc->ExportCaption($this->usu_nombre);
				if ($this->usu_login->Exportable) $Doc->ExportCaption($this->usu_login);
				if ($this->usu_pass->Exportable) $Doc->ExportCaption($this->usu_pass);
				if ($this->emp_id->Exportable) $Doc->ExportCaption($this->emp_id);
				if ($this->per_id->Exportable) $Doc->ExportCaption($this->per_id);
				if ($this->usu_vigente->Exportable) $Doc->ExportCaption($this->usu_vigente);
			} else {
				if ($this->usu_id->Exportable) $Doc->ExportCaption($this->usu_id);
				if ($this->usu_nombre->Exportable) $Doc->ExportCaption($this->usu_nombre);
				if ($this->usu_login->Exportable) $Doc->ExportCaption($this->usu_login);
				if ($this->usu_pass->Exportable) $Doc->ExportCaption($this->usu_pass);
				if ($this->emp_id->Exportable) $Doc->ExportCaption($this->emp_id);
				if ($this->per_id->Exportable) $Doc->ExportCaption($this->per_id);
				if ($this->usu_vigente->Exportable) $Doc->ExportCaption($this->usu_vigente);
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
					if ($this->usu_id->Exportable) $Doc->ExportField($this->usu_id);
					if ($this->usu_nombre->Exportable) $Doc->ExportField($this->usu_nombre);
					if ($this->usu_login->Exportable) $Doc->ExportField($this->usu_login);
					if ($this->usu_pass->Exportable) $Doc->ExportField($this->usu_pass);
					if ($this->emp_id->Exportable) $Doc->ExportField($this->emp_id);
					if ($this->per_id->Exportable) $Doc->ExportField($this->per_id);
					if ($this->usu_vigente->Exportable) $Doc->ExportField($this->usu_vigente);
				} else {
					if ($this->usu_id->Exportable) $Doc->ExportField($this->usu_id);
					if ($this->usu_nombre->Exportable) $Doc->ExportField($this->usu_nombre);
					if ($this->usu_login->Exportable) $Doc->ExportField($this->usu_login);
					if ($this->usu_pass->Exportable) $Doc->ExportField($this->usu_pass);
					if ($this->emp_id->Exportable) $Doc->ExportField($this->emp_id);
					if ($this->per_id->Exportable) $Doc->ExportField($this->per_id);
					if ($this->usu_vigente->Exportable) $Doc->ExportField($this->usu_vigente);
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
