<?php 
namespace Star\utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class Serializer
{
	public static function jsonEncode($data, array $context = array())
	{
		$defaults = array (
			'json_encode_options' => defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0
		);
		return (new JsonEncoder)->encode($data, 'json', array_replace($defaults, $context));
	}

	public static function jsonDecode($data, array $context = array())
	{
		$defaults = array (
			'json_decode_associative'       => true,
            			'json_decode_recursion_depth'   => 512,
            			'json_decode_options'           => 0,
		);
		return (new JsonEncoder)->decode($data, 'json', array_replace($defaults, $context));
	}

	public static function xmlEncode($data, array $context = array())
	{
		$defaults = array (
			'xml_root_node_name'    => 'xml',
			'xml_format_output'     => true,
			'xml_version'           => '1.0',
			'xml_encoding'          => 'utf-8',
			'xml_standalone'        => false,
		);
		return (new XmlEncoder)->encode($data, 'xml', array_replace($defaults, $context));
	}

	public static function xmlDecode($data, array $context = array())
	{
		return (new XmlEncoder)->decode($data, 'xml', $context);
	}

	public static function parse($string)
	{
		if (static::isJSON($string)) {
			$result = static::jsonDecode($string);
		} elseif (static::isXML($string)) {
			$result = static::xmlDecode($string);
		} else {
			throw new \InvalidArgumentException(sprintf('Unable to parse: %s', (string) $string));
		}

		return (array) $result;
	}

	public static function isJSON($data)
	{
		return (@json_decode($data) !== null);
	}

	public static function isXML($data)
	{
		$xml = @simplexml_load_string($data);
		return ($xml instanceof \SimpleXMLElement);
	}
}