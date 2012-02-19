#!/usr/bin/env php
<?php
file_put_contents('composer.phar', file_get_contents('http://getcomposer.org/composer.phar'));
shell_exec('php composer.phar install');
