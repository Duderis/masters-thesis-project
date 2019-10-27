FROM tensorflow/tensorflow:latest-gpu

COPY ./server /magic/server

RUN chmod +x /magic/server/start_server.sh

ENTRYPOINT ["/magic/server/start_server.sh"]