<?php
/* Copyright (C) 2022 SuperAdmin <eurochef.support@adepia.fr>
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
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    core/triggers/interface_99_modProductDefault_ProductDefaultTriggers.class.php
 * \ingroup productdefault
 * \brief   Example trigger.
 *
 * Put detailed description here.
 *
 * \remarks You can create other triggers by copying this one.
 * - File name should be either:
 *      - interface_99_modProductDefault_MyTrigger.class.php
 *      - interface_99_all_MyTrigger.class.php
 * - The file must stay in core/triggers
 * - The class name must be InterfaceMytrigger
 * - The constructor method must be named InterfaceMytrigger
 * - The name property name must be MyTrigger
 */

require_once DOL_DOCUMENT_ROOT.'/core/triggers/dolibarrtriggers.class.php';


/**
 *  Class of triggers for ProductDefault module
 */
class InterfaceProductDefaultTriggers extends DolibarrTriggers
{
	/**
	 * Constructor
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;

		$this->name = preg_replace('/^Interface/i', '', get_class($this));
		$this->family = "demo";
		$this->description = "ProductDefault triggers.";
		// 'development', 'experimental', 'dolibarr' or version
		$this->version = 'development';
		$this->picto = 'productdefault@productdefault';
	}

	/**
	 * Trigger name
	 *
	 * @return string Name of trigger file
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Trigger description
	 *
	 * @return string Description of trigger file
	 */
	public function getDesc()
	{
		return $this->description;
	}


	/**
	 * Function called when a Dolibarrr business event is done.
	 * All functions "runTrigger" are triggered if file
	 * is inside directory core/triggers
	 *
	 * @param string 		$action 	Event action code
	 * @param CommonObject 	$object 	Object
	 * @param User 			$user 		Object user
	 * @param Translate 	$langs 		Object langs
	 * @param Conf 			$conf 		Object conf
	 * @return int              		<0 if KO, 0 if no triggered ran, >0 if OK
	 */
	public function runTrigger($action, $object, User $user, Translate $langs, Conf $conf)
	{
		global $db;

		if (empty($conf->productdefault) || empty($conf->productdefault->enabled)) {
			return 0; // If module is not enabled, we do nothing
		}

		// Put here code you want to execute when a Dolibarr business events occurs.
		// Data and type of action are stored into $object and $action

		// You can isolate code for each action in a separate method: this method should be named like the trigger in camelCase.
		// For example : COMPANY_CREATE => public function companyCreate($action, $object, User $user, Translate $langs, Conf $conf)
		$methodName = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($action)))));
		$callback = array($this, $methodName);
		if (is_callable($callback)) {
			dol_syslog(
				"Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id
			);

			return call_user_func($callback, $action, $object, $user, $langs, $conf);
		};

		// Or you can execute some code here
		switch ($action) {

			// Customer orders
			case 'ORDER_CREATE':

				// AJOUT AUTOMATIQUE DES PRODUITS PAR DEFAUT  ASSIGNÉS AU  TIER DE CETTE PROPOSITION COMMERCIALE
				dol_include_once('/productdefault/class/productthirdpartydefault.class.php');
				$pdefault = new ProductThirdpartyDefault($db);
				$records = $pdefault->fetchAll('ASC','t.rowid',0,0, array('t.fk_soc' => $object->socid, 'pa.type_assignment'  => ProductThirdpartyDefault::TYPE_ASSIGNMENT_ORDER,'t.entity'=> $conf->entity ),'AND',true);
				//var_dump($records);
				foreach ($records as $record){
					$object->addLine($record->description,
									 $record->subprice,
									 $record->qty,
									 $record->tva_tx,
									 0,
									 0,
									$record->fk_product,
									$record->remise_percent,
									0,
									0,
									'HT',
									0,
									$record->date_start,
									$record->date_end,
									0,
									-1,
									0,
									0,
									null,
									$record->buy_price_ht, //pa_ht
									"",
									0,
									$record->fk_unit);
				}
				break;

			// Proposals
			case 'PROPAL_CREATE':

				// AJOUT AUTOMATIQUE DES PRODUITS PAR DEFAUT  ASSIGNÉS AU  TIER DE CETTE PROPOSITION COMMERCIALE
				dol_include_once('/productdefault/class/productthirdpartydefault.class.php');
				$pdefault = new ProductThirdpartyDefault($db);
				$records = $pdefault->fetchAll('ASC','t.rowid',0,0, array('t.fk_soc' => $object->socid, 'pa.type_assignment'  => ProductThirdpartyDefault::TYPE_ASSIGNMENT_PROPOSAL,'t.entity'=> $conf->entity ),'AND',true);
				foreach ($records as $record){
						$object->addLine($record->description,
							$record->subprice,
							$record->qty,
							$record->tva_tx,
							0,
							0,
							$record->fk_product,
							$record->remise_percent,
							'HT',
							0.0,
							0,
							0,
							-1,
							0, //special code
							0,
							0,
							$record->buy_price_ht,
							'',
							$record->date_start,
							$record->date_end,
							0,
							$record->fk_unit);
				}
				break;

			default:
				dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);
				break;
		}

		return 0;
	}
}
