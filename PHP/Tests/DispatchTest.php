<?php

	#region Mocks

	class JsonRequestHelperMock extends N2f\RequestHelper {
		public function __construct($JsonData) {
			self::$_InputString = $JsonData;

			return;
		}

		protected static function ReadInput() {
			$Ret = new N2f\ReturnHelper();
			$Ret->SetGud();

			return $Ret;
		}
	}

	class NonJsonRequestHelperMock extends N2f\RequestHelper {
		public function IsJson() {
			return false;
		}
	}

	class MockJsonHelper extends N2f\JsonHelper {
		public function __construct() {
			parent::__construct();
		}
	}

	#endregion

	class DispatchTest extends PHPUnit_Framework_TestCase {
		#region CliDispatch

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

		#endregion

		#region ConfigDispatch

		public function testConfigDispatch_setsAsInvalidWithoutCliInitializers() {
			$disp = new N2f\ConfigDispatch();
			$disp->Initialize('test');

			$this->assertEquals(false, $disp->IsValid());
		}

		public function testConfigDispatch_setsAsInvalidWithoutExt() {
			$disp = new N2f\ConfigDispatch();
			$disp->Initialize(array('dosomething=true'));

			$this->assertEquals(false, $disp->IsValid());
		}

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

		#endregion

		#region ExtensionDispatch

		public function testExtensionDispatch_setsAsInvalidWithoutCliInitializers() {
			$disp = new N2f\ExtensionDispatch();
			$disp->Initialize('test');

			$this->assertEquals(false, $disp->IsValid());
		}

		public function testExtensionDispatch_setsUpAddCorrectly() {
			$ch = new N2f\ConsoleHelper(4, array('n2f-cli', 'ext', 'add', 'ExtName'), true);

			$disp = new N2f\ExtensionDispatch();
			$disp->Initialize(array('ConsoleHelper' => $ch));

			$this->assertEquals(true, $disp->IsValid());
			$this->assertEquals('ExtName', $disp->GetExtension());
			$this->assertEquals('add', $disp->GetAction());
		}

		public function testExtensionDispatch_setsUpRemoveCorrectly() {
			$ch = new N2f\ConsoleHelper(4, array('n2f-cli', 'ext', 'remove', 'ExtName'), true);

			$disp = new N2f\ExtensionDispatch();
			$disp->Initialize(array('ConsoleHelper' => $ch));

			$this->assertEquals(true, $disp->IsValid());
			$this->assertEquals('ExtName', $disp->GetExtension());
			$this->assertEquals('remove', $disp->GetAction());
		}

		public function testExtensionDispatch_failsNonAddRemoveSetup() {
			$ch = new N2f\ConsoleHelper(4, array('n2f-cli', 'ext', 'test', 'ExtName'), true);

			$disp = new N2f\ExtensionDispatch();
			$disp->Initialize(array('ConsoleHelper' => $ch));

			$this->assertEquals(false, $disp->IsValid());
		}

		#endregion

		#region GenerateDispatch

		public function testGenerateDispatch_setsAsInvalidWithoutCliInitializers() {
			$disp = new N2f\GenerateDispatch();
			$disp->Initialize('test');

			$this->assertEquals(false, $disp->IsValid());
		}

		public function testGenerateDispatch_setsTypeCorrectly() {
			$ch = new N2f\ConsoleHelper(3, array('n2f-cli', 'generate', '-type', 'extension'));

			$disp = new N2f\GenerateDispatch();
			$disp->Initialize(array('ConsoleHelper' => $ch));

			$this->assertEquals(true, $disp->IsValid());
			$this->assertEquals('extension', $disp->GetEntityType());
		}

		public function testGenerateDispatch_setsFileHelperCorrectly() {
			$ch = new N2f\ConsoleHelper(3, array('n2f-cli', 'generate', '-type', 'extension'));
			$fh = new N2f\FileHelper('..\my_rel_dir');

			$disp = new N2f\GenerateDispatch();
			$disp->Initialize(array('ConsoleHelper' => $ch, 'FileHelper' => $fh));

			$this->assertEquals('..\my_rel_dir', $disp->GetFileHelper()->GetRelDir());
		}

		#endregion

		#region JsonRawDispatch

		public function testJsonRawDispatch_setsAsInvalidWithNoInput() {
			$disp = new N2f\JsonRawDispatch();
			$disp->Initialize(null);

			$this->assertEquals(false, $disp->IsValid());
		}

		public function testJsonRawDispatch_setsAsValidWithSimpleJson() {
			$disp = new N2f\JsonRawDispatch();
			$disp->Initialize('5');

			$this->assertEquals(true, $disp->IsValid());
			$this->assertEquals('5', $disp->GetRaw());
			$this->assertEquals('5', $disp->GetDecoded());
			$this->assertEquals('5', $disp->GetDecodedAssoc());
		}

		public function testJsonRawDispatch_setsAsValidWithSimpleArrayJson() {
			$disp = new N2f\JsonRawDispatch();
			$disp->Initialize(array('Json' => '5'));

			$this->assertEquals(true, $disp->IsValid());
			$this->assertEquals('5', $disp->GetRaw());
			$this->assertEquals('5', $disp->GetDecoded());
			$this->assertEquals('5', $disp->GetDecodedAssoc());
		}

		public function testJsonRawDispatch_setsSameJsonHelperInstance() {
			$disp = new N2f\JsonRawDispatch();
			$disp->Initialize(array('Json' => '5', 'JsonHelper' => new MockJsonHelper()));

			$this->assertEquals(true, $disp->GetJsonHelper() instanceof MockJsonHelper);
		}

		#endregion
	}
