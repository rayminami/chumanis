<?php
//Include Common Files @1-733B8520
define("RelativePath", "..");
define("PathToCurrentPage", "/admin.menu/");
define("FileName", "p_role_module.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-ED59B080
include_once(RelativePath . "/admin.menu/../Designs/d01/MasterPage.php");
//End Master Page implementation

class clsGridroleGrid { //roleGrid class @6-1EED1807

//Variables @6-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @6-6E005CCF
    function clsGridroleGrid($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "roleGrid";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid roleGrid";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsroleGridDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->code = new clsControl(ccsLabel, "code", "code", ccsText, "", CCGetRequestParam("code", ccsGet, NULL), $this);
        $this->valid_from = new clsControl(ccsLabel, "valid_from", "valid_from", ccsDate, array("dd", "-", "mmm", "-", "yyyy"), CCGetRequestParam("valid_from", ccsGet, NULL), $this);
        $this->editlink = new clsControl(ccsImageLink, "editlink", "editlink", ccsText, "", CCGetRequestParam("editlink", ccsGet, NULL), $this);
        $this->editlink->Page = "p_role_module_form.php";
        $this->role_name = new clsControl(ccsLabel, "role_name", "role_name", ccsText, "", CCGetRequestParam("role_name", ccsGet, NULL), $this);
        $this->valid_to = new clsControl(ccsLabel, "valid_to", "valid_to", ccsDate, array("dd", "-", "mmm", "-", "yyyy"), CCGetRequestParam("valid_to", ccsGet, NULL), $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpMoving, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->addlink = new clsControl(ccsImageLink, "addlink", "addlink", ccsText, "", CCGetRequestParam("addlink", ccsGet, NULL), $this);
        $this->addlink->Parameters = CCGetQueryString("QueryString", array("p_role_module_id", "FormFilter", "ccsForm"));
        $this->addlink->Page = "p_role_module_form.php";
        $this->p_module_id = new clsControl(ccsHidden, "p_module_id", "p_module_id", ccsFloat, "", CCGetRequestParam("p_module_id", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @6-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @6-8AE47B34
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlp_module_id"] = CCGetFromGet("p_module_id", NULL);
        $this->DataSource->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["code"] = $this->code->Visible;
            $this->ControlsVisible["valid_from"] = $this->valid_from->Visible;
            $this->ControlsVisible["editlink"] = $this->editlink->Visible;
            $this->ControlsVisible["role_name"] = $this->role_name->Visible;
            $this->ControlsVisible["valid_to"] = $this->valid_to->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->code->SetValue($this->DataSource->code->GetValue());
                $this->valid_from->SetValue($this->DataSource->valid_from->GetValue());
                $this->editlink->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->editlink->Parameters = CCAddParam($this->editlink->Parameters, "p_role_module_id", $this->DataSource->f("p_role_module_id"));
                $this->role_name->SetValue($this->DataSource->role_name->GetValue());
                $this->valid_to->SetValue($this->DataSource->valid_to->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->code->Show();
                $this->valid_from->Show();
                $this->editlink->Show();
                $this->role_name->Show();
                $this->valid_to->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if (($this->Navigator->TotalPages <= 1 && $this->Navigator->PageNumber == 1) || $this->Navigator->PageSize == "") {
            $this->Navigator->Visible = false;
        }
        $this->p_module_id->SetValue($this->DataSource->p_module_id->GetValue());
        $this->Navigator->Show();
        $this->addlink->Show();
        $this->p_module_id->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @6-3F1D09AB
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->code->Errors->ToString());
        $errors = ComposeStrings($errors, $this->valid_from->Errors->ToString());
        $errors = ComposeStrings($errors, $this->editlink->Errors->ToString());
        $errors = ComposeStrings($errors, $this->role_name->Errors->ToString());
        $errors = ComposeStrings($errors, $this->valid_to->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End roleGrid Class @6-FCB6E20C

class clsroleGridDataSource extends clsDBhrcon {  //roleGridDataSource Class @6-64CA47F9

//DataSource Variables @6-9F4B3D6E
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $code;
    public $valid_from;
    public $role_name;
    public $valid_to;
    public $p_module_id;
//End DataSource Variables

//DataSourceClass_Initialize Event @6-F2633CA1
    function clsroleGridDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid roleGrid";
        $this->Initialize();
        $this->code = new clsField("code", ccsText, "");
        
        $this->valid_from = new clsField("valid_from", ccsDate, $this->DateFormat);
        
        $this->role_name = new clsField("role_name", ccsText, "");
        
        $this->valid_to = new clsField("valid_to", ccsDate, $this->DateFormat);
        
        $this->p_module_id = new clsField("p_module_id", ccsFloat, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @6-8757247C
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "p_role_module.p_role_module_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @6-8FCB3F06
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlp_module_id", ccsFloat, "", "", $this->Parameters["urlp_module_id"], 0, false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
    }
//End Prepare Method

//Open Method @6-7E2AAE68
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) FROM (SELECT p_role_module.*, p_role.code AS role_name, p_module.code AS module_name \n" .
        "FROM (p_role_module INNER JOIN p_role ON\n" .
        "p_role_module.p_role_id = p_role.p_role_id) INNER JOIN p_module ON\n" .
        "p_role_module.p_module_id = p_module.p_module_id\n" .
        "WHERE p_role_module.p_module_id = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . " \n" .
        "and (p_role.code like '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' or p_module.code like '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%')) cnt";
        $this->SQL = "SELECT p_role_module.*, p_role.code AS role_name, p_module.code AS module_name \n" .
        "FROM (p_role_module INNER JOIN p_role ON\n" .
        "p_role_module.p_role_id = p_role.p_role_id) INNER JOIN p_module ON\n" .
        "p_role_module.p_module_id = p_module.p_module_id\n" .
        "WHERE p_role_module.p_module_id = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . " \n" .
        "and (p_role.code like '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' or p_module.code like '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%') {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @6-2C9FD7C7
    function SetValues()
    {
        $this->code->SetDBValue($this->f("module_name"));
        $this->valid_from->SetDBValue(trim($this->f("valid_from")));
        $this->role_name->SetDBValue($this->f("role_name"));
        $this->valid_to->SetDBValue(trim($this->f("valid_to")));
        $this->p_module_id->SetDBValue(trim($this->f("p_module_id")));
    }
//End SetValues Method

} //End roleGridDataSource Class @6-FCB6E20C

class clsRecordp_module1 { //p_module1 Class @33-78189409

//Variables @33-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @33-1AA19EBF
    function clsRecordp_module1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record p_module1/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "p_module1";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "Keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method, NULL), $this);
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->idmenulabel = new clsControl(ccsTextBox, "idmenulabel", "Keyword", ccsText, "", CCGetRequestParam("idmenulabel", $Method, NULL), $this);
            $this->p_module_id = new clsControl(ccsTextBox, "p_module_id", "Keyword", ccsInteger, "", CCGetRequestParam("p_module_id", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @33-4A6F47BA
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $Validation = ($this->idmenulabel->Validate() && $Validation);
        $Validation = ($this->p_module_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_keyword->Errors->Count() == 0);
        $Validation =  $Validation && ($this->idmenulabel->Errors->Count() == 0);
        $Validation =  $Validation && ($this->p_module_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @33-9E0BD9E0
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->idmenulabel->Errors->Count());
        $errors = ($errors || $this->p_module_id->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @33-DD94EE4C
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = $FileName;
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = $FileName . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @33-08B10DE2
    function Show()
    {
        global $CCSUseAmp;
        $Tpl = CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_keyword->Errors->ToString());
            $Error = ComposeStrings($Error, $this->idmenulabel->Errors->ToString());
            $Error = ComposeStrings($Error, $this->p_module_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->s_keyword->Show();
        $this->Button_DoSearch->Show();
        $this->idmenulabel->Show();
        $this->p_module_id->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End p_module1 Class @33-FCB6E20C

//Include Page implementation @37-675D7754
include_once(RelativePath . "/admin/NewPage1.php");
//End Include Page implementation













//Initialize Page @1-9D04B826
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";
$PathToCurrentMasterPage = "";
$TemplatePathValue = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";
$MasterPage = null;
$TemplateSource = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "p_role_module.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "../";
$PathToRootOpt = "../";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/external/jquery.cookie.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.tabs.js|js/jquery/tab/ccs-tabs.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-C7701334
include_once("./p_role_module_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-3866BC5D
$DBhrcon = new clsDBhrcon();
$MainPage->Connections["hrcon"] = & $DBhrcon;
$Attributes = new clsAttributes("page:");
$Attributes->SetValue("pathToRoot", $PathToRoot);
$MainPage->Attributes = & $Attributes;

// Controls
$MasterPage = new clsMasterPage("/admin.menu/../Designs/d01/", "MasterPage", $MainPage);
$MasterPage->Attributes = $Attributes;
$MasterPage->Initialize();
$Head = new clsPanel("Head", $MainPage);
$Head->PlaceholderName = "Head";
$Content = new clsPanel("Content", $MainPage);
$Content->GenerateDiv = true;
$Content->PanelId = "Content";
$Content->PlaceholderName = "Content";
$Panel1 = new clsPanel("Panel1", $MainPage);
$roleGrid = new clsGridroleGrid("", $MainPage);
$Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet, NULL), $MainPage);
$Link1->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
$Link1->Page = "p_module.php";
$p_module1 = new clsRecordp_module1("", $MainPage);
$NewPage1 = new clsNewPage1("../admin/", "NewPage1", $MainPage);
$NewPage1->Initialize();
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->Panel1 = & $Panel1;
$MainPage->roleGrid = & $roleGrid;
$MainPage->Link1 = & $Link1;
$MainPage->p_module1 = & $p_module1;
$MainPage->NewPage1 = & $NewPage1;
$Content->AddComponent("NewPage1", $NewPage1);
$Content->AddComponent("Panel1", $Panel1);
$Panel1->AddComponent("roleGrid", $roleGrid);
$Panel1->AddComponent("p_module1", $p_module1);
$Panel1->AddComponent("Link1", $Link1);
$roleGrid->Initialize();
$ScriptIncludes = "";
$SList = explode("|", $Scripts);
foreach ($SList as $Script) {
    if ($Script != "") $ScriptIncludes = $ScriptIncludes . "<script src=\"" . $PathToRoot . $Script . "\" type=\"text/javascript\"></script>\n";
}
$Attributes->SetValue("scriptIncludes", $ScriptIncludes);

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-A7427295
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
if (strlen($TemplateSource)) {
    $Tpl->LoadTemplateFromStr($TemplateSource, $BlockToParse, "CP1252");
} else {
    $Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
}
$Tpl->SetVar("CCS_PathToRoot", $PathToRoot);
$Tpl->SetVar("CCS_PathToMasterPage", RelativePath . $PathToCurrentMasterPage);
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "../");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-F8C0A9F8
$MasterPage->Operations();
$NewPage1->Operations();
$p_module1->Operation();
//End Execute Components

//Go to destination page @1-06F9471A
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBhrcon->close();
    header("Location: " . $Redirect);
    unset($roleGrid);
    unset($p_module1);
    $NewPage1->Class_Terminate();
    unset($NewPage1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-1919319E
$Head->Show();
$Content->Show();
$MasterPage->Tpl->SetVar("Head", $Tpl->GetVar("Panel Head"));
$MasterPage->Tpl->SetVar("Content", $Tpl->GetVar("Panel Content"));
$MasterPage->Show();
if (!isset($main_block)) $main_block = $MasterPage->HTML;
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-61B82C50
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBhrcon->close();
unset($MasterPage);
unset($roleGrid);
unset($p_module1);
$NewPage1->Class_Terminate();
unset($NewPage1);
unset($Tpl);
//End Unload Page


?>
