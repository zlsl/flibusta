#!/bin/sh
. /application/tools/dbinit.sh

if [ -z "$DB_HOST_PINGS" ]; then
   DB_HOST_PINGS=3
fi

for i in `seq 1 $DB_HOST_PINGS`
do
 ping -q -c 1 $FLIBUSTA_DBHOST
 EXITCODE=$?
 if [ $EXITCODE -eq 0 ]; then
  break
 fi
 if [ $i -lt $DB_HOST_PINGS ]; then
  sleep 1
 fi
done

if [ $EXITCODE -ne 0 ]; then
   echo $FLIBUSTA_DBHOST is unreachable, can not access DB, exiting
   exit 1
fi


if [ `$SQL_CMD -c "select 1 from pg_roles where rolname='flibusta'"  | wc -l` -eq 0 ]; then
    echo attempt to connect to postgres db has failed. Trying to initialize the DB

    FLIBUSTA_DBPASSWORD=$PGPASSWORD

    if [ -z "$POSTGRES_ADMIN_USER" ]; then
        POSTGRES_ADMIN_USER=postgres
    fi
    
    if [ ! -z "$POSTGRES_ADMIN_DBPASSWORD_FILE" ] && [ -e $POSTGRES_ADMIN_DBPASSWORD_FILE ]; then
        TPOSTGRES_ADMIN_PASSWD=`cat $POSTGRES_ADMIN_DBPASSWORD_FILE`
    fi
    if [ ! -z "$TPOSTGRES_ADMIN_PASSWD" ]; then
        PGPASSWORD=$TPOSTGRES_ADMIN_PASSWD
    fi
    
    if [ ! -z "$POSTGRES_ADMIN_PASSWD" ]; then
        PGPASSWORD=$POSTGRES_ADMIN_PASSWD
    fi
    
    if [ -z "$PGPASSWORD" ]; then
        echo Attempt to initialize postgres DB has failed. Cant obtain PG admin password
        exit 1
    fi

    psql -h $FLIBUSTA_DBHOST -d $POSTGRES_ADMIN_USER -U $POSTGRES_ADMIN_USER -v FLIBUSTA_DBUSER="$FLIBUSTA_DBUSER"  -v FLIBUSTA_DBPASSWORD="$FLIBUSTA_DBPASSWORD" -v FLIBUSTA_DBNAME="$FLIBUSTA_DBNAME" -f /application/tools/external_services_config/external_postgres_init.sql
    #restore flibusta password
    export PGPASSWORD=$FLIBUSTA_DBPASSWORD

    if [ `$SQL_CMD -c "select 1 from pg_roles where rolname='flibusta'"  | wc -l` -eq 0 ]; then
        echo "Can't connect to the DB after initialization attempt, exiting"
        exit 1
    fi
fi

exec php-fpm