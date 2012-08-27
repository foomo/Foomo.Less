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

namespace Foomo\Less;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 * @author Jan Halfar <jan@bestbytes.com>
 * @author franklin <franklin@weareinteractive.com>
 */
class Utils
{
	//---------------------------------------------------------------------------------------------
	// Public static methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $source
	 * @param string $destination
	 * @return boolean
	 */
	public static function compile($source, $destination)
	{
		$ret = true;
		$pwd = \getcwd();
		# cd to the modules dir
		\chdir(\Foomo\Config::getModuleDir());
		# compile css
		$call = \Foomo\CliCall\Lessc::create($source, $destination)->execute();
		# validate
		if ($call->exitStatus !== 0) {
			\unlink($destination);
			\trigger_error('lessc gave up' . $call->report, \E_USER_WARNING);
			$ret = false;
		}
		\chdir($pwd);
		return $ret;
	}

	/**
	 * @param string $source
	 * @param string $destination
	 * @return boolean
	 */
	public static function uglify($source, $destination)
	{
		# uglify
		$call = \Foomo\CliCall\Uglifycss::create($source)->addArguments(array('>', $destination))->execute();
		# validate
		if ($call->exitStatus !== 0) {
			\trigger_error('uglifying failed:' . $call->stdErr, E_USER_WARNING);
			return false;
		} else {
			return true;
		}
	}

	/**
	 * @param string $filename
	 * @return string[]
	 */
	public static function getDependencies($filename)
	{
		$deps = array($filename);
		self::crawlDependencies($filename, $deps);
		return $deps;
	}

	//---------------------------------------------------------------------------------------------
	// Private static methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $filename
	 * @param string[] $deps
	 */
	private static function crawlDependencies($filename, array &$deps)
	{
		foreach (self::extractImports($filename) as $import) {
			if (!in_array($import, $deps)) {
				$deps[] = $import;
				self::crawlDependencies($import, $deps);
			}
		}
	}

	/**
	 * Extract @import "....less";
	 *
	 * @param type $filename
	 * @return string[] absolute filename
	 */
	private static function extractImports($filename)
	{
		$imports = array();
		$matches = self::pregMatch($filename);
		foreach ($matches[1] as $rawImport) {
			$resolvedFilename = self::resolveFilename($filename, $rawImport);
			if(!empty($resolvedFilename)) {
				$imports[] = $resolvedFilename;
			} else {
				trigger_error('can not resolve import in ' . $filename . ' to ' . $rawImport . ' from statement ' . $statement);
			}
		}
		return $imports;
	}

	/**
	 * @param string $filename
	 * @return array
	 */
	private static function pregMatch($filename)
	{
		$matches = array();
		\preg_match_all('/^@import *["\'](.*?)["\']/im', \file_get_contents($filename), $matches);
		return $matches;
	}

	/**
	 * Resolve a filename relative to a less filename
	 *
	 * @param string $filename absolute filename of the less file
	 * @param string $path (relative) filename of the import
	 * @return string resolved absolute filename
	 */
	private static function resolveFilename($filename, $path)
	{
		if (substr($path, 0, 2) == './') $path = substr($path, 2);
		foreach (array('', '.less') as $suffix) {
			foreach (self::getLookupRoots($filename) as $rootDir) {
				$resolvedFilename = $rootDir . $path . $suffix;
				if (file_exists($resolvedFilename) && is_file($resolvedFilename)) {
					return $resolvedFilename;
				}
			}
		}
	}

	/**
	 * Get import lookup root directories for a less file
	 *
	 * @param string $filename absolute filename of the .less file
	 * @return string[]
	 */
	private static function getLookupRoots($filename)
	{
		return array(
			'', // absolute path given
			\dirname($filename) . DIRECTORY_SEPARATOR, // relative path
			\Foomo\Config::getModuleDir() . DIRECTORY_SEPARATOR // modules dir
		);
	}
}