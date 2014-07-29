<?php

# include Shell class
include('../FileSystem.php');

# New shell
$filesystem = new \FileSystem\Shell();

# Create testing directory and change directory to it
$filesystem ->mkdir('testing')
			->cd('testing');

echo "<h2>Creating directories and files</h2>";

echo <<<P
	<p>Creating the directory <em>testing</em> and the files <em>file-1.txt, file-2.txt, file-3.txt</em>,
	and teh directories <em>newDir</em> and <em>work</em> into it.</p>
P;

# show my path
echo "<strong>Directory</strong> -> " . $filesystem->pwd()->get('path') . "<br /><br />";

# Create something files
$filesystem->touch('file-1.txt');
$filesystem->touch('file-2.txt');
$filesystem->touch('file-3.txt');
$filesystem->mkdir('newDir')->mkdir('work');

echo "<strong>Files</strong> ->";

# Listing files from 'testing'
var_dump($filesystem->ls()->get('files'));

echo "<h2>Deleting directories and files</h2>";

echo <<<P
	<p>Deleting the directory <em>newDir</em> and the file <em>file-2.txt</em> from 'testing'.</p>
P;

$filesystem->rm('file-2.txt');
$filesystem->rmdir('newDir');

# Listing files from 'testing'
var_dump($filesystem->ls()->get('files'));

echo "<h2>Moving directories and files</h2>";

echo <<<P
	<p>Movign the file <em>file-1.tet</em> in the 'work' directory</p>
P;

$filesystem->mv('file-1.txt', 'work');

# Listing files from 'testing'
var_dump($filesystem->ls()->get('files'));

echo <<<P
	<p>In the end, the directory hierachy will be like this</p>
	+ testing <br />
	&nbsp;&nbsp; + work <br />
	&nbsp;&nbsp;&nbsp;&nbsp; - file-1.txt <br />
	&nbsp;&nbsp; - file-3.txt <br />
P;

echo "<h2>Listing directories and files</h2>";

# Listing files from 'testing' recursively
var_dump($filesystem->ls('.', true)->get('files'));
