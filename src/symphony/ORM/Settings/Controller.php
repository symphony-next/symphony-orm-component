<?php

	namespace symphony\ORM\Settings;
	use ArrayIterator;
	use DOMElement;
	use StdClass;

	class Controller extends ArrayIterator {
		protected $mappings;

		public function __construct(array $mappings = []) {
			$this->mappings = $mappings;

			parent::__construct([]);
		}

		public function __get($name) {
			if ($this->offsetExists($name) === false) return null;

			return $this->offsetGet($name);
		}

		public function __isset($name) {
			return $this->offsetExists($name);
		}

		public function __set($name, $value) {
			return $this->offsetSet($name, $value);
		}

		public function __unset($name) {
			$this->offsetUnset($name);
		}

		public function fromObject(StdClass $settings) {
			foreach ($settings as $key => $value) {
				if (isset($this->mappings[$key])) {
					$this->mappings[$key]->fromObject((object)$value);
				}

				else {
					$this->offsetSet($key, $value);
				}
			}
		}

		public function fromXML(DOMElement $xml) {
			foreach ($xml->childNodes as $node) {
				if (($node instanceof DOMElement) === false) continue;

				if (isset($this->mappings[$node->nodeName])) {
					$this->mappings[$node->nodeName]->fromXML($node);
				}

				else {
					$this->offsetSet($node->nodeName, $node->nodeValue);
				}
			}
		}
	}