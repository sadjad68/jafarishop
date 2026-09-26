# Project Rules

This document defines the **mandatory rules and standards** for the project.
You MUST follow these rules strictly.

---

## Naming Conventions

### Classes & Backend Folders

- Class names MUST use **PascalCase**
- Backend folder names MUST use **PascalCase**

Examples:
UserController
OrderService
CreateUserRequest

---

### Functions & Methods

- Function and method names MUST use **camelCase**

Examples:
getUserData
createOrder

---

### Variables

- Variable names MUST use **snake_case**

Examples:
$user_id
$total_price

---

### Database Naming

- Table names MUST be **plural**
- Table names MUST use **snake_case**
- Column names MUST use **snake_case**

Examples:
users
order_items
created_at

---

### Resources (Views)

- Resource folder names MUST use **kebab-case**
- Blade file names MUST use **kebab-case**

Examples:
resources/views/pages/home-page/
index.blade.php
header-section.blade.php

---

## Blade Rules

- Business logic MUST NOT be placed inside Blade files
- Blade files are for presentation only
- Any logic beyond simple conditions MUST be handled in Controllers or Services

❌ Logic-heavy Blade templates
❌ Service or database calls inside Blade

---

## Routing Rules

- Routes MUST be grouped using route groups
- Route structure MUST avoid duplication as much as possible
- All routes MUST be named
- Routes MUST be referenced ONLY by route name

❌ Direct URL usage
❌ Using `action()` or hardcoded paths
✅ Use `route('route.name')` only

---

## Controller Rules

- Controllers MUST NOT exceed **50 lines of code**
- Controllers MUST act only as coordinators
- Controllers MUST NOT contain:
  - Business logic
  - Validation logic

---

## Service Rules

- Services MUST NOT exceed **150 lines of code**
- Each service MUST have a single responsibility
- All business logic MUST live inside services

---

## Function Rules

- Functions MUST NOT exceed **25 lines of code**
- Large functions MUST be refactored
- Each function MUST do one thing only

---

## Validation Rules

- Validation MUST be handled using **Request Validation Classes**
- Validation MUST NOT be done in:
  - Controllers
  - Services
- Only validated data may reach Controllers, DTOs, and Services

❌ `$request->validate()` in controllers
❌ Manual validation inside services
✅ Dedicated Request Validation classes only

---

## General Rules

- Keep code simple, explicit, and readable
- Avoid duplication at all levels
- Follow clean code principles consistently

---

## Summary

- Consistent naming
- Clean routing
- Thin controllers
- Proper validation
