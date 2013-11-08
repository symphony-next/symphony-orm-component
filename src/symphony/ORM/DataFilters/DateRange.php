<?php

	namespace symphony\ORM\DataFilters;

	class DateRange implements NumericRangeType {
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