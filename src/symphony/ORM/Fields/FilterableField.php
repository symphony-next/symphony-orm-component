<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\Settings;

	interface FilterableField {
		public function filters();
	}