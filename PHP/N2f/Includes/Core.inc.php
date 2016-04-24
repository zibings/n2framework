<?php

	if (defined('N2F_REL_DIR')) {
		// Load the return helper
		require_once(N2F_REL_DIR . 'N2f/Core/Helpers/ReturnHelper.cls.php');

		// Load the file helper
		require_once(N2F_REL_DIR . 'N2f/Core/Helpers/FileHelper.cls.php');

		// File helper
		$Fh = new N2f\FileHelper(N2F_REL_DIR, array(
			N2F_REL_DIR . 'N2f/Core/Helpers/ReturnHelper.cls.php',
			N2F_REL_DIR . 'N2f/Core/Helpers/FileHelper.cls.php'
		));

		// Includes
		$Fh->Load("~N2f/Includes/Constants.inc.php");
		$Fh->Load(\N2f\N2fStrings::DirIncludes . "Functions.inc.php");
		$Fh->Load(\N2f\N2fStrings::DirIncludes . "Enums.inc.php");

		// Base classes
		$Fh->Load(\N2f\N2fStrings::DirCoreBaseClasses . "Extension.base.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreBaseClasses . "Dispatch.base.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreBaseClasses . "Node.base.php");

		// Helpers
		$Fh->Load(\N2f\N2fStrings::DirCoreHelpers . "ChainHelper.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreHelpers . "ConsoleHelper.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreHelpers . "JsonHelper.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreHelpers . "RequestHelper.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreHelpers . "VersionHelper.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreHelpers . "ParameterHelper.cls.php");

		// Dispatches
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Cli.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Config.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Extension.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Generate.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "JsonRaw.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "JsonWeb.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Log.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "LogOutput.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Shutdown.dispatch.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreDispatches . "Web.dispatch.php");

		// Nodes
		$Fh->Load(\N2f\N2fStrings::DirCoreNodes . "CoreConfig.node.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreNodes . "CoreGenerate.node.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreNodes . "ExtensionConfig.node.php");
		$Fh->Load(\N2f\N2fStrings::DirCoreNodes . "LoggerProcessor.node.php");

		// Core classes
		$Fh->Load(\N2f\N2fStrings::DirCore . "Config.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCore . "Extension.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCore . "Logger.cls.php");
		$Fh->Load(\N2f\N2fStrings::DirCore . "N2f.cls.php");
	} else {
		die("System must be loaded with a relative directory set (N2F_REL_DIR).");
	}
