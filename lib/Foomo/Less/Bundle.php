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

use Foomo\Bundle\Compiler\Result;
use Foomo\Less;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 * @author Jan Halfar <jan@bestbytes.com>
 */
class Bundle extends \Foomo\Bundle\AbstractBundle
{
	private $less;
	/**
	 * @param Result $result
	 * @return Bundle
	 */
	public function compile(Result $result)
	{
		$lessCompiler = Less::create($this->less)
			->watch($this->debug)
			->name($this->name)
			->compress(!$this->debug)
			->compile()
		;
		$result->resources[] = Result\Resource::create(
			Result\Resource::MIME_TYPE_CSS,
			$lessCompiler->getOutputFilename(),
			$lessCompiler->getOutputPath()
		);
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $less less file name
	 * @return Bundle
	 */
	public static function create($name)
	{
		$ret = parent::create($name);
		$ret->less = func_get_arg(1);
		return $ret;
	}
	public static function mergeFiles(array $files, $debug)
	{
		$css = '';
		foreach($files as $file) {
			$css .= file_get_contents($file) . PHP_EOL;
		}
		return $css;
	}
	public static function canMerge($mimeType)
	{
		return $mimeType == Result\Resource::MIME_TYPE_CSS;
	}
}
