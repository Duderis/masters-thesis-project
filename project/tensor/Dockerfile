# FROM tensorflow/tensorflow:1.15.0-gpu 
FROM tensorflow/tensorflow:1.15.0-gpu-jupyter
RUN ln -fs /usr/share/zoneinfo/Europe/Vilnius /etc/localtime

RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install python-pil python-numpy python-tk -y && \
    pip install jupyter && \
    pip install matplotlib && \
    pip install PrettyTable && \
    pip install tqdm

COPY ./segmentation /magic/segmentation

ENV PYTHONPATH="/magic/segmentation:/magic/segmentation/slim:${PYTHONPATH}"
ENV TENSOR_CMD_1="echo"
ENV PYTHONWARNINGS="ignore::DeprecationWarning"

RUN mkdir -p /magic/segmentation/deeplab/datasets/SYS/dataset

RUN cd /magic/segmentation/deeplab/datasets/SYS/dataset && \
    ln -sf ../../../../../data/learning/segmentation/sets ./ImageSets && \
    ln -sf ../../../../../data/learning/segmentation/label ./JPEGImages && \
    ln -sf ../../../../../data/learning/segmentation/class ./SegmentationClass    
RUN cd /magic/segmentation/deeplab/datasets/SYS && \
    ln -sf ../../../../data/models ./models && \
    ln -sf ../../../../data/analyses ./analyses

COPY ./server /magic/server

RUN chmod +x /magic/server/start_server.sh

# ENTRYPOINT ["/magic/server/start_server.sh"]