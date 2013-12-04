<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\Filters;
	use symphony\ORM\Formats;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use DateTime;
	use PDO;

	class Date extends Field implements FilterableField {
		public function format() {
			return new Formats\Date($this);
		}

		public function filters() {
			return [
				new Filters\DateEquality(),
				new Filters\DateRange()
			];
		}
	}