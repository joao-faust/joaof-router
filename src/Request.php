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
    $data = filter_var_array($_GET, FILTER_SANITIZE_SPECIAL_CHARS);
    return $data;
  }

  private function buildFormData()
  {
    if (!isset($_SERVER['CONTENT_TYPE']))  {
      return [];
    }

    $contentTypeArr = explode(',', $_SERVER['CONTENT_TYPE']);

    foreach ($contentTypeArr as $contentType) {
      switch ($contentType) {
        case 'application/json':
          $json = file_get_contents('php://input');
          $data = json_decode($json, true);
          $filtered = filter_var_array($data, FILTER_SANITIZE_SPECIAL_CHARS);
          return $filtered;
        case 'application/x-www-form-urlencoded':
          $data = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
          return $data;
        default:
          return [];
      }
    }
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
