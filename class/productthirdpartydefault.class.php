<?php
/* Copyright (C) 2017  Laurent Destailleur <eldy@users.sourceforge.net>
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
 * \file        class/productthirdpartydefault.class.php
 * \ingroup     productdefault
 * \brief       This file is a CRUD class file for ProductThirdpartyDefault (Create/Read/Update/Delete)
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class for ProductThirdpartyDefault
 */
class ProductThirdpartyDefault extends CommonObject
{
	/**
	 * @var string ID of module.
	 */
	public $module = 'productdefault';

	/**
	 * @var string ID to identify managed object.
	 */
	public $element = 'productdefault';

	/**
	 * @var string Name of table without prefix where object is stored. This is also the key used for extrafields management.
	 */
	public $table_element 		= 'productdefault_productthirdpartydefault';
	public $table_element_line 	= 'productdefault_productthirdpartydefault';

	/**
	 * @var int  Does this object support multicompany module ?
	 * 0=No test on entity, 1=Test with field entity, 'field@table'=Test with link by field@table
	 */
	public $ismultientitymanaged = 0;

	/**
	 * @var int  Does object support extrafields ? 0=No, 1=Yes
	 */
	public $isextrafieldmanaged = 1;

	/**
	 * @var string String with name of icon for productthirdpartydefault. Must be the part after the 'object_' into object_productthirdpartydefault.png
	 */
	public $picto = 'productthirdpartydefault@productdefault';

	// type d'assignation de la ligne de produit
	const TYPE_ASSIGNMENT_PROPOSAL = 1;
	const TYPE_ASSIGNMENT_ORDER = 2;

	public $Tassignment = array();


	/**
	 *  'type' field format ('integer', 'integer:ObjectClass:PathToClass[:AddCreateButtonOrNot[:Filter]]', 'sellist:TableName:LabelFieldName[:KeyFieldName[:KeyFieldParent[:Filter]]]', 'varchar(x)', 'double(24,8)', 'real', 'price', 'text', 'text:none', 'html', 'date', 'datetime', 'timestamp', 'duration', 'mail', 'phone', 'url', 'password')
	 *         Note: Filter can be a string like "(t.ref:like:'SO-%') or (t.date_creation:<:'20160101') or (t.nature:is:NULL)"
	 *  'label' the translation key.
	 *  'picto' is code of a picto to show before value in forms
	 *  'enabled' is a condition when the field must be managed (Example: 1 or '$conf->global->MY_SETUP_PARAM)
	 *  'position' is the sort order of field.
	 *  'notnull' is set to 1 if not null in database. Set to -1 if we must set data to null if empty ('' or 0).
	 *  'visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create). 5=Visible on list and view only (not create/not update). Using a negative value means field is not shown by default on list but can be selected for viewing)
	 *  'noteditable' says if field is not editable (1 or 0)
	 *  'default' is a default value for creation (can still be overwrote by the Setup of Default Values if field is editable in creation form). Note: If default is set to '(PROV)' and field is 'ref', the default value will be set to '(PROVid)' where id is rowid when a new record is created.
	 *  'index' if we want an index in database.
	 *  'foreignkey'=>'tablename.field' if the field is a foreign key (it is recommanded to name the field fk_...).
	 *  'searchall' is 1 if we want to search in this field when making a search from the quick search button.
	 *  'isameasure' must be set to 1 if you want to have a total on list for this field. Field type must be summable like integer or double(24,8).
	 *  'css' and 'cssview' and 'csslist' is the CSS style to use on field. 'css' is used in creation and update. 'cssview' is used in view mode. 'csslist' is used for columns in lists. For example: 'css'=>'minwidth300 maxwidth500 widthcentpercentminusx', 'cssview'=>'wordbreak', 'csslist'=>'tdoverflowmax200'
	 *  'help' is a 'TranslationString' to use to show a tooltip on field. You can also use 'TranslationString:keyfortooltiponlick' for a tooltip on click.
	 *  'showoncombobox' if value of the field must be visible into the label of the combobox that list record
	 *  'disabled' is 1 if we want to have the field locked by a 'disabled' attribute. In most cases, this is never set into the definition of $fields into class, but is set dynamically by some part of code.
	 *  'arrayofkeyval' to set a list of values if type is a list of predefined values. For example: array("0"=>"Draft","1"=>"Active","-1"=>"Cancel"). Note that type can be 'integer' or 'varchar'
	 *  'autofocusoncreate' to have field having the focus on a create form. Only 1 field should have this property set to 1.
	 *  'comment' is not used. You can store here any text of your choice. It is not used by application.
	 *
	 *  Note: To have value dynamic, you can set value to 0 in definition and edit the value on the fly into the constructor.
	 */

	// BEGIN MODULEBUILDER PROPERTIES
	/**
	 * @var array  Array with all fields and their property. Do not use it as a static var. It may be modified by constructor.
	 */
	public $fields=array(
		'rowid' =>array('type'=>'integer', 'label'=>'TechnicalID', 'enabled'=>1, 'visible'=>-1, 'notnull'=>1, 'position'=>10),
		'fk_unit' =>array('type'=>'integer', 'label'=>'fk_unit', 'enabled'=>1, 'visible'=>-1, 'notnull'=>1, 'position'=>11),
		'entity' =>array('type'=>'integer', 'label'=>'Entity', 'default'=>1, 'enabled'=>1, 'visible'=>-2, 'notnull'=>1, 'position'=>15, 'index'=>1),
		'fk_soc' =>array('type'=>'integer:Societe:societe/class/societe.class.php', 'label'=>'ThirdParty', 'enabled'=>1, 'visible'=>-1, 'position'=>23),
		//'fk_projet' =>array('type'=>'integer:Project:projet/class/project.class.php:1:fk_statut=1', 'label'=>'Fk projet', 'enabled'=>1, 'visible'=>-1, 'position'=>24),
		'fk_product' =>array('type'=>'integer:Product:product/class/product.class.php:1:fk_statut=1', 'label'=>'Fk product', 'enabled'=>1, 'visible'=>-1, 'position'=>25),
		'product_type' =>array('type'=>'integer', 'label'=>'productType', 'enabled'=>1, 'visible'=>-1, 'position'=>25),
		'date_start' =>array('type'=>'datetime', 'label'=>'DateStart', 'enabled'=>1, 'visible'=>-1, 'position'=>55),
		'date_end' =>array('type'=>'datetime', 'label'=>'DateEnd', 'enabled'=>1, 'visible'=>-1, 'position'=>55),
		//'tms' =>array('type'=>'timestamp', 'label'=>'DateModification', 'enabled'=>1, 'visible'=>-1, 'notnull'=>1, 'position'=>25),
		//'datec' =>array('type'=>'datetime', 'label'=>'DateCreation', 'enabled'=>1, 'visible'=>-1, 'position'=>55),
		//'datep' =>array('type'=>'date', 'label'=>'Date', 'enabled'=>1, 'visible'=>-1, 'position'=>60),
		//'fin_validite' =>array('type'=>'datetime', 'label'=>'DateEnd', 'enabled'=>1, 'visible'=>-1, 'position'=>65),
		//'date_valid' =>array('type'=>'datetime', 'label'=>'DateValidation', 'enabled'=>1, 'visible'=>-1, 'position'=>70),
		//'date_cloture' =>array('type'=>'datetime', 'label'=>'DateClosing', 'enabled'=>1, 'visible'=>-1, 'position'=>75),
		//'fk_user_author' =>array('type'=>'integer:User:user/class/user.class.php', 'label'=>'Fk user author', 'enabled'=>1, 'visible'=>-1, 'position'=>80),
		//'fk_user_modif' =>array('type'=>'integer:User:user/class/user.class.php', 'label'=>'UserModif', 'enabled'=>1, 'visible'=>-2, 'notnull'=>-1, 'position'=>85),
		//'fk_user_valid' =>array('type'=>'integer:User:user/class/user.class.php', 'label'=>'UserValidation', 'enabled'=>1, 'visible'=>-1, 'position'=>90),
		'price' =>array('type'=>'double', 'label'=>'Price', 'enabled'=>1, 'visible'=>-1, 'position'=>105),
		'subprice' =>array('type'=>'double', 'label'=>'subPrice', 'enabled'=>1, 'visible'=>-1, 'position'=>105),
		'remise_percent' =>array('type'=>'double', 'label'=>'RelativeDiscount', 'enabled'=>1, 'visible'=>-1, 'position'=>110),
		//'remise_absolue' =>array('type'=>'double', 'label'=>'CustomerRelativeDiscount', 'enabled'=>1, 'visible'=>-1, 'position'=>115),
		//'remise' =>array('type'=>'double', 'label'=>'Remise', 'enabled'=>1, 'visible'=>-1, 'position'=>120),
		'total_ht' =>array('type'=>'double(24,8)', 'label'=>'TotalHT', 'enabled'=>1, 'visible'=>-1, 'position'=>125, 'isameasure'=>1),
		'total_tva' =>array('type'=>'double(24,8)', 'label'=>'VAT', 'enabled'=>1, 'visible'=>-1, 'position'=>130, 'isameasure'=>1),
		'buy_price_ht' =>array('type'=>'double(24,8)', 'label'=>'BuyingPrice', 'enabled'=>1, 'visible'=>-1, 'position'=>125, 'isameasure'=>1),
		//'localtax1' =>array('type'=>'double(24,8)', 'label'=>'LocalTax1', 'enabled'=>1, 'visible'=>-1, 'position'=>135, 'isameasure'=>1),
		//'localtax2' =>array('type'=>'double(24,8)', 'label'=>'LocalTax2', 'enabled'=>1, 'visible'=>-1, 'position'=>140, 'isameasure'=>1),
		'total_ttc' =>array('type'=>'double(24,8)', 'label'=>'TotalTTC', 'enabled'=>1, 'visible'=>-1, 'position'=>145, 'isameasure'=>1),
		//'fk_account' =>array('type'=>'integer', 'label'=>'BankAccount', 'enabled'=>1, 'visible'=>-1, 'position'=>150),
		'description' =>array('type'=>'varchar(255)', 'label'=>'description', 'enabled'=>1, 'visible'=>-1, 'position'=>155),
		'qty' =>array('type'=>'integer', 'label'=>'qty', 'enabled'=>1, 'visible'=>-1, 'position'=>160),
		'tva_tx' =>array('type'=>'integer', 'label'=>'tvaTx', 'enabled'=>1, 'visible'=>-1, 'position'=>165),
		'vat_src_code' =>array('type'=>'varchar(10)', 'label'=>'var_src_code', 'enabled'=>1, 'visible'=>-1, 'position'=>155),
		//'note_private' =>array('type'=>'text', 'label'=>'NotePublic', 'enabled'=>1, 'visible'=>0, 'position'=>170),
		//'note_public' =>array('type'=>'text', 'label'=>'NotePrivate', 'enabled'=>1, 'visible'=>0, 'position'=>175),
		'fk_multicurrency' =>array('type'=>'integer', 'label'=>'MulticurrencyID', 'enabled'=>1, 'visible'=>-1, 'position'=>230),
		'multicurrency_code' =>array('type'=>'varchar(255)', 'label'=>'MulticurrencyCurrency', 'enabled'=>'$conf->multicurrency->enabled', 'visible'=>-1, 'position'=>235),
		//'multicurrency_tx' =>array('type'=>'double(24,8)', 'label'=>'MulticurrencyRate', 'enabled'=>'$conf->multicurrency->enabled', 'visible'=>-1, 'position'=>240, 'isameasure'=>1),
		'multicurrency_subprice' =>array('type'=>'double', 'label'=>'subPrice', 'enabled'=>1, 'visible'=>-1, 'position'=>105),
		'multicurrency_total_ht' =>array('type'=>'double(24,8)', 'label'=>'MulticurrencyAmountHT', 'enabled'=>'$conf->multicurrency->enabled', 'visible'=>-1, 'position'=>245, 'isameasure'=>1),
		'multicurrency_total_tva' =>array('type'=>'double(24,8)', 'label'=>'MulticurrencyAmountVAT', 'enabled'=>'$conf->multicurrency->enabled', 'visible'=>-1, 'position'=>250, 'isameasure'=>1),
		'multicurrency_total_ttc' =>array('type'=>'double(24,8)', 'label'=>'MulticurrencyAmountTTC', 'enabled'=>'$conf->multicurrency->enabled', 'visible'=>-1, 'position'=>255, 'isameasure'=>1),
		//'fk_statut' =>array('type'=>'smallint(6)', 'label'=>'Status', 'enabled'=>1, 'visible'=>-1, 'notnull'=>1, 'position'=>500),
		'import_key' =>array('type'=>'varchar(14)', 'label'=>'ImportId', 'enabled'=>1, 'visible'=>-2, 'position'=>900),
	);
	/**
	 * @var string ID to identify managed object
	 */


	public $rowid;
	public $entity;
	// From llx_societe
	public $fk_soc;
	public $fk_element;
	//public $fk_parent_line;
	public $desc; // Description ligne
	public $fk_product; // Id produit predefini

	/**
	 * Product type.
	 * @var int
	 * @see Product::TYPE_PRODUCT, Product::TYPE_SERVICE
	 */
	public $product_type;

	public $qty;
	public $tva_tx;
	public $vat_src_code;
	public $subprice;
	public $remise_percent;
	public $fk_remise_except;
	public $rang = 0;
	public $fk_fournprice;
	public $pa_ht;
	public $marge_tx;
	public $marque_tx;
	public $special_code; // Tag for special lines (exlusive tags)
	// 1: frais de port
	// 2: ecotaxe
	// 3: option line (when qty = 0)
	public $info_bits = 0; // Some other info:
	// Bit 0: 	0 si TVA normal - 1 si TVA NPR
	// Bit 1:	0 ligne normale - 1 si ligne de remise fixe
	public $total_ht; // Total HT  de la ligne toute quantite et incluant la remise ligne
	public $total_tva; // Total TVA  de la ligne toute quantite et incluant la remise ligne
	public $total_ttc; // Total TTC de la ligne toute quantite et incluant la remise ligne

	/**
	 * @deprecated
	 * @see $remise_percent, $fk_remise_except
	 */
	public $remise;
	/**
	 * @deprecated
	 * @see $subprice
	 */
	public $price;

	/**
	 * Product reference
	 * @var string
	 */
	public $product_ref;
	/**
	 * @deprecated
	 * @see $product_label
	 */
	public $libelle;
	/**
	 * @deprecated
	 * @see $product_label
	 */
	public $label;
	/**
	 *  Product label
	 * @var string
	 */
	public $product_label;
	/**
	 * Product description
	 * @var string
	 */
	public $product_desc;

	/**
	 * Product use lot
	 * @var string
	 */
	public $product_tobatch;

	/**
	 * Product barcode
	 * @var string
	 */
	public $product_barcode;

	public $localtax1_tx; // Local tax 1
	public $localtax2_tx; // Local tax 2
	public $localtax1_type; // Local tax 1 type
	public $localtax2_type; // Local tax 2 type
	public $total_localtax1; // Line total local tax 1
	public $total_localtax2; // Line total local tax 2

	public $date_start;
	public $date_end;

	public $skip_update_total; // Skip update price total for special lines

	// Multicurrency
	public $fk_multicurrency;
	public $multicurrency_code;
	public $multicurrency_subprice;
	public $multicurrency_total_ht;
	public $multicurrency_total_tva;
	public $multicurrency_total_ttc;
	// END MODULEBUILDER PROPERTIES;

	public $statut = 0;
	public $fk_unit;
	// /**
	//  * @var ProductThirdpartyDefaultLine[]     Array of subtable lines
	//  */
	 public $lines = array();



	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		global $conf, $langs;

		$this->db = $db;

		if (empty($conf->global->MAIN_SHOW_TECHNICAL_ID) && isset($this->fields['rowid'])) {
			$this->fields['rowid']['visible'] = 0;
		}
		if (empty($conf->multicompany->enabled) && isset($this->fields['entity'])) {
			$this->fields['entity']['enabled'] = 0;
		}

		// Example to show how to set values of fields definition dynamically
		if ($user->rights->productdefault->productthirdpartydefault->read) {
			$this->fields['myfield']['visible'] = 1;
			$this->fields['myfield']['noteditable'] = 0;
		}

		// Unset fields that are disabled
		foreach ($this->fields as $key => $val) {
			if (isset($val['enabled']) && empty($val['enabled'])) {
				unset($this->fields[$key]);
			}
		}

		// Translate some data of arrayofkeyval
		if (is_object($langs)) {
			foreach ($this->fields as $key => $val) {
				if (!empty($val['arrayofkeyval']) && is_array($val['arrayofkeyval'])) {
					foreach ($val['arrayofkeyval'] as $key2 => $val2) {
						$this->fields[$key]['arrayofkeyval'][$key2] = $langs->trans($val2);
					}
				}
			}
		}


		$this->Tassignment[SELF::TYPE_ASSIGNMENT_PROPOSAL] = $langs->trans('TYPE_ASSIGNMENT_PROPOSAL_LANG');
		$this->Tassignment[SELF::TYPE_ASSIGNMENT_ORDER] = $langs->trans('TYPE_ASSIGNMENT_ORDER_LANG');
	}

	/**
	 * Create object into database
	 *
	 * @param  User $user      User that creates
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, Id of created object if OK
	 */
	/*public function create(User $user, $notrigger = false)
	{
		$resultcreate = $this->createCommon($user, $notrigger);

		//$resultvalidate = $this->validate($user, $notrigger);

		return $resultcreate;
	}*/

	/**
	 *	Returns the label, shot_label or code found in units dictionary from ->fk_unit.
	 *  A langs->trans() must be called on result to get translated value.
	 *
	 * 	@param	string $type 	Label type ('long', 'short' or 'code'). This can be a translation key.
	 *	@return	string|int 		<0 if KO, label if OK (Example: 'long', 'short' or 'unitCODE')
	 */
	public function getLabelOfUnit($type = 'long')
	{
		global $langs;

		if (empty($this->fk_unit)) {
			return '';
		}

		$langs->load('products');

		$label_type = 'label';

		$label_type = 'label';
		if ($type == 'short') {
			$label_type = 'short_label';
		} elseif ($type == 'code') {
			$label_type = 'code';
		}

		$sql = 'select '.$label_type.', code from '.MAIN_DB_PREFIX.'c_units where rowid = '.((int) $this->fk_unit);
		$resql = $this->db->query($sql);
		if ($resql && $this->db->num_rows($resql) > 0) {
			$res = $this->db->fetch_array($resql);
			if ($label_type == 'code') {
				$label = 'unit'.$res['code'];
			} else {
				$label = $res[$label_type];
			}
			$this->db->free($resql);
			return $label;
		} else {
			$this->error = $this->db->error().' sql='.$sql;
			dol_syslog(get_class($this)."::getLabelOfUnit Error ".$this->error, LOG_ERR);
			return -1;
		}
	}
	/**
	 * @param $desc
	 * @param $pu_ht
	 * @param $qty
	 * @param $txtva
	 * @param $txlocaltax1
	 * @param $txlocaltax2
	 * @param $fk_product
	 * @param $remise_percent
	 * @param $price_base_type
	 * @param $pu_ttc
	 * @param $info_bits
	 * @param $type
	 * @param $rang
	 * @param $special_code
	 * @param $fk_parent_line
	 * @param $fk_fournprice
	 * @param $pa_ht
	 * @param $label
	 * @param $date_start
	 * @param $date_end
	 * @param $array_options
	 * @param $fk_unit
	 * @param $origin
	 * @param $origin_id
	 * @param $pu_ht_devise
	 * @param $fk_remise_except
	 * @return void
	 */
	public function create($desc, $pu_ht, $qty, $txtva, $txlocaltax1 = 0.0, $txlocaltax2 = 0.0, $fk_product = 0, $remise_percent = 0.0, $price_base_type = 'HT', $pu_ttc = 0.0, $info_bits = 0, $type = 0, $rang = -1, $special_code = 0, $fk_parent_line = 0, $fk_fournprice = 0, $pa_ht = 0, $label = '', $date_start = '', $date_end = '', $array_options = 0, $fk_unit = null, $origin = '', $origin_id = 0, $pu_ht_devise = 0, $fk_remise_except = 0){

		$this->addline($desc, $pu_ht, $qty, $txtva, $txlocaltax1 = 0.0, $txlocaltax2 = 0.0, $fk_product = 0, $remise_percent = 0.0, $price_base_type = 'HT', $pu_ttc = 0.0, $info_bits = 0, $type = 0, $rang = -1, $special_code = 0, $fk_parent_line = 0, $fk_fournprice = 0, $pa_ht = 0, $label = '', $date_start = '', $date_end = '', $array_options = 0, $fk_unit = null, $origin = '', $origin_id = 0, $pu_ht_devise = 0, $fk_remise_except = 0);

	}

	/**
	 *    	Add a productDefault line into database (linked to product/service or not)
	 *      The parameters are already supposed to be appropriate and with final values to the call
	 *      of this method. Also, for the VAT rate, it must have already been defined
	 *      by whose calling the method get_default_tva (societe_vendeuse, societe_acheteuse, '' product)
	 *      and desc must already have the right value (it's up to the caller to manage multilanguage)
	 *
	 * 		@param    	string		$desc				Description of line
	 * 		@param    	float		$pu_ht				Unit price
	 * 		@param    	float		$qty             	Quantity
	 * 		@param    	float		$txtva           	Force Vat rate, -1 for auto (Can contain the vat_src_code too with syntax '9.9 (CODE)')
	 * 		@param		float		$txlocaltax1		Local tax 1 rate (deprecated, use instead txtva with code inside)
	 *  	@param		float		$txlocaltax2		Local tax 2 rate (deprecated, use instead txtva with code inside)
	 *		@param    	int			$fk_product      	Product/Service ID predefined
	 * 		@param    	float		$remise_percent  	Pourcentage de remise de la ligne
	 * 		@param    	string		$price_base_type	HT or TTC
	 * 		@param    	float		$pu_ttc             Prix unitaire TTC
	 * 		@param    	int			$info_bits			Bits for type of lines
	 *      @param      int			$type               Type of line (0=product, 1=service). Not used if fk_product is defined, the type of product is used.
	 *      @param      int			$rang               Position of line
	 *      @param		int			$special_code		Special code (also used by externals modules!)
	 *      @param		int			$fk_parent_line		Id of parent line
	 *      @param		int			$fk_fournprice		Id supplier price
	 *      @param		int			$pa_ht				Buying price without tax
	 *      @param		string		$label				???
	 *		@param      int			$date_start       	Start date of the line
	 *		@param      int			$date_end         	End date of the line
	 *      @param		array		$array_options		extrafields array
	 * 		@param 		string		$fk_unit 			Code of the unit to use. Null to use the default one
	 *      @param		string		$origin				Depend on global conf MAIN_CREATEFROM_KEEP_LINE_ORIGIN_INFORMATION can be 'orderdet', 'propaldet'..., else 'order','propal,'....
	 *      @param		int			$origin_id			Depend on global conf MAIN_CREATEFROM_KEEP_LINE_ORIGIN_INFORMATION can be Id of origin object (aka line id), else object id
	 * 		@param		double		$pu_ht_devise		Unit price in currency
	 * 		@param		int    		$fk_remise_except	Id discount if line is from a discount
	 *    	@return    	int         	    			>0 if OK, <0 if KO
	 *    	@see       	add_product()
	 */
	public function addline($fk_soc, $typeAssignment, $desc, $pu_ht, $qty, $txtva, $txlocaltax1 = 0.0, $txlocaltax2 = 0.0, $fk_product = 0, $remise_percent = 0.0, $price_base_type = 'HT', $pu_ttc = 0.0, $info_bits = 0, $type = 0, $rang = -1, $special_code = 0, $fk_parent_line = 0, $fk_fournprice = 0, $pa_ht = 0, $label = '', $date_start = '', $date_end = '', $array_options = 0, $fk_unit = null, $origin = '', $origin_id = 0, $pu_ht_devise = 0, $fk_remise_except = 0)
	{
		global $mysoc, $conf, $langs;

		dol_syslog(get_class($this)."::addline propalid=$this->id, desc=$desc, pu_ht=$pu_ht, qty=$qty, txtva=$txtva, fk_product=$fk_product, remise_except=$remise_percent, price_base_type=$price_base_type, pu_ttc=$pu_ttc, info_bits=$info_bits, type=$type, fk_remise_except=".$fk_remise_except);

			include_once DOL_DOCUMENT_ROOT.'/core/lib/price.lib.php';

			// Clean parameters
			if (empty($remise_percent)) {
				$remise_percent = 0;
			}
			if (empty($qty)) {
				$qty = 0;
			}
			if (empty($info_bits)) {
				$info_bits = 0;
			}
			if (empty($rang)) {
				$rang = 0;
			}
			if (empty($fk_parent_line) || $fk_parent_line < 0) {
				$fk_parent_line = 0;
			}

			$remise_percent = price2num($remise_percent);
			$qty = price2num($qty);
			$pu_ht = price2num($pu_ht);
			$pu_ht_devise = price2num($pu_ht_devise);
			$pu_ttc = price2num($pu_ttc);
			if (!preg_match('/\((.*)\)/', $txtva)) {
				$txtva = price2num($txtva); // $txtva can have format '5,1' or '5.1' or '5.1(XXX)', we must clean only if '5,1'
			}
			$txlocaltax1 = price2num($txlocaltax1);
			$txlocaltax2 = price2num($txlocaltax2);
			$pa_ht = price2num($pa_ht);
			if ($price_base_type == 'HT') {
				$pu = $pu_ht;
			} else {
				$pu = $pu_ttc;
			}

			// Check parameters
			if ($type < 0) {
				return -1;
			}

			if ($date_start && $date_end && $date_start > $date_end) {
				$langs->load("errors");
				$this->error = $langs->trans('ErrorStartDateGreaterEnd');
				return -1;
			}

			$this->db->begin();

			$product_type = $type;
			if (!empty($fk_product) && $fk_product > 0) {
				$product = new Product($this->db);
				$result = $product->fetch($fk_product);
				$product_type = $product->type;

				if (!empty($conf->global->STOCK_MUST_BE_ENOUGH_FOR_PROPOSAL) && $product_type == 0 && $product->stock_reel < $qty) {
					$langs->load("errors");
					$this->error = $langs->trans('ErrorStockIsNotEnoughToAddProductOnProposal', $product->ref);
					$this->db->rollback();
					return -3;
				}
			}

			// Calcul du total TTC et de la TVA pour la ligne a partir de
			// qty, pu, remise_percent et txtva
			// TRES IMPORTANT: C'est au moment de l'insertion ligne qu'on doit stocker
			// la part ht, tva et ttc, et ce au niveau de la ligne qui a son propre taux tva.

			$localtaxes_type = getLocalTaxesFromRate($txtva, 0, $this->thirdparty, $mysoc);

			// Clean vat code
			$reg = array();
			$vat_src_code = '';
			$reg = array();
			if (preg_match('/\((.*)\)/', $txtva, $reg)) {
				$vat_src_code = $reg[1];
				$txtva = preg_replace('/\s*\(.*\)/', '', $txtva); // Remove code into vatrate.
			}

			$tabprice = calcul_price_total($qty, $pu, $remise_percent, $txtva, $txlocaltax1, $txlocaltax2, 0, $price_base_type, $info_bits, $product_type, $mysoc, $localtaxes_type, 100, $this->multicurrency_tx, $pu_ht_devise);

			$total_ht  = $tabprice[0];
			$total_tva = $tabprice[1];
			$total_ttc = $tabprice[2];
			$total_localtax1 = $tabprice[9];
			$total_localtax2 = $tabprice[10];
			$pu_ht  = $tabprice[3];
			$pu_tva = $tabprice[4];
			$pu_ttc = $tabprice[5];

			// MultiCurrency
			$multicurrency_total_ht  = $tabprice[16];
			$multicurrency_total_tva = $tabprice[17];
			$multicurrency_total_ttc = $tabprice[18];
			$pu_ht_devise = $tabprice[19];

			// Rang to use
			$ranktouse = $rang;
			if ($ranktouse == -1) {
				$rangmax = $this->line_max($fk_parent_line);
				$ranktouse = $rangmax + 1;
			}

			// TODO A virer
			// Anciens indicateurs: $price, $remise (a ne plus utiliser)
			$price = $pu;
			$remise = 0;
			if ($remise_percent > 0) {
				$remise = round(($pu * $remise_percent / 100), 2);
				$price = $pu - $remise;
			}

			// Insert line
			$this->line = new $this($this->db);

			//@todo choisir
			$this->line->fk_soc =$fk_soc;
			$this->fk_soc =$fk_soc;


			$this->line->context = $this->context;

			$this->line->fk_propal = $this->id;
			$this->line->label = $label;
			$this->line->desc = $desc;
			$this->line->qty = $qty;

			$this->line->vat_src_code = $vat_src_code;
			$this->line->tva_tx = $txtva;
			$this->line->localtax1_tx = ($total_localtax1 ? $localtaxes_type[1] : 0);
			$this->line->localtax2_tx = ($total_localtax2 ? $localtaxes_type[3] : 0);
			$this->line->localtax1_type = empty($localtaxes_type[0]) ? '' : $localtaxes_type[0];
			$this->line->localtax2_type = empty($localtaxes_type[2]) ? '' : $localtaxes_type[2];
			$this->line->fk_product = $fk_product;
			$this->line->product_type = $type;
			$this->line->fk_remise_except = $fk_remise_except;
			$this->line->remise_percent = $remise_percent;
			$this->line->subprice = $pu_ht;
			$this->line->rang = $ranktouse;
			$this->line->info_bits = $info_bits;
			$this->line->total_ht = $total_ht;
			$this->line->total_tva = $total_tva;
			$this->line->total_localtax1 = $total_localtax1;
			$this->line->total_localtax2 = $total_localtax2;
			$this->line->total_ttc = $total_ttc;
			$this->line->special_code = $special_code;
			$this->line->fk_parent_line = $fk_parent_line;
			$this->line->fk_unit = $fk_unit;

			$this->line->date_start = $date_start;
			$this->line->date_end = $date_end;

			$this->line->fk_fournprice = $fk_fournprice;
			$this->line->pa_ht = $pa_ht;

			//$this->line->origin_id = $origin_id;
			//$this->line->origin = $origin;

			// Multicurrency
			$this->line->fk_multicurrency = $this->fk_multicurrency;
			$this->line->multicurrency_code = $this->multicurrency_code;
			$this->line->multicurrency_subprice		= $pu_ht_devise;
			$this->line->multicurrency_total_ht 	= $multicurrency_total_ht;
			$this->line->multicurrency_total_tva 	= $multicurrency_total_tva;
			$this->line->multicurrency_total_ttc 	= $multicurrency_total_ttc;

			// Mise en option de la ligne
			if (empty($qty) && empty($special_code)) {
				$this->line->special_code = 3;
			}

			// TODO deprecated
			$this->line->price = $price;
			$this->line->remise = $remise;

			if (is_array($array_options) && count($array_options) > 0) {
				$this->line->array_options = $array_options;
			}

			$result = $this->line->insert();
			$errors = 0;

			// ajout des type d'assignation
			foreach ($typeAssignment as $type){

					$sql =  " INSERT INTO  " . MAIN_DB_PREFIX . "productdefault_assignment  (fk_line_productdefault, type_assignment) ";
					$sql .= " VALUES ( " .(int) $this->line->id . "," . (int) $type . ")";
					$resql = $this->db->query($sql);
					if (!$resql){
						$errors++;
						break;
					}
			}


			if ($result > 0  ) {
				// Reorder if child line
				if (!empty($fk_parent_line)) {
					$this->line_order(true, 'DESC');
				} elseif($ranktouse > 0 && $ranktouse <= count($this->lines)) { // Update all rank of all other lines
					$linecount = count($this->lines);
					for ($ii = $ranktouse; $ii <= $linecount; $ii++) {
						$this->updateRangOfLine($this->lines[$ii - 1]->id, $ii + 1);
					}
				}

				// Mise a jour informations denormalisees au niveau de la propale meme
				// aucun total de document à mettre à jour pouisque nous ne sommes pas sur une propale ou commande
				//$result = $this->update_price(1, 'auto', 0, $object); // This method is designed to add line from user input so total calculation must be done using 'auto' mode.

				if ($result > 0 &&  $errors == 0) {
					$this->db->commit();
					return $this->line->id;
				} else {
					$this->error = $this->db->error();
					$this->db->rollback();
					return -1;
				}
			} else {
				$this->error = $this->line->error;
				$this->errors = $this->line->errors;
				$this->db->rollback();
				return -2;
			}

	}


	/**
	 *  Update a productDefault
	 *
	 *  @param      int			$rowid           	Id of line
	 *  @param      float		$pu		     	  	Unit price (HT or TTC depending on price_base_type)
	 *  @param      float		$qty            	Quantity
	 *  @param      float		$remise_percent  	Discount on line
	 *  @param      float		$txtva	          	VAT Rate (Can be '1.23' or '1.23 (ABC)')
	 * 	@param	  	float		$txlocaltax1		Local tax 1 rate
	 *  @param	  	float		$txlocaltax2		Local tax 2 rate
	 *  @param      string		$desc            	Description
	 *	@param	  	string		$price_base_type	HT or TTC
	 *	@param      int			$info_bits        	Miscellaneous informations
	 *	@param		int			$special_code		Special code (also used by externals modules!)
	 * 	@param		int			$fk_parent_line		Id of parent line (0 in most cases, used by modules adding sublevels into lines).
	 * 	@param		int			$skip_update_total	Keep fields total_xxx to 0 (used for special lines by some modules)
	 *  @param		int			$fk_fournprice		Id of origin supplier price
	 *  @param		int			$pa_ht				Price (without tax) of product when it was bought
	 *  @param		string		$label				???
	 *  @param		int			$type				0/1=Product/service
	 *	@param      int			$date_start       	Start date of the line
	 *	@param      int			$date_end         	End date of the line
	 *  @param		array		$array_options		extrafields array
	 * 	@param 		string		$fk_unit 			Code of the unit to use. Null to use the default one
	 * 	@param		double		$pu_ht_devise		Unit price in currency
	 * 	@param		int			$notrigger			disable line update trigger
	 *  @return     int     		        		0 if OK, <0 if KO
	 */
	public function updateline($rowid, $typeAssignment, $pu, $qty, $remise_percent, $txtva, $txlocaltax1 = 0.0, $txlocaltax2 = 0.0, $desc = '', $price_base_type = 'HT', $info_bits = 0, $special_code = 0, $fk_parent_line = 0, $skip_update_total = 0, $fk_fournprice = 0, $pa_ht = 0, $label = '', $type = 0, $date_start = '', $date_end = '', $array_options = 0, $fk_unit = null, $pu_ht_devise = 0, $notrigger = 0)
	{
		global $mysoc, $langs, $user;

		dol_syslog(get_class($this)."::updateLine rowid=$rowid, pu=$pu, qty=$qty, remise_percent=$remise_percent,
        txtva=$txtva, desc=$desc, price_base_type=$price_base_type, info_bits=$info_bits, special_code=$special_code, fk_parent_line=$fk_parent_line, pa_ht=$pa_ht, type=$type, date_start=$date_start, date_end=$date_end");
		include_once DOL_DOCUMENT_ROOT.'/core/lib/price.lib.php';

		// Clean parameters
		$remise_percent = price2num($remise_percent);
		$qty = price2num($qty);
		$pu = (float) price2num($pu);

		$pu_ht_devise = (float) price2num($pu_ht_devise);

		/*if (empty($pu_ht_devise)) {
			$pu_ht_devise = 0;
		}
		if (empty($pu)){
			$pu = $pu_ht_devise;
		}*/


		if (!preg_match('/\((.*)\)/', $txtva)) {
			$txtva = price2num($txtva); // $txtva can have format '5.0(XXX)' or '5'
		}
		$txlocaltax1 = price2num($txlocaltax1);
		$txlocaltax2 = price2num($txlocaltax2);
		$pa_ht = price2num($pa_ht);

		if (empty($qty) && empty($special_code)) {
			$special_code = 3; // Set option tag
		}
		if (!empty($qty) && $special_code == 3) {
			$special_code = 0; // Remove option tag
		}
		if (empty($type)) {
			$type = 0;
		}

		if ($date_start && $date_end && $date_start > $date_end) {
			$langs->load("errors");
			$this->error = $langs->trans('ErrorStartDateGreaterEnd');
			return -1;
		}


			$this->db->begin();

			// Calcul du total TTC et de la TVA pour la ligne a partir de
			// qty, pu, remise_percent et txtva
			// TRES IMPORTANT: C'est au moment de l'insertion ligne qu'on doit stocker
			// la part ht, tva et ttc, et ce au niveau de la ligne qui a son propre taux tva.

			$localtaxes_type = getLocalTaxesFromRate($txtva, 0, $this->thirdparty, $mysoc);

			// Clean vat code
			$reg = array();
			$vat_src_code = '';
			if (preg_match('/\((.*)\)/', $txtva, $reg)) {
				$vat_src_code = $reg[1];
				$txtva = preg_replace('/\s*\(.*\)/', '', $txtva); // Remove code into vatrate.
			}

			$tabprice = calcul_price_total($qty, $pu, $remise_percent, $txtva, $txlocaltax1, $txlocaltax2, 0, $price_base_type, $info_bits, $type, $mysoc, $localtaxes_type, 100, $this->multicurrency_tx, $pu_ht_devise);
			$total_ht  = $tabprice[0];
			$total_tva = $tabprice[1];
			$total_ttc = $tabprice[2];
			$total_localtax1 = $tabprice[9];
			$total_localtax2 = $tabprice[10];
			$pu_ht  = $tabprice[3];
			$pu_tva = $tabprice[4];
			$pu_ttc = $tabprice[5];

			// MultiCurrency
			$multicurrency_total_ht  = $tabprice[16];
			$multicurrency_total_tva = $tabprice[17];
			$multicurrency_total_ttc = $tabprice[18];
			$pu_ht_devise = $tabprice[19];

			// Anciens indicateurs: $price, $remise (a ne plus utiliser)
			$price = $pu;
			$remise = 0;
			if ($remise_percent > 0) {
				$remise = round(($pu * $remise_percent / 100), 2);
				$price = $pu - $remise;
			}

			//Fetch current line from the database and then clone the object and set it in $oldline property
			$line = new ProductThirdpartyDefault($this->db);
			$line->fetch($rowid);

			$staticline = clone $line;

			$line->oldline = $staticline;
			$this->line = $line;
			$this->line->context = $this->context;

			// Reorder if fk_parent_line change
			if (!empty($fk_parent_line) && !empty($staticline->fk_parent_line) && $fk_parent_line != $staticline->fk_parent_line) {
				$rangmax = $this->line_max($fk_parent_line);
				$this->line->rang = $rangmax + 1;
			}

			$this->line->id = $rowid;
			$this->line->label = $label;
			$this->line->desc = $desc;
			$this->line->qty = $qty;
			$this->line->product_type		= $type;
			$this->line->vat_src_code		= $vat_src_code;
			$this->line->tva_tx = $txtva;
			$this->line->localtax1_tx		= $txlocaltax1;
			$this->line->localtax2_tx		= $txlocaltax2;
			$this->line->localtax1_type		= $localtaxes_type[0];
			$this->line->localtax2_type		= $localtaxes_type[2];
			$this->line->remise_percent		= $remise_percent;
			$this->line->subprice			= $pu_ht;
			$this->line->info_bits			= $info_bits;

			$this->line->total_ht			= $total_ht;
			$this->line->total_tva			= $total_tva;
			$this->line->total_localtax1	= $total_localtax1;
			$this->line->total_localtax2	= $total_localtax2;
			$this->line->total_ttc			= $total_ttc;
			$this->line->special_code = $special_code;
			$this->line->fk_parent_line		= $fk_parent_line;
			$this->line->skip_update_total = $skip_update_total;
			$this->line->fk_unit = $fk_unit;

			$this->line->fk_fournprice = $fk_fournprice;
			$this->line->pa_ht = $this->line->buy_price_ht = $pa_ht;

			$this->line->date_start = $date_start;
			$this->line->date_end = $date_end;

			// TODO deprecated
			$this->line->price = $price;
			$this->line->remise = $remise;

			if (is_array($array_options) && count($array_options) > 0) {
				// We replace values in this->line->array_options only for entries defined into $array_options
				foreach ($array_options as $key => $value) {
					$this->line->array_options[$key] = $array_options[$key];
				}
			}

			// Multicurrency
			$this->line->multicurrency_subprice		= $pu_ht_devise;
			$this->line->multicurrency_total_ht 	= $multicurrency_total_ht;
			$this->line->multicurrency_total_tva 	= $multicurrency_total_tva;
			$this->line->multicurrency_total_ttc 	= $multicurrency_total_ttc;

			$result = $this->line->update($user, $notrigger);

			// delete des enreg type
			$sqlDelType = " DELETE FROM ".MAIN_DB_PREFIX."productdefault_assignment WHERE fk_line_productdefault = ".$this->line->id;
			$resql = $this->db->query($sqlDelType);
			$errors = 0;
			if ($resql >  0){
				foreach ($typeAssignment as $type){

					$sql =  " INSERT INTO  " . MAIN_DB_PREFIX . "productdefault_assignment  (fk_line_productdefault, type_assignment) ";
					$sql .= " VALUES ( " .(int) $this->line->id . "," . (int) $type . ")";
					$resql = $this->db->query($sql);
					if (!$resql){
						$errors++;
						break;
					}
				}
			}

			if ($result > 0) {



				// Reorder if child line
				if (!empty($fk_parent_line)) {
					$this->line_order(true, 'DESC');
				}

				// pas besoin d'update le prix ici
				//$this->update_price(1);

				$this->fk_propal = $this->id;
				$this->rowid = $rowid;

				$this->db->commit();
				return $result;
			} else {
				$this->error = $this->line->error;
				$this->errors = $this->line->errors;
				$this->db->rollback();
				return -1;
			}

	}



	/**
	 * Clone an object into another one
	 *
	 * @param  	User 	$user      	User that creates
	 * @param  	int 	$fromid     Id of object to clone
	 * @return 	mixed 				New object created, <0 if KO
	 */
	public function createFromClone(User $user, $fromid)
	{
		global $langs, $extrafields;
		$error = 0;

		dol_syslog(__METHOD__, LOG_DEBUG);

		$object = new self($this->db);

		$this->db->begin();

		// Load source object
		$result = $object->fetchCommon($fromid);
		if ($result > 0 && !empty($object->table_element_line)) {
			$object->fetchLines();
		}

		// get lines so they will be clone
		//foreach($this->lines as $line)
		//	$line->fetch_optionals();

		// Reset some properties
		unset($object->id);
		unset($object->fk_user_creat);
		unset($object->import_key);

		// Clear fields
		if (property_exists($object, 'ref')) {
			$object->ref = empty($this->fields['ref']['default']) ? "Copy_Of_".$object->ref : $this->fields['ref']['default'];
		}
		if (property_exists($object, 'label')) {
			$object->label = empty($this->fields['label']['default']) ? $langs->trans("CopyOf")." ".$object->label : $this->fields['label']['default'];
		}
		if (property_exists($object, 'status')) {
			$object->status = self::STATUS_DRAFT;
		}
		if (property_exists($object, 'date_creation')) {
			$object->date_creation = dol_now();
		}
		if (property_exists($object, 'date_modification')) {
			$object->date_modification = null;
		}
		// ...
		// Clear extrafields that are unique
		if (is_array($object->array_options) && count($object->array_options) > 0) {
			$extrafields->fetch_name_optionals_label($this->table_element);
			foreach ($object->array_options as $key => $option) {
				$shortkey = preg_replace('/options_/', '', $key);
				if (!empty($extrafields->attributes[$this->table_element]['unique'][$shortkey])) {
					//var_dump($key); var_dump($clonedObj->array_options[$key]); exit;
					unset($object->array_options[$key]);
				}
			}
		}

		// Create clone
		$object->context['createfromclone'] = 'createfromclone';
		$result = $object->createCommon($user);
		if ($result < 0) {
			$error++;
			$this->error = $object->error;
			$this->errors = $object->errors;
		}

		if (!$error) {
			// copy internal contacts
			if ($this->copy_linked_contact($object, 'internal') < 0) {
				$error++;
			}
		}

		if (!$error) {
			// copy external contacts if same company
			if (property_exists($this, 'fk_soc') && $this->fk_soc == $object->socid) {
				if ($this->copy_linked_contact($object, 'external') < 0) {
					$error++;
				}
			}
		}

		unset($object->context['createfromclone']);

		// End
		if (!$error) {
			$this->db->commit();
			return $object;
		} else {
			$this->db->rollback();
			return -1;
		}
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param int    $id   Id object
	 * @param string $ref  Ref
	 * @return int         <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetch($id, $ref = null)
	{
		$result = $this->fetchCommon($id, $ref);
		if ($result > 0 && !empty($this->table_element_line)) {
			$this->fetchLines();
		}
		return $result;
	}

	/**
	 * Load object lines in memory from the database
	 *
	 * @return int         <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetchLines()
	{
		$this->lines = array();

		$result = $this->fetchLinesCommon();
		return $result;
	}

	/**
	 * @param $notrigger
	 * @return float|int
	 * @throws Exception
	 */
	public function insert($notrigger = 0)
	{
		global $conf, $user;

		$error = 0;

		dol_syslog(get_class($this)."::insert rang=".$this->rang);

		$pa_ht_isemptystring = (empty($this->pa_ht) && $this->pa_ht == ''); // If true, we can use a default value. If this->pa_ht = '0', we must use '0'.

		// Clean parameters
		if (empty($this->tva_tx)) {
			$this->tva_tx = 0;
		}
		if (empty($this->localtax1_tx)) {
			$this->localtax1_tx = 0;
		}
		if (empty($this->localtax2_tx)) {
			$this->localtax2_tx = 0;
		}
		if (empty($this->localtax1_type)) {
			$this->localtax1_type = 0;
		}
		if (empty($this->localtax2_type)) {
			$this->localtax2_type = 0;
		}
		if (empty($this->total_localtax1)) {
			$this->total_localtax1 = 0;
		}
		if (empty($this->total_localtax2)) {
			$this->total_localtax2 = 0;
		}
		if (empty($this->rang)) {
			$this->rang = 0;
		}
		if (empty($this->remise)) {
			$this->remise = 0;
		}
		if (empty($this->remise_percent) || !is_numeric($this->remise_percent)) {
			$this->remise_percent = 0;
		}
		if (empty($this->info_bits)) {
			$this->info_bits = 0;
		}
		if (empty($this->special_code)) {
			$this->special_code = 0;
		}
		if (empty($this->fk_parent_line)) {
			$this->fk_parent_line = 0;
		}
		if (empty($this->fk_fournprice)) {
			$this->fk_fournprice = 0;
		}
		if (!is_numeric($this->qty)) {
			$this->qty = 0;
		}
		if (empty($this->pa_ht)) {
			$this->pa_ht = 0;
		}
		if (empty($this->multicurrency_subprice)) {
			$this->multicurrency_subprice = 0;
		}
		if (empty($this->multicurrency_total_ht)) {
			$this->multicurrency_total_ht = 0;
		}
		if (empty($this->multicurrency_total_tva)) {
			$this->multicurrency_total_tva = 0;
		}
		if (empty($this->multicurrency_total_ttc)) {
			$this->multicurrency_total_ttc = 0;
		}

		// if buy price not defined, define buyprice as configured in margin admin
		if ($this->pa_ht == 0 && $pa_ht_isemptystring) {
			if (($result = $this->defineBuyPrice($this->subprice, $this->remise_percent, $this->fk_product)) < 0) {
				return $result;
			} else {
				$this->pa_ht = $result;
			}
		}

		// Check parameters
		if ($this->product_type < 0) {
			return -1;
		}

		$this->db->begin();

		// Insert line into database
		$sql = 'INSERT INTO '.MAIN_DB_PREFIX.$this->table_element_line;
		$sql .= ' (fk_soc, entity, label, description, fk_product, product_type,';
		$sql .= ' fk_remise_except, qty, vat_src_code, tva_tx, localtax1_tx, localtax2_tx, localtax1_type, localtax2_type,';
		$sql .= ' subprice, remise_percent, ';
		$sql .= ' info_bits, ';
		$sql .= ' total_ht, total_tva, total_localtax1, total_localtax2, total_ttc, fk_product_fournisseur_price, buy_price_ht, special_code, rang,';
		$sql .= ' fk_unit,';
		$sql .= ' date_start, date_end';
		$sql .= ', fk_multicurrency, multicurrency_code, multicurrency_subprice, multicurrency_total_ht, multicurrency_total_tva, multicurrency_total_ttc)';
		$sql .= " VALUES (";
		$sql .= " ".$this->db->escape($this->fk_soc).",";
		$sql .= " ".$this->db->escape($conf->entity).",";
		$sql .= " ".(!empty($this->label) ? "'".$this->db->escape($this->label)."'" : "null").",";
		$sql .= " '".$this->db->escape($this->desc)."',";
		$sql .= " ".($this->fk_product ? "'".$this->db->escape($this->fk_product)."'" : "null").",";
		$sql .= " '".$this->db->escape($this->product_type)."',";
		$sql .= " ".($this->fk_remise_except ? "'".$this->db->escape($this->fk_remise_except)."'" : "null").",";
		$sql .= " ".price2num($this->qty).",";
		$sql .= " ".(empty($this->vat_src_code) ? "''" : "'".$this->db->escape($this->vat_src_code)."'").",";
		$sql .= " ".price2num($this->tva_tx).",";
		$sql .= " ".price2num($this->localtax1_tx).",";
		$sql .= " ".price2num($this->localtax2_tx).",";
		$sql .= " '".$this->db->escape($this->localtax1_type)."',";
		$sql .= " '".$this->db->escape($this->localtax2_type)."',";
		$sql .= " ".(price2num($this->subprice) !== '' ?price2num($this->subprice) : "null").",";
		$sql .= " ".price2num($this->remise_percent).",";
		$sql .= " ".(isset($this->info_bits) ? "'".$this->db->escape($this->info_bits)."'" : "null").",";
		$sql .= " ".price2num($this->total_ht).",";
		$sql .= " ".price2num($this->total_tva).",";
		$sql .= " ".price2num($this->total_localtax1).",";
		$sql .= " ".price2num($this->total_localtax2).",";
		$sql .= " ".price2num($this->total_ttc).",";
		$sql .= " ".(!empty($this->fk_fournprice) ? "'".$this->db->escape($this->fk_fournprice)."'" : "null").",";
		$sql .= " ".(isset($this->pa_ht) ? "'".price2num($this->pa_ht)."'" : "null").",";
		$sql .= ' '.$this->special_code.',';
		$sql .= ' '.$this->rang.',';
		$sql .= ' '.(!$this->fk_unit ? 'NULL' : $this->fk_unit).',';
		$sql .= " ".(!empty($this->date_start) ? "'".$this->db->idate($this->date_start)."'" : "null").',';
		$sql .= " ".(!empty($this->date_end) ? "'".$this->db->idate($this->date_end)."'" : "null");
		$sql .= ", ".($this->fk_multicurrency > 0 ? $this->fk_multicurrency : 'null');
		$sql .= ", '".$this->db->escape($this->multicurrency_code)."'";
		$sql .= ", ".$this->multicurrency_subprice;
		$sql .= ", ".$this->multicurrency_total_ht;
		$sql .= ", ".$this->multicurrency_total_tva;
		$sql .= ", ".$this->multicurrency_total_ttc;
		$sql .= ')';

		dol_syslog(get_class($this).'::insert', LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			$this->rowid = $this->db->last_insert_id(MAIN_DB_PREFIX.$this->table_element_line);

			if (!$error) {
				$this->id = $this->rowid;
				$result = $this->insertExtraFields();
				if ($result < 0) {
					$error++;
				}
			}

			if (!$error && !$notrigger) {
				// Call trigger
				$result = $this->call_trigger('PRODUCTDEFAULT_INSERT', $user);
				if ($result < 0) {
					$this->db->rollback();
					return -1;
				}
				// End call triggers
			}

			$this->db->commit();
			return 1;
		} else {
			$this->error = $this->db->error()." sql=".$sql;
			$this->db->rollback();
			return -1;
		}
	}


	/**
	 * Load list of objects in memory from the database.
	 *
	 * @param  string      $sortorder    Sort Order
	 * @param  string      $sortfield    Sort field
	 * @param  int         $limit        limit
	 * @param  int         $offset       Offset
	 * @param  array       $filter       Filter array. Example array('field'=>'valueforlike', 'customurl'=>...)
	 * @param  string      $filtermode   Filter mode (AND or OR)
	 * @return array|int                 int <0 if KO, array of pages if OK
	 */
	public function fetchAll($sortorder = '', $sortfield = '', $limit = 0, $offset = 0, array $filter = array(), $filtermode = 'AND' ,$triggered = false)
	{
		global $conf;

		dol_syslog(__METHOD__, LOG_DEBUG);

		$records = array();
		// l'appel vient d'un trigger activé
		if (!$triggered){
			$sql = 'SELECT  p.label, p.ref, ';
			$sql .= $this->getFieldList('t');
			$sql .= ' FROM '.MAIN_DB_PREFIX.$this->table_element.' as t';
			$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'product'.' as p ON t.fk_product = p.rowid';
		}else{
			$sql = " SELECT * ";
			$sql .= ' FROM '.MAIN_DB_PREFIX.$this->table_element.' as t ';
			$sql .=" INNER JOIN llx_productdefault_assignment as  pa on t.rowid = pa.fk_line_productdefault ";
		}

		if (isset($this->ismultientitymanaged) && $this->ismultientitymanaged == 1) {
			$sql .= ' WHERE t.entity IN ('.getEntity($this->table_element).')';
		} else {
			$sql .= ' WHERE 1 = 1';
		}

		// Manage filter
		$sqlwhere = array();
		if (count($filter) > 0) {
			foreach ($filter as $key => $value) {
				if ($key == 't.rowid') {
					$sqlwhere[] = $key.'='.$value;
				} elseif (in_array($this->fields[$key]['type'], array('date', 'datetime', 'timestamp'))) {
					$sqlwhere[] = $key.' = \''.$this->db->idate($value).'\'';
				} elseif ($key == 'customsql') {
					$sqlwhere[] = $value;
				} elseif (strpos($value, '%') === false) {
					$sqlwhere[] = $key.' IN ('.$this->db->sanitize($this->db->escape($value)).')';
				} else {
					$sqlwhere[] = $key.' LIKE \'%'.$this->db->escape($value).'%\'';
				}
			}
		}
		if (count($sqlwhere) > 0) {
			$sql .= ' AND ('.implode(' '.$filtermode.' ', $sqlwhere).')';
		}

		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield, $sortorder);
		}
		if (!empty($limit)) {
			$sql .= ' '.$this->db->plimit($limit, $offset);
		}


		$resql = $this->db->query($sql);
		if ($resql) {
			$num = $this->db->num_rows($resql);
			$i = 0;
			while ($i < ($limit ? min($limit, $num) : $num)) {
				$obj = $this->db->fetch_object($resql);

				$record = new self($this->db);
				$record->setVarsFromFetchObj($obj);
				// on ajoute les valeurs ajouter dans le fetch all
				$record->ref = $obj->ref;
				$record->product_label = $record->label = $obj->label;
				$record->fk_unit = $obj->fk_unit;
				$record->pa_ht = $obj->buy_price_ht;
				$record->date_start = $obj->date_start;
				$record->date_end = $obj->date_end;
				$records[$record->id] = $record;

				$i++;
			}
			$this->db->free($resql);

			return $records;
		} else {
			$this->errors[] = 'Error '.$this->db->lasterror();
			dol_syslog(__METHOD__.' '.join(',', $this->errors), LOG_ERR);

			return -1;
		}
	}
	/**
	 * Update object into database
	 *
	 * @param  User $user      User that modifies
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, >0 if OK
	 */
	public function update(User $user, $notrigger = false)
	{
		return $this->updateCommon($user, $notrigger);
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user       User that deletes
	 * @param bool $notrigger  false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, >0 if OK
	 */
	public function delete(User $user, $notrigger = false)
	{
		global $langs;





		$this->db->begin();


		$sqlDelType = " DELETE FROM ".MAIN_DB_PREFIX."productdefault_assignment WHERE fk_line_productdefault = ".$this->id;
		$resql = $this->db->query($sqlDelType);
		$errors = 0;
		if (!$resql){
			$errors++;
			$this->error = $this->db->lasterror();
			$this->errors[] = $this->error;

		}else{

			$sql = " DELETE FROM ".MAIN_DB_PREFIX.$this->table_element . "  WHERE rowid =".$this->id;
			$resql = $this->db->query($sql);
			if (!$resql || $errors > 0 ) {
				$this->error = $this->db->lasterror();
				$this->errors[] = $this->error;
				$this->db->rollback();
				return -1;
			}else{
				$this->db->commit();
				setEventMessages($langs->trans('DELETE_SUCCESS'), array());
				return 1;
			}
		}


	}

	/**
	 *  Delete a line of object in database
	 *
	 *	@param  User	$user       User that delete
	 *  @param	int		$idline		Id of line to delete
	 *  @param 	bool 	$notrigger  false=launch triggers after, true=disable triggers
	 *  @return int         		>0 if OK, <0 if KO
	 */
	public function deleteLine(User $user, $idline, $notrigger = false)
	{
		if ($this->status < 0) {
			$this->error = 'ErrorDeleteLineNotAllowedByObjectStatus';
			return -2;
		}

		return $this->deleteLineCommon($user, $idline, $notrigger);
	}


	/**
	 *	Validate object
	 *
	 *	@param		User	$user     		User making status change
	 *  @param		int		$notrigger		1=Does not execute triggers, 0= execute triggers
	 *	@return  	int						<=0 if OK, 0=Nothing done, >0 if KO
	 */
	public function validate($user, $notrigger = 0)
	{
		global $conf, $langs;

		require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

		$error = 0;

		// Protection
		if ($this->status == self::STATUS_VALIDATED) {
			dol_syslog(get_class($this)."::validate action abandonned: already validated", LOG_WARNING);
			return 0;
		}

		/*if (! ((empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->productthirdpartydefault->write))
		 || (! empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->productthirdpartydefault->productthirdpartydefault_advance->validate))))
		 {
		 $this->error='NotEnoughPermissions';
		 dol_syslog(get_class($this)."::valid ".$this->error, LOG_ERR);
		 return -1;
		 }*/

		$now = dol_now();

		$this->db->begin();

		// Define new ref
		if (!$error && (preg_match('/^[\(]?PROV/i', $this->ref) || empty($this->ref))) { // empty should not happened, but when it occurs, the test save life
			$num = $this->getNextNumRef();
		} else {
			$num = $this->ref;
		}
		$this->newref = $num;

		if (!empty($num)) {
			// Validate
			$sql = "UPDATE ".MAIN_DB_PREFIX.$this->table_element;
			$sql .= " SET ref = '".$this->db->escape($num)."',";
			$sql .= " status = ".self::STATUS_VALIDATED;
			if (!empty($this->fields['date_validation'])) {
				$sql .= ", date_validation = '".$this->db->idate($now)."'";
			}
			if (!empty($this->fields['fk_user_valid'])) {
				$sql .= ", fk_user_valid = ".((int) $user->id);
			}
			$sql .= " WHERE rowid = ".((int) $this->id);

			dol_syslog(get_class($this)."::validate()", LOG_DEBUG);
			$resql = $this->db->query($sql);
			if (!$resql) {
				dol_print_error($this->db);
				$this->error = $this->db->lasterror();
				$error++;
			}

			if (!$error && !$notrigger) {
				// Call trigger
				$result = $this->call_trigger('PRODUCTTHIRDPARTYDEFAULT_VALIDATE', $user);
				if ($result < 0) {
					$error++;
				}
				// End call triggers
			}
		}

		if (!$error) {
			$this->oldref = $this->ref;

			// Rename directory if dir was a temporary ref
			if (preg_match('/^[\(]?PROV/i', $this->ref)) {
				// Now we rename also files into index
				$sql = 'UPDATE '.MAIN_DB_PREFIX."ecm_files set filename = CONCAT('".$this->db->escape($this->newref)."', SUBSTR(filename, ".(strlen($this->ref) + 1).")), filepath = 'productthirdpartydefault/".$this->db->escape($this->newref)."'";
				$sql .= " WHERE filename LIKE '".$this->db->escape($this->ref)."%' AND filepath = 'productthirdpartydefault/".$this->db->escape($this->ref)."' and entity = ".$conf->entity;
				$resql = $this->db->query($sql);
				if (!$resql) {
					$error++; $this->error = $this->db->lasterror();
				}

				// We rename directory ($this->ref = old ref, $num = new ref) in order not to lose the attachments
				$oldref = dol_sanitizeFileName($this->ref);
				$newref = dol_sanitizeFileName($num);
				$dirsource = $conf->productdefault->dir_output.'/productthirdpartydefault/'.$oldref;
				$dirdest = $conf->productdefault->dir_output.'/productthirdpartydefault/'.$newref;
				if (!$error && file_exists($dirsource)) {
					dol_syslog(get_class($this)."::validate() rename dir ".$dirsource." into ".$dirdest);

					if (@rename($dirsource, $dirdest)) {
						dol_syslog("Rename ok");
						// Rename docs starting with $oldref with $newref
						$listoffiles = dol_dir_list($conf->productdefault->dir_output.'/productthirdpartydefault/'.$newref, 'files', 1, '^'.preg_quote($oldref, '/'));
						foreach ($listoffiles as $fileentry) {
							$dirsource = $fileentry['name'];
							$dirdest = preg_replace('/^'.preg_quote($oldref, '/').'/', $newref, $dirsource);
							$dirsource = $fileentry['path'].'/'.$dirsource;
							$dirdest = $fileentry['path'].'/'.$dirdest;
							@rename($dirsource, $dirdest);
						}
					}
				}
			}
		}

		// Set new ref and current status
		if (!$error) {
			$this->ref = $num;
			$this->status = self::STATUS_VALIDATED;
		}

		if (!$error) {
			$this->db->commit();
			return 1;
		} else {
			$this->db->rollback();
			return -1;
		}
	}


	/**
	 *	Set draft status
	 *
	 *	@param	User	$user			Object user that modify
	 *  @param	int		$notrigger		1=Does not execute triggers, 0=Execute triggers
	 *	@return	int						<0 if KO, >0 if OK
	 */
	public function setDraft($user, $notrigger = 0)
	{
		// Protection
		if ($this->status <= self::STATUS_DRAFT) {
			return 0;
		}

		/*if (! ((empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->write))
		 || (! empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->productdefault_advance->validate))))
		 {
		 $this->error='Permission denied';
		 return -1;
		 }*/

		return $this->setStatusCommon($user, self::STATUS_DRAFT, $notrigger, 'PRODUCTTHIRDPARTYDEFAULT_UNVALIDATE');
	}

	/**
	 *	Set cancel status
	 *
	 *	@param	User	$user			Object user that modify
	 *  @param	int		$notrigger		1=Does not execute triggers, 0=Execute triggers
	 *	@return	int						<0 if KO, 0=Nothing done, >0 if OK
	 */
	public function cancel($user, $notrigger = 0)
	{
		// Protection
		if ($this->status != self::STATUS_VALIDATED) {
			return 0;
		}

		/*if (! ((empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->write))
		 || (! empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->productdefault_advance->validate))))
		 {
		 $this->error='Permission denied';
		 return -1;
		 }*/

		return $this->setStatusCommon($user, self::STATUS_CANCELED, $notrigger, 'PRODUCTTHIRDPARTYDEFAULT_CANCEL');
	}

	/**
	 *	Set back to validated status
	 *
	 *	@param	User	$user			Object user that modify
	 *  @param	int		$notrigger		1=Does not execute triggers, 0=Execute triggers
	 *	@return	int						<0 if KO, 0=Nothing done, >0 if OK
	 */
	public function reopen($user, $notrigger = 0)
	{
		// Protection
		if ($this->status != self::STATUS_CANCELED) {
			return 0;
		}

		/*if (! ((empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->write))
		 || (! empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! empty($user->rights->productdefault->productdefault_advance->validate))))
		 {
		 $this->error='Permission denied';
		 return -1;
		 }*/

		return $this->setStatusCommon($user, self::STATUS_VALIDATED, $notrigger, 'PRODUCTTHIRDPARTYDEFAULT_REOPEN');
	}

	/**
	 *  Return a link to the object card (with optionaly the picto)
	 *
	 *  @param  int     $withpicto                  Include picto in link (0=No picto, 1=Include picto into link, 2=Only picto)
	 *  @param  string  $option                     On what the link point to ('nolink', ...)
	 *  @param  int     $notooltip                  1=Disable tooltip
	 *  @param  string  $morecss                    Add more css on link
	 *  @param  int     $save_lastsearch_value      -1=Auto, 0=No save of lastsearch_values when clicking, 1=Save lastsearch_values whenclicking
	 *  @return	string                              String with URL
	 */
	public function getNomUrl($withpicto = 0, $option = '', $notooltip = 0, $morecss = '', $save_lastsearch_value = -1)
	{
		global $conf, $langs, $hookmanager;

		if (!empty($conf->dol_no_mouse_hover)) {
			$notooltip = 1; // Force disable tooltips
		}

		$result = '';

		$label = img_picto('', $this->picto).' <u>'.$langs->trans("ProductThirdpartyDefault").'</u>';
		if (isset($this->status)) {
			$label .= ' '.$this->getLibStatut(5);
		}
		$label .= '<br>';
		$label .= '<b>'.$langs->trans('Ref').':</b> '.$this->ref;

		$url = dol_buildpath('/productdefault/productthirdpartydefault_card.php', 1).'?id='.$this->id;

		if ($option != 'nolink') {
			// Add param to save lastsearch_values or not
			$add_save_lastsearch_values = ($save_lastsearch_value == 1 ? 1 : 0);
			if ($save_lastsearch_value == -1 && preg_match('/list\.php/', $_SERVER["PHP_SELF"])) {
				$add_save_lastsearch_values = 1;
			}
			if ($add_save_lastsearch_values) {
				$url .= '&save_lastsearch_values=1';
			}
		}

		$linkclose = '';
		if (empty($notooltip)) {
			if (!empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER)) {
				$label = $langs->trans("ShowProductThirdpartyDefault");
				$linkclose .= ' alt="'.dol_escape_htmltag($label, 1).'"';
			}
			$linkclose .= ' title="'.dol_escape_htmltag($label, 1).'"';
			$linkclose .= ' class="classfortooltip'.($morecss ? ' '.$morecss : '').'"';
		} else {
			$linkclose = ($morecss ? ' class="'.$morecss.'"' : '');
		}

		if ($option == 'nolink') {
			$linkstart = '<span';
		} else {
			$linkstart = '<a href="'.$url.'"';
		}
		$linkstart .= $linkclose.'>';
		if ($option == 'nolink') {
			$linkend = '</span>';
		} else {
			$linkend = '</a>';
		}

		$result .= $linkstart;

		if (empty($this->showphoto_on_popup)) {
			if ($withpicto) {
				$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), ($notooltip ? (($withpicto != 2) ? 'class="paddingright"' : '') : 'class="'.(($withpicto != 2) ? 'paddingright ' : '').'classfortooltip"'), 0, 0, $notooltip ? 0 : 1);
			}
		} else {
			if ($withpicto) {
				require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

				list($class, $module) = explode('@', $this->picto);
				$upload_dir = $conf->$module->multidir_output[$conf->entity]."/$class/".dol_sanitizeFileName($this->ref);
				$filearray = dol_dir_list($upload_dir, "files");
				$filename = $filearray[0]['name'];
				if (!empty($filename)) {
					$pospoint = strpos($filearray[0]['name'], '.');

					$pathtophoto = $class.'/'.$this->ref.'/thumbs/'.substr($filename, 0, $pospoint).'_mini'.substr($filename, $pospoint);
					if (empty($conf->global->{strtoupper($module.'_'.$class).'_FORMATLISTPHOTOSASUSERS'})) {
						$result .= '<div class="floatleft inline-block valignmiddle divphotoref"><div class="photoref"><img class="photo'.$module.'" alt="No photo" border="0" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart='.$module.'&entity='.$conf->entity.'&file='.urlencode($pathtophoto).'"></div></div>';
					} else {
						$result .= '<div class="floatleft inline-block valignmiddle divphotoref"><img class="photouserphoto userphoto" alt="No photo" border="0" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart='.$module.'&entity='.$conf->entity.'&file='.urlencode($pathtophoto).'"></div>';
					}

					$result .= '</div>';
				} else {
					$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), ($notooltip ? (($withpicto != 2) ? 'class="paddingright"' : '') : 'class="'.(($withpicto != 2) ? 'paddingright ' : '').'classfortooltip"'), 0, 0, $notooltip ? 0 : 1);
				}
			}
		}

		if ($withpicto != 2) {
			$result .= $this->ref;
		}

		$result .= $linkend;
		//if ($withpicto != 2) $result.=(($addlabel && $this->label) ? $sep . dol_trunc($this->label, ($addlabel > 1 ? $addlabel : 0)) : '');

		global $action, $hookmanager;
		$hookmanager->initHooks(array('productthirdpartydefaultdao'));
		$parameters = array('id'=>$this->id, 'getnomurl'=>$result);
		$reshook = $hookmanager->executeHooks('getNomUrl', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks
		if ($reshook > 0) {
			$result = $hookmanager->resPrint;
		} else {
			$result .= $hookmanager->resPrint;
		}

		return $result;
	}

	/**
	 *  Return the label of the status
	 *
	 *  @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 *  @return	string 			       Label of status
	 */
	public function getLabelStatus($mode = 0)
	{
		return $this->LibStatut($this->status, $mode);
	}

	/**
	 *  Return the label of the status
	 *
	 *  @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 *  @return	string 			       Label of status
	 */
	public function getLibStatut($mode = 0)
	{
		return $this->LibStatut($this->status, $mode);
	}

	// phpcs:disable PEAR.NamingConventions.ValidFunctionName.ScopeNotCamelCaps
	/**
	 *  Return the status
	 *
	 *  @param	int		$status        Id status
	 *  @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 *  @return string 			       Label of status
	 */
	public function LibStatut($status, $mode = 0)
	{
		// phpcs:enable
		if (empty($this->labelStatus) || empty($this->labelStatusShort)) {
			global $langs;
			//$langs->load("productdefault@productdefault");
			$this->labelStatus[self::STATUS_DRAFT] = $langs->trans('Draft');
			$this->labelStatus[self::STATUS_VALIDATED] = $langs->trans('Enabled');
			$this->labelStatus[self::STATUS_CANCELED] = $langs->trans('Disabled');
			$this->labelStatusShort[self::STATUS_DRAFT] = $langs->trans('Draft');
			$this->labelStatusShort[self::STATUS_VALIDATED] = $langs->trans('Enabled');
			$this->labelStatusShort[self::STATUS_CANCELED] = $langs->trans('Disabled');
		}

		$statusType = 'status'.$status;
		//if ($status == self::STATUS_VALIDATED) $statusType = 'status1';
		if ($status == self::STATUS_CANCELED) {
			$statusType = 'status6';
		}

		return dolGetStatus($this->labelStatus[$status], $this->labelStatusShort[$status], '', $statusType, $mode);
	}

	/**
	 *	Load the info information in the object
	 *
	 *	@param  int		$id       Id of object
	 *	@return	void
	 */
	public function info($id)
	{
		$sql = 'SELECT rowid, date_creation as datec, tms as datem,';
		$sql .= ' fk_user_creat, fk_user_modif';
		$sql .= ' FROM '.MAIN_DB_PREFIX.$this->table_element.' as t';
		$sql .= ' WHERE t.rowid = '.((int) $id);
		$result = $this->db->query($sql);
		if ($result) {
			if ($this->db->num_rows($result)) {
				$obj = $this->db->fetch_object($result);
				$this->id = $obj->rowid;
				if ($obj->fk_user_author) {
					$cuser = new User($this->db);
					$cuser->fetch($obj->fk_user_author);
					$this->user_creation = $cuser;
				}

				if ($obj->fk_user_valid) {
					$vuser = new User($this->db);
					$vuser->fetch($obj->fk_user_valid);
					$this->user_validation = $vuser;
				}

				if ($obj->fk_user_cloture) {
					$cluser = new User($this->db);
					$cluser->fetch($obj->fk_user_cloture);
					$this->user_cloture = $cluser;
				}

				$this->date_creation     = $this->db->jdate($obj->datec);
				$this->date_modification = $this->db->jdate($obj->datem);
				$this->date_validation   = $this->db->jdate($obj->datev);
			}

			$this->db->free($result);
		} else {
			dol_print_error($this->db);
		}
	}

	/**
	 * Initialise object with example values
	 * Id must be 0 if object instance is a specimen
	 *
	 * @return void
	 */
	public function initAsSpecimen()
	{
		// Set here init that are not commonf fields
		// $this->property1 = ...
		// $this->property2 = ...

		$this->initAsSpecimenCommon();
	}

	/**
	 * 	Create an array of lines
	 *
	 * 	@return array|int		array of lines if OK, <0 if KO
	 */
	public function getLinesArray()
	{
		$this->lines = array();

		$objectline = new ProductThirdpartyDefaultLine($this->db);
		$result = $objectline->fetchAll('ASC', 'position', 0, 0, array('customsql'=>'fk_productthirdpartydefault = '.((int) $this->id)));

		if (is_numeric($result)) {
			$this->error = $this->error;
			$this->errors = $this->errors;
			return $result;
		} else {
			$this->lines = $result;
			return $this->lines;
		}
	}

	/**
	 *  Returns the reference to the following non used object depending on the active numbering module.
	 *
	 *  @return string      		Object free reference
	 */
	public function getNextNumRef()
	{
		global $langs, $conf;
		$langs->load("productdefault@productdefault");

		if (empty($conf->global->PRODUCTDEFAULT_PRODUCTTHIRDPARTYDEFAULT_ADDON)) {
			$conf->global->PRODUCTDEFAULT_PRODUCTTHIRDPARTYDEFAULT_ADDON = 'mod_productthirdpartydefault_standard';
		}

		if (!empty($conf->global->PRODUCTDEFAULT_PRODUCTTHIRDPARTYDEFAULT_ADDON)) {
			$mybool = false;

			$file = $conf->global->PRODUCTDEFAULT_PRODUCTTHIRDPARTYDEFAULT_ADDON.".php";
			$classname = $conf->global->PRODUCTDEFAULT_PRODUCTTHIRDPARTYDEFAULT_ADDON;

			// Include file with class
			$dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);
			foreach ($dirmodels as $reldir) {
				$dir = dol_buildpath($reldir."core/modules/productdefault/");

				// Load file with numbering class (if found)
				$mybool |= @include_once $dir.$file;
			}

			if ($mybool === false) {
				dol_print_error('', "Failed to include file ".$file);
				return '';
			}

			if (class_exists($classname)) {
				$obj = new $classname();
				$numref = $obj->getNextValue($this);

				if ($numref != '' && $numref != '-1') {
					return $numref;
				} else {
					$this->error = $obj->error;
					//dol_print_error($this->db,get_class($this)."::getNextNumRef ".$obj->error);
					return "";
				}
			} else {
				print $langs->trans("Error")." ".$langs->trans("ClassNotFound").' '.$classname;
				return "";
			}
		} else {
			print $langs->trans("ErrorNumberingModuleNotSetup", $this->element);
			return "";
		}
	}

	/**
	 *  Create a document onto disk according to template module.
	 *
	 *  @param	    string		$modele			Force template to use ('' to not force)
	 *  @param		Translate	$outputlangs	objet lang a utiliser pour traduction
	 *  @param      int			$hidedetails    Hide details of lines
	 *  @param      int			$hidedesc       Hide description
	 *  @param      int			$hideref        Hide ref
	 *  @param      null|array  $moreparams     Array to provide more information
	 *  @return     int         				0 if KO, 1 if OK
	 */
	public function generateDocument($modele, $outputlangs, $hidedetails = 0, $hidedesc = 0, $hideref = 0, $moreparams = null)
	{
		global $conf, $langs;

		$result = 0;
		$includedocgeneration = 0;

		$langs->load("productdefault@productdefault");

		if (!dol_strlen($modele)) {
			$modele = 'standard_productthirdpartydefault';

			if (!empty($this->model_pdf)) {
				$modele = $this->model_pdf;
			} elseif (!empty($conf->global->PRODUCTTHIRDPARTYDEFAULT_ADDON_PDF)) {
				$modele = $conf->global->PRODUCTTHIRDPARTYDEFAULT_ADDON_PDF;
			}
		}

		$modelpath = "core/modules/productdefault/doc/";

		if ($includedocgeneration && !empty($modele)) {
			$result = $this->commonGenerateDocument($modelpath, $modele, $outputlangs, $hidedetails, $hidedesc, $hideref, $moreparams);
		}

		return $result;
	}

	/**
	 * Action executed by scheduler
	 * CAN BE A CRON TASK. In such a case, parameters come from the schedule job setup field 'Parameters'
	 * Use public function doScheduledJob($param1, $param2, ...) to get parameters
	 *
	 * @return	int			0 if OK, <>0 if KO (this function is used also by cron so only 0 is OK)
	 */
	public function doScheduledJob()
	{
		global $conf, $langs;

		//$conf->global->SYSLOG_FILE = 'DOL_DATA_ROOT/dolibarr_mydedicatedlofile.log';

		$error = 0;
		$this->output = '';
		$this->error = '';

		dol_syslog(__METHOD__, LOG_DEBUG);

		$now = dol_now();

		$this->db->begin();

		// ...

		$this->db->commit();

		return $error;
	}
}

