<?php

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

$response = array();
$response["message"] = "";

$command = explode(" ", $_POST["command"]);
$command[1] = array_key_exists(1, $command) ? $command[1] : "";
$command[2] = array_key_exists(2, $command) ? $command[2] : "";

switch ($command[0])
{
	case 'pwd':
		$response["message"] = $filesystem->$command[0]();
		break;
	case 'ls':
		$response["message"] =  implode(" ", $filesystem->$command[0]($command[1]));
		break;
	case 'cd':
		if ($filesystem->$command[0]($command[1]) === false)
			$response["message"] = "unknown error!";
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
		$response["message"] = "Command not found!";
		break;
}

$response["path"] = $_SESSION["path"] = $filesystem->pwd();

echo json_encode($response);

?>
