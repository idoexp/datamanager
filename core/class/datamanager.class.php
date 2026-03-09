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
    $ip = $this->getConfiguration('datamanager_ip');
    if (!empty($ip)) {
      // Vérifier que l'adresse ne contient pas de caractères dangereux
      if (preg_match('/[;\|&`\$]/', $ip)) {
        throw new Exception(__("L'adresse de l'onduleur contient des caractères non autorisés", __FILE__));
      }
    }
    $port = $this->getConfiguration('datamanager_port');
    if (!empty($port)) {
      $portInt = intval($port);
      if ($portInt < 1 || $portInt > 65535 || strval($portInt) !== $port) {
        throw new Exception(__("Le port doit être un nombre entre 1 et 65535", __FILE__));
      }
    }
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    
    $info = $this->getCmd(null, 'home_instant_consomation');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();
    
    $info = $this->getCmd(null, 'daily_cumulativeConsumption');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();

    $info = $this->getCmd(null, 'daily_cumulativeImport');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphColor', '#d1d3d3');
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();

    $info = $this->getCmd(null, 'daily_cumulativeExport');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphColor', '#47ac34');
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();

    $info = $this->getCmd(null, 'daily_autoconsomation');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphColor', '#25b2e8');
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();

    $info = $this->getCmd(null, 'lastRefresh');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphColor', '#fed308');
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();

    $info = $this->getCmd(null, 'fronius_day_energy');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();

    $info = $this->getCmd(null, 'fronius_year_energy');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("Production annuelle", __FILE__));
    }
    $info->setLogicalId('fronius_year_energy');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(9);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();

    $info = $this->getCmd(null, 'fronius_total_energy');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("Production total", __FILE__));
    }
    $info->setLogicalId('fronius_total_energy');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Wh');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(10);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();

    $info = $this->getCmd(null, 'fronius_uac');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("UAC", __FILE__));
    }
    $info->setLogicalId('fronius_uac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('V');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(11);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();

    $info = $this->getCmd(null, 'fronius_udc');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("UDC", __FILE__));
    }
    $info->setLogicalId('fronius_udc');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('V');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(12);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();


    $info = $this->getCmd(null, 'fronius_iac');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("IAC current", __FILE__));
    }
    $info->setLogicalId('fronius_iac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('A');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(13);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();

    $info = $this->getCmd(null, 'fronius_idc');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("IDC current", __FILE__));
    }
    $info->setLogicalId('fronius_idc');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('A');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(14);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();

    $info = $this->getCmd(null, 'fronius_fac');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
      $info->setName(__("Fréquence", __FILE__));
    }
    $info->setLogicalId('fronius_fac');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('numeric');
    $info->setUnite('Hz');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setOrder(15);
    $info->setConfiguration('historizeMode', 'avg');
    $info->save();

    $info = $this->getCmd(null, 'fronius_powerreal_p_sum');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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
    $info->setDisplay('graphType', 'line');
    $info->setConfiguration('historizeMode', 'avg');
    $info->setConfiguration('historizeRound', 2);
    $info->save();



    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
      $refresh = new datamanagerCmd();
      $refresh->setName(__('Rafraichir', __FILE__));
    }
    $refresh->setEqLogic_id($this->getId());
    $refresh->setLogicalId('refresh');
    $refresh->setType('action');
    $refresh->setSubType('other');
    $refresh->setOrder(1);
    $refresh->save();  

    $info = $this->getCmd(null, 'debug_json');
    if (!is_object($info)) {
      $info = new datamanagerCmd();
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

  /*     * **********************Getteur Setteur*************************** */
  public function updateFroniusDatamanagerInfos() {
    log::add('datamanager', 'info', "Récupération des données sur l'onduleur.");

    try {
    // Récupération des données onduleur (peut être indisponible la nuit)
    $pac = 0;
    $getGetInverterRealtimeData = $this->getGetInverterRealtimeData();
    if ($getGetInverterRealtimeData !== false && isset($getGetInverterRealtimeData->Body->Data->PAC->Value)) {
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
    } else {
      log::add('datamanager', 'debug', "Onduleur indisponible, production mise à 0");
      $this->checkAndUpdateCmd('fronius_pac', 0);
    }

    // Récupération des données SmartMeter (consommation, achat, revente)
    $getGetMeterRealtimeData = $this->getGetMeterRealtimeData();
    if ($getGetMeterRealtimeData === false || !isset($getGetMeterRealtimeData->Body->Data)) {
      log::add('datamanager', 'debug', "SmartMeter indisponible");
      return;
    }
    $data      = $getGetMeterRealtimeData->Body->Data;
    $firstData = current($data);
    $powerReal_P_Sum                = floatval($firstData->PowerReal_P_Sum);
    $instantaneousConsumptionWatts  = $pac + $powerReal_P_Sum;

    $this->checkAndUpdateCmd('fronius_powerreal_p_sum', $powerReal_P_Sum);
    $this->checkAndUpdateCmd('home_instant_consomation', $instantaneousConsumptionWatts);
    $this->checkAndUpdateCmd('debug_json', json_encode($firstData));       



    $currentTimestamp      = time();

    $lastRefresh = (int)$this->getCmd(null,'lastRefresh')->execCmd();
    if ($lastRefresh === 0){
      $lastRefresh = time() - 2;
    }

    $daily_cumulativeConsumption = (float)$this->getCmd(null,'daily_cumulativeConsumption')->execCmd();
    $daily_cumulativeImport     = (float)$this->getCmd(null,'daily_cumulativeImport')->execCmd();
    $daily_cumulativeExport     = (float)$this->getCmd(null,'daily_cumulativeExport')->execCmd();
    $daily_autoconsomation      = (float)$this->getCmd(null,'daily_autoconsomation')->execCmd();

    $timeDifference = $currentTimestamp - $lastRefresh;

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
        
        log::add('datamanager', 'info', "Conso global : $daily_cumulativeConsumption kWh");
        $this->checkAndUpdateCmd('daily_cumulativeConsumption', $daily_cumulativeConsumption);
        $this->checkAndUpdateCmd('daily_cumulativeImport', $daily_cumulativeImport);
        $this->checkAndUpdateCmd('daily_cumulativeExport', $daily_cumulativeExport);
        $this->checkAndUpdateCmd('daily_autoconsomation', $daily_autoconsomation);

        // Remise à zéro des compteurs à minuit (fenêtre de 90s pour ne pas rater le cron)
        $currentHour   = date('H', $currentTimestamp);
        $currentMinute = date('i', $currentTimestamp);
        $isMidnight = ($currentHour === '00' && $currentMinute === '00');
        if ($isMidnight) {
            log::add('datamanager', 'info', "Reset des compteurs journaliers (minuit)");
            $this->checkAndUpdateCmd('daily_cumulativeConsumption', 0);
            $this->checkAndUpdateCmd('daily_cumulativeImport', 0);
            $this->checkAndUpdateCmd('daily_cumulativeExport', 0);
            $this->checkAndUpdateCmd('daily_autoconsomation', 0);
        }
    }
    $this->checkAndUpdateCmd('lastRefresh', $currentTimestamp);
    $this->toHtml();

    } catch (Exception $e) {
      log::add('datamanager', 'error', "Erreur lors de la mise à jour : " . $e->getMessage());
    }
  }


  public function getEndpoint(){
      $protocole = $this->getConfiguration('datamanager_protocole') == "https" ? "https://" : "http://";
      $port      = $this->getConfiguration('datamanager_port');
      $port      = !empty($port) ? ":".$port : "";
      return $protocole . $this->getConfiguration('datamanager_ip') . $port;
  }

  public function getGetInverterRealtimeData(){
    $urlEndpoint = $this->getEndpoint();
    $urlApi = $urlEndpoint."/solar_api/v1/GetInverterRealtimeData.cgi?Scope=Device&DeviceId=1&DataCollection=CommonInverterData";
    $InverterRealtimeData = $this->getJson($urlApi);
    if($InverterRealtimeData === false){
      log::add('datamanager', 'debug', "Impossible de joindre l'onduleur");
    }
    return $InverterRealtimeData;
  }

  public function getGetMeterRealtimeData(){
    $urlEndpoint = $this->getEndpoint();
    $urlApi = $urlEndpoint."/solar_api/v1/GetMeterRealtimeData.cgi?Scope=System";
    $GetMeterRealtimeData = $this->getJson($urlApi);
    if($GetMeterRealtimeData === false){
      log::add('datamanager', 'debug', "Impossible de joindre le SmartMeter");
    }
    return $GetMeterRealtimeData;
  }

  public function getJson($url){
    if (function_exists('curl_init')) {
      log::add('datamanager', 'debug', "fonction getJson via -> cUrl");
      // Utiliser cURL pour récupérer les données
      $curl           = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_TIMEOUT,        15);
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
      $data           = curl_exec($curl);
      $httpRespCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      log::add('datamanager', 'debug', "Réponse HTTP : ". $httpRespCode);
      if ($httpRespCode == 0) {
        log::add('datamanager', 'debug', "Connexion impossible : ". curl_error($curl));
        return false;
      }
  
    } else {
  
      // Utilise file_get_contents pour récupérer les données
      log::add('datamanager', 'debug', "fonction getJson via -> file_get_contents");
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
      $data     = file_get_contents($url, false, $context);
      if ($data === false) {
          log::add('datamanager', 'error', "Impossible de récupérer les données : ". error_get_last()['message']);
          return false;
      }    
    }

    log::add('datamanager', 'debug', "Données récupérées : ". $data);
    $retour   = json_decode($data);
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
        $commandValue = (float)$cmd->execCmd();

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
        $replace["#".$commandName."_id#"] = $cmd->getId();

      } else {
        $replace['#' . $commandName . '#'] = 'Valeur indisponible';
      }
    }

    $maxWC = (float)$this->getConfiguration('datamanager_puissance');
    if ($maxWC <= 0) {
      $maxWC = 1; // Éviter la division par zéro
    }

    $replace["#sunCurrentSpeed#"]   = ($solarProductionW * 100 )/ $maxWC;

    $replace["#houseCurrentSpeed#"] = ($consoInstant * 100 )/ $maxWC;
    if ($solarExport < 0){
      $replace["#houseCurrentSpeed#"] = ( ($solarProductionW - $consoInstant) * 100 )/ $maxWC;
    }

    return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'solar', 'datamanager')));
  }

  function convertToReadablePower($valueInWatts) {
      $valueInWatts = (float)$valueInWatts;
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

  public function execute($_options = array()) {
      $eqlogic = $this->getEqLogic();
      switch ($this->getLogicalId()) {
          case 'refresh': 
              log::add('datamanager', 'info', "Mise à jour forcée le " . date("m-d-Y à H:i"));
              $eqlogic->updateFroniusDatamanagerInfos();     
              $eqlogic->refreshWidget();         
      }
  }
}
