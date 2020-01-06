FROM tensorflow/tensorflow:1.15.0-gpu

RUN apt-get update && apt-get install python-pil python-numpy -y && \
    pip install jupyter && \
    pip install matplotlib && \
    pip install PrettyTable && \
    pip install tqdm

COPY ./segmentation /magic/segmentation

ENV PYTHONPATH="/magic/segmentation:/magic/segmentation/slim:${PYTHONPATH}"
ENV TENSOR_CMD_1="echo"

COPY ./server /magic/server

RUN chmod +x /magic/server/start_server.sh

# ENTRYPOINT ["/magic/server/start_server.sh"]