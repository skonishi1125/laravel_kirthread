#!/bin/bash

GREEN='\033[0;32m'
NC='\033[0m' # No Color

echo -e "${GREEN}Running Pint...${NC}"
./vendor/bin/pint

echo -e "${GREEN}Running PHPStan...${NC}"
./vendor/bin/phpstan analyse --memory-limit=2G

echo -e "${GREEN}Code check completed!${NC}"
