<?php

	class InvalidDispatch extends N2f\DispatchBase {
		public function Initialize($Input) { }
	}

	class ValidDispatch extends N2f\DispatchBase {
		public function Initialize($Input) {
			$this->MakeValid();
		}
	}

	class InvalidNode extends N2f\NodeBase {
		public function __construct() { }

		public function Process($Sender, N2f\DispatchBase &$Dispatch) { }
	}

	class ValidNode extends N2f\NodeBase {
		public function __construct() {
			$this->SetKey("ValidNode")->SetVersion("1");
		}

		public function Process($Sender, N2f\DispatchBase &$Dispatch) { }
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

		public function testNodeIsNotValid() {
			$node = new InvalidNode();

			$this->assertEquals(false, $node->IsValid());
		}

		public function testNodeIsValid() {
			$node = new ValidNode();

			$this->assertEquals(true, $node->IsValid());
		}
	}
