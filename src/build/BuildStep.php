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

namespace HHVM\UserDocumentation;

abstract class BuildStep {
  abstract public function buildAll(): void;

  protected static function findSources(
    string $root,
    \ConstSet<string> $extensions,
  ): Vector<string> {
    $root = realpath($root);
    Log::i("\nFinding sources in $root");
    $rdi = new \RecursiveDirectoryIterator($root);
    $rii = new \RecursiveIteratorIterator(
      $rdi,
      \RecursiveIteratorIterator::CHILD_FIRST,
    );
    $files = Vector {};
    foreach ($rii as $info) {
      if (!$info->isFile()) {
        continue;
      }
      if ($extensions->contains($info->getExtension())) {
        $files[] = $info->getPathname();
        Log::v('.');
      }
    }
    return $files;
  }
}
