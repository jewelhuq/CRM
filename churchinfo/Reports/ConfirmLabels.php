<?php
/*******************************************************************************
*
*  filename    : Reports/ConfimLabels.php
*  last change : 2003-08-30
*  description : Creates a PDF with all the mailing labels for the confirm data letter
*
*  InfoCentral is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
******************************************************************************/

require "../Include/Config.php";
require "../Include/Functions.php";
require "../Include/ReportFunctions.php";
require "../Include/ReportConfig.php";
require "../Include/class_fpdf_labels.php";

class PDF_ConfirmLabels extends PDF_Label {

	// Constructor
	function PDF_ConfirmLabels($sLabelFormat) {
   	parent::PDF_Label ($sLabelFormat);
      $this->Open();
	}
}

$sLabelFormat = FilterInput($_GET["LabelFormat"]);

$pdf = new PDF_ConfirmLabels($sLabelFormat);

// Get all the families
$sSQL = "SELECT * FROM family_fam WHERE 1 ORDER BY fam_Name";
$rsFamilies = RunQuery($sSQL);

// Loop through families
while ($aFam = mysql_fetch_array($rsFamilies)) {
	extract ($aFam);

   $labelStr = $pdf->MakeSalutation ($fam_ID);
	if ($fam_Address1 != "") {
      $labelStr .= "\n" . $fam_Address1;
	}
	if ($fam_Address2 != "") {
      $labelStr .= "\n" . $fam_Address2;
	}
   $labelStr .= sprintf ("\n%s, %s  %s", $fam_City, $fam_State, $fam_Zip);
	if ($fam_Country != "" && $fam_Country != "USA" && $fam_Country != "United States") {
      $labelStr .= "\n" . $fam_Country;
	}
   $pdf->Add_PDF_Label($labelStr);
}

if ($iPDFOutputType == 1)
	$pdf->Output("ConfirmDataLabels" . date("Ymd") . ".pdf", true);
else
	$pdf->Output();	
?>