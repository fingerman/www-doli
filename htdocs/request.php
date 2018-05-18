<?php

define('NOCSRFCHECK',1);	// This is main home and login page. We must be able to go on it from another web site.

require 'main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';

// If not defined, we select menu "home"
$_GET['mainmenu']=GETPOST('mainmenu', 'aZ09')?GETPOST('mainmenu', 'aZ09'):'home';
$action=GETPOST('action','aZ09');

$hookmanager->initHooks(array('index'));

if(isset($_POST['hauptkategorie']) && $_POST['hauptkategorie'] != ""){
	$resql = $db->query("SELECT * FROM ".MAIN_DB_PREFIX."verordner_fachg");
	echo "<option value=\"0\"></option>";
        while($obj = $db->fetch_object($resql)){
		echo "<option value=\"" . $obj->rowid . "\">" . $obj->hauptkategorie . "</option>\n";
	}
}
if(isset($_POST['unterkategorie']) && $_POST['unterkategorie'] != ""){
	$resql = $db->query("SELECT * FROM ".MAIN_DB_PREFIX."verordner_unterk WHERE pid=" . intval($_POST['unterkategorie']));
        echo "<option value=\"0\"></option>";
	while($obj = $db->fetch_object($resql)){
		echo "<option value=\"" . $obj->rowid . "\">" . $obj->unterkategorie . "</option>\n";
	}
}

///// Auswahl 1 Versorgungszuständigkeit
if(isset($_POST['auswahl1']) && $_POST['auswahl1'] != ""){
        $resql = $db->query("SELECT * FROM ".MAIN_DB_PREFIX."versorgungsbereich_kategorie WHERE pid=0");
        echo "<option value=\"\"></option>\n";
        while($obj = $db->fetch_object($resql)){
                echo "<option value=\"" . $obj->rowid . "\">" . $obj->label . "</option>\n";
        }
}
///// Auswahl 2 Versorgungszuständigkeit
if(isset($_POST['auswahl2']) && $_POST['auswahl2'] != ""){
        $resql = $db->query("SELECT * FROM ".MAIN_DB_PREFIX."versorgungsbereich_kategorie WHERE pid='" . intval($_POST['auswahl2']) . "'");
        echo "<option value=\"\"></option>\n";
        while($obj = $db->fetch_object($resql)){
                echo "<option value=\"" . $obj->rowid . "\">" . $obj->label . "</option>\n";
        }
}


?>