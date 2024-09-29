FROM nginx
COPY default.conf /etc/nginx/conf.d/default.conf

RUN apt-get update \
  && apt-get install -y vim

# コンテナのキャッシュ削減のためのクリーンアップ（オプション）
RUN apt-get clean && rm -rf /var/lib/apt/lists/*