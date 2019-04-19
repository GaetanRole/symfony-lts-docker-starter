FROM    node:alpine

LABEL   maintainer="Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>"

RUN     apk add --no-cache su-exec && \
        addgroup bar && \
        adduser -D -h /home -s /bin/sh -G bar foo

# Entrypoint
COPY    ./bin/entrypoint.sh /usr/bin/entrypoint
ENTRYPOINT ["/usr/bin/entrypoint"]
