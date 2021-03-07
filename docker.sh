#!/usr/bin/env bash

set -e

. ./.env

CONTAINER_APPLICATION="app"

do_exec() {
  FPM_RUN_USER=${FPM_RUN_USER:-www-data}
  FPM_RUN_GROUP=${FPM_RUN_GROUP:-www-data}

  docker-compose exec -T --user="${FPM_RUN_USER}:${FPM_RUN_GROUP}" "${CONTAINER_APPLICATION}" "$@"
}

do_init() {
  do_exec printf "Composer: Add github token..."
  do_exec composer config -g github-oauth.github.com "${GITHUB_TOKEN}"
  do_exec printf "Done\n"

  do_exec printf "Composer: Install requirements..."
  do_exec composer install "$@" --prefer-dist --quiet --no-interaction
  do_exec printf "Done\n"
}

do_install() {
  do_exec echo 'install'
}

do_update() {
  do_exec echo 'update'
}

case "$1" in

init)
  shift
  do_init "$@"
  ;;

doctrine)
  shift
  do_exec ./vendor/bin/doctrine "$@"
  ;;

migrations)
  shift
  do_exec ./vendor/bin/doctrine-migrations "$@"
  ;;

exec)
  shift
  do_exec "$@"
  ;;

install)
  shift
  do_install
  ;;

update)
  shift
  do_update
  ;;

*)
  echo "Usage: docker.sh [init|doctrine|migrations|exec|install|update]"
  ;;

esac
