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

$p_menu_list = NULL; // Initialize page object first

class cp_menu_list extends c_menu {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{BCF8DC35-3764-486D-8181-0414D54343BE}";

	// Table name
	var $TableName = 'menu';

	// Page object name
	var $PageObjName = 'p_menu_list';

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

		// Table object (_menu)
		if (!isset($GLOBALS["_menu"])) {
			$GLOBALS["_menu"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["_menu"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "_menuadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "_menudelete.php";
		$this->MultiUpdateUrl = "_menuupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'menu', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall")
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search") {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->men_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->men_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->men_nombre, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->men_link, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->men_id); // men_id
			$this->UpdateSort($this->men_nombre); // men_nombre
			$this->UpdateSort($this->men_link); // men_link
			$this->UpdateSort($this->men_orden); // men_orden
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->men_id->setSort("");
				$this->men_nombre->setSort("");
				$this->men_link->setSort("");
				$this->men_orden->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->CssStyle = "white-space: nowrap; text-align: center; vertical-align: middle; margin: 0px;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" class=\"phpmaker\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		if (TRUE)
			$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->men_id->CurrentValue) . "\" class=\"phpmaker\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($p_menu_list)) $p_menu_list = new cp_menu_list();

// Page init
$p_menu_list->Page_Init();

// Page main
$p_menu_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var p_menu_list = new ew_Page("p_menu_list");
p_menu_list.PageID = "list"; // Page ID
var EW_PAGE_ID = p_menu_list.PageID; // For backward compatibility

// Form object
var f_menulist = new ew_Form("f_menulist");

// Form_CustomValidate event
f_menulist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
f_menulist.ValidateRequired = true;
<?php } else { ?>
f_menulist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var f_menulistsrch = new ew_Form("f_menulistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$p_menu_list->TotalRecs = $_menu->SelectRecordCount();
	} else {
		if ($p_menu_list->Recordset = $p_menu_list->LoadRecordset())
			$p_menu_list->TotalRecs = $p_menu_list->Recordset->RecordCount();
	}
	$p_menu_list->StartRec = 1;
	if ($p_menu_list->DisplayRecs <= 0 || ($_menu->Export <> "" && $_menu->ExportAll)) // Display all records
		$p_menu_list->DisplayRecs = $p_menu_list->TotalRecs;
	if (!($_menu->Export <> "" && $_menu->ExportAll))
		$p_menu_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$p_menu_list->Recordset = $p_menu_list->LoadRecordset($p_menu_list->StartRec-1, $p_menu_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $_menu->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $p_menu_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($_menu->Export == "" && $_menu->CurrentAction == "") { ?>
<form name="f_menulistsrch" id="f_menulistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:f_menulistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="f_menulistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" style="border: 0;"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="f_menulistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="_menu">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($p_menu_list->BasicSearch->getKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $p_menu_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($p_menu_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($p_menu_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($p_menu_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php $p_menu_list->ShowPageHeader(); ?>
<?php
$p_menu_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridUpperPanel">
<?php if ($_menu->CurrentAction <> "gridadd" && $_menu->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($p_menu_list->Pager)) $p_menu_list->Pager = new cPrevNextPager($p_menu_list->StartRec, $p_menu_list->DisplayRecs, $p_menu_list->TotalRecs) ?>
<?php if ($p_menu_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($p_menu_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($p_menu_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $p_menu_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($p_menu_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($p_menu_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $p_menu_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $p_menu_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $p_menu_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $p_menu_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($p_menu_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
	</td>
</tr></table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($p_menu_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $p_menu_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($p_menu_list->TotalRecs > 0) { ?>
<a class="ewGridLink" href="" onclick="ew_SubmitSelected(document.f_menulist, '<?php echo $p_menu_list->MultiDeleteUrl ?>');return false;"><?php echo $Language->Phrase("DeleteSelectedLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</span>
</div>
<form name="f_menulist" id="f_menulist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="_menu">
<div id="gmp__menu" class="ewGridMiddlePanel">
<?php if ($p_menu_list->TotalRecs > 0) { ?>
<table id="tbl__menulist" class="ewTable ewTableSeparate">
<?php echo $_menu->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$p_menu_list->RenderListOptions();

// Render list options (header, left)
$p_menu_list->ListOptions->Render("header", "left");
?>
<?php if ($_menu->men_id->Visible) { // men_id ?>
	<?php if ($_menu->SortUrl($_menu->men_id) == "") { ?>
		<td><span id="elh__menu_men_id" class="_menu_men_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $_menu->men_id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $_menu->SortUrl($_menu->men_id) ?>',1);"><span id="elh__menu_men_id" class="_menu_men_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $_menu->men_id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($_menu->men_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($_menu->men_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($_menu->men_nombre->Visible) { // men_nombre ?>
	<?php if ($_menu->SortUrl($_menu->men_nombre) == "") { ?>
		<td><span id="elh__menu_men_nombre" class="_menu_men_nombre"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $_menu->men_nombre->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $_menu->SortUrl($_menu->men_nombre) ?>',1);"><span id="elh__menu_men_nombre" class="_menu_men_nombre">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $_menu->men_nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($_menu->men_nombre->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($_menu->men_nombre->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($_menu->men_link->Visible) { // men_link ?>
	<?php if ($_menu->SortUrl($_menu->men_link) == "") { ?>
		<td><span id="elh__menu_men_link" class="_menu_men_link"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $_menu->men_link->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $_menu->SortUrl($_menu->men_link) ?>',1);"><span id="elh__menu_men_link" class="_menu_men_link">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $_menu->men_link->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($_menu->men_link->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($_menu->men_link->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($_menu->men_orden->Visible) { // men_orden ?>
	<?php if ($_menu->SortUrl($_menu->men_orden) == "") { ?>
		<td><span id="elh__menu_men_orden" class="_menu_men_orden"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $_menu->men_orden->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $_menu->SortUrl($_menu->men_orden) ?>',1);"><span id="elh__menu_men_orden" class="_menu_men_orden">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $_menu->men_orden->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($_menu->men_orden->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($_menu->men_orden->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$p_menu_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($_menu->ExportAll && $_menu->Export <> "") {
	$p_menu_list->StopRec = $p_menu_list->TotalRecs;
} else {

	// Set the last record to display
	if ($p_menu_list->TotalRecs > $p_menu_list->StartRec + $p_menu_list->DisplayRecs - 1)
		$p_menu_list->StopRec = $p_menu_list->StartRec + $p_menu_list->DisplayRecs - 1;
	else
		$p_menu_list->StopRec = $p_menu_list->TotalRecs;
}
$p_menu_list->RecCnt = $p_menu_list->StartRec - 1;
if ($p_menu_list->Recordset && !$p_menu_list->Recordset->EOF) {
	$p_menu_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $p_menu_list->StartRec > 1)
		$p_menu_list->Recordset->Move($p_menu_list->StartRec - 1);
} elseif (!$_menu->AllowAddDeleteRow && $p_menu_list->StopRec == 0) {
	$p_menu_list->StopRec = $_menu->GridAddRowCount;
}

// Initialize aggregate
$_menu->RowType = EW_ROWTYPE_AGGREGATEINIT;
$_menu->ResetAttrs();
$p_menu_list->RenderRow();
while ($p_menu_list->RecCnt < $p_menu_list->StopRec) {
	$p_menu_list->RecCnt++;
	if (intval($p_menu_list->RecCnt) >= intval($p_menu_list->StartRec)) {
		$p_menu_list->RowCnt++;

		// Set up key count
		$p_menu_list->KeyCount = $p_menu_list->RowIndex;

		// Init row class and style
		$_menu->ResetAttrs();
		$_menu->CssClass = "";
		if ($_menu->CurrentAction == "gridadd") {
		} else {
			$p_menu_list->LoadRowValues($p_menu_list->Recordset); // Load row values
		}
		$_menu->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$_menu->RowAttrs = array_merge($_menu->RowAttrs, array('data-rowindex'=>$p_menu_list->RowCnt, 'id'=>'r' . $p_menu_list->RowCnt . '__menu', 'data-rowtype'=>$_menu->RowType));

		// Render row
		$p_menu_list->RenderRow();

		// Render list options
		$p_menu_list->RenderListOptions();
?>
	<tr<?php echo $_menu->RowAttributes() ?>>
<?php

// Render list options (body, left)
$p_menu_list->ListOptions->Render("body", "left", $p_menu_list->RowCnt);
?>
	<?php if ($_menu->men_id->Visible) { // men_id ?>
		<td<?php echo $_menu->men_id->CellAttributes() ?>><span id="el<?php echo $p_menu_list->RowCnt ?>__menu_men_id" class="_menu_men_id">
<span<?php echo $_menu->men_id->ViewAttributes() ?>>
<?php echo $_menu->men_id->ListViewValue() ?></span>
</span><a id="<?php echo $p_menu_list->PageObjName . "_row_" . $p_menu_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($_menu->men_nombre->Visible) { // men_nombre ?>
		<td<?php echo $_menu->men_nombre->CellAttributes() ?>><span id="el<?php echo $p_menu_list->RowCnt ?>__menu_men_nombre" class="_menu_men_nombre">
<span<?php echo $_menu->men_nombre->ViewAttributes() ?>>
<?php echo $_menu->men_nombre->ListViewValue() ?></span>
</span><a id="<?php echo $p_menu_list->PageObjName . "_row_" . $p_menu_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($_menu->men_link->Visible) { // men_link ?>
		<td<?php echo $_menu->men_link->CellAttributes() ?>><span id="el<?php echo $p_menu_list->RowCnt ?>__menu_men_link" class="_menu_men_link">
<span<?php echo $_menu->men_link->ViewAttributes() ?>>
<?php echo $_menu->men_link->ListViewValue() ?></span>
</span><a id="<?php echo $p_menu_list->PageObjName . "_row_" . $p_menu_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($_menu->men_orden->Visible) { // men_orden ?>
		<td<?php echo $_menu->men_orden->CellAttributes() ?>><span id="el<?php echo $p_menu_list->RowCnt ?>__menu_men_orden" class="_menu_men_orden">
<span<?php echo $_menu->men_orden->ViewAttributes() ?>>
<?php echo $_menu->men_orden->ListViewValue() ?></span>
</span><a id="<?php echo $p_menu_list->PageObjName . "_row_" . $p_menu_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$p_menu_list->ListOptions->Render("body", "right", $p_menu_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($_menu->CurrentAction <> "gridadd")
		$p_menu_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($_menu->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($p_menu_list->Recordset)
	$p_menu_list->Recordset->Close();
?>
<?php if ($p_menu_list->TotalRecs > 0) { ?>
<div class="ewGridLowerPanel">
<?php if ($_menu->CurrentAction <> "gridadd" && $_menu->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($p_menu_list->Pager)) $p_menu_list->Pager = new cPrevNextPager($p_menu_list->StartRec, $p_menu_list->DisplayRecs, $p_menu_list->TotalRecs) ?>
<?php if ($p_menu_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($p_menu_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($p_menu_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $p_menu_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($p_menu_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($p_menu_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $p_menu_list->PageUrl() ?>start=<?php echo $p_menu_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $p_menu_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $p_menu_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $p_menu_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $p_menu_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($p_menu_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
	</td>
</tr></table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($p_menu_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $p_menu_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($p_menu_list->TotalRecs > 0) { ?>
<a class="ewGridLink" href="" onclick="ew_SubmitSelected(document.f_menulist, '<?php echo $p_menu_list->MultiDeleteUrl ?>');return false;"><?php echo $Language->Phrase("DeleteSelectedLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</span>
</div>
<?php } ?>
</td></tr></table>
<script type="text/javascript">
f_menulistsrch.Init();
f_menulist.Init();
</script>
<?php
$p_menu_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$p_menu_list->Page_Terminate();
?>
