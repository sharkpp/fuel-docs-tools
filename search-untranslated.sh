#!/bin/sh

# from http://www.sharkpp.net/blog/2015/11/03/git-memo-for-fuel-docs-trans.html

docs_ja="fuel-docs-nekoget/*"

grep -RE " [a-zA-Z]+\.\s*$" $docs_ja | grep -v "MIT license"
grep -RE " you " $docs_ja
 
