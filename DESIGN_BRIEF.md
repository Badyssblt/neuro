# Neuro — Brief design

## Pitch en une phrase
Une app d'apprentissage personnalisé où l'apprenant exprime une envie en langage naturel ("je veux apprendre React", "je veux comprendre la physique quantique") et reçoit instantanément un **parcours pédagogique complet**, structuré et progressif, généré par IA.

## Le problème résolu
Apprendre seul, c'est se perdre dans un océan de tutos sans fil rouge. Les MOOCs sont rigides, longs, et pas adaptés. Neuro génère **ton** parcours, à ton niveau, avec une vraie progression cognitive — pas une playlist YouTube.

## Public cible
- Autodidactes (devs, étudiants en reconversion, curieux)
- 18-40 ans, à l'aise avec le numérique
- Veulent du **structuré** sans la lourdeur d'une plateforme type Udemy
- Sensibles au design (ils ont essayé Notion, Linear, Arc, Raycast…)

## Concept clé
L'utilisateur tape **une intention** → l'IA produit un arbre vivant :
**Goal → Path → Modules → Concepts → Lessons**

C'est une carte mentale qu'on parcourt, pas une vidéo qu'on consomme.

## Modèle de données (à refléter dans l'UI)

```
LearningGoal         (titre, description, level, status)
 └─ LearningPath     (durée estimée totale en heures)
     └─ Modules[]    (position, difficulty, durée lisible "4h")
         └─ Concepts[] (difficulty, importance_score 1-10, temps en min)
             └─ Lessons[] (content markdown, summary, examples, difficulty)
```

Niveaux : `beginner` | `intermediate` | `advanced`.
Importance : 1 à 10 (à visualiser, c'est un signal clé).

## Écrans à designer (par ordre de priorité)

### 1. Landing / écran d'entrée
- **Le héros** : un grand champ de texte façon "What do you want to learn?" — c'est LE moment magique.
- Suggestions cliquables sous le champ ("React from scratch", "Comprendre les LLMs", "Cuisiner thaï")
- Minimal, ambitieux, qui donne envie de taper.

### 2. Génération en cours
- Animation pendant l'appel IA (15-40s typique).
- Idéalement on voit l'arbre se construire en stream (modules qui apparaissent un par un).
- Pas un spinner banal — quelque chose qui **raconte** ce qui se passe.

### 3. Vue du parcours (LearningGoal)
- Vue d'ensemble : titre, description, niveau, durée totale, progression.
- L'**arbre des modules** : timeline verticale, schéma en réseau, ou cartes empilées — surprends-nous.
- Indicateurs visuels de difficulty et de progression.
- Action principale : "Continuer où je m'étais arrêté".

### 4. Vue d'un module
- Liste des concepts avec leur `importance_score` (à visualiser : taille, couleur, pulsation ?).
- Position dans le module, durée estimée, statut (à faire / en cours / acquis).

### 5. Vue d'une leçon (la lecture)
- **C'est l'écran où l'utilisateur passe 80% du temps.**
- Contenu markdown lisible, typographie soignée, gestion du code (blocks colorés).
- Bloc `summary` mis en avant en tête.
- Bloc `examples` distinct, visuellement riche.
- Barre de progression dans le concept.
- Actions : "J'ai compris" / "Je veux un autre exemple" / "Plus simple, stp" (l'IA peut régénérer).

### 6. Dashboard utilisateur
- Liste des `LearningGoal` actifs et archivés.
- Stats légères : minutes apprises, streaks, prochain truc à faire.
- Pas de gamification lourde façon Duolingo — on est sur un produit pour adultes.

## Flow principal
1. **Onboarding minimal** (email + prénom, optionnel)
2. **Première intention** → génération → parcours créé
3. **Premier concept ouvert** automatiquement
4. **Boucle d'apprentissage** : leçon → "compris" → concept suivant
5. Retour au dashboard quand le module est terminé

## Ton & identité

### Vibe générale
- **Calme, dense, sérieux.** Pas une edtech colorée pour enfants.
- Quelque chose entre **Linear** (rigueur), **Arc browser** (audace) et **Readwise** (lecture longue).
- Le côté "neuro" évoque le cerveau et les connexions — à exploiter avec **subtilité**, pas avec des illustrations de cerveau.

### Palette suggérée (à challenger)
- Fond très sombre OU très clair (pas un mid-gray indécis)
- Une accent color forte et inattendue (vert acide ? violet électrique ? orange brûlé ?)
- Hiérarchie portée par la typographie, pas par 12 couleurs

### Typographie
- Sans-serif moderne pour l'UI (Inter, Geist, Söhne…)
- Possiblement un **serif** pour les leçons (style éditorial) — la lecture est sacrée
- Mono pour le code

### Composants singuliers à inventer
- **L'arbre du parcours** : une représentation du `LearningGoal` qui ne ressemble à rien d'autre. Mind map ? Galaxie ? Constellation ? Subway map ? Surprends.
- **L'indicateur d'importance** d'un concept (importance_score 1-10) — pas une barre triste.
- **L'animation de génération** — le moment où l'IA "réfléchit" doit être un spectacle, pas un load.

## Anti-patterns (à éviter absolument)
- ❌ Glassmorphism par défaut, gradients pastel "IA générique"
- ❌ Mascotte IA (genre robot souriant)
- ❌ Cards arrondies grises identiques partout
- ❌ Dark mode = juste inverser les couleurs
- ❌ Hero avec "AI-powered" en gros (on montre, on dit pas)
- ❌ Emojis en tant qu'icônes système
- ❌ Le terme "Lesson" / "Module" affiché tel quel — préférer des formulations humaines

## Stack technique (contexte d'implémentation)
- **Frontend** : Nuxt 3, Vue 3, TypeScript
- **API** : Symfony + API Platform (REST) en backend
- **Pas de framework UI imposé** — choix libre (UnoCSS, Tailwind, ou CSS natif moderne)

## Inspirations à étudier
- **Linear** : densité, contraste, micro-interactions
- **Arc** : audace graphique sans gimmick
- **Readwise / Reader** : lecture longue, typographie
- **Bear app** : minimalisme expressif
- **Khanmigo** (Khan Academy IA) : à voir pour les anti-patterns à éviter

## Ce qu'on attend du livrable
- Maquettes des 6 écrans listés ci-dessus
- 1 ou 2 propositions d'identité visuelle distinctes
- Un système de composants cohérent (boutons, inputs, cartes, états)
- Le **wow moment** : la visualisation du parcours d'apprentissage. C'est la signature visuelle de Neuro.
