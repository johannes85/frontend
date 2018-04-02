<?php namespace web\frontend\delegates;

use lang\IllegalArgumentException;

class Delegate {
  private static $SOURCES;
  private $instance, $method;
  private $parameters= null;

  static function __static() {
    self::$SOURCES= [
      'value'   => function($req, $name) { return $req->value($name); },
      'cookie'  => function($req, $name) { return $req->cookie($name); },
      'header'  => function($req, $name) { return $req->header($name); },
      'param'   => function($req, $name) { return $req->param($name); },
      'default' => function($req, $name) { return $req->stream(); },
      'segment' => function($req, $name) { throw new IllegalArgumentException('Missing segment "'.$name.'"'); },
    ];
  }

  /**
   * Creates a new delegate
   *
   * @param  object $instance
   * @param  lang.reflect.Method $method
   */
  public function __construct($instance, $method) {
    $this->instance= $instance;
    $this->method= $method;
  }

  /** @return string */
  public function group() {
    $t= strtolower(get_class($this->instance));
    return false === ($p= strrpos($t, '\\')) ? $t : substr($t, $p + 1);
  }

  /** @return string */
  public function name() {
    return nameof($this->instance).'::'.$this->method->getName();
  }

  /**
   * Returns a map of named sources to read arguments from request. Lazily
   * initialized on first use.
   *
   * @return [:(function(web.Request, string): var)]
   */
  public function parameters() {
    if (null === $this->parameters) {
      $this->parameters= [];
      foreach ($this->method->getParameters() as $param) {
        if ($annotations= $param->getAnnotations()) {
          foreach ($annotations as $from => $value) {
            $source= isset(self::$SOURCES[$from]) ? self::$SOURCES[$from] : self::$SOURCES['default'];
          }

          $name= null === $value ? $param->getName() : $value;
          if ($param->isOptional()) {
            $default= $param->getDefaultValue();
            $this->parameters[$name]= function($req, $name) use($source, $default) {
              $r= $source($req, $name);
              return null === $r ? $default : $r;
            };
          } else {
            $this->parameters[$name]= $source;
          }
        } else {
          $this->parameters[$param->getName()]= self::$SOURCES['segment'];
        }
      }
    }
    return $this->parameters;
  }

  /**
   * Invokes this delegate
   *
   * @param  var[] $args
   * @return var
   * @throws lang.reflect.TargetInvocationException
   */
  public function invoke($args) {
    return $this->method->invoke($this->instance, $args);
  }
}