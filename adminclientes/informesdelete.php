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

$informes_delete = NULL; // Initialize page object first

class cinformes_delete extends cinformes {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{BCF8DC35-3764-486D-8181-0414D54343BE}";

	// Table name
	var $TableName = 'informes';

	// Page object name
	var $PageObjName = 'informes_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("informeslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in informes class, informesinfo.php

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
		$this->informes_id->setDbValue($rs->fields('informes_id'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->periodo->setDbValue($rs->fields('periodo'));
		$this->fecha_publicacion->setDbValue($rs->fields('fecha_publicacion'));
		$this->archivo->Upload->DbValue = $rs->fields('archivo');
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
		// nombre
		// periodo
		// fecha_publicacion
		// archivo
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// informes_id
			$this->informes_id->ViewValue = $this->informes_id->CurrentValue;
			$this->informes_id->ViewCustomAttributes = "";

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
			$this->archivo->HrefValue = "";
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
			$this->archivo->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
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
				$sThisKey .= $row['informes_id'];
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
if (!isset($informes_delete)) $informes_delete = new cinformes_delete();

// Page init
$informes_delete->Page_Init();

// Page main
$informes_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var informes_delete = new ew_Page("informes_delete");
informes_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = informes_delete.PageID; // For backward compatibility

// Form object
var finformesdelete = new ew_Form("finformesdelete");

// Form_CustomValidate event
finformesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finformesdelete.ValidateRequired = true;
<?php } else { ?>
finformesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($informes_delete->Recordset = $informes_delete->LoadRecordset())
	$informes_deleteTotalRecs = $informes_delete->Recordset->RecordCount(); // Get record count
if ($informes_deleteTotalRecs <= 0) { // No record found, exit
	if ($informes_delete->Recordset)
		$informes_delete->Recordset->Close();
	$informes_delete->Page_Terminate("informeslist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $informes->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $informes->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $informes_delete->ShowPageHeader(); ?>
<?php
$informes_delete->ShowMessage();
?>
<form name="finformesdelete" id="finformesdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="informes">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($informes_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_informesdelete" class="ewTable ewTableSeparate">
<?php echo $informes->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_informes_informes_id" class="informes_informes_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->informes_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_informes_nombre" class="informes_nombre"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->nombre->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_informes_periodo" class="informes_periodo"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->periodo->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_informes_fecha_publicacion" class="informes_fecha_publicacion"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->fecha_publicacion->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_informes_archivo" class="informes_archivo"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->archivo->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_informes_estado" class="informes_estado"><table class="ewTableHeaderBtn"><tr><td><?php echo $informes->estado->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$informes_delete->RecCnt = 0;
$i = 0;
while (!$informes_delete->Recordset->EOF) {
	$informes_delete->RecCnt++;
	$informes_delete->RowCnt++;

	// Set row properties
	$informes->ResetAttrs();
	$informes->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$informes_delete->LoadRowValues($informes_delete->Recordset);

	// Render row
	$informes_delete->RenderRow();
?>
	<tr<?php echo $informes->RowAttributes() ?>>
		<td<?php echo $informes->informes_id->CellAttributes() ?>><span id="el<?php echo $informes_delete->RowCnt ?>_informes_informes_id" class="informes_informes_id">
<span<?php echo $informes->informes_id->ViewAttributes() ?>>
<?php echo $informes->informes_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $informes->nombre->CellAttributes() ?>><span id="el<?php echo $informes_delete->RowCnt ?>_informes_nombre" class="informes_nombre">
<span<?php echo $informes->nombre->ViewAttributes() ?>>
<?php echo $informes->nombre->ListViewValue() ?></span>
</span></td>
		<td<?php echo $informes->periodo->CellAttributes() ?>><span id="el<?php echo $informes_delete->RowCnt ?>_informes_periodo" class="informes_periodo">
<span<?php echo $informes->periodo->ViewAttributes() ?>>
<?php echo $informes->periodo->ListViewValue() ?></span>
</span></td>
		<td<?php echo $informes->fecha_publicacion->CellAttributes() ?>><span id="el<?php echo $informes_delete->RowCnt ?>_informes_fecha_publicacion" class="informes_fecha_publicacion">
<span<?php echo $informes->fecha_publicacion->ViewAttributes() ?>>
<?php echo $informes->fecha_publicacion->ListViewValue() ?></span>
</span></td>
		<td<?php echo $informes->archivo->CellAttributes() ?>><span id="el<?php echo $informes_delete->RowCnt ?>_informes_archivo" class="informes_archivo">
<span<?php echo $informes->archivo->ViewAttributes() ?>>
<?php if ($informes->archivo->LinkAttributes() <> "") { ?>
<?php if (!empty($informes->archivo->Upload->DbValue)) { ?>
<?php echo $informes->archivo->ListViewValue() ?>
<?php } elseif (!in_array($informes->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($informes->archivo->Upload->DbValue)) { ?>
<?php echo $informes->archivo->ListViewValue() ?>
<?php } elseif (!in_array($informes->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
		<td<?php echo $informes->estado->CellAttributes() ?>><span id="el<?php echo $informes_delete->RowCnt ?>_informes_estado" class="informes_estado">
<span<?php echo $informes->estado->ViewAttributes() ?>>
<?php echo $informes->estado->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$informes_delete->Recordset->MoveNext();
}
$informes_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
finformesdelete.Init();
</script>
<?php
$informes_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$informes_delete->Page_Terminate();
?>
