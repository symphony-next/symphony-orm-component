<?php

	namespace symphony\ORM\DataFilters;

	interface NumericRangeType extends Type {
		public function between($from, $to);
		public function lessThan($value);
		public function greaterThan($value);
	}