<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "_menuinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$p_menu_add = NULL; // Initialize page object first

class cp_menu_add extends c_menu {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{BCF8DC35-3764-486D-8181-0414D54343BE}";

	// Table name
	var $TableName = 'menu';

	// Page object name
	var $PageObjName = 'p_menu_add';

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

		// Table object (_menu)
		if (!isset($GLOBALS["_menu"])) {
			$GLOBALS["_menu"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["_menu"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'menu', TRUE);

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
			if (@$_GET["men_id"] != "") {
				$this->men_id->setQueryStringValue($_GET["men_id"]);
				$this->setKey("men_id", $this->men_id->CurrentValue); // Set up key
			} else {
				$this->setKey("men_id", ""); // Clear key
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
					$this->Page_Terminate("_menulist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "_menuview.php")
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
		$this->men_nombre->CurrentValue = NULL;
		$this->men_nombre->OldValue = $this->men_nombre->CurrentValue;
		$this->men_link->CurrentValue = NULL;
		$this->men_link->OldValue = $this->men_link->CurrentValue;
		$this->men_orden->CurrentValue = NULL;
		$this->men_orden->OldValue = $this->men_orden->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->men_nombre->FldIsDetailKey) {
			$this->men_nombre->setFormValue($objForm->GetValue("x_men_nombre"));
		}
		if (!$this->men_link->FldIsDetailKey) {
			$this->men_link->setFormValue($objForm->GetValue("x_men_link"));
		}
		if (!$this->men_orden->FldIsDetailKey) {
			$this->men_orden->setFormValue($objForm->GetValue("x_men_orden"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->men_nombre->CurrentValue = $this->men_nombre->FormValue;
		$this->men_link->CurrentValue = $this->men_link->FormValue;
		$this->men_orden->CurrentValue = $this->men_orden->FormValue;
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
		$this->men_id->setDbValue($rs->fields('men_id'));
		$this->men_nombre->setDbValue($rs->fields('men_nombre'));
		$this->men_link->setDbValue($rs->fields('men_link'));
		$this->men_orden->setDbValue($rs->fields('men_orden'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("men_id")) <> "")
			$this->men_id->CurrentValue = $this->getKey("men_id"); // men_id
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
		// men_id
		// men_nombre
		// men_link
		// men_orden

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// men_id
			$this->men_id->ViewValue = $this->men_id->CurrentValue;
			$this->men_id->ViewCustomAttributes = "";

			// men_nombre
			$this->men_nombre->ViewValue = $this->men_nombre->CurrentValue;
			$this->men_nombre->ViewCustomAttributes = "";

			// men_link
			$this->men_link->ViewValue = $this->men_link->CurrentValue;
			$this->men_link->ViewCustomAttributes = "";

			// men_orden
			$this->men_orden->ViewValue = $this->men_orden->CurrentValue;
			$this->men_orden->ViewCustomAttributes = "";

			// men_nombre
			$this->men_nombre->LinkCustomAttributes = "";
			$this->men_nombre->HrefValue = "";
			$this->men_nombre->TooltipValue = "";

			// men_link
			$this->men_link->LinkCustomAttributes = "";
			$this->men_link->HrefValue = "";
			$this->men_link->TooltipValue = "";

			// men_orden
			$this->men_orden->LinkCustomAttributes = "";
			$this->men_orden->HrefValue = "";
			$this->men_orden->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// men_nombre
			$this->men_nombre->EditCustomAttributes = "";
			$this->men_nombre->EditValue = ew_HtmlEncode($this->men_nombre->CurrentValue);

			// men_link
			$this->men_link->EditCustomAttributes = "";
			$this->men_link->EditValue = ew_HtmlEncode($this->men_link->CurrentValue);

			// men_orden
			$this->men_orden->EditCustomAttributes = "";
			$this->men_orden->EditValue = ew_HtmlEncode($this->men_orden->CurrentValue);

			// Edit refer script
			// men_nombre

			$this->men_nombre->HrefValue = "";

			// men_link
			$this->men_link->HrefValue = "";

			// men_orden
			$this->men_orden->HrefValue = "";
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
		if (!is_null($this->men_nombre->FormValue) && $this->men_nombre->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->men_nombre->FldCaption());
		}
		if (!is_null($this->men_link->FormValue) && $this->men_link->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->men_link->FldCaption());
		}
		if (!is_null($this->men_orden->FormValue) && $this->men_orden->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->men_orden->FldCaption());
		}
		if (!ew_CheckInteger($this->men_orden->FormValue)) {
			ew_AddMessage($gsFormError, $this->men_orden->FldErrMsg());
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
		if ($this->men_nombre->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(men_nombre = '" . ew_AdjustSql($this->men_nombre->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->men_nombre->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->men_nombre->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->men_link->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(men_link = '" . ew_AdjustSql($this->men_link->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->men_link->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->men_link->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$rsnew = array();

		// men_nombre
		$this->men_nombre->SetDbValueDef($rsnew, $this->men_nombre->CurrentValue, "", FALSE);

		// men_link
		$this->men_link->SetDbValueDef($rsnew, $this->men_link->CurrentValue, "", FALSE);

		// men_orden
		$this->men_orden->SetDbValueDef($rsnew, $this->men_orden->CurrentValue, 0, FALSE);

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
			$this->men_id->setDbValue($conn->Insert_ID());
			$rsnew['men_id'] = $this->men_id->DbValue;
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
if (!isset($p_menu_add)) $p_menu_add = new cp_menu_add();

// Page init
$p_menu_add->Page_Init();

// Page main
$p_menu_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var p_menu_add = new ew_Page("p_menu_add");
p_menu_add.PageID = "add"; // Page ID
var EW_PAGE_ID = p_menu_add.PageID; // For backward compatibility

// Form object
var f_menuadd = new ew_Form("f_menuadd");

// Validate form
f_menuadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_men_nombre"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($_menu->men_nombre->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_men_link"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($_menu->men_link->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_men_orden"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($_menu->men_orden->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_men_orden"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($_menu->men_orden->FldErrMsg()) ?>");

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
f_menuadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
f_menuadd.ValidateRequired = true;
<?php } else { ?>
f_menuadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $_menu->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $_menu->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $p_menu_add->ShowPageHeader(); ?>
<?php
$p_menu_add->ShowMessage();
?>
<form name="f_menuadd" id="f_menuadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="_menu">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl__menuadd" class="ewTable">
<?php if ($_menu->men_nombre->Visible) { // men_nombre ?>
	<tr id="r_men_nombre"<?php echo $_menu->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh__menu_men_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $_menu->men_nombre->CellAttributes() ?>><span id="el__menu_men_nombre">
<input type="text" name="x_men_nombre" id="x_men_nombre" size="60" maxlength="64" value="<?php echo $_menu->men_nombre->EditValue ?>"<?php echo $_menu->men_nombre->EditAttributes() ?>>
</span><?php echo $_menu->men_nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($_menu->men_link->Visible) { // men_link ?>
	<tr id="r_men_link"<?php echo $_menu->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh__menu_men_link"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_link->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $_menu->men_link->CellAttributes() ?>><span id="el__menu_men_link">
<input type="text" name="x_men_link" id="x_men_link" size="30" maxlength="32" value="<?php echo $_menu->men_link->EditValue ?>"<?php echo $_menu->men_link->EditAttributes() ?>>
</span><?php echo $_menu->men_link->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($_menu->men_orden->Visible) { // men_orden ?>
	<tr id="r_men_orden"<?php echo $_menu->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh__menu_men_orden"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_orden->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $_menu->men_orden->CellAttributes() ?>><span id="el__menu_men_orden">
<input type="text" name="x_men_orden" id="x_men_orden" size="10" maxlength="3" value="<?php echo $_menu->men_orden->EditValue ?>"<?php echo $_menu->men_orden->EditAttributes() ?>>
</span><?php echo $_menu->men_orden->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
f_menuadd.Init();
</script>
<?php
$p_menu_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$p_menu_add->Page_Terminate();
?>
