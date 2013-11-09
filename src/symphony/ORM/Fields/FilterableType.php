<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\Settings;

	interface FilterableType {
		public function filters();
	}