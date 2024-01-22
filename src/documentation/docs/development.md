# Development

## Website

The source code of the website is located in the repository [pseudify-page](https://github.com/waldhacker/pseudify-page).  
To develop the website, the programme [ddev](https://ddev.readthedocs.io/en/stable/) is needed.  

### Launch the website

```shell
ddev start
ddev launch
```

### Building the website

```shell
# 0.1 = current version
ddev exec ../build/build.sh 0.1
```

## Core

The source code of pseudify is located in the repository [pseudify-core](https://github.com/waldhacker/pseudify-core).  
To develop pseudify, the programme [ddev](https://ddev.readthedocs.io/en/stable/) is needed.  

### Building the PHAR Archive

```shell
docker run -it -v $(pwd):/data --workdir=/data \
  php:8.1-cli-alpine \
    sh -c '\
      apk update \
      && apk add bash rsync \
      && /bin/bash -c "build/build-phar.sh" \
      && chown -R '$(id -u)':'$(id -g)' .build/ \
    '
```

### Building the docker image

This step requires the execution of the commands under "Building the PHAR archive" as preliminary work.

```shell
./build/build-docker.sh
```

### Building pseudify

```shell
ddev start
ddev exec composer install

docker run -it -v $(pwd)/build/development/userdata/:/app -u $(id -u):$(id -g) \
  composer create-project waldhacker/pseudify-profile-templates --stability=dev --remove-vcs

cp build/development/userdata/pseudify-profile-templates/tests/mariadb/10.5/.env build/development/userdata/pseudify-profile-templates/.env

ddev exec 'rm -f ~/.pgpass ~/.my.cnf && mariadb -h mariadb_10_5 -uroot -p"pseudify(!)w4ldh4ck3r" mysql < ../build/development/userdata/pseudify-profile-templates/tests/mariadb/10.5/pseudify_utf8mb4.sql'

ddev exec bin/pseudify pseudify:debug:table_schema
```
