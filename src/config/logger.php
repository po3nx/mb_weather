<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\ErrorLogHandler;

$logger = new Logger('app');

$streamHandler = new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::DEBUG);
$errorLogHandler = new ErrorLogHandler();

$logger->pushHandler($streamHandler);
$logger->pushHandler($errorLogHandler);

return $logger;