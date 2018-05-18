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

/**
 * 	\file		admin/chantier_param.php
 * 	\brief		This file is an example module setup page
 * 				Put some comments here
 */
// Dolibarr environment
$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include '../../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");
// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
dol_include_once("/easyparam/lib/easyparam.lib.php");
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

$langs->load("easyparam@easyparam");
$langs->load("admin");
// Access control
if (! $user->admin) {
    accessforbidden();
}


if (! $user->admin) accessforbidden();

// Parameters
$action = GETPOST('action', 'alpha');
$name = GETPOST('name','alpha');
// Activate const
if ($action == 'set')
{
    dolibarr_set_const($db, $name,'1','yesno',0,'',$conf->entity);
        
}


else if ($action == 'del')
{
        dolibarr_del_const($db, $name);
}
if ($action == 'setvalue' && $user->admin)
{
	$db->begin();
    $result=dolibarr_set_const($db, "PRODUCT_MAX_VISIBLE_PHOTO",GETPOST('PRODUCT_MAX_VISIBLE_PHOTO'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;

	if (! $error)
  	{
  		$db->commit();
  		setEventMessage($langs->trans("SetupSaved"));
  	}
  	else
  	{
  		$db->rollback();
		dol_print_error($db);
    }
}


/*
 *	View
 */

$form=new Form($db);

llxHeader('',$langs->trans("EasyParamSetup"));


$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre(' - '.$langs->trans("ModuleSetup"),$linkback);
print '<br>';

//$head=paypaladmin_prepare_head();

//dol_fiche_head($head, 'easyparam', '');
// Configuration header
$head = easyparamAdminPrepareHead();
dol_fiche_head($head,'product',  $langs->trans("EasyParam"),
    0,
    "easyparam@easyparam"
);


print '<br>';
print '<br>';
print '<form method="post" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="setvalue">';

print '<table class="noborder" width="100%">';

$var=true;
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("AccountParameter").'</td>';
print '<td>'.$langs->trans("Description").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print "</tr>\n";


$var=!$var;
showConf('PRODUCT_ADD_TYPE_IN_DOCUMENTS', $langs->trans("PRODUCT_ADD_TYPE_IN_DOCUMENTS"), $langs->trans("PRODUCTADDTYPEINDOCUMENTS"),$bc[$var]);
$var=!$var;
showConf('PRODUCT_DONOTSEARCH_ANYWHERE', $langs->trans("PRODUCT_DONOTSEARCH_ANYWHERE"), $langs->trans("PRODUCTDONOTSEARCHANYWHERE"),$bc[$var]);
$var=!$var;
showConf('PRODUIT_DESC_IN_LIST', $langs->trans("PRODUIT_DESC_IN_LIST"), $langs->trans("PRODUITDESCINLIST"),$bc[$var]);

$var=!$var;
showConf('PRODUIT_PDF_MERGE_PROPAL', $langs->trans("PRODUIT_PDF_MERGE_PROPAL"), $langs->trans("PRODUITPDFMERGEPROPAL"),$bc[$var]);

$var=!$var;
showConf('PRODUCT_USE_OLD_PATH_FOR_PHOTO', $langs->trans("PRODUCT_USE_OLD_PATH_FOR_PHOTO"), $langs->trans("PRODUCTUSEOLDPATHFORPHOTO"),$bc[$var]);

$var=!$var;
showConf('PRODUCT_USE_UNITS', $langs->trans("PRODUCT_USE_UNITS"), $langs->trans("PRODUCTUSEUNITS"),$bc[$var]);

$var=!$var;
showConf('MAIN_PRODUCT_DISABLE_CUSTOMCOUNTRYCODE', $langs->trans("MAIN_PRODUCT_DISABLE_CUSTOMCOUNTRYCODE"), $langs->trans("MAINPRODUCTDISABLECUSTOMCOUNTRYCODE"),$bc[$var]);
$var=!$var;
showConf('PRODUIT_MULTIPRICES_USE_VAT_PER_LEVEL', $langs->trans("PRODUIT_MULTIPRICES_USE_VAT_PER_LEVEL"), $langs->trans("PRODUITMULTIPRICESUSEVATPERLEVEL"),$bc[$var]);
$var=!$var;
$version = version_dolibarr();
if($version>=4.0)
showConf('MAIN_DIRECT_STATUS_UPDATE', $langs->trans("MAIN_DIRECT_STATUS_UPDATE"), $langs->trans("MAINDIRECTSTATUSUPDATE"),$bc[$var]);
$var=!$var;
showConf('PRODUCT_CHARGES', $langs->trans("PRODUCT_CHARGES"), $langs->trans("PRODUCTCHARGES"),$bc[$var]);


$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>PRODUCT_MAX_VISIBLE_PHOTO</td><td>'.$langs->trans("PRODUCTMAXVISIBLEPHOTO").'<td><input type="text" name="PRODUCT_MAX_VISIBLE_PHOTO" value="'.$conf->global->PRODUCT_MAX_VISIBLE_PHOTO.'" />';
print '</td></tr>';

print '</table>';

print '<br><center><input type="submit" class="button" value="'.$langs->trans("Modify").'"></center>';

print '</form>';

dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();