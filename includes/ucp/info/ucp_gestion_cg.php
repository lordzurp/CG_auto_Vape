<?php
class ucp_cg_info
{
    function module()
    {
        return array(
            'filename'    => 'ucp_gestion_cg',
            'title'        => 'Gestion CG',
            'version'    => '0.1',
            'modes'        => array(
                'index'        => array('title' => 'Gestion CG', 'auth' => 'acl_u_cg', 'cat' => array('')),
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