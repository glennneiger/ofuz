<?php 
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/ 
// Copyrights 2008 - 2010 all rights reserved, SQLFusion LLC, info@sqlfusion.com

    $pageTitle = 'Ofuz :: '._('Co-Workers');
    $Author = 'SQLFusion LLC';
    $Keywords = 'Keywords for search engine';
    $Description = 'Description for search engine';
    $background_color = 'white';
    include_once('config.php');
    include_once('includes/ofuz_check_access.script.inc.php');
    include_once('includes/header.inc.php');

    $do_notes = new ContactNotes($GLOBALS['conx']);
    $do_contact = new Contact($GLOBALS['conx']);
    $do_company = new Company($GLOBALS['conx']);
    $do_task = new Task($GLOBALS['conx']);
    $do_task_category = new TaskCategory($GLOBALS['conx']);
    $do_contact_task = new Contact();
    $do_project = new Project();

    $do_teams = new Teams();
    $do_teams->getTeams();
    $teams_count = $do_teams->getNumRows(); 

?>
<script type="text/javascript">
//<![CDATA[
function fnHighlightCoworkers(area) {
	var cwid=$("#cwid"+area);
	var div=$("#cw"+area);
	var ctlbar=$("#coworker_ctlbar");
    cwid.attr("checked",(cwid.is(":checked")?"":"checked"));
    if (cwid.is(":checked")) {
        div.css("background-color", "#ffffdd");
        if(ctlbar.is(":hidden"))ctlbar.slideDown("fast");
    } else {
        div.css("background-color", "#ffffff");
        var tally=0;
        $("input[type=checkbox][checked]").each(function(){tally++;});
        if(tally==0)ctlbar.slideUp("fast");
    }
}

function setContactForCoworker(){
  $("#do_contact_sharing__eventShareContactsMultiple").submit();
}
$(document).ready(function() {
    	$("div[id^=invite]").hover(function(){$("div[id^=trashcan]",this).show("slow");},function(){$("div[id^=trashcan]",this).hide("slow");});
    });

function showSharedDetail(divid){
  $("#"+divid).slideToggle("slow");
}

function hideSharedDetail(divid){
  $("#"+divid).fadeOut("slow");
}

//]]>
</script>
<table class="layout_columns"><tr><td class="layout_lmargin"></td><td>
<div class="layout_content">
<?php $thistab = ''; include_once('includes/ofuz_navtabs.php'); ?>
<?php $do_breadcrumb = new Breadcrumb(); $do_breadcrumb->getBreadcrumbs(); ?>
    <div class="grayline1"></div>
    <div class="spacerblock_20"></div>
    <table class="layout_columns"><tr><td class="layout_lcolumn">
	<?php
	  if(isset($_GET["message"])){
	      $message = new Message();
	      $message->setContent(_(htmlentities($_GET["message"])));
	      $message->displayMessage();
	  }
	  $GLOBALS['page_name'] = 'os_co_worker';
	  include_once('plugin_block.php');
	?>
    </td><td class="layout_rcolumn">
        <!--<div class="mainheader">
            <div class="pad20">
                <span class="headline14">Co-Workers</span>-->
              <div class="contentfull">
                          <?php
                            $_SESSION['do_coworker']->getAllRequest(); //Get all the requests
                            if($_SESSION['do_coworker']->getNumrows()){
                              echo '<br /><b>'._('The following User(s) are waiting for approval from you to become co-workers.').'</b><br />';
                              while($_SESSION['do_coworker']->next()){
                                $user_name = $_SESSION['do_User']->getFullName($_SESSION['do_coworker']->iduser);

                                $e_accept = new Event("do_coworker->eventAcceptInvitation");
                                $e_accept->addParam("id",$_SESSION['do_coworker']->iduser_relations);
                                $e_accept->addParam("goto",$_SERVER['PHP_SELF']);
                                $e_accept->addParam("coworker",$_SESSION['do_coworker']->idcoworker);
                                $e_accept->addParam("user",$_SESSION['do_coworker']->iduser);

                                $e_reject = new Event("do_coworker->eventRejectInvitation");
                                $e_reject->addParam("id",$_SESSION['do_coworker']->iduser_relations);
                                $e_reject->addParam("goto",$_SERVER['PHP_SELF']);
                                echo '<div style="width:200px;float:left;">'.$user_name.'</div><div style="position:relative;">'.$e_accept->getLink("Accept").'&nbsp;&nbsp;'.$e_reject->getLink("Reject").'</div>';
                              }
                            }else{
                              //echo '<br /><b>You have no Invitations to be a Co-Worker.</b><br />';
                            }
                            //echo '<div class="solidline"></div>';
                          ?>
           <!-- </div>
        </div>
        <div class="contentfull">-->
                     <?php
                          $set_share = false;
                          if(isset($_POST['ck'])){
                            $contact_ids = $_POST['ck'];
                            $set_share = true; // Request comes from Contact page
                          }
                          $_SESSION['do_coworker']->getAllCoWorker(); //Get all the co-workers
                          if (!is_object($_SESSION['do_contact_sharing'])) {
                            $do_contact_sharing = new ContactSharing();
                            $do_contact_sharing->sessionPersistent("do_contact_sharing", "index.php", 36000);
                           }
                           if($_SESSION['do_coworker']->getNumrows()){

                              if (!is_object($_SESSION['do_Contacts'])) {
                                  $do_Contacts = new Contact();
                                  $do_Contacts->setRegistry("all_contacts");
                                  $do_Contacts->sessionPersistent("do_Contacts", "index.php", 36000);
                              }
                               if(!$set_share){ // If not having POST vales	
                              ?>
                              <table class="mainheader pad20" width="100%">
                                <tr>
                                  <td><span class="headline14">Co-Workers</span>
                                  <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;
                                       <!-- <a href="teams.php"><?php echo _('Teams'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
                                        <a href="os_co_workers.php"><?php echo _('All Co-Workers'); ?></a></td>
                                  </tr>
                              </table>
                            <?php
            //echo '<a href="teams.php">Teamsrrrr</a>'.' - '.'<a href="co_workers.php">All Co-Workers</a>'.'<br /><br />';

			      /*if($teams_count) {
				echo '<br/><br/><b>'._('Your Teams').'</b><br /><br />';
				while($do_teams->next()) {
				  echo $do_teams->team_name.'<br />';
				}
			      } else {
				echo '<div><b>You do not have Team to sahre with.</b></div>';
			      }Commented team concept on Dec 28 2012*/
			      echo '<br />';
			  
                                  echo '<b>'._('Your Co-Workers :').'</b><br /><br />';
                                  $user_coworker = new User();
                                  while($_SESSION['do_coworker']->next()){
                                      $user_coworker->getId($_SESSION['do_coworker']->idcoworker);
                                      // Get the contacts shared for this co-worker
                                      $shared_contacts = $_SESSION['do_contact_sharing']->getSharedContacts($_SESSION['do_coworker']->idcoworker);
                                      if($shared_contacts && is_array($shared_contacts)){ 
                                        $ids_shared_as_user = implode(",",$shared_contacts);
                                      }else{$ids_shared_as_user = '';}
                                      // Create filter event link
                                      $e_shared_contacts_filter = new Event("do_Contacts->eventFilterContactAsCoWorker");
                                      $e_shared_contacts_filter->addParam("ids",$ids_shared_as_user);
                                      $e_shared_contacts_filter->addParam("goto","contacts.php");
                                      $e_shared_contacts_filter->addParam("setShare","No");
                                      $e_shared_contacts_filter->addParam("coworker",$_SESSION['do_coworker']->idcoworker);
                                      // Get Contacts shared by this contact
                                      $shared_contacts_from_coworker = $_SESSION['do_contact_sharing']->getSharedContactsByCoWorker($_SESSION['do_coworker']->idcoworker);  
                                      if($shared_contacts_from_coworker && is_array($shared_contacts_from_coworker)){
                                        $ids_shared_from_coworker = implode(",",$shared_contacts_from_coworker);
                                      }else{$ids_shared_from_coworker = '';}
                                      $e_shared_contacts_from_coworker_filter = new Event("do_Contacts->eventFilterContactAsCoWorker");
                                      $e_shared_contacts_from_coworker_filter->addParam("ids",$ids_shared_from_coworker);
                                      $e_shared_contacts_from_coworker_filter->addParam("goto","contacts.php");
                                      $e_shared_contacts_from_coworker_filter->addParam("setShare","Yes");
                                      $e_shared_contacts_from_coworker_filter->addParam("coworker",$_SESSION['do_coworker']->idcoworker);
                                      $no_cont_shared = $_SESSION['do_contact_sharing']->countSharedContacts($_SESSION['do_coworker']->idcoworker);
                                      $no_cont_shared_by_co_worker =$_SESSION['do_contact_sharing']->countSharedContactsByCoWorker($_SESSION['do_coworker']->idcoworker);
  
                                      $do_user = new User();                                      
                                      $do_contact = new Contact();
                                      $do_user->query("select idcontact from user where iduser = ".$_SESSION['do_coworker']->idcoworker);
                                      $do_user->getValues();
                                      if($do_user->getNumRows() > 0 ){
                                      $contact_id = $do_user->idcontact;
                                      if($contact_id != '' || $contact_id != 0 ){  
                                        $do_contact->getId($contact_id);
                                        $user_picture = $do_contact->picture;
                                      }          
                                      }
                                      $user_name = $do_user->getUserNameByIdUser($_SESSION['do_coworker']->idcoworker);
                                     
                                      //Get Bio Information
                                      $do_userprofile = new UserProfile();
                                      $profile_information = $do_userprofile->getProfileInformation($_SESSION['do_coworker']->idcoworker);    
                                      $job_description = $profile_information['job_description'];
                                      $do_userprofile->free();
                                      
                                      if($user_picture!=''){
                                      $thumb_name = $_SERVER['DOCUMENT_ROOT'].'/dbimage/thumbnail/'.$user_picture;
                                      if(file_exists($thumb_name)) {
                                        $user_pic="/dbimage/thumbnail/".$user_picture;
                                      } else {
                                        $user_pic="/images/empty_avatar.gif";
                                      }
                                      }else{
                                        $user_pic='/images/empty_avatar.gif';
                                      }          
 
                                      
				      
          echo '<div style="position:relative;width:100%;">';
                                      echo '<div class="feed_user_pic" style="positon:relative;float:left;overflow:hidden;">';
                                      
                                      echo '<a href="/profile/'.$user_name.'" target="_blank"> <img height="50px" width ="50px" alt="" src='.$user_pic.' > </a>';
                                      echo '</div>';
                                      echo '<div style="positon:relative;float:left;width:80%;margin:0px;"><a style="color:#C52EAD;" href="javascript:void(0)" onclick="showSharedDetail(\''.$_SESSION['do_coworker']->idcoworker.'\');" >'
                                                            .$_SESSION['do_coworker']->firstname.' '.$_SESSION['do_coworker']->lastname.
                                            '</a><br />'.$job_description.'</div>&nbsp;';

                                        $num_project_shared = $do_project->getNumProjectsShared($_SESSION["do_User"]->iduser,$_SESSION['do_coworker']->idcoworker);
                                        $no_proj_shared_by_co_worker = $do_project->getNumProjectsShared($_SESSION['do_coworker']->idcoworker,$_SESSION["do_User"]->iduser);
                                        echo '<div style="clear:both;padding:10px;"></div>';
                                        echo '<div class="feed_user_pic" style="overflow:hidden;">&nbsp;</div><div id="'.$_SESSION['do_coworker']->idcoworker.'" style="display:none;width:80%;margin:0px;">';
                                                                    if ($no_cont_shared > 0) {
                                                                      echo $e_shared_contacts_filter->getLink(
                                                                      '<span>
'.sprintf(_('You shared %d contacts'), $no_cont_shared).'
</span>
&nbsp;'.sprintf(_('and %d projects'),$num_project_shared).'&nbsp;&nbsp;');
                                                                    }else{
                                                                      echo '<span>'.sprintf(_('You shared %d contacts'), $no_cont_shared).'</span>&nbsp;'.sprintf(_('and %d projects'),$num_project_shared).'&nbsp;&nbsp;';
                                        }

$num_project_shared = $do_project->getNumProjectsShared($_SESSION["do_User"]->iduser,$_SESSION['do_coworker']->idcoworker);
$no_proj_shared_by_co_worker = $do_project->getNumProjectsShared($_SESSION['do_coworker']->idcoworker,$_SESSION["do_User"]->iduser);

echo '<div style="clear:both;padding:10px;"></div>';

echo '<div id="'.$_SESSION['do_coworker']->idcoworker.'" style="display:none;">';
if ($no_cont_shared > 0) {
  echo $e_shared_contacts_filter->getLink('<span>'.sprintf(_('You shared %d contacts'), $no_cont_shared).'</span>&nbsp;'.sprintf(_('and %d projects'),$num_project_shared).'&nbsp;&nbsp;');
}else{
 echo '<span>'.sprintf(_('You shared %d contacts'), $no_cont_shared).'</span>&nbsp;'.sprintf(_('and %d projects'),$num_project_shared).'&nbsp;&nbsp;';
}


if ($no_cont_shared_by_co_worker > 0) {
  echo $e_shared_contacts_from_coworker_filter->getLink(
  '<span>'.sprintf(_("%s shared %d contacts"), $_SESSION['do_coworker']->firstname, $no_cont_shared_by_co_worker).'</span>&nbsp;'.sprintf(_('and %d projects'),$no_proj_shared_by_co_worker));
}else{
  echo '<span>'.sprintf(_("%s shared %d contacts"), $_SESSION['do_coworker']->firstname, $no_cont_shared_by_co_worker).'</span>&nbsp;'.sprintf(_('and %d projects'),$no_proj_shared_by_co_worker);
}
                                                                    //if ($no_cont_shared > 0 || $no_cont_shared_by_co_worker > 0) {
                                                                      //echo '<br />';
                                                                    //}
                                        echo '</div>';
echo '</div>';
echo '<div style="clear:both;padding:10px;"></div>';
                                        echo '<br />';
                                      
                                  }// class="co_worker_pending"
                              }else{ // Having some POST data from contacts.php 

                                   $e_share_cont = new Event("do_contact_sharing->eventShareContactsMultiple");
                                   $e_share_cont->addEventAction("mydb.gotoPage", 304);
				   $e_share_cont->addEventAction("ContactTeam->eventShareExistingContactWithTeamCw", 200);
				   $e_share_cont->addEventAction("Teams->eventShareCWsWithTeam", 210);
                                   $e_share_cont->addParam("goto", "co_workers.php");
                                   $e_share_cont->addParam("idcontacts",$contact_ids);
                                   echo $e_share_cont->getFormHeader();
                                   echo $e_share_cont->getFormEvent();

				  echo '<a href="teams.php">Teams</a>'.' - '.'<a href="co_workers.php">All Co-Workers</a>'.'<br /><br />';

				  if($teams_count) {
				    echo '<b>'._('Your Teams').'</b><br /><br />';
				    while($do_teams->next()) {
				  ?>
				      <input type="checkbox" name="team[]" value="<?php echo $do_teams->idteam;?>" /> <?php echo $do_teams->team_name; ?><br />
				  <?php
				    }
				  } else {
				    echo '<div><b>You do not have Team to sahre with.</b></div>';
				  }
				  echo '<br />';

                                   echo '<b>'._('Choose co-workers for sharing the contacts :').'</b><br />';
                                   echo '<div id="coworker_ctlbar" style="display: none;">';
                                   echo '<span class="redlink"><a href="#" onclick="setContactForCoworker(); return false;" style="font-size:20px;">'._('Share').'</a></span>';
                                   echo '</div>';
                                   $user_coworker = new User();
                                   while($_SESSION['do_coworker']->next()){
                                      $user_coworker->getId($_SESSION['do_coworker']->idcoworker);
                                      echo '<div class="contact" id="cw'.$_SESSION['do_coworker']->idcoworker.'" onclick="fnHighlightCoworkers(\''.$_SESSION['do_coworker']->idcoworker.'\')">';
                                      echo '<input type="checkbox" class="ofuz_list_checkbox" name="cwid[]" id="cwid'.$_SESSION['do_coworker']->idcoworker.'" value="'.$_SESSION['do_coworker']->idcoworker.'" onclick="fnHighlightCoworkers(\''.$_SESSION['do_coworker']->idcoworker.'\')">&nbsp;&nbsp;';
                                      $no_cont_shared = $_SESSION['do_contact_sharing']->countSharedContacts($_SESSION['do_coworker']->idcoworker);

                                       $no_cont_shared_by_co_worker =$_SESSION['do_contact_sharing']->countSharedContactsByCoWorker($_SESSION['do_coworker']->idcoworker);

                                      /*echo '<div style="width:auto;">'
                                              .$user_coworker->getFullName().
                                            '</div>
                                             &nbsp;';*/
					$do_contact = new Contact();
					$do_contact->getUserContacts($_SESSION['do_coworker']->idcoworker);
					if($do_contact->getNumRows()){
					  while($do_contact->next()){
					    $co_workers[] = $do_->idcoworker;
					    $user_picture = $do_contact->picture;
					    $contact_id = $do_contact->idcontact;
					  }
					}


					if($user_picture ==''){
					  $user_pic="/images/empty_avatar.gif";
					}else{
					  $user_pic="/dbimage/".$user_picture;            
					}

				      echo '<div class="feed_user_pic" style="overflow:hidden;">';
				      $user_first_name=$_SESSION['do_coworker']->firstname;
				      echo '<a href="/profile/'.$user_first_name[0].'"> <img height="100%" alt="" src='.$user_pic.' > </a>';       
				      echo '</div>'; 
                                      echo '<div style="width:auto;"><a style="color:#C52EAD;" href="#" onclick="showSharedDetail(\''.$_SESSION['do_coworker']->idcoworker.'\');" >'
                                              .$_SESSION['do_coworker']->firstname.' '.$_SESSION['do_coworker']->lastname.
                                            '</a></div>
                                             &nbsp;';
                                    /**  echo   '<span>
                                               '._('You have shared').' '.$no_cont_shared.' '._('contacts').' 
                                            </span>
                                            &nbsp;&nbsp;';
                                      echo  '<span>' 
                                               .$no_cont_shared_by_co_worker.' '. _('contacts are shared by').' '.$_SESSION['do_User']->getFullName($_SESSION['do_coworker']->idcoworker).
                                            '</span>';
                                            **/
                                      echo '</div>';
                                      echo '<div class="solidline"></div>';
                                  } 
                                echo '</form>';
                              }
                           }else{
                               echo '<b>'. _('You have no co-workers').'</b>';
                           }
                     ?>
        </div>
    </td></tr></table>
    <div class="spacerblock_40"></div>
    <div class="layout_footer"></div>
</div>
</td><td class="layout_rmargin"></td></tr></table>
<?php include_once('includes/ofuz_facebook.php'); ?>
<?php include_once('includes/ofuz_analytics.inc.php'); ?>
</body>
</html>