<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\DataFilters;

	class Text extends Type implements FilterableType {
		public function filters() {
			return [
				new DataFilters\Equality()
			];
		}

		public function validate() {
			if ($this->settings()->required === 'yes') {
				throw new RequiredException("'{$this->settings()->handle}' is a required field.");
			}

			yield true;
		}
	}