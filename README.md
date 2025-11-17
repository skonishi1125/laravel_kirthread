# laravel_kirthread
https://kir-thread.site/

色々弄って試すための掲示板プログラム（最近は触った技術を置く場所になっているのでややキメラ気味）。

## 環境

### 環境構築
下記を参照する。

https://github.com/skonishi1125/laravel_kirthread/wiki/%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E6%89%8B%E9%A0%86%E6%9B%B8

### 開発環境の動作確認済ハード
* MacBook Air (13-inch, 2020, intel) macOS Sonoma(v14.1)
* MacBook Pro (16-inch, 2019, intel) macOS Catalina(v10.15.7) - Monterey(v12.6.1)
* Windows 10 / 11 ※wsl2 ubuntu

### ツールのバージョン

24.8.24 にインフラ周りを刷新。 Pull Request: https://github.com/skonishi1125/laravel_kirthread/pull/34

#### PHP
* PHP v8.2.21
* Laravel Framework 11.21.0
* Composer version 2.7.8

#### Vue
* Vue.js v3.4
* Node.js v20.17.0 (npm導入用)
* npm v10.8.2
* Vite v5.4.2 (laravel-mixから移行)

#### 稼働環境
* Amazon Linux 2023
* MySQL  Ver 8.0.39 for Linux on x86_64 (MySQL Community Server - GPL)
* nginx version: nginx/1.24.0

### その他
#### SSL証明書
* Let's Encryptを使用

#### ドメイン
* ConoHa -> 移管してAmazon Route 53で管理中。

#### インフラ周り
* Amazon VPC
* Amazon EC2
* Amazon Route 53
* Amazon Billing and Cost Management

#### 開発に使用しているツール等
* Docker (version 20.10.10あたりで動作確認)
* VSCode
* TablePlus
* SourceTree
* Pint
  * https://github.com/skonishi1125/laravel_kirthread/pull/64
* Larastan (Level5で設定中)
  * https://github.com/skonishi1125/laravel_kirthread/pull/65





