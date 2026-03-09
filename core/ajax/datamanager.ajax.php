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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    ajax::init();

    if (init('action') == 'testConnection') {
        $eqLogicId = init('id');
        $eqLogic = eqLogic::byId($eqLogicId);
        if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'datamanager') {
            throw new Exception(__('Équipement introuvable', __FILE__));
        }
        $endpoint = $eqLogic->getEndpoint();
        $url = $endpoint . "/solar_api/v1/GetInverterRealtimeData.cgi?Scope=Device&DeviceId=1&DataCollection=CommonInverterData";
        $result = $eqLogic->getJson($url);
        if ($result === false) {
            throw new Exception(__('Connexion échouée : impossible de joindre l\'onduleur à ', __FILE__) . $endpoint);
        }
        if (!isset($result->Body->Data->PAC)) {
            throw new Exception(__('Connexion établie mais réponse inattendue de l\'onduleur (il est peut-être en veille)', __FILE__));
        }
        $pac = $result->Body->Data->PAC->Value;
        ajax::success(__('Connexion réussie ! Production actuelle : ', __FILE__) . $pac . 'W');
    }

    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
}
catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
