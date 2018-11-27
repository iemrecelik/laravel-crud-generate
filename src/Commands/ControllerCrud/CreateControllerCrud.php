<?php

namespace Dirim\LvGenerateCrud\Commands\ControllerCrud;

use Illuminate\Console\Command;
use Artisan;

class CreateControllerCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  '
                                make:crud
                                {controller : enter controller name}
                                {--m|model= : enter model name}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make crud for controller class';

    /**
     * Suppose it has been following:
     * Conroller name: 'Admin/BooksController'
     * Model name: 'Admin/Books'
     * Language Model name: 'Admin/BooksLang'
     * Request rule name: 'Admin/BooksRequest'
     * Advanced request rule name: 'Admin/BooksAdvancedRequest'
     * Images model name: 'Admin/Images'
     * Images request rule name: 'Admin/UpdateImagesPost'
     */

    protected $fieldIDName; // Default value: id
    protected $addFields = []; // Default value: Array()
    protected $addLangFields = []; // Default value: Array()
    protected $controllerPath; // Admin/BooksController
    protected $controllerName; // BooksController
    protected $reqRules; // Admin/BooksRequest
    protected $reqRulesUsePath; // Admin\BooksRequest
    protected $reqRulesName; // BooksRequest
    protected $advancedReqRules; // Admin/BooksAdvancedRequest
    protected $advancedReqRulesName; // BooksAdvancedRequest
    protected $modelPath; // Admin/Books
    protected $modelUsePath; // Admin\Books
    protected $modelNamespacePath; // Admin\Books
    protected $modelName; // Books
    protected $modelVarName; // book

    protected $langFieldIDName; // Default value: id
    protected $fieldDependsOnLang; // Default value: lang_lang
    protected $langModelPath; // Admin/BooksLang
    protected $langModelUsePath; // Admin\BooksLang
    protected $langModelNamespacePath; // Admin\BooksLang
    protected $langModelName; // BooksLang
    protected $langModelVarName; // bookLang

    protected $imgModelPath = null; // Admin/Images
    protected $imgModelName = null; // Images
    protected $imgReqRules = null; // Admin/UpdateImagesPost
    protected $imgReqRulesName = null; // UpdateImagesPost
    protected $crudType;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getUsePath($path)
    {
        return str_replace('/', '\\', $path);
    }

    protected function convertUseToPrefix($path, $search = '\\', $change = '\\')
    {
        $prefix = explode($search, $path);

        if (count($prefix) > 1)
            array_pop($prefix);

        $prefix = implode($change, $prefix);

        return $prefix;
    }

    /**
     * @param mixed $controllerPath
     *
     * @return self
     */
    public function setControllerPath($controllerPath)
    {
        $this->controllerPath = $controllerPath;
        $this->controllerName = preg_replace(
            '/(.+)\/(\w+)$/', '$2', $controllerPath
        );
        
        return $this;
    }

    /**
     * @param mixed $modelNamespacePath
     *
     * @return self
     */
    public function setModelNamespacePath($modelPath)
    {
        $modelNamespacePath = explode('/', $modelPath);
        
        if($modelNamespacePath > 1){
            array_pop($modelNamespacePath);
            $modelNamespacePath = implode('\\', $modelNamespacePath);
        }else
            $modelNamespacePath = '';
        
        $this->modelNamespacePath = $modelNamespacePath;

        return $this;
    }

    /**
     * @param mixed $modelUsePath
     *
     * @return self
     */
    public function setModelUsePath($modelPath)
    {
        $this->modelUsePath = $this->getUsePath($modelPath);

        return $this;
    }

    /**
     * @param mixed $modelName
     *
     * @return self
     */
    public function setModelName($modelPath)
    {
        $this->modelName = explode('/', $modelPath);
        $this->modelName = end($this->modelName);

        return $this;
    }

    /**
     * @param mixed $modelVarName
     *
     * @return self
     */
    public function setModelVarName($modelPath)
    {
        $this->modelVarName =  preg_replace(
            '/(.+\/)?(\w+)s$/', '$2', $modelPath
        );
        $this->modelVarName = strtolower($this->modelVarName);

        return $this;
    }
// -----------------------------------------------------
    /**
     * @param mixed $langModelPath
     *
     * @return self
     */
    public function setLangModelPath($modelPath)
    {
        $this->langModelPath = $modelPath.'Lang';

        return $this;
    }

    /**
     * @param mixed $langModelNamespacePath
     *
     * @return self
     */
    public function setLangModelNamespacePath($langModelPath)
    {
        $langModelNamespacePath = explode('/', $langModelPath);
        
        if($langModelNamespacePath > 1){
            array_pop($langModelNamespacePath);
            $langModelNamespacePath = implode('\\', $langModelNamespacePath);
        }else
            $langModelNamespacePath = '';
        
        $this->langModelNamespacePath = $langModelNamespacePath;

        return $this;
    }

    /**
     * @param mixed $langModelUsePath
     *
     * @return self
     */
    public function setLangModelUsePath($langModelPath)
    {
        $this->langModelUsePath = $this->getUsePath($langModelPath);

        return $this;
    }

    /**
     * @param mixed $langModelName
     *
     * @return self
     */
    public function setLangModelName($langModelPath)
    {
        $this->langModelName = explode('/', $langModelPath);
        $this->langModelName = end($this->langModelName);

        return $this;
    }

    /**
     * @param mixed $langModelVarName
     *
     * @return self
     */
    public function setLangModelVarName($langModelPath)
    {
        $this->langModelVarName =  preg_replace(
            '/(.+\/)?(\w+)s$/', '$2', $langModelPath
        );
        $this->langModelVarName = strtolower($this->langModelVarName);

        return $this;
    }
// ----------------------------------
    /**
     * @param mixed $reqRules
     *
     * @return self
     */
    public function setReqRules($reqRules)
    {
        $this->reqRules = $reqRules;

        $this->reqRulesName = explode('/', $reqRules);
        $this->reqRulesName = end($this->reqRulesName);

        $this->reqRulesUsePath = $this->getUsePath($reqRules);

        return $this;
    }

    /**
     * @param mixed $reqRules
     *
     * @return self
     */
    public function setAdvancedReqRules($advancedReqRules)
    {
        $this->advancedReqRules = $advancedReqRules;

        $this->advancedReqRulesName = explode('/', $advancedReqRules);
        $this->advancedReqRulesName = end($this->advancedReqRulesName);

        return $this;
    }


    /**
     * @param mixed $imgReqRules
     *
     * @return self
     */
    public function setImgReqRules($imgReqRules)
    {
        $this->imgReqRules = $imgReqRules;

        $this->imgReqRulesName = explode('/', $imgReqRules);
        $this->imgReqRulesName = end($this->imgReqRulesName);

        return $this;
    }


    /**
     * @param mixed $imgModelPath
     *
     * @return self
     */
    public function setImgModelPath($imgModelPath)
    {
        $this->imgModelPath = $imgModelPath;

        $this->imgModelName = explode('/', $imgModelPath);
        $this->imgModelName = end($this->imgModelName);

        return $this;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setControllerPath(
            $this->argument('controller')
        );

        $this->modelPath = $this->option('model');

        $this->crudType = $this->choice(
            'Choice a crud process type', 
            ['modal', 'advanced', 'all'], 
            0
        );

        $isModal = $this->crudType === 'modal';

        if ($isModal) {
            $imgConfirm = $this->confirm(
                'Do you wish to add image processes?'
            );
        }else
            $imgConfirm = false;

        if($imgConfirm || !$isModal){

            $imgModelPath = $this->ask(
                'Please enter a image model name?',
                'Images'
            );
            $this->setImgModelPath($imgModelPath);

            if($this->crudType !== 'advanced'){

                $defaultRules = preg_replace(
                    '/(.+\/)?(\w+)$/', '$1Update$2Post', $imgModelPath
                );

                $imgReqRules = $this->ask(
                    'Please enter a request rule name for image model?',
                    $defaultRules
                );
                $this->setImgReqRules($imgReqRules);
            }
        }

        if (!$this->modelPath)
            $this->modelPath = $this->ask(
                'Please enter a model name?'
            );

        if (!$this->modelPath){
            $this->error('Model name is entered empty');
            exit();
        }else{
            /*Field Names*/
            $this->fieldIDName = $this->ask(
                'Please enter a unique id name : ',
                'id'
            );
            do{
                $field['name'] = $this->ask(
                    'Please enter a field name : ',
                    'exit'
                );

                $isContinue = $field['name'] !== 'exit';

                if ($isContinue){
                    if (!empty($field['name'])) {

                        $field['type'] = $this->choice(
                            'Please enter a input type for field name : ',
                            ['text', 'date', 'hidden'],
                            0
                        );
                        
                        $this->addFields[] = $field;

                    }else{
                        $this->error('Field name entered empty');
                    }
                }

            }while($isContinue);

            if (count($this->addFields) < 1) {
                $this->error('Field names must be greather than 0');
                exit();
            }

            /*language Field Names*/
            $isLangFields = $this->confirm(
                'Do you wish to add language table?',
                'yes'
            );

            if ($isLangFields) {

                $this->langModelPath = $this->ask(
                    'Please enter a language model name?',
                    $this->modelPath.'Lang'
                );

                $this->fieldDependsOnLang = $this->ask(
                    'Please enter a field name '.
                    'depends on language : ',
                    'lang_lang'
                );

                $this->langFieldIDName = $this->ask(
                    'Please enter a unique id name for language model : ',
                    'id'
                );

                do{
                    $langField['name'] = $this->ask(
                        'Please enter a language field name : ',
                        'exit'
                    );

                    $isContinue = $langField['name'] !== 'exit';

                    if ($isContinue){
                        $langField['type'] = $this->choice(
                            'Please enter a input type for '.
                            'language field name : ',
                            ['text', 'date', 'hidden'],
                            0
                        );

                        $this->addLangFields[] = $langField;
                    }

                }while($isContinue);

                if (count($this->addLangFields) < 1) {
                    $this->error('Language field names must be greather than 0');
                    exit();
                }
            }

            $this->setModelVarName($this->modelPath)
            ->setModelName($this->modelPath)
            ->setModelUsePath($this->modelPath);

            $this->setLangModelPath($this->modelPath)
            ->setLangModelVarName($this->langModelPath)
            ->setLangModelName($this->langModelPath)
            ->setLangModelUsePath($this->langModelPath);
        }
        
        $outputs = $this->outputFiles();        

        $this->writeFiles($outputs);
        $this->writeRequestRuleFiles();
        $this->writeModelFile();

        $addedInfo = "Following Added Successfully:";

        $lwModelPath = strtolower($this->modelPath);
        $filesAdded = [
            "app/Http/Controller/{$this->controllerPath}.php \n",

            "app/Models/{$this->modelPath}.php \n",
            
            "app/ModelsRepository/{$this->modelPath}.php \n",
            
            "app/Http/Request/{$this->reqRules}.php \n",
            
            "app/resources/js/components/{$lwModelPath}/* ".
                "(Indeed crud files added) \n",
            
            "app/resources/assets/js/component.js ".
                "(Components making crud for processes ".
                "to vue js added) \n",

            "app/view/{$lwModelPath}/* (Indeed view files added) \n",
            
            "app/routes/web.php ".
                "(indeed route names added to route file) \n"
        ];

        if($this->imgModelName){
            $filesAdded[] = 
                "app/config/imageFilters.php ".
                    "(Default filtre settings added within file)\n";
            
            if($this->crudType !== 'advanced'){
                $filesAdded[] = 
                    "app/Http/Request/{$this->imgReqRules}.php\n";
            }
        }

        if($this->crudType === 'all'){
            $filesAdded[] = 
                "app/Http/Request/{$this->advancedReqRules}.php\n";
        }

        foreach ($filesAdded as $fileAdded) {
            $this->info($addedInfo);
            $this->line($fileAdded);
        }
    }

    protected function makeModel($path)
    {
        $modelPath = app_path('Models/'.$path.'.php');
        
        if(!is_file($modelPath) && $path){

            Artisan::call('make:model', [
                'name' => 'Models/'.$path,
            ]);
        }
    }

    protected function writeModelFile()
    {
        $modelPath = $this->makeModel($this->modelPath);
        $langModelPath = $this->makeModel($this->langModelPath);
        $langModelPath = $this->makeModel($this->imgModelPath);

        $modelRepoPath = app_path(
            'ModelsRepository/'.$this->modelPath.'Repository.php'
        );
        
        if(!is_file($modelRepoPath) && $this->modelPath){
            
            $modelPrefix = $this->convertUseToPrefix($this->modelUsePath);
            
            $repoContent = $this->getTemp(
                'ModelTemps/ModelRepoTemp', 
                [
                    'modelPrefix' => $modelPrefix,
                    'modelName' => $this->modelName,
                ]
            );

            $this->writeFiles([
                [
                    'path' => $modelRepoPath,
                    'content' => $repoContent,
                ]
            ]);
        }
    }

    protected function writeRequestRuleFiles()
    {
        $reqRulesPath = app_path(
            'Http/Requests/'.$this->reqRules.'.php'
        );
        if(!is_file($reqRulesPath)
            && $this->reqRules){
            Artisan::call('make:request', [
                'name' => $this->reqRules,
            ]);
        }

        $advancedReqRulesPath = app_path(
            'Http/Requests/'.$this->advancedReqRules.'.php'
        );
        if(!is_file($advancedReqRulesPath)
            && $this->advancedReqRules){
            Artisan::call('make:request', [
                'name' => $this->advancedReqRules,
            ]);
        }

        $imgReqRulesPath = app_path(
            'Http/Requests/'.$this->imgReqRules.'.php'
        );

        if(!is_file($imgReqRulesPath)
            && $this->imgReqRules){
            Artisan::call('make:request', [
                'name' => $this->imgReqRules,
            ]);

            $imgRuleContent = $this->getOpenFileContent(
                $imgReqRulesPath
            );

            $defaultConfig =
                    "\n\t\t\t'images' => 'array',".
                    "\n\t\t\t'images.*.file' => ".
                        "'file|max:2500|mimes:jpeg,jpg,png,gif',\n";

            $imgRuleContent = preg_replace(
                '/(.+public function rules\(\).+return\s\[)'.
                '(.*)(\]\;.+)/s', 
                "$1{$defaultConfig}\t\t$3", 
                $imgRuleContent
            );

            $imgRuleContent = str_replace(
                'return false;', 
                'return true;', 
                $imgRuleContent
            );

            $this->writeFiles([
                [
                    'path' => $imgReqRulesPath,
                    'content' => $imgRuleContent,
                ]
            ]);
        }
    }

    protected function configFilesContents()
    {
        $configContents[] = $this->componentJSContent();
        $configContents[] = $this->routeWebContent();

        if($this->imgModelName){
            $configContents[] = $this->imageFiltersContent();    
        }
        
        return $configContents;
    }

    protected function getOpenFileContent($path)
    {
        $content = '';
        if(is_readable($path) && filesize($path) > 0){
            $fopen = fopen($path, "r");

            $content = fread($fopen, filesize($path));

            fclose($fopen);
        }
        return $content;
    }

    protected function imageFiltersContent()
    {
        $path = config_path('imageFilters.php');
        $content = $this->getOpenFileContent($path);
        $defaultImgFilterContent  =
            "<?php\n\n"."return [\n".
            "\t'filter' => [],\n".
            "];";

        $content = empty($content) 
            ? $defaultImgFilterContent
            : $content;

        $addFilter = $this->getTemp(
            'ImageFiltersTemps/ImageFiltersTemp',
            ['modelVarName' => $this->modelVarName]
        );
            
        $content = preg_replace(
            '/(.+\'filter\' => \[.*)(\]\,)(.*\]\;)$/s', 
            "$1{$addFilter}\t$2$3", 
            $content
        );
        
        return [
            'path' => $path,
            'content' => $content,
        ];
    }

    protected function routeWebContent()
    {
        $path = base_path('routes/web.php');
        $content = $this->getOpenFileContent($path);

        /**
         * Suppose Controller name: Admin/Stationery/BooksController
         * $controllerUsePath: Admin\Stationery (namespace)
         * $controllerPath: admin/stationery (prefix)
         * $controllerPathName: admin.stationery. (name)
         */
        $controllerPath = explode('/', $this->controllerPath);
        array_pop($controllerPath);

        $controllerPath = implode('/', $controllerPath); 
        $controllerUsePath = str_replace('/', '\\', $controllerPath); 
        $controllerPath = strtolower($controllerPath);
        $controllerPathName = str_replace('/', '.', $controllerPath);
        $controllerPathName .= '.';
        $addRoutes = $this->getTemp(
            'RouteTemps/WebRouteTemps', 
            [
                'modelName' => $this->modelName,
                'modelVarName' => $this->modelVarName,
                'controllerName' => $this->controllerName,
                'imgModelName' => $this->imgModelName,
                'crudType' => $this->crudType,
                'langModelName' => $this->langModelName,
            ]
        );

        $controllerPathPreg = addcslashes($controllerPath, '/');
        $controllerUsePathPreg = addslashes($controllerUsePath);
        $controllerPathNamePreg = addslashes($controllerPathName);

        $pregContent = preg_replace(
            "/(Route::prefix\(\'?\"?$controllerPathPreg\'?\"?\)".
            ".*->namespace\(\'?\"?$controllerUsePathPreg\'?\"?\)".
            ".*->name\(\'?\"?$controllerPathNamePreg\'?\"?\)".
            ".*->group\(function\(\)\{.*)(\}\)\;)".
            "(.*)/s", 
            "$1\n{$addRoutes}\t$2\n$3",
            $content
        );

        if($pregContent === $content){
            $params = [
                'prefix' => $controllerPath,
                'namespace' => $controllerUsePath,
                'name' => $controllerPathName,
                'addRoutes' => $addRoutes,
            ];
            $pregContent = $this->getTemp(
                'RouteTemps/WebRouteGroupsTemps', $params
            );
            $mode = 'a';
        }

        return [
            'path' => $path,
            'content' => $pregContent,
            'mode' => $mode ?? 'w',
        ];
    }

    protected function componentJSContent()
    {
        $lwModelPath = strtolower($this->modelPath);
        $lwModelName = strtolower($this->modelName);

        $importRequires = 
        "import {$lwModelName}Component from ".
        "'./components/{$lwModelPath}/IndexComponent.vue';";


        $exportComponents = 
        "\t'{$lwModelName}-component': ".
        "{$lwModelName}Component,";

        if ($this->crudType !== 'modal') {

            $importRequires .= 

            "\nimport {$lwModelName}CreateAdvancedComponent from ".
            "'./components/{$lwModelPath}/".
            "CreateAdvancedComponent.vue';".

            "\nimport {$lwModelName}EditAdvancedComponent from ".
            "'./components/{$lwModelPath}/".
            "EditAdvancedComponent.vue';";


            $exportComponents .= 

            "\n\t'{$lwModelName}-create-advanced-component': ".
            "{$lwModelName}CreateAdvancedComponent,".

            "\n\t'{$lwModelName}-edit-advanced-component': ".
            "{$lwModelName}EditAdvancedComponent,";
        }

        $path = resource_path('js/components.js');
        $content = '';

        if(is_readable($path) && filesize($path) > 0){
            $componentsJS = fopen($path, "r");

            $content = fread($componentsJS, filesize($path));

            fclose($componentsJS);
        }

        $content = preg_replace(
            '/(import.+\;\n)(\nexport.+\,\n)/s',
            "$1$importRequires\n$2$exportComponents\n", 
            $content
        );

        if ($content === '') {
            $content =  "{$importRequires}\n\n".
                        "export default {\n{$exportComponents}\n}";
        }
        
        return [
            'content' => $content,
            'path' => $path,
        ];
    }

    protected function writeFiles($outputs)
    {
        foreach ($outputs as $output) {

            $mode = $output['mode'] ?? 'w';

            $this->writeNonexistentDir($output['path']);

            $controllerClass = fopen($output['path'], $mode);
            
            fwrite($controllerClass, $output['content']);
            
            fclose($controllerClass);
        }
        
    }

    protected function writeNonexistentDir($path)
    {
        $pathArr = explode('/', $path);
        array_pop($pathArr);

        $dir = "";
        foreach ($pathArr as $dirName) {
            
            $dir .= "/{$dirName}";

            if(!is_dir($dir)){
                mkdir($dir);
            }
        }
    }

    protected function outputFiles()
    {
        $outputs[] = $this->controllerClassTemp();
        
        $outputs = array_merge($outputs, $this->viewVueTemp());
        
        $outputs = array_merge(
            $outputs, 
            $this->configFilesContents()
        );

        return $outputs;
    }

    protected function viewVueTemp()
    {
        switch ($this->crudType) {
            case 'modal':
                $bladeTmpNames = [
                    'Index.BladeTemp' => 'modelName|modelVarName',
                ];
                $tmpFileNames = [
                    'CreateTemp' => '',
                    'CreateFormTemp' => 'addLangFields|addFields',
                    'DeleteTemp' => '',
                    'EditTemp' => '',
                    'EditFormTemp' => 'addLangFields|addFields',
                    'FormModalTemp' => '',
                    'IndexTemp' => '
                        modelName|modelVarName|crudType|imgModelName|
                        addLangFields|addFields|fieldIDName
                    ',
                    'ShowTemp' => '',
                ];
                break;

            case 'advanced':
                $bladeTmpNames = [
                    'Create.BladeTemp' => 'modelName|modelVarName',
                    'Edit.BladeTemp' => 'modelName|modelVarName',
                    'Index.BladeTemp' => 'modelName|modelVarName',
                ];
                $tmpFileNames = [
                    'CreateAdvancedTemp' => 'modelVarName',
                    'CreateLangFormTemp' => '
                        addLangFields|fieldDependsOnLang
                        |langFieldIDName
                    ',
                    'CreateFormTemp' => 'addLangFields|addFields',
                    'DeleteTemp' => '',
                    'EditAdvancedTemp' => 'modelVarName',
                    'EditLangFormTemp' => '
                        addLangFields|fieldDependsOnLang
                        |langFieldIDName|langModelName
                    ',
                    'EditFormTemp' => 'addLangFields|addFields',
                    'FormModalTemp' => '',
                    'IndexTemp' => '
                        modelName|modelVarName|crudType
                        |imgModelName|
                        addLangFields|addFields|fieldIDName
                    ',
                    'ShowTemp' => '',
                ];
                break;
            
            case 'all':
                $bladeTmpNames = [
                    'Create.BladeTemp' => 'modelName|modelVarName',
                    'Edit.BladeTemp' => 'modelName|modelVarName',
                    'Index.BladeTemp' => 'modelName|modelVarName',
                ];
                $tmpFileNames = [
                    'CreateAdvancedTemp' => 'modelVarName',
                    'CreateLangFormTemp' => '
                        addLangFields|fieldDependsOnLang
                        |langFieldIDName
                    ',
                    'CreateTemp' => '',
                    'CreateFormTemp' => 'addLangFields|addFields',
                    'DeleteTemp' => '',
                    'EditAdvancedTemp' => 'modelVarName',
                    'EditLangFormTemp' => '
                        addLangFields|fieldDependsOnLang
                        |langFieldIDName|langModelName
                    ',
                    'EditTemp' => '',
                    'EditFormTemp' => 'addLangFields|addFields',
                    'FormModalTemp' => '',
                    'IndexTemp' => '
                        modelName|modelVarName|crudType
                        |imgModelName|
                        addLangFields|addFields|fieldIDName
                    ',
                    'ShowTemp' => '',
                ];
                break;
        }

        if($this->imgModelName){
            $tmpFileNames['ImagesTemp'] = 'modelVarName';
            $tmpFileNames['ImagesFormTemp'] = '';
        }

        $diffVueTmp = $this->getDifferentTemps(
            $tmpFileNames, 
            [
                'fromPath' => 'ViewVueTemps',
                'toPath' => resource_path("js/components/"),
                'extension' => 'vue'
            ]
        );

        $diffBladeTmp = $this->getDifferentTemps(
            $bladeTmpNames,
            [
                'fromPath' => 'ViewVueTemps/BladeTemps',
                'toPath' => resource_path("views/"),
                'extension' => 'blade'
            ]
        );

        return array_merge($diffVueTmp, $diffBladeTmp);
    }

    protected function getDifferentTemps($tmpNames, Array $pathInfo)
    {
        $temps = [];
        $lwModelPath = strtolower($this->modelPath);

        foreach ($tmpNames as $tmpKey => $tmpVal) {

            $params = [];
            if($tmpVal){

                $paramArr = explode('|', trim($tmpVal));

                foreach ($paramArr as $paramVal) {
                    $paramVal = trim($paramVal);
                    $params[$paramVal] = $this->$paramVal;
                }
            }
            
            $content = $this->getTemp(
                $pathInfo['fromPath'].'/'.$tmpKey, $params
            );

            $ext = '';
            switch ($pathInfo['extension']) {
                case 'blade':
                    $fileName = str_replace(
                        'Temp', '', $tmpKey
                    );
                    $fileName = strtolower($fileName);
                    $ext = 'php';
                    break;
                
                default:
                    $fileName = str_replace(
                        'Temp', 'Component', $tmpKey
                    );
                    $ext = 'vue';
                    break;
            }
            
            $temps[] = [
                'content' => trim($content),
                'path' =>   $pathInfo['toPath'].
                            "{$lwModelPath}/{$fileName}.{$ext}",
            ];
        }

        return $temps;
    }

    protected function controllerClassTemp()
    {
        $content = "<?php\n\n".
                $this->namepsace().
                $this->requireFiles().
                $this->classFunc();

        return [
            'content' => $content,
            'path' => app_path(
                "Http/Controllers/{$this->controllerPath}.php"
            ),
        ];
    }    

    protected function classFunc()
    {
        $content = "\nclass {$this->controllerName} ".
                        "extends Controller\n{";

        $modalTmp = [
            'ConstructFunc' => '',
            'GetLangsFunc' => '',
            'IndexFunc' => 'modelPath',
            'GetDataListFunc' => '
                modelName|addLangFields|fieldIDName|fieldDependsOnLang
            ',
            'StoreFunc' => 'modelName|reqRulesName|langModelName
                |reqRulesUsePath',
            'ShowFunc' => 'modelName|modelVarName|modelUsePath',
            'EditFunc' => '
                modelUsePath|modelName|modelVarName|modelPath
                |fieldIDName|langModelName
            ',
            'UpdateFunc' => '
                modelName|modelVarName|reqRulesName|modelUsePath
                |langModelName|reqRulesUsePath
            ',
            'DestroyFunc' => '
                modelName|modelVarName|modelUsePath
            ',
        ];

        $advancedTmp = [
            'ConstructFunc' => '',
            'GetLangsFunc' => '',
            'IndexFunc' => 'modelPath',
            'GetDataListFunc' => '
                modelName|addLangFields|fieldIDName|fieldDependsOnLang
            ',
            'CreateFunc' => 'modelPath',
            'ShowFunc' => 'modelName|modelVarName|modelUsePath',
            'EditFunc' => '
                modelUsePath|modelName|modelVarName|modelPath
                |fieldIDName|langModelName
            ',
            'DestroyFunc' => '
                modelName|modelVarName|modelUsePath
            ',
            'AdvancedUpdateFunc' => '
                modelName|modelVarName|advancedReqRulesName|modelPath|
                fieldIDName|langModelName
            ',
            'AdvancedStoreFunc' => '
                modelName|modelVarName|advancedReqRulesName|modelPath|
                langModelName
            ',
        ];

        $allTmp = [
            'IndexFunc' => 'modelPath',
            'GetDataListFunc' => '
                modelName|addLangFields|fieldIDName|fieldDependsOnLang
            ',
            'CreateFunc' => 'modelPath',
            'StoreFunc' => 'modelName|reqRulesName|langModelName
                |reqRulesUsePath',
            'ShowFunc' => 'modelName|modelVarName|modelUsePath',
            'EditFunc' => '
                modelUsePath|modelName|modelVarName|modelPath
                |fieldIDName|langModelName
            ',
            'UpdateFunc' => '
                modelName|modelVarName|reqRulesName|modelUsePath
                |langModelName|reqRulesUsePath
            ',
            'DestroyFunc' => '
                modelName|modelVarName|modelUsePath
            ',
            'AdvancedUpdateFunc' => '
                modelName|modelVarName|advancedReqRulesName|modelPath|
                fieldIDName|langModelName
            ',
            'AdvancedStoreFunc' => '
                modelName|modelVarName|advancedReqRulesName|modelPath|
                langModelName
            ',
        ];

        $tmpFileNames = [
            'ConstructFunc' => '',
            'GetLangsFunc' => '',
        ];

        switch ($this->crudType) {
            case 'modal':
                $tmpFileNames = array_merge($tmpFileNames,$modalTmp);
                break;

            case 'advanced':
                $tmpFileNames = array_merge($tmpFileNames,$advancedTmp);
                break;
            
            case 'all':
                $tmpFileNames = array_merge($tmpFileNames,$allTmp);
                break;
        }

        if($this->crudType !== 'modal'){
            $tmpFileNames = array_merge($tmpFileNames, [
                'GetImagesFunc' => 'modelName|modelVarName',
                'UpdateImagesFunc' => '
                    modelName|modelVarName|imgReqRulesName
                ',
                'LoadImagesFunc' => 'modelName|modelVarName',
                'ConvertToCropFunc' => '',
                'SaveImageToStorageFunc' => '',
                'DeleteImageFromStorageFunc' => 'imgModelName',
            ]);
        }

        $content .= $this->getAllTemps(
            $tmpFileNames, 
            'ControllerClassTemps'
        );

        $content .= "}";

        return $content;
    }

    protected function getAllTemps($tmpFileNames, $path = '')
    {
        $tmp = '';
        foreach ($tmpFileNames as $tmpKey => $tmpVal) {

            $params = [];
            if($tmpVal){

                $paramArr = explode('|', trim($tmpVal));

                foreach ($paramArr as $paramVal) {
                    $paramVal = trim($paramVal);
                    $params[$paramVal] = $this->$paramVal;
                }
            }

            $tmpFilePath = $path.'/'.$tmpKey;
            $tmp .= $this->getTemp($tmpFilePath, $params);
        }
        return $tmp;
    }

    protected function getTemp($tempFileName, $params = null)
    {
        $func = require "Templates/{$tempFileName}.php";
        return $func($params);
    }

    protected function namepsace()
    {
        $contUsePath = explode('/', $this->controllerPath);
        
        if(count($contUsePath) > 1){
            array_pop($contUsePath);
            $contUsePath = implode('\\', $contUsePath);

            $namespace = "namespace App\Http\Controllers\\";
            $namespace .= "{$contUsePath};\n\n";
        }else{
            $namespace = "namespace App\Http\Controllers;\n\n";
        }

        return $namespace;
    }    

    private function requireFiles()
    {
        switch ($this->crudType) {
            case 'modal':
                $use = $this->modalUseFiles();
                break;

            case 'advanced':
                $use = $this->advancedUseFiles();
                break;
            
            default:
                $use = $this->allUseFiles();
                break;
        }

        if($this->imgModelName){
            $imgReqRules = $this->getUsePath($this->imgReqRules);
            $imgModelPath = $this->getUsePath($this->imgModelPath);

            $use .= "use App\Library\ImgFileUpload;\n".
                    "use App\Http\Requests\\{$imgReqRules};\n".
                    "use App\Models\\{$imgModelPath};\n".
                    "use Storage;\n";
        }

        if ($this->langModelName) {
            $prefix = $this->convertUseToPrefix($this->langModelUsePath);
            $use .= "use App\Models\\{$this->langModelUsePath};\n".
                    "use App\Models\\{$prefix}\Languages;\n";
        }

        return $use;
    }

    protected function modalUseFiles()
    {
        $reqRules = $this->ask(
            'Please enter a request rule for model name?',
            "{$this->modelPath}Request"
        );
        $this->setReqRules($reqRules);

        $reqRules = $this->getUsePath($this->reqRules);
        $modelPath = $this->getUsePath($this->modelPath);

        return  "use App\Http\Controllers\Controller;\n".
                "use App\Http\Requests\\{$reqRules};\n".
                "use App\Models\\{$modelPath};\n".
                "use App\Http\Responsable\isAjaxResponse;\n".
                "use Illuminate\Http\Request;\n";
    }

    protected function advancedUseFiles()
    {
        $advancedReqRules = $this->ask(
            'Please enter a request rule for model name?',
            "{$this->modelPath}Request"
        );
        $this->setAdvancedReqRules($advancedReqRules);

        $advancedReqRules = $this->getUsePath(
            $this->advancedReqRules
        );
        $modelPath = $this->getUsePath($this->modelPath);

        return  "use App\Http\Controllers\Controller;\n".
                "use App\Http\Requests\\{$advancedReqRules};\n".
                "use App\Models\\{$modelPath};\n".
                "use App\Http\Responsable\isAjaxResponse;\n".
                "use Illuminate\Http\Request;\n";
    }

    protected function allUseFiles()
    {
        $reqRules = $this->ask(
            'Please enter a request rule for model name?',
            "{$this->modelPath}Request"
        );
        $this->setReqRules($reqRules);

        $advancedReqRules = $this->ask(
            'Please enter a advanced request rule for model name?',
            "{$this->modelPath}AdvancedRequest"
        );
        $this->setAdvancedReqRules($advancedReqRules);

        $reqRules = $this->getUsePath($this->reqRules);
        $advancedReqRules = $this->getUsePath(
            $this->advancedReqRules
        );
        $modelPath = $this->getUsePath($this->modelPath);

        return  "use App\Http\Controllers\Controller;\n".
                "use App\Http\Requests\\{$reqRules};\n".
                "use App\Http\Requests\\{$advancedReqRules};\n".
                "use App\Models\\{$modelPath};\n".
                "use App\Http\Responsable\isAjaxResponse;\n".
                "use Illuminate\Http\Request;\n";
    }
}
