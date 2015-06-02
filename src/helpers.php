<?php
use Deefour\Authorizer\Authorizer;
use Deefour\Authorizer\Contracts\Authorizable;
use Deefour\Authorizer\Contracts\ResolvesAuthorizable;
use Deefour\Authorizer\Contracts\Scopeable;
use Deefour\Authorizer\Exceptions\NotScopeableException;
use Illuminate\Database\Eloquent\Builder;

if ( ! function_exists('policy')) {
  /**
   * Retrieve policy class for the passed object. The argument can be a FQCN
   * or an identifier that can be resolved through Laravel's service container.
   *
   * If the resolved class itself is not authorizable but implements the
   * ResolvesAuthorizable contract, resolution will be performed by calling through
   * to that method.
   *
   * @param  Authorizable|string $object
   *
   * @return array
   */
  function policy($object) {
    $authorizer = app('authorizer');

    if (is_string($object)) {
      $object = app($object);
    }

    if ($object instanceof ResolvesAuthorizable) {
      $object = $object->resolveAuthorizable();
    }

    return $authorizer->policy($object);
  }
}

if ( ! function_exists('scope')) {
  /**
   * Retrieve a scoped query for the passed object. The argument can be a FQCN,
   * an identifier that can be resolved through Laravel's service container, or
   * an Eloquent query builder instance.
   *
   * The resulting object must implement the Scopeable interface. If a query builder
   * instance is received, the model class will be fetched from it.
   *
   * If the original object passed is Scopeable, the 'base scope' will be pulled
   * from that object as the root of the query.
   *
   * A query builder instance will be returned.
   *
   * @param  Scopeable|string $object
   *
   * @return Builder
   * @throws NotScopeableException
   */
  function scope($object) {
    $scope      = $object;
    $authorizer = app('authorizer');

    if (is_string($object)) {
      $object = app($object);
    } elseif ($object instanceof Builder) {
      $object = $object->getModel();
    }

    if ( ! ($object instanceof Scopeable)) {
      throw new NotScopeableException(sprintf(
        'A $scope must be passed to the scope() helper when $object doesn\'t ' .
        'implement [%s]. The $object passed was [%s].',
        Scopeable::class,
        get_class($object)
      ));
    }

    if ($object === $scope) {
      $scope = $object->baseScope();
    }

    return $authorizer->scope($object, $scope);
  }
}

if ( ! function_exists('authorizer')) {
  /**
   * Resolve an implementation of the authorization manager from Laravel's service
   * container.
   *
   * @return Authorizer
   */
  function authorizer() {
    return app('authorizer');
  }
}