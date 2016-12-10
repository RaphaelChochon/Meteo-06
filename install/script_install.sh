#!/bin/bash
cd ../config/
cp -iv config-SAMPLE.php config.php
cp -iv json_cron-SAMPLE.sh json_cron.sh
cp -iv web_analytics-SAMPLE.php web_analytics.php
cp -iv additional_menu-SAMPLE.php additional_menu.php
chmod +x json_cron.sh
