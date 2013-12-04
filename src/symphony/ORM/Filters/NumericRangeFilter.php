<?php

	namespace symphony\ORM\Filters;

	interface NumericRangeFilter extends Filter {
		public function between($from, $to);
		public function lessThan($value);
		public function greaterThan($value);
	}