<?php

namespace IvanoMatteo\LaravelJsBindings\Commands;


use Illuminate\Console\Command;
use IvanoMatteo\LaravelJsBindings\FsUtils;

class Lang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jsbindings:lang';

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
        $out_path = base_path(config('jsbindings.lang.out_path'));
        if (!file_exists($out_path)) {
            mkdir($out_path, 0775, true);
        }

        $in_path = base_path('resources/lang');

        $langs = [];

        FsUtils::listDir(
            $in_path,
            function (\SplFileInfo $info) use (&$langs) {
                if ($info->isDir()) {
                    $lang = strtolower($info->getFilename());
                    $langs[$lang] = true;

                } else if (strtolower($info->getExtension()) === 'json') {
                    
                    $lang = strtolower($info->getFilename());
                    $lang = substr($lang, 0, strlen($lang) - 5);
                    $langs[$lang] = true;
                }
            }
        );
        
        $langs = array_keys($langs);

        foreach ($langs as $l) {
            $path = $in_path . "/" . $l;
            $langMap = [];
            if (is_dir($path)) {
                $langMap = $this->readDir($path);
            }
            if (is_file($path . ".json")) {
                $tmp = json_decode(file_get_contents($path . ".json"),true);
                if($tmp === null){
                    echo "file: $path.json is not a valid json.\n";
                }
                //$langMap = array_merge($langMap,$tmp);
                $langMap['__json__'] = $tmp;
            }
            if(!empty($langMap)){
                $outfile = $out_path . '/' . $l . '.js';
                $out =  "export default " . json_encode($langMap, JSON_PRETTY_PRINT) . ";\n";
                file_put_contents($outfile, $out);
                echo "Lang $l written to: ".$outfile."\n"; 
            }
        }
    }


    function readDir(string $dir)
    {
        $langMap = [];
        FsUtils::listDir(
            $dir,
            function (\SplFileInfo $info) use (&$langMap) {
                $name = substr($info->getFilename(), 0, strlen($info->getFilename()) - 4);
                $langMap[$name] = include $info->getRealPath();
            }
        );

        return $langMap;
    }


    
}
