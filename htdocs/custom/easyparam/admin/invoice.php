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
dol_fiche_head($head,'invoice',  $langs->trans("EasyParam"),
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
showConf('INVOICE_CAN_ALWAYS_BE_REMOVED', $langs->trans("INVOICE_CAN_ALWAYS_BE_REMOVED"), $langs->trans("INVOICECANALWAYSBEREMOVED"),$bc[$var]);
$var=!$var;
showConf("INVOICE_CAN_NEVER_BE_REMOVED", $langs->trans("INVOICE_CAN_NEVER_BE_REMOVED"), $langs->trans("INVOICECANNEVERBEREMOVED"),$bc[$var]);
$var=!$var;
showConf("INVOICE_POSITIVE_CREDIT_NOTE", $langs->trans("INVOICE_POSITIVE_CREDIT_NOTE"), $langs->trans("INVOICEPOSITIVECREDITNOTE"),$bc[$var]);
$var=!$var;
showConf('FACTURE_CHANGE_THIRDPARTY', $langs->trans("FACTURE_CHANGE_THIRDPARTY"), $langs->trans("FACTURECHANGETHIRDPARTY"),$bc[$var]);
$var=!$var;
showConf("FACTURE_USE_PROFORMAT", $langs->trans("FACTURE_USE_PROFORMAT"), $langs->trans("FACTUREUSEPROFORMAT"),$bc[$var]);
$var=!$var;
showConf('FACTURE_DEPOSITS_ARE_JUST_PAYMENTS', $langs->trans("FACTURE_DEPOSITS_ARE_JUST_PAYMENTS"), $langs->trans("FACTUREDEPOSITSAREJUSTPAYMENTS"),$bc[$var]);
$var=!$var;
showConf("FACTURE_SENDBYEMAIL_FOR_ALL_STATUS", $langs->trans("FACTURE_SENDBYEMAIL_FOR_ALL_STATUS"), $langs->trans("FACTURESENDBYEMAILFORALLSTATUS"),$bc[$var]);
$var=!$var;
showConf("MAIN_GENERATE_DOCUMENTS_WITHOUT_VAT", $langs->trans("MAIN_GENERATE_DOCUMENTS_WITHOUT_VAT"), $langs->trans("MAINGENERATEDOCUMENTSWITHOUTVAT"),$bc[$var]);
$var=!$var;
$version = version_dolibarr();
if($version>4)
    showConf('INVOICE_AUTO_FILLJS', $langs->trans("INVOICE_AUTO_FILLJS"), $langs->trans("AUTOFILLJS"),$bc[$var]);
else
    showConf('FAC_AUTO_FILLJS', $langs->trans("FAC_AUTO_FILLJS"), $langs->trans("AUTOFILLJS"),$bc[$var]);
$var=!$var;
if($version==3.9)
showConf("INVOICE_CREDIT_NOTE_STANDALONE", $langs->trans("INVOICE_CREDIT_NOTE_STANDALONE"), $langs->trans("INVOICECREDITNOTESTANDALONE"),$bc[$var]);
$var=!$var;
showConf("INVOICE_USE_SITUATION", $langs->trans("INVOICE_USE_SITUATION"), $langs->trans("INVOICEUSESITUATION"),$bc[$var]);
$var=!$var;
showConf("WORKFLOW_BILL_ON_SHIPMENT", $langs->trans("WORKFLOW_BILL_ON_SHIPMENT"), $langs->trans("WORKFLOWBILLONSHIPMENT"),$bc[$var]);
$var=!$var;
showConf("INVOICE_DISABLE_DEPOSIT", $langs->trans("INVOICE_DISABLE_DEPOSIT"), $langs->trans("INVOICEDISABLEDEPOSIT"),$bc[$var]);
$var=!$var;
showConf("INVOICE_DISABLE_REPLACEMENT", $langs->trans("INVOICE_DISABLE_REPLACEMENT"), $langs->trans("INVOICEDISABLEREPLACEMENT"),$bc[$var]);
$var=!$var;
showConf("INVOICE_DISABLE_CREDIT_NOTE", $langs->trans("INVOICE_DISABLE_CREDIT_NOTE"), $langs->trans("INVOICEDISABLECREDITNOTE"),$bc[$var]);

print '</table>';




dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();
