<?php 
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/ 
// Copyright 2008 - 2011 all rights reserved, SQLFusion LLC, info@sqlfusion.com
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/

    $pageTitle = 'Ofuz :: Invoice Settings';
    $Author = 'SQLFusion LLC';
    $Keywords = 'Keywords for search engine';
    $Description = 'Description for search engine';
    $background_color = 'white';
    include_once('config.php');
    include_once('includes/ofuz_check_access.script.inc.php');
    include_once('includes/header.inc.php');
    
?>
<?php $do_feedback = new Feedback(); $do_feedback->createFeedbackBox(); ?>
<table class="layout_columns"><tr><td class="layout_lmargin"></td><td>
<div class="layout_content">
<?php $thistab = ''; include_once('includes/ofuz_navtabs.php'); ?>
<?php $do_breadcrumb = new Breadcrumb(); $do_breadcrumb->getBreadcrumbs(); ?>
    <div class="grayline1"></div>
    <div class="spacerblock_20"></div>
    <table class="layout_columns"><tr><td class="layout_lcolumn settingsbg">
        <div class="settingsbar"><div class="spacerblock_16"></div>
            <?php
                $GLOBALS['thistabsetting'] = 'Plugins';
                include_once('includes/setting_tabs.php');
             ?>
        <div class="settingsbottom"></div></div>
    </td><td class="layout_rcolumn">
		<div class="banner60 pad020 text32"><?php echo _('Add-On'); ?></div>

         <div class="mainheader">
            <div class="pad20">
                <span class="headline14"><?php
                   echo _('Full plugin list'); 
                ?></span>
                <?php
                if (is_object($GLOBALS['cfg_submenu_placement']['settings_plugin'] ) ) {
                	echo  $GLOBALS['cfg_submenu_placement']['settings_plugin']->getMenu();
                }
                ?>
            </div>
        </div>       
        <div class="contentfull">    <div class="spacerblock_20"></div>
        <?php
                if($_SESSION['in_page_message'] != ''){
                  echo '<div style="margin-left:0px;">';
                  echo '<div class="messages_unauthorized">';
                  echo htmlentities($_SESSION['in_page_message']);
                  $_SESSION['in_page_message'] = '';
                  echo '</div></div><br /><br />';
              }
          ?>


        <?php 
                  echo '<b>'._('Block Plugins').' :</b><br /><br />';
                  $do_plugin_enable = new PluginEnable();
                  $do_dynamic_button = new DynamicButton();
                  if(is_array($cfg_block_placement) && count($cfg_block_placement) > 0 ){
                      echo '<table width="100%">';
                      foreach($cfg_block_placement as $key=> $val ){
                          foreach($val as $block_class_name){  
                              $do_blocks = new $block_class_name();
                              echo '<tr height="30px;">';
                              echo '<td colspan=1 width="30%">';
                              //echo $block_class_name ;
                              echo $do_blocks->getShortDescription();
                              echo '</td>';
                              echo '<td colspan=2 valign="left" width="50%">';
                              echo $do_blocks->getLongDescription();
                              echo '</td>';
                               echo '<td width=12%>';


                        if(in_array($block_class_name,$core_plugin_names )){     
                              echo "Default plugins";
                              echo '</td>';
                              echo '</tr>';
                              echo '<tr>';
                              echo '<td colspan=4>';
                              echo '<div class="dashedline"></div>'; 
                              echo '</td>';
                              echo '</tr>';
                              echo '<td width=12%>'; 

                        }else{
                              // Enable or desable section comes here
                              $idplugin_enable = $do_plugin_enable->isEnabled($block_class_name);
                              if($idplugin_enable === false ){
                                  $button = $do_dynamic_button->CreateButton('',_('Enable'));
                                  $e_enable = new Event('PluginEnable->eventEnablePlugin');
                                  $e_enable->addParam('goto', $_SERVER['PHP_SELF']);
                                  $e_enable->addParam('plugin', $block_class_name);
                                  echo $e_enable->getLink(_('Enable'));
                                  //echo $e_enable->getLink($button);
                              }else{
                                  $button = $do_dynamic_button->CreateButton('',_('Disable'));
                                  $e_enable = new Event('PluginEnable->eventDisablePlugin');
                                  $e_enable->addParam('goto', $_SERVER['PHP_SELF']);
                                  $e_enable->addParam('idplugin_enable', $idplugin_enable);
                                  echo $e_enable->getLink(_('Disable'));
                                  //echo $e_enable->getLink($button);
                              }
                              echo '</td>';
                              echo '</tr>';
                              echo '<tr>';
                              echo '<td colspan=4>';
                              echo '<div class="dashedline"></div>'; 
                              echo '</td>';
                              echo '</tr>';
                          }
                      }}
                      echo '</table>';
                   }
                  
                  
                  /** Menu Block Displaying **/             
                   echo "<br>"; 
                   echo '<b>'._('Menu Plugins').' :</b><br /><br />';
                  $do_plugin_enable = new PluginEnable();
                  $do_dynamic_button = new DynamicButton();  

                  foreach($GLOBALS['cfg_tab_placement'] as  $tab_plugin ){  
                      if (is_object($tab_plugin )) {  
                          $tab_name=$tab_plugin->getTabName();
              
                          if(in_array($tab_name,$core_tab_name)){
                              echo '<table width="100%">';                      
                              echo '<tr height="30px;">';
                              echo '<td colspan=1 width="30%">';
                             
                              echo $tab_name;
                              echo '</td>';
                               echo '<td colspan=2 valign="left" width="50%">';
                             
                              echo '</td>';
                              echo '<td width=12%>';
                              //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                  
                              echo "Default plugins";
                              echo '</td>';
                              echo '</tr>';
                              echo '<tr>';
                              echo '<td colspan=4>';
                              echo '<div class="dashedline"></div>'; 
                              echo '</td>';
                              echo '</tr>';
                           }else{
                              echo '<table width="100%">';                      
                              echo '<tr height="30px;">';
                              echo '<td colspan=1>';
                             
                              echo $tab_name;
                              echo '</td>';
                              echo '<td colspan=2 valign="left">';
                             
                              echo '</td>';
                              echo '<td width=12%>';
                                            
                             // Enable or disable section comes here
                             $idplugin_enable = $do_plugin_enable->isEnabled($tab_name);
                              if($idplugin_enable === false ){
                                  $button = $do_dynamic_button->CreateButton('',_('Enable'));
                                  $e_enable = new Event('PluginEnable->eventEnablePlugin');
                                  $e_enable->addParam('goto', $_SERVER['PHP_SELF']);
                                  $e_enable->addParam('plugin', $tab_name);
                                  echo $e_enable->getLink(_('Enable'));
                                  //echo $e_enable->getLink($button);
                              }else{
                                  $button = $do_dynamic_button->CreateButton('',_('Disable'));
                                  $e_enable = new Event('PluginEnable->eventDisablePlugin');
                                  $e_enable->addParam('goto', $_SERVER['PHP_SELF']);
                                  $e_enable->addParam('idplugin_enable', $idplugin_enable);
                                  echo $e_enable->getLink(_('Disable'));
                                  //echo $e_enable->getLink($button);
                              }
                              echo '</td>';
                              echo '</tr>';
                              echo '<tr>';
                              echo '<td colspan=4>';
                              echo '<div class="dashedline"></div>'; 
                              echo '</td>';
                              echo '</tr>';
                    }
            }
                
    }
            echo '</table>';   
              

                /** Setting Page Displaying **/
  
                  echo "</br>";
                  echo '<b>'._('Setting Plugins').' :</b><br /><br />';

                  foreach($GLOBALS['cfg_setting_tab_placement'] as  $setting_tab_plugin ){  
                      if (is_object($setting_tab_plugin )) {  
                           
                            $setting_tab_name=$setting_tab_plugin->getTabName();
                   if(in_array($setting_tab_name,$core_setting_tab_name)){
                              echo '<table width="100%">';                      
                              echo '<tr height="30px;">';
                              echo '<td colspan=1 width="30%">';
                             
                              echo $setting_tab_name;
                              echo '</td>';
                              echo '<td colspan=2 valign="left" width="50%">';
                             
                              echo '</td>';
                              echo '<td width=12%>';
                              echo "Default plugins";
                              echo '</td>';
                              echo '</tr>';
                              echo '<tr>';
                              echo '<td colspan=4>';
                              echo '<div class="dashedline"></div>'; 
                              echo '</td>';
                              echo '</tr>';
                           }else{
                              echo '<table width="100%">';                      
                              echo '<tr height="30px;">';
                              echo '<td colspan=1>';
                             
                              echo $setting_tab_name;
                              echo '</td>';
                              echo '<td colspan=2 valign="left">';
                             
                              echo '</td>';
                              echo '<td width=12%>';
                              
                  
                             // Enable or disable section comes here
                             $idplugin_enable = $do_plugin_enable->isEnabled($setting_tab_name);
                              if($idplugin_enable === false ){
                                  $button = $do_dynamic_button->CreateButton('',_('Enable'));
                                  $e_enable = new Event('PluginEnable->eventEnablePlugin');
                                  $e_enable->addParam('goto', $_SERVER['PHP_SELF']);
                                  $e_enable->addParam('plugin', $setting_tab_name);
                                  echo $e_enable->getLink(_('Enable'));
                                  //echo $e_enable->getLink($button);
                              }else{
                                  $button = $do_dynamic_button->CreateButton('',_('Disable'));
                                  $e_enable = new Event('PluginEnable->eventDisablePlugin');
                                  $e_enable->addParam('goto', $_SERVER['PHP_SELF']);
                                  $e_enable->addParam('idplugin_enable', $idplugin_enable);
                                  echo $e_enable->getLink(_('Disable'));
                                  //echo $e_enable->getLink($button);
                              }
                              echo '</td>';
                              echo '</tr>';
                              echo '<tr>';
                              echo '<td colspan=4>';
                              echo '<div class="dashedline"></div>'; 
                              echo '</td>';
                              echo '</tr>';
                    }         echo '</table>';









                      }
                    }

                
            
        ?>
        </div>
        <div class="solidline"></div>
    </td></tr></table>
    <div class="spacerblock_40"></div>
    <div class="layout_footer"></div>
</div>
</td><td class="layout_rmargin"></td></tr></table>
<?php include_once('includes/ofuz_facebook.php'); ?>
<?php include_once('includes/ofuz_analytics.inc.php'); ?>
</body>
</html>
