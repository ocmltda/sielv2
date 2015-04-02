<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "alertasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$alertas_add = NULL; // Initialize page object first

class calertas_add extends calertas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{01BDD2DE-C1A6-464D-8FDD-3525837E1545}";

	// Table name
	var $TableName = 'alertas';

	// Page object name
	var $PageObjName = 'alertas_add';

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
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
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
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (alertas)
		if (!isset($GLOBALS["alertas"])) {
			$GLOBALS["alertas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["alertas"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'alertas', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];

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
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

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
					$this->Page_Terminate("alertaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "alertasview.php")
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
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
		$this->fotografia->Upload->Index = $objForm->Index;
		$this->fotografia->Upload->RestoreDbFromSession();
		if ($confirmPage) { // Post from confirm page
			$this->fotografia->Upload->RestoreFromSession();
		} else {
			if ($this->fotografia->Upload->UploadFile()) {

				// No action required
			} else {
				echo $this->fotografia->Upload->Message;
				$this->Page_Terminate();
				exit();
			}
			$this->fotografia->Upload->SaveToSession();
			$this->fotografia->CurrentValue = $this->fotografia->Upload->FileName;
		}
	}

	// Load default values
	function LoadDefaultValues() {
		$this->clientes_id->CurrentValue = NULL;
		$this->clientes_id->OldValue = $this->clientes_id->CurrentValue;
		$this->locales_id->CurrentValue = NULL;
		$this->locales_id->OldValue = $this->locales_id->CurrentValue;
		$this->tiposincidencias_id->CurrentValue = NULL;
		$this->tiposincidencias_id->OldValue = $this->tiposincidencias_id->CurrentValue;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->hora->CurrentValue = NULL;
		$this->hora->OldValue = $this->hora->CurrentValue;
		$this->comentarios->CurrentValue = NULL;
		$this->comentarios->OldValue = $this->comentarios->CurrentValue;
		$this->tiposacciones_id->CurrentValue = NULL;
		$this->tiposacciones_id->OldValue = $this->tiposacciones_id->CurrentValue;
		$this->fotografia->Upload->DbValue = NULL;
		$this->fotografia->OldValue = $this->fotografia->Upload->DbValue;
		$this->fotografia->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->clientes_id->FldIsDetailKey) {
			$this->clientes_id->setFormValue($objForm->GetValue("x_clientes_id"));
		}
		if (!$this->locales_id->FldIsDetailKey) {
			$this->locales_id->setFormValue($objForm->GetValue("x_locales_id"));
		}
		if (!$this->tiposincidencias_id->FldIsDetailKey) {
			$this->tiposincidencias_id->setFormValue($objForm->GetValue("x_tiposincidencias_id"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->hora->FldIsDetailKey) {
			$this->hora->setFormValue($objForm->GetValue("x_hora"));
		}
		if (!$this->comentarios->FldIsDetailKey) {
			$this->comentarios->setFormValue($objForm->GetValue("x_comentarios"));
		}
		if (!$this->tiposacciones_id->FldIsDetailKey) {
			$this->tiposacciones_id->setFormValue($objForm->GetValue("x_tiposacciones_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->clientes_id->CurrentValue = $this->clientes_id->FormValue;
		$this->locales_id->CurrentValue = $this->locales_id->FormValue;
		$this->tiposincidencias_id->CurrentValue = $this->tiposincidencias_id->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->hora->CurrentValue = $this->hora->FormValue;
		$this->comentarios->CurrentValue = $this->comentarios->FormValue;
		$this->tiposacciones_id->CurrentValue = $this->tiposacciones_id->FormValue;
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
		$this->fotografia->Upload->SaveDbToSession();
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// clientes_id
		// locales_id
		// tiposincidencias_id
		// fecha
		// hora
		// coordenadas
		// incidencia
		// comentarios
		// tiposacciones_id
		// fotografia

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// clientes_id
			$this->clientes_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `clientes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->clientes_id->EditValue = $arwrk;

			// locales_id
			$this->locales_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `clientes_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `locales`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locales_id->EditValue = $arwrk;

			// tiposincidencias_id
			$this->tiposincidencias_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `tipi_id`, `tipi_nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tiposincidencias`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipi_nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tiposincidencias_id->EditValue = $arwrk;

			// fecha
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));

			// hora
			$this->hora->EditCustomAttributes = "";
			$this->hora->EditValue = ew_HtmlEncode($this->hora->CurrentValue);

			// comentarios
			$this->comentarios->EditCustomAttributes = "";
			$this->comentarios->EditValue = ew_HtmlEncode($this->comentarios->CurrentValue);

			// tiposacciones_id
			$this->tiposacciones_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `tipos_acciones_id`, `accion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos_acciones`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `accion` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tiposacciones_id->EditValue = $arwrk;

			// fotografia
			$this->fotografia->EditCustomAttributes = "";
			$this->fotografia->UploadPath = '../imgalerta';
			if (!ew_Empty($this->fotografia->Upload->DbValue)) {
				$this->fotografia->EditValue = $this->fotografia->Upload->DbValue;
			} else {
				$this->fotografia->EditValue = "";
			}

			// Edit refer script
			// clientes_id

			$this->clientes_id->HrefValue = "";

			// locales_id
			$this->locales_id->HrefValue = "";

			// tiposincidencias_id
			$this->tiposincidencias_id->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// hora
			$this->hora->HrefValue = "";

			// comentarios
			$this->comentarios->HrefValue = "";

			// tiposacciones_id
			$this->tiposacciones_id->HrefValue = "";

			// fotografia
			$this->fotografia->UploadPath = '../imgalerta';
			if (!ew_Empty($this->fotografia->Upload->DbValue)) {
				$this->fotografia->HrefValue = ew_UploadPathEx(FALSE, $this->fotografia->UploadPath) . $this->fotografia->Upload->DbValue; // Add prefix/suffix
				$this->fotografia->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->fotografia->HrefValue = ew_ConvertFullUrl($this->fotografia->HrefValue);
			} else {
				$this->fotografia->HrefValue = "";
			}
			$this->fotografia->HrefValue2 = $this->fotografia->UploadPath . $this->fotografia->Upload->DbValue;
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
		if (!ew_CheckFileType($this->fotografia->Upload->FileName)) {
			ew_AddMessage($gsFormError, $Language->Phrase("WrongFileType"));
		}
		if ($this->fotografia->Upload->FileSize > 0 && EW_MAX_FILE_SIZE > 0 && $this->fotografia->Upload->FileSize > EW_MAX_FILE_SIZE) {
			ew_AddMessage($gsFormError, str_replace("%s", EW_MAX_FILE_SIZE, $Language->Phrase("MaxFileSize")));
		}
		if (in_array($this->fotografia->Upload->Error, array(1, 2, 3, 6, 7, 8))) {
			ew_AddMessage($gsFormError, $Language->Phrase("PhpUploadErr" . $this->fotografia->Upload->Error));
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($this->clientes_id->FormValue) && $this->clientes_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->clientes_id->FldCaption());
		}
		if (!is_null($this->locales_id->FormValue) && $this->locales_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->locales_id->FldCaption());
		}
		if (!is_null($this->tiposincidencias_id->FormValue) && $this->tiposincidencias_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tiposincidencias_id->FldCaption());
		}
		if (!is_null($this->fecha->FormValue) && $this->fecha->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->fecha->FldCaption());
		}
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!is_null($this->hora->FormValue) && $this->hora->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->hora->FldCaption());
		}
		if (!ew_CheckTime($this->hora->FormValue)) {
			ew_AddMessage($gsFormError, $this->hora->FldErrMsg());
		}
		if (!is_null($this->tiposacciones_id->FormValue) && $this->tiposacciones_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tiposacciones_id->FldCaption());
		}
		if ($this->fotografia->Upload->Action == "3" && is_null($this->fotografia->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->fotografia->FldCaption());
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
		$rsnew = array();

		// clientes_id
		$this->clientes_id->SetDbValueDef($rsnew, $this->clientes_id->CurrentValue, 0, FALSE);

		// locales_id
		$this->locales_id->SetDbValueDef($rsnew, $this->locales_id->CurrentValue, 0, FALSE);

		// tiposincidencias_id
		$this->tiposincidencias_id->SetDbValueDef($rsnew, $this->tiposincidencias_id->CurrentValue, 0, FALSE);

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// hora
		$this->hora->SetDbValueDef($rsnew, $this->hora->CurrentValue, ew_CurrentTime(), FALSE);

		// comentarios
		$this->comentarios->SetDbValueDef($rsnew, $this->comentarios->CurrentValue, NULL, FALSE);

		// tiposacciones_id
		$this->tiposacciones_id->SetDbValueDef($rsnew, $this->tiposacciones_id->CurrentValue, NULL, FALSE);

		// fotografia
		$this->fotografia->UploadPath = '../imgalerta';
		if ($this->fotografia->Upload->Action == "1") { // Keep
			if ($rsold) {
				$rsnew['fotografia'] = $rsold->fields['fotografia'];
			}
		} elseif ($this->fotografia->Upload->Action == "2" || $this->fotografia->Upload->Action == "3") { // Update/Remove
		if (is_null($this->fotografia->Upload->Value)) {
			$rsnew['fotografia'] = NULL;
		} else {
			$rsnew['fotografia'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->fotografia->UploadPath), $this->fotografia->Upload->FileName);
		}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!ew_Empty($this->fotografia->Upload->Value)) {
					$this->fotografia->Upload->SaveToFile($this->fotografia->UploadPath, $rsnew['fotografia'], FALSE);
				}
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
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// fotografia
		$this->fotografia->Upload->RemoveFromSession(); // Remove file value from Session
		return $AddRow;
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
if (!isset($alertas_add)) $alertas_add = new calertas_add();

// Page init
$alertas_add->Page_Init();

// Page main
$alertas_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var alertas_add = new ew_Page("alertas_add");
alertas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = alertas_add.PageID; // For backward compatibility

// Form object
var falertasadd = new ew_Form("falertasadd");

// Validate form
falertasadd.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_clientes_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->clientes_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_locales_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->locales_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_tiposincidencias_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->tiposincidencias_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_fecha"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->fecha->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_fecha"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($alertas->fecha->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_hora"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->hora->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_hora"];
		if (elm && !ew_CheckTime(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($alertas->hora->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_tiposacciones_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->tiposacciones_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_fotografia"];
		aelm = fobj.elements["a" + infix + "_fotografia"];
		var chk_fotografia = (aelm && aelm[0])?(aelm[2].checked):true;
		if (elm && chk_fotografia && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($alertas->fotografia->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_fotografia"];
		if (elm && !ew_CheckFileType(elm.value))
			return ew_OnError(this, elm, ewLanguage.Phrase("WrongFileType"));

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
falertasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
falertasadd.ValidateRequired = true;
<?php } else { ?>
falertasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
falertasadd.Lists["x_clientes_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasadd.Lists["x_locales_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","x_direccion","",""],"ParentFields":["x_clientes_id"],"FilterFields":["x_clientes_id"],"Options":[]};
falertasadd.Lists["x_tiposincidencias_id"] = {"LinkField":"x_tipi_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipi_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasadd.Lists["x_tiposacciones_id"] = {"LinkField":"x_tipos_acciones_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_accion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $alertas->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $alertas->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $alertas_add->ShowPageHeader(); ?>
<?php
$alertas_add->ShowMessage();
?>
<form name="falertasadd" id="falertasadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" enctype="multipart/form-data" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="alertas">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_alertasadd" class="ewTable">
<?php if ($alertas->clientes_id->Visible) { // clientes_id ?>
	<tr id="r_clientes_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_clientes_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->clientes_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->clientes_id->CellAttributes() ?>><span id="el_alertas_clientes_id">
<?php $alertas->clientes_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_locales_id']); " . @$alertas->clientes_id->EditAttrs["onchange"]; ?>
<select id="x_clientes_id" name="x_clientes_id"<?php echo $alertas->clientes_id->EditAttributes() ?>>
<?php
if (is_array($alertas->clientes_id->EditValue)) {
	$arwrk = $alertas->clientes_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($alertas->clientes_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
falertasadd.Lists["x_clientes_id"].Options = <?php echo (is_array($alertas->clientes_id->EditValue)) ? ew_ArrayToJson($alertas->clientes_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $alertas->clientes_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->locales_id->Visible) { // locales_id ?>
	<tr id="r_locales_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_locales_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->locales_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->locales_id->CellAttributes() ?>><span id="el_alertas_locales_id">
<select id="x_locales_id" name="x_locales_id"<?php echo $alertas->locales_id->EditAttributes() ?>>
<?php
if (is_array($alertas->locales_id->EditValue)) {
	$arwrk = $alertas->locales_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($alertas->locales_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$alertas->locales_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
falertasadd.Lists["x_locales_id"].Options = <?php echo (is_array($alertas->locales_id->EditValue)) ? ew_ArrayToJson($alertas->locales_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $alertas->locales_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->tiposincidencias_id->Visible) { // tiposincidencias_id ?>
	<tr id="r_tiposincidencias_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_tiposincidencias_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->tiposincidencias_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->tiposincidencias_id->CellAttributes() ?>><span id="el_alertas_tiposincidencias_id">
<select id="x_tiposincidencias_id" name="x_tiposincidencias_id"<?php echo $alertas->tiposincidencias_id->EditAttributes() ?>>
<?php
if (is_array($alertas->tiposincidencias_id->EditValue)) {
	$arwrk = $alertas->tiposincidencias_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($alertas->tiposincidencias_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
falertasadd.Lists["x_tiposincidencias_id"].Options = <?php echo (is_array($alertas->tiposincidencias_id->EditValue)) ? ew_ArrayToJson($alertas->tiposincidencias_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $alertas->tiposincidencias_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->fecha->Visible) { // fecha ?>
	<tr id="r_fecha"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_fecha"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->fecha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->fecha->CellAttributes() ?>><span id="el_alertas_fecha">
<input type="text" name="x_fecha" id="x_fecha" value="<?php echo $alertas->fecha->EditValue ?>"<?php echo $alertas->fecha->EditAttributes() ?>>
<?php if (!$alertas->fecha->ReadOnly && !$alertas->fecha->Disabled && @$alertas->fecha->EditAttrs["readonly"] == "" && @$alertas->fecha->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="falertasadd$x_fecha$" name="falertasadd$x_fecha$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar" style="border: 0;">
<script type="text/javascript">
ew_CreateCalendar("falertasadd", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span><?php echo $alertas->fecha->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->hora->Visible) { // hora ?>
	<tr id="r_hora"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_hora"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->hora->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->hora->CellAttributes() ?>><span id="el_alertas_hora">
<input type="text" name="x_hora" id="x_hora" size="30" value="<?php echo $alertas->hora->EditValue ?>"<?php echo $alertas->hora->EditAttributes() ?>>
</span><?php echo $alertas->hora->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->comentarios->Visible) { // comentarios ?>
	<tr id="r_comentarios"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_comentarios"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->comentarios->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->comentarios->CellAttributes() ?>><span id="el_alertas_comentarios">
<textarea name="x_comentarios" id="x_comentarios" cols="35" rows="4"<?php echo $alertas->comentarios->EditAttributes() ?>><?php echo $alertas->comentarios->EditValue ?></textarea>
</span><?php echo $alertas->comentarios->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->tiposacciones_id->Visible) { // tiposacciones_id ?>
	<tr id="r_tiposacciones_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_tiposacciones_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->tiposacciones_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->tiposacciones_id->CellAttributes() ?>><span id="el_alertas_tiposacciones_id">
<select id="x_tiposacciones_id" name="x_tiposacciones_id"<?php echo $alertas->tiposacciones_id->EditAttributes() ?>>
<?php
if (is_array($alertas->tiposacciones_id->EditValue)) {
	$arwrk = $alertas->tiposacciones_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($alertas->tiposacciones_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
falertasadd.Lists["x_tiposacciones_id"].Options = <?php echo (is_array($alertas->tiposacciones_id->EditValue)) ? ew_ArrayToJson($alertas->tiposacciones_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $alertas->tiposacciones_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->fotografia->Visible) { // fotografia ?>
	<tr id="r_fotografia"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_fotografia"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->fotografia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $alertas->fotografia->CellAttributes() ?>><span id="el_alertas_fotografia">
<div id="old_x_fotografia">
<?php if ($alertas->fotografia->LinkAttributes() <> "") { ?>
<?php if (!empty($alertas->fotografia->Upload->DbValue)) { ?>
<a<?php echo $alertas->fotografia->LinkAttributes() ?>><?php echo $alertas->fotografia->EditValue ?></a>
<?php } elseif (!in_array($alertas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($alertas->fotografia->Upload->DbValue)) { ?>
<?php echo $alertas->fotografia->EditValue ?>
<?php } elseif (!in_array($alertas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</div>
<div id="new_x_fotografia">
<?php if ($alertas->fotografia->ReadOnly) { ?>
<?php if (!empty($alertas->fotografia->Upload->DbValue)) { ?>
<input type="hidden" name="a_fotografia" id="a_fotografia" value="1">
<?php } ?>
<?php } else { ?>
<?php if (!empty($alertas->fotografia->Upload->DbValue)) { ?>
<label><input type="radio" name="a_fotografia" id="a_fotografia" value="1" checked="checked"><?php echo $Language->Phrase("Keep") ?></label>&nbsp;
<label><input type="radio" name="a_fotografia" id="a_fotografia" value="2" disabled="disabled"><?php echo $Language->Phrase("Remove") ?></label>&nbsp;
<label><input type="radio" name="a_fotografia" id="a_fotografia" value="3"><?php echo $Language->Phrase("Replace") ?><br></label>
<?php $alertas->fotografia->EditAttrs["onchange"] = "this.form.a_fotografia[2].checked=true;" . @$alertas->fotografia->EditAttrs["onchange"]; ?>
<?php } else { ?>
<input type="hidden" name="a_fotografia" id="a_fotografia" value="3">
<?php } ?>
<input type="file" name="x_fotografia" id="x_fotografia" size="30"<?php echo $alertas->fotografia->EditAttributes() ?>>
<?php } ?>
</div>
</span><?php echo $alertas->fotografia->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
falertasadd.Init();
</script>
<?php
$alertas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$alertas_add->Page_Terminate();
?>
