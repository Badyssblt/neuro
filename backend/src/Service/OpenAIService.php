<?php

namespace App\Service;

use OpenAI;
use OpenAI\Client;

class OpenAIService
{
    private const MODEL = 'llama-3.3-70b-versatile';
    private const TEMPERATURE = 0.6;
    private const MAX_TOKENS = 10000;
    private const BASE_URI = 'api.groq.com/openai/v1';

    private Client $client;

    public function __construct()
    {
        $apiKey = $_ENV['AI_API_KEY'] ?? null;
        $this->client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withBaseUri(self::BASE_URI)
            ->make();
    }

    /**
     * Generate a full personalized learning plan from a free-form topic
     * (e.g. "je veux apprendre React"). Returns a nested array matching
     * the LearningGoal → LearningPath → Modules → Concepts → Lessons tree.
     */
    public function ask(string $topic): array
    {
        $response = $this->client->chat()->create([
            'model' => self::MODEL,
            'temperature' => self::TEMPERATURE,
            'max_tokens' => self::MAX_TOKENS,
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                ['role' => 'system', 'content' => $this->systemPrompt()],
                ['role' => 'user', 'content' => $this->userPrompt($topic)],
            ],
        ]);

        $raw = $response->choices[0]->message->content ?? '';
        $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

        return $this->normalize($decoded);
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
        Tu es un ARCHITECTE PÉDAGOGIQUE expert, spécialisé dans la conception de
        parcours d'apprentissage personnalisés. Tu maîtrises la taxonomie de Bloom,
        le scaffolding cognitif, la répétition espacée et l'apprentissage par projets.

        # MISSION
        À partir d'une demande libre de l'apprenant (ex. "je veux apprendre React"),
        tu produis un PLAN D'APPRENTISSAGE COMPLET, structuré et progressif, au
        format JSON STRICT décrit ci-dessous. Pas de texte hors JSON.

        # PRINCIPES PÉDAGOGIQUES
        1. Progression du concret vers l'abstrait, du simple vers le complexe.
        2. Chaque module construit sur les prérequis du précédent (scaffolding).
        3. Chaque concept est atomique : UNE idée enseignable en une session.
        4. Chaque leçon contient un exemple concret, exécutable ou observable.
        5. La difficulté croît graduellement (beginner → intermediate → advanced).
        6. Le score d'importance reflète l'impact réel sur la maîtrise du sujet.

        # CONTRAINTES DE VOLUME
        - 1 à 2 learning_paths (axes complémentaires sur le même sujet)
        - 2 à 3 modules par path
        - 2 concepts par module
        - 1 à 2 lessons par concept
        - Tout en français, sauf les termes techniques (ex. "useState", "Hook").
        - Le budget de sortie est SERRÉ (≈ 8000 tokens utiles). Reste sobre
          sur la structure pour donner de l'air au contenu des lessons.

        # ÉNUMÉRATIONS AUTORISÉES
        - level / difficulty : "beginner" | "intermediate" | "advanced"
        - importance_score : entier 1..10 (10 = fondamental)
        - estimated_duration (path)   : entier en HEURES totales
        - estimated_duration (module) : entier en HEURES
        - estimated_time (concept)    : entier en MINUTES
        - position : entier 1..N consécutif (ordre de progression dans le parent)

        # CONTENU DES LEÇONS
        Chaque leçon doit être SUBSTANTIELLE et auto-suffisante. C'est le cœur
        pédagogique : ne sois PAS télégraphique, développe vraiment.

        Chaque leçon doit avoir :
        - title    : titre clair et orienté action
        - content  : markdown structuré OBLIGATOIREMENT entre 400 et 700 mots.
                     Inclure :
                       * une intro qui pose le contexte et le "pourquoi"
                       * 2 à 4 sections `##` qui décomposent le sujet
                       * au moins un code block ou un exemple détaillé inline
                         si pertinent (n'hésite pas à mettre du code commenté)
                       * une transition / lien vers la leçon suivante en fin
                     Pas de bullet-list télégraphique seule : raisonne en
                     paragraphes denses, comme un cours de pro.
        - summary  : 1-2 phrases TL;DR
        - examples : un ou deux exemples concrets et développés (code complet
                     commenté, scénario réel, analogie filée) — pas une ligne.

        # FORMAT JSON DE SORTIE (RESPECTER LES NOMS EXACTS)
        {
          "title": "string",
          "description": "string (3-5 phrases, motivation + résultat attendu)",
          "level": "beginner|intermediate|advanced",
          "learning_paths": [
            {
              "title": "string",
              "description": "string",
              "estimated_duration": 40,
              "modules": [
                {
                  "title": "string",
                  "description": "string",
                  "position": 1,
                  "difficulty": "beginner|intermediate|advanced",
                  "estimated_duration": 4,
                  "concepts": [
                    {
                      "title": "string",
                      "description": "string",
                      "position": 1,
                      "difficulty": "beginner|intermediate|advanced",
                      "importance_score": 8,
                      "estimated_time": 30,
                      "lessons": [
                        {
                          "title": "string",
                          "position": 1,
                          "content": "markdown string",
                          "summary": "string",
                          "examples": "string"
                        }
                      ]
                    }
                  ]
                }
              ]
            }
          ]
        }

        # RÈGLES ABSOLUES
        - Réponds UNIQUEMENT avec un objet JSON valide. Aucun préambule, aucun
          commentaire, aucun bloc ```json.
        - Tous les champs listés sont OBLIGATOIRES.
        - Aucune valeur null ; pour un champ inconnu, génère une valeur cohérente.
        - Les `position` sont consécutives à chaque niveau (1, 2, 3, …).
        - La difficulté des lessons est implicite (héritée du concept parent).
        - Ne renvoie PAS de champ `status` : il est piloté côté serveur.
        - PRIORITÉ : la qualité et la longueur des `content` priment sur
          le nombre de leçons. Si tu dois choisir, fais moins de lessons mais
          plus développées plutôt qu'une avalanche de bouts de phrases.
        PROMPT;
    }

    private function userPrompt(string $topic): string
    {
        return sprintf(
            "Demande de l'apprenant :\n\"\"\"\n%s\n\"\"\"\n\n"
            . "Détecte le sujet, le niveau visé (par défaut beginner si non précisé) "
            . "et génère le plan d'apprentissage complet au format JSON spécifié.",
            trim($topic)
        );
    }

    private function normalize(array $decoded): array
    {
        return $decoded['learning_goal'] ?? $decoded;
    }
}
