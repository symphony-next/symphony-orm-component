<?php

	namespace symphony\ORM\Formats;
	use symphony\ORM\Fields\Field;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use symphony\ORM\Settings;
	use DOMElement;
	use PDO;

	trait FormatUtilities {
		protected $settings;

		public function __construct() {
			$this->settings = new Settings\Controller();
		}

		public function settings() {
			return $this->settings;
		}
	}