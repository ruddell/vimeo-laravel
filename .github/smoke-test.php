<?php

require getcwd() . '/vendor/autoload.php';

echo 'PHP ' . PHP_VERSION . "\n\n";

$classes = [
    'Vimeo\Laravel\VimeoServiceProvider',
    'Vimeo\Laravel\VimeoManager',
    'Vimeo\Laravel\VimeoFactory',
    'Vimeo\Laravel\Facades\Vimeo',
];

echo "Checking classes:\n";
foreach ($classes as $class) {
    try {
        if (!class_exists($class)) {
            fwrite(STDERR, "FAIL: class not found: $class\n");
            exit(1);
        }
    } catch (\Throwable $e) {
        fwrite(STDERR, "FAIL: error loading $class: " . $e->getMessage() . "\n");
        exit(1);
    }
    echo "  [OK] $class\n";
}

echo "\nChecking service provider registration:\n";
try {
    $app = new \Illuminate\Container\Container();
    $provider = new \Vimeo\Laravel\VimeoServiceProvider($app);
    $provider->register();
} catch (\Throwable $e) {
    fwrite(STDERR, "FAIL: error registering service provider: " . $e->getMessage() . "\n");
    exit(1);
}

foreach (['vimeo', 'vimeo.factory', 'vimeo.connection'] as $binding) {
    if (!$app->bound($binding)) {
        fwrite(STDERR, "FAIL: '$binding' not bound in container\n");
        exit(1);
    }
    echo "  [OK] '$binding' registered\n";
}

echo "\nAll smoke tests passed!\n";
