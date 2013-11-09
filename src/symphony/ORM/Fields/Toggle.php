<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\DataFilters;

	class Toggle extends Type implements FilterableType {
		public function filters() {
			return [
				new DataFilters\Equality(),
				new DataFilters\Toggle()
			];
		}

		public function validate() {
			yield true;
		}
	}