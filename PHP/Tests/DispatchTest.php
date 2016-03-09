<?php

	class DispatchTest extends PHPUnit_Framework_TestCase {
		public function testCliDispatch_isInvalidWithNoArguments() {
			$disp = new N2f\CliDispatch();
			$disp->Initialize(array());

			$this->assertEquals(false, $disp->IsValid());
		}

		public function testCliDispatch_isWindows() {
			$disp = new N2f\CliDispatch();
			$disp->Initialize(array('test', 'test2'));

			$this->assertEquals(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN', $disp->IsWindows());
		}

		// need to test every possible variation of initialization
		// need to test consolehelper being provided
		// need to do all of these tests for everything derived from CliDispatch

		public function testConfigDispatch_getsExtension() {
			$disp = new N2f\ConfigDispatch();
			$disp->Initialize(array('ext=Test'));

			$this->assertEquals('Test', $disp->GetExt());
		}

		public function testConfigDispatch_isInteractive() {
			$disp = new N2f\ConfigDispatch();
			$disp->Initialize(array('ext=Test'));

			$this->assertEquals(true, $disp->IsInteractive());
		}

		public function testConfigDispatch_isntInteractive() {
			$disp = new N2f\ConfigDispatch();
			$disp->Initialize(array('ext=Test', 'opt1=true', 'opt2=false', 'opt3=true'));

			$this->assertEquals(false, $disp->IsInteractive());
		}
	}
