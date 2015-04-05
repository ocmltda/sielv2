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

$alertas_view = NULL; // Initialize page object first

class calertas_view extends calertas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{01BDD2DE-C1A6-464D-8FDD-3525837E1545}";

	// Table name
	var $TableName = 'alertas';

	// Page object name
	var $PageObjName = 'alertas_view';

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'alertas', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
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
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "alertaslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "alertaslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "alertaslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
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
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($alertas_view)) $alertas_view = new calertas_view();

// Page init
$alertas_view->Page_Init();

// Page main
$alertas_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var alertas_view = new ew_Page("alertas_view");
alertas_view.PageID = "view"; // Page ID
var EW_PAGE_ID = alertas_view.PageID; // For backward compatibility

// Form object
var falertasview = new ew_Form("falertasview");

// Form_CustomValidate event
falertasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
falertasview.ValidateRequired = true;
<?php } else { ?>
falertasview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
falertasview.Lists["x_clientes_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasview.Lists["x_locales_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","x_direccion","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasview.Lists["x_tiposincidencias_id"] = {"LinkField":"x_tipi_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipi_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
falertasview.Lists["x_tiposacciones_id"] = {"LinkField":"x_tipos_acciones_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_accion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $alertas->TableCaption() ?>&nbsp;&nbsp;</span><?php $alertas_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $alertas_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($alertas_view->EditUrl <> "") { ?>
<a href="<?php echo $alertas_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($alertas_view->DeleteUrl <> "") { ?>
<a href="<?php echo $alertas_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $alertas_view->ShowPageHeader(); ?>
<?php
$alertas_view->ShowMessage();
?>
<form name="falertasview" id="falertasview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="alertas">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_alertasview" class="ewTable">
<?php if ($alertas->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->id->CellAttributes() ?>><span id="el_alertas_id">
<span<?php echo $alertas->id->ViewAttributes() ?>>
<?php echo $alertas->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->clientes_id->Visible) { // clientes_id ?>
	<tr id="r_clientes_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_clientes_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->clientes_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->clientes_id->CellAttributes() ?>><span id="el_alertas_clientes_id">
<span<?php echo $alertas->clientes_id->ViewAttributes() ?>>
<?php echo $alertas->clientes_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->locales_id->Visible) { // locales_id ?>
	<tr id="r_locales_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_locales_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->locales_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->locales_id->CellAttributes() ?>><span id="el_alertas_locales_id">
<span<?php echo $alertas->locales_id->ViewAttributes() ?>>
<?php echo $alertas->locales_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->tiposincidencias_id->Visible) { // tiposincidencias_id ?>
	<tr id="r_tiposincidencias_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_tiposincidencias_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->tiposincidencias_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->tiposincidencias_id->CellAttributes() ?>><span id="el_alertas_tiposincidencias_id">
<span<?php echo $alertas->tiposincidencias_id->ViewAttributes() ?>>
<?php echo $alertas->tiposincidencias_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->fecha->Visible) { // fecha ?>
	<tr id="r_fecha"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_fecha"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->fecha->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->fecha->CellAttributes() ?>><span id="el_alertas_fecha">
<span<?php echo $alertas->fecha->ViewAttributes() ?>>
<?php echo $alertas->fecha->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->hora->Visible) { // hora ?>
	<tr id="r_hora"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_hora"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->hora->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->hora->CellAttributes() ?>><span id="el_alertas_hora">
<span<?php echo $alertas->hora->ViewAttributes() ?>>
<?php echo $alertas->hora->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->coordenadas->Visible) { // coordenadas ?>
	<tr id="r_coordenadas"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_coordenadas"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->coordenadas->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->coordenadas->CellAttributes() ?>><span id="el_alertas_coordenadas">
<span<?php echo $alertas->coordenadas->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($alertas->coordenadas->ViewValue) && $alertas->coordenadas->LinkAttributes() <> "") { ?>
<a<?php echo $alertas->coordenadas->LinkAttributes() ?>><?php echo $alertas->coordenadas->ViewValue ?></a>
<?php } else { ?>
<?php echo $alertas->coordenadas->ViewValue ?>
<?php } ?>
</span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->comentarios->Visible) { // comentarios ?>
	<tr id="r_comentarios"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_comentarios"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->comentarios->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->comentarios->CellAttributes() ?>><span id="el_alertas_comentarios">
<span<?php echo $alertas->comentarios->ViewAttributes() ?>>
<?php echo $alertas->comentarios->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->tiposacciones_id->Visible) { // tiposacciones_id ?>
	<tr id="r_tiposacciones_id"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_tiposacciones_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->tiposacciones_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->tiposacciones_id->CellAttributes() ?>><span id="el_alertas_tiposacciones_id">
<span<?php echo $alertas->tiposacciones_id->ViewAttributes() ?>>
<?php echo $alertas->tiposacciones_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($alertas->fotografia->Visible) { // fotografia ?>
	<tr id="r_fotografia"<?php echo $alertas->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_alertas_fotografia"><table class="ewTableHeaderBtn"><tr><td><?php echo $alertas->fotografia->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $alertas->fotografia->CellAttributes() ?>><span id="el_alertas_fotografia">
<span<?php echo $alertas->fotografia->ViewAttributes() ?>>
<?php if ($alertas->fotografia->LinkAttributes() <> "") { ?>
<?php if (!empty($alertas->fotografia->Upload->DbValue)) { ?>
<a<?php echo $alertas->fotografia->LinkAttributes() ?>><?php echo $alertas->fotografia->ViewValue ?></a>
<?php } elseif (!in_array($alertas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($alertas->fotografia->Upload->DbValue)) { ?>
<?php echo $alertas->fotografia->ViewValue ?>
<?php } elseif (!in_array($alertas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
falertasview.Init();
</script>
<?php
$alertas_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$alertas_view->Page_Terminate();
?>
