<?php

	namespace N2f;

	/**
	 * Abstract for extension 'base' classes.
	 *
	 * Abstract which defines the Initialize() method used
	 * by N2f to initialize extensions with injection.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	abstract class ExtensionBase {
		/**
		 * Method to receive initialization pulse from
		 * the N2f instance.
		 * 
		 * @param \N2f\N2f $N2f The N2f instance initializing the extension.
		 */
		abstract public function Initialize(N2f &$N2f);
	}
