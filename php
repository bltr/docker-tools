#! /usr/bin/env bash

if [[ -f docker-compose.yml ]]; then
    docker compose exec php php "$@"
    exit
fi

if [[ -f .env ]]; then
    source .env
    USE_ENV="--env-file .env"
fi

if [[ -z "$I" ]]; then
	I=php
fi

if [[ -z "$V" ]]; then
	V=alpine
fi

if [[ -n "$P" ]]; then
    P="--publish ${P}"
fi

test -t 1 && USE_TTY="--tty"

docker run --rm --interactive ${USE_TTY} ${USE_ENV} ${P} \
    --user `id -u`:`id -g` \
    --volume "$PWD":/app \
    --workdir /app \
    ${I}:${V} php "$@"
