<?php 
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/ 
// Copyright 2008 - 2010 all rights reserved, SQLFusion LLC, info@sqlfusion.com
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/


include_once('config.php');

include_once('plugin/Git/class/UserGitrepo.class.php');

include_once('plugin/Git/class/Git.class.php');

$do_usergit = new UserGitrepo();
$do_usergit->getAll();
$rows = $do_usergit->getNumRows();

$repo_path = "plugin/Git/repos/";
$commitlog = array();
while($do_usergit->fetch()){
	$repo_name = $do_usergit->getData('git_repo');
	$folder = "$repo_path"."$repo_name";
	if ( is_dir($folder)) {
		$repo = Git::open($folder);
		echo'<pre>';print_r($repo->log());echo'</pre>';
		$commitlog = $repo->log();
		$commit_log = split('\^',$commitlog);
		foreach($commit_log as $commits){
			if(!empty($commits)){
				$commit_hash = split(";",$commits);
				$user = split("--",$commit_hash[1]);
				$user_email = $user[0];
				$user_email = trim($user_email);
				$date = split(":",$user[1]);
				$date_log = $date[0];
				
				$note = $date[1];
				$task_ids = '';
				$task_ids = split('#',$note);
				//echo '<pre>';print_r($task_ids);echo'</pre>';
				
				$task_id = $task_ids[1];
				if(!empty($task_ids[2])){
					$time = $task_ids[2];
				}
				
				
				echo 'Commit Hash : '.$commit_hash[0].'<br />';
				echo 'User Name : '.$user_email.'<br />';
				echo 'Date :'.$date_log.'<br />';
				if(!empty($time)){
					echo 'Time :'.$time.'<br />';
				}
				//preg_match("|\d+|", $note, $task_id);
				
				//echo 'Task ID :'.$task_id[0].'<br />';
				echo 'Task ID :'.$task_id.'<br />';
				//var_dump($m);
				echo 'Msg : '.$note.'<br />'.'Commit Id : <a href="#">'.$commit_hash[0].'</a><br /><br />';
				$note .= '<br />Commit Id : <a href="/plugin/Git/git_commitlog.php?repo_name='.$repo_name.'&commithash='.$commit_hash[0].'">'.$commit_hash[0].'</a><br /><br />';
				
				$q = new sqlQuery($conx);
				$q->query("select iduser from user where email='".$user_email."'");
				if($q->getNumRows() >= 1){
					$q->fetch();
					$iduser = $q->getData('iduser');
					//echo '<br />Iduser : '.$iduser.'<br /><br /><br />';
					$do_plugin_enable = new PluginEnable();
					$id_plugin_enable = $do_plugin_enable->isEnabled("Git Repository",$iduser);
					if(!empty($id_plugin_enable)){
						
					$do_project_task = new ProjectTask();
					$do_project_task->getid($task_id);
					if($do_project_task->getNumRows() >= 1){
						
						$q1 = new sqlQuery($conx);
						$q1->query("select * from project_discuss where idproject_task='".$task_id[0]."' and discuss = '".$note."' and iduser='".$iduser."' and date_added ='".$date_log."'");
						if($q1->getNumRows() == 0){
							$do_project_diss = new ProjectDiscuss();
							$do_project_diss->addnew();
							$do_project_diss->idproject_task = $task_id;
							$do_project_diss->discuss = $note;
							$do_project_diss->date_added = $date_log;
							if(!empty($time)){
								$do_project_diss->hours_work = $time;
							}
							$do_project_diss->iduser = $iduser;
							$do_project_diss->discuss_edit_access = 'user';
							$do_project_diss->type = 'Note';
							$do_project_diss->add();
						}
					}//task id block ends
					
				} // Plugin Enable checking ends
				$do_plugin_enable->free();
				}// User block ends
				$q->free();
				
			}
			
		}
		
	}
}

// git log --author="vivek@sqlfusion.com" --grep='#' --pretty=format:'%h %an %ci : %s'
// git log --all --source --pretty=oneline --grep='#6928'

?>
