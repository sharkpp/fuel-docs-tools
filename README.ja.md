# FuelPHP ドキュメント翻訳のためのツール

## インストール

FuelPHP の本家ドキュメント (英語) と日本語ドキュメントのリポジトリを取得し、ブランチを合わせます。

```
$ git clone git://github.com/fuel/docs.git fuel-docs
$ cd fuel-docs
$ git checkout 1.5/develop
$ cd ..

$ git clone git://github.com/NEKOGET/FuelPHP_docs_jp.git fuel-docs-nekoget
$ cd fuel-docs-nekoget
$ git checkout 1.5/develop_japanese
$ cd ..
```

fuel-docs-tools を同じ階層に取得します。

```
$ git clone git://github.com/kenjis/fuel-docs-tools
$ cd fuel-docs-tools
```

## 使い方

行数が原文と一致しているかどうかチェックします。

```
$ php check.php line
```

翻訳の進捗状況を出力します。行数が原文と一致してる必要があります。

```
$ php check.php progress
```

## ライセンス

MIT License
