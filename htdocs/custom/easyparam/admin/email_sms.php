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
    $result=dolibarr_set_const($db, "MAIN_MAIL_AUTOCOPY_PROPOSAL_TO",GETPOST('MAIN_MAIL_AUTOCOPY_PROPOSAL_TO'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_MAIL_AUTOCOPY_ORDER_TO",GETPOST('MAIN_MAIL_AUTOCOPY_ORDER_TO'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_MAIL_AUTOCOPY_INVOICE_TO",GETPOST('MAIN_MAIL_AUTOCOPY_INVOICE_TO'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_EMAIL_ADD_TRACK_ID",GETPOST('MAIN_EMAIL_ADD_TRACK_ID'),'chaine',0,'',$conf->entity);
     if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIL_MAX_NB_OF_RECIPIENTS_IN_SAME_EMAIL",GETPOST('MAIL_MAX_NB_OF_RECIPIENTS_IN_SAME_EMAIL'),'chaine',0,'',$conf->entity);
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
dol_fiche_head($head,'email_sms',  $langs->trans("EasyParam"),
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
showConf('MAIN_MAIL_DEBUG', $langs->trans("MAIN_MAIL_DEBUG"), $langs->trans("MAINMAILDEBUG"),$bc[$var]);
$var=!$var;
showConf("MAIN_SMS_DEBUG", $langs->trans("MAIN_SMS_DEBUG"), $langs->trans("MAINSMSDEBUG"),$bc[$var]);
$var=!$var;
showConf("MAIN_MAIL_ALLOW_SENDMAIL_F", $langs->trans("MAIN_MAIL_ALLOW_SENDMAIL_F"), $langs->trans("MAINMAILALLOWSENDMAILF"),$bc[$var]);
$var=!$var;
showConf('MAIN_MAIL_SENDMAIL_FORCE_BA', $langs->trans("MAIN_MAIL_SENDMAIL_FORCE_BA"), $langs->trans("MAINMAILSENDMAILFORCEBA"),$bc[$var]);
$var=!$var;
showConf("MAIN_MAIL_NO_FULL_EMAIL", $langs->trans("MAIN_MAIL_NO_FULL_EMAIL"), $langs->trans("MAINMAILNOFULLEMAIL"),$bc[$var]);
$var=!$var;
showConf('MAIN_FIX_FOR_BUGGED_MTA', $langs->trans("MAIN_FIX_FOR_BUGGED_MTA"), $langs->trans("MAINFIXFORBUGGEDMTA"),$bc[$var]);
$var=!$var;
showConf("MAIN_MAIL_DO_NOT_USE_SIGN", $langs->trans("MAIN_MAIL_DO_NOT_USE_SIGN"), $langs->trans("MAINMAILDONOTUSESIGN"),$bc[$var]);
$var=!$var;
showConf("MAIL_FORCE_DELIVERY_RECEIPT_INVOICE", $langs->trans("MAIL_FORCE_DELIVERY_RECEIPT_INVOICE"), $langs->trans("MAILFORCEDELIVERYRECEIPT"),$bc[$var]);
$var=!$var;
showConf("MAIL_FORCE_DELIVERY_RECEIPT_ORDER", $langs->trans("MAIL_FORCE_DELIVERY_RECEIPT_ORDER"), $langs->trans("MAILFORCEDELIVERYRECEIPT"),$bc[$var]);
$var=!$var;
showConf("MAIL_FORCE_DELIVERY_RECEIPT_PROPAL", $langs->trans("MAIL_FORCE_DELIVERY_RECEIPT_PROPAL"), $langs->trans("MAILFORCEDELIVERYRECEIPT"),$bc[$var]);
$var=!$var;
showConf("MAIN_EMAIL_USECCC", $langs->trans("MAIN_EMAIL_USECCC"), $langs->trans("MAINEMAILUSECCC"),$bc[$var]);
$var=!$var;
showConf("MAIN_MAIL_FORCE_CONTENT_TYPE_TO_HTML", $langs->trans("MAIN_MAIL_FORCE_CONTENT_TYPE_TO_HTML"), $langs->trans("MAINMAILFORCECONTENTTYPETOHTML"),$bc[$var]);
$var=!$var;
showConf("MAIN_MAIL_ADD_INLINE_IMAGES_IF_IN_MEDIAS", $langs->trans("MAIN_MAIL_ADD_INLINE_IMAGES_IF_IN_MEDIAS"), $langs->trans("MAINMAILADDINLINEIMAGESIFINMEDIAS"),$bc[$var]);
$var=!$var;
$version = version_dolibarr();
if(version_dolibarr()>=5.0)
showConf("MAIN_MAIL_USE_MULTI_PART", $langs->trans("MAIN_MAIL_USE_MULTI_PART"), $langs->trans("MAINMAILUSEMULTIPART"),$bc[$var]);



$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_MAIL_AUTOCOPY_PROPOSAL_TO</td><td>'.$langs->trans("MAINMAILAUTOCOPYTO").'<td><input type="text" name="MAIN_MAIL_AUTOCOPY_PROPOSAL_TO" value="'.$conf->global->MAIN_MAIL_AUTOCOPY_PROPOSAL_TO.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_MAIL_AUTOCOPY_ORDER_TO</td><td>'.$langs->trans("MAINMAILAUTOCOPYTO").'<td><input type="text" name="MAIN_MAIL_AUTOCOPY_ORDER_TO" value="'.$conf->global->MAIN_MAIL_AUTOCOPY_ORDER_TO.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_MAIL_AUTOCOPY_INVOICE_TO</td><td>'.$langs->trans("MAINMAILAUTOCOPYTO").'<td><input type="text" name="MAIN_MAIL_AUTOCOPY_INVOICE_TO" value="'.$conf->global->MAIN_MAIL_AUTOCOPY_INVOICE_TO.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_EMAIL_ADD_TRACK_ID</td><td>'.$langs->trans("MAINEMAILADDTRACKID").'<td><input type="text" name="MAIN_EMAIL_ADD_TRACK_ID" value="'.$conf->global->MAIN_EMAIL_ADD_TRACK_ID.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIL_MAX_NB_OF_RECIPIENTS_IN_SAME_EMAIL</td><td>'.$langs->trans("MAILMAXNBOFRECIPIENTSINSAMEEMAIL").'<td><input type="text" name="MAIL_MAX_NB_OF_RECIPIENTS_IN_SAME_EMAIL" value="'.$conf->global->MAIL_MAX_NB_OF_RECIPIENTS_IN_SAME_EMAIL.'" />';
print '</td></tr>';
print '</table>';




dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();
