<?php

class ucp_cg
{
	var $u_action;
	var $new_config;
	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		include($phpbb_root_path . '/includes/functions_cg.php');
		switch($mode)
		{
			case 'index':
				$this->page_title = 'UCP_CG';
				$this->tpl_name = 'ucp_cg';
				
				// Initial var setup
				$forum_id	= request_var('f', 0);
				$topic_id	= request_var('t', 0);
				$post_id	= request_var('p', 0);
				
				// Liste des cg en cours
				$liste_cg = liste_cg(1);
				
				foreach ($liste_cg as $cg)
				{
					$template->assign_block_vars('liste_cg_en_cours', array(
						'ID'	=> $cg['id'],
						'TITRE'	=> $cg['titre'],
						'ORGA'	=> get_username($cg['orga']),
					));
				}
				
				// Liste des cg archivées
				$liste_cg = liste_cg(0);
				
				foreach ($liste_cg as $cg)
				{
					$template->assign_block_vars('liste_cg_archive', array(
						'ID'	=> $cg['id'],
						'TITRE'	=> $cg['titre'],
						'ORGA'	=> get_username($cg['orga']),
					));
				}
				
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
						$lien_liste_edf = '<a href="ucp.php?i=cg&mode=liste_edf&t=' . $row['topic_id'] . '">Liste Edf</a>';
						$lien_liste_pp = '<a href="ucp.php?i=cg&mode=liste_PP&t=' . $row['topic_id'] . '">Liste PP</a>';
						$template->assign_block_vars('listing_cg', array(
							'CG'	=> $lien_edf,
							'EDF'	=> $lien_liste_edf,
							'PP'	=> $lien_liste_pp,
						));
					}
				}
			break;
				
			case 'create_cg':
				$this->page_title = 'UCP_CG_CREATE_CG';
				$this->tpl_name = 'ucp_cg_create_cg';
				
				// Initial var setup
				
				$cg_id			= request_var('cg_id', 0);
				$cg_titre		= utf8_normalize_nfc(request_var('cg_titre', '', true));
				$cg_edf			= request_var('cg_edf', 0);
				$cg_cg			= request_var('cg_cg', 0);
				$cg_orga		= request_var('cg_orga', 0);
				$cg_ref			= request_var('cg_ref', 0);
				$cg_shipping	= utf8_normalize_nfc(request_var('cg_shipping', 0, true));
				$cg_pp_fees		= utf8_normalize_nfc(request_var('cg_pp_fees', 0, true));
				$cg_maj			= request_var('maj', 0);
				
				if ($cg_maj == 1)
				{
					$data = array(
						'titre'		=> $cg_titre,
						'edf'		=> $cg_edf,
						'cg'		=> $cg_cg,
						'orga'		=> $cg_orga,
						'ref'		=> $cg_ref,
						'shipping'	=> $cg_shipping,
						'pp_fees'	=> $cg_pp_fees,
					);
					
					if ($cg_id == '') // nouvelle CG
					{
						$sql = 'INSERT INTO cg_cg ' . $db->sql_build_array('INSERT', $data);
					}
					else
					{
						$sql = 'UPDATE cg_cg SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE cg_cg.id = ' . $cg_id;
					}
					$db->sql_query($sql);
					
					// update items
					
					$new_item_1_prix	= utf8_normalize_nfc(request_var('new_item_1_prix', '', true));
					$new_item_1_des	= utf8_normalize_nfc(request_var('new_item_1_des', '', true));
					$new_item_1_ref	= utf8_normalize_nfc(request_var('new_item_1_ref', '', true));
					
					if ($new_item_1_ref != '')
					{
						$data = array(
							'cg'	=> $cg_id,
							'ref'	=> $new_item_1_ref,
							'des'	=> $new_item_1_des,
							'prix'	=> $new_item_1_prix,
						);
						$sql = 'INSERT INTO cg_items ' . $db->sql_build_array('INSERT', $data);
						$db->sql_query($sql);
					}
					
					$new_item_2_prix	= utf8_normalize_nfc(request_var('new_item_2_prix', '', true));
					$new_item_2_des	= utf8_normalize_nfc(request_var('new_item_2_des', '', true));
					$new_item_2_ref	= utf8_normalize_nfc(request_var('new_item_2_ref', '', true));
					
					if ($new_item_2_ref)
					{
						$data = array(
							'cg'	=> $cg_id,
							'ref'	=> $new_item_2_ref,
							'des'	=> $new_item_2_des,
							'prix'	=> $new_item_2_prix,
						);
						$sql = 'INSERT INTO cg_items ' . $db->sql_build_array('INSERT', $data);
						$db->sql_query($sql);
					}
						
					$new_item_3_prix	= utf8_normalize_nfc(request_var('new_item_3_prix', '', true));
					$new_item_3_des	= utf8_normalize_nfc(request_var('new_item_3_des', '', true));
					$new_item_3_ref	= utf8_normalize_nfc(request_var('new_item_3_ref', '', true));
					
					if ($new_item_3_ref)
					{
						$data = array(
							'cg'	=> $cg_id,
							'ref'	=> $new_item_3_ref,
							'des'	=> $new_item_3_des,
							'prix'	=> $new_item_3_prix,
						);
						$sql = 'INSERT INTO cg_items ' . $db->sql_build_array('INSERT', $data);
						$db->sql_query($sql);
					}
					
					$new_item_4_prix	= utf8_normalize_nfc(request_var('new_item_4_prix', '', true));
					$new_item_4_des	= utf8_normalize_nfc(request_var('new_item_4_des', '', true));
					$new_item_4_ref	= utf8_normalize_nfc(request_var('new_item_4_ref', '', true));
					
					if ($new_item_4_ref)
					{
						$data = array(
							'cg'	=> $cg_id,
							'ref'	=> $new_item_4_ref,
							'des'	=> $new_item_4_des,
							'prix'	=> $new_item_4_prix,
						);
						$sql = 'INSERT INTO cg_items ' . $db->sql_build_array('INSERT', $data);
						$db->sql_query($sql);
					}
					
					$new_item_5_prix	= utf8_normalize_nfc(request_var('new_item_5_prix', '', true));
					$new_item_5_des	= utf8_normalize_nfc(request_var('new_item_5_des', '', true));
					$new_item_5_ref	= utf8_normalize_nfc(request_var('new_item_5_ref', '', true));
					
					if ($new_item_5_ref)
					{
						$data = array(
							'cg'	=> $cg_id,
							'ref'	=> $new_item_5_ref,
							'des'	=> $new_item_5_des,
							'prix'	=> $new_item_5_prix,
						);
						$sql = 'INSERT INTO cg_items ' . $db->sql_build_array('INSERT', $data);
						$db->sql_query($sql);
					}

					
				}
				
				$liste_cg = liste_cg(2);
				
				foreach ($liste_cg as $cg)
				{
					$template->assign_block_vars('liste_cg', array(
					'ID'		=> $cg['id'],
					'TITRE'	=> $cg['titre'],
					'STATUS'	=> (($cg['status'] == 0) ? 'Archivée' : 'En cours')
					));
				}
				
				if ($cg_id)
				{
					// detail CG
					$liste_cg = get_cg_info($cg_id);
					
					$username_orga = get_username($liste_cg['orga']);
					$username_ref = get_username($liste_cg['ref']);
					
					$template->assign_vars(array(
						'CG_ID'		=> $liste_cg['id'],
						'CG_TITRE'	=> $liste_cg['titre'],
						'CG_EDF'	=> $liste_cg['edf'],
						'CG_CG'		=> $liste_cg['cg'],
						'CG_ORGA'	=> $liste_cg['orga'],
						'CG_ORGA_USERNAME'	=> $username_orga,
						'CG_REF'	=> $liste_cg['ref'],
						'CG_REF_USERNAME'	=> $username_ref,
						'CG_SHIPPING'	=> $liste_cg['shipping'],
						'CG_PP_FEES'	=> $liste_cg['pp_fees'],
					));
				}
				
				
				// Liste des items de la CG
				$sql = 'SELECT id, cg, ref, des, prix 
						FROM cg_items 
						WHERE cg_items.cg = ' . $cg_id;
				
				$result = $db->sql_query($sql);
				$liste_items = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				foreach ($liste_items as $row)
				{
					$template->assign_block_vars('liste_items', array(
					'REF'	=> $row['ref'],
					'DES'	=> $row['des'],
					'PRIX'	=> $row['prix'],
					));
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
				$sql = 'SELECT poster_id, post_id
					FROM ' . POSTS_TABLE . ' AS p, ' . USERS_TABLE . ' AS u 
					WHERE topic_id = ' . $topic_id . ' AND p.poster_id = u.user_id';
				
				$result = $db->sql_query($sql);
				$liste_posteur = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				$template->assign_vars(array(
					'TOPIC_ID' => $topic_id,
				));
				
				$i = -1;
				foreach ($liste_posteur as $row2)
				{
				if ($i >= 1)
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
					'POSTER'		 => get_username($row2['user']),
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
				
				// Initial var setup
				$cg_id		= request_var('cg_id', '0');
				$cg_titre	= utf8_normalize_nfc(request_var('cg_titre', '', true));
				$cg_edf		= request_var('cg_edf', 0);
				$cg_cg		= request_var('cg_cg', 0);
				$cg_orga	= request_var('cg_orga', 0);
				$cg_ref		= request_var('cg_ref', 0);
				$cg_maj		= request_var('maj', 0);
				
				// Liste des cg
				$liste_cg = liste_cg(1);
				foreach ($liste_cg as $row)
				{
					$template->assign_block_vars('liste_cg', array(
						'ID'		=> $row['id'],
						'TITRE'	=> $row['titre'],
					));
				}
				
				// verif autorisation orga
				$cg_info = get_cg_info($cg_id);
				
				
				// Info cg
				$cg_courante = get_cg_info($cg_id);
				
				//liste des participants à la CG
				$sql = 'SELECT cg_liste_pp.user, '. USERS_TABLE . '.username FROM cg_liste_pp, ' . USERS_TABLE . ' 
						WHERE cg_liste_pp.cg = ' . $cg_id . ' AND cg_liste_pp.user = ' . USERS_TABLE . '.user_id ORDER BY username COLLATE utf8_general_ci';
				$result = $db->sql_query($sql);
				$liste_participants = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$update_pp = $liste_participants;
				
				if ($cg_maj == 1)
				{
					foreach ($update_pp as $participant)
					{
						$data = array(
							'charte'	=> request_var($participant['user'] . '_charte', 0),
							'pp1_ok'	=> request_var($participant['user'] . '_PP1_OK', 0),
							'pp2'		=> utf8_normalize_nfc(request_var($participant['user'] . '_PP2', '0', true)),
							'pp2_ok'	=> request_var($participant['user'] . '_PP2_OK', 0),
							'pp3'		=> utf8_normalize_nfc(request_var($participant['user'] . '_PP3', '0', true)),
							'pp3_ok'	=> request_var($participant['user'] . '_PP3_OK', 0),
						);
						
						//$debug = utf8_normalize_nfc(request_var($participant['user'] . '_PP2', '0', true));
						//echo $participant['user'] . ' ' . $participant['username'] . ' ' . $data['charte'] . ' ' . $debug . '<br>';
						
						$sql = 'UPDATE cg_liste_pp SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE cg_liste_pp.user = ' . $participant['user'];
						$db->sql_query($sql);
					}
				}
				
				$liste_pp_formate = '[titre]Liste des participants[/titre]<br>[monospace][list=1]<br>';
				
				foreach ($liste_participants as $participant)
				{
					$sql = 'SELECT * FROM cg_liste_pp WHERE cg_liste_pp.cg = ' . $cg_id . ' AND cg_liste_pp.user = ' . $participant['user'];
					$result = $db->sql_query($sql);
					$liste_pp = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					
					($liste_pp['charte']) ? $charte = 'checked' : $charte = '';
					($liste_pp['pp1_ok']) ? $PP1 = 'checked' : $PP1 = '';
					($liste_pp['pp2_ok']) ? $PP2 = 'checked' : $PP2 = '';
					($liste_pp['pp3_ok']) ? $PP3 = 'checked' : $PP3 = '';
					
					$template->assign_block_vars('liste_pp', array(
						'USER'		=> $participant['user'],
						'PSEUDO'	=> $participant['username'],
						'CHARTE'	=> $charte,
						'PP1'		=> $liste_pp['pp1'],
						'PP1_OK'	=> $PP1,
						'PP2'		=> $liste_pp['pp2'],
						'PP2_OK'	=> $PP2,
						'PP3'		=> $liste_pp['pp3'],
						'PP3_OK'	=> $PP3,
//						'PM_IMG' 	=> $participant->img('icon_contact_pm', 'SEND_PRIVATE_MESSAGE'),
						'U_PM'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=compose&amp;u=' . $participant['user']),
						'U_EMAIL'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=email&amp;u=' . $participant['user']),

					));
					
					$spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					$len_username = 20 - mb_strlen($participant['username']);
					$ll_username = substr($spacer, 0, $len_username*6);
					
					$len_pp1 = 7 - mb_strlen($liste_pp['pp1']);
					$ll_pp1 = substr($spacer, 0, $len_pp1*6);
					
					$len_pp2 = 7 - mb_strlen($liste_pp['pp2']);
					$ll_pp2 = substr($spacer, 0, $len_pp2*6);
					
					$len_pp3 = 7 - mb_strlen($liste_pp['pp3']);
					$ll_pp3 = substr($spacer, 0, $len_pp3*6);
					
					$liste_charte = ($liste_pp['charte'] == 1) ? '[vert]charte OK [/vert] - ' : '[rouge] charte OK [/rouge] - ';
					$liste_pp1 = ($liste_pp['pp1_ok'] == 1) ? '[vert]PP1 : ' . $ll_pp1 . $liste_pp['pp1'] . ' $[/vert] - ' : '[rouge]PP1 : ' . $ll_pp1 . $liste_pp['pp1'] . ' $[/rouge] - ';
					$liste_pp2 = ($liste_pp['pp2_ok'] == 1) ? '[vert]PP2 : ' . $ll_pp2 . $liste_pp['pp2'] . ' €[/vert] - ' : '[rouge]PP2 : ' . $ll_pp2 . $liste_pp['pp2'] . ' €[/rouge] - ';
					$liste_pp3 = ($liste_pp['pp3_ok'] == 1) ? '[vert]PP3 : ' . $ll_pp3 . $liste_pp['pp3'] . ' €[/vert]<br>' : '[rouge]PP3 : ' . $ll_pp3 . $liste_pp['pp3'] . ' €[/rouge]<br>';
					
					$liste_pp_formate .= '[*][b]' . $participant['username'] . '[/b]' . $ll_username . '- ' . $liste_charte . $liste_pp1 . $liste_pp2 . $liste_pp3;

				}
				
				$liste_pp_formate .= '[/list][/monospace]';
				
				$template->assign_var('LISTE_PP_FORMATE', $liste_pp_formate);
				
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
			
				// Initial var setup
				$cg_id		= request_var('cg_id', '0');
				$cg_titre	= utf8_normalize_nfc(request_var('cg_titre', '', true));
				$cg_edf		= request_var('cg_edf', 0);
				$cg_cg		= request_var('cg_cg', 0);
				$cg_orga	= request_var('cg_orga', 0);
				$cg_ref		= request_var('cg_ref', 0);
				$cg_maj		= request_var('maj', 0);
				$print		= request_var('print', 0);
				$start		= request_var('start', 0);
				
				// Liste des cg
				$liste_cg = liste_cg(1);
				foreach ($liste_cg as $row)
				{
					$template->assign_block_vars('liste_cg', array(
						'ID'		=> $row['id'],
						'TITRE'	=> $row['titre'],
					));
				}
				
				//liste des participants à la CG
				$sql = 'SELECT cg_liste_pp.user, '. USERS_TABLE . '.username FROM cg_liste_pp, ' . USERS_TABLE . ' 
						WHERE cg_liste_pp.cg = ' . $cg_id . ' AND cg_liste_pp.user = ' . USERS_TABLE . '.user_id ORDER BY username COLLATE utf8_general_ci';
				$result = $db->sql_query($sql);
				$liste_participants = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$i = 0;
				foreach ($liste_participants as $participant)
				{
				if ($print == 1)
				{
//					echo $i . ' ' . $start . ' ' . ($start + 24) . '<br>';
					if (($start <= $i) && (($start + 23) >= $i))
					{
//						echo 'bingo<br>';
						$sql = 'SELECT pf_adresse FROM phpbb_profile_fields_data WHERE user_id = ' . $participant['user'];
						$result = $db->sql_query($sql);
						$adresse = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						
						$template->assign_block_vars('liste_adresse', array(
							'USER'		=> $participant['user'],
							'PSEUDO'	=> $participant['username'],
							'ADRESSE'	=> nl2br($adresse['pf_adresse']),
						));
					}
					$i ++;
				}
				else
				{
						$sql = 'SELECT pf_adresse FROM phpbb_profile_fields_data WHERE user_id = ' . $participant['user'];
						$result = $db->sql_query($sql);
						$adresse = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						
						$template->assign_block_vars('liste_adresse', array(
							'USER'		=> $participant['user'],
							'PSEUDO'	=> $participant['username'],
							'ADRESSE'	=> nl2br($adresse['pf_adresse']),
						));
						$i ++;
				}
				}
				$template->assign_var('PRINT', $print);
			break;
			
			case 'recap_edf':
				$this->page_title = 'UCP_CG_RECAP_EDF_TITLE';
				$this->tpl_name = 'ucp_cg_recap_edf';
				
				// Initial var setup
				$cg_id	= request_var('cg_id', 0);
				$update_truc		= request_var('update_quantite', 0);
				$start_page	= request_var('start', 0);
				
				$limit_page = 10;
				$topic_id = 0;
				
				echo $topic_id . '<br>';
				
				// Liste des cg
				$sql = 'SELECT id, titre 
						FROM cg_cg 
						WHERE 1';
				
				$result = $db->sql_query($sql);
				$liste_cg = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				foreach ($liste_cg as $row)
				{
					$template->assign_block_vars('liste_cg', array(
					'ID'		=> $row['id'],
					'TITRE'	=> $row['titre'],
					));
				}
				
				// récup topic EdF de la CG
				$sql = 'SELECT edf 
						FROM cg_cg 
						WHERE id = ' . $cg_id;
				$result = $db->sql_query($sql);
				$topic = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
				
				$topic_id = $topic['edf'];
				if ($topic_id == null) $topic_id = 0;
				
				$template->assign_var('START_PAGE', $start_page);
				
				$user_modif = request_var('user', 0);
				
				if ($update_truc == 1)
				{
				//echo 'debug';
					
					// Liste des posteurs du topic
					$sql = 'SELECT poster_id, username, post_id
						FROM ' . POSTS_TABLE . ' AS p, ' . USERS_TABLE . ' AS u 
						WHERE topic_id = ' . $topic_id . ' AND p.poster_id = u.user_id LIMIT ' . $start_page . ',' . $limit_page;
					
					$result = $db->sql_query($sql);
					$liste_posteur = $db->sql_fetchrowset($result);
					
					$db->sql_freeresult($result);
					
				//echo ' debug2';
					foreach ($liste_posteur as $user_temp)
					{
				//echo ' debug3';
						$sql = 'SELECT id, ref, des, prix 
								FROM cg_items 
								WHERE cg_items.cg = 3';
						$result = $db->sql_query($sql);
						
						$liste_items = $db->sql_fetchrowset($result);
						
						$db->sql_freeresult($result);
				//echo ' debug4';
						
						$random_key_user = request_var($user_temp['poster_id'] . '_key', 0);
						$valid_form = request_var($user_temp['poster_id'] . '_' . $random_key_user . '_valid', 0);
						
						//echo 'user : ' . $user_temp['poster_id'] . ' random key : ' . $random_key_user . ' valide ? ' . $valid_form . '<br>';
						
						if ($random_key_user)
						{
						foreach ($liste_items as $item)
						{
						
				//echo ' debug5 <br>';
							$sql = 'SELECT quantite 
									FROM cg_commande 
									WHERE cg_commande.cg = 3
										AND cg_commande.user = ' . $user_temp['poster_id'] . '
										AND cg_commande.item = '. $item['id'] . '
									';
							$result = $db->sql_query($sql);
							$test = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);
							$temp_var = $user_temp['poster_id'] . '_' . $item['id'];
							//echo $temp_var;
							
							$item_quantite = request_var($user_temp['poster_id'] . '_' . $random_key_user . '_' . $item['id'], 0);
							//echo 'user : ' . $user_temp['poster_id'] . 'quantite : ' . $item_quantite . '<br>';
							if ($test != '' )
							{
								$sql = 'UPDATE cg_commande SET quantite = ' . $item_quantite . ' WHERE user = ' . $user_temp['poster_id'] . ' AND item = ' . $item['id'];
								$db->sql_query($sql);
								//echo 'debug 1 : ' . $sql . '<br>';
							}
							elseif ($item_quantite != 0)
							{
								$new_commande = array(
									'cg'	=> 3,
									'user'	=> $user_temp['poster_id'],
									'item'	=> $item['id'],
									'quantite'	=> $item_quantite,
								);
								$item_quantite = request_var($item['id'], 0);
								$sql = 'INSERT INTO cg_commande ' . $db->sql_build_array('INSERT', $new_commande);
								$db->sql_query($sql);
//								echo 'debug 2 : ' . $sql . '<br>';
							}
						}
						}
					}
				}
				
				$start_page_1 = $start_page; // +1;
				
				// Liste des posteurs du topic
				$sql = 'SELECT poster_id, username, post_id 
					FROM ' . POSTS_TABLE . ' AS p, ' . USERS_TABLE . ' AS u 
					WHERE topic_id = ' . $topic_id . ' AND p.poster_id = u.user_id LIMIT ' . $start_page_1 . ',' . $limit_page;
				
				$result = $db->sql_query($sql);
				$liste_posteur = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				$template->assign_vars(array(
					'CG_ID' => $cg_id,
				));
				
				$i = 1;
				
				foreach ($liste_posteur as $row2)
				{
//					if ($row2['poster_id'] != 3848)
//					{
						
						$sql = 'SELECT post_text, bbcode_uid, bbcode_bitfield, enable_bbcode, enable_smilies, enable_magic_url
							FROM ' . POSTS_TABLE . ' AS p 
							WHERE p.topic_id = ' . $topic_id . ' AND p.post_id = ' . $row2['post_id'];
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						
						$db->sql_freeresult($result);
						
						$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
							(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
							(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
						$text_post = generate_text_for_display($row['post_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options);
						
						$sql = 'SELECT id, ref, des, prix 
								FROM cg_items 
								WHERE cg_items.cg = 3';
						$result = $db->sql_query($sql);
						
						$liste_items = $db->sql_fetchrowset($result);
						
						$db->sql_freeresult($result);
						
						$random_key = rand(0,1000);
						
						$liste_commande = 'valide formulaire <input name="' . $row2['poster_id'] . '_key" type="checkbox" value="' . $random_key . '" checked /><br>';
						foreach ($liste_items as $item)
						{
							$template->assign_block_vars('liste_items', array(
								'REF'	=> $item['ref'],
								'DES'	=> $item['des'],
							));
							
							$sql = 'SELECT item, quantite 
									FROM cg_commande 
									WHERE cg_commande.cg = 3 AND cg_commande.user = ' . $row2['poster_id'] . ' AND cg_commande.item = ' . $item['id'];
							$result = $db->sql_query($sql);
							
							$commande = $db->sql_fetchrow($result);
							
							$db->sql_freeresult($result);
							
							$liste_commande .= '
		'. $item['ref'] . ' - ' . $item['des'] . ' : 
			<input name="' .$row2['poster_id'] . '_' . $random_key . '_' .$item['id'] . '" type="number" size="3" value="' . $commande['quantite'] . '" />
		<br>
		';
						}
						//$liste_commande .= '<input name="' . $row2['poster_id'] . '_key" type="hidden" value="' . $random_key . '" />';
						$template->assign_var('DEBUGMYSQL', $sql);
						$template->assign_block_vars('liste_posts', array(
							'POSTER'		=> $row2['username'],
							'ID' 			=> $i,
							'POSTER_POSTS'	=> $text_post,
							'COMMANDE'		=> $liste_commande
						));
						$i ++;
//					}
				}
				
				
				$sql = 'SELECT id, ref, des, prix 
						FROM cg_items 
						WHERE cg_items.cg = 3';
				$result = $db->sql_query($sql);
				
				$liste_items = $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
				
				
			break;
			
			case 'recap_cg':
			
				$this->page_title = 'UCP_CG_RECAP_CG';
				$this->tpl_name = 'ucp_cg_recap_cg';
				
				// Initial var setup
				$cg_id		= request_var('cg_id', '0');
				$cg_titre	= utf8_normalize_nfc(request_var('cg_titre', '', true));
				$cg_edf		= request_var('cg_edf', 0);
				$cg_cg		= request_var('cg_cg', 0);
				$cg_orga	= request_var('cg_orga', 0);
				$cg_ref		= request_var('cg_ref', 0);
				$cg_maj		= request_var('maj', 0);
				
				// Liste des cg en cours
				$liste_cg = liste_cg(1);
				
				foreach ($liste_cg as $cg)
				{
					$template->assign_block_vars('liste_cg_en_cours', array(
						'ID'	=> $cg['id'],
						'TITRE'	=> $cg['titre'],
						'ORGA'	=> get_username($cg['orga']),
					));
				}
				
				// Liste des cg archivées
				$liste_cg = liste_cg(0);
				
				foreach ($liste_cg as $cg)
				{
					$template->assign_block_vars('liste_cg_archive', array(
						'ID'	=> $cg['id'],
						'TITRE'	=> $cg['titre'],
						'ORGA'	=> get_username($cg['orga']),
					));
				}
				
				// detail CG
				$cg = get_cg_info($cg_id);
				
				//liste des items de la CG
				$sql = 'SELECT * FROM cg_items WHERE cg_items.cg = ' . $cg_id;
				$result = $db->sql_query($sql);
				$liste_items = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				// décompte des items
				foreach ($liste_items as $item)
				{
					$sql = 'SELECT quantite FROM cg_commande WHERE item = ' . $item['id'] . ' AND cg = ' . $cg_id;
					$result = $db->sql_query($sql);
					$total_item = 0;
					
					while ($counter = $db->sql_fetchrow($result))
					{
						$total_item = $total_item + $counter['quantite'];
					}
					$db->sql_freeresult($result);
					
					$total_cost_item = $total_item * $item['prix'];
					$template->assign_block_vars('liste_items_total', array(
						'REF'	=> $item['ref'],
						'DES'	=> $item['des'],
						'COUNT'	=> $total_item,
						'TOTAL_COST_ITEM'	=> $total_cost_item,
					));
				}
				
				//liste des participants à la CG
				$sql = 'SELECT DISTINCT cg_commande.user, '. USERS_TABLE . '.username FROM cg_commande, ' . USERS_TABLE . ' WHERE cg_commande.cg = ' . $cg_id . ' AND cg_commande.user = ' . USERS_TABLE . '.user_id ORDER BY username COLLATE utf8_general_ci';
				$result = $db->sql_query($sql);
				$liste_participants = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$total_cost_produits = '0';
				foreach ($liste_participants as $participant)
				{
					$sql = 'SELECT * FROM cg_commande WHERE cg_commande.cg = ' . $cg_id . ' AND cg_commande.user = ' . $participant['user'];
					$result = $db->sql_query($sql);
					$liste_commande = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$commande_participant = '';
					$cost_participant = '0';
					
					foreach ($liste_commande as $commande)
					{
						$sql = 'SELECT * FROM cg_items WHERE id = ' . $commande['item'];
						$result = $db->sql_query($sql);
						$item = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						$commande_participant .= $commande['quantite'] . ' - ' . $item['des'] . '<br>';
						$cost_participant += $commande['quantite'] * $item['prix'];
					}
					$fees = round((($cg['shipping'] + $cg['pp_fees']) / $cg['total']) * $cost_participant,2);
					$total_cost_participant = $cost_participant + $fees;
					
					$template->assign_block_vars('liste_commande', array(
						'PSEUDO'	=> $participant['username'],
						'COMMANDE'	=> $commande_participant,
						'COST'		=> $cost_participant,
						'FEES'		=> $fees,
						'TOTAL'		=> $total_cost_participant,
					));
					
					$pp2 = round($total_cost_participant * 0.2);
					
					$data = array(
						'cg'	=> $cg_id,
						'user'	=> $participant['user'],
						'produits'	=> $cost_participant,
						'fees'	=> $fees,
						'pp1'	=> $total_cost_participant,
						// very hugly tweak pour le pp2 Efest - avance des frais de douane
						'pp2'	=> $pp2,
					);
					
					$sql = 'SELECT user FROM cg_liste_pp WHERE user = ' . $participant['user'];
					$result = $db->sql_query($sql);
					$test = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					
					if ($test['user'] == '') // nouvelle CG
					{
						$sql = 'INSERT INTO cg_liste_pp ' . $db->sql_build_array('INSERT', $data);
					}
					else
					{
						$sql = 'UPDATE cg_liste_pp SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE cg_liste_pp.user = ' . $participant['user'];
					}
					
					$db->sql_query($sql);

					$total_cost_produits += $cost_participant;
					$total_fees += $fees;
				}
				
				// on enregistre le total de la commande
				$data = array('total' => $total_cost_produits);
				$sql = 'UPDATE cg_cg SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE cg_cg.id = ' . $cg_id;
				$db->sql_query($sql);
				
				if ($cg_id)
				{
					// on récupère le nom de l'orga
					$nom_orga = get_username($cg['orga']);
				}
				
				$total_cost_commande = $total_cost_produits + $cg['shipping'] + $cg['pp_fees'];
				
				$template->assign_var('ORGA', $nom_orga);
				$template->assign_var('TOTAL_COST_PRODUITS', $total_cost_produits);
				$template->assign_var('SHIPPING_COST', $cg['shipping']);
				$template->assign_var('PP_FEES', $cg['pp_fees']);
				$template->assign_var('TOTAL_COST_COMMANDE', $total_cost_commande);
				
			break;
		}
	}
}
?>