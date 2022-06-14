<?php


namespace API\Core\Utils;


const STYLE = 0;
const SCRIPT = 1;

class ScriptLoader
{
    private static ?ScriptLoader $instance = null;
    private static array $buildAssets;
    private function __construct(){
        self::bootstrap();
    }
    public static function getInstance() : ?ScriptLoader
    {
        if(!self::$instance) {
            !self::$instance= new self();
        }
        return self::$instance;
    }
    private  function bootstrap() {
        $scripts = file_get_contents(APP_VIEWS.'partials/scripts.twig');
        $styles = file_get_contents(APP_VIEWS.'partials/styles.twig');
        $scriptManifest = self::haystackParser($scripts);
        $styleManifest = self::haystackParser($styles);
        foreach ($styleManifest as $style) {
            self::$buildAssets[STYLE][] = $style;
        }
        foreach ($scriptManifest as $script) {
            self::$buildAssets[SCRIPT][] = $script;
        }

//        dump(self::$buildAssets);
    }
    private function haystackParser(string $haystack): array
    {
        $needles = explode(',', $haystack);
        for($i=0;$i<count($needles);$i++){
            $needles[$i] = self::sanitizer($needles[$i]);
        }
       return $needles;
    }
    private function sanitizer(string $longName): string
    {
        $short = explode('/', $longName);
        return $short[count($short)-1];
    }
    public static function loadProductionScripts(int $type): string
    {
        $production = '';
        $number_validation_regex = "/^\\d+$/";
        foreach (self::$buildAssets[$type] as $asset ){
            $needle  = explode('.',$asset)[0];
            if( preg_match($number_validation_regex, $needle)){
                if( $type === STYLE){
                    $production.= "<link rel='stylesheet' href='".APP_ASSET_BASE . 'css/'.$asset."'> ";
                }else{
                    $production.='<script src="'.APP_ASSET_BASE . 'js/'.$asset.'" type="text/javascript"></script>';
                }
            }
        }
        return $production;
    }
    public static function loadScriptTag(int $type, string $name): string
    {
        $filter =  function() use($type,$name) {
            foreach (self::$buildAssets[$type] as $asset ){
                if(explode('.',$asset)[0] == $name){
                    if( $type === STYLE){
                        return   APP_ASSET_BASE . 'css/'.$asset;
                    }else{
                        return    APP_ASSET_BASE . 'js/'.$asset;
                    }
                }
            }
            return '';
        };
        return $filter();
    }
}

