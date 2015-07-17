<?php
sleep(1);

include "FileSystem/IShellCommands.php";
include "FileSystem/Shell.php";

if (!isset($_SESSION))
	session_start();

# shell instance
$filesystem = new \Pleets\FileSystem\Shell();

if (isset($_SESSION["path"]))
	$path = $_SESSION["path"];
else 
	$path = $filesystem->pwd();

$filesystem->cd($path);

$command = explode(" ", $_POST["command"]);
$command[1] = array_key_exists(1, $command) ? $command[1] : "";
$command[2] = array_key_exists(2, $command) ? $command[2] : "";

switch ($command[0]) 
{
	case 'pwd':
		echo $filesystem->$command[0]();
		break;
	case 'ls':
		echo implode(" ", $filesystem->$command[0]($command[1]));
		break;
	case 'cd':
		if ($filesystem->$command[0]($command[1]) === false)
			echo "System error!";
		break;
	case 'touch':
		$filesystem->$command[0]($command[1]);
		break;
	case 'rm':
		$filesystem->$command[0]($command[1]);
		break;
	case 'cp':
		$filesystem->$command[0]($command[1], $command[2]);
		break;
	case 'mv':
		$filesystem->$command[0]($command[1], $command[2]);
		break;
	case 'mkdir':
		$filesystem->$command[0]($command[1], $command[2]);
		break;
	case 'rmdir':
		$filesystem->$command[0]($command[1]);
		break;
	default:
		echo "Command not found!";
		break;
}

$_SESSION["path"] = $filesystem->pwd();

?>