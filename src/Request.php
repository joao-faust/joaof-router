<?php

namespace joaof\router;

class Request
{

  private string $method;
  private array $params;
  private array $formData;

  /**
   * @param string $method Instance's method
   */
  public function __construct(string $method)
  {
    $this->method = $method;
    $this->params = $this->buildParams();
    $this->formData = $this->buildFormData();
  }

  private function buildParams()
  {
    $params = [];

    foreach ($_GET as $key => $param) {
      $params[$key] = htmlspecialchars($param);
    }

    return $params;
  }

  private function buildFormData()
  {
    $data = [];

    foreach ($_POST as $name => $inputVal) {
      $data[$name] = htmlspecialchars($inputVal);
    }

    return $data;
  }

  public function getMethod()  {
    return $this->method;
  }

  /**
   * @param string|null $paramName Param's name
   */
  public function getParams(string $paramName=null)
  {
    if (is_null($paramName)) {
      return $this->params;
    }
    return $this->params[$paramName];
  }

  /**
   * @param string|null $inputName Input's name
   */
  public function getFormData(string $inputName=null)
  {
    if (is_null($inputName)) {
      return $this->formData;
    }
    return $this->formData[$inputName];
  }
}
