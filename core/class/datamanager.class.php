<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class datamanager extends eqLogic {
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
    public static function cron() {
    foreach (self::byType('datamanager') as $eqlogic) {//parcours tous les équipements du plugin fronius
      if ($eqlogic->getIsEnable() == 1) {//vérifie que l'équipement est actif
        $cmd = $eqlogic->getCmd(null, 'refresh');//retourne la commande "refresh si elle existe
        if (!is_object($cmd)) {//Si la commande n'existe pas
          continue; //continue la boucle
        }
        $cmd->execCmd(); // la commande existe on la lance
      }
    }
    }
  */
  public static function cron() {
      foreach (self::byType('datamanager') as $eqlogic) {
          if ($eqlogic->getIsEnable() == 1) {
              // Execute the refresh command if it exists
              $cmd = $eqlogic->getCmd(null, 'refresh');
              if (!is_object($cmd)) {
                  continue;
              }
              $cmd->execCmd();
          }
      }
  }



  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
    $this->setIsEnable(1);
    $this->setIsVisible(1);
    $this->save();
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    
    $info = $this->getCmd(null, 'home_instant_consomation');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Consomation instantanée", __FILE__));
    }
    $info->setLogicalId('home_instant_consomation');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('W');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setOrder(1);
    $info->save();
    
    $info = $this->getCmd(null, 'daily_cumulativeConsumption');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Consomation journalière", __FILE__));
    }
    $info->setLogicalId('daily_cumulativeConsumption');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(2);
    $info->save();

    $info = $this->getCmd(null, 'daily_cumulativeImport');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Import journalière", __FILE__));
    }
    $info->setLogicalId('daily_cumulativeImport');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(3);
    $info->save();

    $info = $this->getCmd(null, 'daily_cumulativeExport');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Revente journalière", __FILE__));
    }
    $info->setLogicalId('daily_cumulativeExport');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(3);
    $info->save();

    $info = $this->getCmd(null, 'daily_autoconsomation');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Autoconsomation journalière", __FILE__));
    }
    $info->setLogicalId('daily_autoconsomation');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(4);
    $info->save();

    $info = $this->getCmd(null, 'lastRefresh');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("LastRefresh", __FILE__));
    }
    $info->setLogicalId('lastRefresh');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setIsVisible(0);
    $info->setOrder(25);
    $info->save();

    $info = $this->getCmd(null, 'fronius_pac');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Production", __FILE__));
    }
    $info->setLogicalId('fronius_pac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('W');
    $info->setIsVisible(1);
    $info->setOrder(5);
    $info->setIsHistorized(1);
    // $info->setConfiguration("minValue", 0);
    // $info->setConfiguration("maxValue", config::byKey('global_fronius_wc', 'datamanager'));
    $info->save();

    $info = $this->getCmd(null, 'fronius_day_energy');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Production journalière", __FILE__));
    }
    $info->setLogicalId('fronius_day_energy');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(8);
    $info->save();

    $info = $this->getCmd(null, 'fronius_year_energy');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Production annuelle", __FILE__));
    }
    $info->setLogicalId('fronius_year_energy');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setOrder(9);
    $info->save();

    $info = $this->getCmd(null, 'fronius_total_energy');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Production total", __FILE__));
    }
    $info->setLogicalId('fronius_total_energy');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setOrder(10);
    $info->save();

    $info = $this->getCmd(null, 'fronius_uac');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("UAC", __FILE__));
    }
    $info->setLogicalId('fronius_uac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('V');
    $info->setIsVisible(0);
    $info->setOrder(11);
    $info->save();

    $info = $this->getCmd(null, 'fronius_udc');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("UDC", __FILE__));
    }
    $info->setLogicalId('fronius_udc');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('V');
    $info->setIsVisible(0);
    $info->setOrder(12);
    $info->save();


    $info = $this->getCmd(null, 'fronius_iac');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("IAC current", __FILE__));
    }
    $info->setLogicalId('fronius_iac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('A');
    $info->setIsVisible(0);
    $info->setOrder(13);
    $info->save();

    $info = $this->getCmd(null, 'fronius_idc');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("IDC current", __FILE__));
    }
    $info->setLogicalId('fronius_idc');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('A');
    $info->setIsVisible(0);
    $info->setOrder(14);
    $info->save();

    $info = $this->getCmd(null, 'fronius_fac');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("Fréquence", __FILE__));
    }
    $info->setLogicalId('fronius_fac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Hz');
    $info->setIsVisible(0);
    $info->setOrder(15);
    $info->save();

    $info = $this->getCmd(null, 'fronius_powerreal_p_sum');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("P Sum", __FILE__));
    }
    $info->setLogicalId('fronius_powerreal_p_sum');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('W');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setOrder(16);
    $info->save();



    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
      $refresh = new edf_tempoCmd();
      $refresh->setName(__('Rafraichir', __FILE__));
    }
    $refresh->setEqLogic_id($this->getId());
    $refresh->setLogicalId('refresh');
    $refresh->setType('action');
    $refresh->setSubType('other');
    $info->setOrder(1);
    $refresh->save();  

    $info = $this->getCmd(null, 'debug_json');
    if (!is_object($info)) {
      $info = new edf_tempoCmd();
      $info->setName(__("JSON", __FILE__));
    }
    $info->setLogicalId('debug_json');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setIsVisible(0);
    $info->setOrder(17);
    $info->save();
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }
  */

  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {}
  */

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*     * **********************Getteur Setteur*************************** */
  public function updateFroniusDatamanagerInfos() {
    log::add('datamanager', 'info', "Récupération des données sur l'onduleur.");
    $getGetInverterRealtimeData   = $this->getGetInverterRealtimeData();
    $pac = $getGetInverterRealtimeData->Body->Data->PAC->Value;

    $this->checkAndUpdateCmd('fronius_pac', $pac);
    $this->checkAndUpdateCmd('fronius_day_energy', $getGetInverterRealtimeData->Body->Data->DAY_ENERGY->Value);
    $this->checkAndUpdateCmd('fronius_year_energy', $getGetInverterRealtimeData->Body->Data->YEAR_ENERGY->Value);
    $this->checkAndUpdateCmd('fronius_total_energy', $getGetInverterRealtimeData->Body->Data->TOTAL_ENERGY->Value);

    $this->checkAndUpdateCmd('fronius_fac', $getGetInverterRealtimeData->Body->Data->FAC->Value);
    $this->checkAndUpdateCmd('fronius_iac', $getGetInverterRealtimeData->Body->Data->IAC->Value);
    $this->checkAndUpdateCmd('fronius_idc', $getGetInverterRealtimeData->Body->Data->IDC->Value);
    $this->checkAndUpdateCmd('fronius_uac', $getGetInverterRealtimeData->Body->Data->UAC->Value);
    $this->checkAndUpdateCmd('fronius_udc', $getGetInverterRealtimeData->Body->Data->UDC->Value);

    $getGetMeterRealtimeData        = $this->getGetMeterRealtimeData();
    $data                           = $getGetMeterRealtimeData->Body->Data;
    $firstData                      = current($data);
    $powerReal_P_Sum                = floatval($firstData->PowerReal_P_Sum);
    $instantaneousConsumptionWatts  = $pac + $powerReal_P_Sum;

    $this->checkAndUpdateCmd('fronius_powerreal_p_sum', $powerReal_P_Sum);

    $this->checkAndUpdateCmd('home_instant_consomation', $instantaneousConsumptionWatts);

    $this->checkAndUpdateCmd('debug_json', json_encode($firstData));       



    $currentTimestamp      = time();

    $lastRefresh    = $this->getCmd(null,'lastRefresh')->execCmd();
    if ($lastRefresh === 0){
      $lastRefresh    = time() - 2;
    }

    $daily_cumulativeConsumption    = $this->getCmd(null,'daily_cumulativeConsumption')->execCmd();
    if ($daily_cumulativeConsumption === 0){
      $daily_cumulativeConsumption    = 0;
    }

    $daily_cumulativeImport    = $this->getCmd(null,'daily_cumulativeImport')->execCmd();
    if ($daily_cumulativeImport === 0){
      $daily_cumulativeImport    = 0;
    }

    $daily_cumulativeExport    = $this->getCmd(null,'daily_cumulativeExport')->execCmd();
    if ($daily_cumulativeExport === 0){
      $daily_cumulativeExport    = 0;
    }
    
    $daily_autoconsomation    = $this->getCmd(null,'daily_autoconsomation')->execCmd();
    if ($daily_autoconsomation === 0){
      $daily_autoconsomation    = 0;
    }

    $timeDifference = $currentTimestamp - $lastRefresh;
    // log::add('datamanager', 'info', "Calcul de la conso");
    // log::add('datamanager', 'info', "Delta $timeDifference secondes");

    // log::add('datamanager', 'info', "Conso instantanée: $instantaneousConsumptionWatts Watts");
    // log::add('datamanager', 'info', "lastRefresh : ".$lastRefresh);

    if ($lastRefresh > 0) {
        // Consomation journalière en kWh
        $daily_cumulativeConsumption += ($instantaneousConsumptionWatts * $timeDifference) / 3600; // Convert seconds to hours Wh
        if ($powerReal_P_Sum > 0){
          $daily_cumulativeImport += ($powerReal_P_Sum * $timeDifference) / 3600; // Convert seconds to hours Wh
        }
        if ($powerReal_P_Sum < 0){
          $daily_cumulativeExport += (abs($powerReal_P_Sum) * $timeDifference) / 3600; // Convert seconds to hours Wh
        }

        // Autoconsomation journalière ne kWh
        if ($pac > 0){
          $instantAuto = $pac;
          if ($pac >= $instantaneousConsumptionWatts ){
            $instantAuto = $instantaneousConsumptionWatts;
          }

          $daily_autoconsomation += ($instantAuto * $timeDifference) / 3600; // Convert seconds to hours Wh
        }
        
        // Remise à zéro des compteurs à minuit
        $currentHour    = date('H', $currentTimestamp);   // Heure (format 24 heures)
        $currentMinute  = date('i', $currentTimestamp); // Minutes
        $currentSecond  = date('s', $currentTimestamp); // Secondes

        $isMidnight = ($currentHour === '00' && $currentMinute === '00' && intval($currentSecond) <= 10);
        if ($isMidnight) {
            $daily_cumulativeConsumption  = $daily_autoconsomation = $daily_cumulativeImport = $daily_cumulativeExport  = 0;
        }


        log::add('datamanager', 'info', "Conso global : $daily_cumulativeConsumption kWh");
        $this->checkAndUpdateCmd('daily_cumulativeConsumption', $daily_cumulativeConsumption);
        $this->checkAndUpdateCmd('daily_cumulativeImport', $daily_cumulativeImport);
        $this->checkAndUpdateCmd('daily_cumulativeExport', $daily_cumulativeExport);
        $this->checkAndUpdateCmd('daily_autoconsomation', $daily_autoconsomation);
    }
    $this->checkAndUpdateCmd('lastRefresh', $currentTimestamp);
    $this->toHtml();
  }


  public function getEndpoint(){
      $protocole    = $this->getConfiguration('datamanager_protocole') == "http" ? "http://" : "http://";
      $port         = $this->getConfiguration('datamanager_port');
      $port         = isset($port) && !empty($port) ? ":".$port : "";
      $url            = $protocole . $this->getConfiguration('datamanager_ip').$port;
      // log::add('datamanager', 'info', "Url d'accès : ".$url);
      return $url;
  }

  public function getGetInverterRealtimeData(){
    $urlEndpoint = $this->getEndpoint();
    // $global_fronius_ip = config::byKey('global_fronius_ip', 'datamanager');
    $urlApi = $urlEndpoint."/solar_api/v1/GetInverterRealtimeData.cgi?Scope=Device&DeviceId=1&DataCollection=CommonInverterData";
    $InverterRealtimeData = $this->getJson($urlApi);
    log::add('datamanager', 'debug', "Récupération des informations");
    log::add('datamanager', 'debug', "URL : ". $urlApi);
    if($InverterRealtimeData === false){
      // $InverterRealtimeData = json_decode('{"PARAM_NB_J_BLANC":"NA","PARAM_NB_J_ROUGE":"NA","PARAM_NB_J_BLEU":"NA"}');
      log::add('datamanager', 'error', "Erreur de récupération de InverterRealtimeData");
    }
    return  $InverterRealtimeData;
  }

  public function getGetMeterRealtimeData(){
    $urlEndpoint = $this->getEndpoint();
    // $global_fronius_ip = config::byKey('global_fronius_ip', 'datamanager');
    $urlApi = $urlEndpoint."/solar_api/v1/GetMeterRealtimeData.cgi?Scope=System";
    $GetMeterRealtimeData = $this->getJson($urlApi);
    log::add('datamanager', 'debug', "Récupération des informations du SmartMeter");
    log::add('datamanager', 'debug', "URL : ". $urlApi);
    if($GetMeterRealtimeData === false){
      // $GetMeterRealtimeData = json_decode('{"PARAM_NB_J_BLANC":"NA","PARAM_NB_J_ROUGE":"NA","PARAM_NB_J_BLEU":"NA"}');
      log::add('datamanager', 'error', "Erreur de récupération de GetMeterRealtimeData");
    }
    return  $GetMeterRealtimeData;
  }

  public function getJson($url){
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>array( "User-Agent: Wget/1.20.3 (linux-gnu)",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Content-Type: application/json"
        )
      )
    );
    $context  = stream_context_create($opts);
    $string   = file_get_contents($url, false, $context);
    log::add('datamanager', 'info', $string);
    $retour   = json_decode($string);
// $retour =  json_encode($retour->Body->Data->PAC->Value);
    return $retour;    
  }

  public function toHtml($_version = 'dashboard') {
    $replace = $this->preToHtml($_version);
    if (!is_array($replace)) {
      return $replace;
    }
    $version = jeedom::versionAlias($_version);

    // Liste des commandes à récupérer et remplacer
    $commandsToReplace = array(
      'fronius_pac',
      'home_instant_consomation',
      'fronius_powerreal_p_sum',
      'fronius_day_energy',
      'daily_cumulativeImport',
      'daily_cumulativeExport',
      'daily_autoconsomation'
    );

    $replace["#reverseWay#"] = $replace["#animated_solar#"] = "";
    $replace["#sunCurrentSpeed#"] = $replace["#houseCurrentSpeed#"] =0;
    $solarProductionW =$solarExport = $consoInstant = 0;

    // Parcourir les commandes à remplacer
    foreach ($commandsToReplace as $commandName) {
      $cmd = $this->getCmd(null, $commandName);
      if (is_object($cmd) && $cmd->getType() == 'info') {
        $commandValue = $cmd->execCmd();

        if ($commandName == "home_instant_consomation"){
          $consoInstant = $commandValue;
        }

        if ($commandName == "fronius_powerreal_p_sum"){
          $solarExport = $commandValue;
          if ($commandValue < 0){
            $replace["#reverseWay#"] = "reverse";
            $commandValue = abs($commandValue);
          }          
        }

        if ($commandName == "fronius_pac" && $commandValue > 0){
          $replace["#animated_solar#"] = "animated";
          $solarProductionW = $commandValue;
        }

        $w = $this->convertToReadablePower($commandValue);
        $replace["#".$commandName."_unite#"] = $w['unite'];
        $replace["#".$commandName."#"] = $w['value'];

      } else {
        $replace['#' . $commandName . '#'] = 'Valeur indisponible';
      }
    }

    // $maxWC =config::byKey('global_fronius_wc', __CLASS__);
    $maxWC =$this->getConfiguration('datamanager_puissance');

    $replace["#sunCurrentSpeed#"]   = ($solarProductionW * 100 )/ $maxWC;
    
    $replace["#houseCurrentSpeed#"] = ($consoInstant * 100 )/ $maxWC;
    if ($solarExport < 0){
      $replace["#houseCurrentSpeed#"] = ( ($solarProductionW - $consoInstant) * 100 )/ $maxWC;
    }

    return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'solar', 'datamanager')));
  }

  function convertToReadablePower($valueInWatts) {
      $units = array('W', 'kW', 'MW', 'GW', 'TW', 'PW'); // Unités possibles
      $magnitude = 0;
      while ($valueInWatts >= 1000 && $magnitude < count($units) - 1) {
          $valueInWatts /= 1000;
          $magnitude++;
      }
      $retour['value'] = number_format($valueInWatts, 2);
      $retour['unite'] = $units[$magnitude];
      return $retour;
  }

}

class datamanagerCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
      $eqlogic = $this->getEqLogic();
      switch ($this->getLogicalId()) {
          case 'refresh': 
              log::add('datamanager', 'info', "Mise à jour forcée le " . date("m-d-Y à H:i"));
              $eqlogic->updateFroniusDatamanagerInfos();     
              $eqlogic->refreshWidget();         
      }
  }


  /*     * **********************Getteur Setteur*************************** */

}
