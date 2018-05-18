<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2016 INOVEA CONSEIL <info@inovea-conseil.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

class modEasyParam extends DolibarrModules
{

    /**
     * 	Constructor. Define names, constants, directories, boxes, permissions
     *
     * 	@param	DoliDB		$db	Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;
        $this->numero = 432403;
        $this->rights_class = 'easyparam';
        $this->family = "Inovea Conseil";
        $this->name = "easyparam";
        $this->description = "Module432403Desc";
        $this->descriptionlong = "Module432403Long";
        $this->version = '1.1';
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->editor_name = 'Inovea Conseil';
        $this->editor_url = 'https://www.inovea-conseil.com';
        $this->special = 0;
        $this->picto = 'easyparam@easyparam'; // mypicto@mymodule
        $this->module_parts = array();
        $this->dirs = array();
        $this->config_page_url = array("global.php@easyparam");
        $this->depends = array();
        $this->requiredby = array();
        $this->phpmin = array(5, 3);
        $this->need_dolibarr_version = array(3, 6);
        $this->langfiles = array("easyparam@easyparam");
        $this->const = array();
        $this->tabs = array();         
        if (! isset($conf->easyparam->enabled)) {
            $conf->easyparam=new stdClass();
            $conf->easyparam->enabled = 0;
        }
        $this->dictionnaries = array();
        $this->boxes = array();
      
        $this->rights = array();  // Permission array used by this module
        
        $r++;

        $r = 0;

    }

    /**
     * Function called when module is enabled.
     * The init function add constants, boxes, permissions and menus
     * (defined in constructor) into Dolibarr database.
     * It also creates data directories
     *
     * 	@param		string	$options	Options when enabling module ('', 'noboxes')
     * 	@return		int					1 if OK, 0 if KO
     */
    public function init($options = '')
    {
       
        $result = $this->loadTables();
        
        
        return $this->_init($sql, $options);
    }

    /**
     * Function called when module is disabled.
     * Remove from database constants, boxes and permissions from Dolibarr database.
     * Data directories are not deleted
     *
     * 	@param		string	$options	Options when enabling module ('', 'noboxes')
     * 	@return		int					1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();
        return $this->_remove($sql, $options);
    }

    /**
     * Create tables, keys and data required by module
     * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
     * and create data commands must be stored in directory /mymodule/sql/
     * This function is called by this->init
     *
     * 	@return		int		<=0 if KO, >0 if OK
     */
    private function loadTables()
    {
        return $this->_load_tables('/easyparam/sql/');
    }
}
