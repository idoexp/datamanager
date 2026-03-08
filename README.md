# Fronius DataManager & Smartmeter pour Jeedom

Ce plugin Jeedom, va récupérer les informations sur les votre onduleur Fronius. Il récupère également les informations du smartmeter afin d'afficher la production solaire jounalière ainsi que la consomation instantanée de la maison.

Vous pouvez visualiser le widget d'affichage https://codepen.io/idoExperiences/pen/vYQMQZN

Compatible pour **Jeedom** 4.2

# Installation du plugin

Utilisez le market de **Jeedom** pour télécharger et installer le plugins.
Ou téléchargez le zip, et copier son contenue dans le dossier **/var/www/html/plugins/datamanager** de votre machine (sous linux)

# Activation du plugin

* **Activer** le plugin
Renseignez l'adresse ip de votre onduleur ainsi que la puissance maximale de votre installation (en Warr Crête)

# Création d'un équipement

* Allez dans les plugins > energie > **Fronius Datamanager**
* Cliquez ensuite sur **Ajouter**
* Nommez l'équipement, par exeple **Fronius** et faite **ok**

L'équipement est créé et actif, il vous reste plus qu'à le positionner dans votre environnement, par défaut il est dans la rubrique **aucun**
A partir du moment où l'équipement est activé il récupère les informations de l'onduleur toutes les minutes.
Toutes les valeurs exprimé en kWh (kilowattheure) excepté la production journalière, sont calculés à partir du moment où l'équipement est actif. Il est donc possible que les premières données relevés ne correspondent pas à la réalité.
Par ailleurs le raffraichissement ayant lieu toutes les minutes il est possible que vous ayez un décalage de quelques watts par rapport à votre fournisseur d'électricité ou l'application solar.web.

# changelog

## 8 mars 2026
* Correction du bug critique cron "Unsupported operand types: int - string" (PHP 8+)
* Correction des casts manquants sur les valeurs retournées par `execCmd()` (int/float)
* Correction de `setOrder()` appliqué sur la mauvaise variable dans `postSave()`
* Protection contre la division par zéro dans le widget
* Ajout des graphiques d'historique : clic sur le widget ouvre 4 courbes superposées (Production, AutoConso, Revente, Achat) avec couleurs distinctes
* Affichage forcé en lignes (au lieu de aires) pour une meilleure lisibilité
* Activation de l'historisation sur toutes les commandes numériques
* Configuration du mode d'archivage (`historizeMode: avg`) pour la compaction des données anciennes
* Correction des balises `<sup>` mal fermées dans le template
* Correction de la persistance du bouton refresh après mise à jour du widget (délégation d'événements)

## 8 août 2023
* Mise à jour initiale
