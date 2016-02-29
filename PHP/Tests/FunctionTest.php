<?php

	class N2fFunctionTests extends PHPUnit_Framework_TestCase {
		public function testIsWindowsOrNot() {
			$this->assertEquals(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN', env_is_windows());

			return;
		}
	}
