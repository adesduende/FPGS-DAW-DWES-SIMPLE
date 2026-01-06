FROM mysql:8.0

EXPOSE 3306

COPY ./db_init.sql /docker-entrypoint-initdb.d/01-init.sql
COPY ./db_seed.sql /docker-entrypoint-initdb.d/02-seed.sql

RUN chown -R mysql:mysql /docker-entrypoint-initdb.d/   &
RUN chmod -R 755 /docker-entrypoint-initdb.d/
CMD ["mysqld"]
