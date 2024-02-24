FROM postgres:13.4
WORKDIR /docker-entrypoint-initdb.d
ENV POSTGRES_DB flibusta
COPY init_db.sql /docker-entrypoint-initdb.d/
