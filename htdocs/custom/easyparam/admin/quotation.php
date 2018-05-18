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
showConf('PROPAL_CLONE_ON_CREATE_PAGE', $langs->trans("PROPAL_CLONE_ON_CREATE_PAGE"), $langs->trans("PROPALCLONEONCREATEPAGE"),$bc[$var]);
$var=!$var;
showConf("MAIN_PROPAL_CHOOSE_ODT_DOCUMENT", $langs->trans("MAIN_PROPAL_CHOOSE_ODT_DOCUMENT"), $langs->trans("MAINPROPALCHOOSEODTDOCUMENT"),$bc[$var]);
$var=!$var;
showConf("MAIN_GENERATE_PROPOSALS_WITH_PICTURE", $langs->trans("MAIN_GENERATE_PROPOSALS_WITH_PICTURE"), $langs->trans("MAINGENERATEPROPOSALSWITHPICTURE"),$bc[$var]);
$var=!$var;
showConf('WORKFLOW_PROPAL_CAN_CLASSIFY_BILLED_WITHOUT_INVOICES', $langs->trans("WORKFLOW_PROPAL_CAN_CLASSIFY_BILLED_WITHOUT_INVOICES"), $langs->trans("WORKFLOWPROPALCANCLASSIFYBILLEDWITHOUTINVOICES"),$bc[$var]);
$var=!$var;
showConf("PRODUIT_PDF_MERGE_PROPAL", $langs->trans("PRODUIT_PDF_MERGE_PROPAL"), $langs->trans("PRODUITPDFMERGEPROPAL"),$bc[$var]);
$var=!$var;
showConf("PROPAL_DISABLE_SIGNATURE", $langs->trans("PROPAL_DISABLE_SIGNATURE"), $langs->trans("PROPALDISABLESIGNATURE"),$bc[$var]);

print '</table>';




dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();
