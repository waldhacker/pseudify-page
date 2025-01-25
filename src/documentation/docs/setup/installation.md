# Installation

Pseudify can be used in 2 variants that offer different features:

* Variant 1: The `pseudonymization setup` intended for continuous integration/continuous delivery (CI/CD) systems. This variant can only run pseudonymization tasks.
* Variant 2: The `analysis setup` that offers you a GUI for modeling the pseudonymization profile and additionally a locally hosted AI model for advanced PII detection.

## The pseudonymization setup

!!! info "Dependencies"
    The following components must be installed:

    * [docker](https://docs.docker.com/get-docker/)

### Get the install package

Go to some empty directory.  
Download the ["install package"](https://github.com/waldhacker/pseudify-ai/releases/latest/) and unpack the `userdata` in the current directory:  

```shell
$ docker run --rm -it -v "$(pwd)":/install -w /install -u $(id -u):$(id -g) alpine/curl /bin/sh -c "\
    curl -fsSL https://github.com/waldhacker/pseudify-ai/releases/latest/download/install-package.tar.gz -o install-package.tar.gz \
    && tar -xzf ./install-package.tar.gz ./userdata \
    && rm -f ./install-package.tar.gz \
"
```

### Run pseudify

Now you can test whether pseudify is running correctly:

```shell
$ docker run --rm -it -v "$(pwd)/userdata/":/opt/pseudify/userdata/ \
    ghcr.io/waldhacker/pseudify-ai:2.0 pseudify:information
```

If you need to know how to manage database access look at the [configuration](configuration.md#manage-database-access) chapter.


## The analyze setup

!!! warning "For local use only"
    The GUI is designed as as a single user app and intended for local usage on a development machine.
    This is important for security reasons.
    **NEVER EVER** host the analyze setup on a publicly accessible server.

!!! info "Dependencies"
    The following components must be installed:

    * [docker](https://docs.docker.com/get-docker/)
    * [docker compose](https://docs.docker.com/compose/install/)

    If you want to use the AI support, you need to install:

    * [CUDA drivers](https://docs.nvidia.com/cuda/cuda-installation-guide-linux/)
    * [NVIDIA Container Toolkit](https://docs.nvidia.com/datacenter/cloud-native/container-toolkit/latest/install-guide.html)

### Get the install package

Go to some empty directory.  
Download the ["install package"](https://github.com/waldhacker/pseudify-ai/releases/latest/) and unpack it in the current directory:  

```shell
$ docker run --rm -it -v "$(pwd)":/install -w /install -u $(id -u):$(id -g) alpine/curl /bin/sh -c "\
    curl -fsSL https://github.com/waldhacker/pseudify-ai/releases/latest/download/install-package.tar.gz -o install-package.tar.gz \
    && tar -xzf ./install-package.tar.gz \
    && rm -f ./install-package.tar.gz \
"
```

### Start pseudify without AI support:

```shell
$ docker compose up -d
```

### Start pseudify with AI support:

```shell
$ docker compose -f docker-compose.yml -f docker-compose.llm-addon.yml up -d
$ docker exec -it pseudify_ollama bash -c 'ollama pull $OLLAMA_MODEL'
```

### Launch the GUI

Now go to your browser an open [http://127.0.0.1:9669](http://127.0.0.1:9669) to access the GUI.  

If you need to know how to manage database access look at the [configuration](configuration.md#manage-database-access) chapter.

### Shutdown (without AI support):

```shell
$ docker compose down
```

### Shutdown (with AI support):

```shell
$ docker compose -f docker-compose.yml -f docker-compose.llm-addon.yml down
```
