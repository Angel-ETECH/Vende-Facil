<?php

// Development fallback for Windows PHP reporting directories unwritable despite working writes.
require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
$files = new Illuminate\Filesystem\Filesystem;
$manifest = new class($files, $root, $root.'/bootstrap/cache/packages.php') extends Illuminate\Foundation\PackageManifest {
    protected function write(array $manifest)
    {
        if (file_put_contents($this->manifestPath, '<?php return '.var_export($manifest, true).';') === false) {
            throw new RuntimeException('Cannot write package cache.');
        }
    }
};
$manifest->build();
$app = new Illuminate\Foundation\Application($root);
$providers = Illuminate\Support\ServiceProvider::defaultProviders()->merge($manifest->providers())
    ->merge(require $root.'/bootstrap/providers.php')->toArray();
$repository = new class($app, $files, $root.'/bootstrap/cache/services.php') extends Illuminate\Foundation\ProviderRepository {
    public function rebuild(array $providers) { return $this->compileManifest($providers); }
    public function writeManifest($manifest)
    {
        if (file_put_contents($this->manifestPath, '<?php return '.var_export($manifest, true).';') === false) {
            throw new RuntimeException('Cannot write service cache.');
        }
        return array_merge(['when' => []], $manifest);
    }
};
$repository->rebuild($providers);
echo "Windows development caches rebuilt.\n";
