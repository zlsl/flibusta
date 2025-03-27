#!/bin/sh

if [ -z "$FLIBUSTA_DBNAME" ]; then
  export FLIBUSTA_DBNAME=flibusta
fi

if [ -z "$FLIBUSTA_DBHOST" ]; then
  export FLIBUSTA_DBHOST=postgres
fi

if [ -z "$FLIBUSTA_DBUSER" ]; then
  export FLIBUSTA_DBUSER=flibusta
fi

if [ -z "$FLIBUSTA_DBTYPE" ] || [ "$FLIBUSTA_DBTYPE" == 'postgres' ]; then
  export SQL_CMD="psql -h $FLIBUSTA_DBHOST -d $FLIBUSTA_DBNAME -U $FLIBUSTA_DBUSER"
  export SQL_PASSWORD_VAR=PGPASSWORD

  if [ ! -z "$FLIBUSTA_DBPASSWORD_FILE" ] && [ -e $FLIBUSTA_DBPASSWORD_FILE ]; then
    FPGPASSWORD=`cat $FLIBUSTA_DBPASSWORD_FILE`
  fi

  if [ ! -z "$FLIBUSTA_DBPASSWORD" ] && [ -z "$FPGPASSWORD" ]; then
    FPGPASSWORD=$FLIBUSTA_DBPASSWORD
  fi

  if [ ! -z "$FPGPASSWORD" ]; then
    export PGPASSWORD=$FPGPASSWORD
  fi

  if [ -z "$PGPASSWORD" ]; then
    export PGPASSWORD=flibusta
  fi
fi
