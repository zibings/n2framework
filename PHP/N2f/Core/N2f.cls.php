<?php

	namespace N2f;

	/**
	 * Main class for the N2 Framework.
	 * 
	 * The main class for N2F, handles loading of extensions and manages
	 * several event chains that define the base system.
	 * 
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class N2f {
		protected $_Extensions = array();
		/**
		 * Summary of $_GenerationChain
		 * 
		 * @var \N2f\ChainHelper
		 */
		protected $_GenerationChain;
		/**
		 * Summary of $_ExtensionChain
		 * 
		 * @var \N2f\ChainHelper
		 */
		protected $_ExtensionChain;
		/**
		 * Summary of $_ShutdownChain
		 * 
		 * @var \N2f\ChainHelper
		 */
		protected $_ShutdownChain;
		/**
		 * Summary of $_ExecuteChain
		 * 
		 * @var \N2f\ChainHelper
		 */
		protected $_ExecuteChain;
		/**
		 * Summary of $_ConfigChain
		 * 
		 * @var \N2f\ChainHelper
		 */
		protected $_ConfigChain;
		/**
		 * Summary of $_Config
		 * 
		 * @var \N2f\Config
		 */
		protected $_Config;
		/**
		 * Summary of $_Log
		 * 
		 * @var \N2f\Logger
		 */
		protected $_Log;
		/**
		 * Summary of $_Ch
		 * 
		 * @var \N2f\ConsoleHelper
		 */
		protected $_Ch;
		/**
		 * Summary of $_Fh
		 * 
		 * @var \N2f\FileHelper
		 */
		protected $_Fh;
		/**
		 * Summary of $_Jh
		 * 
		 * @var \N2f\JsonHelper
		 */
		protected $_Jh;
		/**
		 * Summary of $_Rh
		 * 
		 * @var \N2f\RequestHelper
		 */
		protected $_Rh;

		/**
		 * Singleton instance of N2f class.
		 * 
		 * @var \N2f\N2f
		 */
		protected static $_Instance = null;

		/**
		 * Retrieves a reference to the current singleton, creates
		 * a new instance with null values if one hasn't already
		 * been created.
		 * 
		 * @return \N2f\N2f
		 */
		public static function &getInstance() {
			if (N2f::$_Instance === null) {
				N2f::setInstance();
			}

			$Instance = N2f::$_Instance;

			return $Instance;
		}

		/**
		 * Recreates the singleton instance.
		 * 
		 * @param mixed $Config Optional array of configuration options, including dependencies.
		 * 
		 * @return \N2f\N2f
		 */
		public static function &setInstance($Config = null) {
			N2f::$_Instance = new N2f($Config);
			$Instance = N2f::$_Instance;

			return $Instance;
		}

		/**
		 * Creates a one-off instance for use outside the singleton.
		 * 
		 * @param mixed $Config Optional array of configuration options, including dependencies.
		 * 
		 * @return \N2f\N2f
		 */
		public static function &createInstance($Config = null) {
			$ret = new N2f($Config);

			return $ret;
		}

		/**
		 * Creates a new N2f instance.
		 * 
		 * @param array $Config Optional configuration value in the form of an array.
		 * @return void
		 */
		protected function __construct($Config = null) {
			if ($Config !== null && is_array($Config) && count($Config) > 0) {
				if (array_key_exists('Config', $Config)) {
					$this->SetConfig($Config['Config']);
				} else {
					$this->_Config = new Config();
				}

				$this->_GenerationChain = (array_key_exists('GenerationChain', $Config) && $Config['GenerationChain'] instanceof ChainHelper) ? $Config['GenerationChain'] : new ChainHelper();
				$this->_ExtensionChain = (array_key_exists('ExtensionChain', $Config) && $Config['ExtensionChain'] instanceof ChainHelper) ? $Config['ExtensionChain'] : new ChainHelper();
				$this->_ShutdownChain = (array_key_exists('ShutdownChain', $Config) && $Config['ShutdownChain'] instanceof ChainHelper) ? $Config['ShutdownChain'] : new ChainHelper();
				$this->_ExecuteChain = (array_key_exists('ExecuteChain', $Config) && $Config['ExecuteChain'] instanceof ChainHelper) ? $Config['ExecuteChain'] : new ChainHelper();
				$this->_ConfigChain = (array_key_exists('ConfigChain', $Config) && $Config['ConfigChain'] instanceof ChainHelper) ? $Config['ConfigChain'] : new ChainHelper();
				$this->_Log = (array_key_exists('Logger', $Config) && $Config['Logger'] instanceof Logger) ? $Config['Logger'] : new Logger($this->_Config->Logger);
				$this->_Ch = (array_key_exists('ConsoleHelper', $Config) && $Config['ConsoleHelper'] instanceof ConsoleHelper) ? $Config['ConsoleHelper'] : new ConsoleHelper();
				$this->_Fh = (array_key_exists('FileHelper', $Config) && $Config['FileHelper'] instanceof FileHelper) ? $Config['FileHelper'] : new FileHelper(N2F_REL_DIR);
				$this->_Jh = (array_key_exists('JsonHelper', $Config) && $Config['JsonHelper'] instanceof JsonHelper) ? $Config['JsonHelper'] : new JsonHelper();
				$this->_Rh = (array_key_exists('RequestHelper', $Config) && $Config['RequestHelper'] instanceof RequestHelper) ? $Config['RequestHelper'] : new RequestHelper();
			} else {
				$this->_Config = new Config();
				$this->_GenerationChain = new ChainHelper();
				$this->_ExtensionChain = new ChainHelper();
				$this->_ShutdownChain = new ChainHelper();
				$this->_ExecuteChain = new ChainHelper();
				$this->_ConfigChain = new ChainHelper();
				$this->_Log = new Logger();
				$this->_Ch = new ConsoleHelper();
				$this->_Fh = new FileHelper(N2F_REL_DIR);
				$this->_Jh = new JsonHelper();
				$this->_Rh = new RequestHelper();
			}

			// initialize nodes for base operation, etc
			$this->_ExtensionChain->LinkNode(new ExtensionConfig);
			$this->_GenerationChain->LinkNode(new CoreGenerate);

			register_shutdown_function(array($this, 'PerformShutdown'));

			return;
		}

		/**
		 * Method to show results from dipatches being processed.
		 * 
		 * @param \N2f\DispatchBase $Dispatch DispatchBase to pull results out of.
		 * @return void
		 */
		protected function DisplayDispatchResults(DispatchBase &$Dispatch) {
			if ($Dispatch->IsValid()) {
				if ($Dispatch->NumResults() > 0) {
					foreach ($Dispatch->GetResults() as $Res) {
						$this->_Ch->PutLine($Res);
					}
				}
			} else {
				$this->_Ch->PutLine("Invalid dispatch provided for internal chain.");
			}

			return;
		}

		/**
		 * Returns the current Config object for the instance.
		 * 
		 * @return \N2f\Config
		 */
		public function GetConfig() {
			return clone $this->_Config;
		}

		/**
		 * Returns the current ConsoleHelper object for the instance.
		 * 
		 * @return \N2f\ConsoleHelper
		 */
		public function GetConsoleHelper() {
			return $this->_Ch;
		}

		/**
		 * Returns the current stack of loaded extensions.
		 * 
		 * @return array
		 */
		public function GetExtensions() {
			return $this->_Extensions;
		}

		/**
		 * Returns the current FileHelper object for the instance.
		 * 
		 * @return \N2f\FileHelper
		 */
		public function GetFileHelper() {
			return $this->_Fh;
		}

		/**
		 * Returns the current JsonHelper object for the instance.
		 * 
		 * @return \N2f\JsonHelper
		 */
		public function GetJsonHelper() {
			return $this->_Jh;
		}

		/**
		 * Returns the current Logger object for the instance.
		 * 
		 * @return \N2f\Logger
		 */
		public function GetLogger() {
			return $this->_Log;
		}

		/**
		 * Returns the current RequestHelper object for the instance.
		 * 
		 * @return \N2f\RequestHelper
		 */
		public function GetRequestHelper() {
			return $this->_Rh;
		}

		/**
		 * Method to create an instance of a receiver class.
		 * 
		 * @param string $Name Name of class to instantiate.
		 * @return void
		 */
		protected function CallReceiverClass($Name) {
			if (!class_exists($Name)) {
				return;
			}

			try {
				$RefCls = new \ReflectionClass($Name);

				if ($RefCls->isSubclassOf('N2f\ExtensionBase')) {
					$Cls = $RefCls->newInstanceArgs();
					$Cls->Initialize($this);
				}
			} catch (\ReflectionException $Exception) {
				$this->_Log->Debug("Failed to call receiver class for '{$Name}' extension. [" . $Exception->getMessage() . "]");
			}

			return;
		}

		/**
		 * Attempts to load an extension into the system.
		 * 
		 * @param string $Name Name of the extension to attempt loading.
		 * @return \N2f\ReturnHelper
		 */
		public function LoadExtension($Name) {
			$Ret = new ReturnHelper();

			// TODO: Better validation of path name
			if (empty($Name)) {
				$Ret->SetMessage("Extension name invalid");
			} else if (array_key_exists($Name, $this->_Extensions)) {
				$Ret->SetMessage("Extension '{$Name}' already loaded, no changes made.");
				$Ret->SetGud();
			} else {
				if ($this->_Fh->FolderExists("~N2f/Extensions/{$Name}") && $this->_Fh->FileExists("~N2f/Extensions/{$Name}/{$Name}.cfg")) {
					$Cfg = $this->_Jh->DecodeAssoc($this->_Fh->GetContents("~N2f/Extensions/{$Name}/{$Name}.cfg"));

					if ($Cfg == null || !array_key_exists('name', $Cfg) || !array_key_exists('author', $Cfg) || !array_key_exists('version', $Cfg)) {
						$Ret->SetMessage("Extension '{$Name}' configuration was not complete, must have at least 'name', 'author', and 'version' set.");
					} else {
						$Ret->SetGud();
						$tmp = new Extension($Cfg['name'], $Cfg['author'], $Cfg['version']);

						if (array_key_exists('require', $Cfg) && is_array($Cfg['require']) && count($Cfg['require']) > 0) {
							// check dependencies
							// semver examples: https://github.com/vierbergenlars/php-semver/tree/master

							// 1 - check for extensions with the required name
							// 2 - check for thirdparty things with the required name
							// 3 - check composer?
						}

						if ($Ret->IsGud()) {
							if (array_key_exists('base_file', $Cfg) && $this->_Fh->FileExists("~N2f/Extensions/{$Name}/{$Cfg['base_file']}.ext.php")) {
								$this->_Fh->Load("~N2f/Extensions/{$Name}/{$Cfg['base_file']}.ext.php");

								$tmp->SetBaseFile($Cfg['base_file']);
							} else if (array_key_exists('auto_includes', $Cfg)) {
								if (is_array($Cfg['auto_includes']) && count($Cfg['auto_load']) > 0) {
									$SomethingLoaded = false;
									$Loaded = array();

									foreach (array_values($Cfg['auto_includes']) as $File) {
										if ($this->_Fh->FileExists("~N2f/Extensions/{$Name}/{$File}.ext.php")) {
											$this->_Fh->Load("~N2f/Extensions/{$Name}/{$File}.ext.php");

											$SomethingLoaded = true;
											$Loaded[] = $File;
										}
									}

									if (!$SomethingLoaded) {
										$Ret->SetBad();
										$Ret->SetMessage("Unable to load any of the auto_load files for the extension: {$Name}");
									} else {
										$tmp->SetAutoIncludes($Loaded);
									}
								} else if ($this->_Fh->FileExists("~N2f/Extensions/{$Name}/{$Cfg['auto_includes']}.ext.php")) {
									$this->_Fh->Load("~N2f/Extensions/{$Name}/{$Cfg['auto_load']}.ext.php");
								} else {
									$Ret->SetBad();
									$Ret->SetMessage("Invalid auto_load directive for the extension: {$Name}");
								}
							} else if ($this->_Fh->FileExists("~N2f/Extensions/{$Name}/{$Name}.ext.php")) {
								$this->_Fh->Load("~N2f/Extensions/{$Name}/{$Name}.ext.php");
							} else {
								$Ret->SetBad();
								$Ret->SetMessage("Couldn't find a valid entry file for the extension: {$Name}");
							}
						}

						if ($Ret->IsGud()) {
							$this->CallReceiverClass($Name);
							$this->_Extensions[$Name] = $tmp;
						} else {
							$Ret->SetMessage("Extension '{$Name}' wasn't loaded.");
						}
					}
				} else {
					$Ret->SetMessage("Extension '{$Name}' folder or configuration file were not found.");
				}
			}

			return $Ret;
		}

		/**
		 * Attempts to load a group of extensions into the system.
		 * 
		 * @param array $List Group of extensions to attempt loading.
		 * @return \N2f\ReturnHelper
		 */
		public function LoadExtensions(array $List) {
			$Ret = new ReturnHelper();

			if (count($List) > 0) {
				foreach (array_values($List) as $Ext) {
					$Tmp = $this->LoadExtension($Ext);

					if ($Tmp->IsBad()) {
						$Ret->IsBad();

						if ($Tmp->HasMessages()) {
							$Ret->SetMessages($Tmp->GetMessages());
						} else {
							$Ret->SetMessage("Failed to load extension: {$Ext}");
						}
					} else {
						$Ret->SetMessage("Loaded extension: {$Ext}");
					}
				}
			}

			return $Ret;
		}

		/**
		 * Links a node into the Configure chain for the system.
		 * 
		 * @param \N2f\NodeBase $Node Node to add to Configure chain.
		 * @return \N2f\N2f
		 */
		public function LinkConfigNode(NodeBase $Node) {
			$this->_ConfigChain->LinkNode($Node);

			return $this;
		}

		/**
		 * Links a node into the Execute chain for the system.
		 * 
		 * @param \N2f\NodeBase $Node Node to add to Execute chain.
		 * @return \N2f\N2f
		 */
		public function LinkExecuteNode(NodeBase $Node) {
			$this->_ExecuteChain->LinkNode($Node);

			return $this;
		}

		/**
		 * Links a node into the Generation chain for the system.
		 * 
		 * @param \N2f\NodeBase $Node Node to add to Generation chain.
		 * @return \N2f\N2f
		 */
		public function LinkGenerationNode(NodeBase $Node) {
			$this->_GenerationChain->LinkNode($Node);

			return $this;
		}

		/**
		 * Links a node into the Shutdown chain for the system.
		 * 
		 * @param \N2f\NodeBase $Node Node to add to Shutdown chain.
		 * @return \N2f\N2f
		 */
		public function LinkShutdownNode(NodeBase $Node) {
			$this->_ShutdownChain->LinkNode($Node);

			return $this;
		}

		/**
		 * Triggers the traversal of the ConfigChain.
		 * 
		 * @return void
		 */
		protected function PerformConfigure() {
			$Disp = new ConfigDispatch();
			$Disp->Initialize(array('relDir' => $this->_Fh->GetRelDir(), 'ConsoleHelper' => $this->_Ch));

			$this->_ConfigChain->Traverse($Disp, $this);
			$this->DisplayDispatchResults($Disp);

			return;
		}

		/**
		 * Triggers the traversal of the ExecuteChain.
		 * 
		 * @return void
		 */
		protected function PerformExecute() {
			$Disp = null;

			if ($this->_Ch->IsCLI()) {
				$Disp = new CliDispatch();
				$Disp->Initialize(array('relDir' => $this->_Fh->GetRelDir(), 'ConsoleHelper' => $this->_Ch));
			} else if ($this->_Rh->IsJson()) {
				$Disp = new JsonDispatch();
				$Disp->Initialize($this->_Rh);
			} else {
				$Disp = new WebDispatch();
				$Disp->Initialize($this->_Rh);
			}

			$this->_ExecuteChain->Traverse($Disp, $this);

			if ($this->_Ch->IsCLI()) {
				$this->DisplayDispatchResults($Disp);
			}

			return;
		}
		
		/**
		 * Triggers the traversal of the ExtensionChain.
		 * 
		 * @return void
		 */
		protected function PerformExtension() {
			$Disp = new ExtensionDispatch();
			$Disp->Initialize(array('ConsoleHelper' => $this->_Ch, 'FileHelper' => $this->_Fh));

			$this->_ExtensionChain->Traverse($Disp, $this);
			$this->DisplayDispatchResults($Disp);

			return;
		}

		/**
		 * Triggers the traversal of the GenerationChain.
		 * 
		 * @return void
		 */
		protected function PerformGeneration() {
			$Disp = new GenerateDispatch();
			$Disp->Initialize(array('ConsoleHelper' => $this->_Ch, 'FileHelper' => $this->_Fh));

			$this->_GenerationChain->Traverse($Disp, $this);
			$this->DisplayDispatchResults($Disp);

			return;
		}

		/**
		 * Triggers traversal of the ShutdownChain.
		 * 
		 * @return void
		 */
		public function PerformShutdown() {
			$Disp = new ShutdownDispatch();
			$Disp->Initialize(null);
		
			$this->_ShutdownChain->Traverse($Disp, $this);

			return;
		}

		/**
		 * Processes the current request, firing off either the Configure, Generation, or Execute chains as appropriate.
		 * 
		 * @return \N2f\N2f
		 */
		public function Process() {
			if ($this->_Ch->IsCLI()) {
				if ($this->_Ch->CompareArgAt(1, 'config') && $this->_Ch->NumArgs() > 1) {
					$this->PerformConfigure();
				} else if ($this->_Ch->CompareArgAt(1, 'generate') && $this->_Ch->NumArgs() > 1) {
					$this->PerformGeneration();
				} else if ($this->_Ch->CompareArgAt(1, 'extension') && $this->_Ch->NumArgs() >= 4) {
					$this->PerformExtension();
				} else {
					$this->PerformExecute();
				}
			} else {
				$this->PerformExecute();
			}

			return $this;
		}

		/**
		 * Overwrites the current Config object for the instance.
		 * 
		 * @param \N2f\Config $Config Optional Config object to use for the instance (default created if not specified).
		 * @return \N2f\N2f
		 */
		public function SetConfig(Config $Config = null) {
			if ($Config === null) {
				$this->_Config = new Config();
			} else if ($Config instanceof Config) {
				$this->_Config = $Config;
			} else if (is_array($Config) && count($Config) > 0) {
				$this->_Config = new Config($Config);
			} else {
				$this->_Config = new Config();
			}

			return $this;
		}

		/**
		 * Overwrites the current ConsoleHelper object for the instance.
		 * 
		 * @param \N2f\ConsoleHelper $ConsoleHelper ConsoleHelper object to use for the instance.
		 * @return \N2f\N2f
		 */
		public function SetConsoleHelper(ConsoleHelper $ConsoleHelper) {
			$this->_Ch = $ConsoleHelper;

			return $this;
		}

		/**
		 * Overwrites the current FileHelper object for the instance.
		 * 
		 * @param \N2f\FileHelper $FileHelper FileHelper object to use for the instance.
		 * @return \N2f\N2f
		 */
		public function SetFileHelper(FileHelper $FileHelper) {
			$this->_Fh = $FileHelper;

			return $this;
		}

		/**
		 * Overwrites the current JsonHelper object for the instance.
		 * 
		 * @param \N2f\JsonHelper $JsonHelper JsonHelper object to use for the instance.
		 * @return \N2f\N2f
		 */
		public function SetJsonHelper(JsonHelper $JsonHelper) {
			$this->_Jh = $JsonHelper;

			return $this;
		}

		/**
		 * Overwrites the current Logger object for the instance.
		 * 
		 * @param \N2f\Logger $Logger Logger object to use for the instance.
		 * @return \N2f\N2f
		 */
		public function SetLogger(Logger $Logger) {
			$this->_Log = $Logger;

			return $this;
		}

		/**
		 * Overwrites the current RequestHelper object for the instance.
		 * 
		 * @param \N2f\RequestHelper $RequestHelper RequestHelper object to use for the instance.
		 * @return \N2f\N2f
		 */
		public function SetRequestHelper(RequestHelper $RequestHelper) {
			$this->_Rh = $RequestHelper;

			return $this;
		}
	}

?>