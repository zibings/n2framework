<?php

	class N2fFunctionTests extends PHPUnit_Framework_TestCase {
		public function testIsWindowsOrNot() {
			$this->assertEquals(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN', env_is_windows());

			return;
		}

		public function testParamsReturnsCorrectNumberOfArgs() {
			$arguments = array(
				'test',
				'test2',
				'test3'
			);

			$args = env_parse_params($arguments);

			$this->assertEquals(3, count($args));

			return;
		}

		public function testParamsReturnsInsensitiveKeys() {
			$arguments = array(
				'teSt',
				'Test2',
				'TEST3'
			);

			$args = env_parse_params($arguments, true);
			$index = 0;

			foreach ($args as $key => $val) {
				$this->assertNotEquals($key, $arguments[$index]);

				++$index;
			}

			return;
		}

		public function testParamsReturnSensitiveKeys() {
			$arguments = array(
				'teSt',
				'Test2',
				'TEST3'
			);

			$args = env_parse_params($arguments);
			$index = 0;

			foreach ($args as $key => $val) {
				$this->assertEquals($key, $arguments[$index]);

				++$index;
			}

			return;
		}

		public function testParamsAssignedValues() {
			$arguments = array(
				'test=value',
				'test2="howdy mom!"',
				'-test3',
				'value3'
			);

			$args = env_parse_params($arguments);

			$this->assertEquals(3, count($args));

			$this->assertEquals('value', $args['test']);
			$this->assertEquals('howdy mom!', $args['test2']);
			$this->assertEquals('test3', $args['test3']);

			return;
		}
	}
