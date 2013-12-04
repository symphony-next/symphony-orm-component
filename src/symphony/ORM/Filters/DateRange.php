<?php

	namespace symphony\ORM\Filters;

	class DateRange implements NumericRangeFilter {
		public function listMethods() {
			return [
				'between' =>		'between',
				'lessThan' =>		'earlierThan',
				'greaterThan' =>	'laterThan'
			];
		}

		public function between($from, $to) {

		}

		public function lessThan($value) {
			return sprintf('( field.value <= "%s" )', $value);
		}

		public function greaterThan($value) {
			return sprintf('( field.value >= "%s" )', $value);
		}
	}