# Entwicklung

## Website

Der Quellcode der Webseite befindet sich im Repository [pseudify-page](https://github.com/waldhacker/pseudify-page).  
Um die Webseite zu entwickeln, wird das Programm [ddev](https://ddev.readthedocs.io/en/stable/) benötigt.  

### Starten der Webseite

```shell
ddev start
ddev launch
```

### Bauen der Webseite

```shell
# 0.1 = current version
ddev exec ../build/build.sh 0.1
```

## Core

Der Quellcode von pseudify befindet sich im Repository [pseudify-core](https://github.com/waldhacker/pseudify-core).  
Um pseudify zu entwickeln wird das Programm [ddev](https://ddev.readthedocs.io/en/stable/) benötigt.  

### Das PHAR Archiv bauen

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

### Das docker image bauen

Dieser Schritt benötigt als Vorarbeit die Ausführung der Befehle unter "Das PHAR Archiv bauen".

```shell
./build/build-docker.sh
```

### Bauen von pseudify

```shell
ddev start
ddev exec composer install

docker run -it -v $(pwd)/build/development/userdata/:/app -u $(id -u):$(id -g) \
  composer create-project waldhacker/pseudify-profile-templates --stability=dev --remove-vcs

cp build/development/userdata/pseudify-profile-templates/tests/mariadb/10.5/.env build/development/userdata/pseudify-profile-templates/.env

ddev exec 'rm -f ~/.pgpass ~/.my.cnf && mariadb -h mariadb_10_5 -uroot -p"pseudify(!)w4ldh4ck3r" pseudify_utf8mb4 < ../build/development/userdata/pseudify-profile-templates/tests/mariadb/10.5/pseudify_utf8mb4.sql'

ddev exec bin/pseudify pseudify:debug:table_schema
```
