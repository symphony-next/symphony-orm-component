<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\DataFilters;

	class Date extends Type implements FilterableType {
		public function filters() {
			return [
				new DataFilters\DateEquality(),
				new DataFilters\DateRange()
			];
		}
	}