#!/bin/bash

composer install -n --no-dev --no-scripts --optimize-autoloader

npm install -D @babel/preset-react --force
npm install react-router-dom
npm install react react-dom prop-types axios --dev

npm run dev
