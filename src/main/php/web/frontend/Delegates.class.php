<?php namespace web\frontend;

use lang\IllegalArgumentException;

/**
 * Matches request and routes to correct delegate
 */
class Delegates {
  public $patterns= [];

  /**
   * Routes to instance methods based on annotations
   *
   * @param  object $instance
   * @return self
   * @throws lang.IllegalArgumentException
   */
  public function with($instance) {
    if (!is_object($instance)) {
      throw new IllegalArgumentException('Expected an object, have '.typeof($instance));
    }

    foreach (typeof($instance)->getMethods() as $method) {
      $name= $method->getName();
      foreach ($method->getAnnotations() as $verb => $segment) {
        $pattern= $segment
          ? preg_replace(['/\{([^:}]+):([^}]+)\}/', '/\{([^}]+)\}/'], ['(?<$1>$2)', '(?<$1>[^/]+)'], $segment)
          : '.+'
        ;
        $this->patterns['#'.$verb.$pattern.'$#']= new Delegate($instance, $method);
      }
    }
    return $this;
  }

  /**
   * Returns target for a given HTTP verb and path
   *
   * @param  string $verb
   * @param  string $path
   * @return web.frontend.Delegate or NULL
   */
  public function target($verb, $path) {
    $match= $verb.$path;
    foreach ($this->patterns as $pattern => $delegate) {
      if (preg_match($pattern, $match, $matches)) return [$delegate, $matches];
    }
    return null;
  }
}