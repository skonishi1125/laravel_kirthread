# laravel_kirthread
https://kir-thread.site/

色々弄って試すための掲示板サイト。  
触った技術を試し置きする場所としても使用中。

## 稼働環境・ツール

24.8.24 にインフラ周りを刷新。  
関連PR : https://github.com/skonishi1125/laravel_kirthread/pull/34

### アプリケーション
* PHP v8.2.21
* Laravel Framework 11.21.0
* Composer version 2.7.8
* Vue.js v3.4
* Vite v5.4.2 (laravel-mixから移行)
* Node.js v20.17.0 (npm導入用)
* npm v10.8.2

### インフラ
* Amazon Linux 2023
* MySQL Ver 8.0.39 for Linux on x86_64 (MySQL Community Server - GPL)
* nginx version: nginx/1.24.0

#### 使用サービス（AWS）
* Amazon VPC
* Amazon EC2
* Amazon Route 53 (ドメイン管理。ConoHaから移管)
* Amazon S3
* Amazon CloudWatch
* Amazon Data Firehose

#### その他
* SSL証明書: Let's Encrypt

---

## 開発環境

### Local 環境
Docker を使用中 (version 20.10.10あたりで動作確認)。詳細は下記を参照。  
https://github.com/skonishi1125/laravel_kirthread/wiki/%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E6%89%8B%E9%A0%86%E6%9B%B8

#### 動作確認済ハードウェア
Apple Silicon の Mac は未検証。
* MacBook Air (13-inch, 2020, intel) macOS Sonoma(v14.1)
* MacBook Pro (16-inch, 2019, intel) macOS Catalina(v10.15.7) - Monterey(v12.6.1)
* Windows 10 / 11 ※wsl2 ubuntu

### 開発ツール
* Visual Studio Code
* TablePlus
* SourceTree

### ライブラリ
静的解析ツールとして以下を使用。
* Pint
    * https://github.com/skonishi1125/laravel_kirthread/pull/64
* Larastan (Level5で設定中)
    * https://github.com/skonishi1125/laravel_kirthread/pull/65

---

## ゲームについて

https://github.com/user-attachments/assets/1794f4f1-28f8-4542-9ee8-fc96699af2cb

https://kir-thread.site/game/rpg

ブラウザゲーム「Epic Reckoning」を公開しています。

コマンド選択式のブラウザRPGです。

スマホでも動作しますが、PCの方が遊びやすいです。

個人情報が不要な、簡易登録機能を用意していますので是非お試しください！
