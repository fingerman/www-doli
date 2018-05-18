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
    $result=dolibarr_set_const($db, "MAIN_APPLICATION_TITLE",GETPOST('MAIN_APPLICATION_TITLE'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_HELPCENTER_LINKTOUSE",GETPOST('MAIN_HELPCENTER_LINKTOUSE'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_MODULES_FOR_EXTERNAL",GETPOST('MAIN_MODULES_FOR_EXTERNAL'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_DOL_SCRIPTS_ROOT",GETPOST('MAIN_DOL_SCRIPTS_ROOT'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_FORCELANGDIR",GETPOST('MAIN_FORCELANGDIR'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_HTML_TITLE",GETPOST('MAIN_HTML_TITLE'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_LANDING_PAGE",GETPOST('MAIN_LANDING_PAGE'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_WEIGHT_DEFAULT_ROUND",GETPOST('MAIN_WEIGHT_DEFAULT_ROUND'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_WEIGHT_DEFAULT_UNIT",GETPOST('MAIN_WEIGHT_DEFAULT_UNIT'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_VOLUME_DEFAULT_ROUND",GETPOST('MAIN_VOLUME_DEFAULT_ROUND'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_VOLUME_DEFAULT_UNIT",GETPOST('MAIN_VOLUME_DEFAULT_UNIT'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_DEFAULT_PAYMENT_TERM_ID",GETPOST('MAIN_DEFAULT_PAYMENT_TERM_ID'),'chaine',0,'',$conf->entity);
    if (! $result > 0) $error++;
    $result=dolibarr_set_const($db, "MAIN_FILESYSTEM_ENCODING",GETPOST('MAIN_FILESYSTEM_ENCODING'),'chaine',0,'',$conf->entity);
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
dol_fiche_head($head,'global',  $langs->trans("EasyParam"),
    0,
    "easyparam@easyparam"
);


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
showConf('MAIN_AUTOFILL_DATE', $langs->trans("MAIN_AUTOFILL_DATE"), $langs->trans("MAINAUTOFILLDATE"),$bc[$var]);
$var=!$var;
showConf("MAIN_DISABLE_FULL_SCANLIST", $langs->trans("MAIN_DISABLE_FULL_SCANLIST"), $langs->trans("MAINDISABLEFULLSCANLIST"),$bc[$var]);
$var=!$var;
showConf("MAIN_FIRST_TO_UPPER", $langs->trans("MAIN_FIRST_TO_UPPER"), $langs->trans("MAINFIRSTTOUPPER"),$bc[$var]);
$var=!$var;
if(version_dolibarr()>3.9)
showConf("MAIN_ENABLE_LOG_TO_HTML", $langs->trans("MAIN_ENABLE_LOG_TO_HTML"), $langs->trans("MAINENABLELOGTOHTML"),$bc[$var]);
else
showConf("MAIN_LOGTOHTML", $langs->trans("MAIN_LOGTOHTML"), $langs->trans("MAINENABLELOGTOHTML"),$bc[$var]);
    
$var=!$var;
showConf("MAIN_USE_HOURMIN_IN_DATE_RANGE", $langs->trans("MAIN_USE_HOURMIN_IN_DATE_RANGE"), $langs->trans("MAINUSEHOURMININDATERANGE"),$bc[$var]);
$var=!$var;
showConf("MAIN_REMOVE_INSTALL_WARNING", $langs->trans("MAIN_REMOVE_INSTALL_WARNING"), $langs->trans("MAINREMOVEINSTALLWARNING"),$bc[$var]);
$var=!$var;
showConf("MAIN_OPTIMIZE_SPEED", $langs->trans("MAIN_OPTIMIZE_SPEED"), $langs->trans("MAINOPTIMIZESPEED"),$bc[$var]);
$var=!$var;
showConf("MAIN_REPEATCONTACTONEACHTAB", $langs->trans("MAIN_REPEATCONTACTONEACHTAB"), $langs->trans("MAINREPEATCONTACTONEACHTAB"),$bc[$var]);
$var=!$var;
showConf("MAIN_DISABLE_JQUERY_JNOTIFY", $langs->trans("MAIN_DISABLE_JQUERY_JNOTIFY"), $langs->trans("MAINDISABLEJQUERYJNOTIFY"),$bc[$var]);
$var=!$var;
showConf("MAIN_USE_JQUERY_JEDITABLE", $langs->trans("MAIN_USE_JQUERY_JEDITABLE"), $langs->trans("MAINUSEJQUERYJEDITABLE"),$bc[$var]);
$var=!$var;
showConf("MAIN_USE_JQUERY_MULTISELECT", $langs->trans("MAIN_USE_JQUERY_MULTISELECT"), $langs->trans("MAINUSEJQUERYMULTISELECT"),$bc[$var]);
$var=!$var;
showConf("MAIN_PRODUCT_DISABLE_CUSTOMCOUNTRYCODE", $langs->trans("MAIN_PRODUCT_DISABLE_CUSTOMCOUNTRYCODE"), $langs->trans("MAINPRODUCTDISABLECUSTOMCOUNTRYCODE"),$bc[$var]);
$var=!$var;
showConf("MAIN_SHOW_TUNING_INFO", $langs->trans("MAIN_SHOW_TUNING_INFO"), $langs->trans("MAINSHOWTUNINGINFO"),$bc[$var]);
$var=!$var;
showConf("MAIN_MIN_NB_ENABLED_MODULE_FOR_WARNING", $langs->trans("MAIN_MIN_NB_ENABLED_MODULE_FOR_WARNING"), $langs->trans("MAINMINNBENABLEDMODULEFORWARNING"),$bc[$var]);
$var=!$var;
showConf("MAIN_ADD_SALE_REP_SIGNATURE_IN_NOTE", $langs->trans("MAIN_ADD_SALE_REP_SIGNATURE_IN_NOTE"), $langs->trans("MAINADDSALEREPSIGNATUREINNOTE"),$bc[$var]);
$var=!$var;
showConf("MAIN_DISABLE_AJAX_COMBOX", $langs->trans("MAIN_DISABLE_AJAX_COMBOX"), $langs->trans("MAINDISABLEAJAXCOMBOX"),$bc[$var]);
$var=!$var;
showConf("MAIN_USE_CACHE_MANIFEST", $langs->trans("MAIN_USE_CACHE_MANIFEST"), $langs->trans("MAINUSECACHEMANIFEST"),$bc[$var]);
$var=!$var;
showConf("MAIN_FORCELANGDIR", $langs->trans("MAIN_FORCELANGDIR"), $langs->trans("MAINFORCELANGDIR"),$bc[$var]);
$var=!$var;
showConf("MAIN_VAT_DEFAULT_IF_AUTODETECT_FAILS", $langs->trans("MAIN_VAT_DEFAULT_IF_AUTODETECT_FAILS"), $langs->trans("MAINVATDEFAULTIFAUTODETECTFAILS"),$bc[$var]);
$var=!$var;
if(version_dolibarr()>=4)
showConf("MAIN_PROPAGATE_CONTACTS_FROM_ORIGIN", $langs->trans("MAIN_PROPAGATE_CONTACTS_FROM_ORIGIN"), $langs->trans("MAINPROPAGATECONTACTSFROMORIGIN"),$bc[$var]);
$var=!$var;
if(version_dolibarr()>=4)
showConf("MAIN_PUBLIC_NOTE_IN_ADDRESS", $langs->trans("MAIN_PUBLIC_NOTE_IN_ADDRESS"), $langs->trans("MAINPUBLICNOTEINADDRESS"),$bc[$var]);
$var=!$var;
showConf("MAIN_VIEW_LINE_NUMBER", $langs->trans("MAIN_VIEW_LINE_NUMBER"), $langs->trans("MAINVIEWLINENUMBER"),$bc[$var]);
$var=!$var;
showConf("MAIN_AUTO_TIMESTAMP_IN_PUBLIC_NOTES", $langs->trans("MAIN_AUTO_TIMESTAMP_IN_PUBLIC_NOTES"), $langs->trans("MAINAUTOTIMESTAMPINPUBLICNOTES"),$bc[$var]);
$var=!$var;
showConf("MAIN_AUTO_TIMESTAMP_IN_PRIVATE_NOTES", $langs->trans("MAIN_AUTO_TIMESTAMP_IN_PRIVATE_NOTES"), $langs->trans("MAINAUTOTIMESTAMPINPRIVATENOTES"),$bc[$var]);
$var=!$var;
showConf("MAIN_SHOW_TECHNICAL_ID", $langs->trans("MAIN_SHOW_TECHNICAL_ID"), $langs->trans("MAINSHOWTECHNICALID"),$bc[$var]);
$var=!$var;
if(version_dolibarr()>=5.0)
showConf("MAIN_SECURITY_CSRF_WITH_TOKEN", $langs->trans("MAIN_SECURITY_CSRF_WITH_TOKEN"), $langs->trans("MAINSECURITYCSRFWITHTOKEN"),$bc[$var]);

/**
 * $var=!$var;
showConf("MAIN_ROUNDOFTOTAL_NOT_TOTALOFROUND", $langs->trans("MAIN_ROUNDOFTOTAL_NOT_TOTALOFROUND"), $langs->trans("MAINROUNDOFTOTALNOTTOTALOFROUND"),$bc[$var]);
**/

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_APPLICATION_TITLE</td><td>'.$langs->trans("MAINAPPLICATIONTITLE").'<td><input type="text" name="MAIN_APPLICATION_TITLE" value="'.$conf->global->MAIN_APPLICATION_TITLE.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_HELPCENTER_LINKTOUSE</td><td>'.$langs->trans("MAINHELPCENTERLINKTOUSE").'<td><input type="text" name="MAIN_HELPCENTER_LINKTOUSE" value="'.$conf->global->MAIN_HELPCENTER_LINKTOUSE.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_MODULES_FOR_EXTERNAL</td><td>'.$langs->trans("MAINMODULESFOREXTERNAL").'<td><input type="text" name="MAIN_MODULES_FOR_EXTERNAL" value="'.$conf->global->MAIN_MODULES_FOR_EXTERNAL.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_DOL_SCRIPTS_ROOT</td><td>'.$langs->trans("MAINDOLSCRIPTSROOT").'<td><input type="text" name="MAIN_DOL_SCRIPTS_ROOT" value="'.$conf->global->MAIN_DOL_SCRIPTS_ROOT.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_FORCELANGDIR</td><td>'.$langs->trans("MAINFORCELANGDIR").'<td><input type="text" name="MAIN_FORCELANGDIR" value="'.$conf->global->MAIN_FORCELANGDIR.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_HTML_TITLE</td><td>'.$langs->trans("MAINHTMLTITLE").'<td><input type="text" name="MAIN_HTML_TITLE" value="'.$conf->global->MAIN_HTML_TITLE.'" />';
print '</td></tr>';
$var=!$var;
if(version_dolibarr()>=3.9){
print '<tr '.$bc[$var].'>';
print '<td>MAIN_LANDING_PAGE</td><td>'.$langs->trans("MAINLANDINGPAGE").'<td><input type="text" name="MAIN_LANDING_PAGE" value="'.$conf->global->MAIN_LANDING_PAGE.'" />';
print '</td></tr>';

$var=!$var;
}print '<tr '.$bc[$var].'>';
print '<td>MAIN_WEIGHT_DEFAULT_ROUND</td><td>'.$langs->trans("MAINWEIGHTDEFAULTROUND").'<td><input type="text" name="MAIN_WEIGHT_DEFAULT_ROUND" value="'.$conf->global->MAIN_WEIGHT_DEFAULT_ROUND.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_WEIGHT_DEFAULT_UNIT</td><td>'.$langs->trans("MAINWEIGHTDEFAULTUNIT").'<td><input type="text" name="MAIN_WEIGHT_DEFAULT_UNIT" value="'.$conf->global->MAIN_WEIGHT_DEFAULT_UNIT.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_VOLUME_DEFAULT_ROUND</td><td>'.$langs->trans("MAINVOLUMEDEFAULTROUND").'<td><input type="text" name="MAIN_VOLUME_DEFAULT_ROUND" value="'.$conf->global->MAIN_VOLUME_DEFAULT_ROUND.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_VOLUME_DEFAULT_UNIT</td><td>'.$langs->trans("MAINVOLUMEDEFAULTUNIT").'<td><input type="text" name="MAIN_VOLUME_DEFAULT_UNIT" value="'.$conf->global->MAIN_VOLUME_DEFAULT_UNIT.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_DEFAULT_PAYMENT_TERM_ID</td><td>'.$langs->trans("MAINDEFAULTPAYMENTTERMID").'<td><input type="text" name="MAIN_DEFAULT_PAYMENT_TERM_ID" value="'.$conf->global->MAIN_DEFAULT_PAYMENT_TERM_ID.'" />';
print '</td></tr>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>MAIN_FILESYSTEM_ENCODING</td><td>'.$langs->trans("MAINFILESYSTEMENCODING").'<td><input type="text" name="MAIN_FILESYSTEM_ENCODING" value="'.$conf->global->MAIN_FILESYSTEM_ENCODING.'" />';
print '</td></tr>';
print '</table>';


print '<br><center><input type="submit" class="button" value="'.$langs->trans("Modify").'"></center>';

print '</form>';

dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();
