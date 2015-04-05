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

$alertas_edit = NULL; // Initialize page object first

class calertas_edit extends calertas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{01BDD2DE-C1A6-464D-8FDD-3525837E1545}";

	// Table name
	var $TableName = 'alertas';

	// Page object name
	var $PageObjName = 'alertas_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "")
			$this->id->setQueryStringValue($_GET["id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("alertaslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("alertaslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
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
		if (!$this->coordenadas->FldIsDetailKey) {
			$this->coordenadas->setFormValue($objForm->GetValue("x_coordenadas"));
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
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->clientes_id->CurrentValue = $this->clientes_id->FormValue;
		$this->locales_id->CurrentValue = $this->locales_id->FormValue;
		$this->tiposincidencias_id->CurrentValue = $this->tiposincidencias_id->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->hora->CurrentValue = $this->hora->FormValue;
		$this->coordenadas->CurrentValue = $this->coordenadas->FormValue;
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

			// coordenadas
			$this->coordenadas->ViewValue = $this->coordenadas->CurrentValue;
			$this->coordenadas->ViewCustomAttributes = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// clientes_id
			$this->clientes_id->EditCustomAttributes = "";
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
					$this->clientes_id->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->clientes_id->EditValue = $this->clientes_id->CurrentValue;
				}
			} else {
				$this->clientes_id->EditValue = NULL;
			}
			$this->clientes_id->ViewCustomAttributes = "";

			// locales_id
			$this->locales_id->EditCustomAttributes = "";
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
					$this->locales_id->EditValue = $rswrk->fields('DispFld');
					$this->locales_id->EditValue .= ew_ValueSeparator(1,$this->locales_id) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->locales_id->EditValue = $this->locales_id->CurrentValue;
				}
			} else {
				$this->locales_id->EditValue = NULL;
			}
			$this->locales_id->ViewCustomAttributes = "";

			// tiposincidencias_id
			$this->tiposincidencias_id->EditCustomAttributes = "";
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
					$this->tiposincidencias_id->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->tiposincidencias_id->EditValue = $this->tiposincidencias_id->CurrentValue;
				}
			} else {
				$this->tiposincidencias_id->EditValue = NULL;
			}
			$this->tiposincidencias_id->ViewCustomAttributes = "";

			// fecha
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = $this->fecha->CurrentValue;
			$this->fecha->EditValue = ew_FormatDateTime($this->fecha->EditValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// hora
			$this->hora->EditCustomAttributes = "";
			$this->hora->EditValue = $this->hora->CurrentValue;
			$this->hora->ViewCustomAttributes = "";

			// coordenadas
			$this->coordenadas->EditCustomAttributes = "";
			$this->coordenadas->EditValue = $this->coordenadas->CurrentValue;
			$this->coordenadas->ViewCustomAttributes = "";

			// comentarios
			$this->comentarios->EditCustomAttributes = "";
			$this->comentarios->EditValue = $this->comentarios->CurrentValue;
			$this->comentarios->ViewCustomAttributes = "";

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
			$this->fotografia->ViewCustomAttributes = "";

			// Edit refer script
			// id

			$this->id->HrefValue = "";

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

			// coordenadas
			if (!ew_Empty($this->coordenadas->CurrentValue)) {
				$this->coordenadas->HrefValue = "http://www.google.es/maps/preview?q=" . ((!empty($this->coordenadas->EditValue)) ? $this->coordenadas->EditValue : $this->coordenadas->CurrentValue); // Add prefix/suffix
				$this->coordenadas->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->coordenadas->HrefValue = ew_ConvertFullUrl($this->coordenadas->HrefValue);
			} else {
				$this->coordenadas->HrefValue = "";
			}

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

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$rsnew = array();

			// tiposacciones_id
			$this->tiposacciones_id->SetDbValueDef($rsnew, $this->tiposacciones_id->CurrentValue, NULL, $this->tiposacciones_id->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
if (!isset($alertas_edit)) $alertas_edit = new calertas_edit();

// Page init
$alertas_edit->Page_Init();

// Page main
$alertas_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var alertas_edit = new ew_Page("alertas_edit");
alertas_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = alertas_edit.PageID; // For backward compatibility

// Form object
var falertasedit = new ew_Form("falertasedit");

// Validate form
falertasedit.Validate = function(fobj) {
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
falertasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
falertasedit.ValidateRequired = true;
<?php } else { ?>
falertasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
falertasedit.Lists["x_clientes_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasedit.Lists["x_locales_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","x_direccion","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasedit.Lists["x_tiposincidencias_id"] = {"LinkField":"x_tipi_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipi_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasedit.Lists["x_tiposacciones_id"] = {"LinkField":"x_tipos_acciones_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_accion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $alertas->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $alertas->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $alertas_edit->ShowPageHeader(); ?>
<?php
$alertas_edit->ShowMessage();
?>
<form name="falertasedit" id="falertasedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" enctype="multipart/form-data" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="alertas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_alertasedit" class="ewTable">
<?php if ($alertas->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->id->CellAttributes() ?>><span id="el_alertas_id">
<span<?php echo $alertas->id->ViewAttributes() ?>>
<?php echo $alertas->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($alertas->id->CurrentValue) ?>">
</span><?php echo $alertas->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->clientes_id->Visible) { // clientes_id ?>
	<tr id="r_clientes_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_clientes_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->clientes_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->clientes_id->CellAttributes() ?>><span id="el_alertas_clientes_id">
<span<?php echo $alertas->clientes_id->ViewAttributes() ?>>
<?php echo $alertas->clientes_id->EditValue ?></span>
<input type="hidden" name="x_clientes_id" id="x_clientes_id" value="<?php echo ew_HtmlEncode($alertas->clientes_id->CurrentValue) ?>">
</span><?php echo $alertas->clientes_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->locales_id->Visible) { // locales_id ?>
	<tr id="r_locales_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_locales_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->locales_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->locales_id->CellAttributes() ?>><span id="el_alertas_locales_id">
<span<?php echo $alertas->locales_id->ViewAttributes() ?>>
<?php echo $alertas->locales_id->EditValue ?></span>
<input type="hidden" name="x_locales_id" id="x_locales_id" value="<?php echo ew_HtmlEncode($alertas->locales_id->CurrentValue) ?>">
</span><?php echo $alertas->locales_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->tiposincidencias_id->Visible) { // tiposincidencias_id ?>
	<tr id="r_tiposincidencias_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_tiposincidencias_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->tiposincidencias_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->tiposincidencias_id->CellAttributes() ?>><span id="el_alertas_tiposincidencias_id">
<span<?php echo $alertas->tiposincidencias_id->ViewAttributes() ?>>
<?php echo $alertas->tiposincidencias_id->EditValue ?></span>
<input type="hidden" name="x_tiposincidencias_id" id="x_tiposincidencias_id" value="<?php echo ew_HtmlEncode($alertas->tiposincidencias_id->CurrentValue) ?>">
</span><?php echo $alertas->tiposincidencias_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->fecha->Visible) { // fecha ?>
	<tr id="r_fecha"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_fecha"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->fecha->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->fecha->CellAttributes() ?>><span id="el_alertas_fecha">
<span<?php echo $alertas->fecha->ViewAttributes() ?>>
<?php echo $alertas->fecha->EditValue ?></span>
<input type="hidden" name="x_fecha" id="x_fecha" value="<?php echo ew_HtmlEncode($alertas->fecha->CurrentValue) ?>">
</span><?php echo $alertas->fecha->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->hora->Visible) { // hora ?>
	<tr id="r_hora"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_hora"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->hora->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->hora->CellAttributes() ?>><span id="el_alertas_hora">
<span<?php echo $alertas->hora->ViewAttributes() ?>>
<?php echo $alertas->hora->EditValue ?></span>
<input type="hidden" name="x_hora" id="x_hora" value="<?php echo ew_HtmlEncode($alertas->hora->CurrentValue) ?>">
</span><?php echo $alertas->hora->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->coordenadas->Visible) { // coordenadas ?>
	<tr id="r_coordenadas"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_coordenadas"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->coordenadas->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->coordenadas->CellAttributes() ?>><span id="el_alertas_coordenadas">
<span<?php echo $alertas->coordenadas->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($alertas->coordenadas->EditValue) && $alertas->coordenadas->LinkAttributes() <> "") { ?>
<a<?php echo $alertas->coordenadas->LinkAttributes() ?>><?php echo $alertas->coordenadas->EditValue ?></a>
<?php } else { ?>
<?php echo $alertas->coordenadas->EditValue ?>
<?php } ?>
</span>
<input type="hidden" name="x_coordenadas" id="x_coordenadas" value="<?php echo ew_HtmlEncode($alertas->coordenadas->CurrentValue) ?>">
</span><?php echo $alertas->coordenadas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->comentarios->Visible) { // comentarios ?>
	<tr id="r_comentarios"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_comentarios"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->comentarios->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->comentarios->CellAttributes() ?>><span id="el_alertas_comentarios">
<span<?php echo $alertas->comentarios->ViewAttributes() ?>>
<?php echo $alertas->comentarios->EditValue ?></span>
<input type="hidden" name="x_comentarios" id="x_comentarios" value="<?php echo ew_HtmlEncode($alertas->comentarios->CurrentValue) ?>">
</span><?php echo $alertas->comentarios->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->tiposacciones_id->Visible) { // tiposacciones_id ?>
	<tr id="r_tiposacciones_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_tiposacciones_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->tiposacciones_id->FldCaption() ?></td></tr></table></span></td>
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
falertasedit.Lists["x_tiposacciones_id"].Options = <?php echo (is_array($alertas->tiposacciones_id->EditValue)) ? ew_ArrayToJson($alertas->tiposacciones_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $alertas->tiposacciones_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($alertas->fotografia->Visible) { // fotografia ?>
	<tr id="r_fotografia"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_fotografia"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->fotografia->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->fotografia->CellAttributes() ?>><span id="el_alertas_fotografia">
<span<?php echo $alertas->fotografia->ViewAttributes() ?>>
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
</span>
<input type="hidden" name="x_fotografia" id="x_fotografia" value="<?php echo ew_HtmlEncode($alertas->fotografia->CurrentValue) ?>">
</span><?php echo $alertas->fotografia->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
falertasedit.Init();
</script>
<?php
$alertas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$alertas_edit->Page_Terminate();
?>
