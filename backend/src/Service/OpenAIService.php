<?php

namespace App\Service;

use OpenAI;
use OpenAI\Client;

class OpenAIService
{
    private const MODEL = 'llama-3.3-70b-versatile';
    private const TEMPERATURE = 0.6;
    private const MAX_TOKENS = 8000;
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
        - 1 learning_path (principal)
        - 4 à 7 modules
        - 3 à 5 concepts par module
        - 2 à 4 lessons par concept
        - Tout en français, sauf les termes techniques (ex. "useState", "Hook").

        # ÉNUMÉRATIONS AUTORISÉES
        - level / difficulty : "beginner" | "intermediate" | "advanced"
        - status : toujours "draft"
        - importance_score : entier de 1 à 10 (10 = fondamental)
        - estimated_duration (path) : entier en HEURES totales
        - estimated_duration (module) : chaîne lisible (ex. "4h", "1 jour")
        - estimated_time (concept) : entier en MINUTES
        - position (module) : entier 1..N (ordre de progression)

        # CONTENU DES LEÇONS
        Chaque leçon doit avoir :
        - title : titre clair et orienté action
        - content : markdown structuré (200-500 mots) avec sections ##, code blocks
                    si pertinent, et explication pas-à-pas
        - summary : 1-2 phrases TL;DR
        - examples : un ou deux exemples concrets (code, schéma, analogie)
        - difficulty : aligné avec le concept parent

        # FORMAT JSON DE SORTIE (RESPECTER LES NOMS EXACTS)
        {
          "learning_goal": {
            "title": "string",
            "description": "string (3-5 phrases, motivation + résultat attendu)",
            "level": "beginner|intermediate|advanced",
            "status": "draft",
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
                    "estimated_duration": "string",
                    "concepts": [
                      {
                        "title": "string",
                        "description": "string",
                        "difficulty": "beginner|intermediate|advanced",
                        "importance_score": 8,
                        "estimated_time": 30,
                        "lessons": [
                          {
                            "title": "string",
                            "content": "markdown string",
                            "summary": "string",
                            "examples": "string",
                            "difficulty": "beginner|intermediate|advanced"
                          }
                        ]
                      }
                    ]
                  }
                ]
              }
            ]
          }
        }

        # RÈGLES ABSOLUES
        - Réponds UNIQUEMENT avec un objet JSON valide. Aucun préambule, aucun
          commentaire, aucun bloc ```json.
        - Tous les champs listés sont OBLIGATOIRES.
        - Aucune valeur null ; pour un champ inconnu, génère une valeur cohérente.
        - Les positions des modules sont consécutives (1, 2, 3, …).
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

    /**
     * Defensive normalization: ensure the expected top-level shape so the
     * caller can rely on $result['learning_goal']['learning_paths'][…].
     */
    private function normalize(array $decoded): array
    {
        if (isset($decoded['learning_goal'])) {
            return $decoded;
        }

        if (isset($decoded['title'], $decoded['learning_paths'])) {
            return ['learning_goal' => $decoded];
        }

        return $decoded;
    }
}
