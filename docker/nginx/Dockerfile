FROM nginx:1.21-alpine

COPY nginx.conf  /etc/nginx/
COPY interview_host.conf /etc/nginx/sites-enabled/
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
EXPOSE 443