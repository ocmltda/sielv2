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

$users_delete = NULL; // Initialize page object first

class cusers_delete extends cusers {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{60EB35E4-509C-401C-B7D1-5F8A49BCFE4C}";

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("userslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in users class, usersinfo.php

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
				$sThisKey .= $row['usu_id'];
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
if (!isset($users_delete)) $users_delete = new cusers_delete();

// Page init
$users_delete->Page_Init();

// Page main
$users_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var users_delete = new ew_Page("users_delete");
users_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = users_delete.PageID; // For backward compatibility

// Form object
var fusersdelete = new ew_Form("fusersdelete");

// Form_CustomValidate event
fusersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusersdelete.ValidateRequired = true;
<?php } else { ?>
fusersdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusersdelete.Lists["x_emp_id"] = {"LinkField":"x_emp_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_emp_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fusersdelete.Lists["x_per_id"] = {"LinkField":"x_per_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_per_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($users_delete->Recordset = $users_delete->LoadRecordset())
	$users_deleteTotalRecs = $users_delete->Recordset->RecordCount(); // Get record count
if ($users_deleteTotalRecs <= 0) { // No record found, exit
	if ($users_delete->Recordset)
		$users_delete->Recordset->Close();
	$users_delete->Page_Terminate("userslist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $users->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $users->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $users_delete->ShowPageHeader(); ?>
<?php
$users_delete->ShowMessage();
?>
<form name="fusersdelete" id="fusersdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($users_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_usersdelete" class="ewTable ewTableSeparate">
<?php echo $users->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_users_usu_id" class="users_usu_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_users_usu_nombre" class="users_usu_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_nombre->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_users_usu_login" class="users_usu_login"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_login->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_users_usu_pass" class="users_usu_pass"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_pass->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_users_emp_id" class="users_emp_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->emp_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_users_per_id" class="users_per_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->per_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_users_usu_vigente" class="users_usu_vigente"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->usu_vigente->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$users_delete->RecCnt = 0;
$i = 0;
while (!$users_delete->Recordset->EOF) {
	$users_delete->RecCnt++;
	$users_delete->RowCnt++;

	// Set row properties
	$users->ResetAttrs();
	$users->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$users_delete->LoadRowValues($users_delete->Recordset);

	// Render row
	$users_delete->RenderRow();
?>
	<tr<?php echo $users->RowAttributes() ?>>
		<td<?php echo $users->usu_id->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_usu_id" class="users_usu_id">
<span<?php echo $users->usu_id->ViewAttributes() ?>>
<?php echo $users->usu_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $users->usu_nombre->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_usu_nombre" class="users_usu_nombre">
<span<?php echo $users->usu_nombre->ViewAttributes() ?>>
<?php echo $users->usu_nombre->ListViewValue() ?></span>
</span></td>
		<td<?php echo $users->usu_login->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_usu_login" class="users_usu_login">
<span<?php echo $users->usu_login->ViewAttributes() ?>>
<?php echo $users->usu_login->ListViewValue() ?></span>
</span></td>
		<td<?php echo $users->usu_pass->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_usu_pass" class="users_usu_pass">
<span<?php echo $users->usu_pass->ViewAttributes() ?>>
<?php echo $users->usu_pass->ListViewValue() ?></span>
</span></td>
		<td<?php echo $users->emp_id->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_emp_id" class="users_emp_id">
<span<?php echo $users->emp_id->ViewAttributes() ?>>
<?php echo $users->emp_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $users->per_id->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_per_id" class="users_per_id">
<span<?php echo $users->per_id->ViewAttributes() ?>>
<?php echo $users->per_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $users->usu_vigente->CellAttributes() ?>><span id="el<?php echo $users_delete->RowCnt ?>_users_usu_vigente" class="users_usu_vigente">
<span<?php echo $users->usu_vigente->ViewAttributes() ?>>
<?php echo $users->usu_vigente->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$users_delete->Recordset->MoveNext();
}
$users_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fusersdelete.Init();
</script>
<?php
$users_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_delete->Page_Terminate();
?>
