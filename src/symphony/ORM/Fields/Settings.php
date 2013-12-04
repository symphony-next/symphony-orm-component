<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM;
	use DOMElement;

	class Settings extends ORM\Settings\Controller {
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