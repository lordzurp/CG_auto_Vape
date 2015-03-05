<?php
class mcp_cg
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
            $this->page_title = 'MCP_CG';
            $this->tpl_name = 'mcp_cg';
            break;
            
            
         case 'listes_PP':
            $this->page_title = 'MCP_CG';
            $this->tpl_name = 'mcp_cg_listes_pp';
            break;
      }

   }
}
?>