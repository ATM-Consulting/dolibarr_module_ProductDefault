<?php
/* Copyright (C) 2007-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *   	\file       productthirdpartdefault.php
 *		\ingroup    productdefault
 *		\brief      product asignment for the current thirdparty
 */

//if (! defined('NOREQUIREDB'))              define('NOREQUIREDB', '1');				// Do not create database handler $db
//if (! defined('NOREQUIREUSER'))            define('NOREQUIREUSER', '1');				// Do not load object $user
//if (! defined('NOREQUIRESOC'))             define('NOREQUIRESOC', '1');				// Do not load object $mysoc
//if (! defined('NOREQUIRETRAN'))            define('NOREQUIRETRAN', '1');				// Do not load object $langs
//if (! defined('NOSCANGETFORINJECTION'))    define('NOSCANGETFORINJECTION', '1');		// Do not check injection attack on GET parameters
//if (! defined('NOSCANPOSTFORINJECTION'))   define('NOSCANPOSTFORINJECTION', '1');		// Do not check injection attack on POST parameters
//if (! defined('NOCSRFCHECK'))              define('NOCSRFCHECK', '1');				// Do not check CSRF attack (test on referer + on token if option MAIN_SECURITY_CSRF_WITH_TOKEN is on).
//if (! defined('NOTOKENRENEWAL'))           define('NOTOKENRENEWAL', '1');				// Do not roll the Anti CSRF token (used if MAIN_SECURITY_CSRF_WITH_TOKEN is on)
//if (! defined('NOSTYLECHECK'))             define('NOSTYLECHECK', '1');				// Do not check style html tag into posted data
//if (! defined('NOREQUIREMENU'))            define('NOREQUIREMENU', '1');				// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))            define('NOREQUIREHTML', '1');				// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))            define('NOREQUIREAJAX', '1');       	  	// Do not load ajax.lib.php library
//if (! defined("NOLOGIN"))                  define("NOLOGIN", '1');					// If this page is public (can be called outside logged session). This include the NOIPCHECK too.
//if (! defined('NOIPCHECK'))                define('NOIPCHECK', '1');					// Do not check IP defined into conf $dolibarr_main_restrict_ip
//if (! defined("MAIN_LANG_DEFAULT"))        define('MAIN_LANG_DEFAULT', 'auto');					// Force lang to a particular value
//if (! defined("MAIN_AUTHENTICATION_MODE")) define('MAIN_AUTHENTICATION_MODE', 'aloginmodule');	// Force authentication handler
//if (! defined("NOREDIRECTBYMAINTOLOGIN"))  define('NOREDIRECTBYMAINTOLOGIN', 1);		// The main.inc.php does not make a redirect if not logged, instead show simple error message
//if (! defined("FORCECSP"))                 define('FORCECSP', 'none');				// Disable all Content Security Policies
//if (! defined('CSRFCHECK_WITH_TOKEN'))     define('CSRFCHECK_WITH_TOKEN', '1');		// Force use of CSRF protection with tokens even for GET
//if (! defined('NOBROWSERNOTIF'))     		 define('NOBROWSERNOTIF', '1');				// Disable browser notification

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--; $j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) {
	$res = @include "../main.inc.php";
}
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
// load productdefault libraries
require_once __DIR__.'/class/productthirdpartydefault.class.php';

// Load translation files required by the page
$langs->loadLangs(array("productdefault@productdefault", "other"));

$action     	= GETPOST('action', 'aZ09') ?GETPOST('action', 'aZ09') : 'view'; // The action 'add', 'create', 'edit', 'update', 'view', ...
$massaction 	= GETPOST('massaction', 'alpha'); // The bulk action (combo box choice into lists)
$show_files 	= GETPOST('show_files', 'int'); // Show files area generated by bulk actions ?
$confirm    	= GETPOST('confirm', 'alpha'); // Result of a confirmation
$cancel     	= GETPOST('cancel', 'alpha'); // We click on a Cancel button
$toselect   	= GETPOST('toselect', 'array'); // Array of ids of elements selected into a list
$contextpage 	= GETPOST('contextpage', 'aZ') ? GETPOST('contextpage', 'aZ') : 'productthirdpartydefaultlist'; // To manage different context of search
$backtopage 	= GETPOST('backtopage', 'alpha'); // Go back to a dedicated page
$optioncss 		= GETPOST('optioncss', 'aZ'); // Option for the css output (always '' except when 'print')
$selected 		= GETPOST('lineid', 'int'); // Option for the css output (always '' except when 'print')
$rank 			= (GETPOST('rank', 'int') > 0) ? GETPOST('rank', 'int') : -1;
$id 			= GETPOST('id', 'int');
$lineid  		= GETPOST('lineid', 'int');
$TypeAssignment = GETPOST('typeAssignment', 'array');

$form = new Form($db);

$usercanread = $user->rights->productdefault->lire;
$usercancreate = $user->rights->productdefault->creer;
$usercandelete = $user->rights->productdefault->supprimer;

$object = new Societe($db);
$object->fetch($id);

// on a besoin que ce champs soit renseigné pour la suite
$object->socid = $object->id;


$extrafields = new ExtraFields($db);
$productDefault = new ProductThirdpartyDefault($db);

// Multicurrency (test on $this->multicurrency_tx because we should take the default rate only if not using origin rate)
if (!empty($object->multicurrency_code) && empty($productDefault->multicurrency_tx)) {
	list($productDefault->fk_multicurrency, $productDefault->multicurrency_tx) = MultiCurrency::getIdAndTxFromCode($db, $object->multicurrency_code);
} else {
	$productDefault->fk_multicurrency = MultiCurrency::getIdFromCode($db, $object->multicurrency_code);
}
if (empty($productDefault->fk_multicurrency)) {
	$productDefault->multicurrency_code = $conf->currency;
	$productDefault->fk_multicurrency = 0;
	$productDefault->multicurrency_tx = 1;
}

$formconfirm = "";

/** CRUD AJOUT LIGNE  */
if ($action == 'addline' && $usercancreate) {
	$errors = 0;
	if (empty($TypeAssignment)){
		setEventMessage($langs->trans('Assignementmandatory'),'errors');

		$errors++;
	}

	if ($errors == 0){
		// Add line
		// Set if we used free entry or predefined product
		$predef = '';
		$product_desc = (GETPOSTISSET('dp_desc') ? GETPOST('dp_desc', 'restricthtml') : '');
		$price_ht = price2num(GETPOST('price_ht'), 'MU', 2);
		$price_ht_devise = price2num(GETPOST('multicurrency_price_ht'), 'CU', 2);
		$prod_entry_mode = GETPOST('prod_entry_mode');
		if ($prod_entry_mode == 'free') {
			$idprod = 0;
			$tva_tx = (GETPOST('tva_tx') ? GETPOST('tva_tx') : 0);
		} else {
			$idprod = GETPOST('idprod', 'int');
			$tva_tx = '';
		}

		$qty = price2num(GETPOST('qty'.$predef, 'alpha'), 'MS', 2);
		$remise_percent = price2num(GETPOST('remise_percent'.$predef), '', 2);
		if (empty($remise_percent)) {
			$remise_percent = 0;
		}

		// Extrafields
		$extralabelsline = $extrafields->fetch_name_optionals_label($object->table_element_line);
		$array_options = $extrafields->getOptionalsFromPost($object->table_element_line, $predef);
		// Unset extrafield
		if (is_array($extralabelsline)) {
			// Get extra fields
			foreach ($extralabelsline as $key => $value) {
				unset($_POST["options_".$key]);
			}
		}

		if ($prod_entry_mode == 'free' && (empty($idprod) || $idprod < 0) && GETPOST('type') < 0) {
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Type")), null, 'errors');
			$error++;
		}

		if ($prod_entry_mode == 'free' && (empty($idprod) || $idprod < 0) && $price_ht === '' && $price_ht_devise === '') { 	// Unit price can be 0 but not ''. Also price can be negative for proposal.
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("UnitPriceHT")), null, 'errors');
			$error++;
		}
		if ($prod_entry_mode == 'free' && (empty($idprod) || $idprod < 0) && empty($product_desc)) {
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Description")), null, 'errors');
			$error++;
		}

		if (!$error && !empty($conf->variants->enabled) && $prod_entry_mode != 'free') {
			if ($combinations = GETPOST('combinations', 'array')) {
				//Check if there is a product with the given combination
				$prodcomb = new ProductCombination($db);

				if ($res = $prodcomb->fetchByProductCombination2ValuePairs($idprod, $combinations)) {
					$idprod = $res->fk_product_child;
				} else {
					setEventMessages($langs->trans('ErrorProductCombinationNotFound'), null, 'errors');
					$error++;
				}
			}
		}

		if (!$error && ($qty >= 0) && (!empty($product_desc) || (!empty($idprod) && $idprod > 0))) {
			$pu_ht = 0;
			$pu_ttc = 0;
			$price_min = 0;
			$price_base_type = (GETPOST('price_base_type', 'alpha') ? GETPOST('price_base_type', 'alpha') : 'HT');

			$db->begin();

			// $tva_tx can be 'x.x (XXX)'

			// Ecrase $pu par celui du produit
			// Ecrase $desc par celui du produit
			// Ecrase $tva_tx par celui du produit
			// Replaces $fk_unit with the product unit
			if (!empty($idprod) && $idprod > 0) {
				$prod = new Product($db);
				$prod->fetch($idprod);

				$label = ((GETPOST('product_label') && GETPOST('product_label') != $prod->label) ? GETPOST('product_label') : '');

				// Update if prices fields are defined
				$tva_tx = get_default_tva($object, $object, $prod->id);
				$tva_npr = get_default_npr($object, $object, $prod->id);
				if (empty($tva_tx)) {
					$tva_npr = 0;
				}

				// Price unique per product
				$pu_ht = $prod->price;
				$pu_ttc = $prod->price_ttc;
				$price_min = $prod->price_min;
				$price_base_type = $prod->price_base_type;

				// If price per segment
				if (!empty($conf->global->PRODUIT_MULTIPRICES) && $object->thirdparty->price_level) {
					$pu_ht = $prod->multiprices[$object->thirdparty->price_level];
					$pu_ttc = $prod->multiprices_ttc[$object->thirdparty->price_level];
					$price_min = $prod->multiprices_min[$object->thirdparty->price_level];
					$price_base_type = $prod->multiprices_base_type[$object->thirdparty->price_level];
					if (!empty($conf->global->PRODUIT_MULTIPRICES_USE_VAT_PER_LEVEL)) {  // using this option is a bug. kept for backward compatibility
						if (isset($prod->multiprices_tva_tx[$object->thirdparty->price_level])) {
							$tva_tx = $prod->multiprices_tva_tx[$object->thirdparty->price_level];
						}
						if (isset($prod->multiprices_recuperableonly[$object->thirdparty->price_level])) {
							$tva_npr = $prod->multiprices_recuperableonly[$object->thirdparty->price_level];
						}
					}
				} elseif (!empty($conf->global->PRODUIT_CUSTOMER_PRICES)) {
					// If price per customer
					require_once DOL_DOCUMENT_ROOT.'/product/class/productcustomerprice.class.php';

					$prodcustprice = new Productcustomerprice($db);

					$filter = array('t.fk_product' => $prod->id, 't.fk_soc' => $object->thirdparty->id);

					$result = $prodcustprice->fetch_all('', '', 0, 0, $filter);
					if ($result) {
						// If there is some prices specific to the customer
						if (count($prodcustprice->lines) > 0) {
							$pu_ht = price($prodcustprice->lines[0]->price);
							$pu_ttc = price($prodcustprice->lines[0]->price_ttc);
							$price_min =  price($prodcustprice->lines[0]->price_min);
							$price_base_type = $prodcustprice->lines[0]->price_base_type;
							$tva_tx = ($prodcustprice->lines[0]->default_vat_code ? $prodcustprice->lines[0]->tva_tx.' ('.$prodcustprice->lines[0]->default_vat_code.' )' : $prodcustprice->lines[0]->tva_tx);
							if ($prodcustprice->lines[0]->default_vat_code && !preg_match('/\(.*\)/', $tva_tx)) {
								$tva_tx .= ' ('.$prodcustprice->lines[0]->default_vat_code.')';
							}
							$tva_npr = $prodcustprice->lines[0]->recuperableonly;
							if (empty($tva_tx)) {
								$tva_npr = 0;
							}
						}
					}
				} elseif (!empty($conf->global->PRODUIT_CUSTOMER_PRICES_BY_QTY)) {
					// If price per quantity
					if ($prod->prices_by_qty[0]) {	// yes, this product has some prices per quantity
						// Search the correct price into loaded array product_price_by_qty using id of array retrieved into POST['pqp'].
						$pqp = GETPOST('pbq', 'int');

						// Search price into product_price_by_qty from $prod->id
						foreach ($prod->prices_by_qty_list[0] as $priceforthequantityarray) {
							if ($priceforthequantityarray['rowid'] != $pqp) {
								continue;
							}
							// We found the price
							if ($priceforthequantityarray['price_base_type'] == 'HT') {
								$pu_ht = $priceforthequantityarray['unitprice'];
							} else {
								$pu_ttc = $priceforthequantityarray['unitprice'];
							}
							// Note: the remise_percent or price by qty is used to set data on form, so we will use value from POST.
							break;
						}
					}
				} elseif (!empty($conf->global->PRODUIT_CUSTOMER_PRICES_BY_QTY_MULTIPRICES)) {
					// If price per quantity and customer
					if ($prod->prices_by_qty[$object->thirdparty->price_level]) { // yes, this product has some prices per quantity
						// Search the correct price into loaded array product_price_by_qty using id of array retrieved into POST['pqp'].
						$pqp = GETPOST('pbq', 'int');

						// Search price into product_price_by_qty from $prod->id
						foreach ($prod->prices_by_qty_list[$object->thirdparty->price_level] as $priceforthequantityarray) {
							if ($priceforthequantityarray['rowid'] != $pqp) {
								continue;
							}
							// We found the price
							if ($priceforthequantityarray['price_base_type'] == 'HT') {
								$pu_ht = $priceforthequantityarray['unitprice'];
							} else {
								$pu_ttc = $priceforthequantityarray['unitprice'];
							}
							// Note: the remise_percent or price by qty is used to set data on form, so we will use value from POST.
							break;
						}
					}
				}

				$tmpvat = price2num(preg_replace('/\s*\(.*\)/', '', $tva_tx));
				$tmpprodvat = price2num(preg_replace('/\s*\(.*\)/', '', $prod->tva_tx));

				// if price ht is forced (ie: calculated by margin rate and cost price). TODO Why this ?
				if (!empty($price_ht) || $price_ht === '0') {
					$pu_ht = price2num($price_ht, 'MU');
					$pu_ttc = price2num($pu_ht * (1 + ($tmpvat / 100)), 'MU');
				} elseif ($tmpvat != $tmpprodvat) {
					// On reevalue prix selon taux tva car taux tva transaction peut etre different
					// de ceux du produit par defaut (par exemple si pays different entre vendeur et acheteur).
					if ($price_base_type != 'HT') {
						$pu_ht = price2num($pu_ttc / (1 + ($tmpvat / 100)), 'MU');
					} else {
						$pu_ttc = price2num($pu_ht * (1 + ($tmpvat / 100)), 'MU');
					}
				}

				$desc = '';

				// Define output language
				if (!empty($conf->global->MAIN_MULTILANGS) && !empty($conf->global->PRODUIT_TEXTS_IN_THIRDPARTY_LANGUAGE)) {
					$outputlangs = $langs;
					$newlang = '';
					if (empty($newlang) && GETPOST('lang_id', 'aZ09')) {
						$newlang = GETPOST('lang_id', 'aZ09');
					}
					if (empty($newlang)) {
						$newlang = $object->thirdparty->default_lang;
					}
					if (!empty($newlang)) {
						$outputlangs = new Translate("", $conf);
						$outputlangs->setDefaultLang($newlang);
					}

					$desc = (!empty($prod->multilangs[$outputlangs->defaultlang]["description"])) ? $prod->multilangs[$outputlangs->defaultlang]["description"] : $prod->description;
				} else {
					$desc = $prod->description;
				}

				//If text set in desc is the same as product description (as now it's preloaded) whe add it only one time
				if ($product_desc==$desc && !empty($conf->global->PRODUIT_AUTOFILL_DESC)) {
					$product_desc='';
				}

				if (!empty($product_desc) && !empty($conf->global->MAIN_NO_CONCAT_DESCRIPTION)) {
					$desc = $product_desc;
				} else {
					$desc = dol_concatdesc($desc, $product_desc, '', !empty($conf->global->MAIN_CHANGE_ORDER_CONCAT_DESCRIPTION));
				}

				// Add custom code and origin country into description
				if (empty($conf->global->MAIN_PRODUCT_DISABLE_CUSTOMCOUNTRYCODE) && (!empty($prod->customcode) || !empty($prod->country_code))) {
					$tmptxt = '(';
					// Define output language
					if (!empty($conf->global->MAIN_MULTILANGS) && !empty($conf->global->PRODUIT_TEXTS_IN_THIRDPARTY_LANGUAGE)) {
						$outputlangs = $langs;
						$newlang = '';
						if (empty($newlang) && GETPOST('lang_id', 'alpha')) {
							$newlang = GETPOST('lang_id', 'alpha');
						}
						if (empty($newlang)) {
							$newlang = $object->thirdparty->default_lang;
						}
						if (!empty($newlang)) {
							$outputlangs = new Translate("", $conf);
							$outputlangs->setDefaultLang($newlang);
							$outputlangs->load('products');
						}
						if (!empty($prod->customcode)) {
							$tmptxt .= $outputlangs->transnoentitiesnoconv("CustomCode").': '.$prod->customcode;
						}
						if (!empty($prod->customcode) && !empty($prod->country_code)) {
							$tmptxt .= ' - ';
						}
						if (!empty($prod->country_code)) {
							$tmptxt .= $outputlangs->transnoentitiesnoconv("CountryOrigin").': '.getCountry($prod->country_code, 0, $db, $outputlangs, 0);
						}
					} else {
						if (!empty($prod->customcode)) {
							$tmptxt .= $langs->transnoentitiesnoconv("CustomCode").': '.$prod->customcode;
						}
						if (!empty($prod->customcode) && !empty($prod->country_code)) {
							$tmptxt .= ' - ';
						}
						if (!empty($prod->country_code)) {
							$tmptxt .= $langs->transnoentitiesnoconv("CountryOrigin").': '.getCountry($prod->country_code, 0, $db, $langs, 0);
						}
					}
					$tmptxt .= ')';
					$desc = dol_concatdesc($desc, $tmptxt);
				}

				$type = $prod->type;
				$fk_unit = $prod->fk_unit;
			} else {
				$pu_ht = price2num($price_ht, 'MU');
				$pu_ttc = price2num(GETPOST('price_ttc'), 'MU');
				$tva_npr = (preg_match('/\*/', $tva_tx) ? 1 : 0);
				$tva_tx = str_replace('*', '', $tva_tx);
				$label = (GETPOST('product_label') ? GETPOST('product_label') : '');
				$desc = $product_desc;
				$type = GETPOST('type');

				$fk_unit = GETPOST('units', 'alpha');
				$pu_ht_devise = price2num($price_ht_devise, 'MU');
			}

			// Margin
			$fournprice = price2num(GETPOST('fournprice'.$predef) ? GETPOST('fournprice'.$predef) : '');
			$buyingprice = price2num(GETPOST('buying_price'.$predef) != '' ? GETPOST('buying_price'.$predef) : ''); // If buying_price is '0', we muste keep this value

			$date_start = dol_mktime(GETPOST('date_start'.$predef.'hour'), GETPOST('date_start'.$predef.'min'), GETPOST('date_start'.$predef.'sec'), GETPOST('date_start'.$predef.'month'), GETPOST('date_start'.$predef.'day'), GETPOST('date_start'.$predef.'year'));
			$date_end = dol_mktime(GETPOST('date_end'.$predef.'hour'), GETPOST('date_end'.$predef.'min'), GETPOST('date_end'.$predef.'sec'), GETPOST('date_end'.$predef.'month'), GETPOST('date_end'.$predef.'day'), GETPOST('date_end'.$predef.'year'));

			// Local Taxes
			$localtax1_tx = get_localtax($tva_tx, 1, $object->thirdparty, $tva_npr);
			$localtax2_tx = get_localtax($tva_tx, 2, $object->thirdparty, $tva_npr);

			$info_bits = 0;
			if ($tva_npr) {
				$info_bits |= 0x01;
			}

			if (((!empty($conf->global->MAIN_USE_ADVANCED_PERMS) && empty($user->rights->produit->ignore_price_min_advance)) || empty($conf->global->MAIN_USE_ADVANCED_PERMS)) && (!empty($price_min) && (price2num($pu_ht) * (1 - price2num($remise_percent) / 100) < price2num($price_min)))) {
				$mesg = $langs->trans("CantBeLessThanMinPrice", price(price2num($price_min, 'MU'), 0, $langs, 0, 0, - 1, $conf->currency));
				setEventMessages($mesg, null, 'errors');
			} else {
				// Insert line
				$result = $productDefault->addline($id, $TypeAssignment,$desc, $pu_ht, $qty, $tva_tx, $localtax1_tx, $localtax2_tx, $idprod, $remise_percent, $price_base_type, $pu_ttc, $info_bits, $type, min($rank, count($productDefault->lines) + 1), 0, GETPOST('fk_parent_line'), $fournprice, $buyingprice, $label, $date_start, $date_end, $array_options, $fk_unit, '', 0, $pu_ht_devise);

				if ($result > 0) {
					$db->commit();

					unset($_POST['prod_entry_mode']);
					unset($_POST['typeAssignment']);
					unset($_POST['qty']);
					unset($_POST['type']);
					unset($_POST['remise_percent']);
					unset($_POST['price_ht']);
					unset($_POST['multicurrency_price_ht']);
					unset($_POST['price_ttc']);
					unset($_POST['tva_tx']);
					unset($_POST['product_ref']);
					unset($_POST['product_label']);
					unset($_POST['product_desc']);
					unset($_POST['fournprice']);
					unset($_POST['buying_price']);
					unset($_POST['np_marginRate']);
					unset($_POST['np_markRate']);
					unset($_POST['dp_desc']);
					unset($_POST['idprod']);
					unset($_POST['units']);

					unset($_POST['date_starthour']);
					unset($_POST['date_startmin']);
					unset($_POST['date_startsec']);
					unset($_POST['date_startday']);
					unset($_POST['date_startmonth']);
					unset($_POST['date_startyear']);
					unset($_POST['date_endhour']);
					unset($_POST['date_endmin']);
					unset($_POST['date_endsec']);
					unset($_POST['date_endday']);
					unset($_POST['date_endmonth']);
					unset($_POST['date_endyear']);
				} else {
					$db->rollback();

					setEventMessages($object->error, $object->errors, 'errors');
				}
			}
		}
	}

}
elseif ($action == 'updateline' && $usercancreate){

	$errors = 0;
	if (empty($TypeAssignment)){
		setEventMessage($langs->trans('Assignementmandatory'),'errors');
		$errors++;
		$action = 'editline';
	}

	if ($errors == 0){
		// Update a line within proposal
		// Define info_bits
		$info_bits = 0;
		if (preg_match('/\*/', GETPOST('tva_tx'))) {
			$info_bits |= 0x01;
		}

		// Clean parameters
		$description = dol_htmlcleanlastbr(GETPOST('product_desc', 'restricthtml'));

		// Define vat_rate
		$vat_rate = (GETPOST('tva_tx') ? GETPOST('tva_tx') : 0);
		$vat_rate = str_replace('*', '', $vat_rate);
		$localtax1_rate = get_localtax($vat_rate, 1, $object, $object);
		$localtax2_rate = get_localtax($vat_rate, 2, $object, $object);
		$pu_ht = price2num(GETPOST('price_ht'), '', 2);

		// Add buying price
		$fournprice = price2num(GETPOST('fournprice') ? GETPOST('fournprice') : '');
		$buyingprice = price2num(GETPOST('buying_price') != '' ? GETPOST('buying_price') : ''); // If buying_price is '0', we muste keep this value

		$pu_ht_devise = price2num(GETPOST('multicurrency_subprice'), '', 2);

		$date_start = dol_mktime(GETPOST('date_starthour'), GETPOST('date_startmin'), GETPOST('date_startsec'), GETPOST('date_startmonth'), GETPOST('date_startday'), GETPOST('date_startyear'));
		$date_end = dol_mktime(GETPOST('date_endhour'), GETPOST('date_endmin'), GETPOST('date_endsec'), GETPOST('date_endmonth'), GETPOST('date_endday'), GETPOST('date_endyear'));

		$remise_percent = price2num(GETPOST('remise_percent'), '', 2);

		// Extrafields
		$extralabelsline = $extrafields->fetch_name_optionals_label($object->table_element_line);
		$array_options = $extrafields->getOptionalsFromPost($object->table_element_line);
		// Unset extrafield
		if (is_array($extralabelsline)) {
			// Get extra fields
			foreach ($extralabelsline as $key => $value) {
				unset($_POST["options_".$key]);
			}
		}

		// Define special_code for special lines
		$special_code = GETPOST('special_code', 'int');
		if (!GETPOST('qty')) {
			$special_code = 3;
		}

		// Check minimum price
		$productid = GETPOST('productid', 'int');
		if (!empty($productid)) {
			$product = new Product($db);
			$res = $product->fetch($productid);

			$type = $product->type;

			$price_min = $product->price_min;
			if (!empty($conf->global->PRODUIT_MULTIPRICES) && !empty($object->thirdparty->price_level)) {
				$price_min = $product->multiprices_min [$object->thirdparty->price_level];
			}

			$label = ((GETPOST('update_label') && GETPOST('product_label')) ? GETPOST('product_label') : '');
			if (((!empty($conf->global->MAIN_USE_ADVANCED_PERMS) && empty($user->rights->produit->ignore_price_min_advance)) || empty($conf->global->MAIN_USE_ADVANCED_PERMS)) && ($price_min && (price2num($pu_ht) * (1 - $remise_percent / 100) < price2num($price_min)))) {
				setEventMessages($langs->trans("CantBeLessThanMinPrice", price(price2num($price_min, 'MU'), 0, $langs, 0, 0, - 1, $conf->currency)), null, 'errors');
				$error++;
			}
		} else {
			$type = GETPOST('type');
			$label = (GETPOST('product_label') ? GETPOST('product_label') : '');

			// Check parameters
			if (GETPOST('type') < 0) {
				setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Type")), null, 'errors');
				$error++;
			}
		}

		if (!$error) {
			$db->begin();

			if (empty($user->rights->margins->creer)) {
				foreach ($object->lines as &$line) {
					if ($line->id == GETPOST('lineid', 'int')) {
						$fournprice = $line->fk_fournprice;
						$buyingprice = $line->pa_ht;
						break;
					}
				}
			}

			$qty = price2num(GETPOST('qty', 'alpha'), 'MS');

			$result = $productDefault->updateline(GETPOST('lineid', 'int'), $TypeAssignment,  $pu_ht, $qty, $remise_percent, $vat_rate, $localtax1_rate, $localtax2_rate, $description, 'HT', $info_bits, $special_code, GETPOST('fk_parent_line'), 0, $fournprice, $buyingprice, $label, $type, $date_start, $date_end, $array_options, GETPOST("units"), $pu_ht_devise);

			if ($result >= 0) {
				$db->commit();
				unset($_POST['typeAssignment']);
				unset($_POST['qty']);
				unset($_POST['type']);
				unset($_POST['productid']);
				unset($_POST['remise_percent']);
				unset($_POST['price_ht']);
				unset($_POST['multicurrency_price_ht']);
				unset($_POST['price_ttc']);
				unset($_POST['tva_tx']);
				unset($_POST['product_ref']);
				unset($_POST['product_label']);
				unset($_POST['product_desc']);
				unset($_POST['fournprice']);
				unset($_POST['buying_price']);

				unset($_POST['date_starthour']);
				unset($_POST['date_startmin']);
				unset($_POST['date_startsec']);
				unset($_POST['date_startday']);
				unset($_POST['date_startmonth']);
				unset($_POST['date_startyear']);
				unset($_POST['date_endhour']);
				unset($_POST['date_endmin']);
				unset($_POST['date_endsec']);
				unset($_POST['date_endday']);
				unset($_POST['date_endmonth']);
				unset($_POST['date_endyear']);
			} else {
				$db->rollback();

				setEventMessages($object->error, $object->errors, 'errors');
			}
		}
	}


}elseif ($action == 'ask_deleteline') {

	// Confirmation delete product/service line
	$formconfirm = $form->formconfirm($_SERVER["PHP_SELF"].'?id='.$object->id.'&lineid='.$selected, $langs->trans('DeleteProductLine'), $langs->trans('ConfirmDeleteProductLine'), 'confirm_deleteline', '', 0, 1);


}elseif ($action == 'confirm_deleteline' && $confirm == 'yes' && $usercandelete) {
	// Delete proposal
	// à fetcher d'abord
	$productDefault->fetch($selected);

	$result = $productDefault->delete($user);
	if ($result > 0) {
		header('Location: '.$_SERVER["PHP_SELF"].'?id='.$id);
		exit();
	}
}
// Print form confirm



/**
 * VIEW
 */


$acceptedEncodings = array('UTF-8', 'latin1', 'ISO-8859-1', 'ISO-8859-15', 'macintosh');
llxHeader('', '', '', '', '','', '', 0, 0);

$head = societe_prepare_head($object);
print  dol_get_fiche_head($head, 'productdefault', $langs->trans('productdefault'), -1, 'productdefault');
$linkback = '<a href="'.DOL_URL_ROOT.'/societe/list.php?restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';
dol_banner_tab($object, 'id', $linkback,  ($user->socid ? 0 : 1), 'rowid', 'nom');
print "<hr>";

$productDefault->lines = $productDefault->fetchAll("","",0,0,array('fk_soc' => $id));

// insert product

print '	<form name="addproduct" id="addproduct" action="'.$_SERVER["PHP_SELF"].'?id='.$object->id.(($action != 'editline') ? '' : '#line_'.GETPOST('lineid', 'int')).'" method="POST">
	<input type="hidden" name="token" value="' . newToken().'">
	<input type="hidden" name="action" value="' . (($action != 'editline') ? 'addline' : 'updateline').'">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="page_y" value="">
	<input type="hidden" name="id" value="' . $object->id.'">
	';


print load_fiche_titre($langs->transnoentities("AssignedProducts", $object->getNomUrl(1)),'','product');

print '<table id="tablelines" class="noborder noshadow" width="100%">';

	if (!empty($conf->use_javascript_ajax)) {
		include DOL_DOCUMENT_ROOT.'/core/tpl/ajaxrow.tpl.php';
	}
	// ajout de la colonne assigment
	printColAssignment($productDefault);

	if ( is_array( $productDefault->lines) && !empty($productDefault->lines)) {


		$disableedit = 0;
		$disablemove = 1;
		$disableremove = 0;
		$usemargins = 1; // en fait c'est déjà fait dans les tpl que de ligne j'ai copiés et modifiés


		// nécessaire pour la construction de l'url du bouton editline
		$productDefault->id = $object->id;

		// nécessaire pour forcer l'utilisation de la copie des tpl create view update et title (eux mêmes obligatoires pour forcer l'utilisation du module marges)
		$conf->modules_parts['tpl'] = array('productdefault' => '/productdefault/core/tpl');

		// ici on met 2 fois $object pour respecter le nombre de paramtres, mais en vérité, la fonction n'utilise pas ces paramètres
		$productDefault->printObjectLines($action, $object,$object,$selected, 1);

		// Affichage des types assignments liés aux lignes
		showAssignmentLine($productDefault, $object);
	}



	if ($action !== 'editline'){
		$productDefault->formAddObjectLine(1, $object, $object);
	}

	showMultiSelect($productDefault, $object, $form, $lineid, $action);

print "</table>";
print "</form>";


print $formconfirm;


print llxFooter();


/**
 * @return void
 */
function printColAssignment($productDefault) {

	global $langs;

	$langs->load('productdefault@productdefault');
	?>

	<script>
		$(document).ready(function() {
			console.log("col to add")
			// Affichage du titre de la colonne
			let title = <?php echo json_encode($langs->trans('ConcernedDocuments')); ?>;
			$('<td>'+title+'</td>').insertAfter($(".liste_titre").find(".linecoldescription"));


			// Affichage du td des lignes
			let cl = $("tr[id^='row-']").find(".linecoldescription");
			console.log(cl);
			$('<td class="content_line_Assignment"></td>').insertAfter(cl);

			let Tassignment = <?php echo json_encode($productDefault->Tassignment); ?>;
			console.log(Tassignment);
			$('<td id="td_content_line_Assignment"></td>').insertAfter($("#tva_tx").parent('td').prev());
			//$("#td_select_users_guests").append($("#select_users_guests"));

		});

	</script>

	<?php

}

/**
 * @param $productDefault
 * @param $object
 * @return void
 */
function showAssignmentLine($productDefault, $object){
	global $db, $langs;
	// Affichage des type assignment liés aux lignes
	if(!empty($productDefault->lines)) {

		$typeByLine =  getAssignmentLines($productDefault, $object)

		?>
		<script>
			$(document).ready(function() {
				let UsersByLine = <?php echo json_encode($typeByLine['types']); ?>;
				"tr[id^='row-']"
				$("#tablelines").find("tr[id^='row-']").each(function() {
					let list = [];
					if(typeof UsersByLine[$(this).data('id')] !== 'undefined'){
						list = UsersByLine[$(this).data('id')].split(" ");
					}


					list.forEach((item, index) => {
						if (item){
							$(this).find(".content_line_Assignment").append('<span class="badgeneutral " style="margin :5px; padding:5px;">' +  item + '</span>');
						}

					})

				});
			});
		</script>

		<?php
	}
}
/**
 * @param $productDefault
 * @param $object
 * @return array
 */
function getAssignmentLines($productDefault, $object){

	global $langs, $db;
	// requete qui renvoie  toutes les lignes avec les types pour chaque ligne

	// Instanciation d'un tableau de type d'assignation  associés aux lignes de productDefault de ce fk_soc
	$sql  =" SELECT pa.fk_line_productdefault as id_line, pa.type_assignment as type from ".MAIN_DB_PREFIX."productdefault_productthirdpartydefault as pp ";
	$sql .=" INNER JOIN ".MAIN_DB_PREFIX."productdefault_assignment pa ON (pp.rowid = pa.fk_line_productdefault)";
	$sql .=" WHERE pp.fk_soc=".((int)$object->id);

	$resql = $db->query($sql);
	$typeByLine = array();
	if ($resql) {
		while ($ob = $db->fetch_object($resql)) {
			$id_line = $ob->id_line;
			$typeByLine['types'][$id_line] .=  " " . $langs->trans($productDefault->Tassignment[$ob->type]) ;
			$typeByLine['ids'][$id_line][] = $ob->type;
		}
	}
	//var_dump($typeByLine);
	return $typeByLine;

}

/**
 * @param $productDefault
 * @param $object
 * @param $form
 * @param $lineid
 * @return void
 */
function showMultiSelect($productDefault, $object,&$form , $lineid,$action){

	if ($action == "editline") $typeByLine = getAssignmentLines($productDefault, $object);


	// renseignement du multiSelectarray
	$multi = $form->multiselectarray('typeAssignment', $productDefault->Tassignment, $typeByLine['ids'][$lineid], null, null, null, null, "300px;");
	?><script>
		$(document).ready(function () {
			let multi = <?php  echo json_encode($multi) ?>;
			$("#td_content_line_Assignment").append(multi);
		});
	</script><?php
}


