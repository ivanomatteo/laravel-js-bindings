<?php

namespace IvanoMatteo\LaravelJsBindings\Commands;


use Illuminate\Console\Command;
use IvanoMatteo\LaravelJsBindings\FsUtils;

class Routes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jsbindings:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'export all named routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $out_path = base_path(config('jsbindings.routes.out_path'));
        if (!file_exists($out_path)) {
            mkdir($out_path, 0775, true);
        }

        $this->exportNamedRoutes($out_path);
    }

    private function exportNamedRoutes($dir, $prefix = '')
    {
        $routes_map = [];
        foreach (\Route::getRoutes() as $route) {
            $name = $route->getName();

            if (!$name) {
                continue;
            }

            $skip = false;
            foreach (config('jsbindings.routes.exclude_prefixes', []) as $p) {
                # code...
                if (\Str::startsWith($name, $p)) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) {
                continue;
            }

            $routes_map[$name] = [
                'uri' => $route->uri,
                'methods' => array_fill_keys($route->methods, true),
            ];
        }
        $out =  "export const Routes = " . json_encode($routes_map, JSON_PRETTY_PRINT) . ";\n";

        $outfile = $dir . '/' . 'Routes.js';
        file_put_contents($outfile, $out);
        
        echo "Routes written to: ".$outfile."\n";
    }
}
