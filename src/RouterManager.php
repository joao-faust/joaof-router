<?php

namespace joaof\router;

class RouterManager
{

  private array $routes = [
    'GET' => [],
    'POST' => []
  ];

  /**
   * @param string $httpMethod Http verb
   * @param string $uri Endpoint
   * @param string $class Controller class's namespace
   * @param string $method Instace's method
   */
  public function addRoute(
    string $httpMethod,
    string $uri,
    string $class,
    string $method
  ) {
    $this->routes[$httpMethod][$uri] = [
      'class' => $class,
      'method' => $method
    ];
  }

  public function getRoutes() {
    return $this->routes;
  }

  /**
   * @param string|null $notFoundViewPath Custom not found view's absolute path
   */
  public function start(string $notFoundViewPath=null)
  {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $uri = $url['path'];
    $httpMethod = $_SERVER['REQUEST_METHOD'];

    try {
      // check if the request method is invalid
      if (!array_key_exists($httpMethod, $this->routes)) {
        include(__DIR__ . '/views/invalidMethod.php');
        return;
      }

      $uris = $this->routes[$httpMethod];

      // check if the uri is invalid
      if (!array_key_exists($uri, $uris)) {
        if ($notFoundViewPath) {
          include($notFoundViewPath);
        } else {
          include(__DIR__ . '/views/invalidUri.php');
        }
        return;
      }

      $data = $uris[$uri];
      $class = $data['class'];

      // check if the class exist
      if (!class_exists($class)) {
        throw new \Exception("The namespace $class doesn't exist");
      }

      $method = $data['method'];
      $instance = new $class();

      // check  if the method exists
      if (!method_exists($instance, $method)) {
        throw new \Exception("The method $method doesn't exist in $class");
      }

      // build request
      $request = new Request($httpMethod);

      // call the method
      $instance->$method($request);
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }
}
