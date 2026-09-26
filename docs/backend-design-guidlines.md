# Backend Design Guideline

## Purpose
This document defines **class design principles** for the project.
The goal is to keep the codebase **clean, testable, scalable, and maintainable**.

---

## Architecture Overview

- Controllers act as **thin interfaces only**
- Business logic lives in **Services**
- Data transfer is handled via **DTOs**
- Validation is handled via **Form Request classes**
- Models contain only **entity-specific logic**

---

## Controllers

### Responsibilities
- Receive HTTP requests
- Resolve dependencies via Dependency Injection
- Delegate actions to Services
- Return responses

### Rules
- Controllers MUST NOT contain business logic
- Controllers MUST NOT validate data directly
- Controllers MUST NOT exceed **50 lines of code**
- Controllers MUST use **Dependency Injection** for services

❌ No heavy logic  
❌ No validation logic  
✅ Only coordination and delegation

---

## Services

### Responsibilities
- Handle business logic
- Coordinate between models, repositories, and external services
- Execute application use-cases

### Rules
- Each service MUST represent a clear responsibility
- Services MUST NOT exceed **150 lines of code**
- Services SHOULD be stateless
- Cross-entity logic MUST live in services

❌ No fat services  
✅ One responsibility per service

---

## DTO (Data Transfer Objects)

### Purpose
- Encapsulate and transport data between layers
- Prevent passing raw request data into services

### Rules
- DTOs MUST be immutable
- DTOs MUST be created from validated data
- Services MUST accept DTOs instead of Request objects

---

## Validation

### Rules
- Validation MUST be done using **Form Request classes**
- Validation MUST NOT be done inside:
    - Controllers
    - Services
- Only validated data may reach DTOs

❌ Inline validation  
❌ `$request->validate()` inside controllers  
✅ Dedicated validation classes only

---

## Models

### Responsibilities
- Represent database entities
- Contain logic strictly related to the entity itself

### Rules
- Model logic MUST be entity-specific only
- Any logic involving another entity MUST move to a Service
- Models MUST remain thin

❌ Cross-entity logic in models  
❌ Business workflows in models

---

## Inheritance & Code Reuse

### Rules
- Avoid unnecessary inheritance
- Prefer **Composition** over Inheritance
- Use **Traits** for shared, reusable behavior

❌ Deep inheritance trees  
✅ Explicit composition  
✅ Focused traits

---

## Dependency Injection

### Rules
- All services MUST be injected via constructor
- No service instantiation using `new` keyword
- Rely on Laravel’s IoC container

❌ `new SomeService()`  
✅ Constructor injection only

---
## SOLID & Clean Code Principles

All backend code MUST follow these principles:

SRP (Single Responsibility Principle)
Each class MUST have only one clear responsibility.

OCP (Open/Closed Principle)
Classes MUST be open for extension and closed for modification.

DRY (Don’t Repeat Yourself)
Duplicate logic is strictly forbidden; shared behavior MUST be extracted.

---

## General Rules

- Keep classes small and focused
- Favor readability over cleverness
- Any logic harder than one glance belongs in a Service

---

## Summary

- Thin Controllers
- DTO-based data flow
- Service-oriented business logic
- Clean Models
- Strong validation boundaries
- Maintainable and scalable backend  
