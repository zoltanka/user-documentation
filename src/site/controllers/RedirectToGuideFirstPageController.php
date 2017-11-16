<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

use Facebook\HackRouter\SupportsGetRequests;
use HHVM\UserDocumentation\GuidesIndex;
use HHVM\UserDocumentation\GuidesProduct;
use HHVM\UserDocumentation\URLBuilder;
use Psr\Http\Message\ResponseInterface;

final class RedirectToGuideFirstPageController
extends WebController implements RoutableGetController {
  use RedirectToGuideFirstPageControllerParametersTrait;
  
  public static function getUriPattern(): UriPattern {
    return (new UriPattern())
      ->literal('/')
      ->guidesProduct('Product')
      ->literal('/')
      ->string('Guide')
      ->literal('/');
  }

  public async function getResponse(): Awaitable<ResponseInterface> {
    $params = $this->getParameters();
    $product = GuidesProduct::assert($params['Product']);
    $guide = $params['Guide'];
    $path = self::invariantTo404(() ==> {
      $pages = GuidesIndex::getPages($product, $guide);
      $page = $pages[0];
      return URLBuilder::getPathForGuidePage($product, $guide, $page);
    });
    throw new RedirectException($path);
  }
}
