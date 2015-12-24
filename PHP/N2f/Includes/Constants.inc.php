<?php

	namespace N2f;

	// Our version
	define('N2F_VERSION',                   "2.0.0");

	// Log levels
	define('N2F_LOG_NONE',                  0);
	define('N2F_LOG_DEBUG',                 1);
	define('N2F_LOG_INFO',                  2);
	define('N2F_LOG_NOTICE',                4);
	define('N2F_LOG_WARNING',               8);
	define('N2F_LOG_ERROR',                 16);
	define('N2F_LOG_CRITICAL',              32);
	define('N2F_LOG_ALERT',                 64);
	define('N2F_LOG_EMERGENCY',             128);
	define('N2F_LOG_ALL',                   255);

	class N2fStrings {
		// Configuration default strings
		const CfgTimezoneDefault = "America/New_York";
		const CfgLocaleDefault = "en-US";
		const CfgCharsetDefault = "utf-8";
		const CfgExtensionDirDefault = "~N2f/Extensions/";
		const CfgLogLevelDefault = "N2F_LOG_ERROR";

		// System folders
		const DirCoreBaseClasses = "~N2f/Core/BaseClasses/";
		const DirCoreDispatches = "~N2f/Core/Dispatches/";
		const DirCoreHelpers = "~N2f/Core/Helpers/";
		const DirCoreNodes = "~N2f/Core/Nodes/";
		const DirCoreTemplates = "~N2f/Core/Templates/";
		const DirCore = "~N2f/Core/";
		const DirIncludes = "~N2f/Includes/";
	}
