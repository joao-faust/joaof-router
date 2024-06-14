<?php

namespace joaof\router;

class Router
{

  private RouterManager $manager;
  private string $prefix;
  private string $class;

  /**
   * @param RouterManager $manager Router manager
   * @param string $class Controller class's namespace
   * @param string $prefix Route prefix
   */
  public function __construct(
    RouterManager $manager,
    string $class,
    string $prefix = ''
  ) {
    $this->manager = $manager;
    $this->class = '\\' . $class;
    $this->prefix = $prefix;
  }

  /**
   * @param string $uri Endpoint
   */
  private function formatUri(string $uri)
  {
    if ($uri === '') {
      return '/' .  $this->prefix;
    }
    return '/' . $this->prefix . '/' . $uri;
  }

  /**
   * @param string $class Controller class's namespace
   */
  private function defineclass($class)
  {
    if (is_null($class)) {
      return $this->class;
    }
    return $class;
  }

  /**
   * @param string $uri Endpoint
   * @param string $method Instance's method
   * @param string|null $class Custom class's namespace
   */
  public function get(string $uri, string $method, string $class=null)
  {
    $this->manager->addRoute(
      'GET',
      $this->formatUri($uri),
      $this->defineclass($class),
      $method
      );
  }

  /**
   * @param string $uri Endpoint
   * @param string $method Instance's method
   * @param string|null $class Custom class's namespace
   */
  public function post(string $uri, string $method, string $class=null)
  {
    $this->manager->addRoute(
      'POST',
      $this->formatUri($uri),
      $this->defineclass($class),
      $method
    );
  }

  /**
   * @param string $uri Endpoint
   * @param string $method Instance's method
   * @param string|null $class Custom class's namespace
   */
  public function route(string $uri, string $method, string $class=null)
  {
    $this->get($uri, $method);
    $this->post($uri, $method);
  }
}
