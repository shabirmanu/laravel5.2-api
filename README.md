# laravel5.2-api

This repository contains basic code on how to build an API.

There are multiple apps who communicate with a centralized app. The centralized app is named UMS (User Management System), which is responsible of storing users data, roles and permissions and it is treated as server apps exposing api to client apps.

There are two client apps, larablog, a simple laravel blog and a tms, simple task management crud. Both of these apps register/login through the centralized ums app.
