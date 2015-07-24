<?php

	if (!defined('N2F_REL_DIR')) {
		define('N2F_REL_DIR', './');
	}

	// Grab all our files
	require_once(N2F_REL_DIR . 'N2f/Includes/Core.inc.php');

	// Create a couple local utilities
	$Fh = new N2f\FileHelper(N2F_REL_DIR);
	$Ch = new N2f\ConsoleHelper((isset($argc)) ? $argc : 0, (isset($argv)) ? $argv : array());

	// This is the one exception to running without config....to set up (or change) config
	if ($Ch->IsCLI() && $Ch->CompareArgAt(1, 'config') && ($Ch->NumArgs() == 2 || !$Ch->HasArg('ext'))) {
		$Ch->PutLine();
		$Ch->PutLine("You are requesting to configure your installation.  Bully for you!");

		$Disp = new N2f\CliDispatch();
		$Disp->Initialize(array('relDir' => $Fh->GetRelDir(), 'ConsoleHelper' => $Ch));

		$Chain = new N2f\ChainHelper(true);
		$Chain->LinkNode(new N2f\CoreConfig);
		$Chain->Traverse($Disp);

		$Ch->PutLine();
		exit;
	}

	// If we're here, we're not setting up config, so we better have it
	if (!$Fh->FileExists(\N2f\N2fStrings::DirIncludes . 'N2f.cfg')) {
		die("\nNo configuration file present, please make sure you create one or run automatic setup.\n\n");
	}

	$Jh = new N2f\JsonHelper();
	$Cfg = $Jh->DecodeAssoc($Fh->GetContents(\N2f\N2fStrings::DirIncludes . "N2f.cfg"));

	/* Example with all dependencies provided:
	 * 
	 * $N2f = N2f\N2f::setInstance(array(
	 *   'Config' => new Config(),
	 *   'GenerationChain' => new ChainHelper(),
	 *   'ShutdownChain' => new ChainHelper(),
	 *   'ExecuteChain' => new ChainHelper(),
	 *   'ConfigChain' => new ChainHelper(),
	 *   'Logger' => new Logger(),
	 *   'ConsoleHelper' => new ConsoleHelper(),
	 *   'FileHelper' => new FileHelper(),
	 *   'JsonHelper' => new JsonHelper(),
	 *   'RequestHelper' => new RequestHelper()
	 * ));
	 */
	$N2f = N2f\N2f::setInstance(array(
		'Config' => new N2f\Config($Cfg),
		'ConsoleHelper' => $Ch,
		'FileHelper' => $Fh,
		'JsonHelper' => $Jh
	));

	$Cfg = $N2f->GetConfig();

	if (count($Cfg->Extensions) > 0) {
		$Ret = $N2f->LoadExtensions($Cfg->Extensions);

		if ($Ret->HasMessages()) {
			$Logger = $N2f->GetLogger();

			foreach (array_values($Ret->GetMessages()) as $Msg) {
				$Logger->Debug($Msg);
			}
		}
	}

	$N2f->Process();

?>