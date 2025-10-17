<?php
class Router {
    private $routes = [];
    
    public function add($route, $action, $method = 'GET|POST') {
        // Support multiple methods separated by |
        $methods = explode('|', $method);
        foreach ($methods as $m) {
            $this->routes[] = [
                'route' => $route,
                'action' => $action,
                'method' => strtoupper(trim($m))
            ];
        }
    }
    
    public function dispatch($uri, $method) {
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Handle URLs with project folder
        $projectPath = '/demo-pg-01-main';
        if (strpos($uri, $projectPath) === 0) {
            $uri = substr($uri, strlen($projectPath));
        }
        
        $uri = rtrim($uri, '/');
        if (empty($uri)) $uri = '/';
        
        // Debug logging
        error_log("Original URI: " . $_SERVER['REQUEST_URI'] . " | Processed URI: " . $uri);
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }
            
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['route']);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                
                list($controller, $action) = explode('@', $route['action']);
                
                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $action)) {
                        call_user_func_array([$controllerInstance, $action], $matches);
                        return;
                    }
                }
            }
        }
        
        throw new Exception('Route not found');
    }
}
