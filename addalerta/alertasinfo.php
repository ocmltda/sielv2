<?php

// Global variable for table object
$alertas = NULL;

//
// Table class for alertas
//
class calertas extends cTable {
	var $id;
	var $clientes_id;
	var $locales_id;
	var $tiposincidencias_id;
	var $fecha;
	var $hora;
	var $coordenadas;
	var $incidencia;
	var $comentarios;
	var $tiposacciones_id;
	var $fotografia;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'alertas';
		$this->TableName = 'alertas';
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

		// id
		$this->id = new cField('alertas', 'alertas', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// clientes_id
		$this->clientes_id = new cField('alertas', 'alertas', 'x_clientes_id', 'clientes_id', '`clientes_id`', '`clientes_id`', 2, -1, FALSE, '`clientes_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->clientes_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['clientes_id'] = &$this->clientes_id;

		// locales_id
		$this->locales_id = new cField('alertas', 'alertas', 'x_locales_id', 'locales_id', '`locales_id`', '`locales_id`', 3, -1, FALSE, '`locales_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->locales_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['locales_id'] = &$this->locales_id;

		// tiposincidencias_id
		$this->tiposincidencias_id = new cField('alertas', 'alertas', 'x_tiposincidencias_id', 'tiposincidencias_id', '`tiposincidencias_id`', '`tiposincidencias_id`', 2, -1, FALSE, '`tiposincidencias_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tiposincidencias_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tiposincidencias_id'] = &$this->tiposincidencias_id;

		// fecha
		$this->fecha = new cField('alertas', 'alertas', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y %H:%i:%s\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// hora
		$this->hora = new cField('alertas', 'alertas', 'x_hora', 'hora', '`hora`', 'DATE_FORMAT(`hora`, \'%d/%m/%Y %H:%i:%s\')', 134, -1, FALSE, '`hora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hora->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['hora'] = &$this->hora;

		// coordenadas
		$this->coordenadas = new cField('alertas', 'alertas', 'x_coordenadas', 'coordenadas', '`coordenadas`', '`coordenadas`', 200, -1, FALSE, '`coordenadas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['coordenadas'] = &$this->coordenadas;

		// incidencia
		$this->incidencia = new cField('alertas', 'alertas', 'x_incidencia', 'incidencia', '`incidencia`', '`incidencia`', 200, -1, FALSE, '`incidencia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['incidencia'] = &$this->incidencia;

		// comentarios
		$this->comentarios = new cField('alertas', 'alertas', 'x_comentarios', 'comentarios', '`comentarios`', '`comentarios`', 201, -1, FALSE, '`comentarios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comentarios'] = &$this->comentarios;

		// tiposacciones_id
		$this->tiposacciones_id = new cField('alertas', 'alertas', 'x_tiposacciones_id', 'tiposacciones_id', '`tiposacciones_id`', '`tiposacciones_id`', 2, -1, FALSE, '`tiposacciones_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tiposacciones_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tiposacciones_id'] = &$this->tiposacciones_id;

		// fotografia
		$this->fotografia = new cField('alertas', 'alertas', 'x_fotografia', 'fotografia', '`fotografia`', '`fotografia`', 200, -1, TRUE, '`fotografia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fotografia'] = &$this->fotografia;
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
		return "`alertas`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "clientes_id in (SELECT EU.clientes_id FROM alertas AS A INNER JOIN empusu AS EU ON A.clientes_id = EU.clientes_id INNER JOIN usuarios AS U ON U.id = EU.usuarios_id WHERE U.usuario = '" . CurrentUserName() . "')";
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
		return "`fecha` DESC,`hora` DESC";
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
	var $UpdateTable = "`alertas`";

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
			$sql .= ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType) . ' AND ';
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
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
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
			return "alertaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "alertaslist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("alertasview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "alertasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("alertasedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("alertasadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("alertasdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
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
			$arKeys[] = @$_GET["id"]; // id

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
			$this->id->CurrentValue = $key;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->clientes_id->setDbValue($rs->fields('clientes_id'));
		$this->locales_id->setDbValue($rs->fields('locales_id'));
		$this->tiposincidencias_id->setDbValue($rs->fields('tiposincidencias_id'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->hora->setDbValue($rs->fields('hora'));
		$this->coordenadas->setDbValue($rs->fields('coordenadas'));
		$this->incidencia->setDbValue($rs->fields('incidencia'));
		$this->comentarios->setDbValue($rs->fields('comentarios'));
		$this->tiposacciones_id->setDbValue($rs->fields('tiposacciones_id'));
		$this->fotografia->Upload->DbValue = $rs->fields('fotografia');
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// clientes_id
		// locales_id
		// tiposincidencias_id
		// fecha
		// hora
		// coordenadas
		// incidencia

		$this->incidencia->CellCssStyle = "white-space: nowrap;";

		// comentarios
		// tiposacciones_id
		// fotografia
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// clientes_id
		if (strval($this->clientes_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->clientes_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->clientes_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->clientes_id->ViewValue = $this->clientes_id->CurrentValue;
			}
		} else {
			$this->clientes_id->ViewValue = NULL;
		}
		$this->clientes_id->ViewCustomAttributes = "";

		// locales_id
		if (strval($this->locales_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->locales_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `locales`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->locales_id->ViewValue = $rswrk->fields('DispFld');
				$this->locales_id->ViewValue .= ew_ValueSeparator(1,$this->locales_id) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->locales_id->ViewValue = $this->locales_id->CurrentValue;
			}
		} else {
			$this->locales_id->ViewValue = NULL;
		}
		$this->locales_id->ViewCustomAttributes = "";

		// tiposincidencias_id
		if (strval($this->tiposincidencias_id->CurrentValue) <> "") {
			$sFilterWrk = "`tipi_id`" . ew_SearchString("=", $this->tiposincidencias_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `tipi_id`, `tipi_nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tiposincidencias`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `tipi_nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->tiposincidencias_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->tiposincidencias_id->ViewValue = $this->tiposincidencias_id->CurrentValue;
			}
		} else {
			$this->tiposincidencias_id->ViewValue = NULL;
		}
		$this->tiposincidencias_id->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
		$this->fecha->ViewCustomAttributes = "";

		// hora
		$this->hora->ViewValue = $this->hora->CurrentValue;
		$this->hora->ViewCustomAttributes = "";

		// coordenadas
		$this->coordenadas->ViewValue = $this->coordenadas->CurrentValue;
		$this->coordenadas->ViewCustomAttributes = "";

		// incidencia
		$this->incidencia->ViewValue = $this->incidencia->CurrentValue;
		$this->incidencia->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

		// tiposacciones_id
		if (strval($this->tiposacciones_id->CurrentValue) <> "") {
			$sFilterWrk = "`tipos_acciones_id`" . ew_SearchString("=", $this->tiposacciones_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `tipos_acciones_id`, `accion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos_acciones`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `accion` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->tiposacciones_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->tiposacciones_id->ViewValue = $this->tiposacciones_id->CurrentValue;
			}
		} else {
			$this->tiposacciones_id->ViewValue = NULL;
		}
		$this->tiposacciones_id->ViewCustomAttributes = "";

		// fotografia
		$this->fotografia->UploadPath = '../imgalerta';
		if (!ew_Empty($this->fotografia->Upload->DbValue)) {
			$this->fotografia->ViewValue = $this->fotografia->Upload->DbValue;
		} else {
			$this->fotografia->ViewValue = "";
		}
		$this->fotografia->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// clientes_id
		$this->clientes_id->LinkCustomAttributes = "";
		$this->clientes_id->HrefValue = "";
		$this->clientes_id->TooltipValue = "";

		// locales_id
		$this->locales_id->LinkCustomAttributes = "";
		$this->locales_id->HrefValue = "";
		$this->locales_id->TooltipValue = "";

		// tiposincidencias_id
		$this->tiposincidencias_id->LinkCustomAttributes = "";
		$this->tiposincidencias_id->HrefValue = "";
		$this->tiposincidencias_id->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// hora
		$this->hora->LinkCustomAttributes = "";
		$this->hora->HrefValue = "";
		$this->hora->TooltipValue = "";

		// coordenadas
		$this->coordenadas->LinkCustomAttributes = "";
		if (!ew_Empty($this->coordenadas->CurrentValue)) {
			$this->coordenadas->HrefValue = "http://www.google.es/maps/preview?q=" . ((!empty($this->coordenadas->ViewValue)) ? $this->coordenadas->ViewValue : $this->coordenadas->CurrentValue); // Add prefix/suffix
			$this->coordenadas->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->coordenadas->HrefValue = ew_ConvertFullUrl($this->coordenadas->HrefValue);
		} else {
			$this->coordenadas->HrefValue = "";
		}
		$this->coordenadas->TooltipValue = "";

		// incidencia
		$this->incidencia->LinkCustomAttributes = "";
		$this->incidencia->HrefValue = "";
		$this->incidencia->TooltipValue = "";

		// comentarios
		$this->comentarios->LinkCustomAttributes = "";
		$this->comentarios->HrefValue = "";
		$this->comentarios->TooltipValue = "";

		// tiposacciones_id
		$this->tiposacciones_id->LinkCustomAttributes = "";
		$this->tiposacciones_id->HrefValue = "";
		$this->tiposacciones_id->TooltipValue = "";

		// fotografia
		$this->fotografia->LinkCustomAttributes = "";
		$this->fotografia->UploadPath = '../imgalerta';
		if (!ew_Empty($this->fotografia->Upload->DbValue)) {
			$this->fotografia->HrefValue = ew_UploadPathEx(FALSE, $this->fotografia->UploadPath) . $this->fotografia->Upload->DbValue; // Add prefix/suffix
			$this->fotografia->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->fotografia->HrefValue = ew_ConvertFullUrl($this->fotografia->HrefValue);
		} else {
			$this->fotografia->HrefValue = "";
		}
		$this->fotografia->HrefValue2 = $this->fotografia->UploadPath . $this->fotografia->Upload->DbValue;
		$this->fotografia->TooltipValue = "";

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
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->clientes_id->Exportable) $Doc->ExportCaption($this->clientes_id);
				if ($this->locales_id->Exportable) $Doc->ExportCaption($this->locales_id);
				if ($this->tiposincidencias_id->Exportable) $Doc->ExportCaption($this->tiposincidencias_id);
				if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
				if ($this->hora->Exportable) $Doc->ExportCaption($this->hora);
				if ($this->coordenadas->Exportable) $Doc->ExportCaption($this->coordenadas);
				if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
				if ($this->tiposacciones_id->Exportable) $Doc->ExportCaption($this->tiposacciones_id);
				if ($this->fotografia->Exportable) $Doc->ExportCaption($this->fotografia);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->clientes_id->Exportable) $Doc->ExportCaption($this->clientes_id);
				if ($this->locales_id->Exportable) $Doc->ExportCaption($this->locales_id);
				if ($this->tiposincidencias_id->Exportable) $Doc->ExportCaption($this->tiposincidencias_id);
				if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
				if ($this->hora->Exportable) $Doc->ExportCaption($this->hora);
				if ($this->coordenadas->Exportable) $Doc->ExportCaption($this->coordenadas);
				if ($this->tiposacciones_id->Exportable) $Doc->ExportCaption($this->tiposacciones_id);
				if ($this->fotografia->Exportable) $Doc->ExportCaption($this->fotografia);
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
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->clientes_id->Exportable) $Doc->ExportField($this->clientes_id);
					if ($this->locales_id->Exportable) $Doc->ExportField($this->locales_id);
					if ($this->tiposincidencias_id->Exportable) $Doc->ExportField($this->tiposincidencias_id);
					if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
					if ($this->hora->Exportable) $Doc->ExportField($this->hora);
					if ($this->coordenadas->Exportable) $Doc->ExportField($this->coordenadas);
					if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
					if ($this->tiposacciones_id->Exportable) $Doc->ExportField($this->tiposacciones_id);
					if ($this->fotografia->Exportable) $Doc->ExportField($this->fotografia);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->clientes_id->Exportable) $Doc->ExportField($this->clientes_id);
					if ($this->locales_id->Exportable) $Doc->ExportField($this->locales_id);
					if ($this->tiposincidencias_id->Exportable) $Doc->ExportField($this->tiposincidencias_id);
					if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
					if ($this->hora->Exportable) $Doc->ExportField($this->hora);
					if ($this->coordenadas->Exportable) $Doc->ExportField($this->coordenadas);
					if ($this->tiposacciones_id->Exportable) $Doc->ExportField($this->tiposacciones_id);
					if ($this->fotografia->Exportable) $Doc->ExportField($this->fotografia);
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
		$Email->Recipient = 'ocmchile@gmail.com'; // Change recipient to a field value in the new record 
		$Email->Subject = "prueba correo"; // Change subject
		$Email->Content .= "\nAdded by " . CurrentUserName();
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
