<?php

	class InvalidDispatch extends N2f\DispatchBase {
		public function Initialize($Input) { }
	}

	class ValidDispatch extends N2f\DispatchBase {
		public function Initialize($Input) {
			$this->MakeValid();
		}
	}

	class N2fBaseTests extends PHPUnit_Framework_TestCase {
		public function testDispatchIsNotValid() {
			$disp = new InvalidDispatch();
			$disp->Initialize(null);

			$this->assertEquals(false, $disp->IsValid());
		}

		public function testDispatchIsValid() {
			$disp = new ValidDispatch();
			$disp->Initialize(null);

			$this->assertEquals(true, $disp->IsValid());
		}
	}
