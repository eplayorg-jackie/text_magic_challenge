#!/bin/bash

composer install

curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install nodejs npm -y

npm install -D @babel/preset-react --force
npm install react-router-dom
npm install react react-dom prop-types axios --dev

npm install
npm run dev
