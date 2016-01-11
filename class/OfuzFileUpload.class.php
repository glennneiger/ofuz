<?php

/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/ 
// Copyright 2008 - 2010 all rights reserved, SQLFusion LLC, info@sqlfusion.com
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/

/**
 * Class strFBFieldFile RegistryField class
 *
 * In the Form context Display a input type File and trigger the EventAction: mydb.formatPictureField that will process the uploaded file.
 * In the Disp context if the file is an image display it in an image tag, otherwize in a link to download it.
 * @package PASClass
 */
Class OfuzFileUpload extends RegistryFieldBase {
    function default_Disp($field_value="") {
        $file_path = trim($this->rdata['picture']);
        if (!ereg("/$", $file_path)) {
            $file_path .= "/";
        }
        if (!$this->getRdata('execute')) {
            $field_value = $this->no_PhpCode($field_value);
        }
        if ($this->getRData('showpicture')=="1" && !empty($field_value)) {
            $fval="<img border=\"0\" src=\"".$file_path.$field_value."\">";
         } else {
            $fval = $file_path.$field_value;
            $fval = "<a href=\"".$fval."\">".$fval."</a>" ;
         }
         $this->processed .= $fval;
    }

    function default_Form($field_value="") {
      if (!$this->rdata['hidden'] && !$this->rdata['readonly']) {
            if (!$this->getRdata('execute')) {
                    $field_value = $this->no_PhpCode($field_value);
            }
      //      list ($filedir, $filename) = explode(":", $this->rdata['picture']) ; PHL 012006 uses no overwrite
            $overwrite = strtolower($this->rdata['overwrite']);
            $filedir =  $this->rdata['picture'];
            $fval .= "<input type=\"hidden\" name=\"mydb_events[5]\" value=\"ofuz.formatPictureField\"/>" ;
        //    if (strlen($filename) > 0) {
        //        $fval .= "<input type=\"hidden\" name=\"filenameuploaded[]\" value=\"$filename\"/>" ;
        //    }
            if ($overwrite == "no") {
                $fval .= "<input type=\"hidden\" name=\"fileoverwrite[]\" value=\"no\"/>" ;
            }
            $fval .= "<input type=\"hidden\" name=\"filedirectoryuploaded[]\" value=\"$filedir\"/>" ;
            $fval .= "<input type=\"hidden\" name=\"filefield[]\" value=\"".$this->field_name."\"/>";
            $fval .= "<input type=\"hidden\" name=\"fields[".$this->field_name."]\" value=\"".$field_value."\"/>";
            $fval .= "<input class=\"adformfield\" name=\"userfile[]\" type=\"file\"/>";
            if($field_value!="") $fval .= "(".$field_value.")";
            $this->processed .= $fval;
        }
    }
}
?>