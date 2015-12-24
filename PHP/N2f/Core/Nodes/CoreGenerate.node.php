<?php

	namespace N2f;

	/**
	 * Node for generating some types of files.
	 *
	 * Node that generates certain types of files
	 * based on the CLI arguments provided.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class CoreGenerate extends NodeBase {
		/**
		 * Creates a new CoreGenerate instance.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->SetKey('N2f-CoreGenerate')->SetVersion('1.0');

			return;
		}

		/**
		 * Processes a GenerateDispatch from a sender.
		 * 
		 * @param mixed $Sender Sender value.
		 * @param \N2f\DispatchBase $Dispatch The DispatchBase instance for the chain, must be a GenerateDispatch.
		 * @return void
		 */
		public function Process($Sender, DispatchBase &$Dispatch) {
			if (!($Dispatch instanceof GenerateDispatch)) {
				return;
			}

			/** @var GenerateDispatch $Dispatch */

			$Jh = new JsonHelper();
			$Fh = $Dispatch->GetFileHelper();
			$EntityType = $Dispatch->GetEntityType();
			$Params = $Dispatch->GetAssocParameters();

			if (stripos($EntityType, 'n2f:') === false || !array_key_exists('name', $Params)) {
				return;
			}

			$EntityType = substr($EntityType, 4);

			if ($EntityType != 'ext' && $EntityType != 'extension') {
				return;
			}

			$Cfg = new Config($Jh->DecodeAssoc($Fh->GetContents(N2fStrings::DirIncludes . "N2f.cfg")));

			$ext = $Params['name'];
			$Dispatch->Consume();

			if ($Fh->FolderExists($Cfg->ExtensionDirectory . "{$ext}")) {
				$Dispatch->SetResult("Extension folder already exists, could not generate extension.");

				return;
			}

			$config = array(
				'name' => '',
				'author' => '',
				'version' => ''
			);

			$Ch = $Dispatch->GetConsoleHelper();

			$Ch->PutLine();
			$Ch->PutLine("Answer the following questions to configure your new extension.");
			$Ch->PutLine();

			$ExtName = $Ch->GetQueriedInput(
				"Extension Name",
				$Params['name'],
				"Bad name or extension already exists, please try another name.",
				5,
				function ($Value) use ($Fh, $Cfg) { return !empty($Value) && !$Fh->FolderExists($Cfg->ExtensionDirectory . $Value); },
				array($this, 'EscapePhpString')
			);

			if ($ExtName->IsGud()) {
				$config['name'] = $ExtName->GetResults();
			} else {
				foreach (array_values($ExtName->GetMessages()) as $Msg) {
					$Ch->PutLine($Msg);
				}

				return;
			}

			$ExtAuthor = $Ch->GetQueriedInput(
				"Extension Author",
				null,
				"Invalid author name.",
				5,
				null,
				array($this, 'EscapePhpString')
			);

			if ($ExtAuthor->IsGud()) {
				$config['author'] = $ExtAuthor->GetResults();
			} else {
				foreach (array_values($ExtAuthor->GetMessages()) as $Msg) {
					$Ch->PutLine($Msg);
				}

				return;
			}

			$ExtVersion = $Ch->GetQueriedInput(
				"Extension Version",
				null,
				"Invalid version number.",
				5,
				null,
				array($this, 'EscapePhpString')
			);

			if ($ExtVersion->IsGud()) {
				$config['version'] = $ExtVersion->GetResults();
			} else {
				foreach (array_values($ExtVersion->GetMessages()) as $Msg) {
					$Ch->PutLine($Msg);
				}

				return;
			}

			$Jh = new JsonHelper();
			$Ch->PutLine();

			if ($Fh->MakeFolder($Cfg->ExtensionDirectory . "{$ext}")) {
				$Fh->PutContents($Cfg->ExtensionDirectory . "{$ext}/{$ext}.cfg", $Jh->EncodePretty($config));

				$ExtFile = str_replace('%EXTENSION%', $ext, $Fh->GetContents(N2fStrings::DirCoreTemplates . "Extension/Extension.ext.php"));
				$Fh->PutContents($Cfg->ExtensionDirectory . "{$ext}/{$ext}.ext.php", $ExtFile);
				$Fh->PutContents($Cfg->ExtensionDirectory . "{$ext}/index.php", "<?" . "php ?" . ">");

				$Ch->PutLine("Successfully generated base files for {$ext} extension.");

				if ($Ch->HasArg('enable')) {
					$Cfg = $Jh->DecodeAssoc($Fh->GetContents(N2fStrings::DirIncludes . "N2f.cfg"));

					if (isset($Cfg['extensions'])) {
						$found = false;

						foreach (array_values($Cfg['extensions']) as $e) {
							if ($e == $ext) {
								$found = true;

								break;
							}
						}

						if (!$found) {
							$Cfg['extensions'][] = $ext;
						}
					} else {
						$Cfg['extensions'] = array($ext);
					}

					$Fh->PutContents(N2fStrings::DirIncludes . "N2f.cfg", $Jh->EncodePretty($Cfg));
					$Ch->PutLine("Extension {$ext} enabled in N2f.cfg.");
				}
			} else {
				$Dispatch->SetResult("Failed to create extension directory, check filesystem permissions.");
			}

			return;
		}

		/**
		 * Escapes a string for use in a PHP variable.
		 * 
		 * @param string $Str String value to escape quotes within.
		 * @return string Escaped string.
		 */
		public function EscapePhpString($Str) {
			return str_replace(array('"', "'"), array('\"', "\'"), $Str);
		}
	}
