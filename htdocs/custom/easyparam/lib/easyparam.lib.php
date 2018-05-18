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
 * 	\file		lib/easyparam.lib.php
 * 	\ingroup	easyparam
 * 	\brief		This file is an example module library
 * 				Put some comments here
 */
function easyparamAdminPrepareHead() {
    global $langs, $conf;

    $langs->load("easyparam@easyparam");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/easyparam/admin/global.php", 1);
    $head[$h][1] = $langs->trans("Global");
    $head[$h][2] = 'global';
    $h++;
    $head[$h][0] = dol_buildpath("/easyparam/admin/theme.php", 1);
    $head[$h][1] = $langs->trans("Theme");
    $head[$h][2] = 'theme';
    $h++;

    $head[$h][0] = dol_buildpath("/easyparam/admin/email_sms.php", 1);
    $head[$h][1] = $langs->trans("Email & SMS");
    $head[$h][2] = 'email_sms';
    $h++;

    if ($conf->societe->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/thirdparty.php", 1);
        $head[$h][1] = $langs->trans("Thirdparty");
        $head[$h][2] = 'thirdparty';
        $h++;
    }
    if ($conf->propal->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/quotation.php", 1);
        $head[$h][1] = $langs->trans("Propal");
        $head[$h][2] = 'quotation';
        $h++;
    }
    if ($conf->facture->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/invoice.php", 1);
        $head[$h][1] = $langs->trans("Invoice");
        $head[$h][2] = 'invoice';
        $h++;
    }
    if ($conf->commande->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/order.php", 1);
        $head[$h][1] = $langs->trans("Order");
        $head[$h][2] = 'order';
        $h++;
    }
    if ($conf->export->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/export.php", 1);
        $head[$h][1] = $langs->trans("Export");
        $head[$h][2] = 'export';
        $h++;
    }
    if ($conf->agenda->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/agenda.php", 1);
        $head[$h][1] = $langs->trans("Agenda");
        $head[$h][2] = 'agenda';
        $h++;
    }
    if ($conf->product->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/product.php", 1);
        $head[$h][1] = $langs->trans("Product");
        $head[$h][2] = 'product';
        $h++;
    }
    if ($conf->service->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/service.php", 1);
        $head[$h][1] = $langs->trans("Service");
        $head[$h][2] = 'service';
        $h++;
    }
    if ($conf->stock->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/stock.php", 1);
        $head[$h][1] = $langs->trans("Stock");
        $head[$h][2] = 'stock';
        $h++;
    }
    if ($conf->cashdesk->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/cashdesk.php", 1);
        $head[$h][1] = $langs->trans("Cashdesk");
        $head[$h][2] = 'cashdesk';
        $h++;
    }
    if ($conf->contract->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/contract.php", 1);
        $head[$h][1] = $langs->trans("Contract");
        $head[$h][2] = 'contract';
        $h++;
    }
    if ($conf->project->enabled) {

        $head[$h][0] = dol_buildpath("/easyparam/admin/project.php", 1);
        $head[$h][1] = $langs->trans("Project");
        $head[$h][2] = 'project';
        $h++;
    }
    if ($conf->adherent->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/foundation.php", 1);
        $head[$h][1] = $langs->trans("Foundation");
        $head[$h][2] = 'foundation';
        $h++;
    }
    if ($conf->intervention->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/intervention.php", 1);
        $head[$h][1] = $langs->trans("Intervention");
        $head[$h][2] = 'intervention';
        $h++;
    }
    if ($conf->fournisseur->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/supplier.php", 1);
        $head[$h][1] = $langs->trans("Supplier");
        $head[$h][2] = 'supplier';
        $h++;
    }
    if ($conf->banque->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/bank.php", 1);
        $head[$h][1] = $langs->trans("Banks");
        $head[$h][2] = 'banks';
        $h++;
    }
    if ($conf->fckeditor->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/wysiwyg.php", 1);
        $head[$h][1] = $langs->trans("Wysiwyg");
        $head[$h][2] = 'wysiwyg';
        $h++;
    }
    if ($conf->mailing->enabled) {
        $head[$h][0] = dol_buildpath("/easyparam/admin/emailing.php", 1);
        $head[$h][1] = $langs->trans("Emailing");
        $head[$h][2] = 'emailing';
        $h++;
    }
    $head[$h][0] = dol_buildpath("/easyparam/admin/pdf.php", 1);
    $head[$h][1] = $langs->trans("PDF");
    $head[$h][2] = 'pdf';
    $h++;


    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@easyparam:/easyparam/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@easyparam:/easyparam/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'easyparam');

    return $head;
}

function showConf($const, $texte_nom, $texte_descr, $var) {
    global $conf, $langs;

    print '<tr ' . $var . '>' . "\n";
    print '<td>' . $texte_nom . '</td>' . "\n";
    print '<td> ' . $texte_descr . '</td>' . "\n";
//    print '<td class="nowrap">' . $texte_exemple . '</td>' . "\n";

    if ($conf->global->$const == '1') {
        echo '<td align="center">' . "\n";
        print '<a href="' . $_SERVER['PHP_SELF'] . '?action=del&name=' . $const . '">';
        echo img_picto($langs->trans("Activated"), 'switch_on');
        echo "</td>\n";
    } else if (is_null($conf->global->$const) || $conf->global->$const == 0) {
        $disabled = false;
        if (!empty($conf->multicompany->enabled) && (is_object($mc) && !empty($mc->sharings['referent']) && $mc->sharings['referent'] == $conf->entity) ? false : true)
            ;
        print '<td align="center">';
        if (!$disabled)
            print '<a href="' . $_SERVER['PHP_SELF'] . '?action=set&name=' . $const . '">';
        print img_picto($langs->trans("Disabled"), 'switch_off');
        if (!$disabled)
            print '</a>';
        print '</td>';
    }
}
