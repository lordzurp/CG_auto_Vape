<?php
class ucp_cg_info
{
	function module()
	{
		return array(
			'filename'		=> 'ucp_cg',
			'title'			=> 'UCP_CG',
			'version'		=> '0.1',
			'modes'			=> array(
				'index'				=> array('title' => 'UCP_CG_INDEX_TITLE', 'auth' => 'acl_u_cg', 'cat' => array('UCP_CG')),
				'listes_PP'			=> array('title' => 'UCP_CG_LISTE_PP_TITLE', 'auth' => 'acl_u_cg', 'cat' => array('UCP_CG')),
				'liste_edf'			=> array('title' => 'UCP_CG_LISTE_EDF_TITLE', 'auth' => 'acl_u_cg', 'cat' => array('UCP_CG')),
				'inscription_edf'	=> array('title' => 'UCP_CG_INSCRIPTION_EDF_TITLE', 'auth' => 'acl_u_cg', 'cat' => array('UCP_CG')),
				'gen_adresse'		=> array('title' => 'UCP_CG_GEN_ADRESSE_TITLE', 'auth' => 'acl_u_cg', 'cat' => array('UCP_CG')),
				'recap_edf'		=> array('title' => 'UCP_CG_RECAP_EDF_TITLE', 'auth' => 'acl_u_cg', 'cat' => array('UCP_CG')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
?>