<?php

	namespace symphony\ORM\Filters;

	class Equality implements EqualityFilter {
		public function listMethods() {
			return [
				'filter' =>		'is',
				'notFilter' =>	'isNot'
			];
		}

		public function is($value, $something = false) {
			return $this->filter($value, $something);
		}

		public function filter($value, $something = false) {
			return sprintf('( field.value = "%s" )', $value);
		}

		public function notFilter($value) {
			return sprintf('( field.value != "%s" )', $value);
		}
	}