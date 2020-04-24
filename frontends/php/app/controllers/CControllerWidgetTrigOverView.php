<?php
/*
** Zabbix
** Copyright (C) 2001-2020 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


class CControllerWidgetTrigOverView extends CControllerWidget {

	public function __construct() {
		parent::__construct();

		$this->setType(WIDGET_TRIG_OVER);
		$this->setValidationRules([
			'name' => 'string',
			'fields' => 'json',
			'uniqueid' => 'required|string',
			'initial_load' => 'in 0,1'
		]);
	}

	protected function doAction() {
		$fields = $this->getForm()->getFieldsData();

		$data = [
			'name' => $this->getInput('name', $this->getDefaultHeader()),
			'style' => $fields['style'],
			'user' => [
				'debug_mode' => $this->getDebugMode()
			],
			'initial_load' => (bool) $this->getInput('initial_load', false),
			'menu_options' => [
				'widget' => $this->getInput('uniqueid')
			]
		];

		$trigger_options = [
			'skipDependent' => ($fields['show'] == TRIGGERS_OPTION_ALL) ? null : true,
			'only_true' => ($fields['show'] == TRIGGERS_OPTION_RECENT_PROBLEM) ? true : null,
			'filter' => [
				'value' => ($fields['show'] == TRIGGERS_OPTION_IN_PROBLEM) ? TRIGGER_VALUE_TRUE : null
			]
		];

		$problem_options = [
			'show_suppressed' => $fields['show_suppressed'],
			'show_recent' => ($fields['show'] == TRIGGERS_OPTION_RECENT_PROBLEM) ? true : null
		];

		$host_options = [
			'hostids' => $fields['hostids'] ? $fields['hostids'] : null
		];

		list($data['db_hosts'], $data['db_triggers'], $data['dependencies'], $data['triggers_by_name'],
			$data['hosts_by_name'], $data['exceeded_hosts'], $data['exceeded_trigs']
		) = getTriggersOverviewData(getSubGroups($fields['groupids']), $fields['application'], $host_options,
			$trigger_options, $problem_options
		);

		$this->setResponse(new CControllerResponseData($data));
	}
}
