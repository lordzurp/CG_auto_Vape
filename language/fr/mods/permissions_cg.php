<?php
/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
    $lang = array();
}
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine


// Adding new category
$lang['permission_cat']['gestion_cg']   = 'Gestion des CG';

$lang = array_merge($lang, array(
	'acl_u_cg_ref' => array('lang' => 'Peut créer et gérer des CG', 'cat' => 'gestion_cg'),
	'acl_u_cg_orga' => array('lang' => 'Peut organiser une CG', 'cat' => 'gestion_cg'),
	'acl_u_cg' => array('lang' => 'Peut participer à une CG', 'cat' => 'gestion_cg'),
	'acl_m_cg' => array('lang' => 'Peut gérer des CG', 'cat' => 'gestion_cg'),
	'acl_a_cg' => array('lang' => 'Peut gérer des CG', 'cat' => 'gestion_cg'),
));
?>