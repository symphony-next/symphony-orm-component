<?php

	namespace symphony\ORM\DataFilters;

	class Checkbox implements BooleanType {
		public function listMethods() {
			return [
				'filter' =>		'isChecked',
				'notFilter' =>	'isNotChecked'
			];
		}

		public function filter() {
			// Is checkbox = 'yes'
		}

		public function notFilter() {
			// Is checkbox = 'no' or not found or null
		}
	}