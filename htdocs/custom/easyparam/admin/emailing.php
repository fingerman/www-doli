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
    $result=dolibarr_set_const($db, "MAILING_PREFIX_FOR_EMAIL_ID",GETPOST('MAILING_PREFIX_FOR_EMAIL_ID'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
     $result=dolibarr_set_const($db, "MAILING_SMTP_SETUP_EMAILS_FOR_QUESTIONS",GETPOST('MAILING_SMTP_SETUP_EMAILS_FOR_QUESTIONS'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
     $result=dolibarr_set_const($db, "MAILING_LIMIT_WARNING_PHPMAIL",GETPOST('MAILING_LIMIT_WARNING_PHPMAIL'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
     $result=dolibarr_set_const($db, "MAILING_LIMIT_WARNING_NOPHPMAIL",GETPOST('MAILING_LIMIT_WARNING_NOPHPMAIL'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
     $result=dolibarr_set_const($db, "MAILING_LIMIT_SENDBYWEB",GETPOST('MAILING_LIMIT_SENDBYWEB'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAILING_LIMIT_SENDBYCLI",GETPOST('MAILING_LIMIT_SENDBYCLI'),'chaine',0,'',$conf->entity);
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
showConf('MAILING_NO_USING_PHPMAIL', $langs->trans("MAILING_NO_USING_PHPMAIL"), $langs->trans("MAILINGNOUSINGPHPMAIL"),$bc[$var]);
$var=!$var;
showConf('EMAILING_USE_ADVANCED_SELECTOR', $langs->trans("EMAILING_USE_ADVANCED_SELECTOR"), $langs->trans("EMAILINGUSEADVANCEDSELECTOR"),$bc[$var]);


$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAILING_PREFIX_FOR_EMAIL_ID</td><td>'.$langs->trans("MAILINGPREFIXFOREMAILID").'<td><input type="text" name="MAILING_PREFIX_FOR_EMAIL_ID" value="'.$conf->global->MAILING_PREFIX_FOR_EMAIL_ID.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAILING_SMTP_SETUP_EMAILS_FOR_QUESTIONS</td><td>'.$langs->trans("MAILINGSMTPSETUPEMAILSFORQUESTIONS").'<td><input type="text" name="MAILING_SMTP_SETUP_EMAILS_FOR_QUESTIONS" value="'.$conf->global->MAILING_SMTP_SETUP_EMAILS_FOR_QUESTIONS.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAILING_LIMIT_WARNING_PHPMAIL</td><td>'.$langs->trans("MAILINGLIMITWARNINGPHPMAIL").'<td><input type="text" name="MAILING_LIMIT_WARNING_PHPMAIL" value="'.$conf->global->MAILING_LIMIT_WARNING_PHPMAIL.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAILING_LIMIT_WARNING_NOPHPMAIL</td><td>'.$langs->trans("MAILINGLIMITWARNINGNOPHPMAIL").'<td><input type="text" name="MAILING_LIMIT_WARNING_NOPHPMAIL" value="'.$conf->global->MAILING_LIMIT_WARNING_NOPHPMAIL.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAILING_LIMIT_SENDBYWEB</td><td>'.$langs->trans("MAILINGLIMITSENDBYWEB").'<td><input type="text" name="MAILING_LIMIT_SENDBYWEB" value="'.$conf->global->MAILING_LIMIT_SENDBYWEB.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAILING_LIMIT_SENDBYCLI</td><td>'.$langs->trans("MAILINGLIMITSENDBYCLI").'<td><input type="text" name="MAILING_LIMIT_SENDBYCLI" value="'.$conf->global->MAILING_LIMIT_SENDBYCLI.'" />';
print '</td></tr>';

print '</table>';

print '<br><center><input type="submit" class="button" value="'.$langs->trans("Modify").'"></center>';

print '</form>';

dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();