#!/bin/bash
CURDIR=$(pwd);
TMPDIR=~/.plugin-release-script;

mkdir $TMPDIR;
cd $TMPDIR;
svn co https://plugins.svn.wordpress.org/$SLUG --depth=empty .;
svn up trunk
svn up tags --depth=empty
cd trunk;
rm -rf * .* *.*;
### Clone the repo at the specific version tag
git clone --branch $VERSION git@github.com:$SLUG.git --depth 1 .
### This is where you would run any build commands
svn propset svn:ignore -F .svnignore .;
cd ../;
svn st | grep ^! | awk '{print " --force "$2}' | xargs svn rm;
svn add --force .;
svn cp trunk tags/$VERSION;
svn ci -m "Tagging $VERSION from Github" --no-auth-cache --non-interactive --username "$SVN_USERNAME" --password "$SVN_PASSWORD";
cd $CURDIR;
rm -rf $TMPDIR;
echo 'Done!';
