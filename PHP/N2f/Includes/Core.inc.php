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
		$Fh->Load("~N2f/Includes/Functions.inc.php");
		$Fh->Load("~N2f/Includes/Enums.inc.php");

		// Base classes
		$Fh->Load("~N2f/Core/BaseClasses/Extension.base.php");
		$Fh->Load("~N2f/Core/BaseClasses/Dispatch.base.php");
		$Fh->Load("~N2f/Core/BaseClasses/Node.base.php");

		// Helpers
		$Fh->Load("~N2f/Core/Helpers/ChainHelper.cls.php");
		$Fh->Load("~N2f/Core/Helpers/ConsoleHelper.cls.php");
		$Fh->Load("~N2f/Core/Helpers/JsonHelper.cls.php");
		$Fh->Load("~N2f/Core/Helpers/RequestHelper.cls.php");
		$Fh->Load("~N2f/Core/Helpers/VersionHelper.cls.php");
		$Fh->Load("~N2f/Core/Helpers/ParameterHelper.cls.php");

		// Dispatches
		$Fh->Load("~N2f/Core/Dispatches/Cli.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Config.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Extension.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Generate.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Json.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Log.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/LogOutput.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Shutdown.dispatch.php");
		$Fh->Load("~N2f/Core/Dispatches/Web.dispatch.php");

		// Nodes
		$Fh->Load("~N2f/Core/Nodes/CoreConfig.node.php");
		$Fh->Load("~N2f/Core/Nodes/CoreGenerate.node.php");
		$Fh->Load("~N2f/Core/Nodes/ExtensionConfig.node.php");
		$Fh->Load("~N2f/Core/Nodes/LoggerProcessor.node.php");

		// Core classes
		$Fh->Load("~N2f/Core/Config.cls.php");
		$Fh->Load("~N2f/Core/Extension.cls.php");
		$Fh->Load("~N2f/Core/Logger.cls.php");
		$Fh->Load("~N2f/Core/N2f.cls.php");
	} else {
		die("System must be loaded with a relative directory set (N2F_REL_DIR).");
	}

?>