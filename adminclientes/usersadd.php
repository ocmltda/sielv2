<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$users_add = NULL; // Initialize page object first

class cusers_add extends cusers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{60EB35E4-509C-401C-B7D1-5F8A49BCFE4C}";

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_add';

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

		// Table object (users)
		if (!isset($GLOBALS["users"])) {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

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
			if (@$_GET["usu_id"] != "") {
				$this->usu_id->setQueryStringValue($_GET["usu_id"]);
				$this->setKey("usu_id", $this->usu_id->CurrentValue); // Set up key
			} else {
				$this->setKey("usu_id", ""); // Clear key
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
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "usersview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->usu_nombre->CurrentValue = NULL;
		$this->usu_nombre->OldValue = $this->usu_nombre->CurrentValue;
		$this->usu_login->CurrentValue = NULL;
		$this->usu_login->OldValue = $this->usu_login->CurrentValue;
		$this->usu_pass->CurrentValue = NULL;
		$this->usu_pass->OldValue = $this->usu_pass->CurrentValue;
		$this->emp_id->CurrentValue = NULL;
		$this->emp_id->OldValue = $this->emp_id->CurrentValue;
		$this->per_id->CurrentValue = NULL;
		$this->per_id->OldValue = $this->per_id->CurrentValue;
		$this->usu_vigente->CurrentValue = 1;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->usu_nombre->FldIsDetailKey) {
			$this->usu_nombre->setFormValue($objForm->GetValue("x_usu_nombre"));
		}
		if (!$this->usu_login->FldIsDetailKey) {
			$this->usu_login->setFormValue($objForm->GetValue("x_usu_login"));
		}
		if (!$this->usu_pass->FldIsDetailKey) {
			$this->usu_pass->setFormValue($objForm->GetValue("x_usu_pass"));
		}
		if (!$this->emp_id->FldIsDetailKey) {
			$this->emp_id->setFormValue($objForm->GetValue("x_emp_id"));
		}
		if (!$this->per_id->FldIsDetailKey) {
			$this->per_id->setFormValue($objForm->GetValue("x_per_id"));
		}
		if (!$this->usu_vigente->FldIsDetailKey) {
			$this->usu_vigente->setFormValue($objForm->GetValue("x_usu_vigente"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->usu_nombre->CurrentValue = $this->usu_nombre->FormValue;
		$this->usu_login->CurrentValue = $this->usu_login->FormValue;
		$this->usu_pass->CurrentValue = $this->usu_pass->FormValue;
		$this->emp_id->CurrentValue = $this->emp_id->FormValue;
		$this->per_id->CurrentValue = $this->per_id->FormValue;
		$this->usu_vigente->CurrentValue = $this->usu_vigente->FormValue;
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
		$this->usu_id->setDbValue($rs->fields('usu_id'));
		$this->usu_nombre->setDbValue($rs->fields('usu_nombre'));
		$this->usu_login->setDbValue($rs->fields('usu_login'));
		$this->usu_pass->setDbValue($rs->fields('usu_pass'));
		$this->emp_id->setDbValue($rs->fields('emp_id'));
		$this->per_id->setDbValue($rs->fields('per_id'));
		$this->usu_vigente->setDbValue($rs->fields('usu_vigente'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("usu_id")) <> "")
			$this->usu_id->CurrentValue = $this->getKey("usu_id"); // usu_id
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
		// usu_id
		// usu_nombre
		// usu_login
		// usu_pass
		// emp_id
		// per_id
		// usu_vigente

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// usu_nombre
			$this->usu_nombre->EditCustomAttributes = "";
			$this->usu_nombre->EditValue = ew_HtmlEncode($this->usu_nombre->CurrentValue);

			// usu_login
			$this->usu_login->EditCustomAttributes = "";
			$this->usu_login->EditValue = ew_HtmlEncode($this->usu_login->CurrentValue);

			// usu_pass
			$this->usu_pass->EditCustomAttributes = "";
			$this->usu_pass->EditValue = ew_HtmlEncode($this->usu_pass->CurrentValue);

			// emp_id
			$this->emp_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `emp_id`, `emp_nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresa`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `emp_nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->emp_id->EditValue = $arwrk;

			// per_id
			$this->per_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `per_id`, `per_nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `perfil`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `per_nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->per_id->EditValue = $arwrk;

			// usu_vigente
			$this->usu_vigente->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->usu_vigente->FldTagValue(1), $this->usu_vigente->FldTagCaption(1) <> "" ? $this->usu_vigente->FldTagCaption(1) : $this->usu_vigente->FldTagValue(1));
			$arwrk[] = array($this->usu_vigente->FldTagValue(2), $this->usu_vigente->FldTagCaption(2) <> "" ? $this->usu_vigente->FldTagCaption(2) : $this->usu_vigente->FldTagValue(2));
			$this->usu_vigente->EditValue = $arwrk;

			// Edit refer script
			// usu_nombre

			$this->usu_nombre->HrefValue = "";

			// usu_login
			$this->usu_login->HrefValue = "";

			// usu_pass
			$this->usu_pass->HrefValue = "";

			// emp_id
			$this->emp_id->HrefValue = "";

			// per_id
			$this->per_id->HrefValue = "";

			// usu_vigente
			$this->usu_vigente->HrefValue = "";
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
		if (!is_null($this->usu_nombre->FormValue) && $this->usu_nombre->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->usu_nombre->FldCaption());
		}
		if (!is_null($this->usu_login->FormValue) && $this->usu_login->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->usu_login->FldCaption());
		}
		if (!is_null($this->usu_pass->FormValue) && $this->usu_pass->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->usu_pass->FldCaption());
		}
		if ($this->usu_vigente->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->usu_vigente->FldCaption());
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
		if ($this->usu_login->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(usu_login = '" . ew_AdjustSql($this->usu_login->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->usu_login->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->usu_login->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$rsnew = array();

		// usu_nombre
		$this->usu_nombre->SetDbValueDef($rsnew, $this->usu_nombre->CurrentValue, "", FALSE);

		// usu_login
		$this->usu_login->SetDbValueDef($rsnew, $this->usu_login->CurrentValue, "", FALSE);

		// usu_pass
		$this->usu_pass->SetDbValueDef($rsnew, $this->usu_pass->CurrentValue, "", FALSE);

		// emp_id
		$this->emp_id->SetDbValueDef($rsnew, $this->emp_id->CurrentValue, NULL, FALSE);

		// per_id
		$this->per_id->SetDbValueDef($rsnew, $this->per_id->CurrentValue, NULL, FALSE);

		// usu_vigente
		$this->usu_vigente->SetDbValueDef($rsnew, $this->usu_vigente->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
			$this->usu_id->setDbValue($conn->Insert_ID());
			$rsnew['usu_id'] = $this->usu_id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
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
if (!isset($users_add)) $users_add = new cusers_add();

// Page init
$users_add->Page_Init();

// Page main
$users_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var users_add = new ew_Page("users_add");
users_add.PageID = "add"; // Page ID
var EW_PAGE_ID = users_add.PageID; // For backward compatibility

// Form object
var fusersadd = new ew_Form("fusersadd");

// Validate form
fusersadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_usu_nombre"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->usu_nombre->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_usu_login"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->usu_login->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_usu_pass"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->usu_pass->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_usu_vigente"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->usu_vigente->FldCaption()) ?>");

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
fusersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusersadd.ValidateRequired = true;
<?php } else { ?>
fusersadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusersadd.Lists["x_emp_id"] = {"LinkField":"x_emp_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_emp_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fusersadd.Lists["x_per_id"] = {"LinkField":"x_per_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_per_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $users->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $users->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $users_add->ShowPageHeader(); ?>
<?php
$users_add->ShowMessage();
?>
<form name="fusersadd" id="fusersadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_usersadd" class="ewTable">
<?php if ($users->usu_nombre->Visible) { // usu_nombre ?>
	<tr id="r_usu_nombre"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $users->usu_nombre->CellAttributes() ?>><span id="el_users_usu_nombre">
<input type="text" name="x_usu_nombre" id="x_usu_nombre" size="60" maxlength="64" value="<?php echo $users->usu_nombre->EditValue ?>"<?php echo $users->usu_nombre->EditAttributes() ?>>
</span><?php echo $users->usu_nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->usu_login->Visible) { // usu_login ?>
	<tr id="r_usu_login"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_login"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_login->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $users->usu_login->CellAttributes() ?>><span id="el_users_usu_login">
<input type="text" name="x_usu_login" id="x_usu_login" size="30" maxlength="16" value="<?php echo $users->usu_login->EditValue ?>"<?php echo $users->usu_login->EditAttributes() ?>>
</span><?php echo $users->usu_login->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->usu_pass->Visible) { // usu_pass ?>
	<tr id="r_usu_pass"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_pass"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_pass->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $users->usu_pass->CellAttributes() ?>><span id="el_users_usu_pass">
<input type="password" name="x_usu_pass" id="x_usu_pass" size="30" maxlength="16"<?php echo $users->usu_pass->EditAttributes() ?>>
</span><?php echo $users->usu_pass->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->emp_id->Visible) { // emp_id ?>
	<tr id="r_emp_id"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_emp_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->emp_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->emp_id->CellAttributes() ?>><span id="el_users_emp_id">
<select id="x_emp_id" name="x_emp_id"<?php echo $users->emp_id->EditAttributes() ?>>
<?php
if (is_array($users->emp_id->EditValue)) {
	$arwrk = $users->emp_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($users->emp_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fusersadd.Lists["x_emp_id"].Options = <?php echo (is_array($users->emp_id->EditValue)) ? ew_ArrayToJson($users->emp_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $users->emp_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->per_id->Visible) { // per_id ?>
	<tr id="r_per_id"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_per_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->per_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->per_id->CellAttributes() ?>><span id="el_users_per_id">
<select id="x_per_id" name="x_per_id"<?php echo $users->per_id->EditAttributes() ?>>
<?php
if (is_array($users->per_id->EditValue)) {
	$arwrk = $users->per_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($users->per_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fusersadd.Lists["x_per_id"].Options = <?php echo (is_array($users->per_id->EditValue)) ? ew_ArrayToJson($users->per_id->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $users->per_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->usu_vigente->Visible) { // usu_vigente ?>
	<tr id="r_usu_vigente"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_vigente"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_vigente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $users->usu_vigente->CellAttributes() ?>><span id="el_users_usu_vigente">
<div id="tp_x_usu_vigente" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_usu_vigente" id="x_usu_vigente" value="{value}"<?php echo $users->usu_vigente->EditAttributes() ?>></div>
<div id="dsl_x_usu_vigente" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $users->usu_vigente->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($users->usu_vigente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label><input type="radio" name="x_usu_vigente" id="x_usu_vigente" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->usu_vigente->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span><?php echo $users->usu_vigente->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fusersadd.Init();
</script>
<?php
$users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_add->Page_Terminate();
?>
