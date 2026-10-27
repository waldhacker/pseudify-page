# Vendored Symplify path packages

The upstream GitHub repositories for these abandoned Symplify packages no longer
serve the Dist-ZIP URLs pinned by Packagist (HTTP 404). Composer therefore
cannot install `symplify/symfony-static-dumper` and its transitive Symplify
dependencies from the public network.

This directory holds the last usable package trees and is registered in
`composer.json` as path repositories so `composer install` works offline for
those packages.
