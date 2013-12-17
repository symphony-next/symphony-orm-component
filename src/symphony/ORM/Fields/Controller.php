<?php

	namespace symphony\ORM\Fields;
	use ArrayIterator;
	use DOMElement;
	use StdClass;

	class Controller extends ArrayIterator {
		public function __construct() {
			parent::__construct([]);
		}

		public function __get($handle) {
			if ($this->offsetExists($handle) === false) return null;

			return $this->offsetGet($handle);
		}

		public function __isset($handle) {
			return $this->offsetExists($handle);
		}

		public function __set($handle, $value) {
			return $this->offsetSet($handle, $value);
		}

		public function __unset($handle) {
			$this->offsetUnset($handle);
		}

		public function fromObject(StdClass $settings) {
			foreach ($settings as $value) {
				if (($value instanceof StdClass) === false) continue;

				$type = $value->type;
				$field = new $type();
				$field->settings->fromObject($value);
				$handle = $field->settings()->handle;

				$this->offsetSet($handle, $field);
			}
		}

		public function fromXML(DOMElement $xml) {
			foreach ($xml->childNodes as $node) {
				if (
					($node instanceof DOMElement) === false
					|| $node->hasAttribute('type') === false
				) continue;

				$type = $node->getAttribute('type');
				$field = new $type();
				$field->settings()->fromXML($node);
				$handle = $field->settings()->handle;

				$this->offsetSet($handle, $field);
			}
		}
	}