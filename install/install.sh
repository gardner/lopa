#!/bin/bash

here="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
if [ -f "$here/../db.ini" ]; then
    source "$here/../db.ini"
    $mysql_options="-u $username --password=$password"
fi

mysqladmin $mysql_options create lopa
mysql $mysql_options lopa < "$here/lopa.sql"

