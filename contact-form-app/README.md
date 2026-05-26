#Project_Name
 contactform

#Summary
目的
 本プロジェクトを通して教材で学んだバックエンド技術を実践的にアウトプットし、復習箇所を洗い出すこと。

機能要件概要
 お問い合わせフォーム画面
 ・入力ページ
 ・確認ページ
 ・サンクスページ（送信完了メッセージの表示）

 登録、ログイン画面
 ・管理者登録ページ（新規登録）
 ・ログインページ
 ・ログアウトページ

 その他画面
 ・管理画面（お問い合わせ詳細、タグ編集ページに推移できる）
 ・お問い合わせ詳細ページ
 ・タグ編集ページ
 ・公開API

#ER図
![ER図](/er_diagram.png)

#Environment_Setup
 0.DockerDesktopを起動しておく
 1.Laravelプロジェクトを作成する（ver.10.xを指定）
  以下のコマンドを実行する。
  docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    composer create-project laravel/laravel:^10.0 contact-form-app

 2.Laravel sailをインストールする
   Laravel sailとは、Laravelが公式で用意しているDocker環境開発を簡単に使えるようにしたツールのこと。

   contact-form-appに移動し、Laravel sailをインストールする。
   //プロジェクトディレクトリに移動
   cd contact-form-app

   //Laravel sailをインストール
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    composer require laravel/sail --dev

    //sailの設定ファイルを生成
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    php artisan sail:install --with=mysql

    ※M1/M2/M3 Mac（Apple Silicon）をお使いの方
    Apple Silicon搭載のMacでは'sail up -d'実行時に以下のエラーが出ることがある。

    ・・・
    no matching manifest for linux/arm64/v8
    ・・・

    解決方法
    'compose.yaml'を開き、mysqlサービスに'platform:'linux/arm64/''を追加する
    mysql:
      image:'mysql/mysql-server:8.0'
      platform:'linux/arm64'  ←この行を追加

 3.'.env'ファイルを設定する
   .envファイルを開き、DBと接続情報が一致していることを確認する

   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=sail
   DB_PASSWORD=password

 4.sailを起動し、エイリアスを設定する
  //sailをバックグラウンドで起動する
  ./vendor/bin/sail up -d

  //以降 'sail コマンド'　で実行できるようにエイリアスを設定する
  echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc

  //再起動する
  exec $SHELL

 5.phpMyAdminを追加する
   compose.yamlファイルを開き、servicesの中にあるmysqlサービスの後に追加する
   ※必ず、縦のラインを揃えるようにする。phpmyadminはmysqlと同じ列に記載すること。

   mysql:
   ・・・
   phpmyadmin:
    image: 'phpmyadmin:latest'
    ports:
        - '${FORWARD_PHPMYADMIN_PORT:-8080}:80'
    environment:
        PMA_HOST: mysql
        PMA_USER: '${DB_USERNAME}'
        PMA_PASSWORD: '${DB_PASSWORD}'
    networks:
        - sail
    depends_on:
        - 

 6.アプリケーションキーを生成する
 .envファイルのAPP_KEYに暗号化キーを設定する
  sail artisan key:generate

 7.フロントエンドをセットアップする
   本プロジェクトでは、フロントエンドのスタイリングにTailwind CSSを使用する。
   //NPM依存パッケージのインストール
   ※sail npm installを実行する前に必ずsailコンテナが起動していることを確認する(sail upで起動できる)
   sail npm install

   //Tailwind CSSのインストール
   sail npm install -D tailwindcss@^3.4.0 postcss autoprefixer
   sail npm install alpinejs

   //設定ファイルの生成
   sail npx tailwindcss init -p

   //Tailwind CSSのテンプレートパス設定
   tailwind.config.js を開き、以下のように設定する。
/** @type {import("tailwindcss").Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

   //提供リポジトリのresourcesディレクトリと入れ替える
   以下のリポジトリをクローンし、resourcesディレクトリと丸ごと入れ替える。
   git clone https://github.com/coachtech-prepared-file/Preparedblade-ConfirmationTest-ContactForm.git

   入れ替え手順
   1．Finderでプロジェクトフォルダを開く
   open .
   2.プロジェクト内のresourcesフォルダを削除する
   3．クローンしたリポジトリないのresourcesフォルダをプロジェクト直下にコピーする。

   //Viteサーバーを起動する
   sail npm run dev


 8.DBのマイグレーションと初期データを投入する
  //テーブルを作成し、初期データを投入する
  sail artisan migrate --seed

  ※既存のDBを削除したい場合は以下のコマンドを実行する
  sail artisan migrate:fresh --seed

#Use_Technology
OS     : Windows 11 25H2
PHP    : 8.2
Larave : 10.x
DB     : MySQL8.0
Webサーバー   :Nginx
フロントエンド :Vite & Tailwind CSS^3.4.0
開発ツール
・Docker
・Laravel sail
・phpMyAdmin

#API_Endpoint
 GETメソッド /api/contacts　　　　　　　  お問い合わせ一覧ページを取得できる
 GETメソッド /api/contacts/{contacts}　  お問い合わせ詳細ページを取得できる　
 POSTメソッド /api/contacts　　　　　　   お問い合わせを送信できる
 PUTメソッド /api/contacts/{contacts}　　お問い合わせを更新できる
 DELETEメソッド /api/contacts/{contacts} お問い合わせを削除できる

#Development_Environment_URL
お問い合わせ入力ページ
 http::/localhost/contact
お問い合わせ確認ページ
 http::/localhost/contact/confirm
thanksページ
 http::/localhost/contact/thanks

管理画面
 http::/localhost/admin/
お問い合わせ詳細ページ
 http::/localhost/admin/contacts/{contact}
タグ編集ページ
 http::/localhost/admin/tags/{tags}/edit
ログイン画面
 http::/localhost/login
管理者登録画面
 http::/localhost/register

#Deveroper
 奈良 那々美 (Nara Nanami)