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

$lang = array_merge($lang, array(
	'UCP_CG'						=> 'Index CG',
	'UCP_CG_INDEX_TITLE'			=> 'Index CG',
	'UCP_CG_CREATE_CG_TITLE'		=> 'Gestion CG',
	'UCP_CG_RECAP_CG_TITLE'			=> 'Récap CG',
	'UCP_CG_PARSE_EDF_TITLE'		=> 'Suivi EdF',
	'UCP_CG_INSCRIPTION_EDF_TITLE'	=> 'Inscription EdF',
	'UCP_CG_LISTE_EDF_TITLE'		=> 'listing EdF',
	'UCP_CG_LISTE_PP_TITLE'			=> 'listing PP',
	'UCP_CG_GEN_ADRESSE_TITLE'		=> 'Générateur d\'étiquettes',
	'UCP_CG_RECAP_EDF_TITLE'		=> 'Récap EdF',
));

?>
