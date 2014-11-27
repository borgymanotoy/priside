<?php


$languages = is_null($_GET['lang']) ? "sv" : $_GET['lang'];	

$translation_array_sv = array(
					"my_projects_main_project_overview"=>"Projektöversikt",
					"my_projects_main_documents_files" =>"Dokument och filer",

				);

$translation_array_en = array(
					"my_projects_main_project_overview"=>"Project Overview",
					"my_projects_main_documents_files" =>"Documents and Files",
				);
				
if($languages == "en")
	$translation = $translation_array_sv;
else
	$translation = $translation_array_sv;

if( ! is_array( $trans ) )
  $trans = array();
  
if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}else{
	$trans = array_merge( $trans, $translation_array_sv );

}


return;

?>