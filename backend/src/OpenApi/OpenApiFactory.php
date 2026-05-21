<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: 'api_platform.openapi.factory')]
final readonly class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $errorsSchema = [
            'type' => 'object',
            'properties' => [
                'errors' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                    ],
                ],
            ],
        ];

        $openApi->getPaths()->addPath('/api/register', new Model\PathItem(
            post: new Model\Operation(
                operationId: 'auth_register_post',
                tags: ['Auth'],
                responses: [
                    '201' => [
                        'description' => 'User created — returns JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['token'],
                                    'properties' => ['token' => ['type' => 'string']],
                                ],
                            ],
                        ],
                    ],
                    '409' => [
                        'description' => 'Email already in use',
                        'content' => ['application/json' => ['schema' => $errorsSchema]],
                    ],
                    '422' => [
                        'description' => 'Validation failed',
                        'content' => ['application/json' => ['schema' => $errorsSchema]],
                    ],
                ],
                requestBody: new Model\RequestBody(
                    description: 'Registration data',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['email', 'password'],
                                'properties' => [
                                    'email' => ['type' => 'string', 'format' => 'email'],
                                    'password' => ['type' => 'string', 'minLength' => 8],
                                ],
                            ],
                        ],
                    ]),
                    required: true,
                ),
                security: [],
            ),
        ));

        $openApi->getPaths()->addPath('/api/me', new Model\PathItem(
            get: new Model\Operation(
                operationId: 'auth_me_get',
                tags: ['Auth'],
                responses: [
                    '200' => [
                        'description' => 'Current authenticated user',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'string'],
                                        'email' => ['type' => 'string', 'format' => 'email'],
                                        'roles' => [
                                            'type' => 'array',
                                            'items' => ['type' => 'string'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '401' => ['description' => 'Not authenticated'],
                ],
            ),
        ));

        return $openApi;
    }
}
