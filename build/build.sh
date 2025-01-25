#!/bin/bash

# **********************************************
# @version 0.0.1
# @author Ralf Zimmermann <hello@waldhacker.dev>
# **********************************************

if [ "$BASH" = "" ]; then echo "Error: you are not running this script within the bash."; exit 1; fi
if [ ! -x "$(command -v /pseudify/bin/mkdocs)" ]; then echo "Error: mkdocs is not installed."; exit 1; fi
if [ ! -x "$(command -v php)" ]; then echo "Error: php is not installed."; exit 1; fi
if [ ! -x "$(command -v yarn)" ]; then echo "Error: yarn is not installed."; exit 1; fi

_THIS_SCRIPT_REAL_PATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
_ROOT_DIRECTORY="${_THIS_SCRIPT_REAL_PATH}/.."

function prepare
{
    echo "[BUILD]: prepare"

    cd "$_ROOT_DIRECTORY/src/"
    composer install
    yarn install
}

function build
{
    local CURRENT_DOCS_VERSION=${1:?}

    cd "$_ROOT_DIRECTORY/src/documentation"
    /pseudify/bin/mkdocs build
    cd "$_ROOT_DIRECTORY/src/public/docs/"
    ln -snf "$CURRENT_DOCS_VERSION" current

    cd "$_ROOT_DIRECTORY/src/"
    yarn encore production

    bin/console dump-static-site --output-directory ../dist
    cd "$_ROOT_DIRECTORY/dist/docs/"
    rm -f current
    ln -snf "$CURRENT_DOCS_VERSION" current
    cd "$_ROOT_DIRECTORY/dist/docs/current/"
    sed -i 's#/docs/[0-9]\+\.[0-9]\+/#/docs/current/#g' sitemap.xml
    gzip -kf sitemap.xml
}

function cleanup
{
    echo "[BUILD]: cleanup"
}

function controller
{
    prepare
    build "$@"
    cleanup
}

controller "$@"

unset _THIS_SCRIPT_REAL_PATH
unset _ROOT_DIRECTORY
