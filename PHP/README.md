## N2F 2.0 - PHP
Can be run without any special extensions.  For commandline invocation, simply call:

	n2f-cli <arguments>

For tests, enter top PHP directory and (after ensuring Composer packages have been installed) call:

	php N2f/ThirdParty/phpunit/phpunit/phpunit --bootstrap Tests/TestSetup.php Tests
	// OR
	n2f-test