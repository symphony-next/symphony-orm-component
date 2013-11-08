<?php

	namespace symphony\ORM\DataFilters;

	class Boolean implements BooleanType {
		public function listMethods() {
			return [
				'filter' =>		'is',
				'notFilter' =>	'isNot'
			];
		}

		public function filter() {

		}

		public function notFilter() {

		}
	}