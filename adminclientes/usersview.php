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

$users_view = NULL; // Initialize page object first

class cusers_view extends cusers {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{60EB35E4-509C-401C-B7D1-5F8A49BCFE4C}";

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_view';

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

		// Table object (users)
		if (!isset($GLOBALS["users"])) {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}
		$KeyUrl = "";
		if (@$_GET["usu_id"] <> "") {
			$this->RecKey["usu_id"] = $_GET["usu_id"];
			$KeyUrl .= "&usu_id=" . urlencode($this->RecKey["usu_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->usu_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["usu_id"] <> "") {
				$this->usu_id->setQueryStringValue($_GET["usu_id"]);
				$this->RecKey["usu_id"] = $this->usu_id->QueryStringValue;
			} else {
				$sReturnUrl = "userslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "userslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "userslist.php"; // Not page request, return to list
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
		$this->usu_id->setDbValue($rs->fields('usu_id'));
		$this->usu_nombre->setDbValue($rs->fields('usu_nombre'));
		$this->usu_login->setDbValue($rs->fields('usu_login'));
		$this->usu_pass->setDbValue($rs->fields('usu_pass'));
		$this->emp_id->setDbValue($rs->fields('emp_id'));
		$this->per_id->setDbValue($rs->fields('per_id'));
		$this->usu_vigente->setDbValue($rs->fields('usu_vigente'));
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
if (!isset($users_view)) $users_view = new cusers_view();

// Page init
$users_view->Page_Init();

// Page main
$users_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var users_view = new ew_Page("users_view");
users_view.PageID = "view"; // Page ID
var EW_PAGE_ID = users_view.PageID; // For backward compatibility

// Form object
var fusersview = new ew_Form("fusersview");

// Form_CustomValidate event
fusersview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusersview.ValidateRequired = true;
<?php } else { ?>
fusersview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusersview.Lists["x_emp_id"] = {"LinkField":"x_emp_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_emp_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fusersview.Lists["x_per_id"] = {"LinkField":"x_per_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_per_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $users->TableCaption() ?>&nbsp;&nbsp;</span><?php $users_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $users_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($users_view->AddUrl <> "") { ?>
<a href="<?php echo $users_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php if ($users_view->EditUrl <> "") { ?>
<a href="<?php echo $users_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php if ($users_view->DeleteUrl <> "") { ?>
<a href="<?php echo $users_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
</p>
<?php $users_view->ShowPageHeader(); ?>
<?php
$users_view->ShowMessage();
?>
<form name="fusersview" id="fusersview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="users">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_usersview" class="ewTable">
<?php if ($users->usu_id->Visible) { // usu_id ?>
	<tr id="r_usu_id"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->usu_id->CellAttributes() ?>><span id="el_users_usu_id">
<span<?php echo $users->usu_id->ViewAttributes() ?>>
<?php echo $users->usu_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->usu_nombre->Visible) { // usu_nombre ?>
	<tr id="r_usu_nombre"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_nombre->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->usu_nombre->CellAttributes() ?>><span id="el_users_usu_nombre">
<span<?php echo $users->usu_nombre->ViewAttributes() ?>>
<?php echo $users->usu_nombre->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->usu_login->Visible) { // usu_login ?>
	<tr id="r_usu_login"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_login"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_login->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->usu_login->CellAttributes() ?>><span id="el_users_usu_login">
<span<?php echo $users->usu_login->ViewAttributes() ?>>
<?php echo $users->usu_login->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->usu_pass->Visible) { // usu_pass ?>
	<tr id="r_usu_pass"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_pass"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_pass->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->usu_pass->CellAttributes() ?>><span id="el_users_usu_pass">
<span<?php echo $users->usu_pass->ViewAttributes() ?>>
<?php echo $users->usu_pass->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->emp_id->Visible) { // emp_id ?>
	<tr id="r_emp_id"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_emp_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->emp_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->emp_id->CellAttributes() ?>><span id="el_users_emp_id">
<span<?php echo $users->emp_id->ViewAttributes() ?>>
<?php echo $users->emp_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->per_id->Visible) { // per_id ?>
	<tr id="r_per_id"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_per_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->per_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->per_id->CellAttributes() ?>><span id="el_users_per_id">
<span<?php echo $users->per_id->ViewAttributes() ?>>
<?php echo $users->per_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->usu_vigente->Visible) { // usu_vigente ?>
	<tr id="r_usu_vigente"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_usu_vigente"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_vigente->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->usu_vigente->CellAttributes() ?>><span id="el_users_usu_vigente">
<span<?php echo $users->usu_vigente->ViewAttributes() ?>>
<?php echo $users->usu_vigente->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
fusersview.Init();
</script>
<?php
$users_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_view->Page_Terminate();
?>
