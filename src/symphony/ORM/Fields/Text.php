<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\DataFilters;

	class Text extends Type implements FilterableType {
		public function filters() {
			return [
				new DataFilters\Equality()
			];
		}
	}