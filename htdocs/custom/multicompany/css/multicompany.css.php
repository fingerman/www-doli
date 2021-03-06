<?php
/* Copyright (C) 2011-2016 Regis Houssin  <regis.houssin@inodbox.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 *		\file       /multicompany/css/multicompany.css.php
 *		\brief      Fichier de style CSS complementaire du module Multi-Company
 */

//if (! defined('NOREQUIREUSER')) define('NOREQUIREUSER','1');	// Not disabled cause need to load personalized language
//if (! defined('NOREQUIREDB'))   define('NOREQUIREDB','1');	// Not disabled to increase speed. Language code is found on url.
if (! defined('NOREQUIRESOC'))    define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN')) define('NOREQUIRETRAN','1');	// Not disabled cause need to do translations
if (! defined('NOCSRFCHECK'))     define('NOCSRFCHECK',1);
if (! defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL',1);
if (! defined('NOLOGIN'))         define('NOLOGIN',1);
if (! defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);
if (! defined('NOREQUIREHTML'))   define('NOREQUIREHTML',1);
if (! defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX','1');
if (! defined('NOREQUIREHOOK'))  define('NOREQUIREHOOK','1');  // Disable "main.inc.php" hooks

session_cache_limiter(FALSE);

$res=@include '../../main.inc.php';					// For "root" directory
if (! $res && file_exists($_SERVER['DOCUMENT_ROOT']."/main.inc.php"))
	$res=@include($_SERVER['DOCUMENT_ROOT']."/main.inc.php"); // Use on dev env only
if (! $res) $res=@include '../../../main.inc.php';	// For "custom" directory


// Define css type
header('Content-type: text/css');
// Important: Following code is to avoid page request by browser and PHP CPU at
// each Dolibarr page access.
if (empty($dolibarr_nocache)) header('Cache-Control: max-age=3600, public, must-revalidate');
else header('Cache-Control: no-cache');
?>

.span-icon-multicompany {
	background-image: url(<?php echo dol_buildpath('/multicompany/img/object_multicompany.png',1); ?>);
	background-repeat: no-repeat;
	background-position: left center;
}

img.entity {
	cursor:pointer;
}

div.entityBox {
	margin-top: 10px;
}

div#login_right {
	min-width:0!important;
}

#entity {
	width: 135px;
}

div.mc-upgrade-alert {
  color: #302020;
  padding: 0.5em 0.5em 0.5em 0.5em;
  margin: 0.5em 1.5em 0.5em 1.5em;
  border: 1px solid #e0d0b0;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  background: #EFCFCF;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}