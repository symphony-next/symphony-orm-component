<?php

	namespace symphony\ORM\Formats;
	use symphony\ORM\Fields\Field;
	use DOMElement;
	use ReflectionObject;
	use ReflectionMethod;

	class Controller {
		protected $object;
		protected $reflection;

		public function __call($name, $arguments) {
			return $this->reflection->getMethod($name)->invokeArgs($this->object, $arguments);
		}

		public function __isset($name) {
			return isset($this->object->{$name});
		}

		public function __get($name) {
			return $this->object->{$name};
		}

		public function __set($name, $value) {
			return $this->object->{$name} = $value;
		}

		public function __unset($name) {
			unset($this->object->{$name});
		}

		public function fromXML(DOMElement $xml) {
			if (
				($xml instanceof DOMElement) === false
				|| $xml->hasAttribute('type') === false
			) return;

			$type = $xml->getAttribute('type');
			$format = new $type();
			$format->settings()->fromXML($xml);

			$this->object = $format;
			$this->reflection = new ReflectionObject($format);
		}
	}