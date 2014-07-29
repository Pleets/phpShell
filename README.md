# PHP FileSystem Environment
### FileSystem tools for PHP

PHPFileSystem is an easy script for manage the real filesystem in the server.

Installation
------------

Simply add the file `FileSystem.php` in your project.
```php
include('FileSystem.php');
```

And run this as 
```php
$filesystem = new \FileSystem\Shell();
```

Creating directories and files
------------------------------

To create directories
```php
$filesystem ->mkdir('myDir');
```

To create files
```php
$filesystem ->mkdir('myFile.ext');
```

Deleting directories and files
------------------------------

To delete directories
```php
$filesystem ->rmdir('myDir');
```

To delete files
```php
$filesystem ->rm('myFile.ext');
```

Moving directories and files
------------------------------

To move directories
```php
$filesystem ->mv('myDir', 'myPath');
```

To move files
```php
$filesystem ->rm('myFile.ext', 'myPath');
```

This command is same in Linux, the `mv` command also is useful to rename files an directories.

Listing files and directories
------------------------------

To list files and dirs
```php
$filesystem ->ls()->get('files');
```

To list files and dirs recursively
```php
$filesystem ->ls('path', true)->get('files');
```

Changing the path
-----------------
```php
$filesystem ->cd('myPath');
```