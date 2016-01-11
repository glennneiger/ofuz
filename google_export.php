<?php
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/ 
// Copyright 2008 - 2010 all rights reserved, SQLFusion LLC, info@sqlfusion.com
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/

    include_once("config.php");
    require_once 'class/GoogleContactImport.class.php';

    $gci_contact = new GoogleContactImport();
    $gci_contact->id_user = $_SESSION['do_User']->iduser;

    if(isset($_SESSION['sessionToken'])){
            //$gci_contact->uEmail = $_POST['email'];
            $gci_contact->processAuth();
            $gci_contact->syncOfuzToGmail();
            if($gci_contact->status_code_desc == ""){
                $status_code_desc = $gci_contact->getStatusDescription(208);
            } else{
                $status_code_desc = $gci_contact->status_code_desc;
            }
            header("Location:sync.php?msg=".$status_code_desc);
    }  
    else if($_POST['action']=="export"){
            $gci_contact->uEmail = $_POST['email'];
             $gci_contact->processAuth();            
    }
    else if(isset($_GET['token']) && isset($_SESSION["uEmail"]))
    {
            //$gci_contact->uEmail = $_POST['email'];
            $gci_contact->processAuth();
            $gci_contact->syncOfuzToGmail();
            if($gci_contact->status_code_desc == ""){
                $status_code_desc = $gci_contact->getStatusDescription(208);
            } else{
                $status_code_desc = $gci_contact->status_code_desc;
            }
            header("Location:sync.php?msg=".$status_code_desc);
    }

?>