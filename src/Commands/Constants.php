<?php

namespace IvanoMatteo\LaravelJsBindings\Commands;


use Illuminate\Console\Command;
use IvanoMatteo\LaravelJsBindings\FsUtils;

class Constants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jsbindings:const';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $out_path = base_path(config('jsbindings.const.out_path'));
        if (!file_exists($out_path)) {
            mkdir($out_path, 0775, true);
        }
        $in_path = base_path(config('jsbindings.const.in_path'));
        $in_namespace = config('jsbindings.const.in_namespace');

        FsUtils::findPsr4Classes(
            $in_path,
            $in_namespace,
            function (\ReflectionClass $refclass) use ($out_path) {
                
                $this->exportToJs($out_path, $refclass);
            }
        );
    }


    private function exportToJs($dir, $r)
    {
        $out = "";
        foreach ($r->getConstants() as $k => $v) {
            if (\Str::startsWith($k, '_')) {
                continue;
            }
            $out .= "export const $k = " . json_encode($v) . ";\n";
        }
        $outfile = $dir ."/". $r->getShortName() . '.js';
        file_put_contents($outfile, $out);
        
        echo $r->getName().' -> '.$outfile."\n";

    }
}
