<?php

class ucp_cg
	{
	var $u_action;
	var $new_config;
	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		switch($mode)
		{
			case 'index':
				$this->page_title = 'UCP_CG';
				$this->tpl_name = 'ucp_cg';
				
				// Initial var setup
				$forum_id	= request_var('f', 0);
				$topic_id	= request_var('t', 0);
				$post_id	= request_var('p', 0);
				
				$sql = 'SELECT topic_id, topic_title
					FROM ' . TOPICS_TABLE . ' 
					WHERE forum_id = 11';
				
				$result = $db->sql_query($sql);
				$liste_edf = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				$lien_edf = '';
				foreach ($liste_edf as $row)
				{
					if ($row['topic_id'] != 199)
					{
						$lien_edf = '<a href="viewtopic.php?t=' . $row['topic_id'] . '" target="blank">' . $row['topic_title'] . '</a>';
						$template->assign_block_vars('block_name', array(
							'EDF'	=> $lien_edf,
						));
					}
				}
			break;
				
			case 'liste_edf':
				$this->page_title = 'UCP_CG';
				$this->tpl_name = 'ucp_cg_liste_edf';
				
				// Initial var setup
				$forum_id	= request_var('f', 0);
				$topic_id	= request_var('t', 0);
				$post_id	= request_var('p', 0);
				
								
				// Liste des posteurs du topic
				$sql = 'SELECT poster_id, username, post_id
					FROM ' . POSTS_TABLE . ' AS p, ' . USERS_TABLE . ' AS u 
					WHERE topic_id = ' . $topic_id . ' AND p.poster_id = u.user_id';
				
				$result = $db->sql_query($sql);
				$liste_posteur = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				$template->assign_vars(array(
					'TOPIC_ID' => $topic_id,
				));
				
				$i = 1;
				foreach ($liste_posteur as $row2)
				{
				if ($i != 1)
					{
					$sql = 'SELECT DISTINCT post_id, post_time
						FROM ' . POSTS_TABLE . ' AS p, ' . USERS_TABLE . ' AS u
						WHERE topic_id = ' . $topic_id . ' AND p.post_id = ' . $row2['post_id'];
					$result = $db->sql_query($sql);
					$liste_posts = $db->sql_fetchrowset($result);
					
					$liste_post_complete = '';
					foreach ($liste_posts as $post)
					{
						$post['post_time'] = $post['post_time'] + 3600;
						$heure_post = date('H:m:s \l\e d\/m', $post['post_time']);
						$liste_post_complete = $liste_post_complete . '<a href="http://dev.zurp.me/phpBB/viewtopic.php?t=' . 
									$topic_id . '&p=' . $post['post_id'] . '#p' . $post['post_id'] . '" target="blank">' . 
									$post['post_id'] . '</a> ('. $heure_post . ')	';
					}

					$sql = 'SELECT post_text, bbcode_uid, bbcode_bitfield, enable_bbcode, enable_smilies, enable_magic_url
						FROM ' . POSTS_TABLE . ' AS p 
						WHERE p.topic_id = ' . $topic_id . ' AND p.post_id = ' . $row2['post_id'];
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$bbcode_options = 1 +
						(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
						(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
					$text_post = generate_text_for_display($row['post_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options);

					$template->assign_block_vars('block_name', array(
					'POSTER'		 => $row2['username'],
					'ID' 			=> $i,
					'POSTER_POSTS'	=> $liste_post_complete,
					'POSTER_TEXT'	=> $text_post,
					));
					}
					$i ++;
				}
				
			break;
			
			case 'listes_PP':
				$this->page_title = 'UCP_CG';
				$this->tpl_name = 'ucp_cg_listes_pp';
			
			break;
			
			case 'inscription_edf':
				$this->page_title = 'UCP_CG_INSCRIPTION_EDF_TITLE';
				$this->tpl_name = 'ucp_cg_inscription_edf';
			
				// Initial var setup
				$forum_id	= request_var('f', 0);
				$topic_id	= request_var('t', 0);
				$post_id	= request_var('p', 0);
				$inscription	= request_var('inscription', 0);
				$charte		= request_var('charte_ok', 0);
				$dep		= request_var('departement', 0);

				$template->assign_var('LISTE_EDF', $topic_id);
				
				if ($inscription == 0 )
				{	if ($topic_id == '0')
					{
						$sql = 'SELECT topic_id, topic_title
							FROM ' . TOPICS_TABLE . ' 
							WHERE forum_id = 11';
						
						$result = $db->sql_query($sql);
						$liste_edf = $db->sql_fetchrowset($result);
						
						$db->sql_freeresult($result);
						
						$lien_edf = '';
						foreach ($liste_edf as $row)
						{
							if ($row['topic_id'] != 199)
							{
								$lien_edf = '<a href="ucp.php?i=cg&mode=inscription_edf&t=' . $row['topic_id'] . '">' . $row['topic_title'] . '</a>';
								$template->assign_block_vars('block_name', array('LISTE_EDF' => $lien_edf,
								));
							}
						}
					}
					else
					{
						$sql = 'SELECT topic_title
							FROM ' . TOPICS_TABLE . ' 
							WHERE topic_id = ' . $topic_id;
						
						$result = $db->sql_query($sql);
						$titre_edf = $db->sql_fetchrow($result);
						
						$db->sql_freeresult($result);
						
						$template->assign_var('TITRE_EDF', $titre_edf['topic_title']);
					}
				}
				else
				{
					$template->assign_var('INSCRIPTION', $inscription);
					$template->assign_var('DEP', $dep);
					$template->assign_var('CHARTE_VALIDE', $charte);
					if ($dep)
					{
						$template->assign_var('DEP_VALIDE', 1);
					}
					if ($charte && $dep)
					{
						$message = $username . ' - ' . $dep . ' - jai lu et jaccepte la charte'; 
						
						$errors = generate_text_for_storage($message, $uid, $bitfield, $options, false, false, false);

						if(sizeof($errors)){
							// Errors occured, show them to the user.
							// PARSE_ERRORS variable must be defined in the template
							$template->assign_vars(array(
								'PARSE_ERRORS'	=> implode('<br>', $errors),
							));
							break;
						}
						else
						{
							// No parse errors; save the text in my table

							// New Topic Example
							$mon_super_post = array( 
								// General Posting Settings
								'forum_id'			=> 11,			// The forum ID in which the post will be placed. (int)
								'topic_id'			=> $topic_id,	// Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
								'icon_id'			=> false,		// The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)
							
								// Defining Post Options
								'enable_bbcode'		=> true,		// Enable BBcode in this post. (bool)
								'enable_smilies'	=> true,		// Enabe smilies in this post. (bool)
								'enable_urls'		=> true,		// Enable self-parsing URL links in this post. (bool)
								'enable_sig'		=> true,		// Enable the signature of the poster to be displayed in the post. (bool)
							
								// Message Body
								'message'			=> $message,	// Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
								'message_md5'	=> md5($message),	// The md5 hash of your message
							
								// Values from generate_text_for_storage()
								'bbcode_bitfield'	=> $bitfield,	// Value created from the generate_text_for_storage() function.
								'bbcode_uid'		=> $uid,		// Value created from the generate_text_for_storage() function.
							
								// Other Options
								'post_edit_locked'	=> 0,			// Disallow post editing? 1 = Yes, 0 = No
								'topic_title'		=> 'new',		// Subject/Title of the topic. (string)
							
								// Email Notification Settings
								'notify_set'	=> false,			// (bool)
								'notify'		=> false,			// (bool)
								'post_time'		=> 0,				// Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
								'forum_name'	=> 'pouet',				// For identifying the name of the forum in a notification email. (string)
							
								// Indexing
								'enable_indexing'	=> true,		// Allow indexing the post? (bool)
							
								// 3.0.6
								'force_approved_state'	=> true,	// Allow the post to be submitted without going into unapproved queue
							
								// 3.1-dev, overwrites force_approve_state
								'force_visibility'		=> true,	// Allow the post to be submitted without going into unapproved queue, or make it be deleted
							);
							echo 'test<br>';
							echo $message;
							echo '<br> 2 :';
							echo $mon_super_post['message'];
							echo '<br> 3 : ';
							echo $topic_id;
							echo '<br> 4 :';
							//$url = submit_post('reply','lalala','lolo','POST_NORMAL',$mon_super_post);//'reply','lalala',$username,'POST_NORMAL',,,);
							echo 'truc';
							
							$template->assign_var('RESULT', $message);
							$template->assign_var('OKKK', $pouet);
							$data_concat = implode(', ', $mon_super_post);
							$template->assign_var('DATA_CONCAT', $data_concat);
						}
					}
				}
			break;
			
			case 'gen_adresse':
				$this->page_title = 'UCP_CG';
				$this->tpl_name = 'ucp_cg_gen_adresse';
			
			break;
			
			case 'recap_edf':
				$this->page_title = 'UCP_CG_RECAP_EDF_TITLE';
				$this->tpl_name = 'ucp_cg_recap_edf';
				
				// Initial var setup
				$forum_id	= request_var('f', 0);
				$topic_id	= request_var('t', 0);
				$post_id	= request_var('p', 0);
				
				
				// Liste des posteurs du topic
				$sql = 'SELECT DISTINCT poster_id, username
					FROM ' . POSTS_TABLE . ' AS p, ' . USERS_TABLE . ' AS u 
					WHERE topic_id = ' . $topic_id . ' AND p.poster_id = u.user_id';
				
				$result = $db->sql_query($sql);
				$liste_posteur = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				$template->assign_vars(array(
					'TOPIC_ID' => $topic_id,
				));
				
				$i = 1;
				$count_26_4200 = 0;
				$count_26_3500 = 0;
				
				foreach ($liste_posteur as $row2)
				{
					if ($row2['poster_id'] != 3848)
					{
						
						$sql = 'SELECT post_text, bbcode_uid, bbcode_bitfield, enable_bbcode, enable_smilies, enable_magic_url
							FROM ' . POSTS_TABLE . ' AS p 
							WHERE p.topic_id = ' . $topic_id . ' AND p.poster_id = ' . $row2['poster_id'];
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
							(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
							(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
						$text_post = generate_text_for_display($row['post_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options);


						$count_26_4200 += 
							$text_post[strpos($text_post, '4200mAH :') + 9] + $text_post[strpos($text_post, '4200mAH :') + 10];
						
						$count_26_3500 += 
							$text_post[strpos($text_post, '3500mAH :') + 9] + $text_post[strpos($text_post, '3500mAH :') + 10];
						
						$count_265_3000 += 
							$text_post[strpos($text_post, '3000mAH :') + 9] + $text_post[strpos($text_post, '3000mAH :') + 10];
						
						$count_186_3100 += 
							$text_post[strpos($text_post, '3100mAH ') + 19] + $text_post[strpos($text_post, '3100mAH ') + 18];
						
						$count_186_2500_f += 
							$text_post[strpos($text_post, '2500mAH flat top') + 19] + $text_post[strpos($text_post, '2500mAH flat top') + 18];
						
						$count_186_2500_n += 
							$text_post[strpos($text_post, '2500mAH nipple') + 17] + $text_post[strpos($text_post, '2500mAH nipple') + 18];
						
						$count_186_2100_f += 
							$text_post[strpos($text_post, '2100mAH flat top ') + 19] + $text_post[strpos($text_post, '2100mAH flat top ') + 18];
						
						$count_186_2100_n += 
							$text_post[strpos($text_post, '2100mAH nipple') + 17] + $text_post[strpos($text_post, '2100mAH nipple') + 18];
						
						$count_18_1000_f+= 
							$text_post[strpos($text_post, '1000mAH flat top') + 19] + $text_post[strpos($text_post, '1000mAH flat top') + 18];
						
						$count_18_1000_n += 
							$text_post[strpos($text_post, '1000mAH nipple') + 17] + $text_post[strpos($text_post, '1000mAH nipple') + 18];
						
						$count_18_700_f += 
							$text_post[strpos($text_post, '700mAH flat top') + 17] + $text_post[strpos($text_post, '700mAH flat top') + 18];
						
						$count_18_700_n += 
							$text_post[strpos($text_post, '700mAH nipple') + 17] + $text_post[strpos($text_post, '700mAH nipple') + 16];
						
						$count_16_700 += 
							$text_post[strpos($text_post, '700mAH :') + 9] + $text_post[strpos($text_post, '700mAH :') + 8];



						$count_users_26_4200 = 0;
						$count_users_26_4200 = 
							$text_post[strpos($text_post, '4200mAH :') + 9] + $text_post[strpos($text_post, '4200mAH :') + 10];
						
						$count_users_26_3500 = 0;
						$count_users_26_3500 = 
							$text_post[strpos($text_post, '3500mAH :') + 9] + $text_post[strpos($text_post, '3500mAH :') + 10];
						
						$count_users_265_3000 = 
							$text_post[strpos($text_post, '3000mAH :') + 9] + $text_post[strpos($text_post, '3000mAH :') + 10];
						
						$count_users_186_3100 = 
							$text_post[strpos($text_post, '3100mAH ') + 19] + $text_post[strpos($text_post, '3100mAH ') + 18];
						
						$count_users_186_2500_f = 
							$text_post[strpos($text_post, '2500mAH flat top') + 19] + $text_post[strpos($text_post, '2500mAH flat top') + 18];
						
						$count_users_186_2500_n = 0;
						$count_users_186_2500_n = 
							$text_post[strpos($text_post, '2500mAH nipple') + 17] + $text_post[strpos($text_post, '2500mAH nipple') + 18];
						
						$count_users_186_2100_f = 
							$text_post[strpos($text_post, '2100mAH flat top ') + 19] + $text_post[strpos($text_post, '2100mAH flat top ') + 18];
						
						$count_users_186_2100_n = 
							$text_post[strpos($text_post, '2100mAH nipple') + 17] + $text_post[strpos($text_post, '2100mAH nipple') + 18];
						
						$count_users_18_1000_f= 
							$text_post[strpos($text_post, '1000mAH flat top') + 19] + $text_post[strpos($text_post, '1000mAH flat top') + 18];
						
						$count_users_18_1000_n = 
							$text_post[strpos($text_post, '1000mAH nipple') + 17] + $text_post[strpos($text_post, '1000mAH nipple') + 18];
						
						$count_users_18_700_f = 
							$text_post[strpos($text_post, '700mAH flat top') + 17] + $text_post[strpos($text_post, '700mAH flat top') + 18];
						
						$count_users_18_700_n = 
							$text_post[strpos($text_post, '700mAH nipple') + 17] + $text_post[strpos($text_post, '700mAH nipple') + 16];
						
						$count_users_16_700 = 
							$text_post[strpos($text_post, '700mAH :') + 9] + $text_post[strpos($text_post, '700mAH :') + 8];


						
						$template->assign_var('DEBUGMYSQL', $sql);
						$template->assign_block_vars('block_name', array(
							'POSTER'		=> $row2['username'],
							'ID' 			=> $i,
							'POSTER_POSTS'	=> $text_post,
							'USER_4200'	=> $count_users_26_4200,
							'USER_3500'	=> $count_users_26_3500,
							'USER_3000'	=> $count_users_265_3000,
				//18650
							'USER_18_3100'	=> $count_users_186_3100,
							'USER_18_2500_F'	=> $count_users_186_2500_f,
							'USER_18_2500_N'	=> $count_users_186_2500_n,
							'USER_18_2100_F'	=> $count_users_186_2100_f,
							'USER_18_2100_N'	=> $count_users_186_2100_n,
				//18500
							'USER_18_1000_F'	=> $count_users_18_1000_f,
							'USER_18_1000_N'	=> $count_users_18_1000_n,
				//18350
							'USER_18_700_F'	=> $count_users_18_700_f,
							'USER_18_700_N'	=> $count_users_18_700_n,
				//16340
							'USER_16_700'	=> $count_users_16_700,
						));
						$i ++;
					}
				}
				
				// 26650
				$template->assign_var('TOTAL_4200', $count_26_4200);
				$template->assign_var('TOTAL_3500', $count_26_3500);
				// 26500
				$template->assign_var('TOTAL_3000', $count_265_3000);
				//18650
				$template->assign_var('TOTAL_18_3100', $count_186_3100);
				$template->assign_var('TOTAL_18_2500_F', $count_186_2500_f);
				$template->assign_var('TOTAL_18_2500_N', $count_186_2500_n);
				$template->assign_var('TOTAL_18_2100_F', $count_186_2100_f);
				$template->assign_var('TOTAL_18_2100_N', $count_186_2100_n);
				//18500
				$template->assign_var('TOTAL_18_1000_F', $count_18_1000_f);
				$template->assign_var('TOTAL_18_1000_N', $count_18_1000_n);
				//18350
				$template->assign_var('TOTAL_18_700_F', $count_18_700_f);
				$template->assign_var('TOTAL_18_700_N', $count_18_700_n);
				//16340
				$template->assign_var('TOTAL_16_700', $count_16_700);
				
				$total = $count_26_4200 + $count_26_3500 + $count_265_3000 + 
						$count_186_3100 + $count_186_2500_f + $count_186_2500_n + $count_186_2100_f + $count_186_2100_n + 
						$count_18_1000_f + $count_18_1000_n + 
						$count_18_700_f + $count_18_700_n + $count_16_700;
				
				$template->assign_var('TOTAL', $total);
				
			break;
		}
	}
}
?>