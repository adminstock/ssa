#!/bin/bash

declare path="$1"
declare url="$2"
declare top_path="$(dirname "$path")"
declare defaultConfigPath="$(sudo mktemp --dry-run $top_path/ssa.config.XXXXX.backup)";
declare serversPath
declare currentVersion="$(cat $path/.version | grep -o '[0-9\.]\+' | head -n 1)"

if [[ "${path: -1}" == "/" ]]; then
  path="${path:0:${#path}-1}"
fi

if [[ -d "$path/servers" && "$(ls -1 $path/servers | wc -l)" != 0 ]]; then 
  serversPath="$(mktemp --directory $top_path/servers.XXXXX.backup)"
fi

if [[ -e "/tmp/ssa" ]]; then
  rm --force /tmp/ssa
fi

# export files
svn export $url /tmp/ssa || exit 1

# full backup
declare backupPath="/var/backups/ssa-webpanel-v$currentVersion.tar.gz"
if [[ -e "$backupPath" ]]; then
  backupPath="$(sudo mktemp --dry-run /var/backups/ssa-webpanel-v$currentVersion.XXXXX.tar.gz)"
fi
tar -zcf "$backupPath" "$path" || exit 1

# backup ssa.config.php
mv "$path/ssa.config.php" "$defaultConfigPath" || exit 1

# backup servers
if [[ -n "$serversPath" ]]; then
  for f in $path/servers/*.php; do
    mv "$f" "$serversPath/$(basename $f)"
  done
fi

# remove old version
rm -r "$path"

# set new version
mv "/tmp/ssa" "$path"

# remove unused files
rm --force -r "$path/Content/scss"
rm --force -r "$path/compilerconfig.json"
rm --force -r "$path/compilerconfig.json.defaults"
rm --force -r "$path/*.phpproj"

# restore ssa.config.php
mv --force "$defaultConfigPath" "$path/ssa.config.php"

# restore servers
if [[ -n "$serversPath" && -d "$serversPath" ]]; then
  if [[ ! -d "$path/servers" ]]; then
    mkdir "$path/servers"
  fi

  for f in $serversPath/*.php; do
    mv "$f" "$path/servers/$(basename $f)"
  done

  rm -r $serversPath
fi