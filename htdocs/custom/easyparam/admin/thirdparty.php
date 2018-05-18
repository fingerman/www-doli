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
/*	$db->begin();
    $result=dolibarr_set_const($db, "MAIN_OPTIMIZEFORTEXTBROWSER",GETPOST('MAIN_OPTIMIZEFORTEXTBROWSER'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "THEME_ELDY_DISABLE_IMAGE",GETPOST('THEME_ELDY_DISABLE_IMAGE'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_MENU_HIDE_UNAUTHORIZED",GETPOST('MAIN_MENU_HIDE_UNAUTHORIZED'),'chaine',0,'',$conf->entity);
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
    }*/
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
dol_fiche_head($head,'',  $langs->trans("EasyParam"),
    0,
    "easyparam@easyparam"
);


print '<br>';

print '<table class="noborder" width="100%">';

$var=true;
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("AccountParameter").'</td>';
print '<td>'.$langs->trans("Description").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print "</tr>\n";


$var=!$var;
showConf('SOCIETE_DISABLE_CUSTOMERS', $langs->trans("SOCIETE_DISABLE_CUSTOMERS"), $langs->trans("SOCIETEDISABLECUSTOMERS"),$bc[$var]);
$var=!$var;
showConf("SOCIETE_DISABLE_PROSPECTS", $langs->trans("SOCIETE_DISABLE_PROSPECTS"), $langs->trans("SOCIETEDISABLEPROSPECTS"),$bc[$var]);
$var=!$var;
showConf("THIRDPARTY_NOTCUSTOMERPROSPECT_BY_DEFAULT", $langs->trans("THIRDPARTY_NOTCUSTOMERPROSPECT_BY_DEFAULT"), $langs->trans("THIRDPARTYNOTCUSTOMERPROSPECTBYDEFAULT"),$bc[$var]);
$var=!$var;
showConf('THIRDPARTY_NOTSUPPLIER_BY_DEFAULT', $langs->trans("THIRDPARTY_NOTSUPPLIER_BY_DEFAULT"), $langs->trans("THIRDPARTYNOTSUPPLIERBYDEFAULT"),$bc[$var]);
$var=!$var;
$version = version_dolibarr();
if($version>=5.0)
showConf("THIRDPARTY_DEFAULT_CREATE_CONTACT", $langs->trans("THIRDPARTY_DEFAULT_CREATE_CONTACT"), $langs->trans("THIRDPARTYDEFAULTCREATECONTACT"),$bc[$var]);
else
showConf("MAIN_THIRDPARTY_CREATION_INDIVIDUAL", $langs->trans("MAIN_THIRDPARTY_CREATION_INDIVIDUAL"), $langs->trans("THIRDPARTYDEFAULTCREATECONTACT"),$bc[$var]);
$var=!$var;
showConf("SOCIETE_SORT_ON_TYPEENT", $langs->trans("SOCIETE_SORT_ON_TYPEENT"), $langs->trans("SOCIETESORTONTYPEENT"),$bc[$var]);
$var=!$var;
showConf("SOCIETE_DISABLE_STATE", $langs->trans("SOCIETE_DISABLE_STATE"), $langs->trans("SOCIETEDISABLESTATE"),$bc[$var]);
$var=!$var;
showConf("THIRDPARTY_DEFAULT_USEVAT", $langs->trans("THIRDPARTY_DEFAULT_USEVAT"), $langs->trans("THIRDPARTYDEFAULTUSEVAT"),$bc[$var]);
$var=!$var;
showConf("THIRDPARTY_DEFAULT_USELOCALTAX1", $langs->trans("THIRDPARTY_DEFAULT_USELOCALTAX1"), $langs->trans("THIRDPARTYDEFAULTUSELOCALTAX1"),$bc[$var]);
$var=!$var;
showConf("THIRDPARTY_DEFAULT_USELOCALTAX2", $langs->trans("THIRDPARTY_DEFAULT_USELOCALTAX2"), $langs->trans("THIRDPARTYDEFAULTUSELOCALTAX2"),$bc[$var]);
$var=!$var;
showConf("THIRDPARTY_INCLUDE_PARENT_IN_LINKTO", $langs->trans("THIRDPARTY_INCLUDE_PARENT_IN_LINKTO"), $langs->trans("THIRDPARTYINCLUDEPARENTINLINKTO"),$bc[$var]);
$var=!$var;
showConf("THIRDPARTY_INCLUDE_PROJECT_THIRDPARY_IN_LINKTO", $langs->trans("THIRDPARTY_INCLUDE_PROJECT_THIRDPARY_IN_LINKTO"), $langs->trans("THIRDPARTYINCLUDEPROJECTTHIRDPARYINLINKTO"),$bc[$var]);

print '</table>';




dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();
