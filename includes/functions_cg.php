<?php
/**
*
* @package phpBB3
* @version $Id$
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

function liste_cg($status = 0)
{
	global $db, $auth, $user, $template;
	global $phpbb_root_path, $phpEx, $config;

	$sql = 'SELECT * FROM cg_cg ' . (($status == 2) ? '' : 'WHERE status = ' . $status);
	$result = $db->sql_query($sql);
	$liste_cg = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $liste_cg;
}

function get_username($user_id)
{
	global $db, $auth, $user, $template;
	global $phpbb_root_path, $phpEx, $config;

	$sql = 'SELECT username FROM ' . USERS_TABLE . ' WHERE ' . USERS_TABLE . '.user_id = ' . $user_id;
	$result = $db->sql_query($sql);
	$username = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $username['username'];
}

function get_cg_info($id)
{
	global $db, $auth, $user, $template;
	global $phpbb_root_path, $phpEx, $config;

	$sql = 'SELECT * FROM cg_cg WHERE id = ' . $id;
	$result = $db->sql_query($sql);
	$cg = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $cg;
}
?>