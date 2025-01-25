# Development

## Website

The source code of the website is located in the repository [pseudify-page](https://github.com/waldhacker/pseudify-page).  
To develop the website, the programme [ddev](https://ddev.readthedocs.io/en/stable/) is needed.  

### Launch the website

```shell
$ ddev start
$ ddev exec composer install
$ ddev launch
```

### Building the website

```shell
# 1.0 = current version
$ ddev exec ../build/build.sh 1.0
```

## Core

The source code of pseudify is located in the repository [pseudify](https://github.com/waldhacker/pseudify-ai).  
To develop pseudify, the programme [docker compose](https://docs.docker.com/compose/) is needed.  

### Build the pseudify docker image

```shell
$ ./build/dev/build-docker.sh
```

### Start pseudify development environment

```shell
$ docker compose -f docker-compose.yml -f docker-compose.llm-addon.yml -f docker-compose.dev.yml -f docker-compose.test.yml up -d
$ ./build/dev/install.sh
```

### Import testdata

```shell
$ ./tests/import.sh
```

### Run the pseudify development environment

You can access the GUI in your browser with the url [`http://127.0.0.1:9669`](http://127.0.0.1:9669).

Run the commandline tool like this:

```shell
$ docker exec -it pseudify bin/pseudify pseudify:debug:table_schema
```
