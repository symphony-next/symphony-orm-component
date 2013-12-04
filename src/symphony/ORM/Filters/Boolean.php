<?php

	namespace symphony\ORM\Filters;

	class Boolean implements BooleanFilter {
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