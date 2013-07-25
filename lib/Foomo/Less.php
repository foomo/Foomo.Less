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
	private $filename;
	/**
	 * @var boolean
	 */
	private $watch = false;
	/**
	 * @var string
	 */
	private $name = '';
	/**
	 * @var boolean
	 */
	private $compress = false;

	//---------------------------------------------------------------------------------------------
	// ~ Constructor
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $filename
	 */
	public function __construct($filename)
	{
		$this->filename = $filename;
		if (!\file_exists($this->filename)) \trigger_error ('Source does not exist: ' . $this->filename, \E_USER_ERROR);
	}

	//---------------------------------------------------------------------------------------------
	// ~ Public methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function getModule()
	{
		return $this->filename;
	}

	/**
	 * @param $name
	 * @return $this
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}
	/**
	 * @return string
	 */
	public function getSource()
	{
		return $this->source;
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
	public function getCompress()
	{
		return $this->compress;
	}

	/**
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * @return string
	 */
	public function getOutputPath()
	{
		return \Foomo\Less\Module::getHtdocsVarBuildPath() . '/' . $this->getOutputBasename();
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
	public function getOutputBasename()
	{
		$basename = \md5($this->filename);
		if ($this->compress) $basename .= '.min';
		if(empty($this->name)) {
			return  $basename . '.css';
		} else {
			return  $this->name . '-' . $basename . '.css';
		}
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
	 * @param boolean $compress
	 * @return \Foomo\Less
	 */
	public function compress($compress=true)
	{
		$this->compress = $compress;
		return $this;
	}
	private function needsCompilation()
	{
		$source = $this->getFilename();
		$output = $this->getOutputFilename();

		$compile = (!\file_exists($output));

		if (!$compile && $this->getWatch()) {
			$deps = \Foomo\Less\Utils::getDependencies($source);
			$cmd = \Foomo\CliCall\Find::create($deps)->type('f')->newer($output)->execute();
			if (!empty($cmd->stdOut)) $compile = true;
		}
		return $compile;
	}
	/**
	 * @return \Foomo\Less
	 */
	public function compile()
	{
		$output = $this->getOutputFilename();

		if (
			$this->needsCompilation() &&
			Lock::lock($lockName = 'LESS-' . basename($output)) &&
			$this->needsCompilation()
		) {
			$source = $this->getFilename();
			$success = \Foomo\Less\Utils::compile($source, $output);
			if ($success && $this->compress) \Foomo\Less\Utils::uglify($output, $output);
			Lock::release($lockName);
		}

		return $this;
	}

	//---------------------------------------------------------------------------------------------
	// Public static methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $filename Path to the less file
	 * @return \Foomo\Less
	 */
	public static function create($filename)
	{
		return new self($filename);
	}

	//---------------------------------------------------------------------------------------------
	// Private static methods
	//---------------------------------------------------------------------------------------------

	private static function sourceMaps()
	{
		return;
		$call = new \Foomo\CliCall(\Foomo\Less\Module::getVendorDir('less.js/bin/lessc'), array(
			'--source-map=' . ($map = \Foomo\Less\Module::getHtdocsDir('test.map')),
			\Foomo\Less\Module::getBaseDir('less/demo.less'),
			$css = \Foomo\Less\Module::getHtdocsDir('test.css')
		));
		$call->execute();
		file_put_contents(
			$css,
			str_replace(
				'/*# sourceMappingURL=' . $map . ' */',
				'/*# sourceMappingURL=' . basename($map) . ' */',
				file_get_contents($css)
			)
		);

	}

}