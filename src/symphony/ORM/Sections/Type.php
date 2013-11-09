<?php

	namespace symphony\ORM\Sections;
	use symphony\ORM\Fields;
	use symphony\ORM\Settings;
	use DOMDocument;

	class Type {
		protected $fields;
		protected $settings;

		public function __construct() {
			$this->fields = new Fields\Controller();
			$this->settings = new Settings\Controller([
				'fields' =>		$this->fields
			]);
		}

		public function openUri($file) {
			$document = new DOMDocument();
			$document->load($file);

			$this->settings->fromXML($document->documentElement);
		}

		public function settings() {
			return $this->settings;
		}

		public function fields() {
			return $this->fields;
		}
	}