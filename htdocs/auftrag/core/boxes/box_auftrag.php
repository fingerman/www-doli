<?php
/* Copyright (C) 2012      Charles-François BENKE <charles.fr@benke.fr>
 * Copyright (C) 2005-2015 Laurent Destailleur    <eldy@users.sourceforge.net>
 * Copyright (C) 2014-2015 Frederic France        <frederic.france@free.fr>
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/mymodules/core/boxes/box_mybox.php
 *  \ingroup    mymodule
 *  \brief      Module to show box of bills, orders & propal of the current year
 */

include_once DOL_DOCUMENT_ROOT.'/core/boxes/modules_boxes.php';

/**
 * Class to manage the box of customer activity (invoice, order, proposal)
 */
class box_auftrag extends ModeleBoxes
{
    var $boxcode="activity";
    var $boximg="object_bill";
    var $boxlabel='BoxGlobalActivity3';
    var $depends = array("societe");

    var $db;
    var $param;
    var $enabled = 1;

    var $info_box_head = array();
    var $info_box_contents = array();


    /**
     *  Constructor
     *
     *  @param  DoliDB  $db         Database handler
     *  @param  string  $param      More parameters
     */
	function __construct($db,$param='')
	{
		global $conf, $user;

		$this->db = $db;

		$this->hidden = ! ($user->rights->societe->lire);
	}

	/**
     *  Load data for box to show them later
     *
     *  @param	int		$max        Maximum number of records to load
     *  @return	void
	 */
	function loadBox($max=5)
	{
		global $user, $langs, $db, $conf;
		$langs->load("boxes");

		$this->max=$max;

        include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
        $thirdpartystatic=new Societe($db);

        $this->info_box_head = array('text' => 'Testbox...');

		if ($user->rights->societe->lire)
		{

			$sql = "SELECT s.rowid, s.nom as name, s.logo, s.code_client, s.code_fournisseur, s.client, s.fournisseur, s.tms as datem, s.status as status,";
			$sql.= " count(*) as nbfact, sum(". $db->ifsql('f.paye=1','1','0').") as nbfactpaye";
			$sql.= " FROM ".MAIN_DB_PREFIX."societe as s, ".MAIN_DB_PREFIX."facture as f";
			$sql.= ' WHERE s.entity IN ('.getEntity('societe').')';
			$sql.= ' AND s.rowid = f.fk_soc';
			$sql.= " GROUP BY s.rowid, s.nom, s.logo, s.code_client, s.code_fournisseur, s.client, s.fournisseur, s.tms, s.status";
			$sql.= $db->order("nbfact","DESC");
			$sql.= $db->plimit($max,0);

			dol_syslog(get_class($this)."::loadBox", LOG_DEBUG);
			$result = $db->query($sql);
			if ($result)
			{
				$num = $db->num_rows($result);

				$line = 0;
				while ($line < $num)
				{
					$objp = $db->fetch_object($result);
					$datem=$db->jdate($objp->tms);
					$thirdpartystatic->id = $objp->rowid;
					$thirdpartystatic->name = $objp->name;
					$thirdpartystatic->code_client = $objp->code_client;
					$thirdpartystatic->code_fournisseur = $objp->code_fournisseur;
					$thirdpartystatic->client = $objp->client;
					$thirdpartystatic->fournisseur = $objp->fournisseur;
					$thirdpartystatic->logo = $objp->logo;
					$nbfact = $objp->nbfact;
					$nbimpaye = $objp->nbfact - $objp->nbfactpaye;

					$this->info_box_contents[$line][] = array(
					    'td' => '',
					    'text' => $thirdpartystatic->getNomUrl(1),
					    'asis' => 1,
					);

					$this->info_box_contents[$line][] = array(
					    'td' => 'class="right"',
					    'text' => dol_print_date($datem, "day")
					);

					$this->info_box_contents[$line][] = array(
					    'td' => 'class="right"',
					    'text' => $nbfact.( $nbimpaye != 0 ? ' ('.$nbimpaye.')':'')
					);

					$this->info_box_contents[$line][] = array(
					    'td' => 'align="right" width="18"',
					    'text' => $thirdpartystatic->LibStatut($objp->status,3)
					);

					$line++;
				}

				if ($num==0) $this->info_box_contents[$line][0] = array('td' => 'align="center"','text'=>$langs->trans("NoRecordedCustomers"));

				$db->free($result);
			}
			else {
				$this->info_box_contents[0][0] = array(	'td' => '',
    	        										'maxlength'=>500,
	            										'text' => ($db->error().' sql='.$sql));
			}
		}
		else {
			$this->info_box_contents[0][0] = array(
			    'td' => 'align="left" class="nohover opacitymedium"',
				'text' => $langs->trans("ReadPermissionNotAllowed")
			);
		}

	}

	/**
	 *	Method to show box
	 *
	 *	@param	array	$head       Array with properties of box title
	 *	@param  array	$contents   Array with properties of box lines
	 *  @param	int		$nooutput	No print, only return string
	 *	@return	string
	 */
    function showBox($head = null, $contents = null, $nooutput=0)
    {
		return parent::showBox($this->info_box_head, $this->info_box_contents, $nooutput);
	}
}
