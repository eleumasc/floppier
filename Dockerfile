FROM mattrayner/lamp:latest-1804

USER root
COPY ./app /app
COPY ./supporting_files/run.sh /
COPY ./supporting_files/create_mysql_users.sh /
RUN chmod 750 /*.sh
COPY ./supporting_files/database.sql /
RUN chmod 750 /database.sql

EXPOSE 80

