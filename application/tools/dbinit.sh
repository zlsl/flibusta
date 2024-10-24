#!/bin/sh

if [ -z "$FLUBUSTA_DBNAME" ]; then
export FLUBUSTA_DBNAME=flibusta
fi

if [ -z "$FLUBUSTA_DBHOST" ]; then
export FLUBUSTA_DBHOST=postgres
fi

if [ -z "$FLIBUSTA_DBUSER" ]; then
export FLIBUSTA_DBUSER=flibusta
fi

if [ -z "$FLIBUSTA_DB_TYPE" ] || [ "$FLIBUSTA_DB_TYPE" == 'postgres' ]; then
export SQL_CMD="psql -h $FLUBUSTA_DBHOST  -d $FLUBUSTA_DBNAME -U $FLIBUSTA_DBUSER"
export SQL_PASSWORD_VAR=PGPASSWORD

if [ ! -z "$FLIBUSTA_DBPASSWORD_FILE" ] && [ -e $FLIBUSTA_DBPASSWORD_FILE ]; then
FPGPASSWORD=`cat $FLIBUSTA_DBPASSWORD_FILE`
fi

if [ ! -z "$FPGPASSWORD" ]; then
export PGPASSWORD=$FPGPASSWORD
fi

if [ -z "$PGPASSWORD" ]; then
export PGPASSWORD=flibusta
fi
fi

