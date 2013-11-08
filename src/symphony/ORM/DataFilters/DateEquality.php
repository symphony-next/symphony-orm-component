<?php

	namespace symphony\ORM\DataFilters;

	class DateEquality implements EqualityType {
		public function listMethods() {
			return [
				'filter' =>		'is',
				'notFilter' =>	'isNot'
			];
		}

		public function filter($value) {

		}

		public function notFilter($value) {

		}
	}