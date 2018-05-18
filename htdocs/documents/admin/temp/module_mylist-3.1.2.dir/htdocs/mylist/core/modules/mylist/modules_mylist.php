<?php
/* Copyright (C) 2014-2017		Charlie Benke	<charlie@patas-monkey.com>
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
 * or see http://www.gnu.org/
 */

/**
 *  \file		/mylist/core/modules/mylist/modules_mylist.php
 *  \ingroup	mylist
 *  \brief		Fichier contenant la classe mere de generation des fiches equipement en PDF
 */

require_once DOL_DOCUMENT_ROOT."/core/class/commondocgenerator.class.php";


/**
 *	Parent class to manage equipement document templates
 */
abstract class ModeleMylist extends CommonDocGenerator
{
	var $error='';

	/**
	 *	Return list of active generation modules
	 *
	 *  @param	DoliDB	$db	 			Database handler
	 *  @param  string	$maxfilenamelength  Max length of value to show
	 *  @return	array						List of templates
	 */
	function liste_modeles($db, $maxfilenamelength=0)
	{
//		global $conf;
//		global $myliststatic;
		$type='mylist';
		
		include_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
		$liste=getListOfModels($db, $type, $maxfilenamelength);
		return $liste;
	}
}

/**
 *  \class	  ModeleNumRefFicheinter
 *  \brief	  Classe mere des modeles de numerotation des references de fiches d'intervention
 */
abstract class ModeleNumRefFactory
{
	var $error='';

	/**
	 * 	Return if a module can be used or not
	 *
	 * 	@return		boolean	 true if module can be used
	 */
	function isEnabled()
	{
		return true;
	}

	/**
	 * 	Renvoi la description par defaut du modele de numerotation
	 *
	 * 	@return	 string	  Texte descripif
	 */
	function info()
	{
		global $langs;
		$langs->load("mylist@mylist");
		return $langs->trans("NoDescription");
	}

	/**
	 * 	Renvoi un exemple de numerotation
	 *
	 * 	@return	 string	  Example
	 */
	function getExample()
	{
		global $langs;
		$langs->load("mylist@mylist");
		return $langs->trans("NoExample");
	}

	/**
	 * 	Test si les numeros deja en vigueur dans la base ne provoquent pas de
	 * 	de conflits qui empechera cette numerotation de fonctionner.
	 *
	 * 	@return	 boolean	 false si conflit, true si ok
	 */
	function canBeActivated()
	{
		return true;
	}

	/**
	 * 	Renvoi prochaine valeur attribuee
	 *
	 * 	@return	 string	  Valeur
	 */
	function getNextValue()
	{
		global $langs;
		return $langs->trans("NotAvailable");
	}

	/**
	 * 	Renvoi version du module numerotation
	 *
	 * 	@return	 string	  Valeur
	 */
	function getVersion()
	{
		global $langs;
		$langs->load("admin");

		if ($this->version == 'development') return $langs->trans("VersionDevelopment");
		if ($this->version == 'experimental') return $langs->trans("VersionExperimental");
		if ($this->version == 'dolibarr') return DOL_VERSION;
		return $langs->trans("NotAvailable");
	}
}


/**
 *  Create an mylist document on disk using template defined into MYLIST_ADDON_PDF
 *
 *  @param	DoliDB		$db  			objet base de donnee
 *  @param	Object		$object			Object mylist
 *  @param	string		$modele			force le modele a utiliser ('' par defaut)
 *  @param	Translate	$outputlangs	objet lang a utiliser pour traduction
 *  @param  int			$hidedetails	Hide details of lines
 *  @param  int			$hidedesc	   Hide description
 *  @param  int			$hideref		Hide ref
 *  @param  HookManager	$hookmanager	Hook manager instance
 *  @return int		 				0 if KO, 1 if OK
 */
function mylist_create($db, $object, $modele, $outputlangs, $hidedetails=0, $hidedesc=0, $hideref=0, $hookmanager=false)
{
	global $conf, $langs, $user;
	$langs->load("mylist@mylist");

	$error=0;

	$srctemplatepath='';

	// Positionne modele sur le nom du modele de factory a utiliser
	if (! dol_strlen($modele)) {
		if ($conf->global->MYLIST_ADDON_PDF)
			$modele = $conf->global->MYLIST_ADDON_PDF;
		else
			$modele = 'mylist';
	}

	// If selected modele is a filename template (then $modele="modelname:filename")
	$tmp=explode(':', $modele, 2);
	if (! empty($tmp[1])) {
		$modele=$tmp[0];
		$srctemplatepath=$tmp[1];
	}

	// Search template files
	$file=''; $classname=''; $filefound=0;
	$dirmodels=array('/');
	if (is_array($conf->modules_parts['models'])) 
		$dirmodels=array_merge($dirmodels, $conf->modules_parts['models']);
	foreach ($dirmodels as $reldir) {
		foreach (array('doc', 'pdf') as $prefix) {
			$file = $prefix."_".$modele.".modules.php";

			// On verifie l'emplacement du modele
			$file=dol_buildpath($reldir."core/modules/mylist/doc/".$file, 0);
			if (file_exists($file)) {
				$filefound=1;
				$classname=$prefix.'_'.$modele;
				break;
			}
		}
		if ($filefound) break;
	}

	// Charge le modele
	if ($filefound) {
		require_once($file);

		$obj = new $classname($db);

		// We save charset_output to restore it because write_file can change it if needed for
		// output format that does not support UTF8.
		$sav_charset_output=$outputlangs->charset_output;
		if ($obj->write_file($object, $outputlangs, $srctemplatepath, $hidedetails, $hidedesc, $hideref, $hookmanager) > 0) {
			$outputlangs->charset_output=$sav_charset_output;

			// We delete old preview
			require_once(DOL_DOCUMENT_ROOT."/core/lib/files.lib.php");
			dol_delete_preview($object);

			// Success in building document. We build meta file.
			dol_meta_create($object);

			// Appel des triggers
			include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
			$interface=new Interfaces($db);
			$result=$interface->run_triggers('MYLIST_BUILDDOC', $object, $user, $langs, $conf);
			if ($result < 0) { 
				$error++; 
				$this->errors=$interface->errors; 
			}
			// Fin appel triggers

			return 1;
		} else {
			$outputlangs->charset_output=$sav_charset_output;
			dol_print_error($db, "mylist_pdf_create Error: ".$obj->error);
			return 0;
		}
	} else {
		print $langs->trans("Error")." ".$langs->trans("ErrorFileDoesNotExists", $file);
		return 0;
	}
}