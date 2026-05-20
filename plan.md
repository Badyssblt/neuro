# 🧠 Synapse AI — Roadmap Complète

## Vision

Transformer des notes et ressources en :
- connaissances structurées
- apprentissage actif
- mémoire long terme
- assistant IA personnel

---

# 🏗️ Stack Technique

## Frontend
- Nuxt
- TailwindCSS
- Pinia

## BFF Layer
- Nuxt server routes

## Backend
- Symfony

## Async / Workers
- Symfony Messenger
- Redis

## Database
- PostgreSQL
- pgvector

## AI
- OpenAI API

---

# 🧱 Architecture Globale

```txt
Nuxt Frontend
      ↓
Nuxt BFF Layer
      ↓
Symfony API
      ↓
Messenger Queue
      ↓
Workers
      ↓
OpenAI API
      ↓
PostgreSQL + pgvector