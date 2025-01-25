RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y -o Dpkg::Options::='--force-confnew' --no-install-recommends \
      python3-full \
    && python3 -m venv pseudify \
    && pseudify/bin/pip install mkdocs mkdocs-static-i18n mkdocs-callouts mkdocs-glightbox pymdown-extensions
