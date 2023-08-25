#!/usr/bin/env bash

### Options ###
while getopts d:g option
do
case "${option}"
in
d) PROJECTPATH=${OPTARG};;
t) DRYRUN=true;;
esac
done



### Project path? ###
if [ -z "$PROJECTPATH" ]; then
	echo -n "Enter the full path to your project's root directory: "
	read -e PROJECTPATH
fi

cd $PROJECTPATH



### Simple XML Site Map will screw everything up ###
enabled=$(drush pml | grep "simple_sitemap" | grep "Enabled    3")
if [ ! -z "$enabled" ]; then
  echo "The Simple XML sitemap must be upgraded *prior* to starting this process."
  echo "Not so simple after all..."
  echo "Please upgrade this module to version 4.x and then start the updater again."
  exit 1
fi



### Uninstall back_to_top temporarily
enabled=$(drush pml | grep "back_to_top" | grep "Enabled")
if [ ! -z "$enabled" ]; then
  echo "Temporarily uninstalling back_to_top."
  drush pmu back_to_top
  BTT=true 
fi



### Uninstall incompatible modules ###
declare -A incompatibles=(
  ["Block Blacklist"]="block_blacklist"
  ["Calendar"]="calendar"
  ["Calendar Datetime"]="calendar_datetime"
  ["CKEditor Entity Link"]="ckeditor_entity_link"
  ["Console"]="console"
  ["Content Type Clone"]="content_type_clone"
  ["Image Field Caption"]="image_field_caption"
  ["jQuery UI Effects"]="jquery_ui_effects"
  ["SEO Checklist"]="seo_checklist"
  ["Simple Timeline"]="simple_timeline"
  ["Superfish"]="superfish"
  ["Views Templates"]="views_templates"
  ["Checklist API"]="checklistapi"
)

for i in ${incompatibles[@]}; do
  enabled=$(drush pml | grep $i | grep "Enabled")
  if [ ! -z "$enabled" ]; then
    echo "Uninstalling incompatible module: $i"
    drush pmu $i
  fi
done

# Also uninstall GT_Footerdaemon if need be. We'll do this separately because we
# don't want composer to try removing it. 
enabled=$(drush pml | grep "gt_footerdaemon" | grep "Enabled")
if [ ! -z "$enabled" ]; then
  echo "Uninstalling incompatible module: gt_footerdaemon"
  drush pmu gt_footerdaemon
fi


### Remove composer lock file ###
if test -f "composer.lock"; then
  echo "Removing composer lock file."
  rm composer.lock
fi



### Remove incompatible modules from manifest ###
echo "Removing incompatible module entries from composer.json."
for i in ${incompatibles[@]}; do
  installed=$((composer show "drupal/$i") 2>&1)
  if [[ $installed != *"not found"* ]]; then
    echo "Removing $i"
    composer remove "drupal/$i" --no-update
  fi
done



### Update module constraints ###
echo "Updating module constraints for D10 compatibility."

declare -A upgrades=(
  ["drupal/back_to_top"]="3.0"
  ["drupal/better_exposed_filters"]="6.0"
  ["drupal/cas"]="2.0"
  ["drupal/captcha"]="2.0"
  ["drupal/colorbox"]="2.0"
  ["drupal/content_access"]="2.0@RC"
  ["drupal/core-composer-scaffold"]="10.0"
  ["drupal/core-project-message"]="10.0"
  ["drupal/core-recommended"]="10.0"
  ["drupal/custom_add_another"]="2.0@beta"
  ["drupal/devel"]="5.0"
  ["drupal/entity_type_clone"]="4.0"
  ["drupal/focal_point"]="2.0"
  ["drupal/imce"]="3.0"
  ["drupal/jquery_ui_accordion"]="2.0"
  ["drupal/jquery_ui_datepicker"]="2.0"
  ["drupal/jquery_ui_slider"]="2.0"
  ["drupal/module_filter"]="4.0"
  ["drupal/simple_sitemap"]="4.0"
  ["drupal/twig_vardumper"]="3.0"
  ["drupal/pathologic"]="2.0@alpha"
  ["drupal/video"]="3.0"
  ["drupal/views_block_filter_block"]="2.0"
  ["drupal/views_infinite_scroll"]="2.0"
  ["drush/drush"]="11.0"
  ["gt/gt_editor"]="dev-4.x-dev"
  ["gt/gt_profile"]="4.0"
  ["gt/gt_theme"]="4.0"
  ["gt/gt_tools"]="4.0"
  ["gt/hg_reader"]="4.0"
)

for u in ${!upgrades[@]}; do
  installed=$((composer show "$u") 2>&1)
  if [[ $installed != *"not found"* ]]; then
    echo "Updating $u"
    if [[ $u == *'gt_editor'* ]]; then
      composer require "$u: ${upgrades[$u]}" --no-update
    else
      composer require "$u:^${upgrades[$u]}" --no-update
    fi
  fi
done



### Adding core module replacements ###
echo "Adding replacements for deprecated core modules."

composer require 'drupal/ckeditor:^1.0' 'drupal/classy:^1.0' 'drupal/externalauth:^2.0' 'drupal/jquery_ui_slider:^2.0' 'drupal/quickedit:^1.0' 'drupal/rdf:^2.0' 'drupal/stable:^2.0' --no-update

### Install ###
echo "Installing Drupal 10 and dependencies."

if [ "$DRYRUN" = true ]; then
  installation=$(composer update --dry-run -W)
else
  installation=$(composer update -W)
fi
echo "COMPOSER RETURN CODE: $installation"
if [ "$installation" = 2 ]; then
  echo "As you can see, composer is not happy about something. Skipping database updates."
  exit 1
fi



### DB update ###
echo "Updating database."
drush updb



### Re-enable back_to_top if needed ###
if [ "$BTT" = true ]; then
  echo "Re-enabling back_to_top."
  drush en back_to_top
fi



### DONE ###
echo "Finished."

echo "Before proceeding, make sure ALL of the following modules are UNINSTALLED from your production site:"
delim=""
for item in "${!incompatibles[@]}"; do
  printf "%s" "$delim$item"
  delim=", "
done
echo

if [ "$BTT" = true ]; then
  echo "N.B.: You will need to uninstall Back to Top *before* uninstalling jQuery UI Effects. It can be re-enabled after the upgrade is complete and pushed to production."
fi
echo

echo "Once this is done, you may commit your files and run update.php. Most sites will WSOD until update.php is run; don't be frightened."
