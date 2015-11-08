# FuelPHP ドキュメント翻訳のためのツール

## インストール

fuel-docs-tools を取得します。

```
$ git clone git://github.com/kenjis/fuel-docs-tools
$ cd fuel-docs-tools
```

FuelPHP の本家ドキュメント (英語) と日本語ドキュメントのリポジトリを取得し、ブランチを合わせます。フォルダ名は、以下のように fuel-docs および fuel-docs-nekoget としてください。

```
$ git clone git://github.com/fuel/docs.git fuel-docs
$ cd fuel-docs
$ git checkout 1.8/develop
$ cd ..

$ git clone git://github.com/NEKOGET/FuelPHP_docs_jp.git fuel-docs-nekoget
$ cd fuel-docs-nekoget
$ git checkout 1.8/develop_japanese
$ cd ..
```

## 使い方

日本語ドキュメントに、GitHub へのリンクを追加します。

```
$ php add-github-link.php <バージョン>

例
$ php add-github-link.php 1.8
```

行数が原文と一致しているかどうかチェックします。

```
$ php check.php line
```

翻訳の進捗状況を出力します。行数が原文と一致してる必要があります。

```
$ php check.php progress
```

原文と翻訳のコミット日付をチェックします。

```
$ php check.php date
```

## ライセンス

MIT License
