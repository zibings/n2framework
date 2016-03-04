<?php

	class DispatchTest extends PHPUnit_Framework_TestCase {
		public function testConfigDispatch_getsExtension() {
			$disp = new N2f\ConfigDispatch();
			$disp->Initialize(array('ext=Test'));

			$this->assertEquals('Test', $disp->GetExt());
		}
	}
