<?php

	namespace symphony\ORM\Filters;

	class DateEquality implements EqualityFilter {
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