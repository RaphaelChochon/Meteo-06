#!/bin/bash
cd ../config/
cp -iv config-SAMPLE.php config.php
cp -iv json_cron-SAMPLE.sh json_cron.sh
cp -iv json_archives_cron-SAMPLE.sh json_archives_cron.sh
cp -iv web_analytics-SAMPLE.php web_analytics.php
cp -iv additional_menu-SAMPLE.php additional_menu.php
cp -iv res_sociaux-SAMPLE.php res_sociaux.php
cp -iv widget_vigi-SAMPLE.php widget_vigi.php
chmod +x json_cron.sh json_archives_cron.sh
cd ../img/
cp -iv logo-SAMPLE.jpg logo.jpg
