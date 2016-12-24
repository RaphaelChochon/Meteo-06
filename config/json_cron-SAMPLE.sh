#!/bin/bash

# path to folder sql/
cd /var/www/html/meteo/sql/

# DO NOT MODIFY THIS
php makejson_48h.php
php makejson_indoor.php
