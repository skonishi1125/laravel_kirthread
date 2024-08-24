# laravel_kirthread
https://kir-thread.site/

色々弄って試すための掲示板プログラム。


## 環境

### 環境構築
下記を参照する。

https://github.com/skonishi1125/laravel_kirthread/wiki/%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E6%89%8B%E9%A0%86%E6%9B%B8

### 開発環境の動作確認済ハード
* MacBook Air (13-inch, 2020, intel) macOS Sonoma(v14.1)

* MacBook Pro (16-inch, 2019, intel) macOS Catalina(v10.15.7) - Monterey(v12.6.1)

* Windows 10 / 11 ※wsl2 ubuntu


### ツールのバージョン

24.8.24 にインフラ周りの更新作業を行なった。 Pull Request: https://github.com/skonishi1125/laravel_kirthread/pull/34

#### PHP
* PHP 8.2.21

* Laravel Framework 11.21.0

#### OS / 使用しているツールなど
* Amazon Linux 2023

* mysql  Ver 8.0.39 for Linux on x86_64 (MySQL Community Server - GPL)

* nginx version: nginx/1.24.0

* Composer version 2.7.8

* Docker (version 20.10.10あたりで動作確認)

* vscode

* TablePlus

* SourceTree


### その他
#### SSL証明書
* Let's Encryptを使用

#### ドメイン
* conoha -> 移管してAmazon Route53で管理中。

#### prod環境に関連するAWSのサービス
* Amazon VPC
* Amazon EC2
* Amazon Route53
* Amazon Billing and Cost Management











