<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\Filters;
	use symphony\ORM\Formats;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use PDO;

	class Text extends Field implements FilterableField {
		public function format() {
			return new Formats\Text();
		}

		public function filters() {
			return [
				new Filters\Equality()
			];
		}
	}