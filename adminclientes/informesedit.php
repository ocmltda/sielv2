<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "informesinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$informes_edit = NULL; // Initialize page object first

class cinformes_edit extends cinformes {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{BCF8DC35-3764-486D-8181-0414D54343BE}";

	// Table name
	var $TableName = 'informes';

	// Page object name
	var $PageObjName = 'informes_edit';

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

		// Table object (informes)
		if (!isset($GLOBALS["informes"])) {
			$GLOBALS["informes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["informes"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'informes', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->informes_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["informes_id"] <> "")
			$this->informes_id->setQueryStringValue($_GET["informes_id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->informes_id->CurrentValue == "")
			$this->Page_Terminate("informeslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("informeslist.php"); // No matching record, return to list
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
		$this->archivo->Upload->Index = $objForm->Index;
		$this->archivo->Upload->RestoreDbFromSession();
		if ($confirmPage) { // Post from confirm page
			$this->archivo->Upload->RestoreFromSession();
		} else {
			if ($this->archivo->Upload->UploadFile()) {

				// No action required
			} else {
				echo $this->archivo->Upload->Message;
				$this->Page_Terminate();
				exit();
			}
			$this->archivo->Upload->SaveToSession();
			$this->archivo->CurrentValue = $this->archivo->Upload->FileName;
		}
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->informes_id->FldIsDetailKey)
			$this->informes_id->setFormValue($objForm->GetValue("x_informes_id"));
		if (!$this->clientes_id->FldIsDetailKey) {
			$this->clientes_id->setFormValue($objForm->GetValue("x_clientes_id"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->periodo->FldIsDetailKey) {
			$this->periodo->setFormValue($objForm->GetValue("x_periodo"));
			$this->periodo->CurrentValue = ew_UnFormatDateTime($this->periodo->CurrentValue, 7);
		}
		if (!$this->fecha_publicacion->FldIsDetailKey) {
			$this->fecha_publicacion->setFormValue($objForm->GetValue("x_fecha_publicacion"));
			$this->fecha_publicacion->CurrentValue = ew_UnFormatDateTime($this->fecha_publicacion->CurrentValue, 7);
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->informes_id->CurrentValue = $this->informes_id->FormValue;
		$this->clientes_id->CurrentValue = $this->clientes_id->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->periodo->CurrentValue = $this->periodo->FormValue;
		$this->periodo->CurrentValue = ew_UnFormatDateTime($this->periodo->CurrentValue, 7);
		$this->fecha_publicacion->CurrentValue = $this->fecha_publicacion->FormValue;
		$this->fecha_publicacion->CurrentValue = ew_UnFormatDateTime($this->fecha_publicacion->CurrentValue, 7);
		$this->estado->CurrentValue = $this->estado->FormValue;
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
		$this->informes_id->setDbValue($rs->fields('informes_id'));
		$this->clientes_id->setDbValue($rs->fields('clientes_id'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->periodo->setDbValue($rs->fields('periodo'));
		$this->fecha_publicacion->setDbValue($rs->fields('fecha_publicacion'));
		$this->archivo->Upload->DbValue = $rs->fields('archivo');
		$this->archivo->Upload->SaveDbToSession();
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// informes_id
		// clientes_id
		// nombre
		// periodo
		// fecha_publicacion
		// archivo
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// informes_id
			$this->informes_id->ViewValue = $this->informes_id->CurrentValue;
			$this->informes_id->ViewCustomAttributes = "";

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

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// periodo
			$this->periodo->ViewValue = $this->periodo->CurrentValue;
			$this->periodo->ViewValue = ew_FormatDateTime($this->periodo->ViewValue, 7);
			$this->periodo->ViewCustomAttributes = "";

			// fecha_publicacion
			$this->fecha_publicacion->ViewValue = $this->fecha_publicacion->CurrentValue;
			$this->fecha_publicacion->ViewValue = ew_FormatDateTime($this->fecha_publicacion->ViewValue, 7);
			$this->fecha_publicacion->ViewCustomAttributes = "";

			// archivo
			$this->archivo->UploadPath = "../informes";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->ViewValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->ViewValue = "";
			}
			$this->archivo->ViewCustomAttributes = "";

			// estado
			if (strval($this->estado->CurrentValue) <> "") {
				switch ($this->estado->CurrentValue) {
					case $this->estado->FldTagValue(1):
						$this->estado->ViewValue = $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(2):
						$this->estado->ViewValue = $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->CurrentValue;
						break;
					default:
						$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// informes_id
			$this->informes_id->LinkCustomAttributes = "";
			$this->informes_id->HrefValue = "";
			$this->informes_id->TooltipValue = "";

			// clientes_id
			$this->clientes_id->LinkCustomAttributes = "";
			$this->clientes_id->HrefValue = "";
			$this->clientes_id->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// periodo
			$this->periodo->LinkCustomAttributes = "";
			$this->periodo->HrefValue = "";
			$this->periodo->TooltipValue = "";

			// fecha_publicacion
			$this->fecha_publicacion->LinkCustomAttributes = "";
			$this->fecha_publicacion->HrefValue = "";
			$this->fecha_publicacion->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->UploadPath = "../informes";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->HrefValue = ew_UploadPathEx(FALSE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
			$this->archivo->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// informes_id
			$this->informes_id->EditCustomAttributes = "";
			$this->informes_id->EditValue = $this->informes_id->CurrentValue;
			$this->informes_id->ViewCustomAttributes = "";

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

			// nombre
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

			// periodo
			$this->periodo->EditCustomAttributes = "";
			$this->periodo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->periodo->CurrentValue, 7));

			// fecha_publicacion
			$this->fecha_publicacion->EditCustomAttributes = "";
			$this->fecha_publicacion->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_publicacion->CurrentValue, 7));

			// archivo
			$this->archivo->EditCustomAttributes = "";
			$this->archivo->UploadPath = "../informes";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->EditValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->EditValue = "";
			}

			// estado
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			$this->estado->EditValue = $arwrk;

			// Edit refer script
			// informes_id

			$this->informes_id->HrefValue = "";

			// clientes_id
			$this->clientes_id->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// periodo
			$this->periodo->HrefValue = "";

			// fecha_publicacion
			$this->fecha_publicacion->HrefValue = "";

			// archivo
			$this->archivo->UploadPath = "../informes";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->HrefValue = ew_UploadPathEx(FALSE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;

			// estado
			$this->estado->HrefValue = "";
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
		if (!ew_CheckFileType($this->archivo->Upload->FileName)) {
			ew_AddMessage($gsFormError, $Language->Phrase("WrongFileType"));
		}
		if ($this->archivo->Upload->FileSize > 0 && EW_MAX_FILE_SIZE > 0 && $this->archivo->Upload->FileSize > EW_MAX_FILE_SIZE) {
			ew_AddMessage($gsFormError, str_replace("%s", EW_MAX_FILE_SIZE, $Language->Phrase("MaxFileSize")));
		}
		if (in_array($this->archivo->Upload->Error, array(1, 2, 3, 6, 7, 8))) {
			ew_AddMessage($gsFormError, $Language->Phrase("PhpUploadErr" . $this->archivo->Upload->Error));
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($this->clientes_id->FormValue) && $this->clientes_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->clientes_id->FldCaption());
		}
		if (!is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nombre->FldCaption());
		}
		if (!is_null($this->periodo->FormValue) && $this->periodo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->periodo->FldCaption());
		}
		if (!ew_CheckEuroDate($this->periodo->FormValue)) {
			ew_AddMessage($gsFormError, $this->periodo->FldErrMsg());
		}
		if (!is_null($this->fecha_publicacion->FormValue) && $this->fecha_publicacion->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->fecha_publicacion->FldCaption());
		}
		if (!ew_CheckEuroDate($this->fecha_publicacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_publicacion->FldErrMsg());
		}
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->estado->FldCaption());
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

			// clientes_id
			$this->clientes_id->SetDbValueDef($rsnew, $this->clientes_id->CurrentValue, 0, $this->clientes_id->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// periodo
			$this->periodo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->periodo->CurrentValue, 7), ew_CurrentDate(), $this->periodo->ReadOnly);

			// fecha_publicacion
			$this->fecha_publicacion->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_publicacion->CurrentValue, 7), ew_CurrentDate(), $this->fecha_publicacion->ReadOnly);

			// archivo
			if (!($this->archivo->ReadOnly)) {
			$this->archivo->UploadPath = "../informes";
			if ($this->archivo->Upload->Action == "1") { // Keep
			} elseif ($this->archivo->Upload->Action == "2" || $this->archivo->Upload->Action == "3") { // Update/Remove
			$this->archivo->Upload->DbValue = $rs->fields('archivo'); // Get original value
			if (is_null($this->archivo->Upload->Value)) {
				$rsnew['archivo'] = NULL;
			} else {
				$rsnew['archivo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->archivo->UploadPath), $this->archivo->Upload->FileName);
			}
			}
			}

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, 0, $this->estado->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				if (!ew_Empty($this->archivo->Upload->Value)) {
					$this->archivo->Upload->SaveToFile($this->archivo->UploadPath, $rsnew['archivo'], FALSE);
				}
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

		// archivo
		$this->archivo->Upload->RemoveFromSession(); // Remove file value from Session
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
if (!isset($informes_edit)) $informes_edit = new cinformes_edit();

// Page init
$informes_edit->Page_Init();

// Page main
$informes_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var informes_edit = new ew_Page("informes_edit");
informes_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = informes_edit.PageID; // For backward compatibility

// Form object
var finformesedit = new ew_Form("finformesedit");

// Validate form
finformesedit.Validate = function(fobj) {
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
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($informes->clientes_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_nombre"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($informes->nombre->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_periodo"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($informes->periodo->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_periodo"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($informes->periodo->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_fecha_publicacion"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($informes->fecha_publicacion->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_fecha_publicacion"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($informes->fecha_publicacion->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_archivo"];
		if (elm && !ew_CheckFileType(elm.value))
			return ew_OnError(this, elm, ewLanguage.Phrase("WrongFileType"));
		elm = fobj.elements["x" + infix + "_estado"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($informes->estado->FldCaption()) ?>");

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
finformesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finformesedit.ValidateRequired = true;
<?php } else { ?>
finformesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finformesedit.Lists["x_clientes_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $informes->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $informes->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $informes_edit->ShowPageHeader(); ?>
<?php
$informes_edit->ShowMessage();
?>
<form name="finformesedit" id="finformesedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" enctype="multipart/form-data" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="informes">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_informesedit" class="ewTable">
<?php if ($informes->informes_id->Visible) { // informes_id ?>
	<tr id="r_informes_id"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_informes_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->informes_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $informes->informes_id->CellAttributes() ?>><span id="el_informes_informes_id">
<span<?php echo $informes->informes_id->ViewAttributes() ?>>
<?php echo $informes->informes_id->EditValue ?></span>
<input type="hidden" name="x_informes_id" id="x_informes_id" value="<?php echo ew_HtmlEncode($informes->informes_id->CurrentValue) ?>">
</span><?php echo $informes->informes_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($informes->clientes_id->Visible) { // clientes_id ?>
	<tr id="r_clientes_id"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_clientes_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->clientes_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $informes->clientes_id->CellAttributes() ?>><span id="el_informes_clientes_id">
<select id="x_clientes_id" name="x_clientes_id"<?php echo $informes->clientes_id->EditAttributes() ?>>
<?php
if (is_array($informes->clientes_id->EditValue)) {
	$arwrk = $informes->clientes_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($informes->clientes_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
finformesedit.Lists["x_clientes_id"].Options = <?php echo (is_array($informes->clientes_id->EditValue)) ? ew_ArrayToJson($informes->clientes_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $informes->clientes_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($informes->nombre->Visible) { // nombre ?>
	<tr id="r_nombre"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $informes->nombre->CellAttributes() ?>><span id="el_informes_nombre">
<input type="text" name="x_nombre" id="x_nombre" size="60" maxlength="64" value="<?php echo $informes->nombre->EditValue ?>"<?php echo $informes->nombre->EditAttributes() ?>>
</span><?php echo $informes->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($informes->periodo->Visible) { // periodo ?>
	<tr id="r_periodo"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_periodo"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->periodo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $informes->periodo->CellAttributes() ?>><span id="el_informes_periodo">
<input type="text" name="x_periodo" id="x_periodo" size="12" maxlength="10" value="<?php echo $informes->periodo->EditValue ?>"<?php echo $informes->periodo->EditAttributes() ?>>
<?php if (!$informes->periodo->ReadOnly && !$informes->periodo->Disabled && @$informes->periodo->EditAttrs["readonly"] == "" && @$informes->periodo->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="finformesedit$x_periodo$" name="finformesedit$x_periodo$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar" style="border: 0;">
<script type="text/javascript">
ew_CreateCalendar("finformesedit", "x_periodo", "%d-%m-%Y");
</script>
<?php } ?>
</span><?php echo $informes->periodo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($informes->fecha_publicacion->Visible) { // fecha_publicacion ?>
	<tr id="r_fecha_publicacion"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_fecha_publicacion"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->fecha_publicacion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $informes->fecha_publicacion->CellAttributes() ?>><span id="el_informes_fecha_publicacion">
<input type="text" name="x_fecha_publicacion" id="x_fecha_publicacion" size="12" maxlength="10" value="<?php echo $informes->fecha_publicacion->EditValue ?>"<?php echo $informes->fecha_publicacion->EditAttributes() ?>>
<?php if (!$informes->fecha_publicacion->ReadOnly && !$informes->fecha_publicacion->Disabled && @$informes->fecha_publicacion->EditAttrs["readonly"] == "" && @$informes->fecha_publicacion->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="finformesedit$x_fecha_publicacion$" name="finformesedit$x_fecha_publicacion$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar" style="border: 0;">
<script type="text/javascript">
ew_CreateCalendar("finformesedit", "x_fecha_publicacion", "%d-%m-%Y");
</script>
<?php } ?>
</span><?php echo $informes->fecha_publicacion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($informes->archivo->Visible) { // archivo ?>
	<tr id="r_archivo"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_archivo"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->archivo->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $informes->archivo->CellAttributes() ?>><span id="el_informes_archivo">
<div id="old_x_archivo">
<?php if ($informes->archivo->LinkAttributes() <> "") { ?>
<?php if (!empty($informes->archivo->Upload->DbValue)) { ?>
<a<?php echo $informes->archivo->LinkAttributes() ?>><?php echo $informes->archivo->EditValue ?></a>
<?php } elseif (!in_array($informes->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($informes->archivo->Upload->DbValue)) { ?>
<?php echo $informes->archivo->EditValue ?>
<?php } elseif (!in_array($informes->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</div>
<div id="new_x_archivo">
<?php if ($informes->archivo->ReadOnly) { ?>
<?php if (!empty($informes->archivo->Upload->DbValue)) { ?>
<input type="hidden" name="a_archivo" id="a_archivo" value="1">
<?php } ?>
<?php } else { ?>
<?php if (!empty($informes->archivo->Upload->DbValue)) { ?>
<label><input type="radio" name="a_archivo" id="a_archivo" value="1" checked="checked"><?php echo $Language->Phrase("Keep") ?></label>&nbsp;
<label><input type="radio" name="a_archivo" id="a_archivo" value="2"><?php echo $Language->Phrase("Remove") ?></label>&nbsp;
<label><input type="radio" name="a_archivo" id="a_archivo" value="3"><?php echo $Language->Phrase("Replace") ?><br></label>
<?php $informes->archivo->EditAttrs["onchange"] = "this.form.a_archivo[2].checked=true;" . @$informes->archivo->EditAttrs["onchange"]; ?>
<?php } else { ?>
<input type="hidden" name="a_archivo" id="a_archivo" value="3">
<?php } ?>
<input type="file" name="x_archivo" id="x_archivo" size="30"<?php echo $informes->archivo->EditAttributes() ?>>
<?php } ?>
</div>
</span><?php echo $informes->archivo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($informes->estado->Visible) { // estado ?>
	<tr id="r_estado"<?php echo $informes->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_informes_estado"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $informes->estado->CellAttributes() ?>><span id="el_informes_estado">
<div id="tp_x_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_estado" id="x_estado" value="{value}"<?php echo $informes->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $informes->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($informes->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label><input type="radio" name="x_estado" id="x_estado" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $informes->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span><?php echo $informes->estado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
finformesedit.Init();
</script>
<?php
$informes_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$informes_edit->Page_Terminate();
?>
