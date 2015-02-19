<?php
class ucp_cg_info
{
    function module()
    {
        return array(
            'filename'    => 'ucp_cg',
            'title'        => 'UCP_CG',
            'version'    => '1.2.3',
            'modes'        => array(
                'index'        => array('title' => 'UCP_CG_INDEX_TITLE', 'auth' => 'acl_u_', 'cat' => array('')),
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