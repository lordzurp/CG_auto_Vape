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
            break;
      }

   }
}
?>