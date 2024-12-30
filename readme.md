# 在线书店管理系统

## 项目简介

本项目是一个基于PHP和MySQL的在线书店管理系统,实现了图书销售、库存管理、订单处理等功能。系统分为前台用户界面和后台管理界面两部分。

## 技术栈

- 后端: PHP 8.2
- 数据库: MySQL 8.0
- 前端: Bootstrap 5.3, jQuery
- UI组件: Bootstrap Icons

## 系统功能

### 前台功能

1. 用户注册与登录
2. 图书浏览和搜索
3. 购物车管理
4. 订单管理
5. 个人中心

### 后台功能

1. 图书管理
   - 添加/编辑/删除图书
   - 图书分类管理
   - 库存管理
2. 订单管理
   - 订单列表查看
   - 订单状态更新
3. 用户管理
4. 销售统计

## 目录结构

```
/bookstore
├── /config
│   ├── config.php
│   ├── constants.php
│   └── db_connect.php
├── /includes
│   ├── admin_sidebar.php
│   ├── auth.php
│   ├── customer_sidebar.php
│   ├── Logger.php
│   ├── navbar.php
│   └── Validator.php
├── /cart
│   └── add.php
│   ├── checkout.php
│   ├── remove.php
│   ├── update_quantity.php
│   └── view.php
├── /pages
│   ├── /admin
│   │   ├── /books
│   │   ├── /orders
│   │   ├── /users
│   │   └── ...
│   ├── /user
│   │   ├── index.php
│   │   ├── order_detail.php
│   │   └── orders.php
│   └── ...
├── /uploads
│   └── (uploaded files)
├── /css
├── book.sql
├── index.php
└── README.md
```

## 安装说明

1. 环境要求

   - PHP >= 8.0
   - MySQL >= 8.0
   - Apache/Nginx
2. 安装步骤

   ```bash
   # 1. 克隆项目
   git clone https://github.com/aoxing5/online-bookstore.git

   # 2. 导入数据库
   mysql -u root -p book < book.sql

   # 3. 配置数据库连接
   # 修改 config/config.php 中的数据库配置信息
   ```
3. 配置Web服务器

   - 将网站根目录指向项目根目录
   - 确保uploads目录具有写入权限
4. 可以选择安装虚拟环境xampp一键部署

   - https://www.apachefriends.org/zh_cn/index.html

## 使用说明

### 前台访问

- 网址: `http://localhost/bookstore/`
- 默认用户: user/123123

### 后台访问

- 网址: `http://localhost/bookstore/`
- 默认管理员: root/123123

## 主要功能说明

### 1. 图书管理

- 支持图书信息的增删改查
- 支持图书封面上传
- 支持库存管理

### 2. 订单管理

- 订单状态流转:待付款->已付款->已发货->已完成
- 支持订单详情查看
- 支持订单状态更新

### 3. 用户管理

- 用户信息管理
- 用户权限控制

### 4. 统计分析

- 销售额统计
- 热销图书排行
- 库存预警

## 开发说明

### 数据库设计

- book: 图书信息表
- user: 用户表
- order: 订单表
- order_detail: 订单详情表
- cart: 购物车表
- category: 图书分类表
- stock_record: 库存记录表

### 关键文件说明

- config/config.php: 系统配置文件
- includes/auth.php: 权限验证
- includes/navbar.php: 导航栏组件
- cart/add.php: 购物车添加功能
- pages/admin/*: 后台管理功能

## 注意事项

1. 请确保uploads目录具有写入权限
2. 建议定期备份数据库
3. 生产环境部署时请修改默认密码

## 更新日志

- 2024-12-26: 初始版本发布
