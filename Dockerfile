FROM ci4-base:0.1.0
COPY ./ /var/www/project-root
WORKDIR /var/www/project-root/
EXPOSE 80
RUN chmod +x initproj.sh
RUN ./initproj.sh
ENTRYPOINT tail -f /dev/null 