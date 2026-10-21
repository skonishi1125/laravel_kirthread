#!/usr/bin/env bash

set -euo pipefail

GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

run_php_tool() {
    if php -r 'exit(PHP_VERSION_ID >= 80500 ? 0 : 1);' 2>/dev/null; then
        "$@"
        return
    fi

    if command -v docker >/dev/null 2>&1 && docker compose version >/dev/null 2>&1; then
        docker compose run --rm --no-deps app "$@"
        return
    fi

    echo -e "${RED}PHP 8.5以上、またはDocker Composeが必要です。${NC}" >&2
    exit 1
}

echo -e "${GREEN}Running Pint...${NC}"
run_php_tool ./vendor/bin/pint

echo -e "${GREEN}Running PHPStan...${NC}"
run_php_tool ./vendor/bin/phpstan analyse --memory-limit=2G

echo -e "${GREEN}Code check completed!${NC}"
