Web frontends change log
========================

## ?.?.? / ????-??-??

## 0.7.0 / 2018-11-02

* Merged PR #8: Request in templates - @johannes85, @thekid

## 0.6.0 / 2018-10-19

* Added possibility to inject request by using `request` as parameter
  annotation
  (@johannes85, @thekid)

## 0.5.0 / 2018-10-10

* Merged PR #6: Allows to throw web.Error in handler - @johannes85

## 0.4.1 / 2018-04-29

* Fixed patterns to always be applied in order of their length, longest
  patterns first
  (@thekid)

## 0.4.0 / 2018-04-29

* Merged PR #5: Delegates; adding shorthand alternative to manually
  entering all routes
  (@thekid)
* Added support for patterns in path segments, e.g. `/users/{id:[0-9]+}`
  (@thekid)

## 0.3.1 / 2018-04-29

* Fixed issue #3: Two named subpatterns have the same name - @thekid

## 0.3.0 / 2018-04-10

* Changed dependency on `xp-forge/web` to version 1.0.0 since it has
  been released
  (@thekid)

## 0.2.0 / 2018-04-03

* Changed parameter annotations parsing to no longer be performed for
  every request, instead lazily initialize on first use; then cache.
  See https://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html
  (@thekid)
* Made HTTP response headers controllable via `View::header()` - @thekid
* Made HTTP response status controllable via `View::status()` - @thekid

## 0.1.0 / 2018-04-02

* Hello World! First release - @thekid