<?php

/*
 * PHP FileSystem Environment - FileSystem tools for PHP
 * http://www.pleets.org
 * Copyright 2014, Pleets Apps
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Date: 2014-08-25
 */

namespace Pleets\FileSystem;

class Shell implements IShellCommands
{
	private $home = null;				# Home path. Equivalent to ~
	private $path = null;				# Current path
	private $files = null;				# Last files
	private $buffer = null;				# Buffer

	public function __construct($home = null, $path = null, $files = null)
	{
		# Get initial properties
		$this->home = is_null($home) ? $this->pwd()->path : $home;
		$this->path = is_null($path) ? $this->pwd()->path : $path;
		$this->files = is_null($files) ? $this->ls()->files : $files;

		# Global buffer
		$_SESSION["BUFFER"]["EXO"] = array();
	}

	public function get($value)
	{
		switch ($value) {
			case 'home':
				return $this->home;
				break;
			case 'path':
				return $this->path;
				break;
			case 'files':
				return $this->files;
				break;
			case 'buffer':
				return $this->buffer;
				break;			
			default:
				return false;
				break;
		}
	}

	public function getContents($handler, $fileCallback, $dirCallback, $callback = null)
	{
		$contents = array();

		if (is_dir($handler)) 
		{
			$this->ls($handler);
			foreach ($this->files as $item) 
			{
				if ($item != '.' && $item != '..')
					$contents[] = $item;
			}
			if (count($contents) > 0) 
			{
				foreach ($contents as $i) 
				{
					if (is_file($handler.'/'.$i)) 
					{
						$this->buffer = $handler.'/'.$i;
						call_user_func($fileCallback, $this);
					}
					elseif (is_dir($handler.'/'.$i)) 
					{
						$this->buffer = $handler.'/'.$i;
						$this->getContents($handler.'/'.$i,$fileCallback,$dirCallback);
						$directory = scandir($handler);

						if (!count($directory) < 3)
							$this->buffer = $handler.'/'.$i;

						call_user_func($dirCallback, $this);
					}
					else
						continue;
				}
			}

		}

		if (!is_null($callback))
			call_user_func($callback, $this);
	}

	public function pwd()
	{
		if (getcwd()) {
			$this->buffer = getcwd();
			$this->path = getcwd();
		}
		else
			return false;
		return $this;
	}

	public function ls($path = null, $recursive = false)
	{
		$this->files = array();
		if (is_dir($path))
			$pathIns = dir($path);
		elseif (is_dir($this->path))
			$pathIns = dir($this->path);
		elseif (is_dir($this->buffer))
			$pathIns = dir($this->buffer);
		else
			$pathIns = dir($this->home);

		if (is_file($path)) {
			$this->files = $path;
			$this->buffer = $path;
		}
		elseif (is_dir($path))
		{
			if ($recursive) 
			{
				$dirs = $files = array();

				$this->getContents($path, function($event) use (&$files) {
					$files[] = $event->get('buffer');
				}, function($event) use (&$dirs) {
					$dirs[] = $event->get('buffer');
				});

				$this->files = null;

				foreach ($dirs as $item) {
					$this->files[] = $item;
				}

				foreach ($files as $item) {
					$this->files[] = $item;
				}
			}
			else {
				while (false !== ($item = $pathIns->read())) {
					$files[] = $item;
				}
				$pathIns->close();
			}
		}
		else {
			$buffer = array(); $search = $path; $contents = $this->ls($this->get('path'))->get('files');
			foreach ($contents as $item) {
				if (!empty($search))
					if (!strlen(stristr($item,$search))>0)
						continue;
				if (strstr($item,'~') === false && $item != '.' && $item != '..')
					$buffer[] = $item;
			}
			$files = $buffer;
		}
		if (!(count($this->files)))
			$this->files = $files;
		return $this;
	}

	public function cd($path = null)
	{
		$moveTo = null;
		if (is_null($path))
			$moveTo = $this->home;
		elseif (is_dir($path))
			$moveTo = $path;
		else return false;

		if (!is_null($moveTo) && chdir($moveTo))
			$this->pwd();
		else return false;
		return $this;
	}

	public function touch($file)
	{
		if (!file_exists($this->path."/".$file)) {
			if (!$openFile = fopen($this->path.'/'.$file, 'w+'))
				return false;
			fwrite($openFile, "");
			fclose($openFile);
		}
		else
			return false;
		return $this;
	}

	public function rm($file = null, $recursive = null)
	{
		$recursive = is_null($recursive) ? false : $recursive;

		if (is_null($file))
			$file = $this->buffer;
		# Detect absolute path
		elseif (strpos($file, '/') === 0)
			$file = $file;
		# Else detect relative path
		elseif (!is_dir($file))
			$file = $this->path.'/'.$file;

		if (file_exists($file) && !$recursive)
			unlink($file);
		elseif (is_dir($file) && $recursive) {

			$_SESSION["BUFFER"]["EXO"]["rm"] = $file;
			$that = $this;
			$this->getContents($file,function() use ($that) {
					unlink($that->get('buffer'));
				},function() use ($that) {
					rmdir($that->get('buffer'));
				},function() {
					@rmdir($_SESSION["BUFFER"]["EXO"]["rm"]);
				});
		}
		return $this;
	}

	public function cp($file = null, $dest, $recursive = null)
	{
		$recursive = (is_null($recursive)) ? false : $recursive;
		$source = is_null($file) ? $this->buffer : $file;
		if (is_dir($dest)) {
			if (!$recursive)
				copy($source, $dest.'/'.$source);
			else {

				$_SESSION["BUFFER"]["EXO"]["cp"] = array();
				$_SESSION["BUFFER"]["EXO"]["cp"][0] = array();
				$_SESSION["BUFFER"]["EXO"]["cp"][1] = array();

				$_SESSION["BUFFER"]["EXO"]["cp"][2] = $dest;
				
				$that = $this;

				$this->getContents($source,function() use($that) {
					$_SESSION["BUFFER"]["EXO"]["cp"][0][] = $that->get('buffer');
				},function() use($that) {
					$_SESSION["BUFFER"]["EXO"]["cp"][1][] = $that->get('buffer');
				},function() {
					$files = $_SESSION["BUFFER"]["EXO"]["cp"][0];
					$dirs = $_SESSION["BUFFER"]["EXO"]["cp"][1];
					$dest = $_SESSION["BUFFER"]["EXO"]["cp"][2];

					foreach ($dirs as $item) {
						if (!file_exists($dest.'/'.$item))
							mkdir("$dest/$item",0777,true);
					}

					foreach ($files as $item) {
						if (!file_exists("$dest/$files"))
							copy($item, $dest.'/'.$item);
					}
				});
			}
		}
		else
			copy($source, $dest);
		return $this;
	}

	public function mv($oldfile = null, $newfile)
	{
		$oldfile = is_null($oldfile) ? $this->buffer : $oldfile;
		if (is_dir($newfile))
				$newfile .= '/'.basename($oldfile);
		if ($oldfile == $newfile)
			return $this;
		if(!rename($oldfile,$newfile))
			return false;
		return $this;
	}

	public function mkdir($dir = null, $dest = null, $recursive = null)
	{
		$recursive = (is_null($recursive)) ? false : $recursive;
		if ($recursive)
			mkdir("$dest/$dir",0777,true);
		else {
			if (!is_dir($dir))
			{
				if(!mkdir("$dir",0777))
					return false;		
			}
		}
		return $this;
	}

	public function rmdir($dir = null)
	{
		if (rmdir($dir))
			return $this;
		else
			return false;
	}
}
