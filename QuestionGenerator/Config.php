<?php

/*****************************************************************************/
/*                                                                           */
/*  CConfig class interface                                                  */
/*                                                                           */
/*  (C) 2012 Sinca Stefan (andy@acrux.biz) 2012-02-03                        */
/*                                                                           */
/*****************************************************************************/
define("CONFIG_INCLUDED", "1");

include_once 'File.php';
include_once 'ConfigQuestion.php';
include_once 'ConfigQuestionGroup.php';
include_once 'ConfigLanguage.php';

//////////////////////////////////////////////////////////////////////////////PB
//
// [CLASS NAME]:   CConfig
//
// [DESCRIPTION]:  CConfig class interface
//
// [FUNCTIONS]:    
//
// [CREATED BY]:   Sinca Stefan (andy@acrux.biz) 2012-02-03
//
// [MODIFIED]:     - [programmer (email) date]
//                   [short description]
//
//////////////////////////////////////////////////////////////////////////////PE
class CConfig
{
   var $config;

   var $strERR;       // last error string

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: CConfig
   //
   // [DESCRIPTION]:   Default class constructor. Initialization goes here.
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  none
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-03
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function CConfig()
   {

      $this->config  = null;
   }


   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetSettings($resetCache, $system='', $wlName='')
   //
   // [DESCRIPTION]:   read the settings for the partner and system
   //
   // [PARAMETERS]:    $resetCache, $system='', $wlName=''
   //
   // [RETURN VALUE]:  settings for this WL
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-03
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function GetSettings($resetCache, $system='', $wlName='')
   {
      // Get the partner and system from SESSION
      if($system == '')
         $system = strtolower($this->session['_QZ_QUOTE_DETAILS_']['system']);
      if($wlName == '')
         $wlName = $this->session['_QZ_QUOTE_DETAILS_']['wlUserName'];
      
      // Check if config file is cached and no reset
      if(!$resetCache)
         if($this->GetCachedConfigFile($system,$wlName))
            return $this->config;
      
      if(!$commonConfig = $this->ReadCommonConfigFile($system))
      {
         $this->strERR .= "\nUnable to read the commoon config file.";
         return false;
      }

      //
      // Read the partner config file if that exists
      //

      if(!$partnerConfig = $this->ReadPartnerConfigFile($system, $wlName))
      {
         $this->strERR .= "\nUnable to read the partner config files.";
         return false;
      }

      $settings = $this->ReplaceRecursive($commonConfig, $partnerConfig, true);

      $this->SetSettings($settings);

      //
      // Read the partner language files
      //

      $languageFilesArray = array(
            'errors',
            'help',
            'labels',
            'titles',
         );

      $languageObj = new stdClass();

      $languageObj->language = $settings->language->language;

      foreach($languageFilesArray as $fileId=>$fileName)
      {
         if(!$languageObj->$fileName = $this->GetLanguageConfig($system,$settings->language->language,$fileName,$wlName))
         {
            $languageObj->$fileName = new stdClass();
         }
      }

      $languageObj = $this->ReplaceRecursive($settings->language, $languageObj, true);

      $settings->language = $languageObj;
      $this->SetSettings($settings);

      // Try to write the StepElements.php file just for the standard
      if($settings->name == 'standard')
         if(!$this->CreateStepElementsFile())
         {
            // do nothing
         }
      
      // try to write the config file to the cache
      if($this->CacheConfigFile())
      {
         // do nothing
      }

      return $this->config;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetCachedConfigFile($system,$wlName)
   //
   // [DESCRIPTION]:   read the settings from the cached config file
   //
   // [PARAMETERS]:    $system,$wlName
   //
   // [RETURN VALUE]:  true | false
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-05-15
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function GetCachedConfigFile($system,$wlName)
   {
      // Path to cached config file
      $configFilePath   = ROOT_PATH."system/config/partner/".$wlName."/".$system."/cache/".$wlName.".".$system.".conf";
      $standardFilePath = ROOT_PATH."system/config/partner/standard/".$system."/cache/standard.".$system.".conf";

      $fileObj      = new CFile();

      // Try to open config file with absolute path
      if(!$fileObj->Exists($configFilePath))
      {
         // Check if we have the partner dir (personalized wl)
         $configDirPath = ROOT_PATH."system/config/partner/".$wlName."/";
         if($fileObj->IsDir($configDirPath))
         {
            // It is a personalized wl, but it does not have a cached config file yet
            return false;
         }
         
         // It is a standard wl so try to read the standard cached config
         if($fileObj->Exists($standardFilePath))
         {
            if(!$fileObj->Open($standardFilePath))
            {
               $this->strERR .= $fileObj->GetError();
               return false;
            }
         }
         else
         {
            return false;
         }
      }
      else
      {
         if(!$fileObj->Open($configFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      }

      // Read the json from file
      $jsonFileContents = $fileObj->Read($fileObj->GetSize());
      $jsonFileContents = str_replace("\n", "", $jsonFileContents);
      $jsonFileContents = str_replace("\t", "", $jsonFileContents);
      $fileObj->Close();

      // Create the commonSettings object from json
      $settings = json_decode($jsonFileContents);

      $this->SetSettings($settings);
      return true;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: CacheConfigFile()
   //
   // [DESCRIPTION]:   chache the config file
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  true | false
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-05-15
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function CacheConfigFile()
   {
      $wlName = $this->config->name;
      $system = strtolower($this->session['_QZ_QUOTE_DETAILS_']['system']);
      
      // Path to cached config file
      $configFileDir     = ROOT_PATH."system/config/partner/".$wlName."/".$system."/cache/";
      $configFilePath     = $wlName.".".$system.".conf";

      $settingstxt = json_encode($this->config);

      $fileObj      = new CFile();
      
      // Check if the cache dir exists
      if(!$fileObj->IsDir($configFileDir))
      {
         // Try to create the cache dir
         if(!$fileObj->MkDir($configFileDir,0775,true))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      }
      // If the cache dir exists clar all cached files(remove all it's content)
      else
      {
         if(!$fileObj->EmptyDir($configFileDir))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      }

      // Try to create the config file if it does not exist
      if(!$fileObj->Exists($configFileDir.$configFilePath))
         if(!$fileObj->Create($configFileDir.$configFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      
      // Try to open the config file
      if(!$fileObj->Open($configFileDir.$configFilePath,"w+"))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }
      
      // Write to the file
      if(!$fileObj->Write($settingstxt))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }
      
      $fileObj->Close();
      return true;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: CacheSystemConfigFile()
   //
   // [DESCRIPTION]:   chache the system config file
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  true | false
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-06-07
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function CacheSystemConfigFile()
   {
      $system = strtolower($this->session['_QZ_QUOTE_DETAILS_']['system']);
      
      // Path to cached config file
      $configFileDir     = ROOT_PATH."system/config/common/".$system."/cache/";
      $configFilePath     = $system.".conf";

      $settingstxt = json_encode($this->config);

      $fileObj      = new CFile();
      
      // Check if the cache dir exists
      if(!$fileObj->IsDir($configFileDir))
      {
         // Try to create the cache dir
         if(!$fileObj->MkDir($configFileDir,0775,true))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      }

      // Try to create the config file if it does not exist
      if(!$fileObj->Exists($configFileDir.$configFilePath))
         if(!$fileObj->Create($configFileDir.$configFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      
      // Try to open the config file
      if(!$fileObj->Open($configFileDir.$configFilePath,"w+"))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }
      
      // Write to the file
      if(!$fileObj->Write($settingstxt))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }
      
      $fileObj->Close();
      return true;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: CreateStepElementsFile()
   //
   // [DESCRIPTION]:   Create the StepElements.php file
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  true | false
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-05-22
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function CreateStepElementsFile()
   {
      //$wlName = $this->config->name;
      $system = strtolower($this->session['_QZ_QUOTE_DETAILS_']['system']);
      
      // Path to StepElements file
      $stepsDir         = ROOT_PATH."system/core/".$system."/steps/";
      $stepElementsFile = "StepElements.php";

      $phpStart = '<?php
';
      $phpEnd = '?>';
      
      $stepElementsData = '$GLOBALS["_STEPS"] = array(
';
      // Get all questions
      foreach($this->GetAllQuestions() as $qstObj)
         foreach($qstObj->main->input as $inputObj)
         {
            if(strtolower($inputObj->type) != 'select')
               continue;
            
            if($inputObj->add_to_step_elements != 'Y')
               continue;
            
            $stepElementsData .= '   "'.$inputObj->name.'"  => array(
';
            
            if(is_object($inputObj->value))
            {
               foreach($inputObj->value as $valuesObj)
                  $stepElementsData .= '      "'.$valuesObj->value.'" => "'.$valuesObj->dispalyed_value.'",
';
                  $stepElementsData .= '   ),

';
            }
            else
            {
               if(preg_match("/^(.*)\(()\);*$/", $inputObj->value, $matches))
               {
                  $tmpPrepArray = $this->RunConfigFunction($inputObj->function, $matches[2]);

                  foreach($tmpPrepArray['select_options'] as $valuesObj)
                     $stepElementsData .= '      "'.$valuesObj->value.'" => "'.$valuesObj->dispalyed_value.'",
';
                  
                  $stepElementsData .= '   ),

';
               }
               elseif(preg_match("/^(.*)\((.*)\);*$/", $inputObj->value, $matches))
               {
                  if(is_object($inputObj->step_elements))
                     foreach($inputObj->step_elements as $valuesObj)
                        $stepElementsData .= '      "'.$valuesObj->value.'" => "'.$valuesObj->dispalyed_value.'",
';
                  $stepElementsData .= '   ),

';
               }
            }
         }

      $stepElementsData .= '
);

$GLOBALS["_VEHICLE"] = $GLOBALS["_PROPOSER"] = $GLOBALS["_ADDITIONAL"] = $GLOBALS["_CLAIMS"] = $GLOBALS["_CONVICTIONS"] = $GLOBALS["_COVER"] = $GLOBALS["_CONTENTS_COVER"] = $GLOBALS["_HOME_EXTRA"] = $GLOBALS["_CONTENTS_CLAIMS"] = $GLOBALS["_BUILD_CLAIMS"] = $GLOBALS["_PROPOSER_NEW"] = $GLOBALS["_JOINT"] = $GLOBALS["_JOINT_NEW"] = $GLOBALS["_BUILDINGS_COVER"] = $GLOBALS["_HOME"] = $GLOBALS["_HOME_OCCUPANCY"] = $GLOBALS["_HOME_SECURITY"] = $GLOBALS["_MEDICAL_CONDITIONS"] = $GLOBALS["_STEPS"];

function GetElementByCode($element,$elementCode)
{
   global $_ADDITIONAL;
   
   $subElement = $_ADDITIONAL[$element];
   
   foreach($subElement as $code => $name)
   {
      if(strval($code) === strval($elementCode))
      {
         $result  = $name;
         break;
      }
   }

   return $result;
}

function GetAllClaimElements($element,$limitNameChars=0)
{
   if(! $limitNameChars)
      return $element;

   $newArray = array();
   foreach($element as $code => $name)
   {
      if(strlen($name) > $limitNameChars)
         $newArray[$code] = substr($name, 0, $limitNameChars)."...";
      else
         $newArray[$code] = $name;
   }

   return $newArray;

}

function GetClaimElementByCode($element,$elementCode)
{
   global $_CLAIMS;
   
   $subElement = $_CLAIMS[$element];

   foreach($subElement as $code => $name)
   {
      if(strval($code) === strval($elementCode))
      {
         $result  = $name;
         break;
      }
   }

   return $result;
}

function GetAllConvictionElements($element,$limitNameChars=0)
{
   if(! $limitNameChars)
      return $element;

   $newArray = array();
   foreach($element as $code => $name)
   {
      if(strlen($name) > $limitNameChars)
         $newArray[$code] = substr($name, 0, $limitNameChars)."...";
      else
         $newArray[$code] = $name;
   }

   return $newArray;

}


function GetConvictionElementByCode($element,$elementCode)
{
   global $_CONVICTIONS;
   
   $subElement = $_CONVICTIONS[$element];

   foreach($subElement as $code => $name)
   {
      if(strval($code) === strval($elementCode))
      {
         $result  = $name;
         break;
      }
   }

   return $result;
}

// "return true;" MUST BE AT THE END OF THIS TO BE ABLE TO TEST THIS FOR PHP ERRORS
return true;
';

      // Check if php code inside the file is OK 
      if(!eval($stepElementsData))
         return false;
      
      $stepElementsData = $phpStart.$stepElementsData.$phpEnd;
      
      $fileObj      = new CFile();
      
      // Check if the steps/ dir exists
      if(!$fileObj->IsDir($stepsDir))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }

      // Try to create the Stepelements.php file if it does not exist
      if(!$fileObj->Exists($stepsDir.$stepElementsFile))
         if(!$fileObj->Create($stepsDir.$stepElementsFile))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      
      // Try to open the file
      if(!$fileObj->Open($stepsDir.$stepElementsFile,"w+"))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }
      
      // Write the file
      if(!$fileObj->Write($stepElementsData))
      {
         $this->strERR .= $fileObj->GetError();
         return false;
      }
      
      $fileObj->Close();
      return true;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: ReadCommonConfigFile($system)
   //
   // [DESCRIPTION]:   read the settings from the config file
   //
   // [PARAMETERS]:    $system
   //
   // [RETURN VALUE]:  settings object
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-08
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function ReadCommonConfigFile($system)
   {
      // Path to common config file
      $configFilePath   = ROOT_PATH."system/config/common/".$system."/config/".$system.".conf";

      $fileObj      = new CFile();

      // Try to open config file with absolute path
      if($fileObj->Exists($configFilePath))
      {
         if(!$fileObj->Open($configFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }
      }

      // Read the json from file
      $jsonFileContents = $fileObj->Read($fileObj->GetSize());
      $jsonFileContents = str_replace("\n", "", $jsonFileContents);
      $jsonFileContents = str_replace("\t", "", $jsonFileContents);
      $fileObj->Close();

      // Create the commonSettings object from json
      $settings = json_decode($jsonFileContents);

      $this->SetSettings($settings);

      //
      // Read the question groups config files
      //

      if(!$question_groups = $this->GetQuestionGroupsConfig())
      {
         $this->strERR .= "\nUnable to read the configs for the question groups.";
         return false;
      }
      
      $settings->question_groups = $question_groups;
      $this->SetSettings($settings);

      //
      // Read the questions config files
      //

      if(!$questions = $this->GetQuestionsConfig())
      {
         $this->strERR .= "\nUnable to read the configs for the questions.";
         return false;
      }
      
      // Set the defaul questions in the settings object
      $settings->questions = $this->ReplaceRecursive($questions,$settings->questions,true);
      
      $this->SetSettings($settings);

      //
      // Read the common language files
      //

      $languageFilesArray = array(
            'errors',
            'help',
            'labels',
            'titles',
         );

      $languageObj = new stdClass();
      
      $languageObj->language = $settings->language->language;

      foreach($languageFilesArray as $fileId=>$fileName)
      {
         if(!$languageObj->$fileName = $this->GetLanguageConfig($system,$languageObj->language,$fileName,'common'))
         {
            $languageObj->$fileName = new stdClass();
         }
      }

      $settings->language = $languageObj;
      $this->SetSettings($settings);

      // try to write the config file to the cache
      //if($this->CacheSystemConfigFile())
      //{
         // do nothing
      //}
      
      return $settings;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: ReadPartnerConfigFile($system, $wlName="standard")
   //
   // [DESCRIPTION]:   read the settings from the config file
   //
   // [PARAMETERS]:    $system, $wlName
   //
   // [RETURN VALUE]:  settings object
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-08
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function ReadPartnerConfigFile($system, $wlName="standard")
   {
      $partnerSystemSettings = null;
      $partnerCommonSettings = null;

      // Path to partner's system config file
      $partnerSystemFilePath = ROOT_PATH."system/config/partner/".$wlName."/".$system."/config/".$wlName.".".$system.".conf";

      $fileObj      = new CFile();

      if($fileObj->Exists($partnerSystemFilePath))
      {
         if(!$fileObj->Open($partnerSystemFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }

         // Read the json from file
         $jsonFileContents = $fileObj->Read($fileObj->GetSize());
         $jsonFileContents = str_replace("\n", "", $jsonFileContents);
         $jsonFileContents = str_replace("\t", "", $jsonFileContents);
         $fileObj->Close();

         // Create the partnerSystemSettings object from json
         $partnerSystemSettings = json_decode($jsonFileContents);
      }

      // Path to partner's common config file
      $partnerCommonFilePath = ROOT_PATH."system/config/partner/".$wlName."/config/".$wlName.".conf";

      if($fileObj->Exists($partnerCommonFilePath))
      {
         if(!$fileObj->Open($partnerCommonFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }

         // Read the json from file
         $jsonFileContents = $fileObj->Read($fileObj->GetSize());
         $jsonFileContents = str_replace("\n", "", $jsonFileContents);
         $jsonFileContents = str_replace("\t", "", $jsonFileContents);
         $fileObj->Close();

         // Create the partnerCommonSettings object from json
         $partnerCommonSettings = json_decode($jsonFileContents);
      }

      // IF we have a personalized WL
      if($partnerCommonSettings || $partnerSystemSettings)
      {
         if(!$partnerCommonSettings)
            $partnerCommonSettings = new stdClass();

         if(!$partnerSystemSettings)
            $partnerSystemSettings = new stdClass();

         // Combine the 2 settings
         $partnerSettings = $this->ReplaceRecursive($partnerCommonSettings, $partnerSystemSettings);

         // Check if the WL has a parent pattern.
         // 'standdard' WL does not have a parent
         $parent = "";
         if($wlName != 'standard')
            $parent = trim($partnerSettings->parent);
            
         if ($parent != '')
         {
            $parentSettings = $this->ReadPartnerConfigFile($system,$parent);

            return $this->ReplaceRecursive($parentSettings, $partnerSettings);
         }

         return $partnerSettings;
      }
      else // Load the setings for 'standard' WL
      {
         return $this->ReadPartnerConfigFile($system);
      }
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetAllCategories
   //
   // [DESCRIPTION]:   Get the categories for the white-label
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  categories
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-09
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetAllCategories()
   {
      return $this->config->categories;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetAllQuestions
   //
   // [DESCRIPTION]:   Get the questions for the white-label
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  questions
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-16
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetAllQuestions()
   {
      return $this->config->questions;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetAllQuestionGroups
   //
   // [DESCRIPTION]:   Get the question groups for the white-label
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  greoups of questions
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-17
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetAllQuestionGroups()
   {
      return $this->config->question_groups;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetCategory
   //
   // [DESCRIPTION]:   Get one category for the white-label
   //
   // [PARAMETERS]:    $category - the name of the category
   //
   // [RETURN VALUE]:  category
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-09
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetCategory($category)
   {
      $category = strtoupper($category);
      return $this->config->categories->$category;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetAllSectionsFromCategory
   //
   // [DESCRIPTION]:   Get all sections from the category for the white-label
   //
   // [PARAMETERS]:    $category - the name of the category
   //
   // [RETURN VALUE]:  sections
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-09
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetAllSectionsFromCategory($category)
   {
      $category = strtoupper($category);
      return $this->config->categories->$category->sections;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetNumberOfSectionsInCategory
   //
   // [DESCRIPTION]:   Get the number of sections from the category for the white-label
   //
   // [PARAMETERS]:    $category - the name of the category
   //
   // [RETURN VALUE]:  $number - the count of sections in the category
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-09
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetNumberOfSectionsInCategory($category)
   {
      $category = strtoupper($category);
      $count = 0;
      foreach($this->config->categories->$category->sections as $section)
         $count++;
         
      return $count;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetQuestionGroupsConfig
   //
   // [DESCRIPTION]:   Get the question groups config from files
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  none
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-17
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetQuestionGroupsConfig()
   {
      $questionGroupConfigObj = new CConfigQuestionGroup();
      
      $questionGroupObj = new stdClass();

      foreach($this->GetAllQuestionGroups() as $questionGroup=>$questionGroupContent)
      {
         if(!$questionGroupObj->$questionGroup = $this->ReplaceRecursive($questionGroupConfigObj->ReadQuestionGroupConfigFiles($questionGroup), $questionGroupContent))
         {
            $this->strERR .= $questionGroupConfigObj->GetError();
            return false;
         }
      }

      return $questionGroupObj;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetQuestionsConfig
   //
   // [DESCRIPTION]:   Get the questions config from files
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  none
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-10
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetQuestionsConfig()
   {
      $questionConfigObj = new CConfigQuestion();
      
      $questionsObj = new stdClass();

      foreach($this->GetAllQuestions() as $question=>$questionContent)
      {
         if(!$questionsObj->$question = $questionConfigObj->ReadQuestionConfigFiles($question))
         {
            $this->strERR .= $questionConfigObj->GetError();
            return false;
         }
      }

      return $questionsObj;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetLanguageConfig($system,$language,$fileName,$wlName="standard")
   //
   // [DESCRIPTION]:   Get the language config from files
   //
   // [PARAMETERS]:    $language
   //
   // [RETURN VALUE]:  none
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-03-26
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetLanguageConfig($system,$language,$fileName,$wlName="standard")
   {
      $systemLangSettings = null;
      $commonLangSettings = null;

      // Path to system language file
      $systemLangFilePath = ROOT_PATH."system/config/partner/".$wlName."/".$system."/languages/".$language."/".$fileName;

      if($wlName == "common")
         $systemLangFilePath = ROOT_PATH."system/config/common/".$system."/languages/".$language."/".$fileName;

      $fileObj      = new CFile();

      if($fileObj->Exists($systemLangFilePath))
      {
         if(!$fileObj->Open($systemLangFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }

         // Read the json from file
         $jsonFileContents = $fileObj->Read($fileObj->GetSize());
         $jsonFileContents = str_replace("\n", "", $jsonFileContents);
         $jsonFileContents = str_replace("\t", "", $jsonFileContents);
         $fileObj->Close();

         // Create the partnerSystemSettings object from json
         $systemLangSettings = json_decode($jsonFileContents);
      }

      // Path to common config file
      $commonLangFilePath = ROOT_PATH."system/config/partner/".$wlName."/languages/".$language."/".$fileName;

      if($wlName == "common")
         $commonLangFilePath = ROOT_PATH."system/config/common/languages/".$language."/".$fileName;

      if($fileObj->Exists($commonLangFilePath))
      {
         if(!$fileObj->Open($commonLangFilePath))
         {
            $this->strERR .= $fileObj->GetError();
            return false;
         }

         // Read the json from file
         $jsonFileContents = $fileObj->Read($fileObj->GetSize());
         $jsonFileContents = str_replace("\n", "", $jsonFileContents);
         $jsonFileContents = str_replace("\t", "", $jsonFileContents);
         $fileObj->Close();

         // Create the partnerCommonSettings object from json
         $commonLangSettings = json_decode($jsonFileContents);
      }

      // IF we have a personalized WL
      if($commonLangSettings || $systemLangSettings)
      {
         if(!$commonLangSettings)
            $commonLangSettings = new stdClass();

         if(!$systemLangSettings)
            $systemLangSettings = new stdClass();

         // Combine the 2 settings
         $langSettings = $this->ReplaceRecursive($commonLangSettings, $systemLangSettings);

         // Check if the WL has a parent pattern.
         // 'standdard' WL does not have a parent
         $parent = "";
         if($wlName != 'standard' && $wlName != 'common')
            $parent = trim($partnerSettings->parent);

         if ($parent != '')
         {
            $parentLangSettings = $this->GetLanguageConfig($system,$language,$fileName,$parent);

            return $this->ReplaceRecursive($parentLangSettings, $langSettings);
         }

         return $langSettings;
      }
      else // Load the setings for 'standard' WL
      {
         if($wlName != 'standard')
            return $this->GetLanguageConfig($system,$language,$fileName);
      }
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: SetSettings
   //
   // [DESCRIPTION]:   Store the settings of the WL
   //
   // [PARAMETERS]:    $settings
   //
   // [RETURN VALUE]:  none
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-08
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function SetSettings($settings)
   {
      $this->config = $settings;
   }
   
   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: SetQuestionSettings
   //
   // [DESCRIPTION]:   Store the settings of the question
   //
   // [PARAMETERS]:    $category, $section, $question, $settings
   //
   // [RETURN VALUE]:  none
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-08
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function SetQuestionSettings($question, $settings)
   {
      $this->config->questions->$question = $settings;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: RunConfigFunction($prepArray, $functionBody, $matches)
   //
   // [DESCRIPTION]:   Executes a function from the config files
   //
   // [PARAMETERS]:    $prepArray, $functionBody, $matches
   //
   // [RETURN VALUE]:  function response array
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-04-23
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function RunConfigFunction($functionBody, $functionParams)
   {
      $params = str_replace('&',"",$functionParams);
      $params = str_replace('$',"",$functionParams);

      $function = create_function($functionParams, $functionBody);

      $paramList = explode(",",$params);
      $paramNr = count($paramList);

      $tmpPrepArray = array();
      switch($paramNr)
      {
         case '1' :
               $tmpPrepArray = $function($$params);
            break;
         case '2' :
               $tmpPrepArray = $function($$paramList[0],$$paramList[1]);
            break;
         case '3' :
               $tmpPrepArray = $function($$paramList[0],$$paramList[1],$$paramList[2]);
            break;
         case '4' :
               $tmpPrepArray = $function($$paramList[0],$$paramList[1],$$paramList[2],$$paramList[3]);
            break;
         case '5' :
               $tmpPrepArray = $function($$paramList[0],$$paramList[1],$$paramList[2],$$paramList[3],$$paramList[4]);
            break;
         case '6' :
               $tmpPrepArray = $function($$paramList[0],$$paramList[1],$$paramList[2],$$paramList[3],$$paramList[4],$$paramList[5]);
            break;
         default:
               $tmpPrepArray = $function();
      }

      return $tmpPrepArray;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: ReplaceRecursive($obj, $obj1, $clearInheritance = false)
   //
   // [DESCRIPTION]:   replace the elements in obj with elements in obj1
   //
   // [PARAMETERS]:    $obj, $obj1, $clearInheritance = false
   //
   // [RETURN VALUE]:  object with values replaced
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-03
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function ReplaceRecursive($obj, $obj1, $clearInheritance = false)
   {
      foreach ($obj1 as $key => $value)
      {
         if(($key == 'sections' || $key == 'questions' || $key == 'value' || $key == 'step_elements' || $key == 'input' || $key == 'js' || $key == 'css' || $key == 'validation' || $key == '1' || $key == '2' || $key == '3' || $key == '4') && $value->inheritance == 'replace')
         {
            // Remove the helper inheritance on the last replacement
            if($clearInheritance)
               unset($value->inheritance);
            
            // create new key in $obj, if it is empty or not an object
            if (!isset($obj->$key) || (isset($obj->$key) && !is_object($obj->$key)))
            {
               $obj->$key = new stdClass;
            }

            // overwrite the value in the base object
            if (is_object($value))
            {
               $intObj = new stdClass;
               foreach($value as $key1=>$val1)
               {
                  $intObj->$key1 = $obj->$key->$key1;
               }
               $obj->$key = $intObj;

               $value = $this->ReplaceRecursive($obj->$key, $value, $clearInheritance);
            }
            
            $obj->$key = $value;
         }
         else
         {
            // create new key in $obj, if it is empty or not an object
            if (!isset($obj->$key) || (isset($obj->$key) && !is_object($obj->$key)))
            {
               $obj->$key = new stdClass;
            }

            // overwrite the value in the base object
            if (is_object($value))
            {
               $value = $this->ReplaceRecursive($obj->$key, $value, $clearInheritance);
            }
            $obj->$key = $value;
         }
      }

      return $obj;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: ArrayToObject($array)
   //
   // [DESCRIPTION]:   transform each array into object
   //
   // [PARAMETERS]:    $array
   //
   // [RETURN VALUE]:  $object
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-03
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   //////////////////////////////////////////////////////////////////////////////FE
   function ArrayToObject($array)
   {
		if (is_array($array))
			return (object) array_map(__FUNCTION__, $array);
		else
			return $array;
   }

   //////////////////////////////////////////////////////////////////////////////FB
   //
   // [FUNCTION NAME]: GetError
   //
   // [DESCRIPTION]:   Retrieve the last error message
   //
   // [PARAMETERS]:    none
   //
   // [RETURN VALUE]:  Error string if there is any, empty string otherwise
   //
   // [CREATED BY]:    Sinca Stefan (andy@acrux.biz) 2012-02-08
   //
   // [MODIFIED]:      - [programmer (email) date]
   //                    [short description]
   /////////////////////////////////////////////////////////////////////////////FE
   function GetError()
   {
      return $this->strERR;
   }

} // end CConfig
