RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y -o Dpkg::Options::='--force-confnew' --no-install-recommends \
      python3-distutils \
    && curl https://bootstrap.pypa.io/get-pip.py -o /tmp/get-pip.py \
    && python3 /tmp/get-pip.py \
    && rm -f /tmp/get-pip.py \
    && pip3 install mkdocs mkdocs-static-i18n mkdocs-callouts pymdown-extensions
