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
if (!$user->admin) {
    accessforbidden();
}


if (!$user->admin)
    accessforbidden();

// Parameters
$action = GETPOST('action', 'alpha');
$name = GETPOST('name', 'alpha');
// Activate const
if ($action == 'set') {
    dolibarr_set_const($db, $name, '1', 'yesno', 0, '', $conf->entity);
} else if ($action == 'del') {
    dolibarr_del_const($db, $name);
}
if ($action == 'setvalue' && $user->admin) {
    $db->begin();
    $result = dolibarr_set_const($db, "MAIN_USE_BACKGROUND_ON_PDF", GETPOST('MAIN_USE_BACKGROUND_ON_PDF'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_ADD_PDF_BACKGROUND", GETPOST('MAIN_ADD_PDF_BACKGROUND'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_FORCE_FONT", GETPOST('MAIN_PDF_FORCE_FONT'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_MARGIN_LEFT", GETPOST('MAIN_PDF_MARGIN_LEFT'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_MARGIN_RIGHT", GETPOST('MAIN_PDF_MARGIN_RIGHT'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_MARGIN_TOP", GETPOST('MAIN_PDF_MARGIN_TOP'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_MARGIN_BOTTOM", GETPOST('MAIN_PDF_MARGIN_BOTTOM'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_FREETEXT_HEIGHT", GETPOST('MAIN_PDF_FREETEXT_HEIGHT'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_PDF_TITLE_BACKGROUND_COLOR", GETPOST('MAIN_PDF_TITLE_BACKGROUND_COLOR'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;
    $result = dolibarr_set_const($db, "MAIN_DOL_SCRIPTS_ROOT", GETPOST('MAIN_DOL_SCRIPTS_ROOT'), 'chaine', 0, '', $conf->entity);
    if (!$result > 0)
        $error++;




    if (!$error) {
        $db->commit();
        setEventMessage($langs->trans("SetupSaved"));
    } else {
        $db->rollback();
        dol_print_error($db);
    }
}


/*
 * 	View
 */

$form = new Form($db);

llxHeader('', $langs->trans("EasyParamSetup"));


$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre(' - ' . $langs->trans("ModuleSetup"), $linkback);
print '<br>';

//$head=paypaladmin_prepare_head();
//dol_fiche_head($head, 'easyparam', '');
// Configuration header
$head = easyparamAdminPrepareHead();
dol_fiche_head($head, 'pdf', $langs->trans("EasyParam"), 0, "easyparam@easyparam"
);


print '<br>';
print '<br>';
print '<form method="post" action="' . $_SERVER["PHP_SELF"] . '">';
print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
print '<input type="hidden" name="action" value="setvalue">';

print '<table class="noborder" width="100%">';

$var = true;
print '<tr class="liste_titre">';
print '<td>' . $langs->trans("AccountParameter") . '</td>';
print '<td>' . $langs->trans("Description") . '</td>';
print '<td>' . $langs->trans("Value") . '</td>';
print "</tr>\n";


$var = !$var;
showConf('MAIN_DISABLE_FORCE_SAVEAS', $langs->trans("MAIN_DISABLE_FORCE_SAVEAS"), $langs->trans("MAINDISABLEFORCESAVEAS"), $bc[$var]);
$var = !$var;
showConf('MAIN_DISABLE_PDF_COMPRESSION', $langs->trans("MAIN_DISABLE_PDF_COMPRESSION"), $langs->trans("MAINDISABLEPDFCOMPRESSION"), $bc[$var]);
$var = !$var;
showConf('MAIN_INVERT_SENDER_RECIPIENT', $langs->trans("MAIN_INVERT_SENDER_RECIPIENT"), $langs->trans("MAININVERTSENDERRECIPIENT"), $bc[$var]);
$var = !$var;
showConf('MAIN_DISABLE_PDF_AUTOUPDATE', $langs->trans("MAIN_DISABLE_PDF_AUTOUPDATE"), $langs->trans("MAININVERTSENDERRECIPIENT"), $bc[$var]);
$var = !$var;
showConf('MAIN_USE_BACKGROUND_ON_PDF', $langs->trans("MAIN_USE_BACKGROUND_ON_PDF"), $langs->trans("MAINUSEBACKGROUNDONPDF"), $bc[$var]);
$var = !$var;
showConf('MAIN_USE_COMPANY_NAME_OF_CONTACT', $langs->trans("MAIN_USE_COMPANY_NAME_OF_CONTACT"), $langs->trans("MAINUSECOMPANYNAMEOFCONTACT"), $bc[$var]);
$var = !$var;
showConf('MAIN_GENERATE_PROPOSALS_WITH_PICTURE', $langs->trans("MAIN_GENERATE_PROPOSALS_WITH_PICTURE"), $langs->trans("MAINGENERATEPROPOSALSWITHPICTURE"), $bc[$var]);
$var = !$var;
showConf('MAIN_GENERATE_INVOICES_WITH_PICTURE', $langs->trans("MAIN_GENERATE_INVOICES_WITH_PICTURE"), $langs->trans("MAINGENERATEINVOICESWITHPICTURE"), $bc[$var]);
$var = !$var;
showConf('MAIN_DOCUMENTS_WITH_PICTURE_WIDTH', $langs->trans("MAIN_DOCUMENTS_WITH_PICTURE_WIDTH"), $langs->trans("MAINDOCUMENTSWITHPICTUREWIDTH"), $bc[$var]);
$var = !$var;
showConf('PDF_SHOW_PROJECT', $langs->trans("PDF_SHOW_PROJECT"), $langs->trans("PDFSHOWPROJECT"), $bc[$var]);
$var = !$var;
showConf('PDF_BANK_HIDE_NUMBER_SHOW_ONLY_BICIBAN', $langs->trans("PDF_BANK_HIDE_NUMBER_SHOW_ONLY_BICIBAN"), $langs->trans("PDFBANKHIDENUMBERSHOWONLYBICIBAN"), $bc[$var]);
$var = !$var;
showConf('MAIN_ODT_AS_PDF_DEL_SOURCE', $langs->trans("MAIN_ODT_AS_PDF_DEL_SOURCE"), $langs->trans("MAINODTASPDFDELSOURCE"), $bc[$var]);

$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_USE_BACKGROUND_ON_PDF</td><td>' . $langs->trans("MAINUSEBACKGROUNDONPDF") . '<td><input type="text" name="MAIN_USE_BACKGROUND_ON_PDF" value="' . $conf->global->MAIN_USE_BACKGROUND_ON_PDF . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_ADD_PDF_BACKGROUND</td><td>' . $langs->trans("MAINADDPDFBACKGROUND") . '<td><input type="text" name="MAIN_ADD_PDF_BACKGROUND" value="' . $conf->global->MAIN_ADD_PDF_BACKGROUND . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_FORCE_FONT</td><td>' . $langs->trans("MAINPDFFORCEFONT") . '<td><input type="text" name="MAIN_PDF_FORCE_FONT" value="' . $conf->global->MAIN_PDF_FORCE_FONT . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_MARGIN_LEFT</td><td>' . $langs->trans("MAINPDFMARGIN") . '<td><input type="text" name="MAIN_PDF_MARGIN_LEFT" value="' . $conf->global->MAIN_PDF_MARGIN_LEFT . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_MARGIN_RIGHT</td><td>' . $langs->trans("MAINPDFMARGIN") . '<td><input type="text" name="MAIN_PDF_MARGIN_RIGHT" value="' . $conf->global->MAIN_PDF_MARGIN_RIGHT . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_MARGIN_TOP</td><td>' . $langs->trans("MAINPDFMARGIN") . '<td><input type="text" name="MAIN_PDF_MARGIN_TOP" value="' . $conf->global->MAIN_PDF_MARGIN_TOP . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_MARGIN_BOTTOM</td><td>' . $langs->trans("MAINPDFMARGIN") . '<td><input type="text" name="MAIN_PDF_MARGIN_BOTTOM" value="' . $conf->global->MAIN_PDF_MARGIN_BOTTOM . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_FREETEXT_HEIGHT</td><td>' . $langs->trans("MAINPDFFREETEXTHEIGHT") . '<td><input type="text" name="MAIN_PDF_FREETEXT_HEIGHT" value="' . $conf->global->MAIN_PDF_FREETEXT_HEIGHT . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_PDF_TITLE_BACKGROUND_COLOR</td><td>' . $langs->trans("MAINPDFTITLEBACKGROUNDCOLOR") . '<td><input type="text" name="MAIN_PDF_TITLE_BACKGROUND_COLOR" value="' . $conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_ODT_AS_PDF</td><td>' . $langs->trans("MAINODTASPDF") . '<td><input type="text" name="MAIN_ODT_AS_PDF" value="' . $conf->global->MAIN_ODT_AS_PDF . '" />';
print '</td></tr>';
$var = !$var;
print '<tr ' . $bc[$var] . '>';
print '<td>MAIN_DOL_SCRIPTS_ROOT</td><td>' . $langs->trans("MAINDOLSCRIPTSROOT") . '<td><input type="text" name="MAIN_DOL_SCRIPTS_ROOT" value="' . $conf->global->MAIN_DOL_SCRIPTS_ROOT . '" />';
print '</td></tr>';
$var = !$var;

print '</table>';

print '<br><center><input type="submit" class="button" value="' . $langs->trans("Modify") . '"></center>';



dol_fiche_end();

print '<br><br>';

llxFooter();
$db->close();
