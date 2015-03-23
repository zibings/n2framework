<?php

	namespace N2f;

	abstract class Enum {
		private static $constCache = null;

		public static function getConstList() {
			if (self::$constCache === null) {
				self::$constCache = array();
			}

			$cclass = get_called_class();

			if (!array_key_exists($cclass, self::$constCache)) {
				$ref = new \ReflectionClass($cclass);
				self::$constCache[$cclass] = $ref->getConstants();
			}

			return self::$constCache[$cclass];
		}
	}

	/**
	 * Enumerated values of possible request types for HTTP requests.
	 */
	class RequestType extends Enum {
		const PUT = 1;
		const POST = 2;
		const GET = 3;
		const HEAD = 4;
		const DELETE = 5;
		const OPTIONS = 6;
		const ERROR = 7;
	}

	/**
	 * Enumerated values of possible environment types.
	 */
	class EnvironmentInfo extends Enum {
		const SERVER = 1;
		const COOKIE = 2;
		const ENV = 3;
		const FILES = 4;
	}

?>