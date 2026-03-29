<?php

declare(strict_types=1);
use App\Domain\Shared\ExchangeRate\Services\Interfaces\IExchangeRateService;

if (! function_exists('exchangeRate')) {
    function exchangeRate(string $from, string $to): ?float
    {
        return resolve(IExchangeRateService::class)
            ->getRate($from, $to);
    }
}

if (! function_exists('convertCurrency')) {
    function convertCurrency(float $amount, string $from, string $to): ?float
    {
        return resolve(IExchangeRateService::class)
            ->convert($amount, $from, $to);
    }
}

if (! function_exists('get_permissions')) {

    function get_permissions(string $folder = ''): array
    {
        $controllersPath = app_path('Http/Controllers'.($folder ? '/'.$folder : ''));
        $namespace = 'App\Http\Controllers'.($folder ? '\\'.$folder : '');

        $result = [];

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($controllersPath)
        );

        foreach ($files as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace(
                [$controllersPath.DIRECTORY_SEPARATOR, '.php'],
                '',
                $file->getPathname()
            );

            $class = $namespace.'\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            if (! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if (! $reflection->isSubclassOf(\App\Http\Controllers\Controller::class)) {
                continue;
            }

            $controllerName = class_basename($class);

            if (in_array($controllerName, config('permission.ignore_controllers', []))) {
                continue;
            }

            $controllerSlug = Str::kebab(
                str_replace('Controller', '', $controllerName)
            );

            $permissions = [];

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if (
                    $method->class !== $class ||
                    in_array($method->name, config('permission.ignore_methods', [])) ||
                    in_array(
                        $method->name,
                        config("permission.ignore_controller_methods.$controllerName", [])
                    )
                ) {
                    continue;
                }

                $action = config("permission.method_action_map.{$method->name}");

                if (! $action) {
                    continue;
                }

                $permissions[] = "{$controllerSlug}-{$action}";
            }

            if ($permissions) {
                $result[$controllerName] = array_values(array_unique($permissions));
            }
        }

        return $result;
    }
}
