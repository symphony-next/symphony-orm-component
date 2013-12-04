<?php

	namespace symphony\ORM\Filters;

	class Toggle implements BooleanFilter {
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