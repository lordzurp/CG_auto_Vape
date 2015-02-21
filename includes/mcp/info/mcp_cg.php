<?php
class mcp_cg_info
{
	function module()
	{
		return array(
			'filename'		=> 'mcp_cg',
			'title'			=> 'MCP_CG',
			'version'		=> '0.1',
			'modes'			=> array(
				'index'		=> array('title' => 'UCP_CG_INDEX_TITLE', 'auth' => 'acl_m_', 'cat' => array('UCP_CG')),
				'listes_PP'	=> array('title' => 'UCP_CG_LISTE_PP_TITLE', 'auth' => 'acl_m_', 'cat' => array('UCP_CG')),
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