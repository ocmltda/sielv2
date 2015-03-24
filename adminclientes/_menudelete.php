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

$p_menu_delete = NULL; // Initialize page object first

class cp_menu_delete extends c_menu {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{BCF8DC35-3764-486D-8181-0414D54343BE}";

	// Table name
	var $TableName = 'menu';

	// Page object name
	var $PageObjName = 'p_menu_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->men_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("_menulist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in _menu class, _menuinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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

			// men_id
			$this->men_id->LinkCustomAttributes = "";
			$this->men_id->HrefValue = "";
			$this->men_id->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['men_id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
if (!isset($p_menu_delete)) $p_menu_delete = new cp_menu_delete();

// Page init
$p_menu_delete->Page_Init();

// Page main
$p_menu_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var p_menu_delete = new ew_Page("p_menu_delete");
p_menu_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = p_menu_delete.PageID; // For backward compatibility

// Form object
var f_menudelete = new ew_Form("f_menudelete");

// Form_CustomValidate event
f_menudelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
f_menudelete.ValidateRequired = true;
<?php } else { ?>
f_menudelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($p_menu_delete->Recordset = $p_menu_delete->LoadRecordset())
	$p_menu_deleteTotalRecs = $p_menu_delete->Recordset->RecordCount(); // Get record count
if ($p_menu_deleteTotalRecs <= 0) { // No record found, exit
	if ($p_menu_delete->Recordset)
		$p_menu_delete->Recordset->Close();
	$p_menu_delete->Page_Terminate("_menulist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $_menu->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $_menu->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $p_menu_delete->ShowPageHeader(); ?>
<?php
$p_menu_delete->ShowMessage();
?>
<form name="f_menudelete" id="f_menudelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="_menu">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($p_menu_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl__menudelete" class="ewTable ewTableSeparate">
<?php echo $_menu->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh__menu_men_id" class="_menu_men_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh__menu_men_nombre" class="_menu_men_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_nombre->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh__menu_men_link" class="_menu_men_link"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_link->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh__menu_men_orden" class="_menu_men_orden"><table class="ewTableHeaderBtn"><tr><td><?php echo $_menu->men_orden->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$p_menu_delete->RecCnt = 0;
$i = 0;
while (!$p_menu_delete->Recordset->EOF) {
	$p_menu_delete->RecCnt++;
	$p_menu_delete->RowCnt++;

	// Set row properties
	$_menu->ResetAttrs();
	$_menu->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$p_menu_delete->LoadRowValues($p_menu_delete->Recordset);

	// Render row
	$p_menu_delete->RenderRow();
?>
	<tr<?php echo $_menu->RowAttributes() ?>>
		<td<?php echo $_menu->men_id->CellAttributes() ?>><span id="el<?php echo $p_menu_delete->RowCnt ?>__menu_men_id" class="_menu_men_id">
<span<?php echo $_menu->men_id->ViewAttributes() ?>>
<?php echo $_menu->men_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $_menu->men_nombre->CellAttributes() ?>><span id="el<?php echo $p_menu_delete->RowCnt ?>__menu_men_nombre" class="_menu_men_nombre">
<span<?php echo $_menu->men_nombre->ViewAttributes() ?>>
<?php echo $_menu->men_nombre->ListViewValue() ?></span>
</span></td>
		<td<?php echo $_menu->men_link->CellAttributes() ?>><span id="el<?php echo $p_menu_delete->RowCnt ?>__menu_men_link" class="_menu_men_link">
<span<?php echo $_menu->men_link->ViewAttributes() ?>>
<?php echo $_menu->men_link->ListViewValue() ?></span>
</span></td>
		<td<?php echo $_menu->men_orden->CellAttributes() ?>><span id="el<?php echo $p_menu_delete->RowCnt ?>__menu_men_orden" class="_menu_men_orden">
<span<?php echo $_menu->men_orden->ViewAttributes() ?>>
<?php echo $_menu->men_orden->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$p_menu_delete->Recordset->MoveNext();
}
$p_menu_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
f_menudelete.Init();
</script>
<?php
$p_menu_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$p_menu_delete->Page_Terminate();
?>
