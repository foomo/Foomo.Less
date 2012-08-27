<?php

/*
 * This file is part of the foomo Opensource Framework.
 *
 * The foomo Opensource Framework is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public License as
 * published  by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The foomo Opensource Framework is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * the foomo Opensource Framework. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Foomo\CliCall;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 * @author franklin <franklin@weareinteractive.com>
 */
class Recess extends \Foomo\CliCall
{
	//---------------------------------------------------------------------------------------------
	// ~ Constructor
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $source
	 */
	public function __construct()
	{
		parent::__construct('recess');
	}

	//---------------------------------------------------------------------------------------------
	// ~ Public methods
	//---------------------------------------------------------------------------------------------

	/**
	 * Compiles your code and outputs it to the terminal.
	 * Fixes white space and sort order.
	 * Can compile css or less.
	 *
	 * @param boolean $compile
	 * @return \Foomo\CliCall\Recess
	 */
	public function compile($compile=true)
	{
		return $this->addArguments(array('--compile', $compile));
	}

	/**
	 * Compress your compiled code.
	 *
	 * @param boolean $compress
	 * @return \Foomo\CliCall\Recess
	 */
	public function compress($compress=true)
	{
		return $this->addArguments(array('--compress', $compress));
	}

	/**
	 * Accepts a path, which specifies a json config object
	 *
	 * @param string $config
	 * @return \Foomo\CliCall\Recess
	 */
	public function config($config)
	{
		return $this->addArguments(array('--config', $config));
	}

	/**
	 * Removes color from output (useful when logging)
	 *
	 * @param boolean $stripColors
	 * @return \Foomo\CliCall\Recess
	 */
	public function stripColors($stripColors=true)
	{
		return $this->addArguments(array('--stripColors', $stripColors));
	}

	/**
	 * doesn't complain about using IDs in your stylesheets
	 *
	 * @param boolean $noIDs
	 * @return \Foomo\CliCall\Recess
	 */
	public function noIDs($noIDs=true)
	{
		return $this->addArguments(array('--noIDs', $noIDs));
	}

	/**
	 * Doesn't complain about styling .js- prefixed classnames
	 *
	 * @param boolean $noJSPrefix
	 * @return \Foomo\CliCall\Recess
	 */
	public function noJSPrefix($noJSPrefix=true)
	{
		return $this->addArguments(array('--noJSPrefix', $noJSPrefix));
	}

	/**
	 * Doesn't complain about overqualified selectors (ie: div#foo.bar)
	 *
	 * @param boolean $noOverqualifying
	 * @return \Foomo\CliCall\Recess
	 */
	public function noOverqualifying($noOverqualifying=true)
	{
		return $this->addArguments(array('--noOverqualifying', $noOverqualifying));
	}

	/**
	 * Doesn't complain about using underscores in your class names
	 *
	 * @param boolean $noUnderscores
	 * @return \Foomo\CliCall\Recess
	 */
	public function noUnderscores($noUnderscores=true)
	{
		return $this->addArguments(array('--noUnderscores', $noUnderscores));
	}

	/**
	 * Doesn't complain about using the universal * selector
	 *
	 * @param boolean $noUniversalSelectors
	 * @return \Foomo\CliCall\Recess
	 */
	public function noUniversalSelectors($noUniversalSelectors=true)
	{
		return $this->addArguments(array('--noUniversalSelectors', $noUniversalSelectors));
	}

	/**
	 * Adds whitespace prefix to line up vender prefixed properties
	 *
	 * @param boolean $prefixWhitespace
	 * @return \Foomo\CliCall\Recess
	 */
	public function prefixWhitespace($prefixWhitespace=true)
	{
		return $this->addArguments(array('--prefixWhitespace', $prefixWhitespace));
	}

	/**
	 * Doesn't looking into your property ordering
	 *
	 * @param boolean $strictPropertyOrder
	 * @return \Foomo\CliCall\Recess
	 */
	public function strictPropertyOrder($strictPropertyOrder=true)
	{
		return $this->addArguments(array('--strictPropertyOrder', $strictPropertyOrder));
	}

	/**
	 * Doesn't complain if you add units to values of 0
	 *
	 * @param boolean $zeroUnits
	 * @return \Foomo\CliCall\Recess
	 */
	public function zeroUnits($zeroUnits=true)
	{
		return $this->addArguments(array('--zeroUnits', $zeroUnits));
	}

	//---------------------------------------------------------------------------------------------
	// ~ Overriden methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @param array $arguments
	 * @return \Foomo\CliCall\Recess
	 */
	public function addArguments(array $arguments)
	{
		return parent::addArguments($arguments);
	}

	//---------------------------------------------------------------------------------------------
	// ~ Overriden static methods
	//---------------------------------------------------------------------------------------------

	/**
	 * create a call
	 *
	 * @param string $source
	 * @return \Foomo\CliCall\Recess
	 */
	public static function create()
	{
		return new self();
	}
}