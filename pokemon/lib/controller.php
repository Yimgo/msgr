<?php

function render_partial($name, $params)
{
    require_once "app/views/partials/".$name.".php";
}

function link_to_css($name)
{
   echo "<link rel=\"stylesheet\" href=\"/pokemon/static/css/".$name.".css\" />\n";
}

class BaseController
{
    function __construct()
    {
        session_start();
    }
    
    function clear_session()
    {
        $_SESSION = array();
        session_destroy();
    }
    
    function session_set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    function session_get($key, $default)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        else
        {
            return $default;
        }
    }
    
    function session_unset_var($key)
    {
		unset($_SESSION[$key]);
	}
    
    function render_view($view_name, $params)
    {
		$controller_name=explode("controller",strtolower(get_class($this)));
        require_once "app/views/".$controller_name[0]."/".$view_name.".php";
    }
    
    function render_error($http_status, $message, $context_map)
    {
        require_once "static/html/error.php";
    }
    
    function redirect_to($url)
    {
        header("Location: $url");
        exit;
    }
}

?>
