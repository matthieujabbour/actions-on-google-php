<?php

  /**
   * Copyright 2017 Google Inc. All Rights Reserved.
   *
   * Licensed under the Apache License, Version 2.0 (the 'License');
   * you may not use this file except in compliance with the License.
   * You may obtain a copy of the License at
   *
   *    http://www.apache.org/licenses/LICENSE-2.0
   *
   * Unless required by applicable law or agreed to in writing, software
   * distributed under the License is distributed on an "AS IS" BASIS,
   * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   * See the License for the specific language governing permissions and
   * limitations under the License.
   */


  namespace ActionsOnGooglePHP;


  // Enable actions-on-google debug logging
  // process.env.DEBUG = 'actions-on-google:*';

  // lodash helpers
  $camelCase = function($string) {
    $string = str_replace('-', ' ', $string);
    $string = str_replace('_', ' ', $string);
    $string = ucwords(strtolower($string));
    $string = str_replace(' ', '', $string);
    $string = lcfirst($string);
    return $string;
  };
  $snakeCase = function($string) {
    $string = str_replace('-', ' ', $string);
    $string = str_replace('_', ' ', $string);
    $string = preg_replace('/([A-Z])/', ' $1', $string);
    $string = strtolower($string);
    $string = str_replace(' ', '_', $string);
    return $string;
  };

  /**
   * Transforms incoming object to new camelCase-keyed object.
   *
   * @example
   * const snakeCaseObject = {
   *   key_one: {
   *     key_two: [
   *       {
   *         key: 'value'
   *       },
   *       'array_item_two'
   *     ]
   *   }
   * };
   * let camelCaseObject = transformToCamelCase(snakeCaseObject);
   * // camelCaseObject === {
   * //   keyOne: {
   * //     keyTwo: [
   * //       {
   * //         key: 'value'
   * //       },
   * //       'array_item_two'
   * //     ]
   * //   }
   * // };
   *
   * @param {Object} object Object to transform.
   * @return {Object} Incoming object deeply mapped to camelCase keys.
   */
  function transformToCamelCase ($object) {
    global $camelCase;
    return transform($object, $camelCase);
  }

  /**
   * Transforms incoming object to new snake_case-keyed object.
   *
   * @example
   * const camelCaseObject = {
   *   keyOne: {
   *     keyTwo: [
   *       {
   *         key: 'value'
   *       },
   *       'arrayItemTwo'
   *     ]
   *   }
   * };
   * let snakeCaseObject = transformToSnakeCase(camelCaseObject);
   * // snakeCaseObject === {
   * //   key_one: {
   * //     key_two: [
   * //       {
   * //         key: 'value'
   * //       },
   * //       'arrayItemTwo'
   * //     ]
   * //   }
   * // };
   *
   * @param {Object} object Object to transform.
   * @return {Object} Incoming object deeply mapped to camelCase keys.
   */
  function transformToSnakeCase ($object) {
    global $snakeCase;
    return transform($object, $snakeCase);
  }

  /**
   * Generic deep object transformation utility. Recursively converts all object
   * keys, including those of array elements, with some transformation function.
   * Note that classes will get converted to objects.
   *
   * @param {Object} object Object to transform.
   * @param {Function} keyTransformation Function that applies the desired transformation.
   * @return {Object} The transformed object.
   */
  function transform ($object, $keyTransformation) {
    $newObject = $object;
    if (is_array($object)) {
      $newObject = [];
      foreach ($object as $key => $value) {
        $newObject[call_user_func($keyTransformation, $key)] = transform($value, $keyTransformation);
      }
    }
    return $newObject;
  }
