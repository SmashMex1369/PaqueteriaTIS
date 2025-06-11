<?php

class XmlHandler {
    public static function generarXML($data, $rootElement, $childElement = null) {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$rootElement/>");
    if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            foreach ($data as $item) {
                $child = $xml->addChild($childElement);
                foreach ($item as $key => $value) {
                    if (is_array($value)) {
                        $subchild = $child->addChild($key);
                        foreach ($value as $subkey => $subvalue) {
                            $subchild->addChild($subkey, htmlspecialchars($subvalue));
                        }
                    } else {
                        $child->addChild($key, htmlspecialchars($value));
                    }
                }
            }
        } else {
            foreach ($data as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
    }
}
?>