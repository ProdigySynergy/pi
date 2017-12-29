<?php
/**
* 
*/
class Bootstrap
{
    
    private $_url = null,
            $_controller = null,
            $_defaultFile = 'index.php';

    public static $_errorFile = '404.php';

    public $model, $param = null;

    /**
     * Starts the Bootstrap
     * 
     * @return boolean
     */
    public function init()
    {
        // Sets the protected $_url
        $this->_getUrl();

        // Load the default controller if no URL is set
        // eg: Visit http://localhost it loads Default Controller
        if (empty($this->_url[0]) || (!isset($this->_url[0])) || $this->_url[0] === "" ) {
            $this->_loadDefaultController();
            return false;
        }

        $this->_loadExistingController();
        $this->_callControllerMethod();
    }

    /**
     * Fetches the $_GET from 'url'
     */
    private function _getUrl()
    {
        //Routing
        if (isset($_GET['route'])) {
            $url = strtolower($_GET['route']);
            $url = rtrim($url, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $this->_url = explode('/', $url);
        } else {
            $this->_url = NULL;
        }
    }
    
    /**
     * This loads if there is no GET parameter passed
     */
    private function _loadDefaultController()
    {
        require ROOT . DS . 'application' . DS . 'Controllers' . DS . $this->_defaultFile;
        $this->_controller = new Index();
        $this->_controller->index();
    }
    
    /**
     * Load an existing controller if there IS a GET parameter passed
     * 
     * @return boolean|string
     */
    private function _loadExistingController()
    {
        $file = ROOT . DS . 'application' . DS .  'Controllers' . DS . $this->_url[0] . '.php';
        
        if (file_exists($file)) {
            require $file;
            $newclassname = preg_replace('#[-_]#i', "", $this->_url[0]);
            $this->_controller = new $newclassname();
            // $this->_controller->loadModel($this->_url[0]);
        } else {
            // Send the value as a param into index
            require ROOT . DS . 'application' . DS .  'Controllers' . DS .'index.php';
            $this->_controller = new Index();
            $method = new ReflectionMethod('Index', 'index');
            $param = $method->getParameters();
            if (isset($this->_url[0]) && method_exists('Index', $this->_url[0]))
            {
                array_unshift($this->_url, 'index');
                $this->_controller = new Index();
                $this->_callControllerMethod();
            }
            else if ( $param )
            {
                //Echo from index class as param
                $this->_controller->index($this->_url[0]);
                return false;
            }
            else
            {
                self::_error();
                return false;
            }
        }
    }
    
    /**
     * If a method is passed in the GET url paremter
     * 
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function _callControllerMethod()
    {
        $length = count($this->_url);
        
        $this->param = array();
        $this->theurls = $this->_url;
        unset($this->theurls[0]);
        $meth = preg_replace('#[-]#i', "_", @$this->theurls[1]);

        for ($ct = 1; $ct < (count($this->theurls) + 1); $ct++) {
            if(isset($this->theurls[$ct]) && $this->theurls[$ct] != null) {
                if (method_exists($this->_controller, $meth)) {
                    $length = count($this->theurls) + 1;
                    continue;
                }
                else {
                    $length = 0;
                    $this->param[] = $this->theurls[$ct];
                }
            }
        }

        $met = preg_replace('#[-]#i', "_", @$this->_url[1]); // Convert page-name to page_name for method
        
        // Determine what to load
        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$met}($this->_url[2], $this->_url[3], $this->_url[4]);
                break;
            
            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$met}($this->_url[2], $this->_url[3]);
                break;
            
            case 3:
                //Controller->Method(Param1)
                $this->_controller->{$met}($this->_url[2]);
                break;
            
            case 2:
                //Controller->Method()
                $this->_controller->{$met}();
                break;
            
            default:
                if (count($this->param) > 0) {
                    call_user_func_array(array($this->_controller, 'index'), $this->param);
                } else {
                    $this->_controller->index($this->param);
                }
                break;
        }
    }
    
    /**
     * Display an error page if nothing exists
     * 
     * @return boolean
     */
    public static function _error() {
        $page = "Error page";
        require ROOT . DS . 'application' . DS .  'views' . DS . 'inc' . DS . 'error' . DS . self::$_errorFile;
        //$this->_controller = new Error();
        //$this->_controller->index();
        exit;
    }
    
}