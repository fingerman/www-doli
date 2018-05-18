<?php
/* Copyright (C) 2002-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2017 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2005      Lionel Cousteix      <etm_ltd@tiscali.co.uk>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2012      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2013      Florian Henry        <florian.henry@open-concept.pro>
 * Copyright (C) 2013-2016 Alexandre Spangaro   <aspangaro.dolibarr@gmail.com>
 * Copyright (C) 2015      Jean-Fran�ois Ferry  <jfefe@aternatik.fr>
 * Copyright (C) 2015      Ari Elbaz (elarifr)  <github@accedinfo.com>
 * Copyright (C) 2015      Charlie Benke        <charlie@patas-monkey.com>
 * Copyright (C) 2016      Rapha�l Doursenaud   <rdoursenaud@gpcsolutions.fr>
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
 *       \file       htdocs/user/card.php
 *       \brief      Tab of user card
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/user.class.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/usergroup.class.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/usergroups.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
if (! empty($conf->ldap->enabled)) require_once DOL_DOCUMENT_ROOT.'/core/class/ldap.class.php';
if (! empty($conf->adherent->enabled)) require_once DOL_DOCUMENT_ROOT.'/adherents/class/adherent.class.php';
if (! empty($conf->multicompany->enabled)) dol_include_once('/multicompany/class/actions_multicompany.class.php');
if (! empty($conf->categorie->enabled)) require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';

$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$mode		= GETPOST('mode','alpha');
$confirm	= GETPOST('confirm','alpha');
$subaction	= GETPOST('subaction','alpha');
$group		= GETPOST("group","int",3);
$cancel     = GETPOST('cancel');

// Users/Groups management only in master entity if transverse mode
if (($action == 'create' || $action == 'adduserldap') && ! empty($conf->multicompany->enabled) && $conf->entity > 1 && $conf->global->MULTICOMPANY_TRANSVERSE_MODE)
{
	accessforbidden();
}

// Define value to know what current user can do on users
$canadduser=(! empty($user->admin) || $user->rights->user->user->creer);
$canreaduser=(! empty($user->admin) || $user->rights->user->user->lire);
$canedituser=(! empty($user->admin) || $user->rights->user->user->creer);
$candisableuser=(! empty($user->admin) || $user->rights->user->user->supprimer);
$canreadgroup=$canreaduser;
$caneditgroup=$canedituser;
if (! empty($conf->global->MAIN_USE_ADVANCED_PERMS))
{
    $canreadgroup=(! empty($user->admin) || $user->rights->user->group_advance->read);
    $caneditgroup=(! empty($user->admin) || $user->rights->user->group_advance->write);
}
// Define value to know what current user can do on properties of edited user
if ($id)
{
    // $user est le user qui edite, $id est l'id de l'utilisateur edite
    $caneditfield=((($user->id == $id) && $user->rights->user->self->creer)
    || (($user->id != $id) && $user->rights->user->user->creer));
    $caneditpassword=((($user->id == $id) && $user->rights->user->self->password)
    || (($user->id != $id) && $user->rights->user->user->password));
}

// Security check
$socid=0;
if ($user->societe_id > 0) $socid = $user->societe_id;
$feature2='user';
if ($user->id == $id) { $feature2=''; $canreaduser=1; } // A user can always read its own card
if (!$canreaduser) {
	$result = restrictedArea($user, 'user', $id, 'user&user', $feature2);
}
if ($user->id <> $id && ! $canreaduser) accessforbidden();

$langs->load("users");
$langs->load("companies");
$langs->load("ldap");
$langs->load("admin");
$langs->load('hrm');

$object = new User($db);
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels=$extrafields->fetch_name_optionals_label($object->table_element);

// Initialize technical object to manage hooks. Note that conf->hooks_modules contains array
$hookmanager->initHooks(array('usercard','globalcard'));



/**
 * Actions
 */

$parameters=array('id'=>$socid);
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook)) {

	if ($action == 'confirm_disable' && $confirm == "yes" && $candisableuser) {
		if ($id <> $user->id) {
			$object->fetch($id);
			$object->setstatus(0);
			header("Location: ".$_SERVER['PHP_SELF'].'?id='.$id);
			exit;
		}
	}
	if ($action == 'confirm_enable' && $confirm == "yes" && $candisableuser) {
		$error = 0;

		if ($id <> $user->id) {
			$object->fetch($id);

			if (!empty($conf->file->main_limit_users)) {
				$nb = $object->getNbOfUsers("active");
				if ($nb >= $conf->file->main_limit_users) {
					$error ++;
					setEventMessages($langs->trans("YourQuotaOfUsersIsReached"), null, 'errors');
				}
			}

			if (!$error) {
				$object->setstatus(1);
				header("Location: ".$_SERVER['PHP_SELF'].'?id='.$id);
				exit;
			}
		}
	}

	if ($action == 'confirm_delete' && $confirm == "yes" && $candisableuser) {
		if ($id <> $user->id) {
			$object = new User($db);
			$object->fetch($id);
			$result = $object->delete();
			if ($result < 0) {
				$langs->load("errors");
				setEventMessages($langs->trans("ErrorUserCannotBeDelete"), null, 'errors');
			} else {
				header("Location: index.php");
				exit;
			}
		}
	}

	// Action Add user
	if ($action == 'add' && $canadduser) {
		$error = 0;

		if (!$_POST["lastname"]) {
			$error ++;
			setEventMessages($langs->trans("NameNotDefined"), null, 'errors');
			$action = "create";       // Go back to create page
		}
		if (!$_POST["login"]) {
			$error ++;
			setEventMessages($langs->trans("LoginNotDefined"), null, 'errors');
			$action = "create";       // Go back to create page
		}

		if (!empty($conf->file->main_limit_users)) { // If option to limit users is set
			$nb = $object->getNbOfUsers("active");
			if ($nb >= $conf->file->main_limit_users) {
				$error ++;
				setEventMessages($langs->trans("YourQuotaOfUsersIsReached"), null, 'errors');
				$action = "create";       // Go back to create page
			}
		}

		if (!$error) {
			$object->lastname = GETPOST("lastname", 'alpha');
			$object->firstname = GETPOST("firstname", 'alpha');
			$object->login = GETPOST("login", 'alpha');
			$object->api_key = GETPOST("api_key", 'alpha');
			$object->gender = GETPOST("gender", 'alpha');
			$object->admin = GETPOST("admin", 'alpha');
			$object->address = GETPOST('address', 'alpha');
			$object->zip = GETPOST('zipcode', 'alpha');
			$object->town = GETPOST('town', 'alpha');
			$object->country_id = GETPOST('country_id', 'int');
			$object->state_id = GETPOST('state_id', 'int');
			$object->office_phone = GETPOST("office_phone", 'alpha');
			$object->office_fax = GETPOST("office_fax", 'alpha');
			$object->user_mobile = GETPOST("user_mobile");
			$object->skype = GETPOST("skype", 'alpha');
			$object->email = GETPOST("email", 'alpha');
			$object->job = GETPOST("job", 'alpha');
			$object->signature = GETPOST("signature");
			$object->accountancy_code = GETPOST("accountancy_code");
			$object->note = GETPOST("note");
			$object->ldap_sid = GETPOST("ldap_sid");
			$object->fk_user = GETPOST("fk_user") > 0 ? GETPOST("fk_user") : 0;
            $object->employee = GETPOST('employee');

			$object->thm = GETPOST("thm") != '' ? GETPOST("thm") : '';
			$object->tjm = GETPOST("tjm") != '' ? GETPOST("tjm") : '';
			$object->salary = GETPOST("salary") != '' ? GETPOST("salary") : '';
			$object->salaryextra = GETPOST("salaryextra") != '' ? GETPOST("salaryextra") : '';
			$object->weeklyhours = GETPOST("weeklyhours") != '' ? GETPOST("weeklyhours") : '';

			$object->color = GETPOST("color") != '' ? GETPOST("color") : '';
			$dateemployment = dol_mktime(0, 0, 0, GETPOST('dateemploymentmonth'), GETPOST('dateemploymentday'), GETPOST('dateemploymentyear'));
			$object->dateemployment = $dateemployment;

			// Fill array 'array_options' with data from add form
			$ret = $extrafields->setOptionalsFromPost($extralabels, $object);
			if ($ret < 0) {
				$error ++;
			}

			// Set entity property
			$entity = GETPOST('entity', 'int');
			if (! empty($conf->multicompany->enabled)) {
				if (GETPOST('superadmin', 'int')) {
					$object->entity = 0;
				} else {
					if (! empty($conf->global->MULTICOMPANY_TRANSVERSE_MODE)) {
						$object->entity = 1; // all users are forced into master entity
					} else {
						$object->entity = ($entity == '' ? 1 : $entity);
					}
				}
			} else {
				$object->entity = ($entity == '' ? 1 : $entity);
                /*if ($user->admin && $user->entity == 0 && GETPOST("admin",'alpha'))
				{
				}*/
			}

			$db->begin();

			$id = $object->create($user);
			if ($id > 0) {
				if (GETPOST('password')) {
					$object->setPassword($user, GETPOST('password'));
				}
            			if (! empty($conf->categorie->enabled)) {
					// Categories association
					$usercats = GETPOST( 'usercats', 'array' );
					$object->setCategories($usercats);
				}
				$db->commit();

				header("Location: ".$_SERVER['PHP_SELF'].'?id='.$id);
				exit;
			}
			else
			{
				$langs->load("errors");
				$db->rollback();
				setEventMessages($object->error, $object->errors, 'errors');
				$action = "create";       // Go back to create page
			}
		}
	}

	// Action add usergroup
	if (($action == 'addgroup' || $action == 'removegroup') && $caneditfield)
	{
		if ($group)
		{
			$editgroup = new UserGroup($db);
			$editgroup->fetch($group);
			$editgroup->oldcopy=clone $editgroup;

			$object->fetch($id);
			if ($action == 'addgroup') {
				$object->SetInGroup($group, (! empty($conf->global->MULTICOMPANY_TRANSVERSE_MODE) ? GETPOST('entity', 'int') : $editgroup->entity));
			}
			if ($action == 'removegroup') {
				$object->RemoveFromGroup($group, (! empty($conf->global->MULTICOMPANY_TRANSVERSE_MODE) ? GETPOST('entity', 'int') : $editgroup->entity));
			}

			if ($result > 0) {
				header("Location: ".$_SERVER['PHP_SELF'].'?id='.$id);
				exit;
			}
			else
			{
				setEventMessages($object->error, $object->errors, 'errors');
			}
		}
	}

	if ($action == 'update' && ! $cancel)
	{
		require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

		if ($caneditfield)    // Case we can edit all field
		{
			$error = 0;

			if (!$_POST["lastname"]) {
				setEventMessages($langs->trans("NameNotDefined"), null, 'errors');
				$action = "edit";       // Go back to create page
				$error ++;
			}
			if (!$_POST["login"]) {
				setEventMessages($langs->trans("LoginNotDefined"), null, 'errors');
				$action = "edit";       // Go back to create page
				$error ++;
			}

			if (!$error)
			{
				$object->fetch($id);

				$object->oldcopy = clone $object;

				$db->begin();

				$object->lastname = GETPOST("lastname", 'alpha');
				$object->firstname = GETPOST("firstname", 'alpha');
				$object->login = GETPOST("login", 'alpha');
				$object->gender = GETPOST("gender", 'alpha');
				$object->pass = GETPOST("password");
				$object->api_key = (GETPOST("api_key", 'alpha')) ? GETPOST("api_key", 'alpha') : $object->api_key;
				if (! empty($user->admin)) $object->admin = GETPOST("admin"); 	// admin flag can only be set/unset by an admin user. A test is also done later when forging sql request
				$object->address = GETPOST('address', 'alpha');
				$object->zip = GETPOST('zipcode', 'alpha');
				$object->town = GETPOST('town', 'alpha');
				$object->country_id = GETPOST('country_id', 'int');
				$object->state_id = GETPOST('state_id', 'int');
				$object->office_phone = GETPOST("office_phone", 'alpha');
				$object->office_fax = GETPOST("office_fax", 'alpha');
				$object->user_mobile = GETPOST("user_mobile");
				$object->skype = GETPOST("skype", 'alpha');
				$object->email = GETPOST("email", 'alpha');
				$object->job = GETPOST("job", 'alpha');
				$object->signature = GETPOST("signature");
				$object->accountancy_code = GETPOST("accountancy_code");
				$object->openid = GETPOST("openid");
				$object->fk_user = GETPOST("fk_user") > 0 ? GETPOST("fk_user") : 0;
				$object->employee = GETPOST('employee');

				$object->thm = GETPOST("thm") != '' ? GETPOST("thm") : '';
				$object->tjm = GETPOST("tjm") != '' ? GETPOST("tjm") : '';
				$object->salary = GETPOST("salary") != '' ? GETPOST("salary") : '';
				$object->salaryextra = GETPOST("salaryextra") != '' ? GETPOST("salaryextra") : '';
				$object->weeklyhours = GETPOST("weeklyhours") != '' ? GETPOST("weeklyhours") : '';

				$object->color = GETPOST("color") != '' ? GETPOST("color") : '';
				$dateemployment = dol_mktime(0, 0, 0, GETPOST('dateemploymentmonth'), GETPOST('dateemploymentday'), GETPOST('dateemploymentyear'));
				$object->dateemployment = $dateemployment;

				if (! empty($conf->multicompany->enabled))
				{
					if (! empty($_POST["superadmin"]))
					{
						$object->entity = 0;
					}
					else if (! empty($conf->global->MULTICOMPANY_TRANSVERSE_MODE))
					{
						$object->entity = 1; // all users in master entity
					}
					else
					{
						$object->entity = (! GETPOST('entity', 'int') ? 0 : GETPOST('entity', 'int'));
					}
				}
				else
				{
					$object->entity = (! GETPOST('entity', 'int') ? 0 : GETPOST('entity', 'int'));
				}

				// Fill array 'array_options' with data from add form
				$ret = $extrafields->setOptionalsFromPost($extralabels, $object);
				if ($ret < 0) {
					$error ++;
				}

				if (GETPOST('deletephoto')) {
					$object->photo = '';
				}
				if (!empty($_FILES['photo']['name'])) {
					$object->photo = dol_sanitizeFileName($_FILES['photo']['name']);
				}

				if (!$error) {
					$ret = $object->update($user);
					if ($ret < 0) {
						$error++;
						if ($db->errno() == 'DB_ERROR_RECORD_ALREADY_EXISTS') {
							$langs->load("errors");
							setEventMessages($langs->trans("ErrorLoginAlreadyExists", $object->login), null, 'errors');
						}
						else
						{
							setEventMessages($object->error, $object->errors, 'errors');
						}
					}
				}

				if (!$error && GETPOST('contactid', 'int')) {
					$contactid = GETPOST('contactid', 'int');

					if ($contactid > 0) {
						$contact = new Contact($db);
						$contact->fetch($contactid);

						$sql = "UPDATE ".MAIN_DB_PREFIX."user";
						$sql .= " SET fk_socpeople=".$db->escape($contactid);
						if (!empty($contact->socid)) {
							$sql .= ", fk_soc=".$db->escape($contact->socid);
						}
						$sql .= " WHERE rowid=".$object->id;
					} else {
						$sql = "UPDATE ".MAIN_DB_PREFIX."user";
						$sql .= " SET fk_socpeople=NULL, fk_soc=NULL";
						$sql .= " WHERE rowid=".$object->id;
					}
					dol_syslog("usercard::update", LOG_DEBUG);
					$resql = $db->query($sql);
					if (!$resql) {
						$error ++;
						setEventMessages($db->lasterror(), null, 'errors');
					}
				}

				if (!$error && !count($object->errors)) {
					if (GETPOST('deletephoto') && $object->photo) {
						$fileimg = $conf->user->dir_output.'/'.get_exdir($object->id, 2, 0, 1, $object, 'user').'/logos/'.$object->photo;
						$dirthumbs = $conf->user->dir_output.'/'.get_exdir($object->id, 2, 0, 1, $object, 'user').'/logos/thumbs';
						dol_delete_file($fileimg);
						dol_delete_dir_recursive($dirthumbs);
					}

					if (isset($_FILES['photo']['tmp_name']) && trim($_FILES['photo']['tmp_name'])) {
						$dir = $conf->user->dir_output.'/'.get_exdir($object->id, 2, 0, 1, $object, 'user');

						dol_mkdir($dir);

						if (@is_dir($dir)) {
							$newfile = $dir.'/'.dol_sanitizeFileName($_FILES['photo']['name']);
							$result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1, 0, $_FILES['photo']['error']);

							if (!$result > 0) {
								setEventMessages($langs->trans("ErrorFailedToSaveFile"), null, 'errors');
							} else {
        					    // Create thumbs
        					    $object->addThumbs($newfile);
							}
						} else {
							$error ++;
							$langs->load("errors");
							setEventMessages($langs->trans("ErrorFailedToCreateDir", $dir), $mesgs, 'errors');
						}
					}
				}

            	if (! $error && ! count($object->errors))
            	{
            		// Then we add the associated categories
            		$categories = GETPOST( 'usercats', 'array' );
            		$object->setCategories($categories);
            	}

				if (!$error && !count($object->errors)) {
					setEventMessages($langs->trans("UserModified"), null, 'mesgs');
					$db->commit();

					$login = $_SESSION["dol_login"];
					if ($login && $login == $object->oldcopy->login && $object->oldcopy->login != $object->login)    // Current user has changed its login
					{
						$error++;
						$langs->load("errors");
						setEventMessages($langs->transnoentitiesnoconv("WarningYourLoginWasModifiedPleaseLogin"), null, 'warnings');
					}
				}
				else {
				    $db->rollback();
				}
            }
        }
		else
		{
		    if ($caneditpassword)    // Case we can edit only password
		    {
		        dol_syslog("Not allowed to change fields, only password");

		        $object->fetch($id);

		        $object->oldcopy = clone $object;

		        $ret = $object->setPassword($user, GETPOST("password"));
		        if ($ret < 0)
		        {
		            setEventMessages($object->error, $object->errors, 'errors');
		        }
		    }
		}
	}

	// Change password with a new generated one
    if ((($action == 'confirm_password' && $confirm == 'yes')
            || ($action == 'confirm_passwordsend' && $confirm == 'yes')) && $caneditpassword
    ) {
        $object->fetch($id);

        $newpassword = $object->setPassword($user, '');
        if ($newpassword < 0) {
            // Echec
            setEventMessages($langs->trans("ErrorFailedToSetNewPassword"), null, 'errors');
        } else {
            // Succes
            if ($action == 'confirm_passwordsend' && $confirm == 'yes') {
                if ($object->send_password($user, $newpassword) > 0)
                {
                    setEventMessages($langs->trans("PasswordChangedAndSentTo", $object->email), null, 'mesgs');
                }
                else
                {
                    setEventMessages($object->error, $object->errors, 'errors');
                }
            }
            else
            {
                setEventMessages($langs->trans("PasswordChangedTo", $newpassword), null, 'warnings');
            }
        }
    }

	// Action initialisation donnees depuis record LDAP
    if ($action == 'adduserldap') {
        $selecteduser = $_POST['users'];

        $required_fields = array(
            $conf->global->LDAP_KEY_USERS,
            $conf->global->LDAP_FIELD_NAME,
            $conf->global->LDAP_FIELD_FIRSTNAME,
            $conf->global->LDAP_FIELD_LOGIN,
            $conf->global->LDAP_FIELD_LOGIN_SAMBA,
            $conf->global->LDAP_FIELD_PASSWORD,
            $conf->global->LDAP_FIELD_PASSWORD_CRYPTED,
            $conf->global->LDAP_FIELD_PHONE,
            $conf->global->LDAP_FIELD_FAX,
            $conf->global->LDAP_FIELD_MOBILE,
            $conf->global->LDAP_FIELD_SKYPE,
            $conf->global->LDAP_FIELD_MAIL,
            $conf->global->LDAP_FIELD_TITLE,
            $conf->global->LDAP_FIELD_DESCRIPTION,
            $conf->global->LDAP_FIELD_SID
        );

        $ldap = new Ldap();
        $result = $ldap->connect_bind();
        if ($result >= 0) {
            // Remove from required_fields all entries not configured in LDAP (empty) and duplicated
            $required_fields = array_unique(array_values(array_filter($required_fields, "dol_validElement")));

            $ldapusers = $ldap->getRecords($selecteduser, $conf->global->LDAP_USER_DN, $conf->global->LDAP_KEY_USERS, $required_fields);
            //print_r($ldapusers);

            if (is_array($ldapusers)) {
                foreach ($ldapusers as $key => $attribute) {
                    $ldap_lastname = $attribute[$conf->global->LDAP_FIELD_NAME];
                    $ldap_firstname = $attribute[$conf->global->LDAP_FIELD_FIRSTNAME];
                    $ldap_login = $attribute[$conf->global->LDAP_FIELD_LOGIN];
                    $ldap_loginsmb = $attribute[$conf->global->LDAP_FIELD_LOGIN_SAMBA];
                    $ldap_pass = $attribute[$conf->global->LDAP_FIELD_PASSWORD];
                    $ldap_pass_crypted = $attribute[$conf->global->LDAP_FIELD_PASSWORD_CRYPTED];
                    $ldap_phone = $attribute[$conf->global->LDAP_FIELD_PHONE];
                    $ldap_fax = $attribute[$conf->global->LDAP_FIELD_FAX];
                    $ldap_mobile = $attribute[$conf->global->LDAP_FIELD_MOBILE];
                    $ldap_skype = $attribute[$conf->global->LDAP_FIELD_SKYPE];
                    $ldap_mail = $attribute[$conf->global->LDAP_FIELD_MAIL];
                    $ldap_sid = $attribute[$conf->global->LDAP_FIELD_SID];
                }
            }
        }
        else
        {
            setEventMessages($ldap->error, $ldap->errors, 'errors');
        }
    }

    // Actions to send emails
    $trigger_name='USER_SENTBYMAIL';
    $paramname='id';    // Name of param key to open the card
    $mode='emailfromuser';
    $trackid='use'.$object->id;
    include DOL_DOCUMENT_ROOT.'/core/actions_sendmails.inc.php';

    // Actions to build doc
    $upload_dir = $conf->user->dir_output;
    $permissioncreate=$user->rights->user->user->creer;
    include DOL_DOCUMENT_ROOT.'/core/actions_builddoc.inc.php';
}


/*
 * View
 */

$form = new Form($db);
$formother=new FormOther($db);
$formcompany = new FormCompany($db);
$formfile = new FormFile($db);

llxHeader('',$langs->trans("UserCard"));

if ($action == 'next')
{
}
else
{
    /* ************************************************************************** */
    /*                                                                            */
    /* View and edition                                                            */
    /*                                                                            */
    /* ************************************************************************** */

    if ($id > 0)
    {
        $object->fetch($id, '', '', 1);
        if ($res < 0) { dol_print_error($db,$object->error); exit; }
        $res=$object->fetch_optionals($object->id,$extralabels);

		// Check if user has rights
		$object->getrights();
		if (empty($object->nb_rights) && $object->statut != 0) setEventMessages($langs->trans('UserHasNoPermissions'), null, 'warnings');

        // Show tabs
		if ($mode == 'employee') // For HRM module development
		{
			$title = $langs->trans("Employee");
			$linkback = '<a href="'.DOL_URL_ROOT.'/hrm/employee/list.php">'.$langs->trans("BackToList").'</a>';
		}
		else
		{
			$title = $langs->trans("User");
			$linkback = '';

			if ($user->rights->user->user->lire || $user->admin) {
				$linkback = '<a href="'.DOL_URL_ROOT.'/user/index.php">'.$langs->trans("BackToList").'</a>';
			}
		}
                
		// Speichern
		if(isset($_POST['submit']) && $_POST['submit'] == 'SPEICHERN'){
			if($id == $user->id || $user->admin || $user->rights->medical24->versorgungsbereich_write){
				$db->query("INSERT INTO ".MAIN_DB_PREFIX."versorgungsbereich_liste SET userid='" . $id . "', cat1='" . intval($_POST['auswahl_1']) . "', cat2='" . intval($_POST['auswahl_2']) . "', plz='" . $db->escape($_POST['plz']) . "'");
			}
                }
		// Entfernen
		if(isset($_POST['delete']) && $_POST['delete'] == 'ENTFERNEN'){
			if($id == $user->id || $user->rights->medical24->versorgungsbereich_write){
				$db->query("DELETE FROM ".MAIN_DB_PREFIX."versorgungsbereich_liste WHERE rowid=" . intval($_POST['delid']) . " AND userid='" . $id . "'");
			}elseif($user->admin){
				$db->query("DELETE FROM ".MAIN_DB_PREFIX."versorgungsbereich_liste WHERE rowid=" . intval($_POST['delid']));
			}
		}

        $head = user_prepare_head($object);

        /*
         * Fiche en mode visu
         */
        if ($action != 'edit')
        {
			dol_fiche_head($head, 'mymodule', $title, -1, 'user');

            dol_banner_tab($object,'id',$linkback,$user->rights->user->user->lire || $user->admin);

	        print '<div class="fichecenter">';
	        print '<div class="fichehalfleft">';

	        print '<div class="underbanner clearboth"></div>';
	        print '<h4>Bitte w&auml;hlen</h4>';
		print '<form action="'.$_SERVER['PHP_SELF'].'?id='.$object->id.'" method="POST" name="savezipcodes" enctype="multipart/form-data">';
                print '<table class="border tableforfield" width="100%">';

                // Form elemente
                print '<tr><td class="titlefield"><label for="auswahl_1">Auswahl 1</label></td><td><select id="auswahl_1" name="auswahl_1" style="width: 100%"></select></td></tr>';
                print '<tr><td class="titlefield"><label for="auswahl_2">Auswahl 2</label></td><td><select id="auswahl_2" name="auswahl_2" style="width: 100%"></select></td></tr>';
                print '<tr><td class="titlefield"><label for="plz">Postleitzahlen</label></td><td><input type="text" id="plz" name="plz" value="" style="width: 100%" placeholder="12345,12346..." /></td></tr>';
                print '<tr><td class="titlefield"></td><td><input type="submit" name="submit" value="SPEICHERN" class="button" style="width: 100%" /></td></tr>';

                print '</table>';
                print '</form>';

	        print '</div>';
	        print '<div class="fichehalfright"><div class="ficheaddleft">';

	        print '<div class="underbanner clearboth"></div>';
	        print '<h4>Ihre Bereiche</h4>';
                print '<table class="border tableforfield" width="100%">';
                print ' <tr><td><strong>1.Auswahl</strong></td><td><strong>2.Auswahl</strong></td><td><strong>Postleitzahlgebiete</strong></td><td>&nbsp;</td></tr>';
                
                $resql = $db->query("	SELECT 	e.rowid AS rowid, 
                                                (SELECT c.label AS cat1 FROM ".MAIN_DB_PREFIX."versorgungsbereich_kategorie c WHERE c.rowid=e.cat1) AS cat1, 
                                                (SELECT c.label AS cat2 FROM ".MAIN_DB_PREFIX."versorgungsbereich_kategorie c WHERE c.rowid=e.cat2) AS cat2, 
                                                e.plz AS plz
                                        FROM 	".MAIN_DB_PREFIX."versorgungsbereich_liste e
                                        WHERE 	userid='" . $id . "'");
                while($obj = $db->fetch_object($resql)){
                    print '	<tr><td>' . $obj->cat1 . '</td><td>' . $obj->cat2 . '</td><td>' . $obj->plz . '</td>';
                    print '<td><form action="'.$_SERVER['PHP_SELF'].'?id='.$object->id.'" method="POST" name="savezipcodes" enctype="multipart/form-data"><input type="hidden" name="delid" value="' . $obj->rowid . '" class="button" /><input type="submit" name="delete" value="ENTFERNEN" class="button" /></form></td></tr>' . "\n";
                }



                print "</table>\n"; 
                print '</div>';

        	print '</div></div>';
        	print '<div style="clear:both"></div>';


            dol_fiche_end();


        }

    }

}

if (! empty($conf->api->enabled) && ! empty($conf->use_javascript_ajax))
{
    print "\n".'<script type="text/javascript">';
    print '$(document).ready(function () {
            $("#generate_api_key").click(function() {
                $.get( "'.DOL_URL_ROOT.'/core/ajax/security.php", {
                    action: \'getrandompassword\',
                    generic: true
                },
                function(token) {
                    $("#api_key").val(token);
                });
            });
    });';
    print '</script>';
}
print	'<script type="text/javascript" language="javascript">';
print	'$(document).ready(function () {
                $("#auswahl_1").on("change", function(){
                        var pid = $(this).children("option:selected").val();
                        $("#auswahl_2").load("/request.php", {"auswahl2": pid});
                });
                $("#auswahl_1").load("/request.php", {"auswahl1": 1});
        })';
print	'</script>';

llxFooter();
$db->close();
