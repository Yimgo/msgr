<?php

function render_partial($name, $params)
{
    require_once "app/views/partials/" . $name . ".php";
}

function link_to_css($name)
{
   echo "<LINK href=\"/static/css/" . $name . "\" rel=\"stylesheet\" type=\"text/css\">";
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
    
    function render_view($view_name, $params)
    {
        // Include app/views/classname/view_name.php
	$classname = get_class($this);
	require_once "app/views/" . lcfirst(substr($classname, 0, strlen($classname)-10)) . "/" . $view_name . ".php";
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
