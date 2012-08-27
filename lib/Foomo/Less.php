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

namespace Foomo;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 * @author Jan Halfar <jan@bestbytes.com>
 * @author franklin <franklin@weareinteractive.com>
 */
class Less
{
	//---------------------------------------------------------------------------------------------
	// ~ Variables
	//---------------------------------------------------------------------------------------------

	/**
	 * @var string
	 */
	private $module;
	/**
	 * @var string
	 */
	private $source;
	/**
	 * @var float
	 */
	private $version;
	/**
	 * @var boolean
	 */
	private $watch = false;
	/**
	 * @var boolean
	 */
	private $uglify = true;

	//---------------------------------------------------------------------------------------------
	// ~ Constructor
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $module
	 * @param string $source name of the less file
	 * @param float $version update this, if you want a safe deployment
	 */
	public function __construct($module, $source, $version)
	{
		$this->module = $module;
		$this->source = (substr($source, -5) != '.less') ? $source . '.less' : $source;
		$this->version = $version;
		if (!\file_exists($this->getSourceFilename())) \trigger_error ('Source does not exist: ' . $this->getSourceFilename (), \E_USER_ERROR);
	}

	//---------------------------------------------------------------------------------------------
	// ~ Public methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function getModule()
	{
		return $this->module;
	}

	/**
	 * @return string
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @return boolean
	 */
	public function getWatch()
	{
		return $this->watch;
	}

	/**
	 * @return boolean
	 */
	public function getUglify()
	{
		return $this->uglify;
	}

	/**
	 * @return string
	 */
	public function getOutputPath()
	{
		return \Foomo\Less\Module::getHtdocsVarPath() . DIRECTORY_SEPARATOR . $this->getOutputBasename();
	}

	/**
	 * @return string
	 */
	public function getSourceFilename()
	{
		return \Foomo\Config::getModuleDir($this->module) . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR . $this->source;
	}

	/**
	 * @return string
	 */
	public function getOutputFilename()
	{
		return \Foomo\Less\Module::getHtdocsVarDir() . DIRECTORY_SEPARATOR . $this->getOutputBasename();
	}

	/**
	 * @return string
	 */
	public function getSourceBasename()
	{
		return $this->source;
	}

	/**
	 * @return string
	 */
	public function getOutputBasename()
	{
		return $this->module . '-' . $this->source . '-' . $this->version . (($this->uglify) ? '-uglified' : '') . '.css';
	}

	/**
	 * @param boolean $watch
	 * @return \Foomo\Less
	 */
	public function watch($watch=true)
	{
		$this->watch = $watch;
		return $this;
	}

	/**
	 * @param boolean $uglify
	 * @return \Foomo\Less
	 */
	public function uglify($uglify=true)
	{
		$this->uglify = $uglify;
		return $this;
	}

	/**
	 * @return \Foomo\Less
	 */
	public function compile()
	{
		$source = $this->getSourceFilename();
		$output = $this->getOutputFilename();

		$compile = (!\file_exists($output));

		if (!$compile && $this->getWatch()) {
			$deps = \Foomo\Less\Utils::getDependencies($source);
			$cmd = \Foomo\CliCall\Find::create($deps)->type('f')->newer($output)->execute();
			if (!empty($cmd->stdOut)) $compile = true;
		}

		if ($compile) {
			$success = \Foomo\Less\Utils::compile($this->getSourceFilename(), $this->getOutputFilename());
			if ($success && $this->uglify) \Foomo\Less\Utils::uglify($this->getOutputFilename(), $this->getOutputFilename());
		}

		return $this;
	}

	//---------------------------------------------------------------------------------------------
	// Public static methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $module
	 * @param string $name name of the less file
	 * @param float $version update this, if you want a safe deployment
	 * @return \Foomo\Less
	 */
	public static function create($module, $source, $version)
	{
		return new self($module, $source, $version);
	}

}