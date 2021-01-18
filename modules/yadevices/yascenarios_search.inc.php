<?php
global $session;
if ($this->owner->name=='panel') {
	$out['CONTROLPANEL']=1;
}

if($this->mode = 'runScenario' && !empty(strip_tags($this->id))) {
	$this->runScenario(strip_tags($this->id));
}

if($this->mode = 'delScenario' && !empty(strip_tags($this->id))) {
	$this->delScenario(strip_tags($this->id));
}

$data = $this->apiRequest('https://iot.quasar.yandex.ru/m/user/scenarios');

foreach($data["scenarios"] as $key => $scenarios) {
	$data["scenarios"][$key] = array_change_key_case($scenarios, CASE_UPPER);
	
	
	if(strpos($scenarios['name'], 'мжд ') !== false) {
		$data["scenarios"][$key]['CLOUDSCENARIO'] = 1;
	} else {
		$data["scenarios"][$key]['CLOUDSCENARIO'] = 0;
	}
	
	if($scenarios['is_active']) {
		$data["scenarios"][$key]['IS_ACTIVE'] = 'Активен';
	} else {
		$data["scenarios"][$key]['IS_ACTIVE'] = 'Отключен';
	}
	
	foreach($scenarios["devices"] as $devices) {
		$allowDevice .= $devices.', ';
	}
	$data["scenarios"][$key]['ALLOWDEVICES'] = substr($allowDevice, 0, -2);
	$allowDevice = '';
}

$out['RESULT'] = $data["scenarios"];