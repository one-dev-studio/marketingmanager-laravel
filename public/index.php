<?php

use App\Http\Support\SubdirectoryRequestUriNormalizer;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$envPath = dirname(__DIR__);
if (is_readable($envPath.'/.env')) {
    Dotenv\Dotenv::createImmutable($envPath)->safeLoad();
}

$appUrl = $_ENV['APP_URL'] ?? getenv('APP_URL') ?: '';
if (is_string($appUrl) && $appUrl !== '') {
    $pathPrefix = SubdirectoryRequestUriNormalizer::pathPrefixFromAppUrl($appUrl);
    if ($pathPrefix !== null) {
        SubdirectoryRequestUriNormalizer::applyToServerGlobals($pathPrefix);
    }
}

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
