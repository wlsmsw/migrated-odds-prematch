#!/bin/bash
find . -type d -exec chmod 775 {} \;
find . -type f -exec chmod 644 {} \;
find . -type d -name \* -exec chmod 755 {} \;